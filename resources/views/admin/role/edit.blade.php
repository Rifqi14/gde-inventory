@extends('admin.layouts.app')
@section('title', 'Role')
@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Update Role
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Role</li>
            <li class="breadcrumb-item">Update</li> 
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <form id="form" role="form" action="{{route('role.update',['id'=>$role->id])}}" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr />
                                <h5 class="text-md text-dark text-bold">Role Information</h5>
                            </span>
                            <div class="form-group row mt-5">
                                <label class="col-md-2 col-xs-12 control-label" for="code">Name <b class="text-danger">*</b></label>
                                <div class="col-sm-6 controls">
                                    <input type="text" class="form-control" id="code" name="code" placeholder="Name" required="" aria-required="true" value="{{ $role->code }}">
                                    <p class="help-block mb-0">Ex. administrator (Only letters lowercase input).</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label" for="name">Display Name <b class="text-danger">*</b></label>
                                <div class="col-sm-6 controls">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Display Name" required="" aria-required="true" value="{{ $role->name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label" for="data_manager">District Access</label>
                                <div class="col-sm-6 controls">
                                    <div class="icheck-primary">
                                        <input type="checkbox" name="data_manager" id="data_manager" @if($role->data_manager) checked @endif>
                                        <label for="data_manager"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label" for="guest">Default</label>
                                <div class="col-sm-6 controls">
                                    <div class="icheck-primary">
                                        <input type="checkbox" name="guest" id="guest" @if($role->guest) checked @endif>
                                        <label for="guest"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="put">
                            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
                                <b><i class="fas fa-save"></i></b>
                                Save
                            </button>
                            <a href="{{route('role.index')}}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
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
<script src="{{ asset('assets/js/jquery.inputmask.js') }}"></script>
<script>
    $(function(){
        $("input[name=code]").inputmask("Regex", { regex: "[a-z]*" });
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
    });
</script>
@endsection