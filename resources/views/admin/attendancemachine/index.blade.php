@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-attendance_machine" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('attendancemachine.create') }}')">
  <b><i class="fas fa-plus"></i></b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-role" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
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
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">Home</li>
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
            <table id="table-machine" class="table table-striped datatable" width="100%">
              <thead>
                <tr>
                  <th width="20">No.</th>
                  <th width="100">Machine Name</th>
                  <th width="100">Type</th>
                  <th width="20" class="text-center">#</th>
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
        <form id="form-search" method="post" autocomplete="off">
          {{ csrf_field() }}
          <input type="hidden" name="_method" />
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label" for="machine_name">Machine Name</label>
                <input type="text" name="machine_name" class="form-control" placeholder="Machine Name">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-md-12 col-xs-12 control-label" for="type">Type</label>
                <div class="col-sm-12 controls">
                  <select name="type" id="type" class="form-control select2">
                    <option value="">Select Type</option>
                    @foreach(config('enums.machine_type') as $key => $type)
                    <option value="{{ $key }}">{{ $type }}</option>
                    @endforeach
                  </select>
                </div>
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
  <!-- /.modal-dialog -->
</div>
@endsection

@section('scripts')
<script>
  var actionmenu = JSON.parse('{!! json_encode($actionmenu) !!}');
  function filter() {
    $('#form-filter').modal('show');
  }
  function edit(id) {
      document.location = '{{route('attendancemachine.index')}}/' + id + '/edit';
  }
  $(function() {
    $('.select2').select2();
    dataTable = $('.datatable').DataTable( {
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
            url: "{{route('attendancemachine.read')}}",
            type: "GET",
            data:function(data){
                var machine_name = $('#form-search').find('input[name=machine_name]').val();
                var type = $('#form-search').find('select[name=type]').val();
                data.machine_name = machine_name;
                data.type         = type;
            }
        },
        columnDefs:[
            {
                orderable: false,targets:[0,3]
            },
            { className: "text-right", targets: [0] },
            { className: "text-center", targets: [3] },
            {
              width: "5%",
              render: function(data, type, row) {
                return row.no;
              }, targets: [0]
            },
            {
              render: function (data, type, row) {
                var type = '';
                if (row.type == 'CHECKIN') {
                  type = 'Check In';
                } else {
                  type = 'Check Out';
                }
                return `<span class="badge bg-success color-platte text-sm">${type}</span>`;
              }, targets: [2]
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
                    button += `<a class="dropdown-item delete" href="javascript:void(0);" data-id="${row.id}">
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
            { data: "machine_name", className: "text-bold" },
            { data: "type" },
            { data: "id" },
        ]
    });
    $('#form-search').submit(function (e) {
        e.preventDefault();
        dataTable.draw();
        $('#form-filter').modal('hide');
    });

    $(document).on('click', '.delete', function () {
        var id = $(this).data('id');
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
                        url: `{{route('attendancemachine.index')}}/${id}`,
                        dataType: 'json',
                        data: data,
                        type: 'DELETE',
                        beforeSend: function () {
                            blockMessage('#content', 'Loading', '#fff');
                        }
                    }).done(function (response) {
                        $('#content').unblock();
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
                        $('#content').unblock();
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
    });
  });
</script>
@endsection