@extends('admin.layouts.app')
@section('title', 'Contract')
@section('stylesheets')

@endsection

@section('button')
<button type="button" id="add-role" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{route('contract.create')}}')">
    <b><i class="fas fa-plus"></i></b> Create
</button>
{{-- <button type="button" id="filter-role" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
    <b><i class="fas fa-search"></i></b> Filter
</button> --}}
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Contract
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Procurment</li>
            <li class="breadcrumb-item">Contract</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table id="table-contract" class="table table-striped datatable" width="100%">
                            <thead>
                                <tr>
                                    <th width="20" class="text-left">Contract Number</th>
                                    <th width="50" class="text-left">Title</th>
                                    <th width="20" class="text-center">Signing Date</th>
                                    <th width="20" class="text-left">Contractor</th>
                                    <th width="20" class="text-right">Value</th>
                                    <th width="20" class="text-center">Status</th>
                                    <th width="20" class="text-center">Addendum</th>
                                    <th width="20" class="text-center">#</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    var group_code = '{{ $group_code }}';
    var watcher = JSON.parse('{!! json_encode(config('app.watcher')) !!}')

    function edit(id) {
        document.location = '{{route('contract.index')}}/' + id + '/edit';
    }
    function view(id) {
        document.location = '{{route('contract.index')}}/' + id;
    }
    function batch(id) {
        document.location = '{{route('contract.index')}}/batch/' + id;
    }
    $(function(){
        dataTable = $('.datatable').DataTable( {
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
            pageLength: 50,
            order: [[ 1, "asc" ]],
            ajax: {
                url: "{{route('contract.read')}}",
                type: "GET",
                data:function(data){
                    var code = $('#form-search').find('input[name=code]').val();
                    var name = $('#form-search').find('input[name=name]').val();
                    data.code = code;
                    data.name = name;
                }
            },
            columnDefs:[
                {
                    orderable: false,targets:[7]
                },
                { className: "text-right", targets: [4] },
                { className: "text-center", targets: [2,5,6,7] },
                {
                    render: function ( data, type, row ) {
                    return `<a href="javascript:void(0);" onclick="view(${row.contract_id})" style="line-height: 1;">
                    <div class="text-md text-info text-bold">
                        ${row.title}
                    </div>
                    </a>
                    <small>
                        ${row.date_created}
                    </small>`;
                    },targets: [0]
                },
                {
                    render: function ( data, type, row ) {
                    return `<b>${row.status_pub}</b>`;
                    },targets: [5]
                },
                {
                    width: "10%",
                    render: function ( data, type, row ) {
                    let check2 = $.inArray(group_code, watcher)
                    return `<div class="btn-group">
                    <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);" onclick="batch(${row.contract_id})">
                            <i class="fa fa-shipping-fast"></i>Batch Data
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="view(${row.contract_id})">
                            <i class="far fa-eye"></i>Detail Data
                        </a>
                        <a class="dropdown-item ${(row.progress==1 || check2 > -1) ? 'disabled':''}" href="javascript:void(0);" onclick="edit(${row.contract_id})">
                            <i class="far fa-edit"></i>Update Data
                        </a>
                        <a class="dropdown-item delete ${(row.status=='publish' || check2 > -1) ? 'disabled' : ''}" href="javascript:void(0);" data-id="${row.contract_id}">
                            <i class="fa fa-trash-alt"></i> Delete Data
                        </a>
                    </div>
                    </div>`;
                    },targets: [7]
                }
            ],
            columns: [
                { data: "number", width: 150 },
                { data: "title", width:150 },
                { data: "contract_signing_date", width:150 },
                { data: "contractor", width:100 },
                { data: "contract_value", width:100 },
                { data: "status_pub", width:150 },
                { data: "addendum", width:20 },
                { data: "no" },
            ]
        });

        $(document).on('click', '.delete', function () {
            var id = $(this).data('id');
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
                message: 'Are you sure want to delete this product?',
                callback: function (result) {
                    if (result) {
                        var data = {
                            _token: "{{ csrf_token() }}",
                            id: id
                        };
                        $.ajax({
                            url: `{{route('contract.index')}}`,
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
        });
    });
</script>
@endsection
