@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-contract_receipt" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('contractreceipt.create') }}')">
  <b>
    <i class="fas fa-plus"></i>
  </b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-contract_receipt" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
  <b>
    <i class="fas fa-search"></i>
  </b> Search
</button>
@endif
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">{{ $menu_name }}</h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name }}</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section id="content" class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header"></div>
          <div class="card-body table-responsive p-0">
            <table id="table-contract_receipt" class="table table-striped datatable" width="100%">
              <thead>
                <tr>
                  <th width="5">No.</th>
                  <th width="100">Contract</th>
                  <th width="100">Warehouse</th>
                  <th width="100">Batch</th>
                  <th width="100">Document Uploaded</th>
                  <th width="100">Status</th>
                  <th width="20">#</th>
                </tr>
              </thead>
              <tbody>
                  
              </tbody>
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
          @csrf
          <input type="hidden" name="_method" />
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label" for="contract_id">Contract</label>
                <select name="contract_id" id="contract_id" class="form-control select2" data-placeholder="Select Contract">
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label" for="status">Status</label>
                <select name="status" id="status" class="form-control select2">
                  <option value="">Select Type</option>
                  @foreach(config('enums.status_receipt') as $key => $type)
                  <option value="{{ $key }}">{{ $type }}</option>
                  @endforeach
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
  </div>
</div>
@endsection

@section('scripts')
<script>
  const filter = () => {
    $('#form-filter').modal('show');
  }

  function edit(id) {
      document.location = '{{route('contractreceipt.index')}}/' + id + '/edit';
  }
  function view(id) {
      document.location = '{{route('contractreceipt.index')}}/' + id;
  }

  $(function(){
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
                url: "{{route('contractreceipt.read')}}",
                type: "GET",
                data:function(data){
                    var contract_id = $('#form-search').find('select[name=contract_id]').val();
                    var status = $('#form-search').find('select[name=status]').val();
                    data.contract_id = contract_id;
                    data.status = status;
                }
            },
            columnDefs:[
                {
                    orderable: false,targets:[0,3]
                },
                { className: "text-right", targets: [] },
                { className: "text-center", targets: [0,5,6] },
                {
                    render: function ( data, type, row ) {
                        return `<b><a href="#">${row.contract.number}</a></b><br>${row.contract_date}`;
                    },targets: [1]
                },
                {
                    render: function ( data, type, row ) {
                        return `${row.warehouse.name}`;
                    },targets: [2]
                },
                {
                    render: function ( data, type, row ) {
                        return `${row.batch} of ${row.total_batch}`;
                    },targets: [3]
                },
                {
                    render: function ( data, type, row ) {
                        return `${row.uploaded_document} of ${row.document_count}`;
                    },targets: [4]
                },
                {
                    render: function ( data, type, row ) {
                        return `${row.status_text}`;
                    },targets: [5]
                },
                {   
                    width: "10%",
                    render: function ( data, type, row ) {
                    return `<div class="btn-group">
                    <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);" onclick="view(${row.id})">
                        <i class="far fa-eye"></i>View Data
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
                        <i class="far fa-edit"></i>Update Data
                        </a>
                        <a class="dropdown-item delete" href="javascript:void(0);" data-id="${row.id}">
                        <i class="fa fa-trash-alt"></i> Delete Data
                        </a>
                    </div>
                    </div>`;
                    },targets: [6]
                }
            ],
            columns: [
                { data: "no" },
                { data: "contract_id" },
                { data: "warehouse_id" },
                { data: "batch" },
                { data: "id" },
                { data: "status" },
                { data: "id" },
            ]
        });

    $('#contract_id').select2({
      ajax: {
        url: "{{ route('contractreceipt.selectcontract') }}",
        type: "GET",
        dataType: "json",
        data: function (params) {
          return {
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function (data, params) {
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item){
            option.push({
              id:item.id,
              text: item.title,
              number: item.number,
              exp_status: item.exp_status,
              contract_date: item.contract_date,
            });
          });
          return { results: option, more: more };
        },
      },
      allowClear: true,
      templateSelection: selectionContract,
      templateResult: resultContract
    });

    $('#form-search').submit(function(e){
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
            message: 'Are you sure want to delete this contract receipt?',
            callback: function (result) {
                if (result) {
                    var data = {
                        _token: "{{ csrf_token() }}"
                    };
                    $.ajax({
                        url: `{{route('contractreceipt.index')}}/${id}`,
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

  function resultContract(state){
        if (!state.id) {
            return state.text;
        }
        var $state = $(`
            <span>${state.text}</span><span class="float-right">${state.number}</span><br>
            <small>${state.exp_status}</small>
        `);
        return $state;
    }

    function selectionContract(state) {
        if (!state.id) {
            return state.text;
        }
        var $state = $(`<span>${state.text}</span> - <span>${state.number}</span>`);
        return $state;
    };

</script>
@endsection