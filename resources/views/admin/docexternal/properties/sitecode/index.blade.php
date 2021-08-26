<div class="tab-pane fade show active" id="sitecode" role="tabpanel" aria-labelledby="sitecode-tab">
  <div class="table-responsive">
    <table class="table table-striped" id="table-sitecode" width="100%">
      <thead>
        <tr>
          <th width="2%">No.</th>
          <th width="50%">Code</th>
          <th width="45%">Name</th>
          <th width="3%">Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('filter')
<div class="modal fade" id="form-filter-sitecode">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Filter Site Code</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-search-sitecode" method="POST" autocomplete="off">
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
        <button type="submit" form="form-search-sitecode" class="btn btn-labeled text-sm btn-default btn-flat legitRipple">
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
  const editsitecode = (id) => {
    document.location = `{{ route('sitecode.index') }}/${id}/edit`;
  }
  const destroySiteCode = (id) => {
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
            url: `{{route('sitecode.index')}}/${id}`,
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
              dataTablesitecode.ajax.reload(null, false);
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
    dataTablesitecode = $('#table-sitecode').DataTable({
      processing: true,
      language: {
        processing: `<div class="p-2 text-center"><i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...</div>`
      },
      serverSide: true,
      filter: false,
      responsive: true,
      lengthChange: false,
      order: [[1, "asc"]],
      ajax: {
        url: "{{ route('sitecode.read') }}",
        type: "GET",
        data: function(data){
          data.code   = $('#form-search-sitecode').find('input[name=code]').val();
          data.name   = $('#form-search-sitecode').find('input[name=name]').val();
        }
      },
      columnDefs: [
        { orderable: false, targets: [0, 3] },
        { className: "text-center", targets: [0, 3] },
        {   
          width: "10%",
          render: function ( data, type, row ) {
            var button = '';
            if (actionmenu.indexOf('update') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="editsitecode(${row.id})">
              <i class="far fa-edit"></i>Update Data
              </a>`;
            }
            if (actionmenu.indexOf('delete') > 0) {
              button += `<a class="dropdown-item delete" href="javascript:void(0);" onclick="destroySiteCode(${row.id})">
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
          },targets: [3]
        }
      ],
      columns: [
          { data: "no" },
          { data: "code" },
          { data: "name" },
          { data: "id" },
      ]
    });

    $('#form-search-sitecode').submit(function(e) {
      e.preventDefault();
      dataTablesitecode.draw();
      $('#form-filter-sitecode').modal('hide');
    });
  });
</script>
@endsection