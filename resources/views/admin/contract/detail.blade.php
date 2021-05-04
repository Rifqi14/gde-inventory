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
                            <div class="form-group mt-5 detail-item">
                                <i class="fa fa-file-alt"></i> 
                                <label for="complainant" class="mb-0">Procurement Number:</label>
                                <h6>{{ $contract->purchasing->number }}</h6>
                            </div>
                            <div class="form-group detail-item">
                                <i class="fa fa-calendar"></i> 
                                <label for="number" class="mb-0">Contract Signing Date:</label>
                                <h6>{{ date("d/m/Y", strtotime($contract->contract_signing_date)) }}</h6>
                            </div>
                            <div class="form-group detail-item">
                                <i class="fa fa-calendar"></i> 
                                <label for="number" class="mb-0">Expiration Date:</label>
                                <h6>{{ date("d/m/Y", strtotime($contract->expiration_date)) }}</h6>
                            </div>
                            <div class="form-group detail-item">
                                <i class="fa fa-user-tie"></i> 
                                <label for="number" class="mb-0">Contract Owner:</label>
                                <p>
                                    @foreach ($roles as $role)
                                        @if(in_array($role->id, $contract->owner))
                                            <span class="tag">{{ $role->name }}</span>
                                        @endif
                                    @endforeach
                                </p>
                            </div>
                            <div class="form-group detail-item">
                                <i class="fa fa-map-marked-alt"></i> 
                                <label for="number" class="mb-0">Unit:</label>
                                <h6>{{$contract->site->name}}</h6>
                            </div>
                            <div class="form-group detail-item">
                                <i class="fa fa-check-circle"></i> 
                                <label for="number" class="mb-0">Status:</label>
                                <h6>@if($contract->progress) Finished @else In Progress @endif</h6>
                            </div>
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
                                <h5 class="text-md text-dark text-bold">Product</h5>
                            </span>
                            <div class="mt-5"></div>
                            <form id="form" role="form" action="{{route('contract.product.store')}}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="contract_id" value="{{ $contract->id }}">
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label">Product :</label>
                                    <div class="col-sm-4">
                                        <select data-placeholder="Choose Product" style="width: 100%;" required class="select2 form-control" id="product_id" name="product_id">
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" id="add-uom" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple">
                                            <b><i class="fas fa-plus"></i></b> Add
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-striped datatable" width="100%" id="table-product">
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
                    </div>
                </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/jquery.inputmask.js') }}"></script>
    <script type="text/javascript">
        $(function(){
            $(".number-only").inputmask("Regex", { regex: "[1-9]*" });
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
                            var opt = `<option value="">&nbsp;</option>`;
                            for(var uom of row.product.uoms){   
                                selected = "";
                                if(row.uom_id == uom.uom_id){
                                    selected = "selected";
                                }
                                opt += `<option value="${uom.uom_id}" ${selected} >${uom.uom.name}</option>`;
                            }
                            return `
                            <select class="form-control select2uom" data-placeholder="Select UOM" data-id="${row.id}">
                                ${opt}
                            </select>
                            `;
                        },targets: [2]
                    },
                    {
                        render: function ( data, type, row ) {
                            return `
                            <input class="form-control text-right number-only qty-product" value="${row.qty}" data-id="${row.id}">
                            `;
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
                            <a class="dropdown-item delete" href="javascript:void(0);" data-id="${row.id}">
                                <i class="fa fa-trash-alt"></i> Delete Data
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
                    $(".select2uom").select2();
                    $(".number-only").inputmask("Regex", { regex: "[0-9]*" });
                    updatedatatable();
                }
            });

            $("#form").validate({
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
                        url:$('#form').attr('action'),
                        method:'post',
                        data: new FormData($('#form')[0]),
                        processData: false,
                        contentType: false,
                        dataType: 'json', 
                        beforeSend:function(){
                            blockMessage('#content', 'Loading', '#fff');
                        }
                    }).done(function(response){
                        $('#content').unblock();
                        if(response.status){
                            dataTable.draw();
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
                                url: `{{route('contract.product.delete')}}`,
                                dataType: 'json',
                                data: data,
                                type: 'POST',
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

        });

        function addProduct(){

        }

        function updatedatatable(){
            $("body .select2uom").on('select2:select',function(){
                var data = {
                    _token: "{{ csrf_token() }}",
                    id: $(this).attr("data-id"),
                    uom_id: $(this).select2('val')
                };
                $.ajax({
                    url: "{{route('contract.product.update')}}",
                    dataType: 'json', 
                    data:data,
                    type:'POST',
                    beforeSend:function(){
                        blockMessage('.datatable','Loading','#fff');
                    }
                }).done(function(response){
                    if(response.status){
                        $('.datatable').unblock();
                        // dataTable.ajax.reload( null, false );
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
            });

            $("body .qty-product").on("change",function(){
                var data = {
                    _token: "{{ csrf_token() }}",
                    id: $(this).attr("data-id"),
                    qty: $(this).val(),
                };
                $.ajax({
                    url: "{{route('contract.product.update')}}",
                    dataType: 'json', 
                    data:data,
                    type:'POST',
                    beforeSend:function(){
                        blockMessage('.datatable','Loading','#fff');
                    }
                }).done(function(response){
                    if(response.status){
                        $('.datatable').unblock();
                        // dataTable.ajax.reload( null, false );
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
            });
        }

    </script>
@endsection