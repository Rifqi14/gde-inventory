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
        <form id="form" role="form" action="{{route('warehouse.update',['id'=>$warehouse->id])}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="put">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr />
                                <h5 class="text-md text-dark text-bold">Warehouse Information</h5>
                            </span>
                            <div class="mt-5"></div>
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
                            <span class="title">
                                <hr />
                                <h5 class="text-md text-dark text-bold">Rack & Bin</h5>
                            </span>
                            <div class="mt-5"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-5">

                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(function(){
        summernote();
        $(".select2").select2();
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
                        document.location = response.results;
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

        $( "#site_id" ).select2({
            ajax: {
                url: "{{ route('site.select') }}",
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
        });
        $("#site_id").select2("trigger", "select", {
            data: {id:'{{$warehouse->site_id}}', text:'{{$warehouse->site->name}}'}
        });

        $( "#province_id" ).select2({
            ajax: {
                url: "{{ route('province.select') }}",
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
        });
        $("#province_id").select2("trigger", "select", {
            data: {id:'{{$warehouse->province_id}}', text:'{{$warehouse->province->name}}'}
        });

        $( "#region_id" ).select2({
            ajax: {
                url: "{{ route('region.select') }}",
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
        });
        $("#region_id").select2("trigger", "select", {
            data: {id:'{{$warehouse->region_id}}', text:'{{$warehouse->region->name}}'}
        });

        $( "#district_id" ).select2({
            ajax: {
                url: "{{ route('district.select') }}",
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
        });
        $("#district_id").select2("trigger", "select", {
            data: {id:'{{$warehouse->district_id}}', text:'{{$warehouse->district->name}}'}
        });
    });

    function summernote(){
      $('.summernote').summernote({
    	height:145,
    	toolbar: [
    		['style', ['style']],
    		['font-style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
    		['font', ['fontname']],
    		['font-size',['fontsize']],
    		['font-color', ['color']],
    		['para', ['ul', 'ol', 'paragraph']],
    		['table', ['table']],
    		['insert', ['link', 'picture', 'video', 'hr']],
    		['misc', ['fullscreen', 'codeview', 'help']]
    	]
       });
    }
</script>
@endsection