<div class="modal fade modal-primary" id="modalMachineSelect" role="dialog" aria-labelledby="modalProductLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class='modal-title2' id='modal-title2' name='modal-title2'>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cleanModal()">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">cerrar</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-top">
                            <div class="col-sm-8">
                                <div id='select-result' name='select-result'></div>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-md btn-success btn-block" id="saveproduct" name="saveproduct" type="button" data-dismiss = "modal" onclick="saveProductModal()"><i class="fa fa-save" aria-hidden="true"></i> Guardar</button>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-md btn-danger btn-block" id="cancelbtn" name="cancelbtn" type="button" data-dismiss = "modal" onclick="cleanModal()"><i class="fa fa-door-closed" aria-hidden="true"></i> Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
