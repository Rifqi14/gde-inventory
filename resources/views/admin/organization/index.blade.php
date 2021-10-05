@section('button')
@parent
@if (in_array('create', $actionmenu))
<button type="button" id="add-organization" class="btn btn-labeled text-sm btn-sm btn-success btn-flat d-none legitRipple" onclick="windowLocation('{{ route('organization.create') }}')">
  <b><i class="fas fa-plus"></i></b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-organization" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple d-none" onclick="filterOrganizationCode()">
  <b><i class="fas fa-search"></i></b> Filter
</button>
@endif
@endsection

<div class="tab-pane fade show" id="organizationcode" role="tabpanel" aria-labelledby="organizationcode-tab">
  <div class="table-responsive">
    <table id="table-organization" class="table table-striped datatable" width="100%">
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
<div class="modal fade" id="form-filter-organizationcode">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Filter {{ $menu_name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-search-organizationcode" method="POST" autocomplete="off">
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
        <button type="submit" form="form-search-organizationcode" class="btn btn-labeled text-sm btn-default btn-flat legitRipple">
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
  var actionmenu  = @json(json_encode($actionmenu));

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

  const filterOrganizationCode = () => {
    $('#form-filter-organizationcode').modal('show');
  }

  const editOrganizationCode = (id) => {
    document.location   = `{{ route('organization.index') }}/${id}/edit`;
  }

  const destroyOrganizationCode = (id) => {
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
            url: `{{route('organization.index')}}/${id}`,
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
              dataTableOrganization.ajax.reload(null, false);
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
    $('.select2').select2();
    dataTableOrganization = $('#table-organization').DataTable( {
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
      order: [[ 1, "asc" ]],
      ajax: {
        url: "{{route('organization.read')}}",
        type: "GET",
        data:function(data){
          var code      = $('#form-search-organizationcode').find('input[name=code]').val();
          var name      = $('#form-search-organizationcode').find('input[name=name]').val();
          data.code     = code;
          data.name     = name;
        }
      },
      columnDefs:[
        { orderable: false,targets:[0,3] },
        { className: "text-center", targets: [0,3] },
        {
          width: "10%",
          render: function ( data, type, row ) {
            var button = '';
            if (actionmenu.indexOf('update') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="editOrganizationCode(${row.id})">
              <i class="far fa-edit"></i>Update Data
              </a>`;
            }
            if (actionmenu.indexOf('delete') > 0) {
              button += `<a class="dropdown-item delete" href="javascript:void(0);" onclick="destroyOrganizationCode(${row.id})">
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

    $('#form-search-organizationcode').submit(function (e) {
      e.preventDefault();
      dataTableOrganization.draw();
      $('#form-filter-organizationcode').modal('hide');
    });
  });
</script>
@endsection
