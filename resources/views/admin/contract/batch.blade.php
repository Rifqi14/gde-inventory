@extends('admin.layouts.app')
@section('title', 'Contract')
@section('stylesheets')
<style>
    #input-list-checkbox .form-control {
        height: calc(1.9rem + 7.5px);
    }
    .row-form {
        padding-left:30px !important;
    }
    .other {
    display: none;
    padding-left:40px !important;
    }
    #form-idm {
    display: none;
    }
    .bootstrap-switch-handle-on.bootstrap-switch-success{
    width: 100px !important;
    }
    .bootstrap-switch-label{
    width: 126px !important;
    }
    .bootstrap-switch-handle-off.bootstrap-switch-default{
    width: 100px !important;
    }
    .html-viewer{
        position: relative;
        width: 100%;
        height: 100%;
        background: #e9ecef;
        border: 5px solid #fff;
        max-height: 300px;
        min-height: 300px;
        border-radius: .25rem;
        box-shadow: 0px 0px 4px -2px rgb(0 0 0 / 50%);
        padding: 10px;
        overflow: auto;
    }
    .detail-item{
        position: relative;
    }
    .detail-item i{
        position: relative;
        float: left;
        margin-bottom: 20px;
        margin-right: 7px;
        top: 3px;
    }
    .detail-item label{
        
    }
    .detail-item h6{
        
    }
    .transparent-input{

    }
    span.tag{
        color: #fff;
        background-color: #424776 !important;
        border-color: #676992 !important;
        border-radius: 4px;
        padding: 1px 5px;
    }
    .add-right-button{
        position: absolute;
        right: 20px;
        z-index: 9;
    }
</style>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Detail Contract
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Procurment</li>
            <li class="breadcrumb-item">Contract</li>
            <li class="breadcrumb-item">Detail</li> 
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr/>
                                <h5 class="text-md text-dark text-bold">Contract Information</h5>
                                <input type="hidden" name="id" value="{{ $contract->id }}">
                            </span>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group mt-4">
                                        <label for="complainant">Number:</label>
                                        <input type="text" class="form-control" value="{{ $contract->number }}" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mt-4">
                                        <label for="complainant">Title:</label>
                                        <input type="text" class="form-control" value="{{ $contract->title }}" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="complainant">Scope of Work:</label>
                                        <div class="html-viewer">
                                            {!! $contract->scope_of_work !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr/>
                                <h5 class="text-md text-dark text-bold">General Information</h5>
                            </span>
                            <div class="row">
                                <div class="mt-4"></div>
                                <div class="col-sm-12">
                                    <div class="form-group mt-4">
                                        <label for="complainant">Procurement Number:</label>
                                        <input type="text" class="form-control" value="{{ $contract->purchasing->number }}" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="complainant">Contract Signing Date:</label>
                                        <input type="text" class="form-control" value="{{ date("d/m/Y", strtotime($contract->contract_signing_date)) }}" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="complainant">Expiration Date:</label>
                                        <input type="text" class="form-control" value="{{ date("d/m/Y", strtotime($contract->expiration_date)) }}" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="complainant">Status:</label>
                                        <h6>@if($contract->progress) Finished @else In Progress @endif</h6>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="form-group mt-5 detail-item">
                                <i class="fa fa-file-alt"></i> 
                                <label for="complainant" class="mb-0">Procurement Number:</label>
                                <h6>{{ $contract->purchasing->number }}</h6>
                            </div> --}}
                            {{-- <div class="form-group detail-item">
                                <i class="fa fa-calendar"></i> 
                                <label for="number" class="mb-0">Contract Signing Date:</label>
                                <h6>{{ date("d/m/Y", strtotime($contract->contract_signing_date)) }}</h6>
                            </div> --}}
                            {{-- <div class="form-group detail-item">
                                <i class="fa fa-calendar"></i> 
                                <label for="number" class="mb-0">Expiration Date:</label>
                                <h6>{{ date("d/m/Y", strtotime($contract->expiration_date)) }}</h6>
                            </div> --}}
                            {{-- <div class="form-group detail-item">
                                <i class="fa fa-user-tie"></i> 
                                <label for="number" class="mb-0">Contract Owner:</label>
                                <p>
                                    @foreach ($roles as $role)
                                        @if(in_array($role->id, $contract->owner))
                                            <span class="tag">{{ $role->name }}</span>
                                        @endif
                                    @endforeach
                                </p>
                            </div> --}}
                            {{-- <div class="form-group detail-item">
                                <i class="fa fa-map-marked-alt"></i> 
                                <label for="number" class="mb-0">Unit:</label>
                                <h6>{{$contract->site->name}}</h6>
                            </div> --}}
                            {{-- <div class="form-group detail-item">
                                <i class="fa fa-check-circle"></i> 
                                <label for="number" class="mb-0">Status:</label>
                                <h6>@if($contract->progress) Finished @else In Progress @endif</h6>
                            </div> --}}
                        </div>
                    </div>

                    {{-- <div class="text-right mb-4">
                        <a href="{{ url('admin/contract') }}" class="btn bg-maroon color-palette legitRipple text-sm" >
                            <b><i class="fas fa-arrow-left"></i></b>
                        </a>
                    </div> --}}

                </div>


                <div class="col-md-12" id="content">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr/>
                                <h5 class="text-md text-dark text-bold">Product & Batch</h5>
                            </span>
                            <div class="mt-3"></div>

                            <div class="card">
                                <div class="card-body all-border-radius">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active pl-4 pr-4" id="product-tab" data-toggle="tab" data-target="#product" type="button" role="tab" aria-controls="product" aria-selected="true"><b>Product</b></button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link  pl-4 pr-4" id="batch-tab" data-toggle="tab" data-target="#batch" type="button" role="tab" aria-controls="batch" aria-selected="false" ><b>Batch</b></button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="product" role="tabpanel" aria-labelledby="product-tab">
                                            <div class="mt-3"></div>
                                            <table class="table table-striped datatable" width="100%" id="table-productlist">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Product</th>
                                                        <th>UOM</th>
                                                        <th>Qty Order</th>
                                                        <th>Qty Receive</th>
                                                        <th>#</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="batch" role="tabpanel" aria-labelledby="batch-tab">
                                            <div class="mt-3"></div>
                                            <button type="button" id="add-batch" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple add-right-button" onclick="addBatch()">
                                                <b><i class="fas fa-plus"></i></b>&nbsp; add
                                            </button>
                                            <table class="table table-striped" width="100%" id="table-batch">
                                                <thead>
                                                    <tr>
                                                        <th>Batch</th>
                                                        <th>Est. Delivery</th>
                                                        <th>SKU</th>
                                                        <th>#</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
        </div>
    </div>
</section>

<div class="modal fade" id="form-edit">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-bold">Edit Batch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-batch" method="post" autocomplete="off" action="{{ route("contract.batch.update") }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="batch_id" id="batch_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="start_batch">Start Batch</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <input type="text" class="form-control datepicker text-right" id="start_batch" name="start_batch" placeholder="Start Batch" aria-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="end_batch">End Batch</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <input type="text" class="form-control datepicker text-right" id="end_batch" name="end_batch" placeholder="End Batch" aria-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="control-label col-12" for="end_batch">Batch Product</label>
                                <div class="col-sm-9">
                                    <select data-placeholder="Choose Product" style="width: 100%;" class="select2 form-control" id="contract_product" name="contract_product">
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" id="add-uom" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="addBatchProduct()">
                                        <b><i class="fas fa-plus"></i></b> Add
                                    </button>
                                </div>
                                <div class="col-12 mb-3"></div>
                                <div class="col-12">
                                    <table class="table table-striped" width="100%" id="table-product">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>UOM</th>
                                                <th>Qty</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple">
                            <b><i class="fas fa-save"></i></b>
                            Save
                        </button>
                        <button type="button" class="btn btn-labeled text-sm btn-sm btn-secondary btn-flat legitRipple" data-dismiss="modal">
                            <b><i class="fas fa-times"></i></b>
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="form-detail">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-bold">Detail Batch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="batch_id" id="batch_id2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="start_batch">Batch</label>
                            <input type="text" class="form-control"  placeholder="Batch" disabled name="batch">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="start_batch">Total SKU</label>
                            <input type="text" class="form-control"  placeholder="Total SKU" disabled name="total_sku">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="start_batch">Est. Delivery</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control text-right" placeholder="Start Batch" aria-required="true" disabled id="start_batch2">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="end_batch">&nbsp;</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control text-right" placeholder="End Batch" aria-required="true" disabled id="end_batch2">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="control-label col-12" for="end_batch">Batch Product</label>
                            <div class="col-12">
                                <table class="table table-striped" width="100%" id="table-detail-product">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>UOM</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-4">
                    <button type="button" class="btn btn-labeled text-sm btn-sm btn-secondary btn-flat legitRipple" data-dismiss="modal">
                        <b><i class="fas fa-times"></i></b>
                        Close
                    </button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="product-detail">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-bold">Detail Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="contract_product_id" id="contract_product_id">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="start_batch">Product Name</label>
                            <input type="text" class="form-control"  placeholder="Batch" disabled name="product_name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="start_batch">Total Qty</label>
                            <input type="text" class="form-control"  placeholder="Total QTY" disabled name="total_qty">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="control-label col-12" for="end_batch">Batch</label>
                            <div class="col-12">
                                <table class="table table-striped" width="100%" id="table-detail-product-contract">
                                    <thead>
                                        <tr>
                                            <th>Batch</th>
                                            <th>Qty</th>
                                            <th>UOM</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-4">
                    <button type="button" class="btn btn-labeled text-sm btn-sm btn-secondary btn-flat legitRipple" data-dismiss="modal">
                        <b><i class="fas fa-times"></i></b>
                        Close
                    </button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/jquery.inputmask.js') }}"></script>
    <script type="text/javascript">
        $(function(){
            $(".number-only").inputmask("Regex", { regex: "[1-9]*" });
            $(".select2").select2();
            $('.datepicker').daterangepicker({
                singleDatePicker: true,
                timePicker: false,
                timePickerIncrement: 30,
                locale: {
                    format: 'DD/MM/YYYY'
                }
            });

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
                lengthChange: true,
                order: [[ 1, "asc" ]],
                ajax: {
                    url: "{{route('contract.product')}}",
                    type: "GET",
                    data:function(data){
                        var id = $('input[name=id]').val();
                        data.id = id;
                    }
                },
                columnDefs:[
                    {
                        orderable: false,targets:[0,5]
                    },
                    { className: "text-right", targets: [3,4] },
                    { className: "text-center", targets: [0,5] },
                    {
                        render: function ( data, type, row ) {
                        return `<a href="javascript:void(0);" style="line-height: 1;">
                        <div class="text-md text-info">
                            ${row.product.name}
                        </div>
                        </a>
                        <small>
                            ${row.product.merek}
                        </small>`;
                        },targets: [1]
                    },
                    {
                        render: function ( data, type, row ) {
                            var uom_name = "";
                            for(var uom of row.product.uoms){   
                                if(row.uom_id == uom.uom_id){
                                    uom_name = `${uom.uom.name}`;
                                }
                            }
                            return `${uom_name}`;
                        },targets: [2]
                    },
                    {
                        render: function ( data, type, row ) {
                            return `${row.qty}`;
                        },targets: [3]
                    },
                    {
                        render: function ( data, type, row ) {
                            return `0`;
                        },targets: [4]
                    },
                    {   
                        width: "10%",
                        render: function ( data, type, row ) {
                        return `<div class="btn-group dropleft">
                        <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="javascript:void(0);" data-id="${row.id}" onclick="detailProduct(${row.id})">
                                <i class="far fa-eye"></i> Detail
                            </a>
                        </div>
                        </div>`;
                        },targets: [5]
                    }
                ],
                columns: [
                    { data: "no", width: 50 },
                    { data: "product_id", width:150 },
                    { data: "uom_id", width:150 },
                    { data: "qty", width:100 },
                    { data: "qty", width:100 },
                    { data: "no" },
                ],
                drawCallback: function( settings ) {
                    
                }
            });

            tablebatch = $('#table-batch').DataTable( {
                processing: true,
                language: {
                    processing: `<div class="p-2 text-center">
                <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...
                </div>`
                },
                serverSide: true,
                filter: false,
                responsive: true,
                lengthChange: true,
                ajax: {
                    url: "{{route('contract.batch.read')}}",
                    type: "GET",
                    data:function(data){
                        var id = $('input[name=id]').val();
                        data.id = id;
                    }
                },
                columnDefs:[
                    {
                        orderable: false,targets:[0,1,2,3]
                    },
                    { className: "text-right", targets: [2] },
                    { className: "text-center", targets: [0,1,3] },
                    {
                        render: function ( data, type, row ) {
                            var text = "-";
                            if(row.start_batch && row.end_batch){
                                text = `${row.start_date} - ${row.end_date}`
                            }
                            return `${text}`;
                        },targets: [1]
                    },
                    {
                        render: function ( data, type, row ) {
                            return `${row.total_sku}`;
                        },targets: [2]
                    },
                    {   
                        width: "10%",
                        render: function ( data, type, row ) {
                        return `<div class="btn-group dropleft">
                        <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="javascript:void(0);" data-id="${row.id}" onclick="detailBatch(${row.id})">
                                <i class="far fa-eye"></i> Detail
                            </a>
                            <a class="dropdown-item" href="javascript:void(0);" data-id="${row.id}" onclick="editBatch(${row.id})">
                                <i class="far fa-edit"></i> Edit
                            </a>
                            <a class="dropdown-item" href="javascript:void(0);" data-id="${row.id}" onclick="deleteBatch(${row.id})">
                                <i class="far fa-trash-alt"></i> Delete
                            </a>
                        </div>
                        </div>`;
                        },targets: [3]
                    }
                ],
                columns: [
                    { data: "no", width:100 },
                    { data: "start_batch", width:250 },
                    { data: "batchproduct_count", width:100 },
                    { data: "id", width: 20 },
                ],
                drawCallback: function( settings ) {
                    
                }
            });

            tableproduct = $('#table-product').DataTable( {
                processing: true,
                language: {
                    processing: `<div class="p-2 text-center">
                <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...
                </div>`
                },
                serverSide: true,
                filter: false,
                responsive: true,
                lengthChange: true,
                ajax: {
                    url: "{{route('contract.batch.product.read')}}",
                    type: "GET",
                    data:function(data){
                        var id = $('#form-edit input[name=batch_id]').val()?$('#form-edit input[name=batch_id]').val():0;
                        data.id = id;
                    }
                },
                columnDefs:[
                    {
                        orderable: false,targets:[0,1,2,3]
                    },
                    { className: "text-right", targets: [2] },
                    { className: "text-center", targets: [0,1,3] },
                    {
                        render: function ( data, type, row ) {
                            return `${row.contractproduct.product.name}`;
                        },
                        targets: [0]
                    },
                    {
                        render: function ( data, type, row ) {
                            return `${row.contractproduct.uom.name}`;
                        },
                        targets: [1]
                    },
                    {
                        width: "25%",
                        render: function ( data, type, row ) {
                            return `
                                <div class="form-group">
                                <input id="batch_contract_products_id${row.id}" name="batch_contract_products_id[]" type="hidden" value="${row.id}">
                                <input id="qty${row.id}" name="qty[]" class="form-control" value="${row.qty}" type="number" min="0" max="${row.contract_product_qty}"/>
                                </div>
                            `;
                        },
                        targets: [2]
                    },
                    {   
                        width: "10%",
                        render: function ( data, type, row ) {
                        return `<button type="button" class="btn btn-transparent text-md" onclick="removeProduct(${row.id})" data-urutan="${row.id}">
                            <i class="fas fa-trash text-maroon color-palette"></i>
                        </button>`;
                        },targets: [3]
                    }
                ],
                columns: [
                    { data: "id", width:100 },
                    { data: "id", width:250 },
                    { data: "qty", width:100 },
                    { data: "id", width: 20 },
                ],
                drawCallback: function( settings ) {
                    
                }
            });

            tabledetailproduct = $('#table-detail-product').DataTable( {
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
                ajax: {
                    url: "{{route('contract.batch.product.read')}}",
                    type: "GET",
                    data:function(data){
                        var id = $('#form-detail input[name=batch_id]').val()?$('#form-detail input[name=batch_id]').val():0;
                        data.id = id;
                    }
                },
                columnDefs:[
                    {
                        orderable: false,targets:[0,1,2]
                    },
                    { className: "text-right", targets: [2] },
                    { className: "text-center", targets: [0,1] },
                    {
                        render: function ( data, type, row ) {
                            return `${row.contractproduct.product.name}`;
                        },
                        targets: [0]
                    },
                    {
                        render: function ( data, type, row ) {
                            return `${row.contractproduct.uom.name}`;
                        },
                        targets: [1]
                    }
                ],
                columns: [
                    { data: "id", width:100 },
                    { data: "id", width:250 },
                    { data: "qty", width:100 }
                ],
                drawCallback: function( settings ) {
                    
                }
            });

            tabledetailproductcontract = $('#table-detail-product-contract').DataTable( {
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
                ajax: {
                    url: "{{route('contract.product.read')}}",
                    type: "GET",
                    data:function(data){
                        var id = $('#product-detail input[name=contract_product_id]').val()?$('#product-detail input[name=contract_product_id]').val():0;
                        data.id = id;
                    }
                },
                columnDefs:[
                    {
                        orderable: false,targets:[0,1,2,3]
                    },
                    { className: "text-right", targets: [1] },
                    { className: "text-center", targets: [2,3] },
                    {
                        render: function ( data, type, row ) {
                            return `${row.batch.no}`;
                        },
                        targets: [0]
                    },
                    {
                        render: function ( data, type, row ) {
                            return `${row.contractproduct.uom.name}`;
                        },
                        targets: [2]
                    },
                    {
                        render: function ( data, type, row ) {
                            return `<span class="badge bg-warning color-platte text-sm">Waiting</span>`;
                        },
                        targets: [3]
                    }
                ],
                columns: [
                    { data: "id", width:100 },
                    { data: "qty", width:250 },
                    { data: "id", width:100 },
                    { data: "id", width:100 }
                ],
                drawCallback: function( settings ) {
                    
                }
            });

            $("#form-batch").validate({
                errorElement: 'span',
                errorClass: 'help-block',
                focusInvalid: false,
                highlight: function (e) {
                    $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
                },
                success: function (e) {
                    $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
                    $(e).remove();
                },
                errorPlacement: function (error, element) {
                    if(element.is(':file')) {
                        error.insertAfter(element.parent().parent().parent());
                    }else if(element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    }else if (element.attr('type') == 'checkbox') {
                        error.insertAfter(element.parent());
                    }else{
                        error.insertAfter(element);
                    }
                },
                submitHandler: function() { 
                    $.ajax({
                        url:$('#form-batch').attr('action'),
                        method:'post',
                        data: new FormData($('#form-batch')[0]),
                        processData: false,
                        contentType: false,
                        dataType: 'json', 
                        beforeSend:function(){
                            blockMessage('#table-batch','Loading','#fff');
                        }
                    }).done(function(response){
                        $('#table-batch').unblock();
                        if(response.status){
                            tablebatch.draw();
                            $("#form-edit").modal("hide");
                        }else{	
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
                        return;
                    }).fail(function(response){
                        $('#table-batch').unblock();
                        var response = response.responseJSON;
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
            });

            $( "#product_id" ).select2({
                ajax: {
                    url: "{{ route('contract.selectproduct') }}",
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
                            text: item.name,
                            uoms: item.uoms,
                        });
                    });
                    return {
                        results: option, more: more,
                    };
                    },
                },
                allowClear: true,
            });

            $( "#contract_product" ).select2({
                ajax: {
                    url: "{{ route('contract.selectbatch') }}",
                    type:'GET',
                    dataType: 'json',
                    data: function (params) {
                        var contract_id = $("input[name=id]").val();
                        return {
                            name:params.term,
                            page:params.page,
                            limit:30,
                            contract_id:contract_id,
                        };
                    },
                    processResults: function (data,params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows,function(index,item){
                        option.push({
                            id:item.id,  
                            text: item.product.name,
                            uom: item.uom.name,
                            qty: item.qty,
                            available_qty: item.available_qty,
                            merek: item.product.merek,
                        });
                    });
                    return {
                        results: option, more: more,
                    };
                    },
                },
                allowClear: true,
                templateSelection: formatSelection,
                templateResult: formatResult
            });

            $('#form-edit').on('hidden.bs.modal', function (event) {
                $("#form-edit #batch_id").val("");
                $("#form-edit #start_batch").val("");
                $("#form-edit #end_batch").val("");
                tablebatch.draw();
            });

            $('#form-detail').on('hidden.bs.modal', function (event) {
                $("#form-detail #batch_id").val("");
                $("#form-detail #start_batch").val("");
                $("#form-detail #end_batch").val("");
            });

        });

        function formatResult(state){
            if (!state.id) {
                return state.text;
            }
            var $state = $(`
                <span>${state.text}</span><small class="float-right">${state.available_qty} ${state.uom}</small><br>
                <small>${state.merek}</small>
            `);
            return $state;
        }

        function formatSelection(state) {
            if (!state.id) {
                return state.text;
            }
            var $state = $(`<span>${state.text}</span> - <span>${state.available_qty} ${state.uom}</span>`);
            return $state;
        };

        function addBatch(){
            var data = {
                _token: "{{ csrf_token() }}",
                contract_id: $('input[name=id]').val(),
            };
            $.ajax({
                url: "{{route('contract.batch.add')}}",
                dataType: 'json', 
                data:data,
                type:'POST',
                beforeSend:function(){
                    blockMessage('#table-batch','Loading','#fff');
                }
            }).done(function(response){
                if(response.status){
                    $('#table-batch').unblock();
                    tablebatch.ajax.reload( null, false );
                }
                else{
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
            }).fail(function(response){
                var response = response.responseJSON;
                $('#table-batch').unblock();
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
            });
        }

        function deleteBatch(id){
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
                message: 'Are you sure want to delete this batch?',
                callback: function (result) {
                    if (result) {
                        var data = {
                            _token: "{{ csrf_token() }}",
                            contract_id: $('input[name=id]').val(),
                            id: id,
                        };
                        $.ajax({
                            url: "{{route('contract.batch.delete')}}",
                            dataType: 'json', 
                            data:data,
                            type:'POST',
                            beforeSend:function(){
                                blockMessage('#table-batch','Loading','#fff');
                            }
                        }).done(function(response){
                            if(response.status){
                                $('#table-batch').unblock();
                                tablebatch.ajax.reload( null, false );
                            }
                            else{
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
                        }).fail(function(response){
                            var response = response.responseJSON;
                            $('#table-batch').unblock();
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
                        });
                    }
                }
            });
        }

        function editBatch(id){
            var data = {
                _token: "{{ csrf_token() }}",
                contract_id: $('input[name=id]').val(),
                id: id,
            };
            $.ajax({
                url: "{{route('contract.batch.edit')}}",
                dataType: 'json', 
                data:data,
                type:'GET',
                beforeSend:function(){
                    blockMessage('#table-batch','Loading','#fff');
                }
            }).done(function(response){
                if(response.status){
                    $('#table-batch').unblock();
                    $("#form-edit #batch_id").val(response.data.id);
                    if(response.data.start_batch){
                        $("#form-edit #start_batch").val(response.data.start_batch);
                    }
                    if(response.data.end_batch){
                        $("#form-edit #end_batch").val(response.data.end_batch);
                    }
                    tableproduct.draw();
                    $("#form-edit").modal("show");
                }
                else{
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
            }).fail(function(response){
                var response = response.responseJSON;
                $('#table-batch').unblock();
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
            });
        }

        function detailBatch(id){
            var data = {
                _token: "{{ csrf_token() }}",
                contract_id: $('input[name=id]').val(),
                id: id,
            };
            $.ajax({
                url: "{{route('contract.batch.show')}}",
                dataType: 'json', 
                data:data,
                type:'GET',
                beforeSend:function(){
                    blockMessage('#table-batch','Loading','#fff');
                }
            }).done(function(response){
                if(response.status){
                    $('#table-batch').unblock();
                    $("#form-detail #batch_id2").val(response.data.id);
                    if(response.data.start_batch){
                        $("#form-detail #start_batch2").val(response.data.start_batch);
                    }
                    if(response.data.end_batch){
                        $("#form-detail #end_batch2").val(response.data.end_batch);
                    }
                    $("#form-detail input[name=batch]").val(response.data.no);
                    $("#form-detail input[name=total_sku]").val(response.data.total_sku);
                    tabledetailproduct.draw();
                    $("#form-detail").modal("show");
                }
                else{
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
            }).fail(function(response){
                var response = response.responseJSON;
                $('#table-batch').unblock();
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
            });
        }

        function addBatchProduct(){
            var data = {
                _token: "{{ csrf_token() }}",
                batch_contract_id: $('#form-batch input[name=batch_id]').val(),
                contract_product: $('#form-batch select[name=contract_product]').val(),
            };
            $.ajax({
                url: "{{route('contract.batch.product.add')}}",
                dataType: 'json', 
                data:data,
                type:'POST',
                beforeSend:function(){
                    blockMessage('#table-product','Loading','#fff');
                }
            }).done(function(response){
                if(response.status){
                    $('#table-product').unblock();
                    $('#form-batch select[name=contract_product]').empty();
                    tableproduct.ajax.reload( null, false );
                }
                else{
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
            }).fail(function(response){
                var response = response.responseJSON;
                $('#table-product').unblock();
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
            });
        }

        function removeProduct(id){
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
                            id: id,
                        };
                        $.ajax({
                            url: "{{route('contract.batch.product.delete')}}",
                            dataType: 'json', 
                            data:data,
                            type:'POST',
                            beforeSend:function(){
                                blockMessage('#table-product','Loading','#fff');
                            }
                        }).done(function(response){
                            if(response.status){
                                $('#table-product').unblock();
                                tableproduct.ajax.reload( null, false );
                            }
                            else{
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
                        }).fail(function(response){
                            var response = response.responseJSON;
                            $('#table-product').unblock();
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
                        });
                    }
                }
            });
        }

        function detailProduct(id){var data = {
                _token: "{{ csrf_token() }}",
                contract_id: $('input[name=id]').val(),
                id: id,
            };
            $.ajax({
                url: "{{route('contract.product.show')}}",
                dataType: 'json', 
                data:data,
                type:'GET',
                beforeSend:function(){
                    blockMessage('.datatable','Loading','#fff');
                }
            }).done(function(response){
                if(response.status){
                    $('.datatable').unblock();
                    $("#product-detail #contract_product_id").val(response.data.id);
                    $("#product-detail input[name=product_name]").val(response.data.product.name);
                    $("#product-detail input[name=total_qty]").val(response.data.qty);
                    tabledetailproductcontract.draw();
                    $("#product-detail").modal("show");
                }
                else{
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
            }).fail(function(response){
                var response = response.responseJSON;
                $('.datatable').unblock();
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
            });
        }

    </script>
@endsection