@extends('admin.layouts.app')
@section('title','Product Borrowing')

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Product Borrowing
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Inventory</li>
            <li class="breadcrumb-item">Product Borrowing</li>
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
                        <button type="button" id="filter-product-borrowing" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple float-right ml-1" onclick="filter()">
                            <b><i class="fas fa-search"></i></b> Search
                        </button>
                        @endif
                        @if(in_array('create',$actionmenu))
                        <button type="button" id="add-product-borrowing" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple float-right ml-1" onclick="windowLocation('{{route('productborrowing.create')}}')">
                            <b><i class="fas fa-plus"></i></b> Create
                        </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="suppDocumentTab" role="tablist">
                            <li class="nav-item">
                                <button type="button" onclick="activeTab('general')" class="nav-link active pl-4 pr-4" id="general-tab" data-toggle="tab" data-target="#general" role="tab" aria-controls="document" aria-selected="true"><b>General</b></button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link pl-4 pr-4" onclick="activeTab('approved')" id="approved-tab" data-toggle="tab" data-target="#approved" type="button" role="tab" aria-controls="photo" aria-selected="false"><b>Approved</b></button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link pl-4 pr-4" onclick="activeTab('archived')" id="archived-tab" data-toggle="tab" data-target="#archived" type="button" role="tab" aria-controls="photo" aria-selected="false"><b>Archive</b></button>
                            </li>
                        </ul>
                        <div class="tab-content" id="dataTabContent">
                            <!-- GENERAL TABLE -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-general" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="10" class="text-center">No</th>
                                                <th width="25">Borrowing Number</th>
                                                <th width="25" class="text-center">Borrowing Date</th>
                                                <th width="50">Issued By</th>
                                                <th width="20" class="text-center">Status</th>
                                                <th width="10" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- APPROVED TABLE -->
                            <div class="tab-pane fade show" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-approved" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="10" class="text-center">No</th>
                                                <th width="25">Borrowing Number</th>
                                                <th width="25" class="text-center">Borrowing Date</th>
                                                <th width="50">Issued By</th>
                                                <th width="20" class="text-center">Status</th>
                                                <th width="10" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- ARCHIVED TABLE -->
                            <div class="tab-pane fade show" id="archived" role="tabpanel" aria-labelledby="archived-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-archived" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="10" class="text-center">No</th>
                                                <th width="25">Borrowing Number</th>
                                                <th width="25" class="text-center">Borrowing Date</th>
                                                <th width="50">Issued By</th>
                                                <th width="20" class="text-center">Status</th>
                                                <th width="10" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="add-filter" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="filter-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Borrowing Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-search" method="POST">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="borrowing-number">Borrowing Number</label>
                                    <input class="form-control" type="text" name="borrowing_number" id="borrowing-number" placeholder="Enter Borrowing Number">
                                </div>
                                <div class="form-group">
                                    <label for="status" class="control-label">Status</label>
                                    <div class="status-general">
                                        <select class="form-control select2 general-status" id="general-status" name="status" data-placeholder="Choose Status">
                                            <option value=""></option>
                                            <option value="draft">Draft</option>
                                            <option value="waiting">Waiting</option>
                                        </select>
                                    </div>
                                    <div class="status-other" style="display: none;">
                                        <select class="form-control select2 other-status" name="status" id="other-status" disabled>
                                            <option value="approved">Approved</option>
                                            <option value="archived">Archived</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="borrowig-date">Date</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="datepicker" class="form-control datepicker text-right" name="dates" id="dates">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="issued-by">Issued By</label>
                                    <input class="form-control" type="text" name="issued_by" id="issued-by" placeholder="Enter Issued Name">
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
    var tab = 'general';

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
            startDate: moment().startOf('month'),
            endDate: moment().endOf('month')
        });

        generalTable = $('#table-general').DataTable({
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
                url: "{{route('productborrowing.read')}}",
                type: "GET",
                data: function(data) {
                    var number = $('#form-search').find('#borrowing-number').val(),
                        dates = $('#form-search').find('#dates').data('daterangepicker'),
                        startDate = dates.startDate.format('YYYY-MM-DD'),
                        finishDate = dates.endDate.format('YYYY-MM-DD'),
                        issuedby = $('#form-search').find('#issued-by').val(),
                        status = $('#general-status').select2('val');

                    data.number = number;
                    data.start_date = startDate;
                    data.finish_date = finishDate;
                    data.issuedby = issuedby;
                    data.status = status;
                }
            },
            columnDefs: [{
                    orderable: false,
                    targets: [0, 5]
                },
                {
                    className: "text-center",
                    targets: [0, 2, 4]
                },
                {
                    render: function(data, type, row) {
                        var badge = '',
                            status = row.status;
                        if (status == 'draft') {
                            badge = 'bg-gray';
                        } else if (status == 'waiting') {
                            badge = 'badge-warning';
                        } else if (status == 'approved') {
                            badge = 'badge-info';
                        }

                        return `<span class="badge ${badge} text-sm" style="text-transform: capitalize;">${status}</span>`;

                    },
                    targets: [4]
                },
                {
                    render: function(data, type, row) {
                        var button = '';
                        // update
                        if (actionmenu.indexOf('update') > 0) {
                            button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
                                        <i class="far fa-edit"></i>Update Data
                                    </a>`;
                        }
                        // delete
                        if (actionmenu.indexOf('delete') > 0) {
                            button += `<a class="dropdown-item" href="javascript:void(0);" onclick="destroy(${row.id},'general')">
                                        <i class="fa fa-trash-alt"></i> Delete Data
                                    </a>`;
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
                    data: "borrowing_number"
                },
                {
                    data: "borrowing_date"
                },
                {
                    data: "issued"
                },
                {
                    data: "status"
                }
            ]
        });

        approvedTable = $('#table-approved').DataTable({
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
                url: "{{route('productborrowing.read')}}",
                type: "GET",
                data: function(data) {
                    var number      = $('#form-search').find('#borrowing-number').val(),
                        dates       = $('#form-search').find('#dates').data('daterangepicker'),
                        startDate   = dates.startDate.format('YYYY-MM-DD'),
                        finishDate  = dates.endDate.format('YYYY-MM-DD'),
                        issuedby    = $('#form-search').find('#issued-by').val(),
                        status      = $('#other-status').select2('val');

                    data.number      = number;
                    data.start_date  = startDate;
                    data.finish_date = finishDate;
                    data.issuedby    = issuedby;
                    data.status      = status;
                }
            },
            columnDefs: [{
                    orderable: false,
                    targets: [0, 5]
                },
                {
                    className: "text-center",
                    targets: [0, 2, 4]
                },
                {
                    render: function(data, type, row) {
                        var badge = '',
                            status = row.status;
                        if (status == 'draft') {
                            badge = 'bg-gray';
                        } else if (status == 'waiting') {
                            badge = 'badge-warning';
                        } else if (status == 'approved') {
                            badge = 'badge-info';
                        }

                        return `<span class="badge ${badge} text-sm" style="text-transform: capitalize;">${status}</span>`;

                    },
                    targets: [4]
                },
                {
                    render: function(data, type, row) {
                        var button = `<a class="dropdown-item" href="javascript:void(0);" onclick="archive(${row.id})">
                                        <i class="far fa-eye"></i>View Data
                                    </a>`;                     
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
                    data: "borrowing_number"
                },
                {
                    data: "borrowing_date"
                },
                {
                    data: "issued"
                },
                {
                    data: "status"
                }
            ]
        });

        archivedTable = $('#table-archived').DataTable({
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
                url: "{{route('productborrowing.readarchived')}}",
                type: "GET",
                data: function(data) {
                    data.status = 'archived';
                }
            },
            columnDefs: [{
                    orderable: false,
                    targets: [0, 5]
                },
                {
                    className: "text-center",
                    targets: [0, 2, 4]
                },
                {
                    render: function(data, type, row) {
                        return `<span class="badge bg-red text-sm" style="text-transform: capitalize;">Archived</span>`;

                    },
                    targets: [4]
                },
                {
                    render: function(data, type, row) {
                        var button = `<a class="dropdown-item" href="javascript:void(0);" onclick="archive(${row.id})">
                                        <i class="far fa-eye"></i>View Data
                                    </a>`;                     
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
                    data: "borrowing_number"
                },
                {
                    data: "borrowing_date"
                },
                {
                    data: "issued"
                },
                {
                    data: "status"
                }
            ]
        });

        $('#form-search').submit(function(e) {
            e.preventDefault();
            if (tab == 'general') {
                generalTable.draw();
            } else if (tab == 'approved') {
                approvedTable.draw();
            } else {
                archivedTable.draw();
            }
            $('#add-filter').modal('hide');
        });
    });

    function destroy(id, table) {
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
            title: 'Archive data?',
            message: 'Are you sure want to archive this data?',
            callback: function(result) {
                if (result) {
                    var data = {
                        _token: "{{ csrf_token() }}"
                    };
                    $.ajax({
                        url: `{{url('admin/productborrowing/delete')}}/${id}`,
                        dataType: 'json',
                        data: data,
                        type: 'GET',
                        beforeSend: function() {
                            blockMessage('#content', 'Loading', '#fff');
                        }
                    }).done(function(response) {
                        $('#content').unblock();

                        var dataTable = '';

                        if (table == 'general') {
                            dataTable = generalTable;
                        } else {
                            dataTable = approvedTable;
                        }

                        if (response.status) {
                            toastr.success(response.message);
                            dataTable.ajax.reload(null, false);
                            archivedTable.draw();
                        } else {
                            toastr.warning(response.message);
                        }
                    }).fail(function(response) {
                        var response = response.responseJSON;
                        $('#content').unblock();
                        toastr.warning(response.message);
                    })
                }
            }
        });
    }

    function edit(id) {
        document.location = `{{ route('productborrowing.index') }}/${id}/edit`;
    }

    const show = (id) => {
        document.location = `{{route('productborrowing.index')}}/${id}`;
    }

    const archive = (id) => {
        document.location = `{{url('admin/productborrowing/archive')}}/${id}`;
    }

    const filter = () => {
        if (tab == 'general') {
            $('.status-general').show();
            $('.status-other').hide();
        } else {
            var state = {};
            if (tab == 'approved') {
                state = {
                    id: 'approved',
                    text: 'Approved'
                };
            } else if (tab == 'archived') {
                state = {
                    id: 'archived',
                    text: 'Archived'
                };
            }

            $('.other-status').select2('trigger', 'select', {
                data: state
            });
            $('.status-other').show();
            $('.status-general').hide();
        }

        $('#add-filter').modal('show');
    }

    const activeTab = (tabs) => {
        tab = tabs;
    }
</script>
@endsection