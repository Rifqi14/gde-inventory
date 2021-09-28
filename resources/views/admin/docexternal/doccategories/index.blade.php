@extends('admin.layouts.app')
@section('title')
{{ $menu_name }}
@endsection
@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-category" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="create()">
  <b><i class="fas fa-plus"></i></b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-category" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
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
          <div class="card-body table-responsive px-0">
            <table id="table-category" class="table table-striped datatable" style="width: 100%">
              <thead>
                <tr>
                  <th width="5%">No.</th>
                  <th width="45%">Sub Menu</th>
                  <th width="45%">Discipline Code</th>
                  <th width="5%">#</th>
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
        <h5 class="modal-title text-bold">Filter Doc Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-search" method="post" autocomplete="off">
          @csrf
          <input type="hidden" name="_method" />
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label" for="menu_id">Sub Menu</label>
                <select name="menu_id" id="menu_id" class="select2 form-control">
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label" for="discipline_code_id">Discipline Code</label>
                <select name="discipline_code_id" id="discipline_code_id" class="select2 form-control">
                </select>
              </div>
            </div>
          </div>
          <div class="text-right mt-4">
            <button type="submit" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple">
              <b><i class="fas fa-search"></i></b>
              Filter
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>
@endsection

@section('scripts')
<script>
  var actionmenu  = @json(json_encode($actionmenu));

  const filter = () => {
      $('#form-filter').modal('show');
  }

  const edit = (id) => {
    document.location = `{{ route('documentcategoriesexternal.index') }}/${id}/edit`;
  }

  const create = () => {
    document.location = `{{ route('documentcategoriesexternal.create') }}`;
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
            url: `{{route('documentcategoriesexternal.index')}}/${id}`,
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

  $(function(){
    $(".select2").select2();
    dataTable = $('#table-category').DataTable({
      processing: true,
      language: {
          processing: `<div class="p-2 text-center"><i class="fas fa-circle-notch fa-spin fa-fw"></i>Loading...</div>`
      },
      serverSide: true,
      filter: false,
      responsive: true,
      lengthChange: false,
      pageLength: 50,
      order: [[ 1, "asc" ]],
      ajax: {
        url: "{{route('documentcategoriesexternal.read')}}",
        type: "GET",
        data:function(data){
          var menu_id             = $('#form-search').find('select[name=menu_id]').val();
          var discipline_code_id    = $('#form-search').find('select[name=discipline_code_id]').val();
          data.menu_id            = menu_id;
          data.discipline_code_id   = discipline_code_id;
        }
      },
      columnDefs:[
        { orderable: false,targets:[0,3] },
        { className: "text-right", targets: [0] },
        { className: "text-center", targets: [3] },
        {
          width: "5%",
          render: function ( data, type, row ) {
            return row.no;
          },targets: [0]
        },
        {
          render: function ( data, type, row ) {
            return row.menu ? row.menu.menu_name : '';
          },targets: [1]
        },
        {
          render: function ( data, type, row ) {
            return row.disciplinecode ? `${row.disciplinecode.code} - ${row.disciplinecode.name}` : '';
          },targets: [2]
        },
        {
          width: "10%",
          render: function ( data, type, row ) {
            var button  = '';
            if (actionmenu.indexOf('update') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})"><i class="far fa-edit"></i>Update Data</a>`;
            }
            if (actionmenu.indexOf('delete') > 0) {
              button += `<a class="dropdown-item delete" href="javascript:void(0);" onclick="destroy(${row.id})"><i class="fa fa-trash-alt"></i> Delete Data</a>`;
            }
            return `<div class="btn-group"><button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fas fa-bars"></i></button><div class="dropdown-menu">${button}</div></div>`;
          },targets: [3]
        }
      ],
      columns: [
        { data: "no" },
        { data: "menu_id" },
        { data: "discipline_code_id" },
        { data: "id" },
      ]
    });

    $('#form-search').submit(function(e){
      e.preventDefault();
      dataTable.draw();
      $('#form-filter').modal('hide');
    });

    $('#menu_id').select2({
      placeholder: "Choose Sub Menu ...",
      ajax: {
        url: "{{ route('menu.select') }}",
        type: 'GET',
        dataType: 'json',
        data: function(params) {
          return {
            route: 'docexternal',
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function(data, params) {
          params.page = params.page || 1;
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item) {
            if (item.child) {
              $.each(item.child, function(indexChild, child) {
                option.push({
                  id: child.id,
                  text: `${child.menu_name}`,
                });
              })
            }
          });
          return { results: option, more: more };
        },
      },
      allowClear: true,
    });

    $('#discipline_code_id').select2({
      placeholder: "Choose Discipline Code ...",
      ajax: {
        url: "{{ route('disciplinecode.select') }}",
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
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item) {
            option.push({
              id: item.id,
              text: `${item.code}`,
              name: `${item.name}`,
            });
          });
          return { results: option, more: more };
        },
      },
      allowClear: true,
    });
  });
</script>
@endsection
