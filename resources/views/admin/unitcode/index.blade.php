@section('button')
@parent
@if (in_array('create', $actionmenu))
<button type="button" id="add-unitcode" class="d-none btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('unitcode.create') }}')">
  <b><i class="fas fa-plus"></i></b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-unitcode" class="d-none btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filterUnitCode()">
  <b><i class="fas fa-search"></i></b> Filter
</button>
@endif
@endsection

<div class="tab-pane fade show" id="unitcode" role="tabpanel" aria-labelledby="unitcode-tab">
  <div class="table-responsive">
    <table id="table-unitcode" class="table table-striped datatable" width="100%">
      <thead>
        <tr>
          <th width="2%">No.</th>
          <th width="25%">Code</th>
          <th width="45%">Name</th>
          <th width="25%">Organization</th>
          <th width="3%">Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@push('filter')
<div class="modal fade" id="form-filter-unitcode">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Filter {{ $menu_name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-search-unitcode" method="POST" autocomplete="off">
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
            <div class="col-md-12">
              <div class="form-group">
                <label for="organization_id" class="control-label">Organization</label>
                <select name="organization_id" id="organization_id" class="form-control select2" data-placeholder="Choose Organization..."></select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <button type="submit" form="form-search-unitcode" class="btn btn-labeled text-sm btn-default btn-flat legitRipple">
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

  const filterUnitCode = () => {
    $('#form-filter-unitcode').modal('show');
  }

  const editUnitCode = (id) => {
    document.location   = `{{ route('unitcode.index') }}/${id}/edit`;
  }

  const destroyUnitCode = (id) => {
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
            url: `{{route('unitcode.index')}}/${id}`,
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
              dataTableUnitCode.ajax.reload(null, false);
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
    dataTableUnitCode = $('#table-unitcode').DataTable( {
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
        url: "{{route('unitcode.read')}}",
        type: "GET",
        data:function(data){
          var code      = $('#form-search-unitcode').find('input[name=code]').val();
          var name      = $('#form-search-unitcode').find('input[name=name]').val();
          var organization_id = $('#form-search-unitcode').find('select[name=organization_id]').val();
          data.code     = code;
          data.name     = name;
          data.organization_id = organization_id;
        }
      },
      columnDefs:[
        { orderable: false,targets:[0,4] },
        { className: "text-center", targets: [0,4] },
        {   
          width: "25%",
          render: function ( data, type, row ) {
          return row.organization ? `<b>${row.organization.code}</b> - ${row.organization.name}` : '';
          },targets: [3]
        },
        {   
          width: "3%",
          render: function ( data, type, row ) {
            var button = '';
            if (actionmenu.indexOf('update') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="editUnitCode(${row.id})">
              <i class="far fa-edit"></i>Update Data
              </a>`;
            }
            if (actionmenu.indexOf('delete') > 0) {
              button += `<a class="dropdown-item delete" href="javascript:void(0);" onclick="destroyUnitCode(${row.id})">
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
          { data: "organization_code_id" },
          { data: "id" },
      ]
    });

    $('#organization_id').select2({
      placeholder: "Choose Organization...",
      ajax: {
        url: "{{ route('organization.select') }}",
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
              text: `${item.code} - ${item.name}`,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    });

    $('#form-search-unitcode').submit(function (e) {
      e.preventDefault();
      dataTableUnitCode.draw();
      $('#form-filter-unitcode').modal('hide');
    });
  });
</script>
@endsection