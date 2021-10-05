<div class="tab-pane fade show" id="contractorname" role="tabpanel" aria-labelledby="contractorname-tab">
  <div class="table-responsive">
    <table class="table table-striped" id="table-contractorname" style="width: 100%">
      <thead>
        <tr>
          <th width="2%">No.</th>
          <th width="50%">Contractor User Group</th>
          <th width="45%">Contractor Name</th>
          <th width="3%">Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('filter')
<div class="modal fade" id="form-filter-contractorname">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Filter {{ $menu_name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-search-contractorname" method="POST" autocomplete="off">
          @csrf
          <input type="hidden" name="_method">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="role_id" class="control-label">Contractor User Group</label>
                <select name="role_id" id="role_id" class="form-control select2"></select>
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
        <button type="submit" form="form-search-contractorname" class="btn btn-labeled text-sm btn-default btn-flat legitRipple">
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
  const editcontractorname = (id) => {
    document.location = `{{ route('contractorname.index') }}/${id}/edit`;
  }
  const destroycontractorname = (id) => {
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
            url: `{{route('contractorname.index')}}/${id}`,
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
              dataTablecontractorname.ajax.reload(null, false);
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
    dataTablecontractorname = $('#table-contractorname').DataTable({
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
        url: "{{ route('contractorname.read') }}",
        type: "GET",
        data: function(data){
          data.role_id= $('#form-search-contractorname').find('select[name=role_id]').val();
          data.name   = $('#form-search-contractorname').find('input[name=name]').val();
        }
      },
      columnDefs: [
        { orderable: false, targets: [0, 3] },
        { className: "text-center", targets: [0, 3] },
        {
          width: "25%",
          render: function ( data, type, row ) {
          return `${row.role ? row.role.name : '-'}`;
          },targets: [1]
        },
        {
          width: "10%",
          render: function ( data, type, row ) {
            var button = '';
            if (actionmenu.indexOf('update') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="editcontractorname(${row.id})">
              <i class="far fa-edit"></i>Update Data
              </a>`;
            }
            if (actionmenu.indexOf('delete') > 0) {
              button += `<a class="dropdown-item delete" href="javascript:void(0);" onclick="destroycontractorname(${row.id})">
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
          { data: "role_id" },
          { data: "name" },
          { data: "id" },
      ]
    });

    $('#form-search-contractorname').submit(function(e) {
      e.preventDefault();
      dataTablecontractorname.draw();
      $('#form-filter-contractorname').modal('hide');
    });

    $('#role_id').select2({
      placeholder: "Choose Contractor User Group...",
      ajax: {
        url: "{{ route('role.select') }}",
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
          params.page = params.page || 1;
          var more  = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item) {
            option.push({
              id: item.id,
              text: `${item.name}`,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    });
  });
</script>
@endsection
