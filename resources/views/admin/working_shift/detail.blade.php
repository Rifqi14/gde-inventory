@extends('admin.layouts.app')

@section('title')
Detail {{ $menu_name }}
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
            <li class="breadcrumb-item">{{ $parent_name }}</li>
            <li class="breadcrumb-item">{{ $menu_name }}</li>
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
                    <form class="form-horizontal no-margin" action="{{route('workingshift.update', ['id' => $user->id])}}" id="form" method="post" />
                    <div class="card-body">
                        <span class="title">
                            <hr />
                            <h5 class="text-md text-dark text-bold">Working Shift Information</h5>
                        </span>
                        <div class="form-group row mt-4">
                            <label class="col-md-2 col-xs-12 control-label" for="shift_type">Shift Type:</label>
                            <div class="col-sm-6 controls">
                                <select name="shift_type" class="select2 form-control" disabled>
                                    <option value="shift" {{($user->shift_type == "shift")?'selected':''}}>Shift
                                    </option>
                                    <option value="non_shift" {{($user->shift_type == "non_shift")?'selected':''}}>
                                        Non Shift</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label" for="shift_name">Shift Name:</label>
                            <div class="col-sm-6 controls">
                                <input type="text" class="form-control" name="shift_name" placeholder="Shift Name..." value="{{$user->shift_name}}" readonly />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="calendar_id" class="col-md-2 col-xs-12 control-label">Calendar</label>
                            <div class="col-sm-6 controls">
                                <select name="calendar_id" id="calendar_id" class="select2 form-control" disabled></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label" for="time_in">Time In</label>
                            <div class="col-sm-6 controls">
                                <input type="text" class="form-control time-in" name="time_in" placeholder="Time In..." value="{{$user->time_in}}" readonly />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label" for="time_out">Time Out</label>
                            <div class="col-sm-6 controls">
                                <input type="text" class="form-control time-out" name="time_out" placeholder="Time Out..." value="{{$user->time_out}}" readonly />
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
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('workingshift.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
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
		$('.time-in').daterangepicker({
			timePicker: true,
			singleDatePicker: true,
			timePicker24Hour: true,
			timePickerIncrement: 1,
			timePickerSeconds: false,
			locale: {
				format: 'HH:mm'
			}
		}).on('show.daterangepicker', function (ev, picker) {
			picker.container.find('.calendar-table').hide();
		});
		$('.time-out').daterangepicker({
			timePicker: true,
			singleDatePicker: true,
			timePicker24Hour: true,
			timePickerIncrement: 1,
			timePickerSeconds: false,
			locale: {
				format: 'HH:mm'
			}
		}).on('show.daterangepicker', function (ev, picker) {
			picker.container.find('.calendar-table').hide();
		});
		$('.select2').select2();
		$('#calendar_id').select2({
			placeholder: "Select Calendar ...",
			ajax: {
				url: "{{ route('calendar.select') }}",
				type: "GET",
				dataType: "json",
				data: function(params) {
					return {
						name: params.term,
						page: params.page,
						limit: 30,
					};
				},
				processResults: function(data, params) {
					params.page = params.page || 1;
					var more		= (params.page * 30) < data.total;
					var option	= [];
					$.each(data.rows, function(index, item) {
						option.push({
							id: item.id,
							text: item.name,
						});
					});
					return { results: option, pagination: { more: more, }, };
				},
			},
			allowClear: true,
		});
        @if ($user->calendar)
            $('#calendar_id').select2('trigger', 'select', {
                data: {
                    id: `{{ $user->calendar->id }}`,
                    text: `{{ $user->calendar->name }}`,
                },
            });
        @endif
	});
</script>
@endsection