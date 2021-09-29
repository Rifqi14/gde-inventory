@extends('admin.layouts.app')
@section('title',$menu_name)

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

@section('button')
<button type="button" id="add" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('hseincident.create') }}')">
    <b><i class="fas fa-plus"></i></b>
    Create
</button>
<div class="mb-2"></div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-0" style="background-color: #f4f6f9; border: none;">

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped datatable" id="table-incident" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="5%">Number</th>
                                    <th width="20%">Subject</th>
                                    <th width="15%">Incident Type</th>
                                    <th width="20%">Detail</th>
                                    <th width="10%" class="text-center">Status</th>
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
    </div>
</section>
@endsection

@section('scripts')
<script>
$(function () {
    dataTable = $('.datatable').DataTable( {
        stateSave:true,
        processing: true,
        serverSide: true,
        filter:false,
        info:false,
        lengthChange:false,
        pageLength: 50,
        responsive: true,
        order: [[ 0, "asc" ]],
        ajax: {
            url: "{{route('hseincident.read')}}",
            type: "GET",
            data:function(data){
                // var name = $('input#filter-name').val();
                // var phone = $('input#filter-phone').val();
                // var email = $('input#filter-email').val();
                // data.name = name;
                // data.phone = phone;
                // data.email = email;
            }
        },
        columnDefs:[
            {
                orderable: false,targets:[0]
            },
            { className: "text-center", targets: [3,4,5] }
        ],
        columns: [
            {
                data: "id",
                width: '20%',
                sortable: false,
                className: "text-left",
                render: function (data, type, full, meta) {
                    return `<a href="{{ route('hseincident.index') }}/${full.id}">
                      <div class="text-md text-info text-bold">
                      ${full.number}
                      </div>
                    </a>`;
                }
            },
            { "data": "subject", "name": "subject", width: "30%", className: "text-left" },
            { "data": "type", "name": "type", width: "20%", className: "text-left" },
            {
                width: "20%",
                className: "text-left",
                render: function (data, type, full, meta) {
                    return `${full.date} ${full.time}<br><b>${full.loss_time} Hours<b>`;
                }
            },
            {
                width: "20%",
                className: "text-center",
                render: function (data, type, full, meta) {

                    if (full.status == 'waiting') {
                        return `<span class="badge bg-warning text-sm">Waiting</span>`;
                    } else if (full.status == 'revise') {
                        return `<span class="badge bg-maroon color-platte text-sm">Revise</span>`;
                    } else if (full.status == 'approved') {
                        return `<span class="badge bg-success text-sm">Approved</span>`;
                    } else if (full.status == 'declined') {
                        return `<span class="badge bg-red text-sm">Declined</span>`;
                    } else {
                        return `<span class="badge bg-gray text-sm">Draft</span>`;
                    }
                }
            },
            {
                width: "10%",
                className: "text-center",
                sortable: false,
                render: function (data, type, full, meta) {
                    return `<div class="btn-group">
                      <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-bars"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item ${(full.status == 'approved' || full.status == 'declined' || full.status == 'waiting' ) ? 'disabled' : ''}" href="javascript:void(0);" onclick="edit(${full.id})">
                          <i class="far fa-edit"></i>Update Data
                        </a>
                        <a class="dropdown-item ${(full.status == 'approved' || full.status == 'waiting' ) ? 'disabled' : ''}" href="javascript:void(0);" onclick="destroy(${full.id})">
                          <i class="far fa-trash-alt"></i> Delete Data
                        </a>
                      </div>
                    </div>`;
                }
            }
        ]
    });
});

function destroy(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Erased data cannot be reversed.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3d9970',
        confirmButtonText: "Yes, i am sure",
        cancelButtonColor: '#d81b60',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.value) {
            var data = {
                id: id,
                _token: "{{ csrf_token() }}"
            };
            $.ajax({
                url: '{{ route('hseincident.index') }}/'+id,
                dataType: 'json',
                data: data,
                type: 'DELETE',
                success: function (response) {
                    if (response.success) {
                        Swal.fire(
                            'Deleted!',
                            'Data Successfully Erased.',
                            'success'
                        )
                        setTimeout(() => {
                            document.location = '{{ route('hseincident.index') }}';
                        }, 1000);
                    }
                    else {
                        Swal.fire(
                            'Error!',
                            'Data Failed to Delete.',
                            'error'
                        )
                    }
                }
            });

        }
    });
}

function edit(id) {
    document.location = '{{ route('hseincident.index') }}/'+id+'/edit';
}
</script>
@endsection
