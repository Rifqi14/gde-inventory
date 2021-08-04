@extends('admin.layouts.app')
@section('title', $menu_name)

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-attendance" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="createData()">
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
          <div class="card-header p-0">
            <ul class="nav nav-tabs tabs-engineering" id="{{ $page }}-tab" role="tablist">
              @foreach ($categories as $key => $category)
              <li class="nav-item">
                <a class="nav-link {{ $key == 0 ? 'active' : ''}} document" id="{{ $category->doctype->code }}-tab" data-toggle="pill" href="#{{ $page }}-tab" role="tab" aria-controls="{{ $page }}-tab" aria-selected="true" data-type="{{ $category->doctype->code }}" data-category="{{ $category->id }}" data-page="{{ $page }}" onClick="changeTab('{{ $category }}')">{{ $category->doctype->code }}</a>
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
                      <th width="2%">No</th>
                      <th width="15%">Document Number</th>
                      <th width="30%">Document Title</th>
                      <th width="10%">Revision</th>
                      <th width="30%">Issue Purpose</th>
                      <th width="10%">Issued By</th>
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
@endsection

@section('scripts')
<script>
  var page      = `{{ $page }}`;
  var typeData  = '';
  var category  = $(`.nav-link.active.document`).data('category');
  var actionmenu= @json(json_encode($actionmenu));

  const changeTab = (type) => {
    type  = JSON.parse(type);
    $('input[name="type"]').val(type.name);
    $('input[name="category"]').val(type.id);
    typeData  = type.name;
    category  = type.id;
    dataTable.draw();
  }

  const createData = () => {
    window.location = `{{ url('admin/documentcenter') }}/${page}/create/${category}`;
  }

  const convertToSlug = (e) => {
    return e.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
  }

  const filter = () => {
    $('#form-filter').modal('show');
  }

  const edit = (id) => {
    document.location = `{{ url('admin/documentcenter') }}/${page}/${id}/edit`;
  }

  $(function() {
    typeData  = $(`.nav-link.active.document`).data('type');
    $('input[name="type"]').val($(`.nav-link.active.document`).data('type'));
    $('input[name="category"]').val($(`.nav-link.active.document`).data('category'));

    $('.select2').select2();
    dataTable = $('.ajaxTable').DataTable({
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
        url: "{{ route('documentcenter.read') }}",
        type: "GET",
        data: function(data){
          data.category = category;
          data.menu     = `{{ $page }}`;
        }
      },
      columnDefs: [
        { orderable: false, targets: [0, 6] },
        { className: "text-center", targets: [0, 3, 6] },
        { render: function (data, type, row) {
          return row.document_number ? `<a href="javascript:void(0);" onclick="edit(${row.id})"><div class="text-md text-info text-bold">${row.document_number}</div></a>` : '';
        }, targets: [1] },
        { render: function (data, type, row) {
          var html  = '';
          html += `<font class="text-md text-bold">${row.revision}</font>
                   <div class="text-sm text-semibold">Date: <font class="text-info">${row.revision_date}</font></div>`
          return html;
        }, targets: [3] },
        { render: function (data, type, row) {
          return row.created_by ? row.created_by.name : '';
        }, targets: [5] },
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
        { data: "no" },
        { data: "document_number" },
        { data: "title", className: "text-md text-bold" },
        { data: "revision" },
        { data: "purpose" },
        { data: "created_user" },
        { data: "id" },
      ]
    });
  });
</script>
@endsection