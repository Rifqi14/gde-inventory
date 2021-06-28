@extends('admin.layouts.app')
@section('title', @$menu_name)

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-consumable" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('consumable.create') }}')">
  <b><i class="fas fa-plus"></i></b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-consumable" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
  <b><i class="fas fa-search"></i></b> Search
</button>
@endif
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">{{ @$menu_name }}</h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ @$menu_parent }}</li>
      <li class="breadcrumb-item">{{ @$menu_name }}</li>
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
            <table id="table-consumable" class="table table-striped datatable" width="100%">
              <thead>
                <tr>
                  <th width="10" class="text-center">No.</th>
                  <th width="25" class="text-center">Consume Date</th>
                  <th width="100">Consume Number</th>
                  <th width="50">Issued By</th>
                  <th width="20" class="text-center">Status</th>
                  <th width="10" class="text-center">Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div id="add-filter" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="filter-modal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ @$menu_name }} Filter</h5>
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
                  <label class="control-label" for="consume-number">Consume Number</label>
                  <input class="form-control" type="text" name="consume_number" id="consume-number" placeholder="Enter consume number">
                </div>
                <div class="form-group">
                  <label for="status" class="control-label">Status</label>
                  <select class="form-control select2 status" id="status" name="status" data-placeholder="Choose Status">
                    <option value=""></option>
                    <option value="draft">Draft</option>
                    <option value="waiting">Waiting</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label" for="borrowig-date">Consume Date</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="datepicker" class="form-control datepicker text-right" name="dates" id="dates">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label" for="issued-by">Issued By</label>
                  <select name="issuedby" id="issued-by" class="form-control select2" data-placeholder="Choose employee"></select>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-labeled  btn-danger btn-sm btn-sm btn-flat legitRipple" data-dismiss="modal" onclick="resetTable()"><b><i class="fas fa-times"></i></b> Cancel</button>
        <button type="submit" form="form-search" class="btn btn-labeled  btn-default  btn-sm btn-flat legitRipple"><b><i class="fas fa-search"></i></b> Search</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  var actionmenu = JSON.parse(`{!! json_encode($actionmenu) !!}`);
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

  $(function() {
    $(".select2").select2({
      allowClear: true
    });

    $("#issued-by").select2({
      ajax: {
        url: "{{route('employee.select')}}",
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

    consumableTable = $("#table-consumable").DataTable({
      processing: true,
      language: {
        processing: `<div class="p-2 text-center"><i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading ...</div>`
      },
      serverSide: true,
      filter: false,
      responsive: true,
      lengthChange: false,
      order: [
        [1, "asc"]
      ],
      ajax: {
        url: "{{ route('consumable.read') }}",
        type: "GET",
        data: function(data) {
          var dates    = $('#form-search').find('#dates').data('daterangepicker');

          data.number     = $('#form-search').find('#consume-number').val();
          data.status     = $('#status').find('option:selected').val();
          data.startdate  = dates.startDate.format('YYYY-MM-DD');
          data.finishdate = dates.endDate.format('YYYY-MM-DD');
          data.employee   = $('#form-search').find('#issued-by').select2('val');
        }
      },
      columnDefs: [{
          orderable: false,
          targets: [0, 5]
        },
        {
          className: "text-right",
          targets: []
        },
        {
          className: "text-center",
          targets: [0, 1, 4, 5]
        },
        {
          render: function(data, type, row) {
            return `<b>${row.consumable_number}</b>`;
          },
          targets: [2]
        },
        {
          render: function(data, type, row) {
            var name = row.issued_by;
            name = name ? name : '-';

            return `${name}`;
          },
          targets: [3]
        },
        {
          render: function(data, type, row) {
            var status = '';
            var badge = '';
            switch (row.status) {
              case 'waiting':
                status = 'Waiting Approval';
                badge = 'badge-warning';
                break;
              case 'approved':
                status = 'Approved';
                badge = 'badge-info';
                break;
              case 'rejected':
                status = 'Rejected';
                badge = 'bg-red';
                break;

              default:
                status = 'Draft';
                badge = 'badge-secondary';
                break;
            }

            return `<span class="badge ${badge} text-sm" style="text-transform: capitalize;">${status}</span>`;
          },
          targets: [4]
        },
        {
          render: function(data, type, row) {
            var button = '';

            if (actionmenu.indexOf('read') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="detail(${row.id})">
              <i class="far fa-eye"></i>View Data
              </a>`;
            }
            if (actionmenu.indexOf('update') > 0 && row.status != 'approved') {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
              <i class="far fa-edit"></i>Update Data
              </a>`;
            }
            if (actionmenu.indexOf('delete') > 0 && row.status != 'approved') {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="destroy(${row.id})">
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
        },
      ],
      columns: [{
          data: "no"
        },
        {
          data: "date_consumable"
        },
        {
          data: "consumable_number"
        },
        {
          data: "issued_by"
        },
        {
          data: "status"
        }
      ]
    });

    $("#form-search").submit(function(e) {
      e.preventDefault();
      consumableTable.draw();
      $("#add-filter").modal('hide');
    });

  });

  const filter = () => {
    $('#add-filter').modal('show');
  }

  const edit = (id) => {
    document.location = `{{ route('consumable.index') }}/${id}/edit`;
  }

  const detail = (id) => {
    document.location = `{{ route('consumable.index') }}/${id}`;
  }

  const destroy = (id) => {
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
      message: 'Are you sure want to delete this data?',
      callback: function(result) {
        if (result) {
          var data = {
            _token: "{{ csrf_token() }}"
          };
          $.ajax({
            url: `{{route('consumable.index')}}/${id}`,
            dataType: 'json',
            data: data,
            type: 'DELETE',
            beforeSend: function() {
              blockMessage('#content', 'Loading', '#fff');
            }
          }).done(function(response) {
            $('#content').unblock();
            if (response.status) {
              toastr.success(response.message);
              consumableTable.ajax.reload(null, false);
            } else {
              toastr.warning(response.message);
            }
          }).fail(function(response) {
            var response = response.responseJSON;
            $('#content').unblock();
            toastr.warning(response.message);
          })
        }
      }
    });
  }

  const resetTable = () => {
    var dates = $('#form-search').find('#dates').data('daterangepicker');
    $('#form-search').find('#consume-number').val('');        
    $('#status').val(null).trigger('change');    
    $('#form-search').find('#issued-by').val(null).trigger('change');
    dates.setStartDate(moment().startOf('month'));
    dates.setEndDate(moment().endOf('month'));

    $('#form-search').trigger('submit');
  }
</script>
@endsection