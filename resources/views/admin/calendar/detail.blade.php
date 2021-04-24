@extends('admin.layouts.app')

@section('title')
Detail {{ $menu_name }}
@endsection

@section('stylesheets')
<link href="{{asset('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.2/main.min.css" rel="stylesheet">
<style>
  #label_span {
    display: block;
    height: 1rem;
    width: 1rem;
  }

  #text_span {
    display: block;
    height: 1rem;
    width: 1rem;
  }

  .fc-daygrid-day {
    cursor: pointer;
    transition: .5s;
  }

  .fc-daygrid-day:hover {
    cursor: pointer;
    background: rgba(199, 234, 70, 0.2) !important;
    transition: .5s;
  }
</style>
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
      <li class="breadcrumb-item active">Detail</li>
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
          <form class="form-horizontal no-margin" action="{{route('calendar.update', ['id' => $calendar->id])}}" id="form" method="post" />
          {{ csrf_field() }}
          @method('PUT')
          <div class="card-body">
            <span class="title">
              <hr />
              <h5 class="text-md text-dark text-bold">Calendar Information</h5>
            </span>
            <div class="form-group row">
              <label class="col-md-2 col-xs-12 control-label" for="code">Code: <b class="text-danger">*</b></label>
              <div class="col-sm-6 controls">
                <input type="text" class="form-control" name="code" placeholder="Code..." value="{{ $calendar->code }}" disabled />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 col-xs-12 control-label" for="name">Name: <b class="text-danger">*</b></label>
              <div class="col-sm-6 controls">
                <input type="text" class="form-control" name="name" placeholder="Name..." value="{{ $calendar->name }}" disabled />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 col-xs-12 control-label" for="is_default">Active Calendar</label>
              <div class="col-sm-6 controls">
                <div class="icheck-primary">
                  <input type="checkbox" name="is_default" id="is_default" @if ($calendar->is_default == 'YES') checked @endif disabled="disabled">
                  <label for="is_default"></label>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 col-xs-12 control-label" for="description">Description:</label>
              <div class="col-sm-6 controls">
                <textarea class="form-control" name="description" rows="4" style="resize: none;" placeholder="Description..." disabled>{!! $calendar->description !!}</textarea>
              </div>
            </div>
          </div>
          </form>
        </div>
      </div>
      <div class="col-12">
        <div class="card">
          <ul class="nav nav-tabs tabs-engineering" id="tabs-calendar" role="tablist">
            <li class="nav-item"><a href="#tab-list-exception" class="nav-link active" id="list-exception" data-toggle="pill" role="tab" aria-controls="tab-list-exception" aria-selected="true">List Exception</a></li>
            <li class="nav-item"><a href="#tab-calendar-exception" class="nav-link" id="calendar-exception" data-toggle="pill" role="tab" aria-controls="tab-calendar-exception" aria-selected="true">Calendar Exception</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane" id="tab-list-exception">
              <div class="card-header">
                <span class="title">
                  <hr />
                  <h5 class="text-md text-dark text-bold">List Exception</h5>
                </span>
                <a class="btn btn-labeled btn-sm text-sm btn-default btn-flat legitRipple  float-right ml-1" onclick="filter()"><b><i class="fas fa-search"></i></b> Search</a>
                <a href="javascript:void(0)" onclick="addException()" class="btn btn-labeled btn-sm text-sm btn-success btn-flat legitRipple  float-right ml-1"><b><i class="fas fa-plus"></i></b> Add Exception</a>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="exception-table" class="table table-bordered table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th width="5">No.</th>
                        <th width="50">Date</th>
                        <th width="200">Description</th>
                        <th width="5">Action</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
            <div class="tab-pane active" id="tab-calendar-exception">
              <div class="card-header">
                <span class="title">
                  <hr />
                  <h5 class="text-md text-dark text-bold">Calendar Exception</h5>
                </span>
              </div>
              <div class="card-body">
                <div id="calendar_exception"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="add-exception" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Exception</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="form-exception" class="form-horizontal" method="POST" autocomplete="off">
          {{ csrf_field() }}
          <input type="hidden" name="calendar_id">
          <div class="form-group row">
            <label class="col-md-3 col-xs-12 control-label" for="description">Description: <b class="text-danger">*</b></label>
            <div class="col-sm-9 controls">
              <input type="text" class="form-control" name="description" placeholder="Description..." />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3 col-xs-12 control-label" for="label_color">Label color: <b class="text-danger">*</b></label>
            <div class="col-sm-9 controls">
              <div class="input-group my-colorpicker1 colorpicker-element" data-colorpicker-id="2">
                <input type="text" class="form-control" data-original-title="" title="" name="label_color" id="label_color">
                <div class="input-group-append">
                  <span class="input-group-text"><i class="fas fa-square"></i></span>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3 col-xs-12 control-label" for="text_color">Text color: <b class="text-danger">*</b></label>
            <div class="col-sm-9 controls">
              <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
                <input type="text" class="form-control" data-original-title="" title="" name="text_color" id="text_color">
                <div class="input-group-append">
                  <span class="input-group-text"><i class="fas fa-square"></i></span>
                </div>
              </div>
            </div>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="reccurence_day" id="reccurence_day" value="reccurence_day" onclick="reccurence_pattern()">
            <label class="form-check-label" for="reccurence_day">
              <b>Reccurence Day</b>
            </label>
          </div>
          <div class="row py-3">
            <div class="col-sm-6">
              <div class="form-check">
                <input class="form-check-input check-day" type="checkbox" value="monday" id="monday" name="day[]">
                <label class="form-check-label" for="day">
                  <b>Monday</b>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input check-day" type="checkbox" value="tuesday" id="tuesday" name="day[]">
                <label class="form-check-label" for="day">
                  <b>Tuesday</b>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input check-day" type="checkbox" value="wednesday" id="wednesday" name="day[]">
                <label class="form-check-label" for="day">
                  <b>Wednesday</b>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input check-day" type="checkbox" value="thursday" id="thursday" name="day[]">
                <label class="form-check-label" for="day">
                  <b>Thursday</b>
                </label>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-check">
                <input class="form-check-input check-day" type="checkbox" value="friday" id="friday" name="day[]">
                <label class="form-check-label" for="day">
                  <b>Friday</b>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input check-day" type="checkbox" value="saturday" id="saturday" name="day[]">
                <label class="form-check-label" for="day">
                  <b>Saturday</b>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input check-day" type="checkbox" value="sunday" id="sunday" name="day[]">
                <label class="form-check-label" for="day">
                  <b>Sunday</b>
                </label>
              </div>
            </div>
          </div>
          {{-- .Day Checkbox --}}
          <div class="form-group mb-0">
            <label class="control-label" for="recurrence_range">Range of Recurrence</label>
          </div>
          {{-- Start and Finish Date Reccurence --}}
          <div class="form-group row">
            <div class="col-sm-5 mr-5">
              <label class="control-label" for="start_range">Start</label>
              <div class="controls col-xs-10">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" name="start_range" id="start_range" class="form-control datepicker" placeholder="Start Date">
                </div>
              </div>
            </div>
            <div class="col-sm-5">
              <label class="control-label" for="finish_range">Finish</label>
              <div class="controls col-xs-10">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" name="finish_range" id="finish_range" class="form-control datepicker" placeholder="Finish Date">
                </div>
              </div>
            </div>
          </div>
          {{-- .Start and Finish Date Reccurence --}}
          <hr>
          {{-- Specific Day Radio --}}
          <div class="form-check">
            <input class="form-check-input" type="radio" name="reccurence_day" id="specific_day" value="specific_day" onclick="reccurence_pattern()">
            <label class="form-check-label" for="specific_day">
              <b>Specific Day</b>
            </label>
          </div>
          {{-- .Specific Day Radio --}}
          {{-- Specific Date --}}
          <div class="form-group row py-3">
            <label class="col-md-2 control-label" for="specific_date">Date</label>
            <div class="col-sm-5 controls">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                  </span>
                </div>
                <input type="text" name="specific_date" class="form-control datepicker" id="specific_date" placeholder="Date">
              </div>
            </div>
          </div>
          {{-- .Specific Date --}}
          {{-- Start and Finish Date Specific Day --}}
          <div class="form-group mb-0">
            <label class="control-label" for="recurrence_range">Range of Recurrence</label>
          </div>
          <div class="form-group row">
            <div class="col-sm-5 mr-5">
              <label class="control-label" for="start_range">Start</label>
              <div class="controls col-xs-10">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" name="start_specific" id="start_specific" class="form-control datepicker" placeholder="Start Date">
                </div>
              </div>
            </div>
            <div class="col-sm-5">
              <label class="control-label" for="finish_range">Finish</label>
              <div class="controls col-xs-10">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" name="finish_specific" id="finish_specific" class="form-control datepicker" placeholder="Finish Date">
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" name="_method" />
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="form-exception" class="btn btn-labeled btn-danger btn-sm btn-flat legitRipple"><b><i class="fas fa-plus"></i></b> Submit</button>
        <button type="button" class="btn btn-labeled btn-default btn-sm btn-flat legitRipple"><b><i class="fas fa-times" data-dismiss="modal"></i></b> Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="add-filter" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="filter-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Advance Filter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form method="post" id="form-search">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="description" class="control-label">Description</label>
                  <input type="text" name="description" id="description" class="form-control" placeholder="Description">
                </div>
              </div>
              {{-- <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="date">Finish</label>
                  <div class="controls col-xs-10">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="far fa-calendar-alt"></i>
                        </span>
                      </div>
                      <input type="text" name="date" id="date" class="form-control datepicker" placeholder="Finish Date">
                    </div>
                  </div>
                </div>
              </div> --}}
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-labeled btn-danger btn-sm btn-flat legitRipple" data-dismiss="modal"><b><i class="fas fa-times"></i></b> Cancel</button>
        <button type="submit" form="form-search" class="btn btn-labeled btn-default btn-sm btn-flat legitRipple"><b><i class="fas fa-search"></i></b> Search</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade modal-allow-overflow" id="edit-exception" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Exception</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mb-1">
        <form id="form-edit" class="form-horizontal" method="post" autocomplete="off">
          {{ csrf_field() }}
          <input type="hidden" name="_method" />
          <div class="row">
            <div class="form-group row col-12">
              <label class="col-sm-3 control-label" for="exception_date">Date</label>
              <div class="col-sm-5 controls">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" name="exception_date" id="exception_date" class="form-control datepicker2" placeholder="Date" required />
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group row col-12">
              <label class="col-sm-3 control-label" for="exception_desc">Description</label>
              <div class="col-sm-9 controls">
                <input type="text" class="form-control" name="exception_desc" id="exception_desc" placeholder="Description" required>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3 col-xs-12 control-label" for="exception_label">Label color: <b class="text-danger">*</b></label>
            <div class="col-sm-9 controls">
              <div class="input-group my-colorpicker1 colorpicker-element" data-colorpicker-id="2">
                <input type="text" class="form-control" data-original-title="" title="" name="exception_label" id="exception_label">
                <div class="input-group-append">
                  <span class="input-group-text"><i id="label_span"></i></span>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3 col-xs-12 control-label" for="exception_text">Text color: <b class="text-danger">*</b></label>
            <div class="col-sm-9 controls">
              <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
                <input type="text" class="form-control" data-original-title="" title="" name="exception_text" id="exception_text">
                <div class="input-group-append">
                  <span class="input-group-text"><i id="text_span"></i></span>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" name="calendar_id">
          <input type="hidden" name="exception_id">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="form-edit" class="btn btn-labeled btn-danger btn-sm btn-flat legitRipple"><b><i class="fas fa-plus"></i></b> Submit</button>
        <button type="button" class="btn btn-labeled btn-default btn-sm btn-flat legitRipple"><b><i class="fas fa-times" data-dismiss="modal"></i></b> Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade modal-allow-overflow" id="add-calendar" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Calendar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mb-1">
        <form id="form-calendar" class="form-horizontal" method="post" autocomplete="off">
          {{ csrf_field() }}
          <div class="form-group row">
            <label class="col-sm-3 control-label" for="calendar_date">Date</label>
            <div class="col-sm-5 controls">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                  </span>
                </div>
                <input type="text" name="calendar_date" id="calendar_date" class="form-control" placeholder="Date" readonly required />
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 control-label" for="calendar_desc_add">Description</label>
            <div class="col-sm-9 controls">
              <input type="text" class="form-control" name="calendar_desc_add" id="calendar_desc_add" placeholder="Description" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="calendar_label">Label Color</label>
            <div class="col-sm-9">
              <div class="input-group my-colorpicker2">
                <input type="text" class="form-control" name="calendar_label" id="calendar_label">
                <div class="input-group-append">
                  <span class="input-group-text input-group-addon">
                    <i id="label_span"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="calendar_text">Text Color</label>
            <div class="col-sm-9">
              <div class="input-group my-colorpicker2">
                <input type="text" class="form-control" name="calendar_text" id="calendar_text">
                <div class="input-group-append">
                  <span class="input-group-text input-group-addon">
                    <i id="text_span"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" name="id_calendar">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="form-calendar" class="btn btn-labeled btn-danger btn-sm btn-flat legitRipple"><b><i class="fas fa-plus"></i></b> Submit</button>
        <button type="button" class="btn btn-labeled btn-default btn-sm btn-flat legitRipple"><b><i class="fas fa-times" data-dismiss="modal"></i></b> Cancel</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.2/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.2/locales-all.min.js"></script>
<script type="text/javascript">
  $(function() {
    $("#form-search").submit(function(e) {
      e.preventDefault();
      dataTableException.draw();
      $("#add-filter").modal('hide');
    });
    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        timePicker: false,
        timePickerIncrement: 30,
        locale: {
        format: 'DD/MM/YYYY'
        }
    });
    // Datepirkcer for Search
    $('.datepicker2').daterangepicker({
        singleDatePicker: true,
        timePicker: false,
        timePickerIncrement: 30,
        locale: {
        format: 'DD/MM/YYYY'
        }
    });
    $('.timepicker').daterangepicker({
			singleDatePicker: true,
			timePicker: true,
			timePicker24Hour: true,
			timePickerIncrement: 1,
			timePickerSeconds: false,
			locale: {
				format: 'HH:mm'
			}
		}).on('show.daterangepicker', function(ev, picker) {
			picker.container.find('.calendar-table').hide();
    });
    $(document).on("change", ".select2", function () {
			if (!$.isEmptyObject($('#form').validate().submitted)) {
				$('#form').validate().form();
			}
		});
    $('.my-colorpicker1').colorpicker();

    $('.my-colorpicker1').on('colorpickerChange', function(event) {
      $('.my-colorpicker1 .fa-square').css('color', event.color.toString());
    });
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
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
    dataTableException  = $('#exception-table').DataTable({
      processing: true,
      language: {
        processing: `<div class="p-2 text-center">
                        <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading ...
                     </div>`
      },
      serverSide: true,
      aaSorting: [],
      filter: false,
      responsive: true,
      lengthChange: false,
      order: [[ 1, "asc"]],
      ajax: {
        url: "{{ route('calendarexception.read') }}",
        type: "GET",
        data: function (data) {
          var date          = $('#form-search-exception').find('#date').val();
          var description   = $('#form-search-exception').find('#description').val();
          data.calendar_id  = {{ $calendar->id }};
          data.date         = date;
          data.description  = description;
        }
      },
      columns: [
        { "data": "no", "name": "no", width: 10, className: "text-center", orderable: false },
        { "data": "date_exception", "name": "date", width: 100 },
        { "data": "description", "name": "description", width: 120, orderable: false },
        { 
          width: 50,
          className: "text-center",
          orderable: false,
          render: function(data, type, full, meta) {
            return `<div class="btn-group">
                        <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="javascript:void(0);" onclick="editException(${full.id})">
                                <i class="far fa-edit"></i>Update Data
                            </a>
                            <a class="dropdown-item " href="javascript:void(0);" onclick="destroyException(${full.id})">
                                <i class="far fa-trash-alt"></i> Delete Data
                            </a>
                        </div>
                    </div>`;
          }
        }
      ]
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

    $("#form-exception").validate({
      errorElement: 'div',
      errorClass: 'invalid-feedback',
      focusInvalid: false,
      highlight: function (e) {
        $(e).closest('.form-group').removeClass('has-success').addClass('was-validated has-error');
      },
      success: function (e) {
        $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
        $(e).remove();
      },
      errorPlacement: function (error, element) {
        if(element.is(':file')) {
          error.insertAfter(element.parent().parent().parent());
        } else if(element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else if (element.attr('type') == 'checkbox') {
          error.insertAfter(element.parent());
        } else {
          error.insertAfter(element);
        }
      },
      submitHandler: function() {
        $.ajax({
          url: $('#form-exception').attr('action'),
          method: 'POST',
          data: new FormData($('#form-exception')[0]),
          processData: false,
          contentType: false,
          dataType: 'json',
          success:function(result){
            $('#form-exception').unblock();
            if (result.status) {
              dataTableException.draw();
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
              toastr.success(result.message);
              $("#add-exception").modal("hide");
            } else {
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
          beforeSend: function () {
            blockMessage('#form-exception', 'Loading', '#fff');
          }
        });
      }
    });
    $("#form-edit").validate({
      errorElement: 'div',
      errorClass: 'invalid-feedback',
      focusInvalid: false,
      highlight: function (e) {
        $(e).closest('.form-group').removeClass('has-success').addClass('was-validated has-error');
      },
      success: function (e) {
        $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
        $(e).remove();
      },
      errorPlacement: function (error, element) {
        if(element.is(':file')) {
          error.insertAfter(element.parent().parent().parent());
        } else if(element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else if (element.attr('type') == 'checkbox') {
          error.insertAfter(element.parent());
        } else {
          error.insertAfter(element);
        }
      },
      submitHandler: function() {
        $.ajax({
          url: $('#form-edit').attr('action'),
          method: 'POST',
          data: new FormData($('#form-edit')[0]),
          processData: false,
          contentType: false,
          dataType: 'json',
          success:function(result){
            $('#form-edit').unblock();
            if (result.status) {
              dataTableException.draw();
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
              toastr.success(result.message);
              $("#edit-exception").modal("hide");
            } else {
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
          beforeSend: function () {
            blockMessage('#form-edit', 'Loading', '#fff');
          }
        });
      }
    });
    $("#form-calendar").validate({
      errorElement: 'div',
      errorClass: 'invalid-feedback',
      focusInvalid: false,
      highlight: function (e) {
        $(e).closest('.form-group').removeClass('has-success').addClass('was-validated has-error');
      },
      success: function (e) {
        $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
        $(e).remove();
      },
      errorPlacement: function (error, element) {
        if(element.is(':file')) {
          error.insertAfter(element.parent().parent().parent());
        } else if(element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else if (element.attr('type') == 'checkbox') {
          error.insertAfter(element.parent());
        } else {
          error.insertAfter(element);
        }
      },
      submitHandler: function() {
        $.ajax({
          url: $('#form-calendar').attr('action'),
          method: 'POST',
          data: new FormData($('#form-calendar')[0]),
          processData: false,
          contentType: false,
          dataType: 'json',
          success:function(result){
            $('#form-calendar').unblock();
            if (result.status) {
              dataTableException.draw();
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
              toastr.success(result.message);
              $("#add-calendar").modal("hide");
            } else {
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
          beforeSend: function () {
            blockMessage('#form-calendar', 'Loading', '#fff');
          }
        });
      }
    });

    var calendarEl = document.getElementById('calendar_exception');
    var id = {!! $calendar->id !!};
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        start: 'prev,today',
        center: 'title',
        end: 'today,next'
      },
      events: `{{url('admin/calendarexception')}}/${id}/calendar`,
      firstDay: 1,
      dateClick: function (info) {
        $('#add-calendar .modal-title').html('Add Calendar');
        $('#add-calendar').modal('show');
        $('#form-calendar')[0].reset();
        $('#form-calendar .invalid-feedback').each(function () { $(this).remove(); });
        $('#form-calendar .form-group').removeClass('has-error').removeClass('has-success');
        $('#form-calendar input[name=id_calendar]').attr('value',{{ $calendar->id }});
        $('#form-calendar input[name=calendar_date]').attr('value', info.dateStr);
        $('#form-calendar input[name=calendar_desc_add]').attr('placeholder', 'Description');
        $('#form-calendar input[name=calendar_label]').attr('value', '#000');
        $('#form-calendar input[name=calendar_text]').attr('value', '#fff');
        $('#form-calendar #label_span').css('background-color', '#000');
        $('#form-calendar #text_span').css('background-color', '#fff');
        $('#form-calendar').attr('action',"{{ route('calendarexception.addcalendar') }}");
      }
    });
    calendar.render();
    $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
      var currentTab = $(e.target).text();
      switch (currentTab) {
        case 'List Exception':
          $('#table-exception').css("width", "100%")
          $($.fn.dataTableException.tables(true)).DataTable().columns.adjust().responsive.recalc();
          break;
        case 'Calendar Exception':
        calendar.refetchEvents();
        calendar.render();
          break;
      
        default:
          break;
      }
    });
  });
  function reccurence_pattern() {
    var val = $('input[name="reccurence_day"]:checked').val();
    if (val == 'reccurence_day') {
      $("input.check-day").removeAttr("disabled");
      $('#start_range').attr("required", true);
      $('#finish_range').attr("required", true);
      $('#start_range').removeAttr("disabled");
      $('#finish_range').removeAttr("disabled");
      $('#specific_date').removeAttr("required");
      $('#start_specific').removeAttr("required");
      $('#finish_specific').removeAttr("required");
      $('#start_specific').attr("disabled", true);
      $('#finish_specific').attr("disabled", true);
      $('#specific_date').attr("disabled", true);
    } else {
      $("input.check-day").prop("checked", false);
      $("input.check-day").attr("disabled", true);
      $('#start_range').removeAttr("required");
      $('#finish_range').removeAttr("required");
      $('#specific_date').attr("required", true);
      $('#start_specific').attr("required", true);
      $('#finish_specific').attr("required", true);
      $('#specific_date').removeAttr("disabled");
      $('#start_specific').removeAttr("disabled");
      $('#finish_specific').removeAttr("disabled");
      $('#start_range').attr("disabled", true);
      $('#finish_range').attr("disabled", true);
    }
  }

  function editException(id) {
    $.ajax({
      url:`{{url('admin/calendarexception')}}/${id}/edit`,
      method:'GET',
      dataType:'json',
      beforeSend:function(){
        blockMessage('#edit-exception', 'Loading', '#fff');
      },
    }).done(function(response){
      $('#edit-exception').unblock();
      if(response.status){
        var date = changeDateFormat(response.data.date_exception);
        $('#edit-exception .modal-title').html('Edit Exception');
        $('#edit-exception').modal('show');
        $('#form-edit')[0].reset();
        $('#form-edit .invalid-feedback').each(function () { $(this).remove(); });
        $('#form-edit .form-group').removeClass('has-error').removeClass('has-success');
        $('#form-edit input[name=_method]').attr('value','PUT');
        $('#form-edit input[name=calendar_id]').attr('value',{{ $calendar->id }});
        $('#form-edit input[name=exception_id]').attr('value',id);
        $('#form-edit input[name=exception_date]').daterangepicker({startDate:date, endDate:date, singleDatePicker:true, locale:{format:'DD/MM/YYYY'}});
        $('#form-edit input[name=exception_desc]').attr('value',response.data.description);
        $('#form-edit input[name=exception_label]').attr('value',response.data.label_color);
        $('#form-edit input[name=exception_text]').attr('value',response.data.text_color);
        $('#form-edit #label_span').css('background-color',response.data.label_color);
        $('#form-edit #text_span').css('background-color',response.data.text_color);
        $('#form-edit').attr('action',`{{url('admin/calendarexception/')}}/${response.data.id}`);

        dataTableException.draw();
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
        toastr.success(result.message);
        $("#add-exception").modal("hide");
      }          
    }).fail(function(response){
      var response = response.responseJSON;
      $('#form-exception').unblock();
      dataTableException.draw();
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
      $("#add-exception").modal("hide");
    });
  }

  function destroyException(id)
	{
		Swal.fire({
			title: 'Hapus',
			text: "Apa Anda Yakin Akan Menghapus Data ?",
			icon: 'error',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Hapus!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.value) {
                var data = {
                    _token: "{{ csrf_token() }}"
                };
				$.ajax({
					url: `{{url('admin/calendarexception')}}/${id}`,
					dataType: 'json', 
					data:data,
					type:'DELETE',
					success:function(response){
						if(response.status){
              dataTableException.ajax.reload(null, false);
						}
						else{
							Swal.fire(
								'Error!',
								'Data Gagal Di Hapus.',
								'error'
							)
						}
				}});
			}
		});
	}
  
  function changeDateFormat(date) {
    var piece = date.split('-');
    var newdate = piece[1]+'/'+piece[2]+'/'+piece[0];

    return new Date(newdate);
  }

  function cancel_edit() {
    $('#edit-exception').modal('hide');
    $('#form-edit').find('input[name=exception_id]').val();
    $('#form-edit').find('#exception_desc').val('');
    $('#form-edit').find('#exception_date').daterangepicker({
      startDate:moment(), endDate:moment(), singleDatePicker:true, locale:{format:'DD/MM/YYYY'}
    });
  }

  function filter() {
    $("#add-filter").modal("show");
  }

  function addException() {
    // $('#form-exception')[0].reset();
    $('#form-exception').attr('action', "{{ route('calendarexception.store') }}");
    $('#form-exception input[name=_method]').attr('value', 'POST');
    $('#form-exception input[name=calendar_id]').attr('value', {{ $calendar->id }});
    $('#form-exception input[name=description]').attr('value', '');
    $("#add-exception").modal("show");
    $("input.check-day").attr("disabled", true);
    $('#start_specific').attr("disabled", true);
    $('#finish_specific').attr("disabled", true);
    $('#specific_date').attr("disabled", true);
    $('#start_range').attr("disabled", true);
    $('#finish_range').attr("disabled", true);
  }
</script>
@endsection