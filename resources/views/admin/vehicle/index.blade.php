@extends('admin.layouts.app')

@section('title')
Vehicle Database
@endsection

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <!-- <h5 class="m-0 ml-2 text-dark text-md breadcrumb">Grievance Redress &nbsp;<small class="font-uppercase"></small></h5> -->
        <h1 id="title-branch" class="m-0 text-dark">
            Vehicle Database
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Preferences</li>
            <li class="breadcrumb-item">Vehicle</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a class="btn btn-labeled btn-sm text-sm btn-default btn-flat legitRipple  float-right ml-1"
                            onclick="filter()">
                            <b><i class="fas fa-search"></i></b> Search
                        </a>
                        <a href="{{ route('vehicle.create') }}"
                            class="btn btn-labeled btn-sm text-sm btn-success btn-flat legitRipple  float-right ml-1">
                            <b><i class="fas fa-plus"></i></b> Create
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="user-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Police Number</th>
                                        <th>Name</th>
                                        <th>Unit</th>
                                        <th>Remarks</th>
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
<!-- /.content -->

<div id="add-filter" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="filter-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Advance Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-search" method="POST">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="police_number">Police Number</label>
                                    <input type="text" name="police_number" class="form-control"
                                        placeholder="Police Number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="vehicle_name">Name</label>
                                    <input type="text" name="vehicle_name" class="form-control" placeholder="Name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="site_id">Unit</label>
                                    <select type="text" class="form-control" id="unit_id" name="site_id"
                                        data-placeholder="Unit"></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="status">Status</label>
                                    <select name="status" id="status" class="select2 form-control">
                                        <option value="active">Active</option>
                                        <option value="non_active">Non Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-labeled  btn-danger btn-sm btn-sm btn-flat legitRipple"
                    data-dismiss="modal"><b><i class="fas fa-times"></i></b> Cancel</button>
                <button type="submit" form="form-search"
                    class="btn btn-labeled  btn-default  btn-sm btn-flat legitRipple"><b><i
                            class="fas fa-search"></i></b> Search</button>
            </div>
        </div>
    </div>
</div>
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
            order: [[ 1, "asc" ]],
            ajax: {
                url: "{{route('vehicle.read')}}",
                type: "GET",
                data:function(data){
                    var site_id = $('#form-search').find('#unit_id').val();
                    var police_number = $('#form-search').find('input[name=police_number]').val();
                    var vehicle_name = $('#form-search').find('input[name=vehicle_name]').val();
                    var status = $('#form-search').find('#status').val();
                    data.site_id = site_id;
                    data.police_number = police_number;
                    data.vehicle_name = vehicle_name;
                    data.status = status;
                }
            },
            columns: [
                {"data": "no", "name": "no", width: 10, className: "text-center" , orderable:false},
                {"data": "police_number", "name": "police_number", width: 80},
                {
                    width: 120,
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return `<a href="{{url('admin/vehicle')}}/${full.id}">
                                    <div class="text-md text-info text-bold">
                                        ${full.vehicle_name}
                                    </div>
                                </a>`;
                    }
                },
                {"data": "site_name", "name": "site_name", width: 50, orderable: false},
                {"data": "remarks", "name": "remarks", width: 120, orderable: false},
                {
                    width: 100,
                    className: "text-center",
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return `<a href="javascript:;" onclick="edit(${full.id})" class="btn btn-warning btn-sm text-white ">
                                    <span class="fas fa-pencil-alt" title="Edit"></span>
                                </a>
                                <a href="javascript:;" onclick="destroy(${full.id})" class="btn btn-danger btn-sm text-white ">
                                    <span class="fas fa-trash" title="Delete"></span>
                                </a>`;
                    }
                }
            ]
        });
		$('#form-search').submit(function(e) {
			e.preventDefault();
			dataTable.draw();
			$('#add-filter').modal('hide');
		});

        $( "#unit_id" ).select2({
			ajax: {
				url: "{{ route('site.select') }}",
				type:'GET',
				dataType: 'json',
				data: function (params) {
					return {
						name:params.term,
						page:params.page,
						limit:30,
					};
				},
				processResults: function (data,params) {
				 var more = (params.page * 30) < data.total;
				 var option = [];
				 $.each(data.rows,function(index,item){
					option.push({
						id:item.id,  
						text: item.name
					});
				 });
				  return {
					results: option, more: more,
				  };
				},
			},
			allowClear: true,
		});
	});

	function edit(id)
	{
		window.location.href = `{{url('admin/vehicle')}}/${id}/edit`;
	}
	function detail(id)
	{
		window.location.href = `{{url('admin/vehicle')}}/${id}`;
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
					url: `{{url('admin/user')}}/${id}`,
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