@extends('admin.layouts.app')
@section('title', $menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            {{ $menu_name }}
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{ $parent_name }}</li>
            <li class="breadcrumb-item">{{ $menu_name }}</li>
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <form class="form-horizontal no-margin" action="{{route('employee.update', ['id' => $data->id])}}" id="form" enctype="multipart/form-data">
            {{ csrf_field() }}
            @method('PUT')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr>
                                <h5 class="text-dark text-bold text-md">Employee Information</h5>
                            </span>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <!-- Employee Name -->
                                    <div class="form-group">
                                        <label class="control-label" for="name">Name</label>
                                        <input class="form-control" type="text" name="employee_name" id="employee-name" placeholder="Name" value="{{$data->name}}">
                                    </div>
                                    <!-- Email -->
                                    <div class="form-group mt-2">
                                        <label class="control-label" for="email">Email</label>
                                        <input class="form-control" type="text" name="email" id="email" placeholder="Email" value="{{$data->email}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- NID -->
                                    <div class="form-group">
                                        <label class="control-label" for="nid">Employee ID</label>
                                        <input class="form-control" type="text" name="nid" id="nid" placeholder="Employee ID" value="{{$data->nid}}">
                                    </div>
                                    <!-- Phone Number -->
                                    <div class="form-group">
                                        <label class="control-label" for="phone-number">Phone Number</label>
                                        <input class="form-control numberfield" type="text" name="phone_number" id="phone-number" placeholder="Phone Number" maxlength="12" value="{{$data->phone}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- Photo -->
                                    <div class="form-group">
                                        <div class="controls text-center upload-image">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="upload-preview-wrapper">
                                                        <a class="remove" style="{{$data->photo?'display : block;':''}}"><i class="fa fa-trash"></i></a>
                                                        <img src="{{asset($data->photo?$data->photo:'assets/img/no-image.png')}}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="upload-btn-wrapper">
                                                        <a class="btn btn-sm btn-default btn-block btn-upload-image"><i class="fa fa-image"></i> Upload a Picture</a>
                                                        <input class="form-control" type="file" accept="image/*" value="{{$data->photo}}" name="photo" id="photo" />
                                                        <input type="hidden" class="has-image" name="has_photo" value="{{$data->photo!=''?1:0}}">
                                                    </div>
                                                    <p class="text-sm text-muted">File must be no more than 2 MB</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="title">
                                <hr>
                                <h5 class="text-dark text-bold text-md">Personal Information</h5>
                            </span>
                            <div class="row mt-4">
                                <div class="col-md-8">
                                    <div class="row">
                                        <!-- NIK -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="nik">NIK</label>
                                                <input class="form-control numberfield" type="text" name="nik" id="nik" placeholder="NIK" value="{{$data->nik}}" maxlength="16">
                                            </div>
                                        </div>
                                        <!-- NPWP -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="npwp">NPWP</label>
                                                <input class="form-control" type="text" name="npwp" id="npwp" placeholder="NPWP" value="{{$data->npwp}}">
                                            </div>
                                        </div>
                                        <!-- Address -->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label" for="address">Address</label>
                                                <input class="form-control" type="text" name="address" id="address" placeholder="Address" value="{{$data->address}}">
                                            </div>
                                        </div>
                                        <!-- City -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="city">City</label>
                                                <select class="form-control" name="city" id="city" data-placeholder="City">
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Province -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="province">Province</label>
                                                <select class="form-control" name="province" id="province" data-placeholder="Province">
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Account Bank -->
                                        <div class="col-md-6">
                                            <label class="control-label" for="account">Account</label>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <input class="form-control" type="text" name="account_bank" id="account-bank" placeholder="Bank" value="{{$data->account_bank}}">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input class="form-control numberfield" type="text" name="account_number" id="account-number" placeholder="Account Number" value="{{$data->account_number}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Account Information -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="account-name">Account Name</label>
                                                <input class="form-control" type="text" name="account_name" id="account-name" placeholder="Account Name" value="{{$data->account_name}}">
                                            </div>
                                        </div>
                                        <!-- Shift Type -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="shift-type">Shift Type</label>
                                                <select class="form-control select2" name="shift_type" id="shift-type" data-placeholder="Shift Type">
                                                    <option value=""></option>
                                                    <option value="shift" data-type="shift" {{$data->shift_type=='shift'?'selected':''}}>Shift</option>
                                                    <option value="non_shift" data-type="non_shift" {{$data->shift_type=='non_shift'?'selected':''}}>Non-Shift</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Shift -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="shift">Shift</label>
                                                <select class="form-control" name="shift" id="shift" data-placeholder="Shift" {{$data->shift_type=='shift'?'disabled':''}}>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- Status -->
                                    <div class="form-group">
                                        <label class="control-label" for="status">Status</label>
                                        <select class="form-control select2" name="status" id="status" data-placeholder="Status">
                                            <option value="1" {{$data->status==1?'selected':''}}>Active</option>
                                            <option value="0" {{$data->status!=1?'selected':''}}>Non-Active</option>
                                        </select>
                                    </div>
                                    <!-- Join Date -->
                                    <div class="form-group">
                                        <label class="control-label" for="join-date">Join Date</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input class="form-control datepicker text-right" type="text" name="join_date" id="join-date" placeholder="<?php echo date('d/m/Y') ?>" value="{{date('d/m/Y', strtotime($data->join_date))}}">
                                            <div class="input-group-append ml-2">
                                                <button type="button" class="btn btn-sm btn-secondary color-palette legitRipple text-sm reset-date" id="reset-date" style="margin:0;" data-target="join-date"><b><i class="fas fa-redo"></b></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Resign Date -->
                                    <div class="form-group">
                                        <label class="control-label" for="resign-date">Resign Date</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input class="form-control datepicker text-right" type="text" name="resign_date" id="resign-date" placeholder="<?php echo date('d/m/Y') ?>" value="{{$data->resign_date?date('d/m/Y', strtotime($data->resign_date)):''}}">
                                            <div class="input-group-append ml-2">
                                                <button type="button" class="btn btn-sm btn-secondary color-palette legitRipple text-sm reset-date" id="reset-date" style="margin:0;" data-target="resign-date"><b><i class="fas fa-redo"></b></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Salary -->
                                    <div class="form-group">
                                        <label class="control-label" for="base-salary">Base Salary</label>
                                        <input class="form-control input-price text-right" type="text" name="salary" id="base-salary" value="{{$data->salary?$data->salary:0}}" maxlength="15">
                                    </div>
                                    <!-- Is User -->
                                    <div class="form-group">
                                        <label class="control-label" for="user">User</label>
                                        <select class="form-control select2" name="user" id="user" data-placeholder="Yes/No" onchange="roleUser(this)">
                                            <option value="1" {{$data->user?'selected':''}}>Yes</option>
                                            <option value="0" {{$data->user==null?'selected':''}}>No</option>
                                        </select>
                                    </div>
                                    <div class="user-existed d-none">
                                        <div class="form-group">
                                            <label class="control-label" for="username">Username</label>
                                            <input class="form-control" type="text" name="username" id="username" placeholder="Username" value="{{ $data->user->username }}">
                                        </div>
                                        <div class="form-group role-select">
                                            <label class="control-label" for="role">Group</label>
                                            <select class="form-control select2" name="role" id="role" data-placeholder="Choose Group">
                                            </select>
                                        </div>
                                        <!-- Supervisor -->
                                        <div class="form-group spv-select">
                                            <label class="control-label" for="spv">Supervisor</label>
                                            <select class="form-control select2" name="spv" id="spv" data-placeholder="Choose Supervisor">
                                            </select>
                                        </div>
                                        <!-- Site -->
                                        <div class="form-group site-select">
                                            <label class="control-label" for="site">Site</label>
                                            <select class="form-control select2" name="site" id="site" data-placeholder="Choose Site">
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Payroll -->
                                    <div class="form-group">
                                        <label class="control-label" for="payroll">Payroll</label>
                                        <select class="form-control select2" name="payroll" id="payroll" data-placeholder="Yes/No">
                                            <option value="1" @if ($data->payroll == '1') selected @endif>Yes</option>
                                            <option value="0" @if ($data->payroll == '0') selected @endif>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
                                <b><i class="fas fa-save"></i></b>
                                Save
                            </button>
                            <a href="{{ route('employee.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-times"></i></b>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const roleUser = (a) => {
        var value = a.value;
        if (value == 1) {
            $('.user-existed').removeClass('d-none');
            return;
        }
        $('.user-existed').addClass('d-none');
    }
    $(function() {
        $('#user').trigger('change');
        $('.select2').select2({
            allowClear: true
        });

        $(".input-price").priceFormat({
            prefix: '',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0,
            clearOnEmpty: false
        });

        $(document).on("input keydown keyup mousedown mouseup select contextmenu drop", ".numberfield", function() {
            if (/^\d*$/.test(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            }
        });

        $('.upload-image').find('input:file').change(function() {
            var image = $(this).closest('.upload-image').find('img');
            var remove = $(this).closest('.upload-image').find('.remove');
            var fileReader = new FileReader();
            fileReader.onload = function() {
                var data = fileReader.result;
                image.attr('src', data);
                remove.css('display', 'block');                
            };            
            fileReader.readAsDataURL($(this).prop('files')[0]);
            $(this).closest('.upload-image').find('.has-image').val(1);
        });

        $('.upload-image').on('click', '.remove', function() {
            var image = $(this).closest('.upload-image').find('img');
            var file = $(this).closest('.upload-image').find('input:file');            
            file.val('');
            image.attr('src', "{{asset('assets/img/no-image.png')}}");
            $(this).css('display', 'none');
            $(this).closest('.upload-image').find('.has-image').val(0);
        });

        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            timePicker: false,
            timePickerIncrement: 30,
            drops: 'auto',
            opens: 'center',
            locale: {
                format: 'DD/MM/YYYY'
            },
            autoUpdateInput: false
        });

        $('.datepicker').on('hide.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY'));

            var elementId = $(this).attr('id');
            if (elementId == 'resign-date') {
                employeeStatus(elementId);
            }
        });

        $("#city").select2({
            ajax: {
                url: "{{route('region.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {                    
                    var province_id = $('#province').find('option:selected').val();
                        province_id = province_id?province_id:'';                    
                    return {
                        province_id : province_id,
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name,
                            province_id : item.province_id,
                            province : item.province.name?item.province.name:''
                        });
                    });                    
                    return {
                        results: option,
                        more: more,
                    };
                }
            },
            allowClear: true,
        }).on('select2:select', function(e){
            var data = e.params.data;
            if(data.province_id){
                $('#province').select2('trigger','select', {
                    data : {
                        id : data.province_id,
                        text : `${data.province}`
                    }
                });
            }else{
                console.log({error : 'Undefined province id'});
            }
        }).on('select2:clearing',function(){
            $('#province').val(null).trigger('change');
        });                  

        $("#province").select2({
            ajax: {
                url: "{{route('province.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    return {                        
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name,
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:clearing',function(){
            $('#city').val(null).trigger('change');
        }).on('select2:close', function(e){
            var data = $(this).find('option:selected').val();
            var city = $('#city').select2('data');            
            
            if(city[0] && city[0].province_id != data){
                $('#city').val(null).trigger('change');                
            }
        });

        $("#shift").select2({
            ajax: {
                url: "{{route('workingshift.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    return {
                        name: params.term,
                        page: params.page,
                        type: 'non_shift',
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.shift_name + ' | ' + item.time_in + ' - ' + item.time_out
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        });

        $("#role").select2({
            ajax: {
                url: "{{route('role.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    return {
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        });

        $("#site").select2({
            ajax: {
                url: "{{route('site.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    return {
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        });

        $("#spv").select2({
            ajax: {
                url: "{{route('user.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    return {
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        });

        @if ($data->user)
            @if ($data->user->roles->count() > 0)
            $('#role').select2('trigger', 'select', {
                data: {
                    id: {{ $data->user->roles->first()->id }},
                    text: "{{ $data->user->roles->first()->name }}",
                }
            });
            @endif
            @if ($data->user->sites->count() > 0)    
            $('#site').select2('trigger', 'select', {
                data: {
                    id: {{ $data->user->sites->first()->id }},
                    text: "{{ $data->user->sites->first()->name }}",
                }
            });
            @endif
            @if ($data->user->spv)
                $('#spv').select2('trigger', 'select', {
                    data: {
                        id: {{ $data->user->spv->id }},
                        text: "{{ $data->user->spv->name }}",
                    }
                });
            @endif
        @endif

        @if($data->region)
        $('#city').select2('trigger', 'select', {
            data: {
                id: {{$data->region->id}},
                text: "{{$data->region->name}}",
                province_id : {{$data->region->province_id}},
                province : '{{$data->province->name}}'
            }
        });
        @endif

        @if($data->province)
        $('#province').select2('trigger', 'select', {
            data: {
                id: {{$data->province->id}},
                text: "{{$data->province->name}}"
            }
        });
        @endif

        @if($data->workingshift)
        $('#shift').select2('trigger', 'select', {
            data: {
                id: {{$data->workingshift->id}},
                text: "{{$data->workingshift->shift_name}}"
            }
        });
        @endif

        $('#shift-type').on('select2:select', function(e) {
            var type = $(this).find('option:selected').data('type');
            if (type == 'shift') {
                $('#shift').attr('disabled', true);
                $('#shift').val(null).trigger('change');
            } else {
                $('#shift').removeAttr('disabled');
            }
        });

        $('#shift-type').on('select2:clearing', function(e) {
            $('#shift').attr('disabled', true);
            $('#shift').val(null).trigger('change');
        });

        $('.reset-date').on('click', function() {
            var target = $(this).data('target');
            $('.datepicker[id=' + target + ']').val('');
            employeeStatus();
        });

        $("#form").validate({
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true
                },
                nid: {
                    required: true
                },
                address: {
                    required: true
                },
                city: {
                    required: true
                },
                province: {
                    required: true
                },
                shift_type: {
                    required: true
                },
                join_date: {
                    required: true
                },
                salary: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "This field is required.",
                },
                email: {
                    required: "This field is required.",
                },
                nid: {
                    required: "This field is required.",
                },
                address: {
                    required: "This field is required.",
                },
                city: {
                    required: "This field is required.",
                },
                province: {
                    required: "This field is required.",
                },
                shift_type: {
                    required: "This field is required.",
                },
                join_date: {
                    required: "This field is required.",
                },
                salary: {
                    required: "This field is required.",
                }
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group .controls').append(error);

                if (element.is(':file')) {
                    error.insertAfter(element.parent().parent().parent());
                } else
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else
                if (element.attr('type') == 'checkbox') {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function() {
                var post        = new FormData($('#form')[0]),
                    joinDate    = $('#form').find('input[name=join_date]').val(),
                    resignDate  = $('#form').find('input[name=resign_date]').val();                    

                    post.append('date_join',changeDateFormat(joinDate));
                    post.append('date_resign',changeDateFormat(resignDate));

                $.ajax({
                    url: $('#form').attr('action'),
                    method: 'post',
                    data: post,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function() {
                        blockMessage('body', 'Please Wait . . . ', '#fff');
                    }
                }).done(function(response) {                    
                    $('body').unblock();
                    console.log({response :response});
                    if (response.status) {
                        toastr.success('Data has been updated.');
                        document.location = "{{route('employee.index')}}";
                    } else {
                        toastr.warning(`${response.message}`);
                    }
                    return;
                }).fail(function(response) {
                    $('body').unblock();
                    var response = response.responseJSON,
                        message  = response.message?response.message:'Failed to insert data.';

                    toastr.warning(message);
                    console.log({
                        errorMessage: message
                    });
                })
            }
        });
    });

    function employeeStatus(elementId) {
        var data = {
            id: 1,
            text: 'Active'
        };
        if (elementId == 'resign-date') {
            data = {
                id: 0,
                text: 'Non-Active'
            };
        }

        $('#status').select2('trigger', 'select', {
            data: data
        });
    }
    
    function changeDateFormat(date) {
        var newdate = '';
        if(date){
            var piece = date.split('/');
                newdate = piece[2]+'-'+piece[1]+'-'+piece[0];
        }        

        return newdate;
    }
</script>
@endsection