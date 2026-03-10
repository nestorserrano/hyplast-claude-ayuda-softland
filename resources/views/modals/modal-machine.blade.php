<div class="modal fade modal-danger" id="modalMachine" role="dialog" aria-labelledby="modalMachineLabel" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">
                    {{ trans('modals.products_machines') }}
                </h4>
                <button type="button" class="close" onclick="closeModal('modalMachine')" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center">{!! trans('hyplast.machines-table.code') !!}</th>
                                        <th>{!! trans('hyplast.machines-table.name') !!}</th>
                                    </tr>
                                </thead>
                                <tbody id="modal_result" name="modal_result">
                                    <tr><td colspan="2" class="text-center">Cargando...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalMachine')">
                    <i class="fa fa-fw fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
