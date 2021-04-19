@extends('admin.layouts.app')
@section('title', 'Warehouse')
@section('stylesheets')
<style>
    .list-group-item{
        border: 0px solid;
    }
    .card-body{
        border: 1px solid #f7f7f7;
        border-radius: 10px 10px 0px 0px;
    }
    .all-border-radius{
        border-radius: 10px;
    }
    .card-footer{
        border: 1px solid #f7f7f7;
        border-radius: 0px 0px 10px 10px;
    }
    .card{
        min-height: 100%;
    }
</style>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Edit Warehouse
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Warehouse</li>
            <li class="breadcrumb-item">Edit</li> 
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <span class="title">
                            <hr />
                            <h5 class="text-md text-dark text-bold">Warehouse Information</h5>
                        </span>
                        <div class="mt-5"></div>
                        <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Code</b> <a class="float-right">{{ $warehouse->code }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Name</b> <a class="float-right">{{ $warehouse->name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Type</b> <a class="float-right">{{ config('enums.warehouse_type')[$warehouse->type] }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Unit</b> <a class="float-right">{{ $warehouse->site->name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Province</b> <a class="float-right">{{ $warehouse->province->name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Region</b> <a class="float-right">{{ $warehouse->region->name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>District</b> <a class="float-right">{{ $warehouse->district->name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Sub District</b> <a class="float-right">{{ $warehouse->subdistrict_id }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Postal Code</b> <a class="float-right">{{ $warehouse->postal_code }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('warehouse.index')}}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                            <b><i class="fas fa-reply"></i></b>
                            Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body all-border-radius">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active pl-4 pr-4" id="rack-tab" data-toggle="tab" data-target="#rack" type="button" role="tab" aria-controls="rack" aria-selected="true"><b>Rack</b></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link  pl-4 pr-4" id="bin-tab" data-toggle="tab" data-target="#bin" type="button" role="tab" aria-controls="bin" aria-selected="false"><b>Bin</b></button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="rack" role="tabpanel" aria-labelledby="rack-tab">
                                <div class="mt-3"></div>
                                <div class="row">   
                                    <div class="col-12 text-left">
                                        <button type="button" id="add-role" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="addRack()">
                                            <b><i class="fas fa-plus"></i></b> Add
                                        </button>
                                        <button type="button" id="add-role" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filterRack()">
                                            <b><i class="fas fa-search"></i></b> Filter
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-3"></div>
                                <input type="hidden" name="all_rack" id="all_rack" value="[]">
                                <table class="table table-striped datatable" id="rack-table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Rack</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="bin" role="tabpanel" aria-labelledby="bin-tab">
                                <div class="mt-3"></div>
                                <div class="row">   
                                    <div class="col-12 text-left">
                                        <button type="button" id="add-role" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="addBin()">
                                            <b><i class="fas fa-plus"></i></b> Add
                                        </button>
                                        <button type="button" id="add-role" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filterBin()">
                                            <b><i class="fas fa-search"></i></b> Filter
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-3"></div>
                                <table class="table table-striped datatable" id="bin-table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Rack</th>
                                            <th>Bin</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-5"></div>
        </div>
    </div>
</section>

<div class="modal fade" id="form-add-rack">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-bold">Add Rack</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-create-rack" method="post" autocomplete="off" action="{{ route('rack.store') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" />
                    <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Name">
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple">
                            <b><i class="fas fa-plus"></i></b>
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="filter-rack">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-bold">Filter Rack</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-filter-rack" method="post" autocomplete="off">
                    <input type="hidden" name="_method" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Name">
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="submitFilterRack()">
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

<div class="modal fade" id="form-add-bin">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-bold">Add Bin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-create-bin" method="post" autocomplete="off" action="{{ route('bin.store') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Rack</label>
                                <select name="rack_id" id="rack_id" class="form-control" data-placeholder="Select Rack"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Name">
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple">
                            <b><i class="fas fa-plus"></i></b>
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="filter-bin">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-bold">Filter Bin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-filter-bin" method="post" autocomplete="off">
                    <input type="hidden" name="_method" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Rack</label>
                                <select name="rack_id" id="rack_id2" class="form-control" data-placeholder="Select Rack"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Name">
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="submitFilterBin()">
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
    var warehouse_id = '{{ $warehouse->id }}';
</script>
<script>
    function filterRack(){
        $("#filter-rack").modal("show");
    }
    function filterBin(){
        $("#filter-bin").modal("show");
    }
    function submitFilterRack(){
        tableRack.draw();
        $("#filter-rack").modal('hide');
    }
    function submitFilterBin(){
        tableBin.draw();
        $("#filter-bin").modal('hide');
    }
    function addRack(){
        $("#form-add-rack").modal("show");
    }
    function addBin(){
        $("#form-add-bin").modal("show");
    }
    function editrack(id, name){
        $("#form-create-rack input[name=name]").val(name);
        $("#form-create-rack").append(`<input type="hidden" name="id" value="${id}">`);
        $("#form-add-rack button[type=submit]").html(`<b><i class="fas fa-save"></i></b>Save`);
        $("#form-add-rack .modal-title ").html(`Edit Rack`);
        $("#form-add-rack").modal("show");
    }
    function editbin(id, name, rack_id){
        $("#form-create-bin input[name=name]").val(name);
        var allrack = JSON.parse($("#all_rack").val());
        var rack_name = "";
        for(var x of allrack){
            if(x.id == rack_id){
                rack_name = x.name;
            }
        }
        $("#form-create-bin select[name=rack_id]").select2("trigger", "select", {
            data: {id:rack_id, text:rack_name}
        });
        $("#form-create-bin").append(`<input type="hidden" name="id" value="${id}">`);
        $("#form-add-bin button[type=submit]").html(`<b><i class="fas fa-save"></i></b>Save`);
        $("#form-add-bin .modal-title ").html(`Edit Bin`);
        $("#form-add-bin").modal("show");
    }
    function deleterack(id){
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
                        url: `{{route('rack.index')}}/${id}`,
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
                            tableRack.draw();
                            tableBin.draw();
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
    function deletebin(id){
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
                        url: `{{route('bin.index')}}/${id}`,
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
                            tableRack.draw();
                            tableBin.draw();
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

    $(function(){
        $(".select2").select2();

        tableRack = $('#rack-table').DataTable( {
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
                url: "{{route('rack.read')}}",
                type: "GET",
                data:function(data){
                    var name = $("#filter-rack input[name=name]").val();
                    var warehouse_id = $('#content').find('input[name=warehouse_id]').val();
                    data.name = name;
                    data.warehouse_id = warehouse_id;
                }
            },
            columnDefs:[
                {
                    orderable: false,targets:[0,2]
                },
                { className: "text-right", targets: [0] },
                { className: "text-center", targets: [2] },
                {
                    width: "5%",
                    render: function ( data, type, row ) {
                        return row.no;
                    },targets: [0]
                },
                {   
                    width: "10%",
                    render: function ( data, type, row ) {
                    return `<div class="btn-group dropleft">
                    <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);" onclick="editrack(${row.id},'${row.name}')">
                            <i class="far fa-edit"></i>Update Data
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="deleterack(${row.id})">
                            <i class="fa fa-trash-alt"></i> Delete Data
                        </a>
                    </div>
                    </div>`;
                    },targets: [2]
                }
            ],
            columns: [
                { data: "no" },
                { data: "name", className: "text-bold" },
                { data: "id" },
            ],
            drawCallback: function( settings ) {
                var api = this.api();
                var json = api.ajax.json();
                var data = json.data;
                $("input[name=all_rack]").val(JSON.stringify(data));
            }
        });

        tableBin = $('#bin-table').DataTable( {
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
                url: "{{route('bin.read')}}",
                type: "GET",
                data:function(data){
                    var name = $("#filter-bin input[name=name]").val();
                    var rack_id = $("#filter-bin select[name=rack_id]").val();
                    var warehouse_id = $('#content').find('input[name=warehouse_id]').val();
                    data.name = name;
                    data.rack_id = rack_id;
                    data.warehouse_id = warehouse_id;
                }
            },
            columnDefs:[
                {
                    orderable: false,targets:[0,3]
                },
                { className: "text-right", targets: [0] },
                { className: "text-center", targets: [3] },
                {
                    width: "1%",
                    render: function ( data, type, row ) {
                        return row.no;
                    },targets: [0]
                },
                {   
                    width: "10%",
                    render: function ( data, type, row ) {
                    return `<div class="btn-group dropleft">
                    <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);" onclick="editbin(${row.id},'${row.name}', ${row.rack_id})">
                            <i class="far fa-edit"></i>Update Data
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="deletebin(${row.id})">
                            <i class="fa fa-trash-alt"></i> Delete Data
                        </a>
                    </div>
                    </div>`;
                    },targets: [3]
                }
            ],
            columns: [
                { data: "no" },
                { data: "rack_name", className: "text-bold" },
                { data: "name", className: "text-bold" },
                { data: "id" },
            ]
        });

        $("#form-create-rack").validate({
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
                    url:$('#form-create-rack').attr('action'),
                    method:'post',
                    data: new FormData($('#form-create-rack')[0]),
                    processData: false,
                    contentType: false,
                    dataType: 'json', 
                    beforeSend:function(){
                        blockMessage('#content', 'Loading', '#fff');
                    }
                }).done(function(response){
                    $('#content').unblock();
                    if(response.status){
                        $('#form-add-rack').modal("hide");
                        tableRack.draw();
                        tableBin.draw();
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
                    $('#content').unblock();
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

        $("#form-create-bin").validate({
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
                    url:$('#form-create-bin').attr('action'),
                    method:'post',
                    data: new FormData($('#form-create-bin')[0]),
                    processData: false,
                    contentType: false,
                    dataType: 'json', 
                    beforeSend:function(){
                        blockMessage('#content', 'Loading', '#fff');
                    }
                }).done(function(response){
                    $('#content').unblock();
                    if(response.status){
                        $('#form-add-bin').modal("hide");
                        tableRack.draw();
                        tableBin.draw();
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
                    $('#content').unblock();
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

        $('#form-add-rack').on('hidden.bs.modal', function (event) {
            $("#form-create-rack input[name=name]").val("");
            $("#form-create-rack input[name=id]").remove();
            $("#form-add-rack button[type=submit]").html(`<b><i class="fas fa-plus"></i></b>Add`);
            $("#form-add-rack .modal-title ").html(`Add Rack`);
        })

        $('#form-add-bin').on('hidden.bs.modal', function (event) {
            $("#form-create-bin input[name=name]").val("");
            $("#form-create-bin select[name=rack_id]").val("").trigger("change");
            $("#form-create-bin input[name=id]").remove();
            $("#form-add-bin button[type=submit]").html(`<b><i class="fas fa-plus"></i></b>Add`);
            $("#form-add-bin .modal-title ").html(`Add Bin`);
        })

        $( "#rack_id" ).select2({
            ajax: {
                url: "{{ route('rack.select') }}",
                type:'GET',
                dataType: 'json',
                data: function (params) {
                    return {
                        name:params.term,
                        page:params.page,
                        limit:30,
                        warehouse_id: warehouse_id
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
        });

        $( "#rack_id2" ).select2({
            ajax: {
                url: "{{ route('rack.select') }}",
                type:'GET',
                dataType: 'json',
                data: function (params) {
                    return {
                        name:params.term,
                        page:params.page,
                        limit:30,
                        warehouse_id: warehouse_id
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
        });
    });
</script>
@endsection