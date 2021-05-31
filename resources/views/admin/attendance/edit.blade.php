@extends('admin.layouts.app')
@section('title', @$menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">
      Create {{ @$menu_name }}
    </h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ @$parent_name }}</li>
      <li class="breadcrumb-item">{{ @$menu_name }}</li>
      <li class="breadcrumb-item">Create</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <form action="{{ route('attendance.update', ['id' => $attendance->id]) }}" role="form" enctype="multipart/form-data" method="post" autocomplete="off" id="form">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">{{ $menu_name }} Information</h5>
              </span>
              <div class="mt-5"></div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="employee_name">Employee ID</label>
                    <input type="text" name="employee_name" class="form-control" placeholder="Employee ID" disabled value="{{ @$attendance->employee->name }}">
                    <input type="hidden" name="employee_id" class="form-control" value="{{ $attendance->employee->id }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="position">Position</label>
                    <input type="text" name="position" class="form-control" placeholder="Position" disabled value="{{ @$attendance->employee->position }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="shift_type">Shift Type</label>
                    <input type="text" name="shift_type" class="form-control" placeholder="Shift Type" disabled value="{{ ucwords(str_replace('_', ' ', @$attendance->employee->shift_type)) }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-12 col-xs-12 control-label" for="shift">Shift</label>
                    <div class="col-sm-12 controls">
                      <select name="shift" id="shift" class="form-control select2" @if (!in_array('approval', $actionmenu)) disabled @endif>
                        <option value="">Select Shift</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @if (in_array('approval', $actionmenu))
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">{{ $menu_name }} Log</h5>
              </span>
              <div class="mt-5"></div>
              <table id="attendance-id" class="table table-striped datatable" width="100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Machine</th>
                    {{-- <th>Action</th> --}}
                  </tr>
                </thead>
              </table>
            </div>
          </div>
          @endif
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">Time Information</h5>
              </span>
              <div class="mt-5"></div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="check_in">Check In</label>
                    <input type="text" name="check_in" class="form-control datepicker text-right check-in" placeholder="Check In" value="{{ @$attendance->attendance_in }}" @if (!in_array('approval', $actionmenu)) disabled @endif>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="check_out">Check Out</label>
                    <input type="text" name="check_out" class="form-control datepicker text-right check-out" placeholder="Check Out" value="{{ @$attendance->attendance_out }}" @if (!in_array('approval', $actionmenu)) disabled @endif>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="working_time">Working Time</label>
                    <input type="text" name="working_time" class="form-control" placeholder="Working Time" value="{{ @$attendance->working_time }}" @if (!in_array('approval', $actionmenu)) disabled @endif>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="over_time">Over Time</label>
                    <input type="text" name="over_time" class="form-control" placeholder="Over Time" value="{{ @$attendance->over_time }}" @if (!in_array('approval', $actionmenu)) disabled @endif>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label" for="description">Note</label>
                    <textarea class="form-control summernote" name="description" id="description" rows="4" placeholder="Description..." @if (!in_array('approval', $actionmenu)) disabled @endif>{{ @$attendance->remarks }}</textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <input type="hidden" name="status">
              @if (in_array('approval', $actionmenu))
              <button type="button" onclick="submitTest(`approved`)" class="btn btn-success btn-labeled legitRipple btn-sm text-sm" data-type="approved">
                <b><i class="fas fa-check-circle"></i></b>
                Approved
              </button>
              @endif
              @if ($countDayDiff < 2) <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm checkout">
                <b><i class="fas fa-save"></i></b> Check Out
                </button>
                @endif
                <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                  <b><i class="fas fa-times"></i></b> Cancel
                </a>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection

@section('scripts')
<script>
  function submitTest(status) {
    if (status) {
      $('input[name=status]').val(status);
    }
    $("form").first().trigger("submit");
  }
  $(function() {
    $('.check-in').daterangepicker({
      singleDatePicker: true,
      timePicker: true,
      timePickerIncrement: 1,
      timePicker24Hour: true,
      timePickerSeconds: false,
      drops: 'auto',
      opens: 'center',
      locale: {
        format: 'YYYY-MM-DD hh:mm:ss'
      }
    });
    @if ($attendance->attendance_out)
      $('.check-out').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePickerIncrement: 1,
        timePicker24Hour: true,
        timePickerSeconds: false,
        drops: 'auto',
        opens: 'center',
        locale: {
          format: 'YYYY-MM-DD hh:mm:ss'
        }
      });
    @else  
      $('.check-out').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePickerIncrement: 1,
        timePicker24Hour: true,
        startDate: moment(),
        timePickerSeconds: false,
        drops: 'auto',
        opens: 'center',
        locale: {
          format: 'YYYY-MM-DD hh:mm:ss'
        }
      });
    @endif
    $("#shift").select2({
        ajax: {
            url: "{{route('workingshift.select')}}",
            type: 'GET',
            dataType: 'json',
            data: function(params) {
                return {
                    name: params.term,
                    page: params.page,
                    limit: 30,
                };
            },
            processResults: function(data, params) {
                var more = (params.page * 30) < data.total;
                var option = [];
                $.each(data.rows, function(index, item) {
                    option.push({
                        id: item.id,
                        text: item.shift_name + ' | ' + item.time_in + ' - ' + item.time_out,
                        time_in: item.time_in,
                        time_out: item.time_out,
                    });
                });
                return {
                    results: option,
                    more: more,
                };
            },
        },
        allowClear: true,
    });
    $('#shift').select2('trigger', 'select', {
      data: {id: `{{ $attendance->working_shift_id }}`, text: `{{ $attendance->shift->shift_name }}`}
    });

    $('#form').validate({
      errorElement: 'span',
      errorClass: 'help-block',
      focusInvalid: false,
      highlight: function(e) {
        $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
      },
      success: function (e) {
        $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
        $(e).remove();
      },
      errorPlacement: function(error, element) {
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
        });
      }
    });

    dataTable = $('.datatable').DataTable( {
        processing: true,
        language: {
            processing: `<div class="p-2 text-center">
        <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...
        </div>`
        },
        serverSide: true,
        filter: false,
        responsive: true,
        lengthChange: false,
        order: [[ 1, "asc" ]],
        ajax: {
            url: "{{route('attendancelog.read')}}",
            type: "GET",
            data:function(data){
                data.attendance_id     = `{{ $attendance->id }}`;
            }
        },
        columnDefs:[
            {
                orderable: false,targets:[0]
            },
            { className: "text-right", targets: [0,1] },
            { className: "text-center", targets: [] },
            {
              width: "2%",
              render: function(data, type, row) {
                return row.no;
              }, targets: [0]
            },
            {
              width: "10%",
              render: function(data, type, row) {
                return row.machine ? row.machine.machine_name : '-';
              }, targets: [3]
            },
        ],
        columns: [
            { data: "no" },
            { data: "attendance" },
            { data: "type" },
            { data: "machine_id" },
        ]
    });
  });

</script>
@endsection