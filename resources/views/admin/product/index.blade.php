@extends('admin.layouts.app')
@section('title', 'Products')
@section('stylesheets')

@endsection

@section('button')
<button type="button" id="add-role" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{route('product.create')}}')">
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
            Products
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Products</li>
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
                        <table id="table-product" class="table table-striped datatable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5" class="text-center"></th>
                                    <th width="100">Nama Product</th>
                                    <th width="20" class="text-right">Stock</th>
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

<div class="modal fade" id="form-filter">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-bold">Filter Product</h5>
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
                                <input type="text" name="name" class="form-control" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Category</label>
                                <select name="product_category_id" data-placeholder="Product Category" style="width: 100%;" class="select2 form-control" id="product_category_id">
                                </select>
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
<script src='{{asset('assets/plugins/treegrid-datatable/dataTables.treeGrid.js')}}'></script>
<script>
    function filter() {
        $('#form-filter').modal('show');
    }
    function edit(id) {
        document.location = '{{route('product.index')}}/' + id + '/edit';
    }
    function view(id) {
        document.location = '{{route('product.index')}}/' + id;
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
            orderable: false,
            dom: "l",
            ajax: {
                url: "{{route('product.read')}}",
                type: "GET",
                data:function(data){
                    var name = $('#form-search').find('input[name=name]').val();
                    var product_category_id = $('#form-search').find('#product_category_id').val();
                    data.name = name;
                    data.product_category_id = product_category_id;
                }
            },
            treeGrid: {
                left: 10,
                expandIcon: `<span><i class="fa fa-caret-square-down"></i></span>`,
                collapseIcon: `<span><i class="fa fa-caret-square-up"></i></span>`,
            },
            columnDefs:[
                {
                    orderable: false,targets:[0,1,2,]
                },
                { className: "text-center", targets: [0,2,3] },
                {
                    width: "2%",
                    render: function ( data, type, row ) {
                        if (row.children && row.children.length > 0) {
                            return `<span><i class="fa fa-caret-square-down"></i></span>`;
                        }
                        return ``;
                    },targets: [0]
                },
                {
                    render: function ( data, type, row ) {
                        if (row.isParent) {
                            return `<b>${row.name}</b>`;
                        }
                        return `${row.name}`;
                    },targets: [1]
                },
                {
                    render: function ( data, type, row ) {
                        if (row.isParent) {
                            return ``;
                        }
                        return `${row.stock}`;
                    },targets: [2]
                },
                {   
                    width: "10%",
                    render: function ( data, type, row ) {
                        if (row.isParent) {
                            return ``;
                        }
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
                    },targets: [3]
                }
            ],
            columns: [
                { data: "id", className: "treegrid-control" },
                { data: "name", },
                { data: "stock" },
                { data: "id" },
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
                message: 'Are you sure want to delete this product?',
                callback: function (result) {
                    if (result) {
                        var data = {
                            _token: "{{ csrf_token() }}"
                        };
                        $.ajax({
                            url: `{{route('product.index')}}/${id}`,
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

        $( "#product_category_id" ).select2({
            ajax: {
                url: "{{ route('productcategory.select') }}",
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
            escapeMarkup: function (text) { return text; }
        });

    });
</script>
@endsection