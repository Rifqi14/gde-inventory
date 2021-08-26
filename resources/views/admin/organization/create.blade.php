@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">Create {{ $menu_name }}</h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name }}</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
      <li class="breadcrumb-item">Create</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <form action="{{ route('organization.store') }}" method="post" role="form" enctype="multipart/form-data" autocomplete="off" id="form">
      @csrf
      <div class="card">
        <div class="card-body">
          <span class="title">
            <hr>
            <h5 class="text-md text-dark text-bold">{{ $menu_name }} Information</h5>
          </span>
          <div class="mt-5"></div>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group row">
                <label for="code" class="control-label col-md-3">Code</label>
                <input type="text" name="code" id="code" class="form-control col-md-8" placeholder="Code">
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group row">
                <label for="name" class="control-label col-md-3">Name</label>
                <input type="text" name="name" id="name" class="form-control col-md-8" placeholder="Name">
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm" form="form">
            <b><i class="fas fa-save"></i></b> Submit
          </button>
          <a href="{{ route('organization.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
            <b><i class="fas fa-times"></i></b> Cancel
          </a>
        </div>
      </div>
    </form>
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
        },
      },
      messages: {
        code: {
          required: "This field is required",
        },
        name: {
          required: "This field is required",
        },
      },
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
          url: $('#form').attr('action'),
          method: 'post',
          data: new FormData($('#form')[0]),
          processData: false,
          contentType: false,
          dataType: 'json',
          beforeSend: function() {
            blockMessage('body', 'Loading...', '#fff');
          }
        }).done(function(response) {
          $('body').unblock();
          if (response.status) {
            document.location = response.results;
          } else {
            toastr.warning(response.message);
          }
          return;
        }).fail(function(response){
          $('body').unblock();
          var response  = response.responseJSON;
          toastr.error(response.message);
        });
      }
    });
  });
</script>
@endsection