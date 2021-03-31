@extends('admin.layouts.app')

@section('title')
Registered User
@endsection

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <!-- <h5 class="m-0 ml-2 text-dark text-md breadcrumb">Grievance Redress &nbsp;<small class="font-uppercase"></small></h5> -->
        <h1 id="title-branch" class="m-0 text-dark">
            Registered User
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Preferences</li>
            <li class="breadcrumb-item">User</li>
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
                        <a href="{{ route('user.create') }}"
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
                                        <th>Group</th>
                                        <th>Username</th>
                                        <th>Full Name</th>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
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
                                    <label class="control-label" for="group">Group</label>
                                    <input type="text" id="group" name="group" class="form-control" placeholder="Group">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="username">Username</label>
                                    <input type="text" id="username" name="username" class="form-control"
                                        placeholder="Username">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="realname">Full Name</label>
                                    <input type="text" id="realname" name="realname" class="form-control"
                                        placeholder="Realname">
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
                url: "{{route('user.read')}}",
                type: "GET",
                data:function(data){
                    var group = $('#form-search').find('input[name=group]').val();
                    var username = $('#form-search').find('input[name=username]').val();
                    var realname = $('#form-search').find('input[name=realname]').val();
                    data.group = group;
                    data.username = username;
                    data.realname = realname;
                }
            },
            columns: [
                {"data": "no", "name": "no", width: 10, className: "text-center" , orderable:false},
                {"data": "group_description", "name": "group_description", width: 120, orderable: true},
                {
                    width: 120,
                    sortable: true,
                    render: function(data, type, full, meta) {
                        return `<a href="{{url('admin/user')}}/${full.id}/edit">
                                    <div class="text-md text-info text-bold">
                                        ${full.username}
                                    </div>
                                </a>`;
                    }
                },
                {
                    width: 120,
                    orderable: true,
                    render: function(data, type, full, meta) {
                        return `<a href="{{url('admin/user')}}/${full.id}/edit">
                                    <div class="text-md text-info text-bold">
                                        ${full.name}
                                    </div>
                                </a>`;
                    }
                },
                {
                    width: 100,
                    className: "text-center",
                    orderable: false,
                    render: function(data, type, full, meta) {
                        // let check2 = $.inArray(group_code, watcher)
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
	});

	function edit(id)
	{
		window.location.href = `{{url('admin/user')}}/${id}/edit`;
	}
	function detail(id)
	{
		window.location.href = `{{url('admin/user')}}/${id}`;
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
				// var data = {id : id};
				$.ajax({
					url: `{{url('admin/user')}}/${id}`,
					dataType: 'json', 
					data:data,
					type:'DELETE',
					success:function(response){
						if(response.status){
                            dataTable.ajax.reload(null, false);
							// Swal.fire(
							// 	'Deleted!',
							// 	'Data Berhasil Di Hapus.',
							// 	'success'
							// )
							// setTimeout(() => {
							// 	document.location = "{{url('admin/user')}}";
							// }, 1000);
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