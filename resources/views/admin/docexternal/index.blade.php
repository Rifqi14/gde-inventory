@extends('admin.layouts.app')
@section('title', $menu_name)

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-documentexternal" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="create()">
  <b><i class="fas fa-plus"></i></b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-documentexternal" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
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
          <div class="card-header p-0">
            <ul class="nav nav-tabs tabs-engineering" id="{{ $page }}-tab" role="tablist">
              @foreach ($categories as $key => $category)
              <li class="nav-item">
                <a class="nav-link {{ $key == 0 ? 'active' : ''}} document" id="{{ $category->disciplinecode->code }}-tab" data-toggle="pill" href="#{{ $page }}-tab" role="tab" aria-controls="{{ $page }}-tab" aria-selected="true" data-type="{{ $category->disciplinecode->code }}" data-category="{{ $category->disciplinecode->id }}" data-page="{{ $page }}" onClick="changeTab('{{ $category }}')">{{ $category->disciplinecode->code }}</a>
              </li>
              @endforeach
            </ul>
          </div>
          <div class="card-body table-responsive p-0">
            <div class="tab-content" id="{{ $page }}-tab-content">
              <div class="tab-pane fade show active" id="{{ $page }}-tab" role="tabpanel" aria-labelledby="{{ $page }}-tab">
                <input type="hidden" name="type">
                <input type="hidden" name="category">
                <table id="table-{{ $page }}" class="table table-striped datatable ajaxTable" width="100%">
                  <thead>
                    <tr>
                      <th width="15%">Drawing & Document No.</th>
                      <th width="30%">Document Title</th>
                      <th width="10%">Revision</th>
                      <th width="30%">Document Type Code</th>
                      <th width="10%">Planned Date</th>
                      <th width="10%">Issue Status</th>
                      <th width="3%" class="text-center">Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
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
        <h5 class="modal-title text-bold">Filter</h5>
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
  var category    = $(`.nav-link.active.document`).data('category');
  var page        = `{{ $page }}`;

  const filter = () => $('#form-filter').modal('show');

  const edit = id => document.location = `{{ url('admin/documentcenterexternal') }}/${page}/${id}/edit`;

  const create = () => document.location = `{{ route('documentcenterexternal.create', ['page' => $page]) }}`;

  const changeTab = type => {
    type  = JSON.parse(type);
    $('input[name="type"]').val(type.name);
    $('input[name="category"]').val(type.id);
    typeData  = type.name;
    category  = type.disciplinecode.id;
    dataTable.draw();
  }

  const destroy = id => {
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
            url: `{{url('admin/documentcenterexternal')}}/${page}/${id}`,
            dataType: 'json',
            data: data,
            type: 'post',
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
    dataTable = $('.ajaxTable').DataTable({
      processing: true,
      language: {
        processing: `<div class="p-2 text-center"><i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...</div>`
      },
      serverSide: true,
      filter: false,
      responsive: true,
      lengthChange: true,
      pageLength: 50,
      order: [[1, "asc"]],
      ajax: {
        url: "{{ route('documentcenterexternal.read') }}",
        type: "GET",
        data: function(data){
          data.category = category;
          data.page     = `{{ $page }}`;
        }
      },
      columnDefs: [
        { orderable: false, targets: [0, 6] },
        { className: "text-center", targets: [0, 3, 6] },
        { render: function (data, type, row) {
          return row.document_number ? `<a href="javascript:void(0);" onclick="edit(${row.id})"><div class="text-md text-info text-bold">${row.document_number}</div></a>` : '';
        }, targets: [1] },
        { render: function (data, type, row) {
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
        }, targets: [6] },
      ],
      columns: [
        { data: "document_number" },
        { data: "document_title" },
        { data: "document_title", className: "text-md text-bold" },
        { data: "discipline_code_id" },
        { data: "site_code_id" },
        { data: "created_by" },
        { data: "id" },
      ]
    });
  });
</script>
@endsection
