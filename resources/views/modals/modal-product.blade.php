<div class="modal fade modal-primary" id="modalProduct" role="dialog" aria-labelledby="modalProductLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ trans('modals.machines_products') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id = "cancelbutton3" name = "cancelbutton3">
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
                            <div class="row">
                                <div class="form-group has-feedback row {{ $errors->has('product2') ? ' has-error ' : '' }}">
                                    {!! Form::label('product2', trans('forms.create_extruder_label_selectproduct'), array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <select class="custom-select form-control" name="product2" id="product2">
                                                <option value="">{{ trans('forms.create_extruder_label_product') }}</option>
                                                @foreach ($products as $product)
                                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                                @endforeach
                                            </select>

                                            <div class="input-group-append">
                                                <label class="input-group-text" for="role">
                                                    <i class="{{ trans('forms.create_extruder_icon_product') }}" aria-hidden="true"></i>
                                                </label>
                                            </div>
                                        </div>
                                        @if ($errors->has('product2'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('product2') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-2" id="btn1" name="btn1">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive machine-table">
                                <table class="table table-striped table-sm data-table">
                                    <thead class="thead">
                                        <tr>
                                            <th>{!! trans('hyplast.machines-table.code') !!}</th>
                                            <th class="hidden-xs">{!! trans('hyplast.machines-table.name') !!}</th>
                                            <th>{!! trans('hyplast.machines-table.actions') !!}</th>
                                        </tr>
                                    </thead>
                                     <tbody id="modal_result2" name="modal_result2">

                                     </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </p>
            </div>
            <div class="modal-footer">
                {!! Form::button('<i class="fa fa-fw fa-times" aria-hidden="true"></i> Cancelar', array('class' => 'btn btn-outline pull-left btn-light', 'type' => 'button', 'data-dismiss' => 'modal', 'id' => 'cancelbutton4', 'name' => 'cancelbutton4' )) !!}
            </div>
        </div>
    </div>
</div>
