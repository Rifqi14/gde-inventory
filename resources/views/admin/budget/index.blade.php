@extends('admin.layouts.app')

@section('title')
Budgetary Data
@endsection

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Budgetary Data
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Preferences</li>
            <li class="breadcrumb-item">Budgetary Data</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div id="chartTemplate" class="row">

            <!-- DPR RI -->
            <div class="col-lg-12">
                <div class="card dashboard-item-overview">
                    <div class="card-header border-0">
                        <h3 class="card-title val"></h3>
                    </div>
                    <div class="card-body" style="padding-top: 0px;">
                        <div class="row">
                            <div class="position-relative mb-12 col-md-12" id="parent-pieChart1">
                                <div id="stackChart"
                                    style="min-height: 318px; height: 415px; max-height: 415px; max-width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- END DPR RI -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="text-right">
                            <a class="btn btn-labeled btn-sm text-sm btn-default btn-flat legitRipple  float-right ml-1"
                                onclick="filter()">
                                <b><i class="fas fa-search"></i></b> Search
                            </a>
                            <a href="{{ route('budgetary.create') }}"
                                class="btn btn-labeled btn-sm text-sm btn-success btn-flat legitRipple  float-right ml-1">
                                <b><i class="fas fa-plus"></i></b> Create
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="card document-engineering elevation-0">
                            <div class="card-header p-0" style="background-color: #f4f6f9; border: none;">
                                <ul class="nav nav-tabs tabs-engineering" id="tabs-mom" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="budget-tab-dieng" data-toggle="pill"
                                            href="#tab-dieng" role="tab" aria-controls="tab-dieng" aria-selected="true"
                                            onClick="changeTab('dieng2')">Dieng</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="budget-tab-patuha" data-toggle="pill" href="#tab-patuha"
                                            role="tab" aria-controls="tab-patuha"
                                            onClick="changeTab('patuha2')">Patuha</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="budget-tab-ho" data-toggle="pill" href="#tab-ho"
                                            role="tab" aria-controls="tab-ho" onClick="changeTab('ho')">Head Office</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <div class="tab-content" id="engineering-tab-tabContent">
                                <div class="tab-pane fade show active" id="tab-mechanical" role="tabpanel"
                                    aria-labelledby="engineering-tab-mechanical">
                                    <input name="type" type="hidden" value="dieng">
                                    <table class="table table-striped ajaxTable" style="width: 100%" id="user-table">
                                        <thead>
                                            <tr>
                                                <th width="5%">No.</th>
                                                <th width="15%">Budget Name</th>
                                                <th width="20%">Budget Description</th>
                                                <th width="15%" class="text-right">Budget Amount</th>
                                                <th width="15%">Type</th>
                                                <th width="10%" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="police_number">Shift Name</label>
                                    <input type="text" name="shift_name" class="form-control" placeholder="Shift Name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
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
        let unit = $('input[name=type]').val()
        // stackChart(unit)
        $('.select2').select2();
		// dataTable = $('#user-table').DataTable({
        //     processing: true,
        //     language: {
        //         processing: `<div class="p-2 text-center">
        //                         <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...
        //                     </div>`
        //     },
        //     serverSide: true,
        //     aaSorting: [],
        //     filter: false,
        //     responsive: true,
        //     lengthChange: false,
        //     order: [[ 2, "asc" ]],
        //     ajax: {
        //         url: "{{route('workingshift.read')}}",
        //         type: "GET",
        //         data:function(data){
        //             var shift_name = $('#form-search').find('input[name=shift_name]').val();
        //             var status = $('#form-search').find('#status').val();
        //             data.shift_name = shift_name;
        //             data.status = status;
        //         }
        //     },
        //     columns: [
        //         {"data": "no", "name": "no", width: 10, className: "text-center" , orderable:false},
        //         {
        //             width: 50,
        //             orderable: false,
        //             render: function(data, type, full, meta) {
        //                 if (full.shift_type == 'shift') {
        //                     return `Shift`;
        //                 } else {
        //                     return `Non Shift`;
        //                 }
        //             }
        //         },
        //         {
        //             "data": "shift_name", 
        //             "name": "shift_name",
        //             width: 150,
        //             render: function(data, type, full, meta) {
        //                 return `<a href="{{url('admin/workingshift')}}/${full.id}">
        //                             <div class="text-md text-info text-bold">
        //                                 ${full.shift_name}
        //                             </div>
        //                         </a>`;
        //             }
        //         },
        //         {
        //             width: 50,
        //             className: "text-center",
        //             orderable: false,
        //             render: function(data, type, full, meta) {
        //                 if (full.status == 'active') {
        //                     return `<span class="badge bg-success text-sm">Active</span>`;
        //                 } else {
        //                     return `<span class="badge bg-gray color-platte text-sm">Non Active</span>`;
        //                 }
        //             }
        //         },
        //         {
        //             width: 50,
        //             className: "text-center",
        //             orderable: false,
        //             render: function(data, type, full, meta) {
        //                 return `<div class="btn-group">
        //                             <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
        //                                 <i class="fas fa-bars"></i>
        //                             </button>
        //                             <div class="dropdown-menu">
        //                                 <a class="dropdown-item" href="javascript:void(0);" onclick="edit(${full.id})">
        //                                     <i class="far fa-edit"></i>Update Data
        //                                 </a>
        //                                 <a class="dropdown-item " href="javascript:void(0);" onclick="destroy(${full.id})">
        //                                     <i class="far fa-trash-alt"></i> Delete Data
        //                                 </a>
        //                             </div>
        //                         </div>`;
        //             }
        //         }
        //     ]
        // });
		$('#form-search').submit(function(e) {
			e.preventDefault();
			dataTable.draw();
			$('#add-filter').modal('hide');
		});

	});

    function changeTab(type){
        $('input[name="type"]').val(type);
        // stackChart(type)
        dataTable.draw();
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
					url: `{{url('admin/budgetary')}}/${id}`,
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