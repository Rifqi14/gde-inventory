@extends('admin.layouts.app')
@section('title',@$menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            {{ @$menu_name }}
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{ @$parent_name }}</li>
            <li class="breadcrumb-item">{{ @$menu_name }}</li>
            <li class="breadcrumb-item">Create</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form class="form-horizontal no-margin" action="{{$url}}" id="form">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <span class="title">
                                        <hr>
                                        <h5 class="text-dark text-bold text-md">Request Information</h5>
                                    </span>
                                    <div class="row mt-4">
                                        <!-- Vehicle -->
                                        <div class="form-group col-6">
                                            <label class="control-label" for="vehicle">Vehicle</label>
                                            <select class="form-control" name="vehicle" id="vehicle" data-placeholder="Vehicle" required></select>
                                        </div>
                                        <!-- Borrower -->
                                        <div class="form-group col-6">
                                            <label class="control-label" for="borrower">Borrower</label>
                                            <input type="text" class="form-control" value="{{Auth::guard('admin')->user()->name}}" readonly>
                                            <input type="hidden" name="issued_by" value="{{Auth::guard('admin')->user()->id}}">
                                        </div>
                                        <!-- Date Request -->
                                        <div class="form-group col-6">
                                            <label class="control-label" for="date-borrowed">Date</label>
                                            <input class="form-control datepicker" type="datepicker" name="date_borrowed" id="date-borrowed" placeholder="Start Date - Finish Date" autocomplete="off" required>
                                        </div>
                                        <!-- Status -->
                                        <div class="form-group col-6">
                                            <label class="control-label" for="status-request">Status</label>
                                            <select class="form-control select2" name="status" id="status-request" disabled>
                                                @foreach (config('enums.global_status') as $key => $item)
                                                <option value="{{ $key }}" @if ($key=='DRAFT' ) selected @endif>{{ $item['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <span class="title">
                                        <hr>
                                        <h5 class="text-dark text-bold text-md">Other</h5>
                                    </span>
                                    <!-- Notes -->
                                    <div class="form-group">
                                        <label class="control-label" for="notes">Notes</label>
                                        <textarea class="form-control summernote" name="notes" id="notes" rows="4" placeholder="Notes"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
                                <b><i class="fas fa-save"></i></b>
                                Save
                            </button>
                            <a href="{{ route('requestvehicle.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-times"></i></b>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    $(function() {
        $('.select2').select2({
            allowClear: true
        });

        $('.datepicker').daterangepicker({
            timePicker: false,
            timePickerIncrement: 30,
            drops: 'auto',
            opens: 'center',
            locale: {
                format: 'DD/MM/YYYY'
            },
            startDate: moment(new Date()),
            endDate: moment(new Date()).add(6, 'days')
        });

        $('.summernote').summernote({
            height: 145,
            toolbar: [
                ['style', ['style']],
                ['font-style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['font', ['fontname']],
                ['font-size', ['fontsize']],
                ['font-color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['misc', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $("#vehicle").select2({
            ajax: {
                url: "{{route('vehicle.select')}}",
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
                            text: `${item.vehicle_name} | ${item.police_number}`,
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:select', function(e){
            var data  = e.params.data,
                date  = $('#form').find('input[name=date_borrowed]').data('daterangepicker'),
                startDate = date.startDate.format('DD/MM/YYYY'),
                endDate   = date.endDate.format('DD/MM/YYYY');
                checkDateRequest(data.id);          
        });

        $("#borrower").select2({
            ajax: {
                url: "{{route('employee.select')}}",
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
            multiple: true,
            allowClear: true,
        });

        $('#date-borrowed').on('apply.daterangepicker',function(ev,picker){
            var vehicle_id = $('select[name=vehicle]').select2('val');
            if(vehicle_id){
                checkDateRequest(vehicle_id);
            }
        });

        $("#form").validate({
            rules: {
                vehicle: {
                    required: true,
                },
                borrower: {
                    required: true
                },
                date_borrowed: {
                    required: true
                },
            },
            messages: {
                vehicle: {
                    required: "This field is required.",
                },
                borrower: {
                    required: "This field is required.",
                },
                date_borrowed: {
                    required: "This field is required.",
                },
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
                var post = new FormData($('#form')[0]),
                    date = $('#form').find('input[name=date_borrowed]').data('daterangepicker'),
                    startDate = changeDateFormat(date.startDate.format('DD/MM/YYYY')),
                    endDate = changeDateFormat(date.endDate.format('DD/MM/YYYY'));                    

                // $.each($('#borrower').find('option:selected'), function(index, value) {
                //     var employee_id = $(this).val();
                //     borrowers.push({
                //         employee_id: employee_id
                //     });
                // });

                post.append('status', $('#form').find('select[name=status]').val());                
                post.append('startdate', startDate);
                post.append('finishdate', endDate);

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
                    console.log({
                        response: response
                    });
                    if (response.status) {
                        toastr.success('Data has been saved.');
                        document.location = "{{route('requestvehicle.index')}}";
                    } else {
                        toastr.warning(`${response.message}`);
                    }
                    return;
                }).fail(function(response) {
                    $('body').unblock();
                    var response = response.responseJSON,
                        message = response.message ? response.message : 'Failed to insert data.';

                    toastr.warning(message);
                    console.log({
                        errorMessage: message
                    });
                })
            }
        });
    });

    function changeDateFormat(date) {
        var newdate = '';
        if (date) {
            var piece = date.split('/');
            newdate = piece[2] + '-' + piece[1] + '-' + piece[0];
        }

        return newdate;
    }

    function checkDateRequest(vehicle_id) {
        if(vehicle_id){
            var date      = $('#form').find('input[name=date_borrowed]').data('daterangepicker');
                startDate = changeDateFormat(date.startDate.format('DD/MM/YYYY')),
                endDate   = changeDateFormat(date.endDate.format('DD/MM/YYYY'));
            $.ajax({
                type: "GET",
                url: "{{route('requestvehicle.daterequest')}}",
                data: {
                    vehicle_id     : vehicle_id,
                    start_request  : startDate,
                    finish_request : endDate
                },
                dataType: "json",
                success: function (response) {                                        
                    if(response.status == false){
                        $('#form').find('input[name=date_borrowed]').val('');   
                        toastr.warning(response.message);

                        return false;
                    }
                }
            });
        }
    }
</script>
@endsection