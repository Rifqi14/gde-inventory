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
            <table class="table table-striped" id="table-bt" data-status="">
              <thead>
                <tr>
                  <th width="5%">No.</th>
                  <th width="30%">Business Trip Number</th>
                  <th width="20%">Activity</th>
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
            <table class="table table-striped" id="table-bt2" data-status="approved">
              <thead>
                <tr>
                  <th width="5%">No.</th>
                  <th width="30%">Business Trip Number</th>
                  <th width="20%">Activity</th>
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
  $(function() {
        $('.select2').select2();
		dataTable = $('#user-table').DataTable({
            processing: true,
            language: {
                processing: `<div class="p-2 text-center">
                                <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...
                            </div>`
            },
            serverSide: true,
            aaSorting: [],
            filter: false,
            responsive: true,
            lengthChange: false,
            order: [[ 2, "asc" ]],
            ajax: {
                url: "{{route('workingshift.read')}}",
                type: "GET",
                data:function(data){
                    var shift_name = $('#form-search').find('input[name=shift_name]').val();
                    var status = $('#form-search').find('#status').val();
                    data.shift_name = shift_name;
                    data.status = status;
                }
            },
            columns: [
                {"data": "no", "name": "no", width: 10, className: "text-center" , orderable:false},
                {
                    width: 50,
                    orderable: false,
                    render: function(data, type, full, meta) {
                        if (full.shift_type == 'shift') {
                            return `Shift`;
                        } else {
                            return `Non Shift`;
                        }
                    }
                },
                {
                    "data": "shift_name", 
                    "name": "shift_name",
                    width: 150,
                    render: function(data, type, full, meta) {
                        return `<a href="{{url('admin/workingshift')}}/${full.id}">
                                    <div class="text-md text-info text-bold">
                                        ${full.shift_name}
                                    </div>
                                </a>`;
                    }
                },
                {
                    width: 50,
                    className: "text-center",
                    orderable: false,
                    render: function(data, type, full, meta) {
                        if (full.status == 'active') {
                            return `<span class="badge bg-success text-sm">Active</span>`;
                        } else {
                            return `<span class="badge bg-gray color-platte text-sm">Non Active</span>`;
                        }
                    }
                },
                {
                    width: 50,
                    className: "text-center",
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return `<div class="btn-group">
                                    <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="edit(${full.id})">
                                            <i class="far fa-edit"></i>Update Data
                                        </a>
                                        <a class="dropdown-item " href="javascript:void(0);" onclick="destroy(${full.id})">
                                            <i class="far fa-trash-alt"></i> Delete Data
                                        </a>
                                    </div>
                                </div>`;
                    }
                }
            ]
        });
		$('#form-search').submit(function(e) {
			e.preventDefault();
			dataTable.draw();
			$('#add-filter').modal('hide');
		});

	});

	function edit(id)
	{
		window.location.href = `{{url('admin/workingshift')}}/${id}/edit`;
	}
	function detail(id)
	{
		window.location.href = `{{url('admin/workingshift')}}/${id}`;
	}
	function filter(){
		$('#add-filter').modal('show');
	}

	function destroy(id)
	{
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
					data:data,
					type:'DELETE',
					success:function(response){
						if(response.status){
                            dataTable.ajax.reload(null, false);
						}
						else{
							Swal.fire(
								'Error!',
								'Data Gagal Di Hapus.',
								'error'
							)
						}
				}});
				
			}
		});
	}
</script>
@endsection