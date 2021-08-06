@extends('admin.layouts.app')
@section('title', @$menu_name)

@section('stylesheets')
<style>
  .table#attendancerequest-id td {
    vertical-align: middle !important;
  }
</style>
@endsection

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
                      <select name="shift" id="shift" class="form-control select2 spv-access">
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
            <div class="card-body text-right">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">{{ $menu_name }} Log</h5>
              </span>
              <div class="mt-2"></div>
              <button type="button" id="request-attendance" class="btn text-sm btn-sm btn-warning btn-flat legitRipple" onclick="request()">
                <b><i class="fas fa-book-open"></i></b>
              </button>
              <div class="mt-2"></div>
              <ul class="nav nav-tabs" id="attendanceTab" role="tablist">
                <li class="nav-item">
                  <button type="button" class="nav-link active pl-4 pr-4" id="attendancerequest-tab" data-toggle="tab" data-target="#attendancerequest" role="tab" aria-controls="document" aria-selected="true"><b>Attendance Requests</b></button>
                </li>
                <li class="nav-item">
                  <button type="button" class="nav-link pl-4 pr-4" id="attendancelog-tab" data-toggle="tab" data-target="#attendancelog" role="tab" aria-controls="document" aria-selected="true"><b>Attendance Logs</b></button>
                </li>
              </ul>
              <div class="tab-content" id="dataTabContent">
                <div class="tab-pane fade show" id="attendancelog" role="tabpanel" aria-labelledby="attendancelog-tab">
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
                <div class="tab-pane fade show active" id="attendancerequest" role="tabpanel" aria-labelledby="attendancerequest-tab">
                  <table id="attendancerequest-id" class="table table-striped datatable" width="100%">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Request Reason</th>
                        <th>Type of Change</th>
                        <th>Before Change</th>
                        <th>Request Change</th>
                        <th>Request Time</th>
                        <th>Status</th>
                        <th>#</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
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
                    <input type="text" name="check_in" class="form-control datepicker check-in spv-access" placeholder="Check In" value="{{ @$attendance->attendance_in }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="check_out">Check Out</label>
                    <input type="text" name="check_out" class="form-control datepicker check-out spv-access" placeholder="Check Out" value="{{ @$attendance->attendance_out }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="working_time">Working Time</label>
                    <input type="text" name="working_time" class="form-control" disabled placeholder="Working Time" value="{{ @$attendance->working_time }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="over_time">Over Time</label>
                    <input type="text" name="over_time" class="form-control" disabled placeholder="Over Time" value="{{ @$attendance->over_time }}">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label" for="description">Note</label>
                    <textarea class="form-control summernote spv-access" name="description" id="description" rows="4" placeholder="Description...">{{ @$attendance->remarks }}</textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <input type="hidden" name="status">
              <input type="hidden" name="type">
              @if (in_array('approval', $actionmenu) && ($attendance->employee->user->spv_id == Auth::guard('admin')->user()->id) && $attendance->status == 'WAITING')
              <button type="button" onclick="submitTest(`approved`)" class="btn btn-success btn-labeled legitRipple btn-sm text-sm" data-type="approved">
                <b><i class="fas fa-check-circle"></i></b>
                Approved
              </button>
              <button type="button" onclick="submitTest(`waiting`)" class="btn btn-success btn-labeled legitRipple btn-sm text-sm" data-type="approved">
                <b><i class="fas fa-save"></i></b>
                Save
              </button>
              @endif
              @if (($attendance->attendance_date == date('Y-m-d')) && ($attendance->employee->user->id == Auth::guard('admin')->user()->id)) <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm checkout">
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
<div class="modal fade" id="add-request" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="request-modal" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Request Attendance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('attendancerequest.store') }}" id="form-request" method="post">
          <div class="container-fluid">
            @csrf
            <input type="hidden" name="_method">
            <input type="hidden" name="request_id">
            <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="type_request">Type</label>
                  <select name="type_request" id="type_request" class="form-control select2" onchange="changeType(this)">
                    <option value="">Select Type</option>
                    <option value="checkin">Check In</option>
                    <option value="checkout">Check Out</option>
                    <option value="shift">Shift</option>
                  </select>
                </div>
              </div>
              <div class="col-md-12 in-request">
                <div class="form-group">
                  <label for="attendance_in" class="control-label">Check In</label>
                  <input type="text" name="attendance_in" class="form-control datepicker attendance-in" placeholder="Check In">
                </div>
              </div>
              <div class="col-md-12 out-request">
                <div class="form-group">
                  <label for="attendance_out" class="control-label">Check Out</label>
                  <input type="text" name="attendance_out" class="form-control datepicker attendance-out" placeholder="Check Out">
                </div>
              </div>
              <div class="col-md-12 shift-request">
                <div class="form-group">
                  <label class="control-label" for="shift_request">Shift</label>
                  <select name="shift_request" id="shift_request" class="form-control select2">
                    <option value="">Select Shift</option>
                  </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="request_reason">Request Reason</label>
                  <textarea class="form-control summernote" name="request_reason" id="request_reason" rows="4" placeholder="Request Reason..."></textarea>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <button type="submit" id="submit-request" form="form-request" class="btn btn-labeled text-sm btn-sm btn-danger btn-flat legitRipple">
          <b><i class="fas fa-save"></i></b>
          Request
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  var actionmenu  = JSON.parse('{!! json_encode($actionmenu) !!}');

  const request = () => {
    $('#add-request').modal('show');
  }

  const changeType = (e) => {
    var type = $(e).val();
    switch (type) {
      case 'checkin':
        $('.in-request').removeClass('d-none');
        $('.out-request').addClass('d-none');
        $('.shift-request').addClass('d-none');

        $('.attendance-out').val('');
        $('#shift_request').val('').trigger('change');
        break;
      case 'checkout':
        $('.out-request').removeClass('d-none');
        $('.in-request').addClass('d-none');
        $('.shift-request').addClass('d-none');

        $('.attendance-in').val('');
        $('#shift_request').val('').trigger('change');
        break;
      case 'shift':
        $('.in-request').addClass('d-none');
        $('.out-request').addClass('d-none');
        $('.shift-request').removeClass('d-none');

        $('.attendance-out').val('');
        $('.attendance-in').val('');
        break;
    
      default:
        $('.in-request').addClass('d-none');
        $('.out-request').addClass('d-none');
        $('.shift-request').addClass('d-none');

        $('.attendance-out').val('');
        $('.attendance-in').val('');
        $('#shift_request').val('').trigger('change');
        break;
    }
  }

  const summernote = () => {
    $('.summernote').summernote({
    	height:145,
      contenteditable: false,
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

  const approveRequest = (id, type) => {
    var title = type == 'APPROVED' ? 'Approve request attendance?' : 'Reject request attendance?';
    var message = type == 'APPROVED' ? 'Are you sure want to approve this request?' : 'Are you sure want to reject this request?';
    bootbox.confirm({
      buttons: {
        confirm: {
          label: `<i class="fa fa-check"></i>`,
          className: 'btn-primary btn-sm',
        },
        cancel: {
          label: '<i class="fa fa-undo"></i>',
          className: 'btn-default btn-sm',
        },
      },
      title: title,
      message: message,
      callback: function (result) {
          if (result) {
              var data = {
                  _token: "{{ csrf_token() }}",
                  type: type,
                  id: id
              };
              $.ajax({
                  url: `{{route('attendancerequest.approve')}}`,
                  dataType: 'json',
                  data: data,
                  method: 'post',
                  beforeSend: function () {
                      blockMessage('#content', 'Loading', '#fff');
                  }
              }).done(function (response) {
                  $('#content').unblock();
                  if (response.status) {
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
                      toastr.success(response.message);
                      tableRequest.ajax.reload(null, false);
                      console.log(response);

                      switch (response.type) {
                        case 'checkin':
                          $('.check-in').val(response.value).change();
                          break;
                        case 'checkout':
                          $('.check-out').val(response.value).change();
                          break;
                      
                        default:
                          $('#shift').select2('trigger', 'select', { data: {id: response.value.id, text: `${response.value.text}`}
                          });
                          break;
                      }
                  }else {
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
              }).fail(function (response) {
                  var response = response.responseJSON;
                  $('#content').unblock();
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
      }
    })
  }

  const detailRequest = (id) => {
    $.ajax({
      url: `{{ url('admin/attendancerequest') }}/${id}/edit`,
      method:'GET',
      dataType:'json',
      beforeSend:function(){
        blockMessage('#add-request', 'Loading', '#fff');
      },
    }).done(function(response){
      $('#add-request').unblock();
      if (response.status) {
        var spv_id  = `{!! $attendance->employee->user->spv_id !!}`;
        var user_id = `{!! Auth::guard('admin')->user()->id !!}`;
        var type    = response.data.type;
        if (response.data.status == 'WAITING' && actionmenu.indexOf('approval') > 0 && spv_id == user_id) {
          console.log(response.data);
          $('#add-request .modal-title').html('Edit Exception');
          $('#add-request').modal('show');
          $('#form-request')[0].reset();
          $('#form-request .invalid-feedback').each(function () { $(this).remove(); });
          $('#form-request .form-group').removeClass('has-error').removeClass('has-success');
          $('#form-request input[name=_method]').attr('value','PUT');
          $('#form-request input[name=request_id]').attr('value', id);
          $(`#form-request select[name=type_request] option[value=${type}]`).attr('selected', 'selected').trigger('change');
          switch (response.data.type) {
            case 'checkin':
              $('#form-request input[name=attendance_in]').attr('value', response.data.request_date);
              $('#form-request input[name=attendance_in]').data('daterangepicker').setStartDate(`${response.data.request_date}`);
              break;
            case 'checkout':
              $('#form-request input[name=attendance_out]').attr('value', response.data.request_date);
              $('#form-request input[name=attendance_out]').data('daterangepicker').setStartDate(`${response.data.request_date}`);
              break;
          
            default:
              $('#form-request select[name=shift_request]').select2('trigger', 'select', {
                data: {id: response.data.requestshift.id, text: response.data.requestshift.shift_name}
              });
              break;
          }
          $('#form-request #request_reason').summernote('code', response.data.request_reason);
          $('#form-request').attr('action',`{{url('admin/attendancerequest/')}}/${response.data.id}`);
        } else {
          $('#add-request .modal-title').html('Detail Exception');
          $('#add-request').modal('show');
          $('#form-request')[0].reset();
          $('#form-request .invalid-feedback').each(function () { $(this).remove(); });
          $('#form-request .form-group').removeClass('has-error').removeClass('has-success');
          $('#form-request input[name=_method]').attr('value','PUT');
          $('#form-request input[name=request_id]').attr('value', id);
          $(`#form-request select[name=type_request] option[value=${type}]`).attr('selected', 'selected').trigger('change');
          $('#form-request select[name=type_request]').prop('disabled', true);
          switch (response.data.type) {
            case 'checkin':
              $('#form-request input[name=attendance_in]').attr('readonly', true);
              $('#form-request input[name=attendance_in]').attr('value', response.data.request_date);
              break;
            case 'checkout':
              $('#form-request input[name=attendance_out]').attr('readonly', true);
              $('#form-request input[name=attendance_out]').attr('value', response.data.request_date);
              break;
          
            default:
              $('#form-request select[name=shift_request]').prop('disabled', true);
              $('#form-request select[name=shift_request]').select2('trigger', 'select', {
                data: {id: response.data.requestshift.id, text: response.data.requestshift.shift_name}
              });
              break;
          }
          $('#form-request #request_reason').summernote('code', response.data.request_reason);
          $('#form-request #request_reason').summernote('disable');
          $('#submit-request').prop('disabled', true);
          $('#form-request').attr('action',`{{url('admin/attendancerequest/')}}/${response.data.id}`);
        }
      } else {
        $('#form-request').unblock();
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
        toastr.warning(response.data);
      }
    }).fail(function(response){
      var response = response.responseJSON;
      $('#form-request').unblock();
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
      toastr.warning(response.data);
    });
  }
  function submitTest(status) {
    if (status) {
      $('input[name=status]').val(status);
    }
    $("form").first().trigger("submit");
  }
  $(function() {
    $('input[name="shift_type"]').val() == 'Hourly' ? $('#shift').parent().parent().addClass('d-none') : $('#shift').parent().parent().removeClass('d-none')
    summernote();
    $('#type_request').trigger('change');
    $('.spv-access').prop('disabled', true)
    $('#description').summernote('disable');
    @if (($attendance->employee->user->spv_id == Auth::guard('admin')->user()->id) && in_array('approval', $actionmenu))
      $('#description').summernote('enable');
      $('.spv-access').prop('disabled', false)
    @endif
    $('.select2').select2();
    $('.datepicker').daterangepicker({
      singleDatePicker: true,
      timePicker: true,
      timePickerIncrement: 1,
      timePicker24Hour: true,
      timePickerSeconds: false,
      autoUpdateInput: false,
      drops: 'auto',
      opens: 'center',
      locale: {
        format: 'YYYY-MM-DD HH:mm:ss'
      }
    }).on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
    }).on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
    $('.check-in').daterangepicker({
      singleDatePicker: true,
      timePicker: true,
      timePickerIncrement: 1,
      timePicker24Hour: true,
      timePickerSeconds: false,
      autoUpdateInput: false,
      drops: 'auto',
      opens: 'center',
      locale: {
        format: 'YYYY-MM-DD HH:mm:ss'
      }
    }).on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
    }).on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
    $('.check-out').daterangepicker({
      singleDatePicker: true,
      timePicker: true,
      timePickerIncrement: 1,
      timePicker24Hour: true,
      timePickerSeconds: false,
      autoUpdateInput: false,
      drops: 'auto',
      opens: 'center',
      locale: {
        format: 'YYYY-MM-DD HH:mm:ss'
      }
    }).on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
    }).on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
    $("#shift_request").select2({
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
    @if ($attendance->working_shift_id)  
      $('#shift').select2('trigger', 'select', {
        data: {id: `{{ $attendance->working_shift_id }}`, text: `{{ $attendance->shift->shift_name }}`}
      });
    @endif

    $("#form-request").validate({
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
                url:$('#form-request').attr('action'),
                method:'post',
                data: new FormData($('#form-request')[0]),
                processData: false,
                contentType: false,
                dataType: 'json', 
                beforeSend:function(){
                    blockMessage('#content', 'Loading', '#fff');
                }
            }).done(function(response){
                $('#content').unblock();
                if(response.status){
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
                    toastr.success(response.message);
                    $('#add-request').modal('hide');
                    tableRequest.draw();
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

    dataTable = $('#attendance-id').DataTable( {
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
    tableRequest = $('#attendancerequest-id').DataTable( {
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
            url: "{{route('attendancerequest.read')}}",
            type: "GET",
            data:function(data){
                data.attendance_id     = `{{ $attendance->id }}`;
            }
        },
        columnDefs:[
            {
                orderable: false,targets:[0]
            },
            { className: "text-right", targets: [0] },
            { className: "text-center", targets: [5, 6, 7] },
            { className: "text-left", targets: [1, 2, 3, 4] },
            {
              width: "2%",
              render: function(data, type, row) {
                return row.no;
              }, targets: [0]
            },
            {
              width: "15%",
              render: function(data, type, row) {
                return `${row.short_reason} <a href="javascript:void(0)" onclick="detailRequest(${row.id})"><b>More <i class="fas fa-angle-double-right"></i></b></a>`
              },  targets: [1]
            },
            {
              width: "15%",
              render: function(data, type, row) {
                html = '';

                switch (row.type) {
                  case 'checkin':
                    html = 'Checkin Change'
                    break;
                  case 'checkout':
                    html = 'Checkout Change'
                    break;
                  case 'shift':
                    html = 'Shift Change'
                    break;
                
                  default:
                    html = 'Type not found'
                    break;
                }

                return html
              }, targets: [2]
            },
            {
              width: "15%",
              render: function(data , type, row) {
                html = '';
                switch (row.type) {
                  case 'checkin':
                  case 'checkout':
                    html = row.value_before
                    break;
                  case 'shift':
                    html = row.attendances.shift ? `${row.attendances.shift.shift_name} <br> ${row.attendances.shift.time_in} - ${row.attendances.shift.time_out}` : '-'
                    break;
                
                  default:
                    html = 'Change type not found'
                    break;
                }
                return html;
              }, targets: [3]
            },
            {
              width: "15%",
              render: function(data , type, row) {
                html = '';
                switch (row.type) {
                  case 'checkin':
                  case 'checkout':
                    html = row.request_date
                    break;
                  case 'shift':
                    html = row.requestshift ? `${row.requestshift.shift_name} <br> ${row.requestshift.time_in} - ${row.requestshift.time_out}` : '-'
                    break;
                
                  default:
                    html = 'Change type not found'
                    break;
                }
                return html;
              }, targets: [4]
            },
            {
              width: "10%",
              render: function(data, type, row) {
                html = '';
                switch (row.status) {
                  case 'WAITING':
                    html = `<span class="badge badge-warning">Waiting Approval</span>`;
                    break;
                  case 'APPROVED':
                    html = `<span class="badge badge-success">Approved</span>`;
                    break;
                  case 'REJECT':
                    html = `<span class="badge badge-danger">Rejected</span>`;
                    break;
                
                  default:
                    html = `<span class="badge badge-info">Not Define</span>`;
                    break;
                }

                return html;
              }, targets: [6]
            },
            {
              width: "10%",
              render: function(data, type, row) {
                html = '';
                var spv_id  = `{!! $attendance->employee->user->spv_id !!}`;
                var user_id = `{!! Auth::guard('admin')->user()->id !!}`;
                
                if (row.status == 'WAITING' && (spv_id == user_id) && actionmenu.indexOf('approval')) {
                  html = `<button type="button" id="approve-request" class="btn text-sm btn-sm btn-success btn-flat legitRipple" onclick="approveRequest(${row.id}, 'APPROVED')" data-toggle="tooltip" data-placement="top" title="Approved Request">
                              <b><i class="fas fa-check"></i></b>
                          </button>
                          <button type="button" id="reject-request" class="btn text-sm btn-sm bg-red btn-flat legitRipple" onclick="approveRequest(${row.id}, 'REJECT')" data-toggle="tooltip" data-placement="top" title="Reject Request">
                            <b><i class="fas fa-times"></i></b>
                          </button>
                          `;
                } else {
                  html = `<button type="button" id="detail-request" class="btn text-sm btn-sm btn-default btn-flat legitRipple" onclick="detailRequest(${row.id})" data-toggle="tooltip" data-placement="top" title="Detail Request">
                            <b><i class="fas fa-search"></i></b>
                          </button>`;
                }

                return html;
              }, targets: [7]
            },
        ],
        columns: [
            { data: "no" },
            { data: "request_reason" },
            { data: "type" },
            { data: "value_before" },
            { data: "type" },
            { data: "created_at" },
            { data: "status" },
            { data: "id" },
        ]
    });
  });

</script>
@endsection