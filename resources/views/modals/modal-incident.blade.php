@section('template_linked_css')
    @if(config('hyplast.enabledDatatablesJs'))
        <link rel="stylesheet" type="text/css" href="{{ config('hyplast.datatablesCssCDN') }}">
    @endif
    <style type="text/css" media="screen">

    </style>
@endsection



<div class="modal fade modal-primary" id="modalIncident" role="dialog" aria-labelledby="modalIncidentLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ trans('modals.requisitions_consume_coils') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id = "cancelbuttonStatus" name = "cancelbuttonStatus">
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
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback row {{ $errors->has('requisition') ? ' has-error ' : '' }}">
                                        {!! Form::label('requisition', trans('forms.create_extruder_label_requisition'), array('class' => 'col-sm-4 control-label')); !!}
                                        <div class="col-sm-8">
                                            <div class="input-group">

                                                <div class="input-group-append">
                                                    <label class="input-group-text" for="role">
                                                        <i class="{{ trans('forms.create_extruder_icon_requisition') }}" aria-hidden="true"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            @if ($errors->has('requisition'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('requisition') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group has-feedback row {{ $errors->has('reason') ? ' has-error ' : '' }}">
                                            {!! Form::label('barcode', trans('forms.create_product_label_reason'), array('class' => 'col-sm-2 control-label')); !!}
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    {!! Form::text('reason', old('reason'), array('id' => 'reason', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_reason'))) !!}
                                                    <div class="input-group-append">
                                                        <label for="reason" class="input-group-text">
                                                            <i class="fa fa-fw {{ trans('forms.create_product_icon_reason') }}" aria-hidden="true"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                                @if ($errors->has('reason'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('reason') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </p>
            </div>
            <div class="modal-footer">
                <span id='buttom_consume'></span>
                {!! Form::button('<i class="fa fa-door-closed" aria-hidden="true"></i>  <span class="hidden-xs">Cerrar</span><span class="hidden-xs"></span>', array('class' => 'btn btn-danger pull-left', 'type' => 'button', 'data-dismiss' => 'modal', 'id' => 'cancelbuttonStatus2', 'name' => 'cancelbuttonStatus2' )) !!}
            </div>
        </div>
    </div>
</div>
