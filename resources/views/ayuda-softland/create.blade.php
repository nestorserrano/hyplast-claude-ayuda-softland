@extends('adminlte::page')

@section('title', 'Subir Nuevo Documento - Ayuda Softland')

@section('content_header')
    <h1>
        <i class="fas fa-upload text-primary"></i>
        Subir Nuevo Documento
    </h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Agregar Manual a la Base de Conocimiento
                </h3>
            </div>

            <form action="{{ route('ayuda-softland.documents.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="uploadForm">
                @csrf

                <div class="card-body">
                    <!-- Alertas -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h5><i class="icon fas fa-ban"></i> Errores de validación:</h5>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Información -->
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Instrucciones</h5>
                        <ul class="mb-0">
                            <li>El archivo debe ser en formato <strong>PDF</strong></li>
                            <li>Tamaño máximo: <strong>50 MB</strong></li>
                            <li>El contenido se indexará <strong>automáticamente</strong> para búsquedas con IA</li>
                            <li>Puedes crear una nueva categoría o usar una existente</li>
                        </ul>
                    </div>

                    <!-- Título del documento -->
                    <div class="form-group">
                        <label for="title">
                            <i class="fas fa-heading"></i> Título del Documento *
                        </label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
                               placeholder="Ej: Manual de Usuario - Facturación 7.0"
                               required
                               maxlength="500">
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Nombre descriptivo del documento (máx. 500 caracteres)
                        </small>
                    </div>

                    <!-- Archivo PDF -->
                    <div class="form-group">
                        <label for="file">
                            <i class="fas fa-file-pdf"></i> Archivo PDF *
                        </label>
                        <div class="custom-file">
                            <input type="file"
                                   class="custom-file-input @error('file') is-invalid @enderror"
                                   id="file"
                                   name="file"
                                   accept=".pdf"
                                   required>
                            <label class="custom-file-label" for="file">Seleccionar archivo PDF...</label>
                            @error('file')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            Solo archivos PDF. Máximo 50 MB.
                        </small>
                        <div id="fileInfo" class="mt-2" style="display: none;">
                            <div class="alert alert-secondary mb-0">
                                <strong>Archivo seleccionado:</strong>
                                <div id="fileName"></div>
                                <div id="fileSize" class="text-muted small"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Categoría -->
                    <div class="form-group">
                        <label for="category">
                            <i class="fas fa-folder"></i> Categoría *
                        </label>
                        <select class="form-control @error('category') is-invalid @enderror"
                                id="category"
                                name="category"
                                required>
                            <option value="">-- Selecciona una categoría --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                            <option value="nueva">➕ Crear nueva categoría</option>
                        </select>
                        @error('category')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nueva categoría (oculto por defecto) -->
                    <div class="form-group" id="newCategoryGroup" style="display: none;">
                        <label for="new_category">
                            <i class="fas fa-folder-plus"></i> Nueva Categoría
                        </label>
                        <input type="text"
                               class="form-control @error('new_category') is-invalid @enderror"
                               id="new_category"
                               name="new_category"
                               value="{{ old('new_category') }}"
                               placeholder="Ej: tutoriales, guias-rapidas, etc."
                               maxlength="100">
                        @error('new_category')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Usa minúsculas y guiones en lugar de espacios
                        </small>
                    </div>

                    <!-- Descripción -->
                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-align-left"></i> Descripción (Opcional)
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="3"
                                  maxlength="1000"
                                  placeholder="Breve descripción del contenido del documento...">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Máximo 1000 caracteres
                        </small>
                    </div>

                    <!-- Versión -->
                    <div class="form-group">
                        <label for="version">
                            <i class="fas fa-code-branch"></i> Versión (Opcional)
                        </label>
                        <input type="text"
                               class="form-control @error('version') is-invalid @enderror"
                               id="version"
                               name="version"
                               value="{{ old('version') }}"
                               placeholder="Ej: 7.0, 2024, etc."
                               maxlength="20">
                        @error('version')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Indicador de procesamiento -->
                    <div id="processingIndicator" class="alert alert-warning" style="display: none;">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        <strong>Procesando...</strong> Subiendo archivo e indexando contenido. Esto puede tomar unos momentos.
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                        <i class="fas fa-cloud-upload-alt"></i> Subir e Indexar Documento
                    </button>
                    <a href="{{ route('ayuda-softland.index') }}" class="btn btn-default btn-lg">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>

        <!-- Información adicional -->
        <div class="card card-secondary collapsed-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-question-circle"></i>
                    ¿Qué sucede al subir un documento?
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <ol>
                    <li><strong>Validación:</strong> Se verifica que el archivo sea PDF y no supere los 50 MB</li>
                    <li><strong>Almacenamiento:</strong> El PDF se guarda en <code>storage/manuales/</code></li>
                    <li><strong>Extracción de texto:</strong> Se extrae automáticamente todo el texto del PDF</li>
                    <li><strong>Indexación:</strong> El contenido se procesa y se hace disponible para búsquedas con IA</li>
                    <li><strong>Disponibilidad inmediata:</strong> El documento queda disponible para consultas</li>
                </ol>
                <div class="alert alert-info mb-0">
                    <strong>💡 Nota:</strong> Los PDFs que solo contienen imágenes tendrán contenido limitado.
                    Para mejor indexación, usa PDFs con texto seleccionable.
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$(document).ready(function() {
    $.noConflict();

    // Mostrar nombre del archivo seleccionado
    $('#file').on('change', function() {
        const file = this.files[0];
        if (file) {
            const fileName = file.name;
            const fileSize = (file.size / (1024 * 1024)).toFixed(2); // MB

            $('.custom-file-label').text(fileName);
            $('#fileName').text(fileName);
            $('#fileSize').text(`Tamaño: ${fileSize} MB`);
            $('#fileInfo').show();

            // Validar tamaño
            if (file.size > 52428800) { // 50 MB
                alert('⚠️ El archivo es muy grande. El tamaño máximo es 50 MB.');
                $(this).val('');
                $('.custom-file-label').text('Seleccionar archivo PDF...');
                $('#fileInfo').hide();
            }
        }
    });

    // Mostrar/ocultar campo de nueva categoría
    $('#category').on('change', function() {
        if ($(this).val() === 'nueva') {
            $('#newCategoryGroup').slideDown();
            $('#new_category').prop('required', true);
        } else {
            $('#newCategoryGroup').slideUp();
            $('#new_category').prop('required', false).val('');
        }
    });

    // Mostrar indicador al enviar formulario
    $('#uploadForm').on('submit', function() {
        $('#submitBtn').prop('disabled', true);
        $('#processingIndicator').show();
    });

    // Auto-limpiar nombre de nueva categoría
    $('#new_category').on('blur', function() {
        let value = $(this).val();
        // Convertir a minúsculas y reemplazar espacios por guiones
        value = value.toLowerCase()
                     .replace(/\s+/g, '-')
                     .replace(/[^a-z0-9-]/g, '');
        $(this).val(value);
    });
});
</script>
@stop
