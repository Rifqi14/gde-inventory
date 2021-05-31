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
  <b><i class="fas fa-search"></i></b> Filter
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
                  <th>No.</th>
                  <th>Consume Date</th>
                  <th>Consume Number</th>
                  <th>Issued By</th>
                  <th>Status</th>
                  <th>#</th>
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
        <h5 class="modal-title text-bold">Filter {{ @$menu_name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" autocomplete="off" id="form-search">
          @csrf
          <div class="row">
            <div class="col-md-12">
              <div class="form-group"><label for="consume_date" class="control-label"></label><input type="text" name="consume_date" id="consume_date" class="form-control"></div>
            </div>
            <div class="col-md-12">
              <div class="form-group"><label for="consume_number" class="control-label"></label><input type="text" name="consume_number" id="consume_number" class="form-control"></div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="status" class="control-label"></label>
                <select name="status" id="status" class="form-control select2">
                  <option value="">Select Status</option>
                  @foreach (config('enums.status_w_rejected') as $key => $status)
                  <option value="{{ $key }}">{{ $status }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="text-right mt-4">
          <button type="submit" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple">
            <b><i class="fas fa-search"></i></b>
            Filter
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  var actionmenu = JSON.parse(`{!! json_encode($actionmenu) !!}`);

  const filter = () => {
    $('#form-filter').modal('show');
  }

  const edit = (id) => {
    document.location = `{{ route('consumable.index') }}/${id}/edit`;
  }

  $(function () {
    $(".select2").select2();

    dataTable = $(".datatable").DataTable({
      processing: true,
      language: {
        processing: `<div class="p-2 text-center"><i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading ...</div>`
      },
      serverSide: true,
      filter: false,
      responsive: true,
      lengthChange: false,
      order: [[ 1, "asc"]],
      ajax: {
        url: "{{ route('consumable.read') }}",
        type: "GET",
        data: function(data){
        }
      },
      columnDefs: [
        { orderable: false, targets: [0,6] },
        { className: "text-right", targets: [0] },
        { className: "text-center", targets: [6] },
        { width: "5%",
          render: function ( data, type, row ) {
            return row.no;
          }, targets: [0]
        },
        { width: "15%",
          render: function ( data, type, row ) {
            return `<a href="javascript:void(0);" onclick="edit(${row.id})" class="link-item"><b>${row.consumable_number}</b></a>`;
          }, targets: [2]
        },
        { width: "20%",
          render: function ( data, type, row ) {
            return row.issued_by ? row.issuedBy.name : '-';
          }, targets: [3]
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

            return `<span class="badge badge-${badge}">${status}</span>`;
          }, targets: [4]
        },
        { width: "10%",
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
          }, targets: [5]
        },
      ],
      columns: [
        { data: "no" },
        { data: "consumable_date" },
        { data: "consumable_number" },
        { data: "issued_by" },
        { data: "status" },
        { data: "id" },
      ]
    });

    $("#form-search").submit(function (e) {
      e.preventDefault();
      dataTable.draw();
      $("#form-filter").modal('hide');
    });

    $(document).on('click', '.delete', function () {
      var id = $(this).data('id');
      bootbox.confirm({
        buttons: {
          confirm: {
            label: `<i class="fa fa-check"></i>`,
            className: `btn-primary btn-sm`,
          },
          cancel: {
            label: `<i class="fa fa-undo"></i>`,
            className: `btn-default btn-sm`
          },
        },
        title: 'Delete data?',
        message: 'Are you sure want to delete this data?',
        callback: function ( result ) {
          if (result) {
            var data = {
              _token: "{{ csrf_token() }}"
            };
            $.ajax({
              url: `{{ route('consumable.index') }}/${id}`,
              dataType: `JSON`,
              data: data,
              type: `DELETE`,
              beforeSend: function () {
                blockMessage("#content", "Loading", "#fff");
              }
            }).done(function (response) {
              $("#content").unblock();
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
            });
          }
        }
      })
    })
  })
</script>
@endsection