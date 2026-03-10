<div class="modal fade" id="modalAsignarMenus" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">
                    <i class="fas fa-check-double"></i> Asignar Menús a Usuario:
                    <span id="modal-usuario-nombre"></span>
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Seleccione los menús que este usuario podrá ver. Los menús disponibles son del catálogo maestro. Los menús marcados son los que ya tiene el usuario.
                </div>

                <form id="form-asignar-menus">
                    <input type="hidden" id="modal-usuario-id" name="user_id">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-sm btn-primary mb-2" id="btn-seleccionar-todos">
                                    <i class="fas fa-check-square"></i> Seleccionar Todos
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary mb-2" id="btn-deseleccionar-todos">
                                    <i class="fas fa-square"></i> Deseleccionar Todos
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="lista-menus-asignar">
                        <!-- Se llenará dinámicamente con JavaScript -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" id="btn-guardar-asignaciones">
                    <i class="fas fa-save"></i> Guardar Asignaciones
                </button>
            </div>
        </div>
    </div>
</div>
