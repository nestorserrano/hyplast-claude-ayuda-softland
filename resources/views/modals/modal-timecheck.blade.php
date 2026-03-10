@section('template_linked_css')
    @if(config('hyplast.enabledDatatablesJs'))
        <link rel="stylesheet" type="text/css" href="{{ config('hyplast.datatablesCssCDN') }}">
    @endif
    <style type="text/css" media="screen">

    </style>
@endsection



<div class="modal fade modal-primary" id="modalTimecheck" role="dialog" aria-labelledby="modalTimecheckLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ trans('modals.timecheck_title') }}
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
                                    {{ trans('modals.timecheck_title2') }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback row {{ $errors->has('id') ? ' has-error ' : '' }}">
                                        {!! Form::label('id', trans('forms.create_extruder_label_id'), array('class' => 'col-md-6 control-label')); !!}
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                {!! Form::text($coilnro, old($coilnro), array('id' => 'id', 'class' => 'form-control', 'placeholder' => trans('forms.create_extruder_ph_id'), 'required','readonly')) !!}
                                                <div class="input-group-append">
                                                    <label class="input-group-text" for="name">
                                                        <i class="{{ trans('forms.create_extruder_icon_id') }}" aria-hidden="true"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            @if ($errors->has('id'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback row {{ $errors->has('id') ? ' has-error ' : '' }}">
                                        {!! Form::label('product', trans('forms.create_extruder_label_product'), array('class' => 'col-md-4 control-label')); !!}
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                {!! Form::text($product, old($product), array('id' => 'product', 'class' => 'form-control', 'placeholder' => trans('forms.create_extruder_ph_product'), 'required','readonly')) !!}
                                                <div class="input-group-append">
                                                    <label class="input-group-text" for="name">
                                                        <i class="{{ trans('forms.create_extruder_icon_product') }}" aria-hidden="true"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            @if ($errors->has('product'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('product') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback row {{ $errors->has('weight') ? ' has-error ' : '' }}">
                                        {!! Form::label('weight', trans('forms.create_extruder_label_weight'), array('class' => 'col-md-4 control-label')); !!}
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                {!! Form::text('weight', old('weight'), array('id' => 'weight', 'class' => 'form-control', 'placeholder' => trans('forms.create_extruder_ph_weight'), 'required','readonly')) !!}
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-info" onclick="getScaleWeight()">
                                                        <i class="{{ trans('forms.create_extruder_icon_weight') }}" aria-hidden="true"></i> Leer peso
                                                    </button>
                                                </div>
                                            </div>
                                            @if ($errors->has('weight'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('weight') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback row {{ $errors->has('operator_id') ? ' has-error ' : '' }}">
                                        {!! Form::label('operator_id', trans('forms.create_extruder_label_operatorf'), array('class' => 'col-sm-4 control-label')); !!}
                                        <div class="col-sm-8 align-self-center">
                                            <div class="input-group">
                                                <select class="custom-select form-control" name="operator_id" id="operator_id" required="true">
                                                    <option value="">{{ trans('forms.create_extruder_ph_operatorf') }}</option>
                                                    @foreach ($operators as $operator)
                                                        @if (old('operator_id') == $operator->id)
                                                            <option value="{{$operator->id}}" selected>{{$operator->name}}</option>
                                                        @else
                                                            <option value="{{$operator->id}}">{{$operator->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                <div class="input-group-append">
                                                    <label class="input-group-text" for="role">
                                                        <i class="{{ trans('forms.create_extruder_icon_operatorf') }}" aria-hidden="true"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            @if ($errors->has('operator_id'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('operator_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback row {{ $errors->has('begin') ? ' has-error ' : '' }}">
                                        {!! Form::label('begin', trans('forms.create_extruder_label_begin'), array('class' => 'col-md-4 control-label')); !!}
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                {!! Form::text('begin', old('begin'), array('id' => 'begin', 'class' => 'form-control', 'placeholder' => trans('forms.create_extruder_ph_begin'), 'required', 'readonly' => 'true')) !!}
                                                <div class="input-group-append">
                                                    <label class="input-group-text" for="name">
                                                        <i class="{{ trans('forms.create_extruder_icon_begin') }}" aria-hidden="true"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            @if ($errors->has('begin'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('begin') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback row {{ $errors->has('ending') ? ' has-error ' : '' }}">
                                        {!! Form::label('ending', trans('forms.create_extruder_label_ending'), array('class' => 'col-md-4 control-label')); !!}
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                {!! Form::text('ending', old('ending'), array('id' => 'ending', 'class' => 'form-control', 'placeholder' => trans('forms.create_extruder_ph_ending'), 'required', 'readonly' => 'true')) !!}
                                                <div class="input-group-append">
                                                    <label class="input-group-text" for="name">
                                                        <i class="{{ trans('forms.create_extruder_icon_ending') }}" aria-hidden="true"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            @if ($errors->has('ending'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('ending') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback row {{ $errors->has('timeproducer') ? ' has-error ' : '' }}">
                                        {!! Form::label('timeproducer', trans('forms.create_extruder_label_time_producer'), array('class' => 'col-md-4 control-label')); !!}
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                {!! Form::text('timeproducer', old('timeproducer'), array('id' => 'timeproducer', 'class' => 'form-control', 'placeholder' => trans('forms.create_extruder_ph_time_producer'), 'required', 'required', 'readonly' => 'true')) !!}
                                                <div class="input-group-append">
                                                    <label class="input-group-text" for="name">
                                                        <i class="{{ trans('forms.create_extruder_icon_time_producer') }}" aria-hidden="true"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            @if ($errors->has('time_producer'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('time_producer') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group has-feedback row {{ $errors->has('ending') ? ' has-error ' : '' }}">
                                        <span id="time-producer"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-sm-12">
                                    <div class="form-group has-feedback row {{ $errors->has('reference') ? ' has-error ' : '' }}">
                                        {!! Form::label('reference', trans('forms.create_extruder_label_reference'), array('class' => 'col-md-2 control-label')); !!}
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                {!! Form::text('reference', old('reference'), array('id' => 'reference', 'class' => 'form-control', 'placeholder' => trans('forms.create_extruder_ph_reference'), 'required', 'required')) !!}
                                                <div class="input-group-append">
                                                    <label class="input-group-text" for="name">
                                                        <i class="{{ trans('forms.create_extruder_icon_reference') }}" aria-hidden="true"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            @if ($errors->has('reference'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('reference') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>






                        </div>
                    </div>

                </p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-warning" type="button" name="btnSend" id="btnSend" onclick="sendData()">{!! trans("hyplast.buttons.save") !!}</a>
                {!! Form::button('<i class="fa fa-door-closed" aria-hidden="true"></i>  <span class="hidden-xs">Cerrar</span><span class="hidden-xs"></span>', array('class' => 'btn btn-danger pull-left', 'type' => 'button', 'data-dismiss' => 'modal', 'id' => 'cancelbuttonStatus2', 'name' => 'cancelbuttonStatus2' )) !!}
            </div>
        </div>
    </div>
</div>
