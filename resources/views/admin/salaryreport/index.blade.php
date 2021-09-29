@extends('admin.layouts.app')
@section('title', $menu_name)

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-salaryreport" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="generate()">
  <b><i class="fas fa-plus"></i></b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-salaryreport" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
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
            <table id="table-salary" class="table table-striped datatable" width="100%">
              <thead>
                <tr>
                  <th width="2%">No</th>
                  <th width="10%">Employee</th>
                  <th width="10%">Group</th>
                  <th width="10%">Period</th>
                  <th width="10%">Total</th>
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
              <div class="form-group row">
                <label class="col-md-12 col-xs-12 control-label" for="employee">Employee</label>
                <div class="col-sm-12 controls">
                  <select name="employee_id" id="employee" class="form-control select2 employee">
                    <option value="">Select Employee</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-md-12 col-xs-12 control-label" for="role_id">Role</label>
                <div class="col-sm-12 controls">
                  <select name="role_id" id="role_id" class="form-control select2 role">
                    <option value="">Select Role</option>
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
<div class="modal fade" id="form-generate">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Generate {{ $menu_name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form" method="post" autocomplete="off" action="{{ route('salaryreport.store') }}">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="type" class="control-label">Generate Type</label>
                <select name="type" id="type" class="form-control select2" onchange="generateType(this)" aria-placeholder="Choose Type">
                  <option value="all">All</option>
                  <option value="employee">Employee</option>
                  <option value="role">Group</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="periode" class="control-label">Periode</label>
                <input type="text" name="periode" class="form-control datepicker" id="periode">
              </div>
            </div>
            <div class="col-md-12 d-none employee-select">
              <div class="form-group row">
                <label class="col-md-12 col-xs-12 control-label" for="employee_generate">Employee</label>
                <div class="col-sm-12 controls">
                  <select name="employee_generate" id="employee_generate" class="form-control select2 employee">
                    <option value="">Select Employee</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-12 d-none role-select">
              <div class="form-group row">
                <label class="col-md-12 col-xs-12 control-label" for="role_id_generate">Group</label>
                <div class="col-sm-12 controls">
                  <select name="role_id_generate" id="role_id_generate" class="form-control select2 role">
                    <option value="">Select Group</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="text-right mt-4">
            <button type="submit" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple">
              <b><i class="fas fa-plus"></i></b>
              Submit
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
  var actionmenu = @json($actionmenu);

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

  const edit = (id) => {
    document.location = `{{ route('salaryreport.index') }}/${id}/edit`;
  }

  const generate = () => {
    $('#form-generate').modal('show');
  }

  const filter = () => {
    $('#form-filter').modal('show');
  }

  const destroy = (id) => {
    bootbox.confirm({
      buttons: {
        confirm: {
          label: '<i class="fa fa-check"></i>',
          className: 'btn-primary btn-sm',
        },
        cancel: {
          label: '<i class="fa fa-undo"></i>',
          className: 'btn-default btn-sm'
        },
      },
      title: 'Delete data?',
      message: 'Are you sure want to delete this data?',
      callback: function (result) {
        if (result) {
          var data = {
            _token: `{{ csrf_token() }}`
          };
          $.ajax({
            url: `{{ route('salaryreport.index') }}/${id}`,
            dataType: 'json',
            data: data,
            type: 'DELETE',
            beforeSend: function () {
              blockMessage('#content', 'Loading...', '#fff');
            }
          }).done(function (response) {
            $('#content').unblock();
            if (response.status) {
              toastr.success(response.message);
              dataTable.ajax.reload(null, false);
            } else {
              toastr.warning(response.message);
            }
          }).fail(function (response) {
            var response = response.responseJSON;
            $('#content').unblock();
            toastr.error(response.message);
          });
        }
      }
    });
  }

  const generateType = (e) => {
    var value = e.value;

    switch (value) {
      case 'employee':
        $('.employee-select').removeClass('d-none');
        $('.role-select').addClass('d-none');
        break;
      case 'role':
        $('.employee-select').addClass('d-none');
        $('.role-select').removeClass('d-none');
        break;

      default:
        $('.employee-select').addClass('d-none');
        $('.role-select').addClass('d-none');
        break;
    }
  }

  $(function() {
    $('.select2').select2();

    $(".employee").select2({
        ajax: {
            url: "{{ route('employee.select') }}",
            type: "GET",
            dataType: "JSON",
            data: function(params) {
                return {
                    name: params.term,
                    page: params.page,
                    limit: 30,
                    salary: true,
                };
            },
            processResults: function(data, params) {
                var more = (params.page * 30) < data.total;
                var option = [];
                $.each(data.rows, function(index, item) {
                    option.push({
                        id: item.id,
                        text: item.name,
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
    $(".role").select2({
        ajax: {
            url: "{{ route('role.select') }}",
            type: "GET",
            dataType: "JSON",
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
                        text: item.name,
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
    $('.datepicker').daterangepicker({
      singleDatePicker: true,
      timePicker: false,
      timePickerIncrement: 1,
      timePicker24Hour: false,
      timePickerSeconds: false,
      autoUpdateInput: false,
      drops: 'auto',
      opens: 'center',
      locale: {
        format: 'YYYY-MM'
      }
    }).on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM'));
    }).on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
    dataTable = $('.datatable').DataTable({
      processing: true,
      language: {
        processing: `<div class="p-2 text-center">
                        <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading ...
                     </div>`
      },
      serverSide: true,
      filter: false,
      responsive: true,
      lengthChange: false,
      order: [[ 1, 'asc' ]],
      pageLength: 50,
      ajax: {
        url: "{{ route('salaryreport.read') }}",
        type: "GET",
        data: function(data) {
          var employee_id   = $('#form-search').find('select[name=employee_id]').val();
          var role_id       = $('#form-search').find('select[name=role_id]').val();
          var status        = $('#form-search').find('select[name=status]').val();
          data.employee_id  = employee_id;
          data.role_id      = role_id;
          data.status       = status;
        }
      },
      columnDefs: [
        { orderable: false, targets: [0, 5] },
        { className: "text-right", targets: [3, 4] },
        { classname: "text-center", targets: [0, 5] },
        { render: function ( data, type, row ) {
          return row.employee.name
        }, targets: [1] },
        { render: function ( data, type, row ) {
          return row.employee.user.roles ? row.employee.user.roles[0].name : '-'
        }, targets: [2] },
        { render: function ( data, type, row ) {
          var button = '';
          if (actionmenu.indexOf('update') > 0) {
            button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
            <i class="far fa-edit"></i>Update Data
            </a>`;
          }
          if (actionmenu.indexOf('delete') > 0) {
            button += `<a class="dropdown-item delete" href="javascript:void(0);" onclick="destroy(${row.id})">
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
        }, targets: [5] },
      ],
      columns: [
        { data: "no", className: "text-center" },
        { data: "employee_id" },
        { data: "employee_id" },
        { data: "period" },
        { data: "total" },
        { data: "id", className: "text-center" },
      ],
    });
    $('#form-search').submit(function(e){
      e.preventDefault();
      dataTable.draw();
      $('#form-filter').modal('hide');
    });
    $("#form").validate({
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
                  $('#form-generate').modal('hide');
                  dataTable.ajax.reload(null, false);
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
            })
        }
    });
  });
</script>
@endsection
