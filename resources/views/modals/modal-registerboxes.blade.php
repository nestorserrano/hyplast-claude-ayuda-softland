@section('template_linked_css')
    @if(config('hyplast.enabledDatatablesJs'))
        <link rel="stylesheet" type="text/css" href="{{ config('hyplast.datatablesCssCDN') }}">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/styles/choices.min.css" />
    <style type="text/css" media="screen">

    </style>
@endsection



<div class="modal fade modal-primary" id="modalRegister" role="dialog" aria-labelledby="modalRegisterLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ trans('modals.requisitions_register_boxes') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id = "cancelbutton8" name = "cancelbutton8">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">cerrar</span>
                </button>
            </div>
            <div class="modal-body">
                @role('admin|supervisornave')
                <p>
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback row {{ $errors->has('requisition2') ? ' has-error ' : '' }}">
                                        {!! Form::label('requisition2', trans('forms.create_extruder_label_requisition'), array('class' => 'col-sm-4 control-label')); !!}
                                        <div class="col-sm-8">
                                            <select class="custom-select form-control" name="requisition2" id="requisition2" required="true" onchange="loadData2()">
                                                <option value="">{{ trans('forms.create_extruder_ph_requisition') }}</option>
                                                @foreach ($requisitions as $requisition)
                                                    @if (old('requisition2') == $requisition->id)
                                                        <option value="{{$requisition->id}}" selected>{{$requisition->id}} | {{$requisition->client_name ?? 'Sin Cliente'}} | {{$requisition->product_name ?? 'Sin Producto'}}</option>
                                                    @else
                                                        <option value="{{$requisition->id}}">{{$requisition->id}} | {{$requisition->client_name ?? 'Sin Cliente'}} | {{$requisition->product_name ?? 'Sin Producto'}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('requisition2'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('requisition2') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback row {{ $errors->has('machine2') ? ' has-error ' : '' }}">
                                        {!! Form::label('machine2', trans('forms.create_extruder_label_consume_coil'), array('class' => 'col-sm-4 control-label')); !!}
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <select class="custom-select form-control" name="machine2" id="machine2" required="true">
                                                </select>

                                                <div class="input-group-append">
                                                    <label class="input-group-text" for="role">
                                                        <i class="{{ trans('forms.create_extruder_icon_machine') }}" aria-hidden="true"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            @if ($errors->has('machine2'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('machine2') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </p>
                <p>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive machine-table">
                                <table class="table table-striped table-sm data-table">
                                    <thead class="thead">
                                        <tr>
                                            <th>{!! trans('hyplast.table.code') !!}</th>
                                            <th>{!! trans('hyplast.table.product') !!}</th>
                                            <th  style="width:10%; text-align: center">{!! trans('hyplast.table.box_litter') !!}</th>
                                            <th  style="width:10%; text-align: center">{!! trans('hyplast.table.platform_litter') !!}</th>
                                            <th  style="width:10%; text-align: center">{!! trans('hyplast.table.boxes') !!}</th>
                                            <th  style="width:10%; text-align: center">{!! trans('hyplast.table.register_boxes') !!}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="result_register" name="result_register">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </p>
                @endrole

            </div>
            <div class="modal-footer">
                <span id='buttom_register'></span>
                {!! Form::button('<i class="fa fa-door-closed" aria-hidden="true"></i>  <span class="hidden-xs">Cerrar</span><span class="hidden-xs"></span>', array('class' => 'btn btn-danger pull-left', 'type' => 'button', 'data-dismiss' => 'modal', 'id' => 'cancelbutton7', 'name' => 'cancelbutton7' )) !!}
            </div>
        </div>
    </div>
</div>
