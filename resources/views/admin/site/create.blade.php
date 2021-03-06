@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Create {{$menu_name}}
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{$parent_name}}</li>
            <li class="breadcrumb-item">{{$menu_name}}</li>
            <li class="breadcrumb-item">Create</li>
        </ol>
    </div>
</div>
@endsection

@section('content')<section class="content" id="content">
    <div class="container-fluid">
        <form id="form" role="form" action="{{route('site.store')}}" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr />
                                <h5 class="text-md text-dark text-uppercase">Site Information</h5>
                            </span>
                            <div class="mt-5"></div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label" for="name">Code <b style="color: red;">*</b></label>
                                <div class="col-sm-6 controls">
                                    <input type="text" class="form-control" id="code" name="code" placeholder="Code" required="" aria-required="true">
                                    <p class="help-block mb-0">Ex. awesomesite (Only letters and number input).</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label" for="name">Name <b style="color: red;">*</b></label>
                                <div class="col-sm-6 controls">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" required="" aria-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            {{ csrf_field() }}
                            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
                                <b><i class="fas fa-save"></i></b>
                                Save
                            </button>
                            <a href="{{route('site.index')}}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
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
        $("input[name=code]").inputmask("Regex", {
            regex: "^[a-zA-Z0-9_.-]*$"
        });
        $("#form").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            focusInvalid: false,
            highlight: function(e) {
                $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
                $(e).remove();
            },
            errorPlacement: function(error, element) {
                if (element.is(':file')) {
                    error.insertAfter(element.parent().parent().parent());
                } else if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.attr('type') == 'checkbox') {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function() {
                $.ajax({
                    url: $('#form').attr('action'),
                    method: 'post',
                    data: new FormData($('#form')[0]),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function() {
                        blockMessage('#content', 'Loading', '#fff');
                    }
                }).done(function(response) {
                    $('#content').unblock();
                    if (response.status) {
                        toastr.success('Data has been saved.');
                        document.location = response.results;
                    } else {
                        toastr.warning(response.message);
                    }
                    return;
                }).fail(function(response) {
                    $('#content').unblock();                    
                    toastr.warning(response.message);
                })
            }
        });
    });
</script>
@endsection