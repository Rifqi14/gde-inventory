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
            <li class="breadcrumb-item">Edit</li>
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
                    <form class="form-horizontal no-margin" action="{{route('requestvehicle.update', ['id' => $data->id])}}" id="form">
                        {{ csrf_field() }}
                        @method('PUT')
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
                                            <select class="form-control" name="vehicle" id="vehicle" data-placeholder="Vehicle" @if ($data->revise_status == 'NO') disabled @endif required></select>
                                            @if ($data->revise_status == 'NO')
                                            <input type="hidden" name="vehicle_id" value="{{ $data->vehicle_id }}">
                                            @endif
                                        </div>
                                        <!-- Borrower -->
                                        <div class="form-group col-6">
                                            <label class="control-label" for="borrower">Borrower</label>
                                            <input type="text" class="form-control" value="{{$data->issuedbyrequest?$data->issuedbyrequest->employees->name:'-'}}" readonly>
                                            <input type="hidden" name="issued_by" value="{{$data->issued_by}}">
                                        </div>
                                        <!-- Date Request -->
                                        <div class="form-group col-6">
                                            <label class="control-label" for="date-borrowed">Date</label>
                                            <input class="form-control datepicker" type="datepicker" name="date_borrowed" id="date-borrowed" placeholder="Start Date - Finish Date" autocomplete="off" @if ($data->revise_status == 'NO')
                                            disabled
                                            @endif required>
                                        </div>
                                        <!-- Status -->
                                        <div class="form-group col-6">
                                            <label class="control-label" for="status-request">Status</label>
                                            <select class="form-control select2" name="status" id="status-request" disabled>
                                                <option value="1" @if ($data->status == 1) selected @endif>Waiting</option>
                                                <option value="2" @if ($data->status == 2) selected @endif>Approved</option>
                                                <option value="3" @if ($data->status == 3) selected @endif>Completed</option>
                                                <option value="4" @if ($data->status == 4) selected @endif>Rejected</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 text-right">
                                            @if ($data->revise_status == 'NO')
                                            <a href="javascript:void(0)" onclick="revise()" class="btn btn-sm btn-warning color-palette legitRipple text-sm">
                                                <b><i class="fas fa-eye"></i></b>
                                            </a>
                                            @endif
                                            <a href="javascript:void(0)" onclick="reviselist()" class="btn btn-sm btn-secondary color-palette legitRipple text-sm">
                                                <b><i class="fas fa-list"></i></b>
                                            </a>
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
                                        <textarea class="form-control summernote" name="notes" id="notes" rows="4" placeholder="Notes">{{$data->remarks}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            @if($data->status == 1 && in_array('approval', $actionmenu))
                            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm" data-update="2">
                                <b><i class="fas fa-save"></i></b>
                                Approve
                            </button>
                            <button type="button" class="btn bg-red color-palette btn-labeled legitRipple text-sm btn-sm" data-update="4" onclick="rejectStatus(this)">
                                <b><i class="fas fa-window-close"></i></b>
                                Reject
                            </button>
                            @endif
                            @if($data->revise_status == 'YES')
                            <button type="button" class="btn bg-success color-palette btn-labeled legitRipple text-sm btn-sm" data-update="1" onclick="rejectStatus(this)">
                                <b><i class="fas fa-save"></i></b>
                                Save
                            </button>
                            @endif
                            @if($data->status == 2 && in_array('approval', $actionmenu))
                            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm" data-update="3">
                                <b><i class="fas fa-save"></i></b>
                                Complete
                            </button>
                            @endif
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
<div id="add-revise" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="revise-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Revise</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-revise" action="{{ route('requestvehicle.revise') }}" method="POST">
                    @csrf
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="request_vehicle_id" value="{{ $data->id }}">
                                <div class="form-group">
                                    <label class="control-label" for="revise_number">Revise Number</label>
                                    <input type="text" name="revise_number" class="form-control" readonly value="{{ $data->revise_number }}" placeholder="Revise Number">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="revise_reason">Revise Reason</label>
                                    <textarea class="form-control summernote" name="revise_reason" id="revise_reason" rows="4" placeholder="Revise Reason"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-labeled  btn-default btn-sm btn-sm btn-flat legitRipple" data-dismiss="modal"><b><i class="fas fa-times"></i></b> Cancel</button>
                <button type="submit" form="form-revise" class="btn btn-labeled  btn-danger  btn-sm btn-flat legitRipple"><b><i class="fas fa-save"></i></b> Revise</button>
            </div>
        </div>
    </div>
</div>
<div id="list-revise" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="revise-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Revise</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="revision-table" class="table table-striped table-striped" width="100%">
                        <thead>
                            <tr>
                                <th width="15%">Rev No.</th>
                                <th width="85%">Revise Reason</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
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
    const reviselist = () => {
        $('#list-revise').modal('show');
    }
    const revise = () => {
        $('#add-revise').modal('show');
    }
    $(function() {        
        var startDate = '{{$data->start_request}}',
            endDate    = '{{$data->finish_request}}';
        $('.select2').select2({
            allowClear: true
        });

        dataTable = $('#revision-table').DataTable({
            processing: true,
            language: {
                processing: `<div class="p-2 text-center">
                                <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...
                            </div>`
            },
            serverSide: true,
            aaSorting: [],
            filter: false,
            responsive: true,
            lengthChange: false,
            order: [[ 0, "asc" ]],
            ajax: {
                url: "{{route('logrevise.read')}}",
                type: "GET",
                data:function(data){
                    data.menu_route = `{{ $menu_route }}`;
                    data.data_id    = {{ $data->id }};
                }
            },
            columnDefs:[
                {
                    orderable: false,targets:[1]
                },
                { className: "text-center", targets: [0] },
                {
                    width: "15%",
                    render: function(data, type, row) {
                        return row.revise_number;
                    }, targets: [0]
                },
                {
                    width: "85%",
                    render: function(data, type, row) {
                        return row.revise_reason;
                    }, targets: [1]
                },
            ],
            columns: [{
                data: "revise_number"
            },
            {
                data: "revise_reason"
            }]
        });

        $('.datepicker').daterangepicker({
            timePicker: false,
            timePickerIncrement: 30,
            drops: 'auto',
            opens: 'center',
            locale: {
                format: 'DD/MM/YYYY'
            },
            startDate: startDate?startDate:moment(new Date()),
            endDate: endDate?endDate:moment(new Date()).add(6, 'days')
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
        @if ($data->revise_status == 'NO')
        $('.summernote').summernote('disable');
        @endif

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
        

        @if($data->borrowers)        
            var borrowers    = @json($data->borrowers);                                                           
            $.each(borrowers, function (index, val) {                  
                $('#borrower').select2('trigger','select',{
                    data : {
                        id : val.employee_id,
                        text : `${val.name}`
                    }
                });
            });            
        @endif        

        @if($data->vehiclerequest)
            $('#vehicle').select2('trigger','select',{
                data : {
                    id : {{$data->vehiclerequest->id}},
                    text : `{{$data->vehiclerequest->vehicle_name}} | {{$data->vehiclerequest->police_number}}`
                }
            });
        @endif

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
                var post      = new FormData($('#form')[0]),
                    date      = $('#form').find('input[name=date_borrowed]').data('daterangepicker'),
                    startDate = changeDateFormat(date.startDate.format('DD/MM/YYYY')),
                    endDate   = changeDateFormat(date.endDate.format('DD/MM/YYYY')),                    
                    status = $('#form').find('button[type=submit]').attr('data-update');

                // $.each($('#borrower').find('option:selected'), function(index, value) {
                //     var employee_id = $(this).val();
                //     borrowers.push({
                //         employee_id: employee_id
                //     });
                // });

                post.append('status', status);                
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
        $("#form-revise").validate({
            rules: {
                revise_number: {
                    required: true,
                },
                revise_reason: {
                    required: true
                },
            },
            messages: {
                revise_number: {
                    required: "This field is required.",
                },
                revise_reason: {
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
                $.ajax({
                    url: $('#form-revise').attr('action'),
                    method: 'post',
                    data: new FormData($('#form-revise')[0]),
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
                        $('#add-revise').modal('hide');
                        toastr.success(`${response.message}`);
                        location.reload();
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

        $(document).on('shown.bs.modal', '#list-revise', function () {
            dataTable.columns.adjust().draw();
        });
    });

    function rejectStatus(a) {
        var post      = new FormData($('#form')[0]),
            date      = $('#form').find('input[name=date_borrowed]').data('daterangepicker'),
            startDate = changeDateFormat(date.startDate.format('DD/MM/YYYY')),
            endDate   = changeDateFormat(date.endDate.format('DD/MM/YYYY')),            
            status    = $(a).data('update');

        // $.each($('#borrower').find('option:selected'), function(index, value) {
        //     var employee_id = $(this).val();
        //     borrowers.push({
        //         employee_id: employee_id
        //     });
        // });

        post.append('status', status);        
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
        }).done(function(response){
            $('body').unblock();
            if (response.status) {
                toastr.success('Data has been saved.');
                document.location   = `{{ route('requestvehicle.index') }}`;
            } else {
                toastr.warning(`${response.message}`);
            }
            return;
        }).fail(function(response){
            $('body').unblock();
            var response    = response.responseJSON;
                message     = response.message ? response.message : 'Failed to update data.';

            toastr.warning(message);
        })
    }

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
                    request_id     : {{$data->id}},
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
                },
                error : function(){
                    toastr('Failed to check date request');
                }
            });
        }
    }
</script>
@endsection