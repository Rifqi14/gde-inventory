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
  var global_status = JSON.parse(`{!! json_encode(config('enums.global_status')) !!}`);
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
            url: `{{url('admin/documentcenter')}}/${page}/${id}`,
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
          return `For ${row.issue_purpose}`;
        }, targets: [4] },
        { render: function (data, type, row) {
          var label     = '',
              text      = '',
              status    = row.issue_status;

          $.each(global_status, function(index, value) {
            if (index == status) {
              label   = value.badge;
              text    = value.text;
            }
            if (status == 'REVISED') {
              label   = 'secondary';
              text    = 'Draft';
            }

            if (status == 'APPROVED') {
              label   = value.badge;
              text    = 'Issued';
            }

            if (row.document_type) {
              label   = 'info';
              String.prototype.ucwords = function() {
                str = this.toLowerCase();
                return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
                  function($1){
                      return $1.toUpperCase();
                  });
              }

              var document_type = row.document_type;
              text    = document_type.ucwords();
            }
          });
          return row.created_by ? `${row.created_by.name}<br><span class="badge bg-${label} text-sm">${text}</span>` : '';
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
        { data: "issue_purpose" },
        { data: "created_user" },
        { data: "id" },
      ]
    });
  });
</script>
@endsection