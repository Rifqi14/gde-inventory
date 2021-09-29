@extends('admin.layouts.app')
@section('title',$menu_name)

@section('button')
@if (in_array('read', $actionmenu))
<button type="button" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
    <b>
        <i class="fas fa-search"></i>
    </b> Search
</button>
@endif
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">{{$menu_name}}</h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{$parent_name}}</li>
            <li class="breadcrumb-item">{{$menu_name}}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="cotainer-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped" id="table-movement" width="100%">
                            <thead>
                                <th class="text-center">No</th>
                                <th>Date</th>
                                <th>Product</th>                                
                                <th>Description</th> 
                                <th class="text-center">Type</th>
                                <th class="text-right">Qty</th>
                                <th class="text-center">Action</th>
                            </thead>
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

    $(function () {
        movementTable = $('#table-movement').DataTable({
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
                [1, "desc"]
            ],
            ajax: {
                url: "{{route('stockmovement.read')}}",
                type: "GET",
                data: function(data) {                    
                }
            },
            columnDefs: [                
                {
                    orderable: false,
                    targets: [0, 6]
                },                
                {
                    className: "text-center",
                    targets: [0, 6]
                },
                {
                    width: "5%",
                    render: function(data, type, row) {
                        return row.no;
                    },
                    targets: [0]
                },              
                {
                    width: "12%",
                    render : function(data, type, row){
                        var date     = row.movement_date;
                        var issuedBy = row.issued_by;
                        return `${date}<br>${issuedBy}`;
                    },
                    targets : [1]
                },
                {
                    width : "40%",
                    render : function(data, type, row){
                        var product   = row.product;
                        var reference = row.reference?row.reference:'-';

                        return `<strong>${product}</strong><br><small>${reference}</small>`;
                    },
                    targets : [2]
                },
                {
                    render : function(data, type, row){
                        var param  = row.type;

                        switch (param) {
                            case 'in':
                                badge = 'badge-success';
                                break;
                            case 'out':
                                badge = 'badge-danger';
                                break;
                            case 'adjustment':
                                badge = 'badge-info';                                
                                break;
                            default:
                                badge = '';
                                break;
                        }

                        return `<span class="badge badge-sm text-sm ${badge}">${param}</span>`;
                    },
                    targets : [4]
                },
                {
                    width: "10%",
                    visible : false,
                    render: function(data, type, row) {                        
                        var button = '';                        

                        // read
                        if (actionmenu.indexOf('read') >= 0) {
                            button += `<a class="dropdown-item" href="javascript:void(0);" onclick="detail(${row.id})">
                                        <i class="far fa-eye"></i>View Data
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
                    targets: [6]
                }
            ],
            columns: [{
                    data: "no"
                },
                {
                    data: "movement_date"
                },
                {
                    data: "product"
                },
                {
                    data : "description"
                },
                {
                    data : "type",
                    className : 'text-center'
                },
                {
                    data: "qty",
                    className : "text-right"
                },
            ]
        });

        $('#form-search').submit(function(e) {
            e.preventDefault();
            movementTable.draw();
            $('#form-filter').modal('hide');
        }); 
    });

    const detail = (id) => {
        document.location = `{{route('stockmovement.index')}}/${id}`;
    }            
</script>
@endsection