@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')
<style type="text/css">
  .text-danger {
    color: red !important;
  }

  .badge-danger {
    background-color: red !important;
  }
</style>
@endsection

@section('button')
@if (in_array('create', $actionmenu) && !$attendanceToday && $employee_id->employees->payroll_type == 1)
<button type="button" id="add-attendance" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('attendance.create') }}')">
  <b><i class="fas fa-plus"></i></b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-attendance" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
  <b><i class="fas fa-search"></i></b> Filter
</button>
@endif
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">
      {{ $menu_name }}
    </h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-dark mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name }}</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header"></div>
          <div class="card-body table-responsive p-0">
            <table id="table-attendance" class="table table-striped datatable" width="100%">
              <thead>
                <tr>
                  <th width="2%">No</th>
                  <th width="10%">Date</th>
                  <th width="10%">Employee</th>
                  <th width="10%">Check In</th>
                  <th width="10%">Check Out</th>
                  <th width="10%">Summary</th>
                  <th width="10%">Status</th>
                  <th width="10%">#</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="form-filter">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Filter {{ $menu_name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-search" method="post" autocomplete="off">
          {{ csrf_field() }}
          <input type="hidden" name="_method" />
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label" for="attendance_date">Attendance Date</label>
                <input type="text" name="attendance_date" class="form-control" placeholder="Attendance Date">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-md-12 col-xs-12 control-label" for="employee">Type</label>
                <div class="col-sm-12 controls">
                  <select name="employee" id="employee" class="form-control select2">
                    <option value="">Select Employee</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-md-12 col-xs-12 control-label" for="status">Status</label>
                <div class="col-sm-12 controls">
                  <select name="status" id="status" class="form-control select2">
                    <option value="">Select Status</option>
                    @foreach(config('enums.status_w_rejected') as $key => $type)
                    <option value="{{ $key }}">{{ $type }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="text-right mt-4">
            <button type="submit" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple">
              <b><i class="fas fa-search"></i></b>
              Filter
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
@endsection

@section('scripts')
<script>
  var actionmenu = JSON.parse('{!! json_encode($actionmenu) !!}');

  function filter(){
    $('#form-filter').modal('show');
  }

  function edit(id){
    document.location = `{{ route('attendance.index') }}/${id}/edit`;
  }

  $(function() {
    $('.select2').select2();
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
            url: "{{route('attendance.read')}}",
            type: "GET",
            data:function(data){
                var date = $('#form-search').find('input[name=date]').val();
                var employee = $('#form-search').find('select[name=employee]').val();
                var status = $('#form-search').find('select[name=status]').val();
                data.date     = date;
                data.employee = employee;
                data.status   = status;
            }
        },
        columnDefs:[
            {
                orderable: false,targets:[0,7]
            },
            { className: "text-right", targets: [0] },
            { className: "text-center", targets: [7,3,4] },
            {
              width: "2%",
              render: function(data, type, row) {
                return row.no;
              }, targets: [0]
            },
            { width: "10%",
              render: function(data, type, row) {
                return row.employee_id ? row.employee.name : '-'
              }, targets: [2]
            },
            {
              width: "10%",
              render: function(data, type, row) {
                html  = '';
                if (row.diff_in) {
                  if (row.diff_in.diff_type == 'late') {
                    html = `<b>${row.attendance_in}</b><br><small class="text-danger">${row.diff_in.diff_format}</small>`;
                  } else {
                    html = `<b>${row.attendance_in}</b><br><small class="text-success">${row.diff_in.diff_format}</small>`;
                  }
                }
                return row.diff_in ? html : '-'
              }, targets: [3]
            },
            {
              width: "10%",
              render: function(data, type, row) {
                html  = '';
                if (row.diff_out) {
                  if (row.diff_out.diff_type == 'late') {
                    html = `<b>${row.attendance_out}</b><br><small class="text-danger">${row.diff_out.diff_format}</small>`;
                  } else {
                    html = `<b>${row.attendance_out}</b><br><small class="text-success">${row.diff_out.diff_format}</small>`;
                  }
                }
                return row.diff_out ? html : '-'
              }, targets: [4]
            },
            {
              width: "10%",
              render: function(data, type, row) {
                var workingday = '';
                switch (row.day_work) {
                  case '1':
                    workingday = `<small class="badge badge-success">Full Day</small>`;
                    break;
                  case '0.5':
                    workingday = `<small class="badge badge-warning">Half Day</small>`;
                    break;
                  case '0':
                    workingday = `<small class="badge badge-danger">Absent</small>`;
                    break;
                
                  default:
                    workingday = `<small class="badge badge-info">Not Completed</small>`;
                    break;
                }
                html  = `<b>WT: </b> ${row.working_time ? row.working_time : 0}<br><b>OT: </b> ${row.over_time ? row.over_time : 0}<br><b>Working Day: </b> ${workingday}`;
                return html;
              }, targets: [5]
            },
            { width: "10%",
              render: function ( data, type, row ) {
                var status  = '';
                var badge   = '';
                switch (row.status) {
                  case 'WAITING':
                    status  = 'Waiting Approval';
                    badge   = 'warning';
                    break;
                  case 'APPROVED':
                    status  = 'Approved';
                    badge   = 'success';
                    break;
                  case 'REJECTED':
                    status  = 'Rejected';
                    badge   = 'danger';
                    break;
                
                  default:
                    status  = 'Draft';
                    badge   = 'secondary';
                    break;
                }

                if ((row.attendance_in && row.attendance_out) || row.status == 'APPROVED') {
                  return `<small class="badge badge-${badge}">${status}</small>`;
                } else {
                  return `<small class="badge badge-info">Not Completed</small>`;
                }

              }, targets: [6]
            },
            {   
                width: "10%",
                render: function ( data, type, row ) {
                  var button = '';
                  if (actionmenu.indexOf('update') > 0) {
                    button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
                    <i class="far fa-edit"></i>Update Data
                    </a>`;
                  }
                  if (actionmenu.indexOf('delete') > 0) {
                    button += `<a class="dropdown-item delete" href="javascript:void(0);" data-id="${row.id}">
                    <i class="fa fa-trash-alt"></i> Delete Data
                    </a>`;
                  }
                return `<div class="btn-group">
                <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="dropdown-menu">
                    ${button}
                </div>
                </div>`;
                },targets: [7]
            }
        ],
        columns: [
            { data: "no" },
            { data: "attendance_date" },
            { data: "employee_id" },
            { data: "attendance_in" },
            { data: "attendance_out" },
            { data: "working_time" },
            { data: "status" },
            { data: "id" },
        ]
    });
    $('#form-search').submit(function (e) {
        e.preventDefault();
        dataTable.draw();
        $('#form-filter').modal('hide');
    });

    $(document).on('click', '.delete', function () {
        var id = $(this).data('id');
        bootbox.confirm({
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i>',
                    className: 'btn-primary btn-sm'
                },
                cancel: {
                    label: '<i class="fa fa-undo"></i>',
                    className: 'btn-default btn-sm'
                },
            },
            title: 'Delete data?',
            message: 'Are you sure want to delete this site?',
            callback: function (result) {
                if (result) {
                    var data = {
                        _token: "{{ csrf_token() }}"
                    };
                    $.ajax({
                        url: `{{route('attendancemachine.index')}}/${id}`,
                        dataType: 'json',
                        data: data,
                        type: 'DELETE',
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
                            dataTable.ajax.reload(null, false);
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
        });
    });
  })
</script>
@endsection