@extends('admin.layouts.app')
@section('title', 'Contract')
@section('stylesheets')
<style>
    #input-list-checkbox .form-control {
        height: calc(1.9rem + 7.5px);
    }

    .row-form {
        padding-left: 30px !important;
    }

    .other {
        display: none;
        padding-left: 40px !important;
    }

    #form-idm {
        display: none;
    }

    .bootstrap-switch-handle-on.bootstrap-switch-success {
        width: 100px !important;
    }

    .bootstrap-switch-label {
        width: 126px !important;
    }

    .bootstrap-switch-handle-off.bootstrap-switch-default {
        width: 100px !important;
    }
</style>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Edit Contract
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Procurment</li>
            <li class="breadcrumb-item">Contract</li>
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <form role="form" id="form-data" action="{{route('contract.update',['id'=>$contract->id])}}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="put">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr />
                                <h5 class="text-md text-dark text-bold">Contract Information</h5>
                            </span>
                            <div class="form-group mt-4">
                                <label for="complainant">Number:</label>
                                <input type="text" class="form-control" name="number" placeholder="Contract number..." required value="{{ $contract->number }}">
                            </div>
                            <div class="form-group">
                                <label for="complainant">Title:</label>
                                <input type="text" class="form-control" name="title" placeholder="Contract Title..." required value="{{ $contract->title }}">
                            </div>
                            <div class="form-group">
                                <label for="id_number">Scope of Work:</label>
                                <textarea class="form-control summernote" name="scope" rows="4" placeholder="Scope of Work and Deliverables">{!! $contract->scope_of_work !!}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="contra">Contractor:</label>
                                <div class="row">
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="contractor" placeholder="Enter Contractor Name..." required value="{{ $contract->contractor }}">
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="icheck-success d-inline ">
                                            <input type="checkbox" name="jv" value="jv" id="jv" @if(count($contract->jvmember) > 0) checked @endif data-total="{{ count($contract->jvmember) }}" >
                                            <label for="jv">JV</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jv-mber" @if(count($contract->jvmember) <= 0) style="display: none;" @endif>
                                    <div class="form-group">
                                        <label for="complainant">JV Members:</label>
                                        <div id="form-member">
                                            @if(count($contract->jvmember) <= 0) <div class="row item-member">
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="jv_member[]" placeholder="Enter member ...." required>
                                                </div>
                                                <div class="col-sm-2">
                                                    @if($contract->status != 'publish')
                                                    <button id="add-member" data-urutan="1" type="button" class="btn btn-success legitRipple text-sm">
                                                        <b><i class="fas fa-plus"></i> Add</b>
                                                    </button>
                                                    @endif
                                                </div>
                                        </div>
                                        @else
                                        @php $num = 0; @endphp
                                        @foreach ($contract->jvmember as $jvmember)
                                        @php $num++; @endphp
                                        <div class="row item-member @if($num>1) mt-1 @endif" @if($num>1) id="member-{{ $num }}" @endif>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="jv_member[]" placeholder="Enter member ...." required value="{{ $jvmember->name }}">
                                            </div>
                                            <div class="col-sm-2" @if(count($contract->jvmember) != $num) @if($num != 1) style="display:none;" @endif @endif >
                                                @if($num == 1)
                                                @if($contract->status != 'publish')
                                                <button id="add-member" data-urutan="@if(count($contract->jvmember) > 0){{ count($contract->jvmember) }}@else 1 @endif" type="button" class="btn btn-success legitRipple text-sm">
                                                    <b><i class="fas fa-plus"></i> Add</b>
                                                </button>
                                                @endif
                                                @endif
                                                @if($num > 1)
                                                @if($contract->status != 'publish')
                                                <button type="button" class="btn btn-transparent text-md" onclick="removeMember(this)" data-urutan="{{ $num }}">
                                                    <i class="fas fa-trash text-maroon color-palette"></i>
                                                </button>
                                                @endif
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="complainant">Contract Value:</label>
                            <div class="row">
                                <div class="col-sm-4">
                                    <select type="text" class="select2 form-control" name="contract_currency">
                                        <option value="">Choose a Currency</option>
                                        <option value="rupiah" @if($contract->contract_currency == "rupiah") selected @endif >Rp</option>
                                        <option value="dollar" @if($contract->contract_currency == "dollar") selected @endif>$</option>
                                        <option value="yen" @if($contract->contract_currency == "yen") selected @endif>¥</option>
                                        <option value="euro" @if($contract->contract_currency == "euro") selected @endif>€</option>
                                    </select>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control text-right input-price" id="contract_value" name="contract_value" placeholder="Contract Value..." required value="{{ number_format($contract->contract_value,'2',',','.') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="complainant">Contract PIC:</label>
                            <input type="text" class="form-control" name="contract_pic" placeholder="Contract PIC..." required value="{{ $contract->contract_pic }}">
                        </div>
                        <div id="input-list-checkbox" class="form-group row">
                            <div class="col-md-12 mb-1">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" value="pb" name="pb" id="type0" @if(count($contract->pb) > 0) checked @endif>
                                    <label for="type0">Performance Bond:</label>
                                </div>
                                <div class="row-form mt-1">
                                    <div class="row mb-1">
                                        <label class="col-sm-4 control-label" for="issued">Issued by</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control " placeholder="Bond Issuer..." name="issued_pb" value="{{ @$contract->pb[0]->issued_by }}">
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <label class="col-sm-4 control-label" for="issued">Validity Period</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control " placeholder="Bond Validity Period..." name="validity_pb" value="{{ @$contract->pb[0]->validity_period }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="">months</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" value="ab" name="ab" id="type1" @if(count($contract->ab) > 0) checked @endif>
                                    <label for="type1">Advance Bond:</label>
                                </div>
                                <div class="row-form mt-1">
                                    <div class="row mb-1">
                                        <label class="col-sm-4 control-label" for="issued">Issued by</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control " placeholder="Bond Issuer..." name="issued_ab" value="{{ @$contract->ab[0]->issued_by }}">
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <label class="col-sm-4 control-label" for="validity">Validity Period</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control " placeholder="Bond Validity Period..." name="validity_ab" value="{{ @$contract->ab[0]->validity_period }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="">months</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <label class="col-sm-4 control-label" for="value">Value</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control " placeholder="Bond Value..." name="value_ab" value="{{ @$contract->ab[0]->value }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" value="rb" name="rb" id="type2" @if(count($contract->rb) > 0) checked @endif>
                                    <label for="type2">Retention Bond:</label>
                                </div>
                                <div class="row-form mt-1">
                                    <div class="row mb-1">
                                        <label class="col-sm-4 control-label" for="issued">Issued by</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control " placeholder="Bond Issuer..." name="issued_rb" value="{{ @$contract->ab[0]->issued_by }}">
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <label class="col-sm-4 control-label" for="validity">Validity Period</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control " placeholder="Bond Validity Period..." name="validity_rb" value="{{ @$contract->ab[0]->validity_period }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="">months</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <label class="col-sm-4 control-label" for="value">Value</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control " placeholder="Bond Value..." name="value_rb" value="{{ @$contract->ab[0]->value }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" value="rm" name="rm" id="type3" @if(count($contract->rm) > 0) checked @endif>
                                    <label for="type3">Retention Money:</label>
                                </div>
                                <div class="row-form mt-1">
                                    <div class="form-group">
                                        <div class="row mb-1">
                                            <label class="col-sm-4 control-label" for="issued">Value</label>
                                            <div class="col-sm-4">
                                                <select type="text" class="select2 form-control" name="currency_rm">
                                                    <option value="">Pick a Currency</option>
                                                    <option value="rupiah" @if(@$contract->rm[0]->currency=='rupiah') selected @endif>Rp</option>
                                                    <option value="dollar" @if(@$contract->rm[0]->currency=='dollar') selected @endif>$</option>
                                                    <option value="yen" @if(@$contract->rm[0]->currency=='yen') selected @endif>¥</option>
                                                    <option value="euro" @if(@$contract->rm[0]->currency=='euro') selected @endif>€</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control text-right input-price" name="value_rm" placeholder="Retention Value..." required value="{{ @$contract->rm[0]->value }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" value="pen" name="pen" id="type4" @if(count($contract->pen) > 0) checked @endif>
                                    <label for="type4">Penalty:</label>
                                </div>
                                <div class="row-form mt-1">
                                    <div class="row mb-1">
                                        <label class="col-sm-4 control-label" for="validity">Late Period</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control " placeholder="Late Period..." name="late_pen" value="{{ @$contract->pen[0]->late_period }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="">months</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <label class="col-sm-4 control-label" for="value">Value</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control " placeholder="Penalty Value" name="value_pen" value="{{ @$contract->pen[0]->value }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" value="wb" name="wb" id="type5" @if(count($contract->wb) > 0) checked @endif>
                                    <label for="type5">Warranty Bond:</label>
                                </div>
                                <div class="row-form mt-1">
                                    <div class="row mb-1">
                                        <label class="col-sm-4 control-label" for="validity">Length</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control " placeholder="Bond Length..." name="length_wb" value="{{ @$contract->wb[0]->length }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="">months</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <label class="col-sm-4 control-label" for="value">Bond Value</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control " placeholder="Bond Value..." name="value_wb" value="{{ @$contract->wb[0]->bond_value }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <label class="col-sm-4 control-label" for="issued">Issued by</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control " placeholder="Bond Issuer..." name="issued_wb" value="{{ @$contract->wb[0]->issued_by }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Remarks:</label>
                            <textarea class="form-control summernote1" name="remarks" rows="4" placeholder="Enter description of the complaint">{!! $contract->remarks !!}</textarea>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <span class="title">
                            <hr />
                            <h5 class="text-md text-dark text-bold">General Information</h5>
                        </span>
                        <div class="form-group mt-4">
                            <label for="number">Procurement Number:</label>
                            <select class="select2" name="purchasing_id" id="procurement" data-placeholder="Tag Procurement Number" style="width: 100%;" required></select>
                        </div>
                        <div class="form-group">
                            <label for="number">Contract Signing Date:</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control datepicker text-right" id="contract_signing_date" name="contract_signing_date" placeholder="Contract Signing Date" required value="{{ date("d/m/Y", strtotime($contract->contract_signing_date)) }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="number">Contract Start Date:</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control datepicker text-right" id="contract_start_date" name="contract_start_date" placeholder="Contract Start Date" required value="{{ date("d/m/Y", strtotime($contract->contract_start_date)) }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="number">Work Start Date:</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control datepicker text-right" id="work_start_date" name="work_start_date" placeholder="Work Start Date" required value="{{ date("d/m/Y", strtotime($contract->work_start_date)) }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="number">Expiration Date:</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control datepicker text-right" id="expiration_date" name="expiration_date" placeholder="Expiration Date" required value="{{ date("d/m/Y", strtotime($contract->expiration_date)) }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="number">Work End Date:</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control datepicker text-right" id="work_end_date" name="work_end_date" placeholder="Work End Date" required value="{{ date("d/m/Y", strtotime($contract->work_end_date)) }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="number">Contract Owner:</label>
                            <select class="select2" name="owner[]" id="owner" data-placeholder="Tag Contract Owner" style="width: 100%;" required multiple>
                                @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @if(in_array($role->id, $contract->owner))) selected @endif>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 mb-1">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" value="insurance" name="insurance" id="insurance" @if($contract->insurance) checked @endif>
                                    <label for="insurance"></label>
                                </div>
                                <span class="text">Insurance</span>
                            </div>
                            <div class="col-md-6 mb-1">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" value="warning" name="warning" id="warning" @if($contract->warning_letter) checked @endif>
                                    <label for="warning"></label>
                                </div>
                                <span class="text">Warning Letter</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Unit:</label>
                            <select name="unit" data-placeholder="Choose Unit" style="width: 100%;" required class="select2 form-control" id="site_id" name="site_id" data-placeholder="Select Site">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="attach">Attachment:</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="attach" accept="image/*" onchange="changePath(this)">
                                    <label class="custom-file-label" for="exampleInputFile">Attach Contract</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="attach">Contract Type: {{ $contract->contract_type }}</label>
                            <select name="contract_type" id="contract_type" class="select2 form-control" data-placeholder="Contract Type" required>
                                <option value="">&nbsp;</option>
                                @foreach(config('enums.contract_type') as $key => $type)
                                <option value="{{ $key }}" @if($contract->contract_type == $key) selected @endif >{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="batch-form" style="display: none;">
                            <label for="attach">Batch:</label>
                            <input type="text" class="form-control" name="batch" placeholder="Batch" required>
                        </div>
                        <div class="form-group">
                            <label>Status:</label><br />
                            <div id="bench">

                            </div>
                            @if($group_code == 'PMU-PRC')
                            <input type="checkbox" id="check-status" class="form-control" name="progress" data-bootstrap-switch data-off-color="default" data-on-color="success" data-on-text="Finished" data-off-text="In Progress" @if($contract->progress) checked @endif @if($range) @if($range <= 29 and $data->status=='publish') @else disabled @endif @else disabled @endif />
                                @else
                                <input type="checkbox" id="check-status" class="form-control" name="progress" data-bootstrap-switch data-off-color="default" data-on-color="success" data-on-text="Finished" data-off-text="In Progress" @if($contract->progress) checked @endif />
                                @endif
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    @if($contract->status != 'publish')
                    <button type="button" onclick="onSubmit('publish')" class="btn btn-success btn-labeled legitRipple text-sm">
                        <b><i class="fas fa-check-circle"></i></b>
                        Publish
                    </button>
                    <button type="button" onclick="onSubmit('draft')" class="btn bg-olive color-palette btn-labeled legitRipple text-sm">
                        <b><i class="fas fa-save"></i></b>
                        Save
                    </button>
                    @else
                    <a href="{{ route('contract.index') }}" class="btn bg-maroon color-palette legitRipple text-sm">
                        <b><i class="fas fa-arrow-left"></i></b>
                    </a>
                    @endif
                </div>
            </div>
    </div>
    </form>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/jquery.inputmask.js') }}"></script>
<script type="text/javascript">
    var prog = @if($contract->status == 'publish') 'publish' @else null @endif;
        var progval = @if($contract->progress) '1' @else null @endif;
        var group_code = '{{ $group_code }}'
        $(function(){
            $("input[name=batch]").inputmask("Regex", { regex: "[1-9]*" });
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            });
            summernote()
            inputPrice()
            $('.datepicker').daterangepicker({
                singleDatePicker: true,
                timePicker: false,
                timePickerIncrement: 30,
                locale: {
                    format: 'DD/MM/YYYY'
                }
            });
            $('.select2').select2();
        
            $("#input-list-checkbox input[type='checkbox']").change(function(){
                let checked = $(this).is(":checked");
                if(checked){
                    $(this).parent().parent().children(".row-form").slideDown("fast");
                    $(this).parent().parent().children(".row-form").find("input").attr('required', 'required');
                }else{
                    $(this).parent().parent().children(".row-form").slideUp("fast");
                    $(this).parent().parent().children(".row-form").find("input").removeAttr('required');
                }
            });
            $("#input-list-checkbox input[type='checkbox']").trigger('change');
        
            $("#jv").change(function(){
                let checked = $(this).is(":checked");
                if(checked){
                    $('#jv-mber').slideDown("fast");
                    $(this).parents('.form-group').find('label[for=contra]').text('JV Leader:')
                    $(this).parents('.form-group').find('input[type=text]').attr('placeholder', 'Enter jv leader ....')
                }else{
                    $('#jv-mber').slideUp("fast");
                    $(this).parents('.form-group').find('label[for=contra]').text('Contractor:')
                    $(this).parents('.form-group').find('input[type=text]').attr('placeholder', 'Enter contractor ....')
                }
            });
            $("#jv").trigger('change')
        
            $("#procurement").select2({
                ajax: {
                    url: '{{ route("purchasing.select") }}',
                    type:'get',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            name:params.term,
                            page:params.page,
                            limit:30,
                            notid:'{{ $userid }}',
                        };
                    },
                    processResults: function (data,params) {
                        var more = (params.page * 30) < data.total;
                        var option = [];
                        $.each(data.rows,function(index,item){
                            option.push({
                                id:item.id,  
                                text: item.number
                            });
                        });
                        return {
                            results: option, more: more,
                        };
                    },
                },
                allowClear: true,
            });
            @if ($contract->purchasing_id)
                $("#procurement").select2("trigger", "select", {
                    data: {id:'{{ $contract->purchasing_id }}', text:'{{ $contract->purchasing->number }}'}
                });
            @endif

            if(prog){
                $('#form-data').find('input, select').attr('disabled', "disabled")
                $('.summernote').summernote('disable');
                $('.summernote1').summernote('disable');
                $('#check-status').removeAttr('disabled')
            }

            if(progval || group_code != 'PMU-PRC'){
                $('#check-status').bootstrapSwitch('disabled', true);
            }

            $( "#site_id" ).select2({
                ajax: {
                    url: "{{ route('site.select') }}",
                    type:'GET',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            name:params.term,
                            page:params.page,
                            limit:30,
                        };
                    },
                    processResults: function (data,params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows,function(index,item){
                        option.push({
                            id:item.id,  
                            text: item.name
                        });
                    });
                    return {
                        results: option, more: more,
                    };
                    },
                },
                allowClear: true,
            });
            $("#site_id").select2("trigger", "select", {
                data: {id:'{{$contract->unit}}', text:'{{$contract->site->name}}'}
            });

            $("#contract_type").on("change",function(){
                if($(this).val() == "product"){
                    $("#batch-form").show(); 
                }else{
                    $("#batch-form").hide(); 
                }
            });

            $("#contract_type").trigger('change');
        
        })
        
        $('#add-member').on('click', function(e) {
            e.preventDefault();
            var no = $(this).data('urutan') + 1,
        	html = `<div class="row item-member mt-1" id="member-${no}">
        				<div class="col-sm-10">
        					<input type="text" class="form-control" name="jv_member[]" placeholder="Enter member ...." required>
        				</div>
        				<div class="col-sm-2">
        					<button type="button" class="btn btn-transparent text-md" onclick="removeMember(this)" data-urutan=${no}><i class="fas fa-trash text-maroon color-palette"></i></button>
        				</div>
        			</div>`;
        
            if (no > 2) {
                $('#member-'+(no-1)).find('.col-sm-2').hide();
            }
            $(this).data('urutan', no);
            $('#form-member').append(html);
        });
        
        let removeMember = (me) => {
            var no = $('#add-member').data('urutan');
            
            if (no == $('.item-member').length) {
                $('#member-'+(no-1)).find('.col-sm-2').show();
                $('#add-member').data('urutan', (no-1));
                $(me).parent().parent().remove();
            }
        }
        
        $('#check-status').on('switchChange.bootstrapSwitch', function (e, data) {
            if ($(this).bootstrapSwitch('state') === true) {
                Swal.fire({
                    title: '<text style="font-size:24px;">Are you sure?<text>',
                    html: '<b><text style="font-size:22px;">WARNING: This Process cannot be Undone</text></b>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3d9970',
                    cancelButtonColor: '#d81b60',
                    confirmButtonText: "<b>I AM SURE</b>",
                    cancelButtonText: "<b>CANCEL</b>",
                }).then((result) => {
                    if (!result.value) {
                        $(this).bootstrapSwitch('state', !data, false);
                    }
                });
            }
        });

        $('#check-status').on('switchChange.bootstrapSwitch', function (e, data) {
            if ($(this).bootstrapSwitch('state') === true) {
                Swal.fire({
                    title: '<text style="font-size:24px;">Are you sure?<text>',
                    html: '<b><text style="font-size:22px;">WARNING: This Process cannot be Undone</text></b>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3d9970',
                    cancelButtonColor: '#d81b60',
                    confirmButtonText: "<b>I AM SURE</b>",
                    cancelButtonText: "<b>CANCEL</b>",
                }).then((result) => {
                    if (!result.value) {
                        $(this).bootstrapSwitch('state', !data, false);
                    } else {
                        $(this).bootstrapSwitch('disabled',true);
                        $('#bench').html('<input type="hidden" value="1" name="progress" />')

                        let data = $('#form-data')[0]
                        let formData = new FormData(data)
                        formData.append('status', 'publish')

                        $.ajax({
                            url: $('#form-data').attr('action'),
                            method: 'post',
                            data: formData,
                            processData: false,
                            contentType: false,
                            dataType: 'json',
                            beforeSend:function(){
                                blockMessage('#bd', 'Please Wait . . . ', '#fff');
                            }
                        }).done(function(response) {
                            $('#bd').unblock();
                            window.location.href = './admin/contract';
                            return;
                        }).fail(function(response) {
                            var response = response.responseJSON;
                            $('#bd').unblock();
                            window.location.href = './admin/contract';
                            return;
                        });
                    }
                });
            } else {
                
            }
        });
        
        function onSubmit(status){
          let data = $('#form-data')[0]
          let formData = new FormData(data)
          formData.append('status', status);
          Swal.fire({
            title: '<text style="font-size:24px;">Are you sure?<text>',
            html: '<text style="font-size:21px;font-weight:bold;">WARNING: This Process cannot be Undone<text>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3d9970',
            cancelButtonColor: '#d81b60',
            confirmButtonText: "<b>I AM SURE</b>",
            cancelButtonText: "<b>CANCEL</b>",
          }).then((result) => {
            if (result.value) {
              $.ajax({
                url: $('#form-data').attr('action'),
                method: 'post',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend:function(){
                  blockMessage('#bd', 'Please Wait . . . ', '#fff');
                }
              }).done(function(response) {
                console.log(response);
                $('#bd').unblock();
                window.location.href = './admin/contract';
                return;
              }).fail(function(response) {
                var response = response.responseJSON;
                console.log(response);
                $('#bd').unblock();
                window.location.href = './admin/contract';
                return;
              });
            }
          })
        }
        
        function summernote(){
          $('.summernote').summernote({
        	height:225,
        	toolbar: [
        		['style', ['style']],
        		['font-style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
        		['font', ['fontname']],
        		['font-size',['fontsize']],
        		['font-color', ['color']],
        		['para', ['ul', 'ol', 'paragraph']],
        		['table', ['table']],
        		['insert', ['link', 'picture', 'video', 'hr']],
        		['misc', ['fullscreen', 'codeview', 'help']]
        	]
           });
        }
        
        function inputPrice(){
            $(".input-price").priceFormat({
                prefix: '',
                centsSeparator: ',',
                thousandsSeparator: '.',
                centsLimit: 2,
                clearOnEmpty: true
            });
        }
        
        function changePath(that) {
            let filename = $(that).val()
            $(that).next().html(filename)
        }
</script>
@endsection