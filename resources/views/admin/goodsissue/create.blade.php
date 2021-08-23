@extends('admin.layouts.app')
@section('title',$menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Create {{ $menu_name }}
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{ $parent_name }}</li>
            <li class="breadcrumb-item">{{ $menu_name }}</li>
            <li class="breadcrumb-item">Create</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <form id="form" action="{{route('goodsissue.store')}}" role="form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Main Information -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <h5 class="text-md text-dark text-uppercase">{{ $menu_name }} Information</h5>
                                <hr>
                            </span>
                            <div class="row">
                                <!-- Issued Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="issued-number" class="control-label">Issued Number</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" placeholder="Automatically Generated" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- Issued By -->
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="issued-by">Issued By</label>
                                        <div class="col-sm-12 controls">
                                            <input type="text" class="form-control" value="{{Auth::guard('admin')->user()->name}}" readonly>
                                            <input type="hidden" name="issuedby" value="{{Auth::guard('admin')->user()->id}}">
                                        </div>
                                    </div>
                                </div>
                                <!-- Date -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date" class="control-label">Date</label>
                                        <div class="controls">
                                            <div class="input-group">
                                                <input type="text" id="date" class="form-control datepicker text-right">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Product Category -->
                                <div class="col-md-6">
                                    <label for="product-category" class="control-label">Product Category</label>
                                    <div class="controls">
                                        <select id="product-category" class="form-control select2" data-placeholder="Choose product category"></select>
                                    </div>
                                </div>
                                <!-- Site -->
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="unit" class="col-md-12 col-xs-12 control-label">Site</label>
                                        <div class="col-sm-12 controls">
                                            <select name="unit" id="unit" class="form-control" data-placeholder="Choose site"></select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Warehouse -->
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="warehouse" class="col-md-12 col-xs-12 control-label">Warehouse</label>
                                        <div class="col-sm-12 controls">
                                            <select name="warehouse" id="warehouse" class="form-control" data-placeholder="Choose warehouse"></select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Status -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="state" class="control-label">Status</label>
                                        <div class="controls">
                                            <p><span class="badge bg-info text-sm">Approved</span> / <span class="badge bg-red text-sm">Rejected</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Other Information -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <h5 class="text-md text-dark text-uppercase">Other Information</h5>
                                <hr>
                            </span>                                                        
                            <!-- Description -->
                            <div class="form-group row">
                                <label for="description" class="col-md-12 col-xs-12 control-label">Description</label>
                                <div class="col-sm-12 controls">
                                    <textarea class="form-control summernote" name="description" id="description" rows="4" placeholder="Description"></textarea>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
                <!-- Reference Information -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <h5 class="text-md text-dark text-uppercase">Product Information</h5>
                                <hr>
                            </span>
                            <div class="form-group">
                                <button type="button" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple" onclick="addReference()">
                                    Add
                                </button>
                            </div>
                            <div class="form-group table-responsive">
                                <table id="table-product" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="100">Product</th>
                                            <th width="100">Category</th>
                                            <th width="30" class="text-center">Has Serial</th>
                                            <th width="30" class="text-right">Qty Request</th>
                                            <th width="30" class="text-right">Qty Receive</th>
                                            <th width="100">Rack</th>
                                            <th width="100">Bin</th>
                                            <th width="10" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="no-available-data">
                                            <td colspan="8" class="text-center">No available data.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Supporting Document -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <h5 class="text-md text-dark text-uppercase">Supporting Document</h5>
                                <hr>
                            </span>
                            <ul class="nav nav-tabs" id="suppDocumentTab" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active pl-4 pr-4" id="document-tab" data-toggle="tab" data-target="#document" role="tab" aria-controls="document" aria-selected="true"><b>Document</b></button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link pl-4 pr-4" id="photo-tab" data-toggle="tab" data-target="#photo" type="button" role="tab" aria-controls="photo" aria-selected="false"><b>Photo</b></button>
                                </li>
                            </ul>
                            <div class="tab-content" id="suppDocumentTabContent">
                                <div class="tab-pane fade show active" id="document" role="tabpanel" aria-labelledby="document-tab">
                                    <div class="form-group mt-3">
                                        <button type="button" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple" onclick="addDocument()">
                                            Add
                                        </button>
                                    </div>
                                    <div class="form-group">
                                        <table id="table-document" class="table table-striped" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="45%">Document Name</th>
                                                    <th width="45%">File</th>
                                                    <th width="10%" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="no-available-data">
                                                    <td colspan="3" class="text-center">No available data.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade show" id="photo" role="tabpanel" aria-labelledby="photo-tab">
                                    <div class="form-group mt-3">
                                        <button type="button" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple" onclick="addPhoto()">
                                            Add
                                        </button>
                                    </div>
                                    <div class="form-group">
                                        <table id="table-photo" class="table table-striped datatable" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="45%">Photo Name</th>
                                                    <th width="45%">File</th>
                                                    <th width="10%" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="no-available-data">
                                                    <td colspan="3" class="text-center">No available data.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <input type="hidden" name="status" value="draft">
                            <button type="button" onclick="onSubmit('rejected')" class="btn bg-danger btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-times"></i></b>
                                Reject
                            </button>
                            <button type="button" onclick="onSubmit('approved')" class="btn bg-success color-palette btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-check-circle"></i></b>
                                Approve
                            </button>
                            <a href="{{ route('goodsissue.index') }}" class="btn btn-secondary color-palette btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-times"></i></b>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Modal Reference -->
<div class="modal fade" id="form-reference">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="text-transform: capitalize;">Add reference</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="reference-tab" role="tablist">
                            <li class="nav-item consumable-reference">
                                <a href="#add-consumable-reference" class="nav-link active" id="add-consumable-reference-tab" data-toggle="pill" role="tab" aria-controls="add-consumable-reference" aria-selected="false">Consumable</a>
                            </li>
                            <li class="nav-item transfer-reference">
                                <a href="#add-transfer-reference" class="nav-link" id="add-transfer-reference-tab" data-toggle="pill" role="tab" aria-controls="add-transfer-reference" aria-selected="false">Product Transfer</a>
                            </li>
                            <li class="nav-item borrowing-reference">
                                <a href="#add-borrowing-reference" class="nav-link" id="add-borrowing-reference-tab" data-toggle="pill" role="tab" aria-controls="add-borrowing-reference" aria-selected="false">Product Borrowing</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="reference-tab">
                            <div class="tab-pane fade show active table-responsive" id="add-consumable-reference" role="tabpanel" aria-labelledby="add-consumable-reference-tab">
                                <table id="table-consumable" class="table table-striped datatable" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="10%" class="text-center">Date</th>
                                            <th width="35%">Product</th>
                                            <th width="35%">Category</th>
                                            <th width="10%" class="text-center">Has Serial</th>
                                            <th width="10%" class="text-center">UOM</th>
                                            <th width="10%" class="text-right">Qty</th>                                            
                                            <th width="8%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="tab-pane fade show table-responsive" id="add-transfer-reference" role="tabpanel" aria-labelledby="add-transfer-reference-tab">
                                <table id="table-transfer" class="table table-striped datatable" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="10%" class="text-center">Date</th>
                                            <th width="35%">Product</th>
                                            <th width="35%">Category</th>
                                            <th width="10%" class="text-center">Has Serial</th>
                                            <th width="10%" class="text-center">UOM</th>
                                            <th width="10%" class="text-right">Qty</th>                                            
                                            <th width="8%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="tab-pane fade show table-responsive" id="add-borrowing-reference" role="tabpanel" aria-labelledby="add-borrowing-reference-tab">
                                <table id="table-borrowing" class="table table-striped datatable" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="10%" class="text-center">Date</th>                                            
                                            <th width="35%">Product</th>
                                            <th width="35%">Category</th>
                                            <th width="10%" class="text-center">Has Serial</th>
                                            <th width="10%" class="text-center">UOM</th>
                                            <th width="10%" class="text-right">Qty</th>                                            
                                            <th width="8%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-labeled  btn-danger btn-sm btn-sm btn-flat legitRipple" data-dismiss="modal"><b><i class="fas fa-times"></i></b> Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Serial -->
<div class="modal fade" id="modal-serial">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="text-transform: capitalize;">Select Product Serial</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="serial-tab" role="tablist">
              <li class="nav-item">
                <a href="#choose-serial" class="nav-link active" id="choose-serial-tab" data-toggle="pill" role="tab" aria-controls="choose-serial" aria-selected="false">Unselected</a>
              </li>
              <li class="nav-item">
                <a href="#remove-serial" class="nav-link" id="remove-serial-tab" data-toggle="pill" role="tab" aria-controls="remove-serial" aria-selected="false">Selected</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="serial-tab">
              <div class="tab-pane fade show active table-responsive p-0" id="choose-serial" role="tabpanel" aria-labelledby="choose-serial-tab">
                <table id="table-select-serial" class="table table-striped datatable" width="100%" data-product-id="" >
                    <thead>
                        <tr>                            
                            <th width="45%">Product</th>
                            <th width="45%">Serial Number</th>
                            <th width="10%" class="text-center">Action</th>
                        </tr>
                    </thead>
                </table>
              </div>
              <div class="tab-pane fade show table-responsive" id="remove-serial" role="tabpanel" aria-labelledby="remove-serial-tab">
                <table id="table-selected-serial" class="table table-striped datatable" width="100%" data-product-id="">
                    <thead>
                        <tr>                            
                            <th width="45%">Product</th>
                            <th width="45%">Serial Number</th>
                            <th width="10%" class="text-center">Action</th>
                        </tr>
                    </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-labeled  btn-danger btn-sm btn-sm btn-flat legitRipple" data-dismiss="modal"><b><i class="fas fa-times"></i></b> Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    var productRole    = '';
    var issuedProduct  = [];
    var selectedSerial = [];
    var dataProducts   = [];
    var dataSet        = [];

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
        $('.summernote').summernote({
            height: 145,
            toolbar: [
                ['style', ['style']],
                ['font-style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['font', ['fontname']],
                ['font-size', ['fontsize']],
                ['font-color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['misc', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            timePicker: false,
            timePickerIncrement: 30,
            drops: 'auto',
            opens: 'center',
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        $("#unit").select2({
            ajax: {
                url: "{{ route('site.select') }}",
                type: "GET",
                dataType: "JSON",
                data: function(params) {
                    return {
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:close', function(e) {
            var data = $(this).find('option:selected').val();
            var warehouse = $('#warehouse').select2('data');

            if (warehouse[0] && warehouse[0].site_id != data) {
                $('#warehouse').val(null).trigger('change');
            }
        }).on('select2:clearing', function() {
            $('#warehouse').val(null).trigger('change');
        });

        $("#warehouse").select2({
            ajax: {
                url: "{{ route('warehouse.select') }}",
                type: "GET",
                dataType: "JSON",
                data: function(params) {
                    var siteID = $('#unit').find('option:selected').val();
                    return {
                        site_id: siteID ? siteID : '',
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name,
                            site_id: item.site_id,
                            site: item.site
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if (data.site_id) {
                $('#unit').select2('trigger', 'select', {
                    data: {
                        id: data.site_id,
                        text: `${data.site}`
                    }
                });
            }
        });

        $("#product-category").select2({
            ajax: {
                url: "{{ route('productcategory.select') }}",
                type: "GET",
                dataType: "JSON",
                data: function(params) {
                    return {
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name,
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
            escapeMarkup: function (text) { return text; }
        }).on('change', function(){
            $(this).focus();
        });

        consumableTable = $('#table-consumable').DataTable({
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
                url: "{{route('goodsissue.consumableproducts')}}",
                type: "GET",
                data: function(data) {
                    data.except = issuedProduct;
                    data.category_id = $('#form').find('#product-category').find('option:selected').val();
                }
            },
            columnDefs: [{
                    orderable: false,
                    targets: [0, 7]
                },
                {
                    className: "text-center",
                    targets: [0, 1, 4 , 5, 7]
                },
                {
                    className: "text-right",
                    targets: [6]
                },
                {
                    render: function(data, type, row){
                            switch (row.is_serial) {
                            case '1':
                                badge = 'badge-info';
                                icon  = 'fas fa-check';                    
                                break;
                            
                            default:
                                badge = 'bg-red';
                                icon  = 'fas fa-times';                    
                                break;
                            }
                        return `<span class="badge ${badge} text-md"><i class="${icon}" style="size: 2x;"></i></span>`;
                    }, targets: [4]
                },
                {
                    render: function(data, type, row) {
                        return `<p>${row.product}<br><b>${row.consumable_number}</b></p>`;
                    },
                    targets: [2]
                },
                {
                    render: function(data, type, row) {
                        var referenceID = row.product_consumable_id,
                            reference   = row.consumable_number,
                            productID   = row.product_id,
                            product     = row.product,
                            category    = row.category,
                            uomID       = row.uom_id,
                            isSerial    = row.is_serial,
                            qty         = row.qty ? row.qty : 0;

                        return `<button class="btn btn-sm text-xs btn-success btn-flat legitRipple" onclick="addProduct($(this),'consumable')" type="button" data-reference-id="${referenceID}" data-reference="${reference}" data-product-id="${productID}" data-product="${product}" data-uom-id="${uomID}" data-qty="${qty}" data-has-serial="${isSerial}">
                                    <i class="fas fa-plus"></i>
                                </button>`;
                    },
                    targets: [7]
                }
            ],
            columns: [{
                    data: "no"
                },
                {
                    data: "date_consumable"
                },                
                {
                    data: "product",
                    className: 'product-item'
                },
                {
                    data: "category",
                    className: 'category'
                },
                {
                    data: "is_serial"
                },
                {
                    data: "uom"
                },
                {
                    data: "qty"
                }
            ]
        });

        transferTable = $('#table-transfer').DataTable({
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
                url: "{{route('goodsissue.transferproducts')}}",
                type: "GET",
                data: function(data) {
                    data.except = issuedProduct;
                    data.category_id = $('#form').find('#product-category').find('option:selected').val();
                }
            },
            columnDefs: [{
                    orderable: false,
                    targets: [0, 7]
                },
                {
                    className: "text-center",
                    targets: [0, 1, 4 , 5, 7]
                },
                {
                    className: "text-right",
                    targets: [6]
                },
                {
                    render: function(data, type, row) {
                        return `<p>${row.product}<br><b>${row.transfer_number}</b></p>`;
                    },
                    targets: [2]
                },
                {
                    render: function(data, type, row){
                            switch (row.is_serial) {
                            case '1':
                                badge = 'badge-info';
                                icon  = 'fas fa-check';                    
                                break;
                            
                            default:
                                badge = 'bg-red';
                                icon  = 'fas fa-times';                    
                                break;
                            }
                        return `<span class="badge ${badge} text-md"><i class="${icon}" style="size: 2x;"></i></span>`;
                    }, targets: [4]
                },
                {
                    render: function(data, type, row) {
                        var referenceID = row.product_transfer_id,
                            reference   = row.transfer_number,
                            productID   = row.product_id,
                            product     = row.product,
                            category    = row.category,
                            isSerial    = row.is_serial,
                            uomID       = row.uom_id,
                            qty         = row.qty ? row.qty : 0;

                        return `<button class="btn btn-sm text-xs btn-success btn-flat legitRipple" onclick="addProduct($(this),'transfer')" type="button" data-reference-id="${referenceID}" data-reference="${reference}" data-product-id="${productID}" data-product="${product}" data-uom-id="${uomID}" data-qty="${qty}" dat-has-serial="${isSerial}">
                                    <i class="fas fa-plus"></i>
                                </button>`;
                    },
                    targets: [7]
                }
            ],
            columns: [{
                    data: "no"
                },
                {
                    data: "transfer_date"
                },                
                {
                    data: "product",
                    className: 'product-item'
                },
                {
                    data: "category",
                    className: 'category'
                },
                {
                    data: "is_serial"
                },                
                {
                    data: "uom"
                },
                {
                    data: "qty"
                }
            ]
        });

        borrowingTable = $('#table-borrowing').DataTable({
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
                url: "{{route('goodsissue.borrowingproducts')}}",
                type: "GET",
                data: function(data) {
                    data.warehouse_id = $('#warehouse').find('option:selected').val();
                    data.except       = issuedProduct;
                    data.category_id  = $('#form').find('#product-category').find('option:selected').val();
                }
            },
            columnDefs: [{
                    orderable: false,
                    targets: [0, 7]
                },
                {
                    className: "text-center",
                    targets: [0, 1, 4, 5, 7]
                },
                {
                    className: "text-right",
                    targets: [6]
                },
                {
                    render: function(data, type, row) {
                        return `<p>${row.product}<br><b>${row.borrowing_number}</b></p>`;
                    },
                    targets: [2]
                },
                {
                    render: function(data, type, row){
                            switch (row.is_serial) {
                            case '1':
                                badge = 'badge-info';
                                icon  = 'fas fa-check';                    
                                break;
                            
                            default:
                                badge = 'bg-red';
                                icon  = 'fas fa-times';                    
                                break;
                            }
                        return `<span class="badge ${badge} text-md"><i class="${icon}" style="size: 2x;"></i></span>`;
                    }, targets: [4]
                },
                {
                    render: function(data, type, row) {
                        var referenceID = row.product_borrowing_id,
                            reference   = row.borrowing_number,
                            productID   = row.product_id,
                            product     = row.product,
                            category    = row.category,
                            isSerial    = row.is_serial,
                            uomID       = row.uom_id,
                            qty         = row.qty_requested ? row.qty_requested : 0;

                        return `<button class="btn btn-sm text-xs btn-success btn-flat legitRipple" onclick="addProduct($(this),'borrowing')" type="button" data-reference-id="${referenceID}" data-reference="${reference}" data-product-id="${productID}" data-product="${product}" data-uom-id="${uomID}" data-qty="${qty}" data-has-serial="${isSerial}">
                                    <i class="fas fa-plus"></i>
                                </button>`;
                    },
                    targets: [7]
                }
            ],
            columns: [{
                    data: "no"
                },
                {
                    data: "date_borrowing"
                },                
                {
                    data: "product",
                    className: 'product-item'
                },
                {
                    data: "category",
                    className: 'category'
                },
                {
                    data: "is_serial"
                },
                {
                    data: "uom"
                },
                {
                    data: "qty_requested"
                }
            ]
        });

        tableSelectSerial = $('#table-select-serial').DataTable({            
            language: {
                loadingRecords: `<div class="p-2 text-center">
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
                url: "{{route('goodsissue.readserial')}}",
                type: "GET",
                data: function(data) {             
                    var productID  =  $('#table-select-serial').attr('data-product-id');                                             
                    var except     = [];                
                    var product    = dataProducts.filter(param => param.product_id == productID);

                    if(product.length > 0){
                        $.each(product[0].serials, function (index, value) { 
                            except.push(value.serial_id);
                        });
                    }                                    

                    data.except     = except;
                    data.product_id = productID;            
                }
            },
            columnDefs: [{
                orderable: false,
                    targets: [2]
                },
                {
                    className: "text-center",
                    targets: [2]
                },
                {
                    render: function(data, type, row){
                        return `<b>${row.product}</b><p style="margin-top: 1px;">${row.category}</p>`;
                    }, 
                    targets: [0]
                },                                
                {
                    render: function(data, type, row) {
                        return `<b>${row.serial_number}</b>`;
                    },
                    targets: [1]
                },                                
                {
                render: function(data, type, row) {     
                        var bar = $('#table-product > tbody').find(`input[class=item-product][value=${row.product_id}]`).parents('tr');                        
                        var qtyOrder   = bar.find('td.qty-order').text();
                        var qtyReceive = bar.find('.qty-receive').val(); 

                        if(parseInt(qtyReceive) >= parseInt(qtyOrder)){
                            disabled = 'disabled';
                        }else{
                            disabled = '';
                        }
                        
                        return `<button class="btn btn-sm text-xs btn-success btn-flat legitRipple ${disabled}" onclick="selectSerial($(this))" data-product-id="${row.product_id}" data-serial-id="${row.id}" data-serial-number="${row.serial_number}" type="button">
                                    <i class="fas fa-plus"></i>
                                </button>`;
                    },
                    targets: [2]
                }
            ],
            columns: [                
                {
                    data: "product",
                    className : 'product'
                },
                {
                    data: "serial_number"
                }
            ]
        });

        tableSelectedSerial = $('#table-selected-serial').DataTable({
            processing: true,
            language: {
                processing: `<div class="p-2 text-center">
                            <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...
                            </div>`
            },                 
            destroy : true,       
            filter: false,
            responsive: true,
            lengthChange: false,
            order: [
                [0, "asc"]
            ],
            data: dataSet,
            columnDefs: [{
                orderable: false,
                    targets: [2]
                },
                {
                    className: "text-center",                                      
                    targets: [2]
                },                
                {
                    render : function(data, type, row){
                        var product  = data;
                        var category = row[4];                                          

                        return `<b>${product}</b><p style="margin-top: 1px;">${category}</p>`;
                    }, targets: [0]
                },
                {                    
                    render: function(data, type, row) {                                                
                        return `<b>${row[1]}</b>`;
                    },                                        
                    targets: [1]
                },                                
                {                    
                    render: function(data, type, row) {                                                  
                            var productID = row[3],
                                serialID  = row[2];

                        return `<button class="btn btn-sm text-xs btn-danger btn-flat legitRipple" onclick="removeSerial($(this))" data-product-id="${productID}" data-serial-id="${serialID}" type="button">
                                    <i class="fas fa-trash"></i>
                                </button>`;
                    },                                        
                    targets: [2]
                }
            ],
            columns: [
                {
                    title : "Product",
                    width : "45%"
                },
                { 
                    title: "Serial Number",
                    width : "45%"
                },
                { 
                    title: "Action",
                    width : "10%"
                }
            ]
        });

        $("#form").validate({
            rules: {
                receipt_date: {
                    required: true
                },
                unit: {
                    required: true
                },
                warehouse: {
                    required: true
                },
                rack: {
                    required: true
                },
                bin: {
                    required: true
                }
            },
            messages: {
                receipt_date: {
                    required: 'This field is required.'
                },
                unit: {
                    required: 'This field is required.'
                },
                warehouse: {
                    required: 'This field is required.'
                },
                rack: {
                    required: 'This field is required.'
                },
                bin: {
                    required: 'This field is required.'
                }
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group .controls').append(error);

                if (element.is(':file')) {
                    // error.insertAfter(element.parent().parent().parent());
                    error.insertAfter(element.parent());
                } else
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else
                if (element.attr('type') == 'checkbox') {
                    error.insertAfter(element.parent());
                } else
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.closest('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function() {
                var data        = new FormData($('#form')[0]),
                    warehouseID = $('#warehouse').find('option:selected').val();
                    issuedDate  = $('#form').find('#date').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                    products    = [],
                    zeroValue   = false;

                $.each($('#table-product > tbody > .product-item'), function(index, value) {
                    var product     = $(this).find('.item-product'),
                        product_id  = product.val(),
                        referenceID = product.attr('data-reference-id'),
                        uomID       = product.attr('data-uom-id'),
                        qtyRequest  = product.attr('data-qty-request'),
                        qtyReceive  = $(this).find('.qty-receive').val(),                        
                        rackID      = product.parents('.product-item').find('.rack-warehouse > option:selected').val(),
                        binID       = product.parents('.product-item').find('.bin-warehouse > option:selected').val(),
                        type        = product.attr('data-type'),
                        hasSerial   = product.attr('data-has-serial')=='1'?true:false,
                        serials     = [];

                    if(hasSerial){
                        serials = dataProducts.filter(param => param.product_id == product_id)[0].serials;
                    }

                    products.push({
                        warehouse_id : warehouseID,
                        product_id   : product_id,
                        reference_id : referenceID,
                        uom_id       : uomID,
                        qty_request  : qtyRequest,
                        qty_receive  : qtyReceive ? qtyReceive : 0,                        
                        rack_id      : rackID,
                        bin_id       : binID,
                        type         : type,
                        has_serial   : hasSerial,
                        serials      : serials
                    });

                });      

                // console.log({products : products});
                // return false;

                if (products.length == 0) {
                    toastr.warning('Select the product first. at least one product');
                    return false;
                } else if (zeroValue) {
                    toastr.warning("Minimum value of qty receive is 1.");
                    return false
                }      
                            

                data.append('products', JSON.stringify(products));
                data.append('issueddate', issuedDate);

                $.ajax({
                    url: $('#form').attr('action'),
                    method: 'post',
                    data: data,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function() {
                        blockMessage('body', 'Please Wait . . . ', '#fff');
                    }
                }).done(function(response) {
                    $('body').unblock();
                    if (response.status) {
                        toastr.success('Data has been saved.');
                        document.location = "{{route('goodsissue.index')}}";
                    } else {
                        toastr.warning(`${response.message}`);
                    }
                    return;
                }).fail(function(response) {
                    $('body').unblock();
                    var response = response.responseJSON,
                        message = response.message ? response.message : 'Failed to insert data.';

                    toastr.warning(message);
                    console.log({
                        errorMessage: message
                    });
                })
            }
        });
    });        

    const initRack = () => {
        $(".rack-warehouse").select2({
            ajax: {
                url: "{{route('warehouse.selectrack')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var warehouseID = $('#form').find('#warehouse > option:selected').val();
                    return {
                        name: params.term,
                        page: params.page,
                        warehouse_id: warehouseID ? warehouseID : null,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id           : item.id,
                            text         : item.name,
                            site_id      : item.site_id,
                            site         : item.site,
                            warehouse_id : item.warehouse_id,
                            warehouse    : item.warehouse                            
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if(data.site_id){
                $('#form').find('#unit').select2('trigger','select', {
                    data : {
                        id   : data.site_id,
                        text : `${data.site}`
                    }
                });
            }
            if (data.warehouse_id) {
                $('#form').find('#warehouse').select2('trigger', 'select', {
                    data: {
                        id   : data.warehouse_id,
                        text : `${data.warehouse}`
                    }
                });                
            }            
            $(this).focus();
        }).on('select2:clear', function() {
            $(this).parents('.product-item').find('.bin-warehouse').val(null).trigger('change');
        }).on('change', function(){            
        });
    }

    const initBin = () => {
        $(".bin-warehouse").select2({
            ajax: {
                url: "{{route('warehouse.selectbin')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var warehouseID = $('#warehouse').find('option:selected').val();
                    var rackID = $(this).parents('.product-item').find('.rack-warehouse > option:selected').val();

                    return {
                        name         : params.term,
                        warehouse_id : warehouseID?warehouseID:null,
                        rack_id      : rackID ? rackID : null,                        
                        page         : params.page,
                        limit        : 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id           : item.id,
                            text         : item.name,
                            rack_id      : item.rack_id,
                            rack         : item.rack,
                            site_id      : item.site_id,
                            site         : item.site,
                            warehouse_id : item.warehouse_id,
                            warehouse    : item.warehouse
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:select', function(e) {            
            var data      = e.params.data;                    
        
            if(data.site_id){
                $('#form').find('#unit').select2('trigger','select',{
                    data : {
                        id   : data.id,
                        text : `${data.site}`
                    }
                });
            }
            if (data.warehouse_id) {
                $('#form').find('#warehouse').select2('trigger', 'select', {
                    data: {
                        id: data.warehouse_id,
                        text: `${data.warehouse}`
                    }
                });
            }
            if (data.rack_id) {
                var rack = $(this).parents('.product-item').find('.rack-warehouse');
                rack.select2('trigger', 'select', {
                    data: {
                        id: data.rack_id,
                        text: `${data.rack}`
                    }
                });                
            }

            var productID = $(this).parents('.product-item').find('.item-product').val();
            var product   = dataProducts.filter(param => param.product_id == productID)[0];
        }).on('change', function(e){            
            $(this).focus();            
        });
    }

    const initInputFile = () => {
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    }

    const addReference = () => {
        if (productRole == 'consumable') {
            consumableTable.draw();
            $('li.transfer-reference').hide();
            $('li.borrowing-reference').hide();
        } else if (productRole == 'transfer') {
            transferTable.draw();
            $('li.consumable-reference').hide();
            $('li.borrowing-reference').hide();
        } else if(productRole == 'borrowing'){
            borrowingTable.draw();
            $('li.consumable-reference').hide();
            $('li.transfer-reference').hide();
        } else {
            consumableTable.draw();
            transferTable.draw();
            borrowingTable.draw();
            $('li.consumable-reference').show();
            $('li.transfer-reference').show();
            $('li.borrowing-reference').show();
        }

        console.log({role : productRole});

        $('#form-reference').modal('show');
    }

    const addProduct = (that, type) => {
        var referenceID = that.attr('data-reference-id'),
            reference   = that.attr('data-reference'),
            productID   = that.attr('data-product-id'),
            product     = that.attr('data-product'),
            category    = that.parents('tr').find('td.category').html(),
            uomID       = that.attr('data-uom-id'),
            qty         = that.attr('data-qty'),            
            isSerial    = that.attr('data-has-serial'),
            table       = $('#table-product > tbody');

        switch (isSerial) {
            case '1':
                icon     = 'fas fa-check';
                badge    = 'badge-info';            
                break;
        
            default:
                icon     = 'fas fa-times';
                badge    = 'bg-red';            
                break;
        }

        var serial = `<span class="badge ${badge} text-md"><i class="${icon}" style="size: 1x;"></i></span>`;

        var html = `<tr class="product-item">
                        <input type="hidden" class="item-product" value="${productID}" data-reference-id="${referenceID}" data-uom-id="${uomID}" data-qty-request="${qty}" data-type="${type}" data-has-serial="${isSerial}">                        
                        <td width="100"><p>${product}<br><b style="font-size: 10pt;">${reference}</b></p></td>
                        <td width="100">${category}</td>
                        <td width="30" class="text-center">${serial}</td>
                        <td class="text-right qty-order" width="30">${qty}</td>
                        <td class="text-right" width="30">
                            <input type="number" class="form-control numberfield text-right qty-receive" placeholder="0" min="1" max="${qty}" ${type=='borrowing' && isSerial=='1'?'readonly':''}>
                        </td>
                        <td width="100">
                            <div class="form-group">
                            <div class="controls">
                                <select name="rack" class="form-control rack-warehouse" data-placeholder="Choose rack" style="width: 100%;" required></select>
                            </div>
                            </div>                    
                        </td>
                        <td width="100">
                        <div class="form-group">
                            <div class="controls">
                                <select name="bin" class="form-control bin-warehouse" data-placeholder="Choose bin" style="width: 100%;" required></select>
                            </div>
                            </div>                    
                        </td>
                        <td class="text-center" width="10">
                            <button class="btn btn-sm text-xs btn-warning btn-flat legitRipple ${type=='borrowing'&&isSerial=='1'?'':'disabled'}" type="button" onclick="showSerial($(this),${productID})"><i class="fas fa-bars"></i></button>
                            <button class="btn btn-sm text-xs btn-danger btn-flat legitRipple" type="button" onclick="removeProduct($(this),${productID})"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;

        table.find('.no-available-data').remove();
        table.append(html);

        issuedProduct.push(parseInt(productID));

        var obj = dataProducts.filter(param => param.product_id == productID);        

        if(obj.length == 0){
            dataProducts.push({
                product_id   : productID,
                product      : product,
                category     : category,
                rack_id      : null,
                bin_id       : null,                
                reference_id : referenceID,
                reference    : reference,
                max_qty      : qty,                
                has_serial   : isSerial=='1'?true:false,
                serials      : isSerial=='1'?[]:null,
            });   
        }            

        initBin();
        initRack();

        productRole = type;

        if (productRole == 'consumable') {
            $('li.transfer-reference').hide();
            $('li.borrowing-reference').hide();
            consumableTable.ajax.reload(null, false);
        } else if (productRole == 'transfer') {
            $('li.consumable-reference').hide();
            $('li.borrowing-reference').hide();
            transferTable.ajax.reload(null, false);
        }else if(productRole == 'borrowing'){
            $('li.consumable-reference').hide();
            $('li.transfer-reference').hide();
            borrowingTable.ajax.reload(null, false);
        }        
    }

    const removeProduct = (that, productID) => {
        that.closest('.product-item').remove();
        if ($('#table-product > tbody > .product-item').length == 0) {
            var html = `<tr class="no-available-data">
                            <td colspan="8" class="text-center">No available data.</td>
                        </tr>`;
            $('#table-product > tbody').append(html);

            productRole = '';
        }
        issuedProduct.splice($.inArray(productID, issuedProduct), 1);
        dataProducts = dataProducts.filter(param => param.product_id != productID);
    }

    const addDocument = () => {
        var noData = $('#table-document > tbody').find('.no-available-data');

        if (noData.length == 1) {
            noData.remove();
        }

        var html = `<tr class="document-item">
                  <td>
                      <input type="text" class="form-control document-name" name="document_name[]" placeholder="Enter document name" required>
                  </td>
                  <td>
                      <div class="input-group">
                          <div class="custom-file">   
                              <input type="file" class="custom-file-input" name="attachment[]" required>
                              <label class="custom-file-label" for="exampleInputFile">Attach a file</label>
                          </div>                                
                      </div>
                  </td>
                  <td class="text-center">
                      <button class="btn btn-md text-xs btn-danger btn-flat legitRipple" type="button" onclick="removeDocument($(this))"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>`;
        $('#table-document > tbody').append(html);
        initInputFile();
    }

    const removeDocument = (that) => {
        that.closest('.document-item').remove();
        if ($('#table-document > tbody > .document-item').length == 0) {
            var html = `<tr class="no-available-data">
                                <td colspan="3" class="text-center">No available data.</td>
                            </tr>`;
            $('#table-document > tbody').append(html);
        }
    }

    const addPhoto = () => {
        var noData = $('#table-photo > tbody').find('.no-available-data');

        if (noData.length == 1) {
            noData.remove();
        }

        var html = `<tr class="photo-item">
                    <td>
                        <input type="text" class="form-control document-name" name="photo_name[]" placeholder="Enter photo name" required>
                    </td>
                    <td>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="photo[]" required>
                                <label class="custom-file-label" for="exampleInputFile">Attach a file</label>
                            </div>                                
                        </div>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-md text-xs btn-danger btn-flat legitRipple" type="button" onclick="removePhoto($(this))"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
        $('#table-photo > tbody').append(html);
        initInputFile();
    }

    const removePhoto = (that) => {
        that.closest('.photo-item').remove();
        if ($('#table-photo > tbody > .photo-item').length == 0) {
            var html = `<tr class="no-available-data">
                                <td colspan="3" class="text-center">No available data.</td>
                            </tr>`;
            $('#table-photo > tbody').append(html);
        }
    }

    const showSerial = (that, productID) => {
        $('#table-select-serial').attr('data-product-id',productID);
        $('#table-selected-serial').attr('data-product-id',productID);
        tableSelectSerial.draw();
        drawSelectedSerial(productID);
        $('#modal-serial').modal('show');
    }

    const drawSelectedSerial  = (productID) => {
        dataSet = [];

        var data      = dataProducts.filter(param => param.product_id == productID);
        var productID = data[0].product_id,
            product   = data[0].product,
            category  = data[0].category,
            reference = data[0].reference,
            serials   = data[0].serials;        
        
        $.each(serials, function (index, value) { 
           dataSet.push([product,value.serial_number,value.serial_id,productID,category,reference]);
        });

        tableSelectedSerial.clear().draw();
        tableSelectedSerial.rows.add(dataSet); // Add new data
        tableSelectedSerial.draw(); // Redraw the DataTable        
    }

    const selectSerial = (that) => {
        var serialID     = that.attr('data-serial-id'),
            serialNumber = that.attr('data-serial-number'),
            productID    = that.attr('data-product-id');
        
        var product = dataProducts.filter(param => param.product_id == productID);
        var serial  = product[0].serials.filter(param => param.serial_id == serialID);

        if(serial.length == 0){
            product[0].serials.push({
                serial_id     : serialID,
                serial_number : serialNumber
            });
        }                   

        countQtyReceive(productID);

        tableSelectSerial.draw();
        drawSelectedSerial(productID);        
    }    

    const removeSerial = (that) => {
        var productID = that.attr('data-product-id');
        var serialID  = that.attr('data-serial-id');

        var product = dataProducts.filter(param => param.product_id == productID);

        product[0].serials = product[0].serials.filter(param => param.serial_id != serialID);

        countQtyReceive(productID);
        
        drawSelectedSerial(productID);            
        tableSelectSerial.draw();                
    }

    const countQtyReceive = (productID) => {
        var serials = dataProducts.filter(param =>  param.product_id == productID)[0].serials;

        var row = $('#table-product >  tbody').find('.product-item').find(`input[class=item-product][value=${productID}]`);        
        row.parents('tr').find('.qty-receive').val(serials.length);
    }

    const onSubmit = (status) => {
        $('input[name=status]').val(status);
        $('#form').first().trigger('submit');
    }
</script>
@endsection