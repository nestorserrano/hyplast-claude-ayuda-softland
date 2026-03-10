@extends('adminlte::page')

@section('title', 'Documentos - Ayuda Softland')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-folder-open"></i> Base de Conocimiento - Todos los Documentos</h1>
        <a href="{{ route('ayuda-softland.index') }}" class="btn btn-primary">
            <i class="fas fa-search"></i> Buscar con IA
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Filtros -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('ayuda-softland.documents') }}" method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <label for="search" class="mr-2">Buscar:</label>
                        <input type="text"
                               name="search"
                               id="search"
                               class="form-control"
                               placeholder="Título o contenido..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="form-group mr-3">
                        <label for="category" class="mr-2">Categoría:</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">Todas las categorías</option>
                            <option value="manuales" {{ request('category') == 'manuales' ? 'selected' : '' }}>Manuales</option>
                            <option value="guias" {{ request('category') == 'guias' ? 'selected' : '' }}>Guías</option>
                            <option value="procedimientos" {{ request('category') == 'procedimientos' ? 'selected' : '' }}>Procedimientos</option>
                            <option value="tecnicos" {{ request('category') == 'tecnicos' ? 'selected' : '' }}>Técnicos</option>
                            <option value="faqs" {{ request('category') == 'faqs' ? 'selected' : '' }}>FAQs</option>
                            <option value="videos" {{ request('category') == 'videos' ? 'selected' : '' }}>Videos</option>
                            <option value="otros" {{ request('category') == 'otros' ? 'selected' : '' }}>Otros</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>

                    <a href="{{ route('ayuda-softland.documents') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Limpiar
                    </a>
                </form>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $documents->total() }}</h3>
                        <p>Documentos Totales</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Documentos -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list"></i>
                    Documentos Disponibles
                    @if(request('search') || request('category'))
                        <span class="badge badge-info">Filtrado</span>
                    @endif
                </h3>
            </div>
            <div class="card-body p-0">
                @if($documents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="50"><i class="fas fa-file"></i></th>
                                <th>Título</th>
                                <th width="120">Categoría</th>
                                <th width="100">Tipo</th>
                                <th width="100">Tamaño</th>
                                <th width="120">Última Act.</th>
                                <th width="120">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                            <tr>
                                <td class="text-center">
                                    <i class="{{ $document->file_icon }} fa-2x" style="color: {{ $document->category_color }}"></i>
                                </td>
                                <td>
                                    <strong>{{ $document->title }}</strong>
                                    @if($document->description)
                                    <br>
                                    <small class="text-muted">{{ Str::limit($document->description, 100) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-pill" style="background-color: {{ $document->category_color }}">
                                        {{ ucfirst($document->category) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <strong>{{ strtoupper($document->file_type) }}</strong>
                                </td>
                                <td class="text-right">
                                    {{ number_format($document->file_size / 1024, 2) }} KB
                                </td>
                                <td>
                                    {{ $document->updated_at->format('d/m/Y') }}
                                    <br>
                                    <small class="text-muted">{{ $document->updated_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('ayuda-softland.documents.show', $document->id) }}"
                                           class="btn btn-info"
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($document->file_url || ($document->file_path && file_exists($document->file_path)))
                                        <a href="{{ route('ayuda-softland.documents.download', $document->id) }}"
                                           class="btn btn-success"
                                           title="Descargar"
                                           target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4 text-center">
                    <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                    <h4>No se encontraron documentos</h4>
                    <p class="text-muted">
                        @if(request('search') || request('category'))
                            Intenta ajustar los filtros de búsqueda.
                        @else
                            No hay documentos indexados en la base de conocimiento.
                        @endif
                    </p>
                </div>
                @endif
            </div>
            @if($documents->count() > 0)
            <div class="card-footer">
                {{ $documents->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .table td {
        vertical-align: middle;
    }

    .small-box {
        border-radius: 0.25rem;
    }
</style>
@stop
