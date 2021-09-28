@extends('admin.layouts.app')
@section('title', 'Purchasing')
@section('stylesheets')

@endsection

@section('button')
<button type="button" id="add-role" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{route('purchasing.create')}}')">
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
            Purchasing
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Procurment</li>
            <li class="breadcrumb-item">Purchasing</li>
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
                        <table id="table-purchasing" class="table table-striped datatable" width="100%">
                            <thead>
                                <tr>
                                    <th width="20" class="text-left">Number</th>
                                    <th width="50" class="text-left">Subject</th>
                                    <th width="20" class="text-center">Est Value</th>
                                    <th width="20" class="text-left">Rule</th>
                                    <th width="20" class="text-right">Date</th>
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
    function edit(id) {
    document.location = '{{route('purchasing.index')}}/' + id + '/edit';
    }
    function view(id) {
        document.location = '{{route('purchasing.index')}}/' + id;
    }

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
        order: [[ 0, "asc" ]],
        ajax: {
            url: "{{route('purchasing.read')}}",
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
                orderable: false,targets:[5]
            },
            { className: "text-right", targets: [2] },
            { className: "text-center", targets: [3,4,5] },
            {
                render: function ( data, type, row ) {
                    return `<a href="{{ url('/') }}admin/purchasing/${row.id}">
                        <div class="text-md text-info text-bold">
                            ${row.number}
                        </div>
                    </a>`;
                },
                targets: [0]
            },
            {
                render: function ( data, type, row ) {
                    return ` <font class="text-md text-bold">${row.subject}</font>`;
                },
                targets: [1]
            },
            {
                render: function ( data, type, row ) {
                    return ` <font class="text-md">${row.est_value}</font>`;
                },
                targets: [2]
            },
            {
                render: function ( data, type, row ) {
                    return `<font class="text-md text-bold">
                      ${(row.rule).toUpperCase()}
                    </font>`;
                },
                targets: [3]
            },
            {
                width: "10%",
                render: function ( data, type, row ) {
                return `<div class="btn-group">
                  <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-bars"></i>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);" onclick="view(${row.id})">
                      <i class="far fa-eye"></i>View Data
                    </a>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
                      <i class="far fa-edit"></i>Update Data
                    </a>
                    <a class="dropdown-item delete" href="javascript:void(0);" data-id="${row.id}">
                      <i class="fa fa-trash-alt"></i> Delete Data
                    </a>
                  </div>
                </div>`;
                },targets: [5]
            }
        ],
        columns: [
            { data: "number", width: 120 },
            { data: "subject", width:250 },
            { data: "est_value", width:100 },
            { data: "rule", width:50 },
            { data: "created_ats", width:50 },
            { data: "id", width:30 },
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
            message: 'Are you sure want to delete this site?',
            callback: function (result) {
                if (result) {
                    var data = {
                        _token: "{{ csrf_token() }}"
                    };
                    $.ajax({
                        url: `{{route('purchasing.index')}}/${id}`,
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
</script>
@endsection
