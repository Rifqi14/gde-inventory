@extends('admin.layouts.app')

@section('title')
User Registration
@endsection

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <!-- <h5 class="m-0 ml-2 text-dark text-md breadcrumb">Grievance Redress &nbsp;<small class="font-uppercase"></small></h5> -->
        <h1 id="title-branch" class="m-0 text-dark">
            Registered User
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Preferences</li>
            <li class="breadcrumb-item">User</li>
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
                    <form class="form-horizontal no-margin" action="{{$url}}" id="form" method="post" />
                    {{ csrf_field() }}
                    @if(@$user)
                    @method('PUT')
                    @endif
                    <div class="card-body">
                        <span class="title">
                            <hr />
                            <h5 class="text-md text-dark text-bold">User Information</h5>
                        </span>
                        <div class="form-group row mt-4">
                            <label class="col-md-2 col-xs-12 control-label" for="group_id">Group:</label>
                            <div class="col-sm-6 controls">
                                <select type="text" class="select2 form-control" id="group_id" name="group_id"
                                    data-placeholder="Tag Group"></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label" for="username">Username:</label>
                            <div class="col-sm-6 controls">
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Username..." value="{{$user->username}}" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label" for="realname">Full Name:</label>
                            <div class="col-sm-6 controls">
                                <input type="text" class="form-control" id="realname" name="realname"
                                    placeholder="Full Name..." value="{{$user->name}}" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label" for="email">E-mail:</label>
                            <div class="col-sm-6 controls">
                                <input type="text" class="form-control" id="email" name="email" placeholder="E-mail..."
                                    value="{{$user->email}}" readonly />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label" for="spv_id">Supervisor:</label>
                            <div class="col-sm-6 controls">
                                <select type="text" class="select2 form-control" id="spv_id" name="spv_id"
                                    data-placeholder="Tag the Supervisor"></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label" for="password">Password:</label>
                            <div class="col-sm-6 controls">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password..." />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label" for="password">Status:</label>
                            <div class="col-sm-6 controls">
                                <select name="is_active" id="is_active" class="select2 form-control">
                                    <option value="1" {{($user->is_active == 1)?'selected':''}}>Active</option>
                                    <option value="0" {{($user->is_active == 0)?'selected':''}}>Disabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
                            <b><i class="fas fa-save"></i></b>
                            Save
                        </button>
                        <a href="{{ route('user.index') }}"
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
		$('#is_active').select2({});
		$( "#group_id" ).select2({
			ajax: {
				url: "{{ route('role.select') }}",
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
        $("#group_id").select2("trigger", "select", {
			data: {id:'{{$user->role_id}}', text:'{{$user->group_description}}'}
		});

		$( "#spv_id" ).select2({
			ajax: {
				url: "{{ route('user.spv_read') }}",
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
        $("#spv_id").select2("trigger", "select", {
			data: {id:'{{$user->spv_id}}', text:'{{$user->spv_name}}'}
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
								document.location = "{{ route('user.index') }}";
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
								toastr.error("Cant Create.");
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
				group_id:{
					required:true,
				},
				username:{
					required:true,
				},
				realname:{
					required:true,
				},
				password:{
					required:true,
				},
				is_active:{
					required:true,
				},
			},
			messages: {
				group_id:{
					required: "This field is required.",
				},
				username:{
					required: "This field is required.",
				},
				realname:{
					required: "This field is required.",
				},
				password:{
					required: "This field is required.",
				},
				is_active:{
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