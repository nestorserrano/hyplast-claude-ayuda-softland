<div class="modal fade" id="cameraScanner" role="dialog" aria-labelledby="cameraScannerLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ trans('modals.machines_products') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id = "close1" name = "close1">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">cerrar</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="qr-reader" style="width:300px"></div>
                <div id="qr-reader-results"></div>
            </div>
            <div class="modal-footer">
                {!! Form::button('<i class="fa fa-fw fa-times" aria-hidden="true"></i> Cerrar', array('class' => 'btn btn-outline pull-left btn-light', 'type' => 'button', 'data-dismiss' => 'modal', 'id' => 'close1', 'name' => 'close1' )) !!}
            </div>
        </div>
    </div>
</div>
