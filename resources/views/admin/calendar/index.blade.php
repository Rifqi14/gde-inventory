@extends('admin.layouts.app')

@section('title', $menu_name)

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">{{ $menu_name }}</h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $menu_name }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            @if(in_array('read',$actionmenu))
            <a class="btn btn-labeled btn-sm text-sm btn-default btn-flat legitRipple  float-right ml-1" onclick="filter()"><b><i class="fas fa-search"></i></b> Search</a>
            @endif
            @if(in_array('create',$actionmenu))
            <a href="{{ route('calendar.create') }}" class="btn btn-labeled btn-sm text-sm btn-success btn-flat legitRipple  float-right ml-1"><b><i class="fas fa-plus"></i></b> Create</a>
            @endif
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="calendar-table" class="table table-bordered table-striped" style="width:100%">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="add-filter" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="filter-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Advance Filter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form method="post" id="form-search">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="name" class="control-label">Name</label>
                  <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="code" class="control-label">Code</label>
                  <input type="text" name="code" id="code" class="form-control" placeholder="Code">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="description" class="control-label">Description</label>
                  <textarea name="description" id="description" class="form-control" rows="4" style="resize: none;" placeholder="Description..."></textarea>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-labeled btn-danger btn-sm btn-flat legitRipple" data-dismiss="modal"><b><i class="fas fa-times"></i></b> Cancel</button>
        <button type="submit" form="form-search" class="btn btn-labeled btn-default btn-sm btn-flat legitRipple"><b><i class="fas fa-search"></i></b> Search</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
  var actionmenu = JSON.parse('{!! json_encode($actionmenu) !!}');
  $(function() {
    $('.select2').select2();
    dataTable   = $('#calendar-table').DataTable({
      processing: true,
      language: {
        processing: `<div class="p-2 text-center">
                        <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading ...
                     </div>`
      },
      serverSide: true,
      aaSorting: [],
      filter: false,
      responsive: true,
      lengthChange: false,
      order: [[ 1, "asc"]],
      ajax: {
        url: "{{ route('calendar.read') }}",
        type: "GET",
        data:function(data){
          var name        = $('#form-search').find('#name').val();
          var code        = $('#form-search').find('#code').val();
          var description = $('#form-search').find('#description').val();
          data.name       = name;
          data.code       = code;
          data.description= description;
        }
      },
      columns: [
        { "data": "no", "name": "no", width: 10, className: "text-center", orderable: false},
        { 
          "data": "name",
          width: 120,
          orderable: false,
          render: function(data, type, full, meta) {
            return `<a href="javascript:void(0)" onclick="show(${full.id})"><b>${full.name}</b></a><br><small>${full.code}</small>`;
          },
        },
        { "data": "description", "name": "description", width: 120, orderable: false },
        {
            width: 50,
            className: "text-center",
            orderable: false,
            render: function(data, type, full, meta) {
                var button = '';
                if (actionmenu.indexOf('read') > 0) {
                  button += `<a class="dropdown-item" href="javascript:void(0);" onclick="show(${full.id})">
                                    <i class="far fa-eye"></i>View Data
                                </a>`;
                }
                if (actionmenu.indexOf('update') > 0) {
                  button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${full.id})">
                                    <i class="far fa-edit"></i>Update Data
                                </a>`;
                }
                if (actionmenu.indexOf('delete') > 0) {
                  button += `<a class="dropdown-item " href="javascript:void(0);" onclick="destroy(${full.id})">
                                    <i class="far fa-trash-alt"></i> Delete Data
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
            }
        }
      ]
    });
    $("#form-search").submit(function(e) {
      e.preventDefault();
      dataTable.draw();
      $("#add-filter").modal('hide');
    });
  });

  function show(id)
	{
		window.location.href = `{{url('admin/calendar')}}/${id}/show`;
	}

  function edit(id)
	{
		window.location.href = `{{url('admin/calendar')}}/${id}/edit`;
	}
  
	function detail(id)
	{
		window.location.href = `{{url('admin/calendar')}}/${id}`;
	}
  
  function filter() {
    $("#add-filter").modal("show");
  }

  function destroy(id)
	{
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
        message: 'Are you sure want to delete this site?',
        callback: function (result) {
            if (result) {
                var data = {
                    _token: "{{ csrf_token() }}"
                };
                $.ajax({
                    url: `{{route('calendar.index')}}/${id}`,
                    dataType: 'json',
                    data: data,
                    type: 'DELETE',
                    beforeSend: function () {
                        blockMessage('body', 'Loading', '#fff');
                    }
                }).done(function (response) {
                    $('body').unblock();
                    if (response.status) {
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
                        toastr.success(response.message);
                        dataTable.ajax.reload(null, false);
                    }else {
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
                        toastr.warning(response.message);
                    }
                }).fail(function (response) {
                    var response = response.responseJSON;
                    $('body').unblock();
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
                    toastr.warning(response.message);
                })
            }
        }
    });
	}
</script>
@endsection