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
<button type="button" id="add-goods_receipt" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('grievance.create') }}')">
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
                        <ul class="nav nav-tabs tabs-engineering" id="engineering-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ ($type=='app')?'active':'' }}" id="grievance-tab-app" data-toggle="pill" href="#tab-app" role="tab" aria-controls="tab-app" aria-selected="{{ ($type=='app')?'true':'false' }}">Application</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ ($type=='report')?'active':'' }}" id="grievance-tab-report" data-toggle="pill" href="#tab-report" role="tab" aria-controls="tab-report" aria-selected="{{ ($type=='report')?'true':'false' }}">Report</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <div class="tab-content" >
                            <div class="tab-pane fade {{ ($type=='app')?'show active':'' }}" id="tab-app" role="tabpanel" aria-labelledby="grievance-tab-app">
                                <table class="table table-striped datatable" id="table-grievance" width="100%">
                                    <thead>                  
                                        <tr>
                                            <!-- <th width="5%">No.</th> -->
                                            <th width="20%">Number</th>
                                            <th width="20%">Complainant</th>
                                            <th width="20%">Detailed Location</th>
                                            <th width="15%">Date & Time</th>
                                            <th width="15%">Author</th>
                                            <th width="15%">Unit</th>
                                            <th width="10%" class="text-center">Application Status</th>
                                            <th width="10%" class="text-center">Complaint Status</th>
                                            <th width="10%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="tab-pane fade {{ ($type=='report')?'show active':'' }}" id="tab-report" role="tabpanel" aria-labelledby="grievance-tab-report">
                                <table id="table-report" class="table table-striped" style="width: 100%">
                                    <thead>                  
                                        <tr>
                                        <!-- <th width="5%">No.</th> -->
                                        <th width="20%">Number</th>
                                        <th width="20%">Complainant</th>
                                        <th width="20%">Detailed Location</th>
                                        <th width="15%">Date & Time</th>
                                        <th width="15%">Author</th>
                                        <th width="15%">Unit</th>
                                        <th width="10%" class="text-center">Report Status</th>
                                        <th width="10%" class="text-center">Complaint Status</th>
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
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
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

    let userid = {{ $userid }};

    function edit(id) {
        document.location = '{{route('grievance.index')}}/' + id + '/edit';
    }

    function editreport(id) {
        document.location = '{{route('grievance.index')}}/report/' + id + '/edit';
    }

    $(function(){
        $('.select2').select2({
            allowClear: true
        });

        dataTable = $('.datatable').DataTable( {
            stateSave:true,
            processing: true,
            serverSide: true,
            filter:false,
            info:false,
            lengthChange:false,
            responsive: true,
            order: [[ 0, "asc" ]],
            ajax: {
                url: "{{route('grievance.read')}}",
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
                    width: 100,
                    sortable: false,
                    render: function (data, type, full, meta) {
                    return `<a href="{{ route('grievance.index') }}/${full.id}">
                                <div class="text-md text-info text-bold">
                                    ${full.number}
                                </div>
                            </a>`;
                    }
                },
                { "data": "complainant", "name": "complainant", width:150, sortable:false },
                { "data": "location", "name": "location", width:150, sortable:false },
                {
                    width: 100,
                    sortable: false,
                    render: function(data, type, full, meta) {
                        return full.date+' '+full.time
                    }
                },
                {
                    width: 100,
                    sortable: false,
                    render: function(data, type, full, meta) {
                        return `<b>${full.reporter}</b>`
                    }
                },
                {
                    width: 50,
                    sortable: false,
                    render: function(data, type, full, meta) {
                        return `${full.unit_name}`;
                    }
                },
                {
                    width: 100,
                    sortable: false,
                    className:"text-center", 
                    render: function(data, type, full, meta) {
                        if (full.status=='waiting') {
                            return `<span class="badge bg-warning text-sm">Waiting</span>`;
                        } else if(full.status=='revise') {
                            return `<span class="badge bg-maroon color-platte text-sm">Revise</span>`;
                        } else if(full.status=='approved') {
                            return `<span class="badge bg-success text-sm">Approved</span>`;
                        } else if(full.status=='declined') {
                            return `<span class="badge bg-red text-sm">Declined</span>`;
                        } else {
                            return `<span class="badge bg-gray text-sm">Draft</span>`;
                        }
                    }
                },
                {
                    width: 100,
                    sortable: false,
                    className:"text-center", 
                    render: function(data, type, full, meta) {
                        if (full.approval_status=='queue') {
                            return `<span class="badge bg-warning text-sm">Queue</span>`;
                        } else if(full.approval_status=='active') {
                            return `<span class="badge bg-success text-sm">Active</span>`;
                        } else if(full.approval_status=='declined') {
                            return `<span class="badge bg-red text-sm">Declined</span>`;
                        } else if(full.approval_status=='cleared') {
                            return `<span class="badge bg-info text-sm">Cleared</span>`;
                        } else {
                            return `<span class="badge bg-gray text-sm">Registered</span>`;
                        }
                    }
                },
                { 
                    width:30, 
                    className:"text-center", 
                    sortable: false,
                    render: function( data, type, full, meta ) {
                        if(userid == full.created_user){
                            return `<div class="btn-group">
                                <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item ${(full.status=='waiting' || full.status=='approved' || full.status=='reject') ? 'disabled' : ''}" href="javascript:void(0);" onclick="edit(${full.id})">
                                        <i class="far fa-edit"></i>Update Data
                                    </a>
                                    <a class="dropdown-item ${(full.status=='waiting' || full.status=='approved' || full.status=='reject') ? 'disabled' : ''}" href="javascript:void(0);" onclick="destroy(${full.id})" data-id="${full.id}">
                                        <i class="far fa-trash-alt"></i> Delete Data
                                    </a>
                                </div>
                            </div>`;
                        } else {
                            return `<div class="btn-group">
                                <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item disabled" href="javascript:void(0);" onclick="edit(${full.id})">
                                        <i class="far fa-edit"></i>Update Data
                                    </a>
                                    <a class="dropdown-item disabled" href="javascript:void(0);" onclick="destroy(${full.id})" data-id="${full.id}">
                                        <i class="far fa-trash-alt"></i> Delete Data
                                    </a>
                                </div>
                            </div>`;
                        }
                    }
                }
            ]
        });

        dataReport = $('#table-report').DataTable( {
            stateSave:true,
            processing: true,
            serverSide: true,
            filter:false,
            info:false,
            lengthChange:false,
            responsive: true,
            order: [[ 0, "asc" ]],
            ajax: {
                url: "{{route('grievance.report.read')}}",
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
                    width: 100,
                    sortable: false,
                    render: function (data, type, full, meta) {
                        return `<a href="{{ route('grievance.index') }}/report/${full.id}">
                                    <div class="text-md text-info text-bold">
                                        ${full.number}
                                    </div>
                                </a>`;
                    }
                },
                { "data": "complainant", "name": "complainant", width:150, sortable:false },
                { "data": "location", "name": "location", width:150, sortable:false },
                {
                    width: 100,
                    sortable: false,
                    render: function(data, type, full, meta) {
                        return full.date+' '+full.time
                    }
                },
                {
                    width: 100,
                    sortable: false,
                    render: function(data, type, full, meta) {
                        if(full.pic){  
                        return `<b>${full.pic}</b><br>
                                <div class="text-xs">
                                    Last Edit: <b>${full.updated_at}</b>
                                </div>`;
                        } else {
                           return '';
                        }
                    }
                },
                {
                    width: 50,
                    sortable: false,
                    render: function(data, type, full, meta) {
                        return `${full.unit_name}`;
                    }
                },
                {
                    width: 100,
                    sortable: false,
                    className:"text-center", 
                    render: function(data, type, full, meta) {
                        if (full.status=='waiting') {
                           return `<span class="badge bg-warning text-sm">Waiting</span>`;
                        } else if(full.status=='revise') {
                            return `<span class="badge bg-maroon color-platte text-sm">Revise</span>`;
                        } else if(full.status=='approved') {
                            return `<span class="badge bg-success text-sm">Approved</span>`;
                        } else {
                            return `<span class="badge bg-gray text-sm">Draft</span>`;
                        }
                    }
                },
                {
                    width: 100,
                    sortable: false,
                    className:"text-center", 
                    render: function(data, type, full, meta) {
                        if (full.approval_status=='queue') {
                           return `<span class="badge bg-warning text-sm">Queue</span>`;
                        } else if(full.approval_status=='active') {
                            return `<span class="badge bg-success text-sm">Active</span>`;
                        } else if(full.approval_status=='declined') {
                            return `<span class="badge bg-red text-sm">Declined</span>`;
                        } else if(full.approval_status=='cleared') {
                            return `<span class="badge bg-info text-sm">Cleared</span>`;
                        } else {
                            return `<span class="badge bg-gray text-sm">Registered</span>`;
                        }
                    }
                },
                { 
                    width:30, 
                    className:"text-center", 
                    sortable: false,
                    render: function( data, type, full, meta ) {
                        if(userid == full.updated_user){
                        return `<div class="btn-group">
                                    <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item ${(full.status=='waiting' || full.status=='approved') ? 'disabled' : ''}" href="javascript:void(0);" onclick="editreport(${full.id})">
                                        <i class="far fa-edit"></i>Update Data
                                        </a>
                                    </div>
                                </div>`;
                        } else {
                        return `<div class="btn-group">
                                    <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item ${(full.updated_user)?'disabled':''}" href="javascript:void(0);" onclick="editreport(${full.id})">
                                        <i class="far fa-edit"></i>Update Data
                                        </a>
                                    </div>
                                </div>`;
                        }
                        
                    }
                }
            ]
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
                message: 'Are you sure want to delete this data?',
                callback: function (result) {
                    if (result) {
                        var data = {
                            _token: "{{ csrf_token() }}",
                            id: id
                        };
                        $.ajax({
                            url: `{{route('grievance.index')}}`,
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
                            } else {
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
                url: '{{ route('grievance.index') }}/'+id,
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
                            document.location = '{{ route('grievance.index') }}';
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
</script>
@endsection