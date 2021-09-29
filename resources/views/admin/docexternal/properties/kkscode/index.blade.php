<div class="tab-pane fade show" id="kkscode" role="tabpanel" aria-labelledby="kkscode-tab">
  <div class="table-responsive">
    <table class="table table-striped" id="table-kkscode" style="width: 100%">
      <thead>
        <tr>
          <th width="2%">No.</th>
          <th width="25%">Code</th>
          <th width="45%">Name</th>
          <th width="25%">KKS Category</th>
          <th width="3%">Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('filter')
<div class="modal fade" id="form-filter-kkscode">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Filter {{ $menu_name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-search-kkscode" method="POST" autocomplete="off">
          @csrf
          <input type="hidden" name="_method">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="code" class="control-label">Code</label>
                <input type="text" name="code" id="code" class="form-control" placeholder="Code">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="name" class="control-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <button type="submit" form="form-search-kkscode" class="btn btn-labeled text-sm btn-default btn-flat legitRipple">
          <b><i class="fas fa-search"></i></b>
          Filter
        </button>
      </div>
    </div>
  </div>
</div>
@endpush

@section('scripts')
@parent
<script>
  const editkkscode = (id) => {
    document.location = `{{ route('kkscode.index') }}/${id}/edit`;
  }
  const destroykkscode = (id) => {
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
      callback: function (result) {
        if (result) {
          var data = {
            _token: "{{ csrf_token() }}"
          };
          $.ajax({
            url: `{{route('kkscode.index')}}/${id}`,
            dataType: 'json',
            data: data,
            type: 'DELETE',
            beforeSend: function () {
              blockMessage('body', 'Loading', '#fff');
            }
          }).done(function (response) {
            $('body').unblock();
            if (response.status) {
              toastr.success(response.message);
              dataTablekkscode.ajax.reload(null, false);
            }else {
              toastr.warning(response.message);
            }
          }).fail(function (response) {
            var response = response.responseJSON;
            $('body').unblock();
            toastr.warning(response.message);
          });
        }
      }
    });
  }

  $(function() {
    dataTablekkscode = $('#table-kkscode').DataTable({
      processing: true,
      language: {
        processing: `<div class="p-2 text-center"><i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...</div>`
      },
      serverSide: true,
      filter: false,
      responsive: true,
      lengthChange: false,
      pageLength: 50,
      order: [[1, "asc"]],
      ajax: {
        url: "{{ route('kkscode.read') }}",
        type: "GET",
        data: function(data){
          data.code   = $('#form-search-kkscode').find('input[name=code]').val();
          data.name   = $('#form-search-kkscode').find('input[name=name]').val();
        }
      },
      columnDefs: [
        { orderable: false, targets: [0, 4] },
        { className: "text-center", targets: [0, 4] },
        {
          width: "25%",
          render: function ( data, type, row ) {
          return `${row.category ? row.category.name : '-'}`;
          },targets: [3]
        },
        {
          width: "10%",
          render: function ( data, type, row ) {
            var button = '';
            if (actionmenu.indexOf('update') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="editkkscode(${row.id})">
              <i class="far fa-edit"></i>Update Data
              </a>`;
            }
            if (actionmenu.indexOf('delete') > 0) {
              button += `<a class="dropdown-item delete" href="javascript:void(0);" onclick="destroykkscode(${row.id})">
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
          },targets: [4]
        }
      ],
      columns: [
          { data: "no" },
          { data: "code" },
          { data: "name" },
          { data: "document_external_kks_category_id" },
          { data: "id" },
      ]
    });

    $('#form-search-kkscode').submit(function(e) {
      e.preventDefault();
      dataTablekkscode.draw();
      $('#form-filter-kkscode').modal('hide');
    });
  });
</script>
@endsection
