@extends('adminlte::page')

@section('title', 'Ayuda Softland - IA')

@section('content_header')
    <h1><i class="fas fa-robot text-primary"></i> Ayuda Softland</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-brain mr-2"></i>
                    Asistente Inteligente con IA
                </h3>
                <div class="card-tools">
                    @can('ayuda-softland.upload')
                        <a href="{{ route('ayuda-softland.documents.create') }}"
                           class="btn btn-success btn-sm mr-2"
                           title="Subir nuevo documento">
                            <i class="fas fa-plus-circle"></i> Subir Documento
                        </a>
                    @endcan
                    <span class="badge badge-success">
                        <i class="fas fa-check-circle"></i> {{ $totalDocuments }} documentos indexados
                    </span>
                </div>
            </div>
            <div class="card-body">
                <!-- Descripción -->
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> ¿Cómo funciona?</h5>
                    <p class="mb-0">
                        Haz cualquier pregunta sobre Softland ERP y la inteligencia artificial buscará en toda nuestra base de conocimiento
                        para darte la mejor respuesta. Pregunta sobre procesos, configuraciones, problemas comunes o cualquier duda que tengas.
                    </p>
                </div>

                <!-- Zona de búsqueda -->
                <div class="search-container mb-4">
                    <form id="searchForm">
                        <div class="input-group input-group-lg">
                            <input type="text"
                                   class="form-control form-control-lg"
                                   id="searchQuery"
                                   placeholder="Escribe tu pregunta aquí... Ej: ¿Cómo crear una factura?"
                                   autocomplete="off"
                                   style="border-radius: 25px 0 0 25px; font-size: 16px;">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-lg"
                                        type="submit"
                                        id="searchBtn"
                                        style="border-radius: 0 25px 25px 0; min-width: 120px;">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>

                        <!-- Filtro por categoría (opcional) -->
                        <div class="mt-2">
                            <select class="form-control form-control-sm" id="categoryFilter" style="max-width: 300px;">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Indicador de carga -->
                <div id="loadingIndicator" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="sr-only">Buscando...</span>
                    </div>
                    <p class="mt-3 text-muted">
                        <i class="fas fa-robot mr-2"></i>La IA está analizando tu pregunta...
                    </p>
                </div>

                <!-- Área de resultados -->
                <div id="resultsArea" style="display: none;">
                    <div class="card card-widget">
                        <div class="card-header bg-gradient-primary">
                            <h3 class="card-title text-white">
                                <i class="fas fa-comment-dots mr-2"></i>Respuesta de la IA
                            </h3>
                        </div>
                        <div class="card-body">
                            <div id="aiAnswer" class="ai-response"></div>

                            <!-- Fuentes consultadas -->
                            <div id="sources" class="mt-4" style="display: none;">
                                <h5 class="text-muted">
                                    <i class="fas fa-book mr-2"></i>Fuentes consultadas:
                                </h5>
                                <div id="sourcesList" class="row mt-3"></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-sm btn-outline-primary" onclick="newSearch()">
                                <i class="fas fa-redo"></i> Nueva búsqueda
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sin resultados -->
                <div id="noResultsArea" class="alert alert-warning" style="display: none;">
                    <h5><i class="fas fa-exclamation-triangle mr-2"></i>Sin resultados</h5>
                    <p class="mb-0">No se encontró información relevante para tu consulta. Intenta reformular tu pregunta o explora los documentos disponibles.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Documentos recientes -->
@if($recentDocuments->count() > 0)
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-alt mr-2"></i>Documentos Recientes
                </h3>
                <div class="card-tools">
                    <a href="{{ route('ayuda-softland.documents') }}" class="btn btn-tool">
                        Ver todos <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <tbody>
                            @foreach($recentDocuments as $doc)
                            <tr>
                                <td style="width: 40px;" class="text-center">
                                    <i class="{{ $doc->file_icon }} fa-2x"></i>
                                </td>
                                <td>
                                    <strong>{{ $doc->title }}</strong>
                                    @if($doc->category)
                                        <br><small class="badge badge-{{ $doc->category_color }}">{{ $doc->category }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($doc->description)
                                        <small class="text-muted">{{ Str::limit($doc->description, 100) }}</small>
                                    @endif
                                </td>
                                <td style="width: 100px;" class="text-right">
                                    <small class="text-muted">{{ $doc->file_size_formatted }}</small>
                                </td>
                                <td style="width: 150px;" class="text-right">
                                    <a href="{{ route('ayuda-softland.documents.show', $doc->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@stop

@section('css')
<style>
    .ai-response {
        font-size: 15px;
        line-height: 1.8;
        color: #333;
    }

    .ai-response h1, .ai-response h2, .ai-response h3 {
        color: #007bff;
        margin-top: 20px;
        margin-bottom: 10px;
    }

    .ai-response p {
        margin-bottom: 15px;
    }

    .ai-response ul, .ai-response ol {
        margin-bottom: 15px;
        padding-left: 25px;
    }

    .ai-response code {
        background-color: #f4f4f4;
        padding: 2px 6px;
        border-radius: 3px;
        font-family: 'Courier New', monospace;
    }

    .ai-response pre {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border-left: 3px solid #007bff;
        overflow-x: auto;
    }

    .source-card {
        transition: transform 0.2s;
        cursor: pointer;
    }

    .source-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    #searchQuery:focus {
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        border-color: #007bff;
    }
</style>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    $.noConflict();

    const CSRF_TOKEN = '{{ csrf_token() }}';

    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        performSearch();
    });

    function performSearch() {
        const query = $('#searchQuery').val().trim();
        const category = $('#categoryFilter').val();

        if (query.length < 3) {
            Swal.fire({
                icon: 'warning',
                title: 'Consulta muy corta',
                text: 'Por favor escribe al menos 3 caracteres'
            });
            return;
        }

        // Mostrar loading
        $('#loadingIndicator').show();
        $('#resultsArea').hide();
        $('#noResultsArea').hide();
        $('#searchBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Buscando...');

        $.ajax({
            url: '{{ route("ayuda-softland.search") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data: {
                query: query,
                category: category
            },
            success: function(response) {
                $('#loadingIndicator').hide();
                $('#searchBtn').prop('disabled', false).html('<i class="fas fa-search"></i> Buscar');

                if (response.success && response.has_results) {
                    displayResults(response.answer, response.sources, response.zendesk_sources);
                } else {
                    $('#noResultsArea').show();
                }
            },
            error: function(xhr) {
                $('#loadingIndicator').hide();
                $('#searchBtn').prop('disabled', false).html('<i class="fas fa-search"></i> Buscar');

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Error al procesar la consulta'
                });
            }
        });
    }

    function displayResults(answer, sources, zendeskSources) {
        // Convertir markdown a HTML
        const htmlAnswer = marked.parse(answer);
        $('#aiAnswer').html(htmlAnswer);

        // Obtener el query actual para pasarlo a los documentos
        const currentQuery = $('#searchQuery').val();

        // Mostrar fuentes locales
        let hasAnySources = false;
        if (sources && sources.length > 0) {
            hasAnySources = true;
            let sourcesHtml = '<h6 class="mb-3"><i class="fas fa-database"></i> Documentos Locales</h6><div class="row">';
            sources.forEach(function(source) {
                const docUrl = `/ayuda-softland/documents/${source.id}?q=${encodeURIComponent(currentQuery)}`;
                sourcesHtml += `
                    <div class="col-md-4 mb-3">
                        <div class="card source-card" onclick="window.location.href='${docUrl}'">
                            <div class="card-body">
                                <div class="text-center mb-2">
                                    <i class="${source.file_icon} fa-3x text-primary"></i>
                                </div>
                                <h6 class="text-center mb-2">${source.title}</h6>
                                ${source.category ? '<span class="badge badge-primary d-block">' + source.category + '</span>' : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
            sourcesHtml += '</div>';
            $('#sourcesList').html(sourcesHtml);
        }

        // Mostrar fuentes de Zendesk
        if (zendeskSources && zendeskSources.length > 0) {
            hasAnySources = true;
            let zendeskHtml = '<h6 class="mb-3 mt-4"><i class="fas fa-external-link-alt"></i> Portal de Ayuda Softland</h6><div class="row">';
            zendeskSources.forEach(function(source) {
                zendeskHtml += `
                    <div class="col-md-4 mb-3">
                        <div class="card source-card" onclick="window.open('${source.url}', '_blank')">
                            <div class="card-body">
                                <div class="text-center mb-2">
                                    <i class="fas fa-globe fa-3x text-success"></i>
                                </div>
                                <h6 class="text-center mb-2">${source.title}</h6>
                                <span class="badge badge-success d-block">Portal Softland</span>
                            </div>
                        </div>
                    </div>
                `;
            });
            zendeskHtml += '</div>';
            $('#sourcesList').append(zendeskHtml);
        }

        if (hasAnySources) {
            $('#sources').show();
        }

        $('#resultsArea').show();

        // Scroll hacia los resultados
        $('html, body').animate({
            scrollTop: $('#resultsArea').offset().top - 100
        }, 500);
    }

    function newSearch() {
        $('#searchQuery').val('').focus();
        $('#resultsArea').hide();
        $('#noResultsArea').hide();
        $('html, body').animate({
            scrollTop: 0
        }, 500);
    }
</script>
@stop
