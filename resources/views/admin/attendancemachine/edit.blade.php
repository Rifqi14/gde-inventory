@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">Edit {{ $menu_name }}</h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">Home</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
      <li class="breadcrumb-item">Edit</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <form action="{{ route('attendancemachine.update', ['id' => $machine->id]) }}" role="form" method="POST" enctype="multipart/form-data" autocomplete="off" id="form">
      @csrf
      @method('PUT')
      <div class="card">
        <div class="card-body">
          <span class="title">
            <hr>
            <h5 class="text-md text-dark text-bold">{{ $menu_name }} Information</h5>
          </span>
          <div class="mt-5"></div>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label" for="machine_name">Machine Name</label>
                <input type="text" name="machine_name" class="form-control" placeholder="Machine Name" value="{{ $machine->machine_name }}">
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group row">
                <label class="col-md-12 col-xs-12 control-label" for="type">Type</label>
                <div class="col-sm-12 controls">
                  <select name="type" id="type" class="form-control select2">
                    <option value="">Select Type</option>
                    @foreach(config('enums.machine_type') as $key => $type)
                    <option value="{{ $key }}" @if ($machine->type==$key) selected @endif>{{ $type }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
            <b><i class="fas fa-save"></i></b> Save
          </button>
          <a href="{{ route('attendancemachine.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
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
  $(function(){
    $('.select2').select2();
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