@extends('admin.layouts.app')

@section('title')
Create Working Shift
@endsection

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
	<div class="col-sm-4">
		<h1 id="title-branch" class="m-0 text-dark">
			Working Shifts
		</h1>
	</div>
	<div class="col-sm-8">
		<ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
			<li class="breadcrumb-item">Preferences</li>
			<li class="breadcrumb-item">Working Shifts</li>
			<li class="breadcrumb-item active">Create</li>
		</ol>
	</div>
</div>
@endsection

@section('content')
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<form class="form-horizontal no-margin" action="{{route('workingshift.store')}}" id="form"
						method="post" />
					{{ csrf_field() }}
					<div class="card-body">
						<span class="title">
							<hr />
							<h5 class="text-md text-dark text-bold">Working Shift Information</h5>
						</span>
						<div class="form-group row mt-4">
							<label class="col-md-2 col-xs-12 control-label" for="shift_type">Shift Type:</label>
							<div class="col-sm-6 controls">
								<select name="shift_type" class="select2 form-control">
									<option value="shift">Shift</option>
									<option value="non_shift">Non Shift</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-2 col-xs-12 control-label" for="shift_name">Shift Name:</label>
							<div class="col-sm-6 controls">
								<input type="text" class="form-control" name="shift_name" placeholder="Shift Name..." />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-2 col-xs-12 control-label" for="time_in">Time In</label>
							<div class="col-sm-6 controls">
								<input type="time" class="form-control" name="time_in" placeholder="Time In..." />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-2 col-xs-12 control-label" for="time_out">Time Out</label>
							<div class="col-sm-6 controls">
								<input type="time" class="form-control" name="time_out" placeholder="Time Out..." />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-2 col-xs-12 control-label" for="status">Status:</label>
							<div class="col-sm-6 controls">
								<select name="status" class="select2 form-control">
									<option value="active">Active</option>
									<option value="non_active">Non Active</option>
								</select>
							</div>
						</div>
					</div>
					<div class="card-footer text-right">
						<button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
							<b><i class="fas fa-save"></i></b>
							Save
						</button>
						<a href="{{ route('workingshift.index') }}"
							class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
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
<!-- /.content -->
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
								document.location = "{{ route('workingshift.index') }}";
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
				shift_name:{
					required:true,
				},
				time_in:{
					required:true,
				},
				time_out:{
					required:true,
				},
			},
			messages: {
				shift_name:{
					required: "This field is required.",
				},
				time_in:{
					required: "This field is required.",
				},
				time_out:{
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