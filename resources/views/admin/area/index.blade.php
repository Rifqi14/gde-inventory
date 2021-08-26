@extends('admin.layouts.app')
@section('title', $menu_name)

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-attendance" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('area.create') }}')">
  <b><i class="fas fa-plus"></i></b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-attendance" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
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
            <table id="table-area" class="table table-striped datatable" width="100%">
              <thead>
                <tr>
                  <th width="2%">No.</th>
                  <th width="20%">Area</th>
                  <th width="15%">Unit</th>
                  <th width="60%">Remark</th>
                  <th width="3%">Action</th>
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
        <form id="form-search" method="POST" autocomplete="off">
          @csrf
          <input type="hidden" name="_method">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="area" class="control-label">Area</label>
                <input type="text" name="area" id="area" class="form-control" placeholder="Area">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="site_id" class="control-label">Unit</label>
                <select name="site_id" id="site_id" class="form-control select2"></select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <button type="submit" form="form-search" class="btn btn-labeled text-sm btn-default btn-flat legitRipple">
          <b><i class="fas fa-search"></i></b>
          Filter
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
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

  const filter = () => {
    $('#form-filter').modal('show');
  }

  const edit = (id) => {
    document.location   = `{{ route('area.index') }}/${id}/edit`;
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
      callback: function (result) {
        if (result) {
          var data = {
            _token: "{{ csrf_token() }}"
          };
          $.ajax({
            url: `{{route('area.index')}}/${id}`,
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
              dataTable.ajax.reload(null, false);
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
    dataTable = $('#table-area').DataTable( {
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
        url: "{{route('area.read')}}",
        type: "GET",
        data:function(data){
          var area      = $('#form-search').find('input[name=area]').val();
          var site_id   = $('#form-search').find('select[name=site_id]').val();
          data.area     = area;
          data.site_id  = site_id;
        }
      },
      columnDefs:[
        { orderable: false,targets:[0,4] },
        { className: "text-center", targets: [0,4] },
        { width: "20%",
          render: function ( data, type, row ) {
            return row.site ? row.site.name : '-';
          }, targets: [2],
        },
        {   
          width: "10%",
          render: function ( data, type, row ) {
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
          },targets: [4]
        }
      ],
      columns: [
          { data: "no" },
          { data: "name" },
          { data: "site_id" },
          { data: "remark" },
          { data: "id" },
      ]
    });

    $('#site_id').select2({
      placeholder: "Choose Unit...",
      ajax: {
        url: "{{ route('site.select') }}",
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
              text: item.name,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    });

    $('#form-search').submit(function (e) {
      e.preventDefault();
      dataTable.draw();
      $('#form-filter').modal('hide');
    });
  });
</script>
@endsection