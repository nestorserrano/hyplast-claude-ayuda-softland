@section('template_linked_css')
    @if(config('hyplast.enabledDatatablesJs'))
        <link rel="stylesheet" type="text/css" href="{{ config('hyplast.datatablesCssCDN') }}">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/styles/choices.min.css" />
    <style type="text/css" media="screen">

    </style>
@endsection


<div class="modal fade  modal-primary" id="modalConsume" role="dialog" aria-labelledby="modalProductLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ trans('modals.requisitions_consume_coils') }}
                </h4>
                <button type="button" class="close cancelbuttom5" data-dismiss="modal" aria-label="Close" id = "cancelbutton6" name = "cancelbutton5">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">cerrar</span>
                </button>
            </div>
            <div class="modal-body">
                <p>

                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback row {{ $errors->has('requisition') ? ' has-error ' : '' }}">
                                        {!! Form::label('requisition', trans('forms.create_extruder_label_requisition'), array('class' => 'col-sm-4 control-label')); !!}
                                        <div class="col-sm-8">
                                            <select class="custom-select form-control" name="requisition" id="requisition" required="true" onchange="loadData()">
                                                <option value="">{{ trans('forms.create_extruder_ph_requisition') }}</option>
                                                @foreach ($requisitions as $requisition)
                                                    @if (old('requisition') == $requisition->id)
                                                        <option value="{{$requisition->id}}" selected>{{$requisition->id}} | {{$requisition->client_name ?? 'Sin Cliente'}} | {{$requisition->product_name ?? 'Sin Producto'}}</option>
                                                    @else
                                                        <option value="{{$requisition->id}}">{{$requisition->id}} | {{$requisition->client_name ?? 'Sin Cliente'}} | {{$requisition->product_name ?? 'Sin Producto'}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('requisition'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('requisition') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback row {{ $errors->has('machine') ? ' has-error ' : '' }}">
                                        {!! Form::label('product', trans('forms.create_extruder_label_consume_coil'), array('class' => 'col-sm-4 control-label')); !!}
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <select class="custom-select form-control" name="machine" id="machine" required="true">

                                                </select>

                                                <div class="input-group-append">
                                                    <label class="input-group-text" for="role">
                                                        <i class="{{ trans('forms.create_extruder_icon_machine') }}" aria-hidden="true"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            @if ($errors->has('machine'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('machine') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group has-feedback row {{ $errors->has('barcode') ? ' has-error ' : '' }}">
                                            {!! Form::label('barcode', trans('forms.create_machine_label_code'), array('class' => 'col-sm-2 control-label')); !!}
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    {!! Form::text('barcode', NULL, array('id' => 'barcode', 'class' => 'form-control', 'placeholder' => trans('forms.create_machine_ph_code'))) !!}
                                                    <div class="input-group-append">
                                                        <label for="batch" class="input-group-text">
                                                            <i class="fa fa-fw {{ trans('forms.create_machine_icon_code') }}" aria-hidden="true"></i>
                                                        </label>
                                                        <a class="btn btn-info" type="button" id="abrirModal" data-toggle="modal" data-target="#cameraScanner" data-keyboard="true" data-backdrop="static" onclick="camara()">
                                                            {!! trans('hyplast.buttons.camera') !!}
                                                        </a>
                                                    </div>
                                                </div>
                                                @if ($errors->has('barcode'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('barcode') }}</strong>
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
                <p>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive machine-table">
                                <table class="table table-striped table-sm data-table">
                                    <thead class="thead">
                                        <tr>
                                            <th style="width:10%">{!! trans('hyplast.machines-table.requisition') !!}</th>
                                            <th>{!! trans('hyplast.machines-table.codebar') !!}</th>
                                            <th>{!! trans('hyplast.machines-table.name') !!}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="result_coil" name="result_coil">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </p>
            </div>
            <div class="modal-footer">
                <span id='buttom_consume'></span>
                {!! Form::button('<i class="fa fa-door-closed" aria-hidden="true"></i>  <span class="hidden-xs">Cerrar</span><span class="hidden-xs"></span>', array('class' => 'btn btn-danger pull-left', 'type' => 'button', 'data-dismiss' => 'modal', 'id' => 'cancelbutton5', 'name' => 'cancelbutton5' )) !!}
            </div>
        </div>
    </div>
</div>

