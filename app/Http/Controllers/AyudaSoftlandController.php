<?php

namespace App\Http\Controllers;

use App\Models\SoftlandKnowledgeBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use App\Traits\HasPermissionChecks;
use RealRashid\SweetAlert\Facades\Alert;

class AyudaSoftlandController extends Controller
{
    use HasPermissionChecks;
    /**
     * Mostrar la interfaz principal de búsqueda
     */
    public function index()
    {
        $categories = SoftlandKnowledgeBase::active()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        $recentDocuments = SoftlandKnowledgeBase::active()
            ->orderBy('last_indexed_at', 'desc')
            ->limit(5)
            ->get();

        $totalDocuments = SoftlandKnowledgeBase::active()->count();

        return view('ayuda-softland.index', compact('categories', 'recentDocuments', 'totalDocuments'));
    }

    /**
     * Buscar con inteligencia artificial (Claude)
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3|max:500',
            'category' => 'nullable|string'
        ]);

        $query = $request->input('query');
        $category = $request->input('category');

        try {
            // Buscar documentos relevantes en la base de conocimiento local
            $relevantDocs = $this->findRelevantDocuments($query, $category);

            // Buscar también en Zendesk de Softland
            $zendeskResults = $this->searchZendesk($query);

            // Si no hay resultados en ninguna fuente
            if ($relevantDocs->isEmpty() && empty($zendeskResults)) {
                return response()->json([
                    'success' => true,
                    'answer' => 'No encontré información relevante en la base de conocimiento ni en el portal de Softland sobre esa consulta. Por favor, intenta reformular tu pregunta o contacta al soporte técnico.',
                    'sources' => [],
                    'zendesk_sources' => [],
                    'has_results' => false
                ]);
            }

            // Preparar contexto para Claude (combinar ambas fuentes)
            $context = $this->prepareContext($relevantDocs);

            // Agregar resultados de Zendesk al contexto
            if (!empty($zendeskResults)) {
                $context .= "\n\n--- INFORMACIÓN ADICIONAL DE PORTAL SOFTLAND ---\n";
                foreach ($zendeskResults as $index => $zendeskResult) {
                    $num = $index + 1;
                    $context .= "\nDocumento {$num} (Portal Softland):\n";
                    $context .= "Título: {$zendeskResult['title']}\n";
                    $context .= "Fragmento: {$zendeskResult['snippet']}\n\n";
                }
            }

            // Llamar a Claude AI con el contexto combinado
            $answer = $this->queryClaudeAI($query, $context);

            // Preparar fuentes locales
            $sources = $relevantDocs->map(function($doc) {
                return [
                    'id' => $doc->id,
                    'title' => $doc->title,
                    'category' => $doc->category,
                    'file_type' => $doc->file_type,
                    'file_icon' => $doc->file_icon,
                    'source_type' => 'local',
                ];
            })->toArray();

            // Agregar fuentes de Zendesk
            $zendeskSources = array_map(function($result) {
                return [
                    'title' => $result['title'],
                    'url' => $result['url'],
                    'source_type' => 'zendesk',
                ];
            }, $zendeskResults);

            return response()->json([
                'success' => true,
                'answer' => $answer,
                'sources' => $sources,
                'zendesk_sources' => $zendeskSources,
                'has_results' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda con IA: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al procesar tu consulta. Por favor, intenta nuevamente.'
            ], 500);
        }
    }

    /**
     * Encontrar documentos relevantes
     */
    private function findRelevantDocuments($query, $category = null)
    {
        $docsQuery = SoftlandKnowledgeBase::active()
            ->search($query);

        if ($category) {
            $docsQuery->category($category);
        }

        // El scope search() ya incluye ORDER BY relevance_score
        // Solo limitamos los resultados
        return $docsQuery->limit(5)->get();
    }

    /**
     * Preparar contexto para Claude
     */
    private function prepareContext($documents)
    {
        $context = "Base de Conocimiento de Softland:\n\n";

        foreach ($documents as $doc) {
            $context .= "Documento: {$doc->title}\n";
            $context .= "Categoría: {$doc->category}\n";

            if ($doc->description) {
                $context .= "Descripción: {$doc->description}\n";
            }

            if ($doc->content) {
                // Limitar el contenido a 1000 caracteres por documento
                $content = substr($doc->content, 0, 1000);
                $context .= "Contenido: {$content}...\n";
            }

            $context .= "\n---\n\n";
        }

        return $context;
    }

    /**
     * Consultar a Claude AI
     */
    private function queryClaudeAI($question, $context)
    {
        $apiKey = config('services.anthropic.api_key');
        $model = config('services.anthropic.model');
        $maxTokens = config('services.anthropic.max_tokens');
        $apiVersion = config('services.anthropic.api_version');
        $endpoint = config('services.anthropic.endpoint');

        if (empty($apiKey)) {
            return "La integración con Claude AI no está configurada. Por favor, contacta al administrador del sistema.";
        }

        $systemPrompt = "Eres un asistente experto en Softland ERP. Tu función es ayudar a los usuarios a responder preguntas sobre el sistema basándote ÚNICAMENTE en la información de la base de conocimiento proporcionada (que incluye documentos locales y artículos del portal oficial de Softland). Si la información no está en el contexto, indícalo claramente. Responde en español de manera clara, profesional y concisa. Formatea tu respuesta usando markdown cuando sea apropiado. Si hay información de múltiples fuentes, integra las respuestas de forma coherente.";

        $userPrompt = "Contexto de la base de conocimiento:\n\n{$context}\n\nPregunta del usuario: {$question}\n\nPor favor, responde basándote en el contexto proporcionado.";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $apiKey,
                'anthropic-version' => $apiVersion
            ])->timeout(30)->post($endpoint, [
                'model' => $model,
                'max_tokens' => $maxTokens,
                'system' => $systemPrompt,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $userPrompt
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['content'][0]['text'] ?? 'No se pudo obtener una respuesta de la IA.';
            } else {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? 'Error desconocido';

                Log::error('Error en API de Claude: ' . $response->status() . ' - ' . $errorMessage);

                // Detectar error de créditos insuficientes
                if (str_contains($errorMessage, 'credit balance') || str_contains($errorMessage, 'too low')) {
                    return "⚠️ **Servicio de IA temporalmente no disponible**\n\n" .
                           "El servicio de inteligencia artificial requiere recarga de créditos. " .
                           "Mientras tanto, puedes:\n\n" .
                           "1. 📥 **Descargar el PDF original** usando el botón verde de arriba\n" .
                           "2. 👁️ **Ver los fragmentos relevantes** del documento haciendo clic en el botón gris\n" .
                           "3. 📄 Consultar directamente el manual descargado\n\n" .
                           "_El equipo técnico ya fue notificado para reactivar el servicio de IA._";
                }

                return "⚠️ **Error al comunicarse con la IA**\n\n" .
                       "Ocurrió un error al procesar tu consulta con el servicio de inteligencia artificial.\n\n" .
                       "**Alternativas:**\n" .
                       "- Descarga el PDF original usando el botón verde\n" .
                       "- Revisa los fragmentos de referencia haciendo clic en el botón gris\n\n" .
                       "_Detalles técnicos: {$errorMessage}_";
            }

        } catch (\Exception $e) {
            Log::error('Excepción al llamar a Claude: ' . $e->getMessage());
            return "⚠️ **Error al procesar la consulta**\n\n" .
                   "No se pudo conectar con el servicio de inteligencia artificial.\n\n" .
                   "**¿Qué puedes hacer?**\n" .
                   "1. Descarga el PDF original para consultarlo directamente\n" .
                   "2. Revisa los fragmentos de referencia del documento\n" .
                   "3. Intenta nuevamente en unos minutos\n\n" .
                   "_Si el problema persiste, contacta al equipo técnico._";
        }
    }

    /**
     * Buscar en Zendesk de Softland
     */
    private function searchZendesk($query)
    {
        try {
            $encodedQuery = urlencode($query);
            $zendeskUrl = "https://softland.zendesk.com/hc/es/search?utf8=%E2%9C%93&query={$encodedQuery}";

            Log::info("Buscando en Zendesk: {$zendeskUrl}");

            // Hacer petición HTTP a Zendesk
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ])
                ->get($zendeskUrl);

            if (!$response->successful()) {
                Log::warning('Error al consultar Zendesk: ' . $response->status());
                return [];
            }

            $html = $response->body();

            // Parsear HTML para extraer resultados
            $results = $this->parseZendeskResults($html);

            // Limitar a 3 resultados
            return array_slice($results, 0, 3);

        } catch (\Exception $e) {
            Log::error('Error al buscar en Zendesk: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Parsear resultados HTML de Zendesk
     */
    private function parseZendeskResults($html)
    {
        $results = [];

        try {
            // Usar DOMDocument para parsear HTML
            $dom = new \DOMDocument();
            @$dom->loadHTML($html); // @ para suprimir warnings de HTML mal formado

            $xpath = new \DOMXPath($dom);

            // Estructura real de Zendesk 2024+:
            // <ul class="search-results-list">
            //   <li>
            //     <article>
            //       <header>
            //         <h2 class="search-result-title">
            //           <a href="...">Título</a>
            //         </h2>
            //       </header>
            //       <p class="search-result-description">Descripción</p>
            //     </article>
            //   </li>
            // </ul>

            // Buscar todos los <article> dentro de la lista de resultados
            $articleNodes = $xpath->query("//ul[contains(@class, 'search-results-list')]//article");

            foreach ($articleNodes as $article) {
                // Buscar título y enlace
                $titleNode = $xpath->query(".//h2[contains(@class, 'search-result-title')]//a", $article);

                if ($titleNode->length > 0) {
                    $linkElement = $titleNode->item(0);
                    $title = trim($linkElement->textContent);
                    $url = $linkElement->getAttribute('href');

                    // Asegurar URL completa
                    if (!empty($url) && strpos($url, 'http') !== 0) {
                        $url = 'https://softland.zendesk.com' . $url;
                    }

                    // Buscar descripción/snippet
                    $snippetNode = $xpath->query(".//p[contains(@class, 'search-result-description')]", $article);
                    $snippet = '';
                    if ($snippetNode->length > 0) {
                        $snippet = trim($snippetNode->item(0)->textContent);
                        // Limpiar tags HTML del snippet
                        $snippet = strip_tags($snippet);
                    }

                    if (!empty($title) && !empty($url)) {
                        $results[] = [
                            'title' => $title,
                            'url' => $url,
                            'snippet' => $snippet ?: 'Artículo de ayuda de Softland',
                        ];
                    }
                }
            }

        } catch (\Exception $e) {
            Log::error('Error al parsear resultados de Zendesk: ' . $e->getMessage());
        }

        return $results;
    }

    /**
     * Listar todos los documentos
     */
    public function documents(Request $request)
    {
        $query = SoftlandKnowledgeBase::active();

        // Filtrar por categoría si se proporciona
        if ($request->has('category') && !empty($request->category)) {
            $query->category($request->category);
        }

        // Buscar si se proporciona término
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        $documents = $query->orderBy('last_indexed_at', 'desc')->paginate(20);

        $categories = SoftlandKnowledgeBase::active()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('ayuda-softland.documents', compact('documents', 'categories'));
    }

    /**
     * Mostrar un documento específico con respuesta de IA
     */
    public function show(Request $request, $id)
    {
        $document = SoftlandKnowledgeBase::active()->findOrFail($id);
        $searchQuery = $request->input('q', '');

        // Si hay query de búsqueda, generar respuesta con IA
        $aiResponse = null;
        $relevantFragments = [];

        if (!empty($searchQuery) && !empty($document->content)) {
            // Extraer fragmentos relevantes
            $relevantFragments = $this->extractRelevantFragments($document->content, $searchQuery, 8);

            // Preparar contexto para Claude AI
            $context = "Documento: {$document->title}\n";
            $context .= "Categoría: {$document->category}\n\n";

            if ($document->description) {
                $context .= "Descripción: {$document->description}\n\n";
            }

            $context .= "Fragmentos Relevantes del Documento:\n\n";
            foreach ($relevantFragments as $index => $fragment) {
                $num = $index + 1;
                // Limpiar las etiquetas HTML del fragmento para Claude
                $cleanText = strip_tags($fragment['text']);
                $context .= "--- Fragmento {$num} ---\n{$cleanText}\n\n";
            }

            // Generar respuesta con Claude AI
            $aiResponse = $this->queryClaudeAI($searchQuery, $context);
        }

        return view('ayuda-softland.show', compact('document', 'searchQuery', 'relevantFragments', 'aiResponse'));
    }

    /**
     * Extraer fragmentos relevantes del contenido basado en la búsqueda
     */
    private function extractRelevantFragments($content, $query, $maxFragments = 5)
    {
        // Dividir el query en palabras individuales (mínimo 3 caracteres)
        $words = array_filter(
            explode(' ', strtolower($query)),
            fn($word) => strlen($word) >= 3
        );

        if (empty($words)) {
            return [];
        }

        $fragments = [];
        $contentLower = strtolower($content);
        $fragmentSize = 400; // Caracteres antes y después de la palabra clave

        foreach ($words as $word) {
            // Buscar todas las posiciones donde aparece la palabra
            $position = 0;
            $foundCount = 0;

            while (($position = strpos($contentLower, $word, $position)) !== false && $foundCount < 2) {
                // Calcular inicio y fin del fragmento
                $start = max(0, $position - $fragmentSize);
                $end = min(strlen($content), $position + strlen($word) + $fragmentSize);

                // Extraer fragmento
                $fragment = substr($content, $start, $end - $start);

                // Agregar puntos suspensivos si no está al inicio/final
                if ($start > 0) {
                    $fragment = '...' . $fragment;
                }
                if ($end < strlen($content)) {
                    $fragment = $fragment . '...';
                }

                // Resaltar las palabras clave en el fragmento
                foreach ($words as $highlightWord) {
                    $fragment = preg_replace(
                        '/(' . preg_quote($highlightWord, '/') . ')/i',
                        '<mark class="bg-warning">$1</mark>',
                        $fragment
                    );
                }

                $fragments[] = [
                    'text' => $fragment,
                    'keyword' => $word,
                    'position' => $position
                ];

                $position += strlen($word);
                $foundCount++;
            }
        }

        // Ordenar por posición y limitar cantidad
        usort($fragments, fn($a, $b) => $a['position'] <=> $b['position']);

        // Eliminar duplicados muy cercanos
        $uniqueFragments = [];
        $lastPosition = -1000;

        foreach ($fragments as $fragment) {
            if ($fragment['position'] - $lastPosition > 200) {
                $uniqueFragments[] = $fragment;
                $lastPosition = $fragment['position'];
            }
        }

        return array_slice($uniqueFragments, 0, $maxFragments);
    }

    /**
     * Descargar un documento
     */
    public function download($id)
    {
        // Verificar permiso para descargar documentos
        if (!$this->checkPermission('ayuda-softland.download')) {
            Alert::error('Acceso Denegado', 'No tienes permisos para descargar documentos.');
            return redirect()->route('ayuda-softland.index');
        }

        $document = SoftlandKnowledgeBase::active()->findOrFail($id);

        // Verificar que el archivo existe
        if (!file_exists($document->file_path)) {
            abort(404, 'El archivo no se encuentra disponible.');
        }

        // Descargar el archivo
        return response()->download($document->file_path, $document->file_name);
    }

    /**
     * Mostrar formulario para subir nuevos documentos
     */
    public function create()
    {
        // Verificar permiso para subir documentos
        if (!$this->checkPermission('ayuda-softland.upload')) {
            Alert::error('Acceso Denegado', 'No tienes permisos para subir documentos.');
            return redirect()->route('ayuda-softland.index');
        }

        $categories = SoftlandKnowledgeBase::active()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('ayuda-softland.create', compact('categories'));
    }

    /**
     * Almacenar un nuevo documento y auto-indexarlo
     */
    public function store(Request $request)
    {
        // Verificar permiso para subir documentos
        if (!$this->checkPermission('ayuda-softland.upload')) {
            Alert::error('Acceso Denegado', 'No tienes permisos para subir documentos.');
            return redirect()->route('ayuda-softland.index');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:500',
            'file' => 'required|file|mimes:pdf|max:51200', // máximo 50MB
            'category' => 'required|string|max:100',
            'new_category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'version' => 'nullable|string|max:20',
        ]);

        try {
            // Determinar la categoría (nueva o existente)
            $category = $request->filled('new_category')
                ? $request->input('new_category')
                : $request->input('category');

            // Obtener el archivo
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            // Generar nombre único para el archivo
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $uniqueName = $fileName . '_' . time() . '.' . $extension;

            // Guardar archivo en storage/manuales/
            $destinationPath = storage_path('manuales');

            // Crear directorio si no existe
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $filePath = $destinationPath . '/' . $uniqueName;
            $file->move($destinationPath, $uniqueName);

            // Extraer contenido del PDF automáticamente
            $content = '';
            $indexError = null;

            try {
                $parser = new Parser();
                $pdf = $parser->parseFile($filePath);
                $text = $pdf->getText();

                // Limpiar y normalizar el texto
                $content = trim($text);
                $content = preg_replace('/\s+/', ' ', $content);

                if (strlen($content) < 50) {
                    $indexError = 'El PDF parece contener principalmente imágenes. Se indexó pero con poco texto extraído.';
                }
            } catch (\Exception $e) {
                $indexError = 'No se pudo extraer el texto del PDF automáticamente: ' . $e->getMessage();
                $content = '[Este documento requiere instalación de herramientas adicionales para extraer su contenido]';
            }

            // Crear registro en la base de datos
            $document = SoftlandKnowledgeBase::create([
                'title' => $validatedData['title'],
                'file_name' => $originalName,
                'file_path' => $filePath,
                'file_url' => null,
                'file_type' => 'pdf',
                'file_size' => $fileSize,
                'description' => $validatedData['description'],
                'content' => $content,
                'category' => $category,
                'version' => $validatedData['version'],
                'is_active' => true,
                'last_indexed_at' => now(),
                'indexed_by' => Auth::id(),
            ]);

            $message = '✅ Documento subido e indexado correctamente';

            if ($indexError) {
                $message .= ' (con advertencias: ' . $indexError . ')';
            }

            $message .= sprintf(
                '. Se extrajeron %s caracteres de contenido.',
                number_format(strlen($content))
            );

            return redirect()
                ->route('ayuda-softland.documents.show', $document->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error al subir documento: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Error al subir el documento: ' . $e->getMessage());
        }
    }
}
