@extends('admin.layouts.app')

@section('title')
Detail Vehicle
@endsection

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Vehicle Database
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Preferences</li>
            <li class="breadcrumb-item">Vehicle</li>
            <li class="breadcrumb-item active">Detail</li>
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
                    <form class="form-horizontal no-margin" action="{{route('vehicle.update', ['id' => $user->id])}}" id="form" method="post" />
                    {{ csrf_field() }}
                    <div class="card-body">
                        <span class="title">
                            <hr />
                            <h5 class="text-md text-dark text-bold">Vehicle Information</h5>
                        </span>
                        <div class="form-group row mt-4">
                            <label class="col-md-2 col-xs-12 control-label" for="site_id">Unit:</label>
                            <div class="col-sm-6 controls">
                                <select type="text" class="select2 form-control" name="site_id" data-placeholder="Unit" disabled></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label" for="police_number">Police Number:</label>
                            <div class="col-sm-6 controls">
                                <input type="text" class="form-control" name="police_number" placeholder="Police Number..." value="{{$user->police_number}}" readonly />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label" for="vehicle_name">Vehicle Name:</label>
                            <div class="col-sm-6 controls">
                                <input type="text" class="form-control" name="vehicle_name" placeholder="Vehicle Name..." value="{{$user->vehicle_name}}" readonly />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label" for="status">Status:</label>
                            <div class="col-sm-6 controls">
                                <select name="status" class="select2 form-control" disabled>
                                    <option value="active" {{($user->status == "active")?'selected':''}}>Active</option>
                                    <option value="non_active" {{($user->status == "non_active")?'selected':''}}>Non
                                        Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label" for="remarks">Remarks:</label>
                            <div class="col-sm-6 controls">
                                <textarea class="form-control" name="remarks" rows="4" style="resize: none;" placeholder="Remarks..." readonly>{{$user->remarks}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
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
    $(function() {
		$('.select2').select2();
		$( "#unit_id" ).select2({
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
        $("#unit_id").select2("trigger", "select", {
			data: {id:'{{$user->site_id}}', text:'{{$user->site_name}}'}
		});

	});
</script>
@endsection