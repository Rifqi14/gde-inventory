@extends('admin.layouts.app')

@section('title')
Create {{ $menu_name }}
@endsection

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">
      {{ $menu_name }}
    </h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $menu_name }}</li>
      <li class="breadcrumb-item active">Create</li>
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
          <form class="form-horizontal no-margin" action="{{route('calendar.store')}}" id="form" method="post" />
          {{ csrf_field() }}
          <div class="card-body">
            <span class="title">
              <hr />
              <h5 class="text-md text-dark text-bold">Calendar Information</h5>
            </span>
            <div class="form-group row">
              <label class="col-md-2 col-xs-12 control-label" for="code">Code: <b class="text-danger">*</b></label>
              <div class="col-sm-6 controls">
                <input type="text" class="form-control" name="code" placeholder="Code..." />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 col-xs-12 control-label" for="name">Name: <b class="text-danger">*</b></label>
              <div class="col-sm-6 controls">
                <input type="text" class="form-control" name="name" placeholder="Name..." />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 col-xs-12 control-label" for="is_default">Active Calendar</label>
              <div class="col-sm-6 controls">
                <div class="icheck-primary">
                  <input type="checkbox" name="is_default" id="is_default">
                  <label for="is_default"></label>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 col-xs-12 control-label" for="description">Description:</label>
              <div class="col-sm-6 controls">
                <textarea class="form-control" name="description" rows="4" style="resize: none;" placeholder="Description..."></textarea>
              </div>
            </div>
          </div>
          <div class="card-footer text-right">
            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
              <b><i class="fas fa-save"></i></b>
              Save
            </button>
            <a href="{{ route('calendar.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
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
<script type="text/javascript">
  $(function() {
    $(document).on("change", ".select2", function () {
			if (!$.isEmptyObject($('#form').validate().submitted)) {
				$('#form').validate().form();
			}
		});
    $('.select2').select2();
    $.validator.setDefaults({
			submitHandler: function () {
				$.ajax({
                        url: $('#form').attr('action'),
                        method: 'post',
                        data: new FormData($('#form')[0]),
                        processData: false,
                        contentType: false,
                        dataType: 'json',
						success:function(result){
							$('#form').unblock();
							 if(result.status){
								document.location = "{{ route('calendar.index') }}";
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
								toastr.warning(result.message);
							}
						},
						beforeSend: function(){
							blockMessage('#form', 'Loading', '#fff');
						}
				});
			}
		});

		$('#form').validate({
			rules: {
				name:{
					required:true,
				},
				code:{
					required:true,
				},
			},
			messages: {
				name:{
					required: "This field is required.",
				},
				code:{
					required: "This field is required.",
				},
			},
			errorElement: 'span',
			errorPlacement: function (error, element) {
				error.addClass('invalid-feedback');
				element.closest('.form-group .controls').append(error);
			},
			highlight: function (element, errorClass, validClass) {
				$(element).addClass('is-invalid');
			},
			unhighlight: function (element, errorClass, validClass) {
				$(element).removeClass('is-invalid');
			}
		});
  });
</script>
@endsection