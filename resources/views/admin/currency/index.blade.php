@extends('admin.layouts.app')
@section('title', $menu_name)

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-attendance" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('currency.create') }}')">
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
            <table id="table-currency" class="table table-striped datatable" width="100%">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Currency</th>
                  <th>Country</th>
                  <th>#</th>
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
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="country_id" class="control-label">Country</label>
                <select name="country_id" id="country_id" class="form-control select2">
                  <option value="">Select Country</option>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="currency" class="control-label">Currency</label>
                <input type="text" name="currency" id="currency" class="form-control" placeholder="Currency">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="text-right mt-4">
          <button type="submit" form="form-search" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple">
            <b><i class="fas fa-search"></i></b>
            Filter
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  var actionmenu = JSON.parse('{!! json_encode($actionmenu) !!}');

  const formatCountry = (country) => {
    if (!country.code) { return country.text; }
    var $country = $(
      '<span class="flag-icon flag-icon-'+ country.code.toLowerCase() +' flag-icon-squared"></span>' +
      '&nbsp;&nbsp;&nbsp;<span class="flag-text">'+ country.text+"</span>"
    );
    return $country;
  }

  const filter = () => {
    $('#form-filter').modal('show');
  }

  const edit = (id) => {
    document.location = `{{ route('currency.index') }}/${id}/edit`;
  }

  $(function() {
    $('.select2').select2();
    $("#country_id").select2({
      placeholder: "Select Country...",
      ajax: {
        url: "{{route('country.select')}}",
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
            var more = (params.page * 30) < data.total;
            var option = [];
            $.each(data.rows, function(index, item) {
                option.push({
                    id: item.id,
                    text: item.country,
                    code: item.code,
                });
            });
            return {
                results: option,
                pagination: {
                  more: more,
                }
            };
        },
      },
      templateResult: formatCountry,
      allowClear: true,
    });
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
            url: "{{route('currency.read')}}",
            type: "GET",
            data:function(data){
                var currency = $('#form-search').find('input[name=currency]').val();
                var country_id = $('#form-search').find('select[name=country_id]').val();
                data.currency     = currency;
                data.country_id = country_id;
            }
        },
        columnDefs:[
            {
                orderable: false,targets:[0,3]
            },
            { className: "text-center", targets: [0,3] },
            { className: "text-right", targets: [0] },
            {
              width: "2%",
              render: function(data, type, row) {
                return row.no;
              }, targets: [0]
            },
            {
              width: "40%",
              render: function(data, type, row) {
                return row.countries_id ? `<span class="flag-icon flag-icon-${row.country.code.toLowerCase()} flag-icon-squared"></span> <span>${row.country.country}</span>` : `-`;
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
            { data: "currency" },
            { data: "countries_id" },
            { data: "id" },
        ]
    });

    $('#form-search').submit(function(e) {
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
                        url: `{{route('currency.index')}}/${id}`,
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