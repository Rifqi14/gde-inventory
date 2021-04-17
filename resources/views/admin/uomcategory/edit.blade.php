@extends('admin.layouts.app')
@section('title','UOM Category')

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            UOM Category
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">UOM Category</li>
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form class="form-horizontal no-margin" action="{{$url}}" id="form" method="post">
                        <div class="card-body">
                            <span class="title">
                                <hr>
                                <h5 class="text-dark text-bold text-md">UOM Category Information</h5>
                            </span>                            
                            {{ csrf_field() }}
                            @method('PUT')
                            <div class="form-group row mt-4">
                                <label class="col-md-2 col-xs-12 control-label" for="code">Code</label>
                                <div class="col-md-6 controls">
                                    <input class="form-control" type="text" name="code" id="code" placeholder="Code" value="{{$data->code}}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label" for="name">Name</label>
                                <div class="col-md-6 controls">
                                    <input class="form-control" type="text" name="name" id="name" placeholder="Name" value="{{$data->name}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
                                <b><i class="fas fa-save"></i></b>
                                Save
                            </button>
                            <a href="{{ route('uomcategory.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-times"></i></b>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
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
        $('#form').validate({
            rules: {
                code: {
                    required: true,
                },
                name: {
                    required: true,
                }
            },
            messages: {
                code: {
                    required: "This field is required.",
                },
                name: {
                    required: "This field is required.",
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group .controls').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
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
                        blockMessage('body', 'Please Wait . . . ', '#fff');
                    }
                }).done(function(response) {
                    $('body').unblock();
                    if (response.status) {
                        toastr.success('Data has been saved.');
                        document.location = response.results;
                    } else {
                        toastr.warning(`${response.message}`);
                    }
                    return;
                }).fail(function(response) {
                    $('body').unblock();
                    var response = response.responseJSON;
                    var message = response.message?response.message:'Failed to insert data.';
                    toastr.warning(message);                    
                })
            }
        });
    });
</script>
@endsection