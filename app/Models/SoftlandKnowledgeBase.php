<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoftlandKnowledgeBase extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'softland_knowledge_base';

    protected $fillable = [
        'title',
        'file_name',
        'file_path',
        'file_url',
        'file_type',
        'file_size',
        'description',
        'content',
        'category',
        'version',
        'is_active',
        'last_indexed_at',
        'indexed_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_indexed_at' => 'datetime',
        'file_size' => 'integer',
    ];

    /**
     * Relación con el usuario que indexó el documento
     */
    public function indexedBy()
    {
        return $this->belongsTo(User::class, 'indexed_by');
    }

    /**
     * Scope para documentos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para búsqueda por categoría
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Buscar en título, descripción y contenido
     * Divide el término de búsqueda en palabras y busca cada una
     * Prioriza documentos que contienen más palabras de la búsqueda
     */
    public function scopeSearch($query, $search)
    {
        // Dividir la búsqueda en palabras (mínimo 3 caracteres)
        $words = array_filter(
            explode(' ', strtolower($search)),
            fn($word) => strlen($word) >= 3
        );

        if (empty($words)) {
            // Si no hay palabras válidas, buscar la frase completa
            return $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        // Construir SQL de scoring para ordenar por relevancia
        $scoreParts = [];
        foreach ($words as $word) {
            // Mayor peso si aparece en título (10 puntos)
            $scoreParts[] = "CASE WHEN LOWER(title) LIKE '%{$word}%' THEN 10 ELSE 0 END";
            // Peso medio si aparece en descripción (5 puntos)
            $scoreParts[] = "CASE WHEN LOWER(description) LIKE '%{$word}%' THEN 5 ELSE 0 END";
            // Menor peso si aparece en file_name (3 puntos)
            $scoreParts[] = "CASE WHEN LOWER(file_name) LIKE '%{$word}%' THEN 3 ELSE 0 END";
            // Mínimo peso si aparece en contenido (1 punto)
            $scoreParts[] = "CASE WHEN LOWER(content) LIKE '%{$word}%' THEN 1 ELSE 0 END";
        }

        $scoreSQL = '(' . implode(' + ', $scoreParts) . ') as relevance_score';

        // Buscar documentos que contengan AL MENOS UNA de las palabras clave
        return $query->selectRaw("*, {$scoreSQL}")
            ->where(function($q) use ($words) {
                foreach ($words as $word) {
                    $q->orWhere(function($subQ) use ($word) {
                        $subQ->whereRaw("LOWER(title) LIKE ?", ["%{$word}%"])
                             ->orWhereRaw("LOWER(description) LIKE ?", ["%{$word}%"])
                             ->orWhereRaw("LOWER(content) LIKE ?", ["%{$word}%"])
                             ->orWhereRaw("LOWER(file_name) LIKE ?", ["%{$word}%"]);
                    });
                }
            })
            ->orderByDesc('relevance_score');
    }

    /**
     * Obtener el tamaño del archivo formateado
     */
    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) {
            return 'Desconocido';
        }

        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Obtener icono según el tipo de archivo
     */
    public function getFileIconAttribute()
    {
        return match($this->file_type) {
            'pdf' => 'fas fa-file-pdf text-danger',
            'doc', 'docx' => 'fas fa-file-word text-primary',
            'xls', 'xlsx' => 'fas fa-file-excel text-success',
            'ppt', 'pptx' => 'fas fa-file-powerpoint text-warning',
            'txt' => 'fas fa-file-alt text-secondary',
            'mp4', 'avi', 'mov' => 'fas fa-file-video text-info',
            default => 'fas fa-file text-muted',
        };
    }

    /**
     * Obtener color del badge según categoría
     */
    public function getCategoryColorAttribute()
    {
        return match($this->category) {
            'manuales' => '#007bff',
            'guias' => '#17a2b8',
            'procedimientos' => '#ffc107',
            'tecnicos' => '#28a745',
            'faqs' => '#6c757d',
            'videos' => '#dc3545',
            'otros' => '#6f42c1',
            default => '#343a40',
        };
    }
}
