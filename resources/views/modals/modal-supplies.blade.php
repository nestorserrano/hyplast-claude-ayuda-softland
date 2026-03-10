<div class="modal fade modal-success" id="modalSupplies" role="dialog" aria-labelledby="modalMachineLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title2">
                    {{ trans('modals.machines_products') }}
                </h4>
                <button type="button" class="close" onclick="closeModal('modalSupplies')" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">cerrar</span>
                </button>
            </div>
            <div class="modal-body">
                <p>

                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span id="modal-title" name="modal-title">
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filtros en primera fila -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="filter_tipo_articulo">Tipo de Artículo</label>
                                    <select class="form-control form-control-sm" id="filter_tipo_articulo">
                                        <option value="">Todos</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="filter_grupo_supplie">Grupo</label>
                                    <select class="form-control form-control-sm" id="filter_grupo_supplie">
                                        <option value="">Todos</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="filter_proceso_supplie">Proceso</label>
                                    <select class="form-control form-control-sm" id="filter_proceso_supplie">
                                        <option value="">Todos</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Selector de insumo y botón agregar en segunda fila -->
                            <div class="row mb-3">
                                <div class="col-md-10">
                                    <label for="product2">{{ trans('forms.create_product_label_selectsupplie') }}</label>
                                    <select class="form-control form-control-sm product2" name="product2" id="product2">
                                        <option value="">{{ trans('forms.create_product_label_supplie') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-success btn-block" id="btnAgregarInsumo">
                                        <i class="fa fa-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive machine-table">
                                <table class="table table-striped table-sm data-table">
                                    <thead class="thead">
                                        <tr>
                                            <th>{!! trans('hyplast.machines-table.code') !!}</th>
                                            <th class="hidden-xs">{!! trans('hyplast.machines-table.name') !!}</th>
                                            <th class="hidden-xs">{!! trans('hyplast.machines-table.quantity') !!}</th>
                                            <th class="hidden-xs">{!! trans('hyplast.machines-table.unit') !!}</th>
                                            <th>{!! trans('hyplast.machines-table.actions') !!}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="modal_result3" name="modal_result3">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-light pull-left" onclick="closeModal('modalSupplies')">
                    <i class="fa fa-fw fa-times" aria-hidden="true"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ingresar cantidad -->
<div class="modal fade" id="modalCantidadInsumo" role="dialog" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cantidad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="input_cantidad_insumo">Ingrese la cantidad del insumo:</label>
                    <input type="text" class="form-control" id="input_cantidad_insumo" placeholder="Cantidad" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarCantidad">Agregar</button>
            </div>
        </div>
    </div>
</div>
