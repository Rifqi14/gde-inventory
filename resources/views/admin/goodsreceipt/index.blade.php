@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-goods_receipt" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('goodsreceipt.create') }}')">
  <b>
    <i class="fas fa-plus"></i>
  </b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-goods_receipt" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
  <b>
    <i class="fas fa-search"></i>
  </b> Search
</button>
@endif
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">{{ $menu_name }}</h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name }}</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section id="content" class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header"></div>
          <div class="card-body table-responsive p-0">
            <table id="table-goods-receipt" class="table table-striped datatable" width="100%">
              <thead>
                <tr>
                  <th width="5" class="text-center">No.</th>
                  <th width="30" class="text-center">Date</th>
                  <th width="200">Receipt Number</th>
                  <th width="20" class="text-right">Product</th>
                  <th width="30" class="text-center">Status</th>
                  <th width="10" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody></tbody>
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
        <h5 class="modal-title">Filter {{ $menu_name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-search" method="post" autocomplete="off">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label" for="date">Date</label>
                <div class="input-group">
                  <input type="text" id="date" class="form-control datepicker text-right" placeholder="Enter date">
                  <div class="input-group-append">
                      <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label" for="receipt_number">Receipt Number</label>
                <input type="text" id="receipt-number" class="form-control" placeholder="Enter receipt number">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="products" class="control-label">Products</label>
                <input type="number" class="form-control text-right" id="products" placeholder="Enter product quantity">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label" for="status">Status</label>
                <select id="status" class="form-control select2" data-placeholder="Select status">
                  <option value=""></option>
                  @foreach(config('enums.status_w_rejected') as $key => $type)
                    @if($type == 'Rejected' || $type == 'Approved')
                    <option value="{{ $key }}">{{ $type }}</option>
                    @endif
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="text-right mt-4">
          <button type="button" class="btn btn-labeled  btn-danger btn-sm btn-sm btn-flat legitRipple" data-dismiss="modal" onclick="resetTable()"><b><i class="fas fa-times"></i></b> Cancel</button>
                <button type="submit" form="form-search" class="btn btn-labeled  btn-default  btn-sm btn-flat legitRipple"><b><i class="fas fa-search"></i></b> Search</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  var actionmenu = JSON.parse('{!! json_encode($actionmenu) !!}');

  $(function() {
    $('.select2').select2({
      allowClear: true
    });

    $('.datepicker').daterangepicker({
      timePicker: false,
      timePickerIncrement: 30,
      drops: 'auto',
      opens: 'center',
      locale: {
        format: 'DD/MM/YYYY'
      },
      startDate: new moment().startOf('month'),
      endDate: new moment().endOf('month')
    });

    dataTable = $('#table-goods-receipt').DataTable({
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
        [2, "desc"]
      ],
      ajax: {
        url: "{{route('goodsreceipt.read')}}",
        type: "GET",
        data: function(data) {
          var search = $('#form-search'),
              dates  = search.find('#date').data('daterangepicker');

          data.startdate = dates.startDate.format('YYYY-MM-DD');
          data.enddate   = dates.endDate.format('YYYY-MM-DD');
          data.number    = search.find('#receipt-number').val();
          data.products  = search.find('#products').val();
          data.status    = $('#status').find('option:selected').val();
        }
      },
      columnDefs: [{
          orderable: false,
          targets: [0, 5]
        },
        {
          className: "text-center",
          targets: [0, 1, 4, 5]
        },
        {
          className: "text-right",
          targets: [3]
        },
        {
          render: function(data, type, row) {
            return `<b>${row.good_receipt_no}</b>`;
          },
          targets: [2]
        },
        {
          render : function(data, type, row){
            var products = row.products?row.products:0;
            return products;
          }, targets : [3]
        },
        {
          render: function(data, type, row) {
            var status = '';
            var badge = '';
            switch (row.status) {
              case 'approved':
                status = 'Approved';
                badge = 'badge-info';
                break;
              case 'rejected':
                status = 'Rejected';
                badge = 'bg-red';
                break;

              default:
                status = '';
                badge = '';
                break;
            }

            return `<span class="badge ${badge} text-sm" style="text-transform: capitalize;">${status}</span>`;
          },
          targets: [4]
        },
        {
          render: function(data, type, row) {
            var button = '';
            button += `<a class="dropdown-item" href="javascript:void(0);" onclick="detail(${row.id})">
                                        <i class="far fa-eye"></i>View Data
                                    </a>`;
            // // update
            // if (actionmenu.indexOf('update') >= 0 && row.status != 'approved') {
            //   button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
            //                             <i class="far fa-edit"></i>Update Data
            //                         </a>`;
            // }
            // // delete
            // if (actionmenu.indexOf('delete') >= 0 && row.status != 'approved') {
            //   button += `<a class="dropdown-item" href="javascript:void(0);" onclick="destroy(${row.id})">
            //                             <i class="fa fa-trash-alt"></i> Delete Data
            //                         </a>`;
            // }

            if (actionmenu.indexOf('export') >= 0 && row.status == 'approved') {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="ekspor(${row.id})">
                            <i class="fas fa-file-export"></i>Export Data
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
          data: "date_receipt"
        },
        {
          data: "good_receipt_no"
        },
        {
          data: "products"
        },
        {
          data: "status"
        }
      ]
    });

    $('#form-search').submit(function(e) {
      e.preventDefault();
      dataTable.draw();
      $('#form-filter').modal('hide');
    });
  });

  const edit = (id) => {
    document.location = `{{route('goodsreceipt.index')}}/${id}/edit`;
  }

  const detail = (id) => {
    document.location = `{{route('goodsreceipt.index')}}/${id}`;
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
            url: `{{route('goodsreceipt.index')}}/${id}`,
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
              dataTable.ajax.reload(null, false);
              archivedTable.draw();
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

  const filter = () => {
    $('#form-filter').modal('show');
  }

  const ekspor = (id) => {
    $.ajax({
        type: "GET",
        url: `{{route('goodsreceipt.export')}}`,
        data: {
            _token: "{{ csrf_token() }}",
            id : id
        },
        dataType: "JSON",
        success: function (response) {
            if (response.status) {
                let download = document.createElement("a");
                download.href = response.file;
                document.body.appendChild(download);
                download.download = response.document;
                download.click();
                download.remove();
            }else{
                toastr.warning(response.message);
            }
        }
    });
  }
</script>
@endsection
