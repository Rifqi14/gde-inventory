@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">Create {{ $menu_name }}</h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name }}</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
      <li class="breadcrumb-item">Create</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <form action="{{ route('attendance.store') }}" role="form" method="POST" enctype="multipart/form-data" autocomplete="off" id="form">
      @csrf
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
                    <input type="text" name="employee_name" class="form-control" placeholder="Employee ID" disabled value="{{ @$employee->name }}">
                    <input type="hidden" name="employee_id" class="form-control" value="{{ $employee->id }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="position">Position</label>
                    <input type="text" name="position" class="form-control" placeholder="Position" disabled value="{{ @$employee->position }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="shift_type">Shift Type</label>
                    <input type="text" name="shift_type" class="form-control" placeholder="Shift Type" disabled value="{{ ucwords(str_replace('_', ' ', @$employee->shift_type)) }}">
                  </div>
                </div>
                <div class="col-md-6 d-none">
                  <div class="form-group row">
                    <label class="col-md-12 col-xs-12 control-label" for="shift">Shift</label>
                    <div class="col-sm-12 controls">
                      <input type="hidden" name="working_shift_id" value="{{ @$employee->working_shift_id }}">
                      <select name="shift" id="shift" class="form-control select2" onchange="changeShift(this)" required>
                        <option value="">Select Shift</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
                    <input type="text" name="check_in" class="form-control" placeholder="Check In" disabled>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="check_out">Check Out</label>
                    <input type="text" name="check_out" class="form-control" placeholder="Check Out" disabled>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="working_time">Working Time</label>
                    <input type="text" name="working_time" class="form-control" placeholder="Working Time" disabled>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="over_time">Over Time</label>
                    <input type="text" name="over_time" class="form-control" placeholder="Over Time" disabled>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label" for="description">Note</label>
                    <textarea class="form-control summernote" name="description" id="description" rows="4" placeholder="Description..."></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <button type="button" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm checkin d-none" data-check="in" onclick="saveAttendance(this)">
                <b><i class="fas fa-save"></i></b> Check In
              </button>
              <button type="button" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm checkout d-none" data-check="out" onclick="saveAttendance(this)">
                <b><i class="fas fa-save"></i></b> Check Out
              </button>
              <a href="{{ route('attendancemachine.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
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
  const timeDiff = (time1, time2) => {
    var diff = (time1.getTime() - time2.getTime()) / 1000;
    diff /= (60 * 60);
    return Math.abs(Math.round(diff));
  }
  const changeShift = (a) => {
    if ($('input[name="shift_type"]').val() != 'Hourly') {
      var currentDate = new Date();
      var data = $(a).select2('data')[0];
      var timeIn = data.time_in.split(':');
      var timeOut = data.time_out.split(':');
      var dateIn = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), timeIn[0], timeIn[1], timeIn[2]);
      var dateOut = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), timeOut[0], timeOut[1], timeOut[2]);
  
      var timeDiffIn = timeDiff(currentDate, dateIn);
      var timeDiffOut= timeDiff(currentDate, dateOut);
  
      if (timeDiffIn < timeDiffOut) {
        $('.checkin').removeClass('d-none');
        $('.checkout').addClass('d-none');
      } else {
        $('.checkout').removeClass('d-none');
        $('.checkin').addClass('d-none');
      }
    } else {
      $('.checkin').removeClass('d-none');
      $('.checkout').addClass('d-none');
    }
  }

  const saveAttendance = (a) => {
    var post = new FormData($('#form')[0]),
        type = $(a).data('check');
    post.append('type', type);
    $.ajax({
        url:$('#form').attr('action'),
        method:'post',
        data: post,
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
    $('input[name="shift_type"]').val() == 'Hourly' ? $('#shift').parent().parent().parent().addClass('d-none') : $('#shift').parent().parent().parent().removeClass('d-none');
    summernote();
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
    @if ($employee->working_shift_id)
      $('#shift').select2('trigger', 'select', {
        data: {
          id: `{{ $employee->workingshift->id }}`,
          text: `{{ $employee->workingshift->shift_name . ' | ' . $employee->workingshift->time_in . ' - ' . $employee->workingshift->time_out }}`,
          time_in: `{{ $employee->workingshift->time_in }}`,
          time_out: `{{ $employee->workingshift->time_out }}`
        }
      });
      $('#shift').prop('disabled', true);
    @endif
  });
</script>
@endsection