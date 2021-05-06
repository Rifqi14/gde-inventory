@extends('admin.layouts.app')

@section('title')
Business Trips
@endsection

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">
      Business Trips
    </h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">Preferences</li>
      <li class="breadcrumb-item">Activities</li>
      <li class="breadcrumb-item active">Business Trips</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title mt-2"> List of Business Trips</h3>
            <div class="text-right">
              <a href="{{ route('businesstrip.create') }}">
                <button type="button" class="btn btn-labeled btn-md text-sm btn-success btn-flat legitRipple">
                  <b><i class="fas fa-plus"></i></b> Create
                </button>
              </a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-striped" id="table-bt-general">
              <thead>
                <tr>
                  <th width="5%">No.</th>
                  <th width="30%">Business Trip Number</th>
                  <th width="20%" class="text-center">Schedule</th>
                  <th width="20%">Budget</th>
                  <th width="15%" class="text-center">Status</th>
                  <th width="10%" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>

        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title"> List of Approved Business Trips</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-striped" id="table-bt-approved">
              <thead>
                <tr>
                  <th width="5%">No.</th>
                  <th width="30%">Business Trip Number</th>
                  <th width="20%" class="text-center">Schedule</th>
                  <th width="20%">Budget</th>
                  <th width="15%" class="text-center">Status</th>
                  <th width="10%" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script type="text/javascript">
  var actionmenu = JSON.parse('{!! json_encode($actionmenu) !!}');
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
  };

  $(function() {
    $('.select2').select2({
      allowClear: true
    });

    tableGeneral = $('#table-bt-general').DataTable({
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
      order: [
        [1, "asc"]
      ],
      ajax: {
        url: "{{route('businesstrip.read')}}",
        type: "GET",
        data: function(data){           
        }
      },
      columnDefs: [{
          orderable: false,
          targets: [0, 5]
        },
        {
          className: "text-center",
          targets: [0,4,5]
        },
        {
          render: function(data, type, row) {
            var button = '';
            // update
            if (actionmenu.indexOf('update') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
                                        <i class="far fa-edit"></i>Update Data
                                    </a>`;
            }
            // delete
            if (actionmenu.indexOf('delete') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="destroy(${row.id})">
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
          },
          targets: [5]
        }
      ],
      columns: [{
          data: "no"
        },
        {
          data: "business_trip_number"
        },
        {
          data: "activity"
        },
        {
          data: "budget"
        },{
          data: "status"
        }
      ]
    });

    tableApproved = $('#table-bt-approved').DataTable({
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
      order: [
        [1, "asc"]
      ],
      ajax: {
        url: "{{route('businesstrip.read')}}",
        type: "GET",
        data: function(data){           
          data.status = 3
        }
      },
      columnDefs: [{
          orderable: false,
          targets: [0, 5]
        },
        {
          className: "text-center",
          targets: [0,4,5]
        },
        {
          render: function(data, type, row) {
            var button = '';
            // update
            if (actionmenu.indexOf('update') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
                                        <i class="far fa-edit"></i>Update Data
                                    </a>`;
            }
            // delete
            if (actionmenu.indexOf('delete') > 0) {
              button += `<a class="dropdown-item" href="javascript:void(0);" onclick="destroy(${row.id})">
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
          },
          targets: [5]
        }
      ],
      columns: [{
          data: "no"
        },
        {
          data: "business_trip_number"
        },
        {
          data: "schedule"
        },
        {
          data: "budget"
        },{
          data: "status"
        }
      ]
    });

  });

  function edit(id) {
    window.location.href = `{{url('admin/workingshift')}}/${id}/edit`;
  }

  function detail(id) {
    window.location.href = `{{url('admin/workingshift')}}/${id}`;
  }

  function filter() {
    $('#add-filter').modal('show');
  }

  function destroy(id) {
    Swal.fire({
      title: 'Hapus',
      text: "Apa Anda Yakin Akan Menghapus Data ?",
      icon: 'error',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.value) {
        var data = {
          _token: "{{ csrf_token() }}"
        };
        $.ajax({
          url: `{{url('admin/workingshift')}}/${id}`,
          dataType: 'json',
          data: data,
          type: 'DELETE',
          success: function(response) {
            if (response.status) {
              dataTable.ajax.reload(null, false);
            } else {
              Swal.fire(
                'Error!',
                'Data Gagal Di Hapus.',
                'error'
              )
            }
          }
        });

      }
    });
  }
</script>
@endsection