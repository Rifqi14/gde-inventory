@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-goods_receipt" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('product.create') }}')">
    <b>
        <i class="fas fa-plus"></i>
    </b> Create
</button>
@endif
@if (in_array('export', $actionmenu))
<button type="button" id="export-massal" class="btn btn-labeled text-sm btn-sm bg-olive btn-flat legitRipple" onclick="ekspor()">
    <b>
        <i class="fas fa-file-export"></i>
    </b> Export
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-goods_receipt" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
    <b>
        <i class="fas fa-search"></i>
    </b> Search
</button>
@endif
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            {{$menu_name}}
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{ $parent_name }}</li>
            <li class="breadcrumb-item">{{ $menu_name }}</li>
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
                                    <th width="100">Product Name</th>
                                    <th width="20" class="text-right">Stock</th>
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
                <h5 class="modal-title">Filter {{$menu_name}}</h5>
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

<div class="modal fade" id="modal-export">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export {{$menu_name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-export" autocomplete="off">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="period">Period</label>
                                <div class="input-group">
                                    <input type="text" name="period" id="period" class="form-control datepicker text-right" placeholder="Enter date" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Category</label>
                                <select name="category" data-placeholder="Product Category" style="width: 100%;" class="select2 form-control" id="category-lv-1">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-labeled  btn-danger btn-sm btn-sm btn-flat legitRipple" data-dismiss="modal" onclick="resetTable()"><b><i class="fas fa-times"></i></b> Cancel</button>
                        <button type="button" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="exportMass()">
                            <b><i class="fas fa-check"></i></b>
                            Submit
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
        dataTable = $('.datatable').DataTable({
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
            orderable: false,
            dom: "l",
            ajax: {
                url: "{{route('product.read')}}",
                type: "GET",
                data: function(data) {
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
            columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 2, ]
                },
                {
                    className: "text-center",
                    targets: [0, 2, 3]
                },
                {
                    width: "2%",
                    render: function(data, type, row) {
                        if (row.children && row.children.length > 0) {
                            return `<span><i class="fa fa-caret-square-down"></i></span>`;
                        }
                        return ``;
                    },
                    targets: [0]
                },
                {
                    render: function(data, type, row) {
                        if (row.isParent) {
                            return `<b>${row.name}</b>`;
                        }
                        return `${row.name}`;
                    },
                    targets: [1]
                },
                {
                    render: function(data, type, row) {
                        if (row.isParent) {
                            return ``;
                        }
                        return `${row.stock}`;
                    },
                    targets: [2]
                },
                {
                    width: "10%",
                    render: function(data, type, row) {
                        if (row.isParent) {
                            return ``;
                        }else{
                            var button = '';

                            if (actionmenu.indexOf('read') >= 0) {
                                button +=   `<a class="dropdown-item" href="javascript:void(0);" onclick="view(${row.id})">
                                                <i class="far fa-eye"></i>View Data
                                            </a>`;
                            }
                            if (actionmenu.indexOf('update') >= 0) {
                                button +=   `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
                                                <i class="far fa-edit"></i>Update Data
                                            </a>`;
                            }
                            if(actionmenu.indexOf('delete') >= 0){
                                button +=  `<a class="dropdown-item delete" href="javascript:void(0);" data-id="${row.id}">
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
                        }
                    },
                    targets: [3]
                }
            ],
            columns: [{
                    data: "id",
                    className: "treegrid-control"
                },
                {
                    data: "name",
                },
                {
                    data: "stock"
                },
                {
                    data: "id"
                },
            ]
        });

        $('#form-search').submit(function(e) {
            e.preventDefault();
            dataTable.draw();
            $('#form-filter').modal('hide');
        });

        $(document).on('click', '.delete', function() {
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
                callback: function(result) {
                    if (result) {
                        var data = {
                            _token: "{{ csrf_token() }}"
                        };
                        $.ajax({
                            url: `{{route('product.index')}}/${id}`,
                            dataType: 'json',
                            data: data,
                            type: 'DELETE',
                            beforeSend: function() {
                                blockMessage('#content', 'Loading', '#fff');
                            }
                        }).done(function(response) {
                            $('#content').unblock();
                            if (response.status) {
                                toastr.success(response.message);
                                dataTable.ajax.reload(null, false);
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
        });

        $("#product_category_id").select2({
            ajax: {
                url: "{{ route('productcategory.select') }}",
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
            escapeMarkup: function(text) {
                return text;
            }
        });

        $('#period').daterangepicker({
            timePicker: false,
            timePickerIncrement: 30,
            drops: 'auto',
            opens: 'center',
            ranges: {
                'Last 30 Days' : [moment().subtract(29, 'days'), moment()],
                'This Month'   : [moment().startOf('month'), moment().endOf('month')],
                'Last Month'   : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: 'MM/YYYY'
            },
            startDate: new moment().startOf('month'),
            endDate: new moment().endOf('month')
        });

        $('#form-export').find("#category-lv-1").select2({
            ajax: {
                url: "{{ route('productcategory.select') }}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    return {
                        name: params.term,
                        page: params.page,
                        parent_only :  true,
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
            escapeMarkup: function(text) {
                return text;
            }
        });
    });

    function filter() {
        $('#form-filter').modal('show');
    }

    function edit(id) {
        document.location = `{{route('product.index')}}/${id}/edit`;
    }

    function view(id) {
        document.location = `{{route('product.index')}}/${id}`;
    }

    const ekspor = () => {
        $('#modal-export').modal('show');
    }

    const exportMass = (data) => {
        var form        = $('#form-export');
        var period      = form.find('#period').data('daterangepicker'),
            start       = period.startDate.format('YYYY-MM'),
            end         = period.endDate.format('YYYY-MM'),
            categoryID  = form.find('#category-lv-1').find('option:selected').val();

        $.ajax({
            type: "GET",
            url: "{{route('product.export')}}",
            data: {
                _token      : "{{ csrf_token() }}",
                start       : start,
                end         : end,
                category_id : categoryID
            },
            dataType: "JSON",
            success: function (response) {
                console.log({data : response.data});
                if(response.status){
                    let download = document.createElement("a");
                    download.href = response.file;
                    document.body.appendChild(download);
                    download.download = response.document;
                    download.click();
                    download.remove();
                }else{
                    toastr.warning(reponse.message);
                }
            }
        });
    }
</script>
@endsection
