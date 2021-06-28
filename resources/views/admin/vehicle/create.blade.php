@extends('admin.layouts.app')

@section('title')
Create {{ @$menu_name }}
@endsection

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
	<div class="col-sm-4">
		<h1 id="title-branch" class="m-0 text-dark">
			{{ @$menu_name }}
		</h1>
	</div>
	<div class="col-sm-8">
		<ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
			<li class="breadcrumb-item">{{ @$parent_name }}</li>
			<li class="breadcrumb-item">{{ @$menu_name }}</li>
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
					<form class="form-horizontal no-margin" action="{{route('vehicle.store')}}" id="form" method="post" />
					{{ csrf_field() }}
					<div class="card-body">
						<span class="title">
							<hr />
							<h5 class="text-md text-dark text-bold">Vehicle Information</h5>
						</span>
						<div class="form-group row mt-4">
							<label class="col-md-2 col-xs-12 control-label" for="site_id">Unit:</label>
							<div class="col-sm-6 controls">
								<select type="text" class="select2 form-control" id="site_id" name="site_id" data-placeholder="Unit"></select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-2 col-xs-12 control-label" for="police_number">Police Number:</label>
							<div class="col-sm-6 controls">
								<input type="text" class="form-control" name="police_number" placeholder="Police Number..." />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-2 col-xs-12 control-label" for="vehicle_name">Vehicle Name:</label>
							<div class="col-sm-6 controls">
								<input type="text" class="form-control" name="vehicle_name" placeholder="Vehicle Name..." />
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
						<div class="form-group row">
							<label class="col-md-2 col-xs-12 control-label" for="remarks">Remarks:</label>
							<div class="col-sm-6 controls">
								<textarea class="form-control summernote" name="remarks" rows="4" style="resize: none;" placeholder="Remarks..."></textarea>
							</div>
						</div>
					</div>
					<div class="card-footer text-right">
						<button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
							<b><i class="fas fa-save"></i></b>
							Save
						</button>
						<a href="{{ route('vehicle.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
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
	const summernote = () => {
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
	$(function() {
		summernote();
		$(document).on("change", ".select2", function () {
			if (!$.isEmptyObject($('#form').validate().submitted)) {
				$('#form').validate().form();
			}
		});
		$('.select2').select2();
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
								document.location = "{{ route('vehicle.index') }}";
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
				site_id:{
					required:true,
				},
				police_number:{
					required:true,
				},
				vehicle_name:{
					required:true,
				},
			},
			messages: {
				site_id:{
					required: "This field is required.",
				},
				police_number:{
					required: "This field is required.",
				},
				vehicle_name:{
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