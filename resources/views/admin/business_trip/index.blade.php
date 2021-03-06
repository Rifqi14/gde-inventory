@extends('admin.layouts.app')

@section('title')
{{  $menu_name  }}
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
      <li class="breadcrumb-item active">{{ $menu_name }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title mt-2"> List of Business Trips Request</h3>
            <div class="text-right">
              @if(in_array('read',$actionmenu))
              <button type="button" id="filter-general-business-trip" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple float-right ml-1" onclick="filter('general')">
                <b><i class="fas fa-search"></i></b> Search
              </button>
              @endif
              @if(in_array('create',$actionmenu))
              <button type="button" id="add-business-trip" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple float-right ml-1" onclick="windowLocation('{{route('businesstrip.create')}}')">
                <b><i class="fas fa-plus"></i></b> Create
              </button>
              @endif
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-striped" id="table-bt-general">
              <thead>
                <tr>
                  <th width="5%">No.</th>
                  <th width="20%">Business Trip Number</th>
                  <th width="20%" class="text-center">Schedule</th>
                  <th width="20%" class="text-right">Total Cost</th>
                  <th width="15%" class="text-center">Status</th>
                  <th width="10%" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>

        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title"> List of Business Trips Declaration</h3>
            <div class="text-right">
              @if(in_array('read',$actionmenu))
              <button type="button" id="filter-general-business-trip" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple float-right ml-1" onclick="filter('approved')">
                <b><i class="fas fa-search"></i></b> Search
              </button>
              @endif
              @if(in_array('create',$actionmenu))
              <button type="button" id="add-business-trip" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple float-right ml-1" onclick="windowLocation('{{route('businesstrip.createdeclare')}}')">
                <b><i class="fas fa-plus"></i></b> Create
              </button>
              @endif
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-striped" id="table-bt-approved">
              <thead>
                <tr>
                  <th width="5%">No.</th>
                  <th width="20%">Business Trip Number</th>
                  <th width="20%" class="text-center">Schedule</th>
                  <th width="20%" class="text-right">Total Cost</th>
                  <th width="15%" class="text-center">Status</th>
                  <th width="10%" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</section>
<!-- /.content -->

<!-- Filter -->
<div id="add-filter" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="filter-modal" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Business Trip Filter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-search" method="POST">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label" for="business-trip-number">Business Trip Number</label>
                  <input class="form-control business-trip-number" type="text" name="business_trip_number" id="business-trip-number" placeholder="Business Trip Number">
                </div>
                <div class="form-group status-general" style="display: none;">
                  <label class="control-label" for="general-status">Status</label>
                  <select name="general_status" class="form-control select2 general-status" id="general-status" data-placeholder="Choose Status">
                    <option value=""></option>
                    <option value="draft">Draft</option>
                    <option value="waiting">Waiting</option>
                    <option value="approved">Approved</option>
                  </select>
                </div>
                <div class="form-group status-approved d-none" style="display: none;">
                  <label class="control-label" for="approved-status">Status</label>
                  <select name="approved_status" class="form-control select2 approved-status" id="apporved-status" style="display: none;" disabled>
                    <option value="approved" selected>Approved</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label" for="schedule">Scedule</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="datepicker" class="form-control datepicker text-right schedule" name="schedule">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label" for="total-cost">Total Cost</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Rp.</span>
                    </div>
                    <input type="text" class="form-control input-price text-right total-cost" id="total-cost" name="total_cost" placeholder="Enter total cost" value="0" maxlength="14">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-labeled btn-danger btn-sm btn-sm btn-flat legitRipple" data-dismiss="modal" onclick="resetTable()"><b><i class="fas fa-times"></i></b> Cancel</button>
        <button type="submit" form="form-search" class="btn btn-labeled btn-default btn-sm btn-flat legitRipple"><b><i class="fas fa-search"></i></b> Search</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
  var actionmenu = JSON.parse('{!! json_encode($actionmenu) !!}');
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
  };
  var tableFilter = '';

  $(function() {
    $('.select2').select2({
      allowClear: true
    });

    $(".input-price").priceFormat({
      prefix: '',
      centsSeparator: ',',
      thousandsSeparator: '.',
      centsLimit: 0,
      clearOnEmpty: false
    });

    $('.datepicker').daterangepicker({
      timePicker: false,
      timePickerIncrement: 30,
      drops: 'auto',
      opens: 'center',
      locale: {
        format: 'DD/MM/YYYY'
      },
      startDate: moment().startOf('month'),
      endDate: moment().endOf('month')
    });

    tableGeneral = $('#table-bt-general').DataTable({
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
      pageLength: 50,
      order: [
        [1, "asc"]
      ],
      ajax: {
        url: "{{route('businesstrip.read')}}",
        type: "GET",
        data: function(data) {
          var businessTripNumber = $('#form-search').find('.business-trip-number').val(),
              schedule           = $('#form-search').find('.schedule').data('daterangepicker'),
              startDate          = schedule.startDate.format('YYYY-MM-DD'),
              endDate            = schedule.endDate.format('YYYY-MM-DD'),
              status             = $('#form-search').find('.general-status').select2('val'),
              totalCost          = $('#form-search').find('.total-cost').val();

          data.businesstripnumber = businessTripNumber;
          data.startdate          = startDate;
          data.enddate            = endDate;
          data.status             = status;
          data.total_cost         = totalCost;
        }
      },
      columnDefs: [{
          orderable: false,
          targets: [0, 5]
        },
        {
          className: "text-center",
          targets: [0, 2, 4, 5]
        },
        {
          className: "text-right",
          targets: [3]
        },
        {
          render : function(data, type, row){
              return `<a href="javascript:void(0);" onclick="edit(${row.id})">
                        <b>${row.business_trip_number}</b>
                      </a>`;
          }, targets : [1]
        },
        {
          render: function(data, type, row) {
            total = ``
            $.each(row.total, function(index, value) {
              if (value.price > 0) {
                total += `${value.symbol} ${accounting.formatMoney(value.price, " ", 0, ".", "")}<br>`;
              }
            });
            return total;
          },
          targets: [3]
        },
        {
          render: function(data, type, row) {
            var badge = '',
              status = row.status;

            if (status == 'draft') {
              badge = '<span class="badge bg-gray text-sm">Draft</span>';
            } else if (status == 'waiting') {
              badge = '<span class="badge bg-warning text-sm">Waiting</span>';
            } else {
              badge = '<span class="badge badge-info text-sm">Approved</span>';
            }

            return badge;
          },
          targets: [4]
        },
        {
          render: function(data, type, row) {
            var button = '';
            // update
            if (actionmenu.indexOf('update') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
                                        <i class="far fa-edit"></i>Update Data
                                    </a>`;
            }
            // delete
            if (actionmenu.indexOf('delete') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="destroy(${row.id},'table-bt-general')">
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
          },
          targets: [5]
        }
      ],
      columns: [{
          data: "no"
        },
        {
          data: "business_trip_number"
        },
        {
          data: "schedule"
        },
        {
          data: "id"
        }, {
          data: "status"
        }
      ]
    });

    tableApproved = $('#table-bt-approved').DataTable({
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
      pageLength: 50,
      order: [
        [1, "asc"]
      ],
      ajax: {
        url: "{{route('declaration.read')}}",
        type: "GET",
        data: function(data) {
          var businessTripNumber = $('#form-search').find('.business-trip-number').val(),
            schedule             = $('#form-search').find('.schedule').data('daterangepicker'),
            startDate            = schedule.startDate.format('YYYY-MM-DD'),
            endDate              = schedule.endDate.format('YYYY-MM-DD'),
            status               = $('#form-search').find('.approved-status').select2('val'),
            totalCost            = $('#form-search').find('.total-cost').val();

          data.businesstripnumber = businessTripNumber;
          data.startdate          = startDate;
          data.enddate            = endDate;
          data.status             = status;
          data.total_cost         = totalCost;
        }
      },
      columnDefs: [{
          orderable: false,
          targets: [0, 5]
        },
        {
          className: "text-center",
          targets: [0, 2, 4, 5]
        },
        {
          className: "text-right",
          targets: [3]
        },
        {
          render : function(data, type, row){
              return `<a href="javascript:void(0);" onclick="edit(${row.id})">
                        <b>${row.declaration_number}</b>
                      </a>`;
          }, targets : [1]
        },
        {
          render: function(data, type, row) {
            total = ``
            $.each(row.total, function(index, value) {
              if (value.price > 0) {
                total += `${value.symbol} ${accounting.formatMoney(value.price, " ", 0, ".", "")}<br>`;
              }
            });
            return total;
          },
          targets: [3]
        },
        {
          render: function(data, type, row) {
            var badge = '',
              status = row.status;

            badge = '<span class="badge badge-success text-sm">Declare</span>';

            return badge;
          },
          targets: [4]
        },
        {
          render: function(data, type, row) {
          var button = `<a class="dropdown-item" href="javascript:void(0);" onclick="detail(${row.business_trip_id},'table-bt-approved')">
                          <i class="fa fa-eye"></i> View Data
                        </a>`;
            if(actionmenu.indexOf('print') > 0){
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="">
                          <i class="fa fa-print"></i> Print Data
                        </a>`;
            }
            if (actionmenu.indexOf('delete') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="deleteDeclare(${row.id})">
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
          },
          targets: [5]
        }
      ],
      columns: [{
          data: "no"
        },
        {
          data: "declaration_number"
        },
        {
          data: "schedule"
        },
        {
          data: "rate"
        }, {
          data: "status"
        }
      ]
    });

    $('#form-search').submit(function(e) {
      e.preventDefault();
      if (tableFilter == 'general') {
        tableGeneral.draw();
      } else {
        tableApproved.draw();
      }
      $('#add-filter').modal('hide');
    });
  });

  const deleteDeclare = (id) => {
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
                    url: `{{route('declaration.index')}}/${id}`,
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
                        tableApproved.ajax.reload(null, false);
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
  }

  function edit(id) {
    if (!id) {
      toastr.warning('Data not found.');
      console.log({
        errorMessage: 'Data not found because id is empty.'
      });
      return false;
    }

    window.location.href = `{{url('admin/businesstrip/${id}/edit')}}`;
  }

  function detail(id) {
    document.location = `{{route('businesstrip.index')}}/${id}`;
  }

  function destroy(id, table) {
    if (!id) {
      toastr.warning('Data not found.');
      console.log({
        errorMessage: 'Data not found because id is empty.'
      });
      return false;
    }
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
                    url: `{{route('businesstrip.index')}}/${id}`,
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
                        tableGeneral.ajax.reload(null, false);
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
  }

  function filter(param) {
    var schedule  = $('#form-search').find('.datepicker').data('daterangepicker')

    tableFilter = param;
    schedule.setStartDate(moment().startOf('month'));
    schedule.setEndDate(moment().endOf('month'));
    if (param == 'general') {
      $('.status-approved').hide();
      $('.status-general').show();
      $('.general-status').val(null).trigger('change');
    } else {
      $('.status-general').hide();
      $('.status-approved').show();
    }

    $('#add-filter').modal('show');
  }

  function resetTable() {
    var schedule  = $('#form-search').find('.datepicker').data('daterangepicker');

    schedule.setStartDate(moment().startOf('month'));
    schedule.setEndDate(moment().endOf('month'));
    $('#form-search').find('.business-trip-number').val('')
    $('#form-search').find('.general-status').select2('val');
    $('#form-search').find('.total-cost').val('');
    $('.general-status').val(null).trigger('change');

    if (tableFilter == 'general') {
      $('.general-status').val(null).trigger('change');
      tableGeneral.draw();
    } else {
      tableApproved.draw();
    }
  }
</script>
@endsection
