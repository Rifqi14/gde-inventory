@extends('admin.layouts.app')
@section('title', 'Stock Adjustment')
@section('stylesheets')

@endsection

@section('button')
<button type="button" id="add-role" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{route('stockadjustment.create')}}')">
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
            Stock Adjustment
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Inventory</li>
            <li class="breadcrumb-item">Stock Adjustment</li>
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
                        <table id="table-adjustment" class="table table-striped datatable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5">No.</th>
                                    <th width="100">Adjustment Number</th>
                                    <th width="100">Warehouse</th>
                                    <th width="20" class="text-center">Product</th>
                                    <th width="20" class="text-center">Status</th>
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
    $(function(){
        $(".select2").select2();
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
                url: "{{route('stockadjustment.read')}}",
                type: "GET",
                data:function(data){
                    // var name = $('#form-search').find('input[name=name]').val();
                    // var type = $('#form-search').find('select[name=type]').val();
                    // data.name = name;
                    // data.type = type;
                }
            },
            columnDefs:[
                {
                    orderable: false,targets:[0,5,3]
                },
                { className: "text-right", targets: [0] },
                { className: "text-center", targets: [4,5,3] },
                {
                    width: "5%",
                    render: function ( data, type, row ) {
                        return row.no;
                    },targets: [0]
                },
                {
                    render: function ( data, type, row ) {
                        return row.warehouse.name;
                    },targets: [2]
                },
                {
                    render: function ( data, type, row ) {
                        var it = " Item";
                        if(row.total_items > 1){
                            it = " Items";
                        }
                        return row.total_items+it;
                    },targets: [3]
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
                { data: "no" },
                { data: "adjustment_number", className: "text-bold" },
                { data: "warehouse_id" },
                { data: "total_items" },
                { data: "status" },
                { data: "id" },
            ]
        });
    });
</script>
@endsection