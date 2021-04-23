@extends('admin.layouts.app')
@section('title', 'Warehouse')
@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Create Warehouse
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Warehouse</li>
            <li class="breadcrumb-item">Create</li> 
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <form id="form" role="form" action="{{route('warehouse.store')}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr />
                                <h5 class="text-md text-dark text-bold">Warehouse Information</h5>
                            </span>
                            <div class="mt-5"></div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="code">Code <b class="text-danger">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <input type="text" class="form-control" id="code" name="code" placeholder="Code" required="" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="name">Name <b class="text-danger">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" required="" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="type">Type <b class="text-danger">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <select name="type" id="type" class="form-control select2" required>
                                                <option value="">Select Type</option>
                                                @foreach(config('enums.warehouse_type') as $key => $type)
                                                    <option value="{{ $key }}">{{ $type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="site_id">Unit <b class="text-danger">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <select data-placeholder="Choose Unit" style="width: 100%;" required class="select2 form-control" id="site_id" name="site_id" data-placeholder="Select Site">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="province_id">Province <b class="text-danger">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <select data-placeholder="Choose Province" style="width: 100%;" required class="select2 form-control" id="province_id" name="province_id" data-placeholder="Select Province">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="name">Region <b class="text-danger">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <select data-placeholder="Choose Region" style="width: 100%;" required class="select2 form-control" id="region_id" name="region_id" data-placeholder="Select Region">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="district_id">Distric <b class="text-danger">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <select data-placeholder="Choose Distric" style="width: 100%;" required class="select2 form-control" id="district_id" name="district_id" data-placeholder="Select Distric">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="subdistrict_id">Sub Distric <b class="text-danger">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <select data-placeholder="Choose Sub Distric" style="width: 100%;" required class="select2 form-control" id="village_id" name="subdistrict_id" data-placeholder="Select Sub Distric">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="postal_code">Postal Code <b class="text-danger">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Postal Code" required="" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="address">Address <b class="text-danger">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Address..." required="" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
                                <b><i class="fas fa-save"></i></b>
                                Save
                            </button>
                            <a href="{{route('warehouse.index')}}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-times"></i></b>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr />
                                <h5 class="text-md text-dark text-bold">Other</h5>
                            </span>
                            <div class="mt-5"></div>
                            <div class="form-group row">
                                <label class="col-md-12 col-xs-12 control-label" for="description">Description</label>
                                <div class="col-sm-12 controls">
                                    <textarea class="form-control summernote" name="description" id="description" rows="4" placeholder="Description..."></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-12 col-xs-12 control-label" for="status">Status <b class="text-danger">*</b></label>
                                <div class="col-sm-12 controls">
                                    <select name="status" id="status" class="form-control select2" required data-placeholder="Select Status">
                                        <option value=""></option>
                                        @foreach(config('enums.warehouse_status') as $key => $status)
                                            <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
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

        $( "#village_id" ).select2({
            ajax: {
                url: "{{ route('village.select') }}",
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