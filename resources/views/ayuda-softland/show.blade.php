@extends('adminlte::page')

@section('title', $document->title . ' - Ayuda Softland')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="{{ $document->file_icon }}"></i>
            {{ $document->title }}
        </h1>
        <a href="{{ route('ayuda-softland.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Búsqueda
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <!-- Información del Documento -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Información del Documento</h3>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-5">Categoría:</dt>
                    <dd class="col-sm-7">
                        <span class="badge" style="background-color: {{ $document->category_color }}">
                            {{ ucfirst($document->category) }}
                        </span>
                    </dd>

                    <dt class="col-sm-5">Tipo de archivo:</dt>
                    <dd class="col-sm-7">
                        <i class="{{ $document->file_icon }}"></i>
                        {{ strtoupper($document->file_type) }}
                    </dd>

                    <dt class="col-sm-5">Tamaño:</dt>
                    <dd class="col-sm-7">{{ number_format($document->file_size / 1024, 2) }} KB</dd>

                    <dt class="col-sm-5">Versión:</dt>
                    <dd class="col-sm-7">{{ $document->version ?? 'N/A' }}</dd>

                    <dt class="col-sm-5">Última actualización:</dt>
                    <dd class="col-sm-7">{{ $document->updated_at->format('d/m/Y H:i') }}</dd>

                    @if($document->indexedBy)
                    <dt class="col-sm-5">Indexado por:</dt>
                    <dd class="col-sm-7">{{ $document->indexedBy->name }}</dd>
                    @endif

                    @if($document->last_indexed_at)
                    <dt class="col-sm-5">Última indexación:</dt>
                    <dd class="col-sm-7">{{ $document->last_indexed_at->diffForHumans() }}</dd>
                    @endif
                </dl>

                @can('ayuda-softland.download')
                    @if($document->file_url)
                    <a href="{{ route('ayuda-softland.documents.download', $document->id) }}"
                       class="btn btn-success btn-block"
                       target="_blank">
                        <i class="fas fa-download"></i> Descargar Documento
                    </a>
                    @endif

                    @if($document->file_path && file_exists($document->file_path))
                    <a href="{{ route('ayuda-softland.documents.download', $document->id) }}"
                       class="btn btn-info btn-block mt-2"
                       target="_blank">
                        <i class="fas fa-external-link-alt"></i> Abrir Documento
                    </a>
                    @endif
                @endcan
            </div>
        </div>

        @if($document->tags)
        <div class="card mt-3">
            <div class="card-header bg-info">
                <h3 class="card-title"><i class="fas fa-tags"></i> Etiquetas</h3>
            </div>
            <div class="card-body">
                @php
                    $tags = is_string($document->tags) ? json_decode($document->tags, true) : $document->tags;
                @endphp
                @if($tags && is_array($tags))
                    @foreach($tags as $tag)
                        <span class="badge badge-info mr-1 mb-1">{{ $tag }}</span>
                    @endforeach
                @else
                    <span class="text-muted">Sin etiquetas</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Contenido del Documento -->
    <div class="col-md-8">
        @if(!empty($searchQuery) && !empty($aiResponse))
            {{-- Respuesta de la IA sobre el documento --}}
            <div class="card card-primary card-outline">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title text-white">
                        <i class="fas fa-robot mr-2"></i>Respuesta de la IA
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-light">
                            <i class="fas fa-search"></i> "{{ $searchQuery }}"
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="ai-response" id="aiResponseContent">
                        {!! $aiResponse !!}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        @can('ayuda-softland.download')
                        <div class="col-md-6">
                            <a href="{{ route('ayuda-softland.documents.download', $document->id) }}"
                               class="btn btn-success btn-block"
                               target="_blank">
                                <i class="fas fa-download"></i> Descargar PDF Original
                            </a>
                        </div>
                        @endcan
                        <div class="{{ Auth::user()->can('ayuda-softland.download') ? 'col-md-6' : 'col-md-12' }}">
                            <button class="btn btn-outline-secondary btn-block"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#fragmentsCollapse">
                                <i class="fas fa-eye"></i> Ver Fragmentos de Referencia
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fragmentos colapsados (para referencia opcional) --}}
            @if(!empty($relevantFragments))
            <div class="collapse mt-3" id="fragmentsCollapse">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title">
                            <i class="fas fa-file-alt"></i> Fragmentos de Referencia del Documento
                        </h3>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle"></i>
                            Estos son los fragmentos del documento que la IA utilizó para generar su respuesta.
                        </div>

                        @foreach($relevantFragments as $index => $fragment)
                            <div class="fragment-container mb-3 p-3" style="background-color: #f8f9fa; border-left: 4px solid #6c757d; border-radius: 4px;">
                                <div class="mb-2">
                                    <span class="badge badge-secondary">Fragmento {{ $index + 1 }}</span>
                                    <small class="text-muted ml-2">
                                        <i class="fas fa-key"></i> Palabra clave: <strong>{{ $fragment['keyword'] }}</strong>
                                    </small>
                                </div>
                                <div style="white-space: pre-wrap; font-size: 0.9rem; line-height: 1.5;">
                                    {!! $fragment['text'] !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @else
            {{-- Vista cuando no hay búsqueda: mostrar descripción y vista previa --}}
            @if($document->description)
            <div class="card">
                <div class="card-header bg-secondary">
                    <h3 class="card-title"><i class="fas fa-align-left"></i> Descripción</h3>
                </div>
                <div class="card-body">
                    <p>{{ $document->description }}</p>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt"></i> Vista Previa del Contenido
                    </h3>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @if($document->content)
                        <div style="white-space: pre-wrap; font-family: monospace; font-size: 0.9rem;">{{ Str::limit($document->content, 5000) }}</div>
                        @if(strlen($document->content) > 5000)
                            <div class="text-center mt-3">
                                <small class="text-muted">Mostrando primeros 5000 caracteres de {{ number_format(strlen($document->content)) }} totales</small>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            No hay contenido indexado para este documento.
                        </div>
                    @endif
                </div>
                @can('ayuda-softland.download')
                <div class="card-footer text-center">
                    <a href="{{ route('ayuda-softland.documents.download', $document->id) }}"
                       class="btn btn-primary btn-lg"
                       target="_blank">
                        <i class="fas fa-file-pdf"></i> Ver PDF Completo
                    </a>
                </div>
                @endcan
            </div>
        @endif

        <!-- Hacer otra pregunta -->
        <div class="card mt-3">
            <div class="card-header bg-warning">
                <h3 class="card-title">
                    <i class="fas fa-question-circle"></i>
                    @if(!empty($aiResponse))
                        ¿Otra pregunta sobre este documento?
                    @else
                        Preguntas que puedes hacer sobre este documento
                    @endif
                </h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">
                    @if(!empty($aiResponse))
                        Puedes hacer otra pregunta diferente sobre este documento:
                    @else
                        Puedes hacer preguntas como:
                    @endif
                </p>
                <ul>
                    <li>"¿De qué trata {{ strtolower($document->title) }}?"</li>
                    <li>"Explícame el contenido de este manual"</li>
                    <li>"¿Qué pasos describe este documento?"</li>
                    <li>"Resume la información de {{ strtolower($document->title) }}"</li>
                </ul>
                <a href="{{ route('ayuda-softland.index') }}" class="btn btn-warning">
                    <i class="fas fa-search"></i> Hacer una Pregunta
                </a>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .card-header h3 {
        margin-bottom: 0;
    }

    dl dt {
        font-weight: 600;
    }

    dl dd {
        margin-bottom: 0.5rem;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.35rem 0.65rem;
    }

    .fragment-container {
        animation: fadeInUp 0.3s ease-in-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    mark.bg-warning {
        font-weight: bold;
        padding: 2px 4px;
        border-radius: 3px;
        background-color: #fff3cd !important;
        color: #856404;
    }

    .fragment-container:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* Estilos para respuesta de IA con markdown */
    .ai-response {
        font-size: 1rem;
        line-height: 1.8;
        color: #333;
    }

    .ai-response h1, .ai-response h2, .ai-response h3 {
        color: #007bff;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }

    .ai-response h1 { font-size: 1.75rem; }
    .ai-response h2 { font-size: 1.5rem; }
    .ai-response h3 { font-size: 1.25rem; }

    .ai-response p {
        margin-bottom: 1rem;
    }

    .ai-response ul, .ai-response ol {
        margin-bottom: 1rem;
        padding-left: 2rem;
    }

    .ai-response li {
        margin-bottom: 0.5rem;
    }

    .ai-response code {
        background-color: #f8f9fa;
        padding: 2px 6px;
        border-radius: 3px;
        font-family: 'Courier New', monospace;
        color: #e83e8c;
    }

    .ai-response pre {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border-left: 3px solid #007bff;
        overflow-x: auto;
    }

    .ai-response blockquote {
        border-left: 4px solid #ddd;
        padding-left: 1rem;
        margin: 1rem 0;
        color: #666;
    }

    .ai-response strong {
        font-weight: 600;
        color: #000;
    }

    .ai-response em {
        font-style: italic;
    }
</style>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    $.noConflict();

    // Convertir markdown a HTML si es necesario
    $(document).ready(function() {
        const aiContent = $('#aiResponseContent');
        if (aiContent.length && aiContent.text().trim().length > 0) {
            // Si el contenido tiene markdown, convertirlo
            const rawContent = aiContent.html();
            if (!rawContent.includes('<p>') && !rawContent.includes('<h1>')) {
                // No tiene HTML, probablemente es markdown
                const htmlContent = marked.parse(rawContent);
                aiContent.html(htmlContent);
            }
        }
    });
</script>
@stop
