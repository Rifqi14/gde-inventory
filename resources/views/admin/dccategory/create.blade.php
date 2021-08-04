@extends('admin.layouts.app')
@section('title', 'Document Category')
@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Create Document Category
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Document Category</li>
            <li class="breadcrumb-item">Create</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <form id="form" role="form" action="{{route('documentcategory.store')}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr />
                                <h5 class="text-md text-dark text-bold">Document Category Information</h5>
                            </span>
                            <div class="mt-5"></div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label" for="menu_id">Sub Menu <b class="text-danger">*</b></label>
                                <select name="menu_id[]" id="menu_id" multiple="multiple" class="select2 form-control col-md-6" required>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-xs-12" for="document_type_id">Document Type Code <b class="text-danger">*</b></label>
                                <select name="document_type_id" id="document_type_id" class="select2 form-control col-md-6" required></select>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-xs-12" for="name">Document Type Name <b class="text-danger">*</b></label>
                                <input type="text" name="name" id="name" class="form-control col-md-6" placeholder="Document Type Name" readonly>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
                                <b><i class="fas fa-save"></i></b>
                                Save
                            </button>
                            <a href="{{route('documentcategory.index')}}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-times"></i></b>
                                Cancel
                            </a>
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

        $('#menu_id').select2({
            placeholder: "Choose Sub Menu ...",
            ajax: {
                url: "{{ route('menu.select') }}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    return {
                        route: 'documentcenter',
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    var more    = (params.page * 30) < data.total;
                    var option  = [];
                    $.each(data.rows, function(index, item) {
                        if (item.child) {
                            $.each(item.child, function(indexChild, child) {
                                option.push({
                                    id: child.id,
                                    text: `${child.menu_name}`,
                                });
                            })
                        }
                    });
                    return { results: option, more: more };
                },
            },
            allowClear: true,
        });

        $('#document_type_id').select2({
            placeholder: "Choose Document Type ...",
            ajax: {
                url: "{{ route('documenttype.select') }}",
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
                    params.page = params.page || 1;
                    var more    = (params.page * 30) < data.total;
                    var option  = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: `${item.code}`,
                            name: `${item.name}`,
                        });
                    });
                    return { results: option, more: more };
                },
            },
            allowClear: true,
        }).on('select2:select', function(e) {
            var data    = $(this).select2('data');
            $('#name').val(data[0].name);
        });
    });
</script>
@endsection