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
                    <div class="card-header">
                        @if(in_array('read',$actionmenu))
                        <button type="button" id="filter-request-vehicle" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple float-right ml-1" onclick="filter()">
                            <b><i class="fas fa-search"></i></b> Search
                        </button>
                        @endif
                        @if(in_array('create',$actionmenu))
                        <button type="button" id="add-request" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple float-right ml-1" onclick="windowLocation('{{route('requestvehicle.create')}}')">
                            <b><i class="fas fa-plus"></i></b> Create
                        </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-request-vehicle" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th width="100">Vehicle</th>
                                        <th width="100">Borrower</th>
                                        <th width="50">Date</th>
                                        <th class="text-center" width="50">Status</th>
                                        <th class="text-center" width="5%">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="add-filter" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="filter-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Vehicle Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-search" method="POST">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="vehicle-name">Vehicle</label>
                                    <select name="vehicle" id="vehicle" class="form-control" data-placeholder="Choose vehicle"></select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="borrower">Borrower</label>
                                    <select class="form-control select2" name="borrower" id="borrower" data-placeholder="Borrower"></select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="date-request">Date</label>
                                    <input class="form-control datepicker" type="datepicker" name="date_request" id="date-request" placeholder="Date" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="status">Status</label>
                                    <select class="form-control select2" name="status" id="status" data-placeholder="Status">
                                        <option value=""></option>
                                        <option value="1">Waiting</option>
                                        <option value="2">Approved</option>
                                        <option value="3">Complete</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-labeled  btn-danger btn-sm btn-sm btn-flat legitRipple" data-dismiss="modal" onclick="resetTable()"><b><i class="fas fa-times"></i></b> Cancel</button>
                <button type="submit" form="form-search" class="btn btn-labeled  btn-default  btn-sm btn-flat legitRipple"><b><i class="fas fa-search"></i></b> Search</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var actionmenu = JSON.parse('{!! json_encode($actionmenu) !!}');
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
            autoUpdateInput: false,
            drops: 'up',
            opens: 'center',
            locale: {
                cancelLabel: 'Clear'
            },
        });

        $('.datepicker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });
        
        $('.datepicker').on('cancel.daterangepicker', function(ev, picker) {            
            $(this).val('');
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
        });

        $('#form-search').find("#borrower").select2({
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
            allowClear: true,
        });

        dataTable = $('#table-request-vehicle').DataTable({
            processing: true,
            language: {
                processing: `<div class="p-2 text-center">
                            <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...
                            </div>`
            },
            serverSide: true,
            filter: false,
            responsive: true,
            lengthChange: false,
            order: [
                [1, "asc"]
            ],
            ajax: {
                url: "{{route('requestvehicle.read')}}",
                type: "GET",
                data: function(data) {
                    var vehicle = $('#vehicle').find('option:selected').val(),
                        plate = $('#form-search').find('input[id=police-number]').val(),
                        date = $('#form-search').find('input[id=date-request]').data('daterangepicker'),
                        status = $('#form-search').find('select[id=status]').select2('val');
                        borrowers = $('#borrower').find('option:selected').val();                  

                    data.vehicle  = vehicle;
                    data.plate    = plate;
                    data.borrower = borrowers;
                    data.status   = status;
                    if ($('#form-search').find('#date-request').val()) {
                        data.startrequest = changeDateFormat(date.startDate.format('DD/MM/YYYY'));
                        data.finishrequest = changeDateFormat(date.endDate.format('DD/MM/YYYY'));
                    }

                }
            },
            columnDefs: [{
                    orderable: false,
                    targets: [0, 5]
                },
                {
                    className: "text-center",
                    targets: [0, 4, 5]
                },
                {
                    render: function(data, type, row) {
                        return `<p>${row.vehicle_name}<br><b>${row.police_number}</b></p>`;
                    },
                    targets: [1]
                },                
                {
                    render: function(data, type, row) {
                        var label = '',
                            status = row.status;

                        if (status == 1) {
                            label = '<span class="badge bg-warning text-sm">Waiting</span>';
                        } else if (status == 2) {
                            label = '<span class="badge bg-success text-sm">Approved</span>';
                        } else if (status == 3) {
                            label = '<span class="badge bg-info text-sm">Completed</span>';
                        } else if (status == 4) {
                            label = '<span class="badge bg-red text-sm">Rejected</span>';
                        }

                        return label;
                    },
                    targets: [4]
                },
                {
                    render: function(data, type, row) {
                        var button = '';

                            button += `<a class="dropdown-item" href="javascript:void(0);" onclick="show(${row.id})">
                                            <i class="fa fa-eye"></i> View Data
                                        </a>`;
                    
                        if(row.status < 3){
                            // update
                            if (actionmenu.indexOf('update') > 0) {
                                button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
                                            <i class="far fa-edit"></i>Update Data
                                        </a>`;
                            }
                            // delete
                            if (actionmenu.indexOf('delete') > 0) {
                                button += `<a class="dropdown-item" href="javascript:void(0);" onclick="destroy(${row.id})">
                                            <i class="fa fa-trash-alt"></i> Delete Data
                                        </a>`;
                            }
                        }                        
                        return `<div class="btn-group">
                                <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <div class="dropdown-menu">
                                    ${button}
                                </div>
                            </div>`;
                    },
                    targets: [5]
                }
            ],
            columns: [{
                    data: "no"
                },
                {
                    data: "vehicle_name"
                },
                {
                    data: "issued_name"
                },
                {
                    data: "date_request"
                },
                {
                    data: "status"
                }
            ]
        });

        $('#form-search').submit(function(e) {
            e.preventDefault();
            dataTable.draw();
            $('#add-filter').modal('hide');
        });
    });

    function filter() {
        $('#form-search').find('input[type=text]').val('');
        $('#form-search').find('.select2').val(null).trigger('change');
        $('#add-filter').modal('show');
    }

    function resetTable() {
        $('#form-search').find('input[type=text]').val('');
        $('#form-search').find('.select2').val(null).trigger('change');
        dataTable.draw();
    }

    function edit(id) {
        if (!id) {
            toastr.warning('Data not found.');
            console.log({
                errorMessage: 'Data not found because id is empty.'
            });
            return false;
        }

        window.location.href = `{{url('admin/requestvehicle/${id}/edit')}}`;
    }

    const show = (id) => {
        window.location = `{{url('admin/requestvehicle/${id}')}}`;
    }

    function destroy(id) {
        if (!id) {
            toastr.warning('Data not found.');
            console.log({
                errorMessage: 'Data not found because id is empty.'
            });
            return false;
        }

        bootbox.confirm({
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i>',
                    className: 'btn-primary btn-sm'
                },
                cancel: {
                    label: '<i class="fa fa-undo"></i>',
                    className: 'btn-default btn-sm'
                },
            },
            title: 'Delete data?',
            message: 'Are you sure want to delete this data?',
            callback: function (result) {
                if (result) {
                    var data = {
                        _token: "{{ csrf_token() }}"
                    };
                    $.ajax({
                        url: `{{route('requestvehicle.index')}}/${id}`,
                        dataType: 'json',
                        data: data,
                        type: 'DELETE',
                        beforeSend: function () {
                            blockMessage('#content', 'Loading', '#fff');
                        }
                    }).done(function (response) {
                        $('#content').unblock();
                        if (response.status) {
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
                            }
                            toastr.success(response.message);
                            dataTable.ajax.reload(null, false);
                        }else {
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
                            }
                            toastr.warning(response.message);
                        }
                    }).fail(function (response) {
                        var response = response.responseJSON;
                        $('#content').unblock();
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
                        }
                        toastr.warning(response.message);
                    })
                }
            }
        });
    }

    function changeDateFormat(date) {
        var newdate = '';
        if (date) {
            var piece = date.split('/');
            newdate = piece[2] + '-' + piece[1] + '-' + piece[0];
        }

        return newdate;
    }    
</script>
@endsection