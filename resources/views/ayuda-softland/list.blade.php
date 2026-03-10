@extends('adminlte::page')

@section('title', 'Documentos - Ayuda Softland')

@section('content_header')
    <h1>
        <i class="fas fa-file-alt text-primary"></i>
        Gestión de Documentos
    </h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-2"></i>
                    Lista de Documentos Indexados
                </h3>
                <div class="card-tools">
                    <a href="{{ route('ayuda-softland.documents.create') }}"
                       class="btn btn-success btn-sm">
                        <i class="fas fa-plus-circle"></i> Subir Nuevo Documento
                    </a>
                    <a href="{{ route('ayuda-softland.index') }}"
                       class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i> Búsqueda con IA
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="documentsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Categoría</th>
                                <th>Tipo</th>
                                <th>Tamaño</th>
                                <th>Última Indexación</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTable se llenará via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $.noConflict();

    $('#documentsTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: '{{ route('ayuda-softland.documents') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'category', name: 'category' },
            { data: 'file_type', name: 'file_type' },
            { data: 'file_size', name: 'file_size' },
            { data: 'last_indexed_at', name: 'last_indexed_at' },
            { data: 'is_active', name: 'is_active' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[5, 'desc']]
    });
});
</script>
@stop
