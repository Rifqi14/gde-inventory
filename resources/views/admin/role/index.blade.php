@extends('admin.layouts.app')
@section('title', 'Role')
@section('stylesheets')

@endsection

@section('button')
<button type="button" id="add-role" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{route('role.create')}}')">
    <b><i class="fas fa-plus"></i></b> Create
</button>
<button type="button" id="filter-role" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
    <b><i class="fas fa-search"></i></b> Filter
</button>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Role
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Role</li>
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
                        <table id="table-role" class="table table-striped datatable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5">No.</th>
                                    <th width="100">Name</th>
                                    <th width="100">Display Name</th>
                                    <th width="20" class="text-center">District Access</th>
                                    <th width="20" class="text-center">Default</th>
                                    <th width="20" class="text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="form-filter">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-bold">Filter Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-search" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Name</label>
                                <input type="text" name="code" class="form-control" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Display Name</label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="Display Name">
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple">
                            <b><i class="fas fa-search"></i></b>
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

@section('scripts')
<script>
    function filter() {
        $('#form-filter').modal('show');
    }
    function edit(id) {
        document.location = '{{route('role.index')}}/' + id + '/edit';
    }
    function view(id) {
        document.location = '{{route('role.index')}}/' + id;
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
            order: [[ 1, "asc" ]],
            ajax: {
                url: "{{route('role.read')}}",
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
                    orderable: false,targets:[0,5]
                },
                { className: "text-right", targets: [0] },
                { className: "text-center", targets: [3,4,5] },
                {
                    width: "5%",
                    render: function ( data, type, row ) {
                        return row.no;
                    },targets: [0]
                },
                { 
                    width: "15%",
                    render: function ( data, type, row ) {
                        if (row.data_manager == 1) {
                            teks = 'Each Unit';
                        } else {
                            teks = 'Centralized';
                        }
                        return `<span class="badge bg-primary text-sm">${teks}</span>`

                    },targets: [3]
                },
                { 
                    width: "10%",
                    render: function ( data, type, row ) {
                        if (row.guest == 1) {
                            return `<span class="badge bg-success"><i class="fa fa-check"></i></span>`
                        } else {
                            return `<span class="badge bg-red"><i class="fa fa-times"></i></span>`
                        }
                    },targets: [4]
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
                          <i class="far fa-trash-alt"></i> Delete Data
                        </a>
                      </div>
                    </div>`;
                    },targets: [5]
                }
            ],
            columns: [
                { data: "no" },
                { data: "code" },
                { data: "name" },
                { data: "data_manager" },
                { data: "guest" },
                { data: "no" },
            ]
        });

        $('#form-search').submit(function (e) {
            e.preventDefault();
            dataTable.draw();
            $('#form-filter').modal('hide');
        })

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
                message: 'Are you sure want to delete this role?',
                callback: function (result) {
                    if (result) {
                        var data = {
                            _token: "{{ csrf_token() }}"
                        };
                        $.ajax({
                            url: `{{route('role.index')}}/${id}`,
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
        })
    });
</script>
@endsection