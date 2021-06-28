@extends('admin.layouts.app')
@section('title',$menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Edit {{ $menu_name }}
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{ $parent_name }}</li>
            <li class="breadcrumb-item">{{ $menu_name }}</li>
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <form id="form" action="{{route('goodsissue.update',['id' => $data->id])}}" role="form" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="issued-number" class="control-label">Issued Number</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" value="{{$data->issued_number}}" placeholder="Automatically Generated" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date" class="control-label">Date</label>
                                        <div class="controls">
                                            <div class="input-group">
                                                <input type="text" id="date" class="form-control datepicker text-right">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="unit" class="col-md-12 col-xs-12 control-label">Unit</label>
                                        <div class="col-sm-12 controls">
                                            <select name="unit" id="unit" class="form-control" data-placeholder="Choose unit"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="warehouse" class="col-md-12 col-xs-12 control-label">Warehouse</label>
                                        <div class="col-sm-12 controls">
                                            <select name="warehouse" id="warehouse" class="form-control" data-placeholder="Choose warehouse"></select>
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
                            <div class="form-group row">
                                <label class="col-md-12 col-xs-12 control-label" for="issued-by">Issued By</label>
                                <div class="col-sm-12 controls">
                                    <input type="text" class="form-control" value="{{$data->issued}}" readonly>                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-md-12 col-xs-12 control-label">Description</label>
                                <div class="col-sm-12 controls">
                                    <textarea class="form-control summernote" name="description" id="description" rows="4" placeholder="Description">{{$data->description}}</textarea>
                                </div>
                            </div>
                            <div class="form-group form-status">
                                <label for="state" class="control-label">Status</label>
                                <div class="controls"></div>
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
                                <button type="button" class="btn btn-success color-palette btn-labeled legitRipple text-sm btn-block" onclick="addReference()">
                                    <b><i class="fas fa-plus"></i></b>
                                    Add
                                </button>
                            </div>
                            <div class="form-group table-responsive">
                                <table id="table-product" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="100">Product Name</th>
                                            <th width="100">Reference</th>
                                            <th width="30" class="text-right">Qty Request</th>
                                            <th width="30" class="text-right">Qty Receive</th>
                                            <th width="100">Rack</th>
                                            <th width="100">Bin</th>
                                            <th width="10" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="no-available-data">
                                            <td colspan="7" class="text-center">No available data.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
                                        <button type="button" class="btn btn-success color-palette btn-labeled legitRipple text-sm btn-block" onclick="addDocument()">
                                            <b><i class="fas fa-plus"></i></b>
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
                                        <button type="button" class="btn btn-success color-palette btn-labeled legitRipple text-sm btn-block" onclick="addPhoto()">
                                            <b><i class="fas fa-plus"></i></b>
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

<div class="modal fade" id="form-reference">
    <div class="modal-dialog modal-lg">
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
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="reference-tab">
                            <div class="tab-pane fade show active table-responsive" id="add-consumable-reference" role="tabpanel" aria-labelledby="add-consumable-reference-tab">
                                <table id="table-consumable" class="table table-striped datatable" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="15%" class="text-center">Date</th>
                                            <th width="20%">Consumable Number</th>
                                            <th width="20%">Produk</th>
                                            <th width="10%" class="text-center">Qty</th>
                                            <th width="15%" class="text-center">UOM</th>
                                            <th width="15%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="tab-pane fade show table-responsive" id="add-transfer-reference" role="tabpanel" aria-labelledby="add-transfer-reference-tab">
                                <table id="table-transfer" class="table table-striped datatable" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="15%" class="text-center">Date</th>
                                            <th width="20%">Transfer Number</th>
                                            <th width="20%">Produk</th>
                                            <th width="10%" class="text-center">Qty</th>
                                            <th width="15%" class="text-center">UOM</th>
                                            <th width="15%" class="text-center">Action</th>
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
    var productRole = '';
    var deletedDoc  = [];
    var issuedProduct = [];

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
        var siteID      = {{$data->site_id?$data->site_id:null}},
            warehouseID = {{$data->warehouse_id?$data->warehouse_id:null}},
            issuedDate  = '{{$data->issueddate}}',
            state       = '{{$data->status}}';

        initData();

        switch (state) {
            case 'approved':
                badge = 'bg-info';
                break;
            case 'rejected':
                badge = 'bg-red';                
                break;
            default:
                badge = '';
                state = '';
                break;
        }

        $('.form-status').find('.controls').html(`<span class="badge ${badge} text-sm" style="text-transform: capitalize">${state}</span>`);

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

        if(issuedDate){
            date = $('#date').data('daterangepicker');
            date.setStartDate(issuedDate);
            date.setEndDate(issuedDate);
        }

        if(siteID){
            $('#unit').select2('trigger','select',{
                data: {
                    id: siteID,
                    text: '{{$data->site}}'
                }
            });
        }
        if(warehouseID){
            $('#warehouse').select2('trigger','select',{
                data: {
                    id: warehouseID,
                    text: '{{$data->warehouse}}'
                }
            });
        }

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
                }
            },
            columnDefs: [{
                    orderable: false,
                    targets: [0, 6]
                },
                {
                    className: "text-center",
                    targets: [0, 1, 4, 5, 6]
                },
                {
                    render: function(data, type, row) {
                        return `<b>${row.consumable_number}</b>`
                    },
                    targets: [2]
                },
                {
                    render: function(data, type, row) {
                        var referenceID = row.product_consumable_id,
                            reference = row.consumable_number,
                            productID = row.product_id,
                            product = row.product,
                            uomID = row.uom_id,
                            qty = row.qty ? row.qty : 0;

                        return `<button class="btn btn-md text-xs btn-success btn-flat legitRipple" onclick="addProduct($(this),'consumable')" type="button" data-reference-id="${referenceID}" data-reference="${reference}" data-product-id="${productID}" data-product="${product}" data-uom-id="${uomID}" data-qty="${qty}">
                      <i class="fas fa-plus"></i>
                    </button>`;
                    },
                    targets: [6]
                }
            ],
            columns: [{
                    data: "no"
                },
                {
                    data: "date_consumable"
                },
                {
                    data: "consumable_number"
                },
                {
                    data: "product"
                },
                {
                    data: "qty"
                },
                {
                    data: "uom"
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
                }
            },
            columnDefs: [{
                    orderable: false,
                    targets: [0, 6]
                },
                {
                    className: "text-center",
                    targets: [0, 1, 4, 5, 6]
                },
                {
                    render: function(data, type, row) {
                        return `<b>${row.transfer_number}</b>`;
                    },
                    targets: [2]
                },
                {
                    render: function(data, type, row) {
                        var referenceID = row.product_transfer_id,
                            reference = row.transfer_number,
                            productID = row.product_id,
                            product = row.product,
                            uomID = row.uom_id,
                            qty = row.qty ? row.qty : 0;

                        return `<button class="btn btn-md text-xs btn-success btn-flat legitRipple" onclick="addProduct($(this),'transfer')" type="button" data-reference-id="${referenceID}" data-reference="${reference}" data-product-id="${productID}" data-product="${product}" data-uom-id="${uomID}" data-qty="${qty}">
                      <i class="fas fa-plus"></i>
                    </button>`;
                    },
                    targets: [6]
                }
            ],
            columns: [{
                    data: "no"
                },
                {
                    data: "transfer_date"
                },
                {
                    data: "transfer_number"
                },
                {
                    data: "product"
                },
                {
                    data: "qty"
                },
                {
                    data: "uom"
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
                var data = new FormData($('#form')[0]),
                    issuedDate = $('#form').find('#date').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                    products = [],
                    documents = [],
                    zeroValue = false;

                $.each($('#table-product > tbody > .product-item'), function(index, value) {
                    var product     = $(this).find('.item-product'),
                        product_id  = product.val(),
                        referenceID = product.attr('data-reference-id'),
                        uomID       = product.attr('data-uom-id'),
                        qtyRequest  = product.attr('data-qty-request'),
                        qtyReceive  = $(this).find('.qty-receive').val(),
                        rackID      = product.parents('.product-item').find('.rack-warehouse > option:selected').val(),
                        binID       = product.parents('.product-item').find('.bin-warehouse > option:selected').val(),
                        type        = product.attr('data-type');

                    products.push({
                        product_id: product_id,
                        reference_id: referenceID,
                        uom_id: uomID,
                        qty_request: qtyRequest,
                        qty_receive: qtyReceive ? qtyReceive : 0,
                        rack_id: rackID,
                        bin_id: binID,
                        type: type
                    })

                });

                if (products.length == 0) {
                    toastr.warning('Select the product first. at least one product');
                    return false;
                } else if (zeroValue) {
                    toastr.warning("Minimum value of qty receive is 1.");
                    return false
                }       
                
                $.each($('#table-document > tbody > .document-item').find('.doc-cell'), function (index, val) { 
                    var input      = $(this).parents('.document-item').find('.document-name'),
                        filename   = input.val(),
                        docID      = input.attr('data-id'),
                        issuedID   = input.attr('data-issued-id'),                        
                        file       = input.attr('data-file');
                    documents.push({
                        id          : docID,
                        issuedID    : issuedID,
                        docName     : filename,
                        type        : 'document',
                        file        : file
                    });
                });

                $.each($('#table-photo > tbody > .photo-item').find('.doc-cell'), function (index, val) { 
                    var input      = $(this).parents('.photo-item').find('.document-name'),
                        filename   = input.val(),
                        docID      = input.attr('data-id'),
                        issuedID   = input.attr('data-issued-id'),                        
                        file       = input.attr('data-file');
                    documents.push({
                        id          : docID,
                        issuedID    : issuedID,
                        docName     : filename,
                        type        : 'image',
                        file        : file
                    });
                });                

                data.append('products', JSON.stringify(products));
                data.append('documents',JSON.stringify(documents));
                data.append('undocuments',JSON.stringify(deletedDoc));
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
                warehouse_id : warehouseID?warehouseID:null,
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
                warehouse_id: item.warehouse_id,
                warehouse: item.warehouse
                });
            });
            return {
                results: option,
                more: more,
            };
            },
        },
        allowClear: true,
        }).on('select2:select', function(e){
        var data = e.params.data;
        if(data.warehouse_id){
            $('#form').find('#warehouse').select2('trigger','select',{
            data : {
                id: data.warehouse_id,
                text: `${data.warehouse}`
            }
            });
        }
        }).on('select2:clear', function() {
        $(this).parents('.product-item').find('.bin-warehouse').val(null).trigger('change');
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
                name: params.term,
                warehouse_id: warehouseID?warehouseID:null,
                rack_id: rackID ? rackID : null,
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
                rack_id : item.rack_id,
                rack : item.rack,
                warehouse_id: item.warehouse_id,
                warehouse: item.warehouse
                });
            });
            return {
                results: option,
                more: more,
            };
            },
        },
        allowClear: true,
        }).on('select2:select', function(e){
        var data = e.params.data;        
        if(data.rack_id){
            var rack = $(this).parents('.product-item').find('.rack-warehouse');
            rack.select2('trigger','select',{
                data : {
                    id   : data.rack_id,
                    text : `${data.rack}`
                }
            });
        }
        if(data.warehouse_id){
            $('#form').find('#warehouse').select2('trigger','select',{
            data: {
                id: data.warehouse_id,
                text: `${data.warehouse}`
            }
            });
        }
        });
    }  

    const initInputFile = () => {
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    } 

    const initData = () => {
        var products = @json(count($data->consumableproducts)>0?$data->consumableproducts:$data->transferproducts);
        var files    = @json($data->files);
        var images   = @json($data->images);

        if(products.length > 0){      
            $.each(products, function (index, value) { 
                var html  = '',
                    table   = $('#table-product > tbody');
                var productID    = value.product_id,
                    product      = value.product,
                    referenceID  = value.reference_id,
                    reference    = value.reference,
                    uomID        = value.uom_id,
                    qtyRequest   = value.qty_request,
                    qtyReceive   = value.qty_receive?value.qty_receive:0,
                    rackID       = value.rack_id,
                    rack         = value.rack,
                    binID        = value.bin_id,
                    bin          = value.bin,
                    type         = value.type;

                    html = `<tr class="product-item">
                        <input type="hidden" class="item-product" value="${productID}" data-reference-id="${referenceID}" data-uom-id="${uomID}" data-qty-request="${qtyRequest}" data-type="${type}">                        
                        <td width="100">${product}</td>
                        <td width="100"><b>${reference}</b></td>
                        <td class="text-right" width="30">${qtyRequest}</td>
                        <td class="text-right" width="30">
                            <input type="number" class="form-control numberfield text-right qty-receive" placeholder="0" value="${qtyReceive}">
                        </td>
                        <td width="100">
                            <div class="form-group">
                            <div class="controls">
                                <select name="rack" class="form-control rack-warehouse" data-placeholder="Choose rack" style="width: 100%;" data-rack-id="${rackID}" data-rack="${rack}" required></select>
                            </div>
                            </div>                    
                        </td>
                        <td width="100">
                            <div class="form-group">
                            <div class="controls">
                            <select name="bin" class="form-control bin-warehouse" data-placeholder="Choose bin" style="width: 100%;" data-bin-id="${binID}" data-bin="${bin}" required></select>
                            </div>
                            </div>                                        
                        </td>
                        <td class="text-center" width="10">
                            <button class="btn btn-md text-xs btn-danger btn-flat legitRipple" type="button" onclick="removeProduct($(this))"><i class="fas fa-trash"></i></button>
                        </td>
                        </tr>`;

                productRole = type;
                issuedProduct.push(productID);

                table.find('.no-available-data').remove();
                table.append(html);                         
            }); 

            initBin();
            initRack();

            $.each($('#table-product > tbody').find('tr.product-item'), function (index, value) { 
                var rack     = $(this).find('.rack-warehouse'),
                    rackID   = rack.attr('data-rack-id'),
                    rackName = rack.attr('data-rack');
                    
                if(rackID){
                    rack.select2('trigger','select',{
                    data : {
                        id : rackID,
                        text : `${rackName}`
                    }
                    });
                }

                var bin     = $(this).find('.bin-warehouse'),
                    binID   = bin.attr('data-bin-id'),
                    binName = bin.attr('data-bin');

                if(binID){
                    bin.select2('trigger','select',{
                    data : {
                        id : binID,
                        text : `${binName}`
                    }
                    });
                }
            });
        }

        if(files.length > 0){
            var html  = '',
                table = $('#table-document > tbody');

                $.each(files, function (index, value) { 
                    var id         = value.id,
                        issuedID   = value.goods_issue_id,
                        filename   = value.document_name,
                        file       = value.file,
                        path       = `{{asset('assets/goodsissue/${issuedID}/document/${file}')}}`;

                    html += `<tr class="document-item">
                                <td>
                                    <input type="text" class="form-control document-name" data-id="${id}" data-issued-id="${issuedID}" data-type="file" data-file="${file}" placeholder="Enter document name" value="${filename}" required>                                
                                </td>
                                <td class="doc-cell">                       
                                    <a href="${path}" target="_blank">
                                    <b><i class="fas fa-download"></i></b> Download File
                                    </a>                             
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-md text-xs btn-danger btn-flat legitRipple" onclick="removeDoc($(this),${id})" type="button"><i class="fas fa-trash"></i></button>                                
                                </td>
                            </tr>`;
                });

                table.find('.no-available-data').remove();
                table.append(html);
            }

        if(images.length > 0){
            var html  = '',
                table = $('#table-photo > tbody');

                $.each(images, function (index, value) { 
                    var id         = value.id,
                        issuedID   = value.goods_issue_id,
                        filename   = value.document_name,
                        file       = value.file,
                        path       = `{{asset('assets/goodsissue/${issuedID}/image/${file}')}}`;

                    html += `<tr class="photo-item">
                                <td>
                                <input type="text" class="form-control document-name" data-id="${id}" data-issued-id="${issuedID}" data-type="photo" data-file="${file}" placeholder="Enter photo name" value="${filename}" required>                            
                                </td>
                                <td class="doc-cell">          
                                    <a href="${path}" target="_blank">
                                    <b><i class="fas fa-download"></i></b> Download File
                                    </a>                      
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-md text-xs btn-danger btn-flat legitRipple" onclick="removePhoto($(this),${id})" type="button"><i class="fas fa-trash"></i></button>                                
                                </td>
                            </tr>`;
                });

                table.find('.no-available-data').remove();
                table.append(html);
        }       
    }       

    const addReference = () => {
        if (productRole == 'consumable') {
            consumableTable.draw();
            $('li.transfer-reference').hide();
        } else if (productRole == 'transfer') {
            transferTable.draw();
            $('li.consumable-reference').hide();
        } else {
            consumableTable.draw();
            transferTable.draw();
            $('li.consumable-reference').show();
            $('li.transfer-reference').show();
        }

        $('#form-reference').modal('show');
    }

    const addProduct = (that, type) => {
        var referenceID = that.attr('data-reference-id'),
            reference = that.attr('data-reference'),
            productID = that.attr('data-product-id'),
            product = that.attr('data-product'),
            uomID = that.attr('data-uom-id'),
            qty = that.attr('data-qty'),
            table = $('#table-product > tbody');


        var html = `<tr class="product-item">
                  <input type="hidden" class="item-product" value="${productID}" data-reference-id="${referenceID}" data-uom-id="${uomID}" data-qty-request="${qty}" data-type="${type}">                        
                  <td width="100">${product}</td>
                  <td width="100"><b>${reference}</b></td>
                  <td class="text-right" width="30">${qty}</td>
                  <td class="text-right" width="30">
                    <input type="number" class="form-control numberfield text-right qty-receive" value="0" placeholder="0">
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
                      <button class="btn btn-md text-xs btn-danger btn-flat legitRipple" type="button" onclick="removeProduct($(this),${productID})"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>`;

        table.find('.no-available-data').remove();
        table.append(html);
        issuedProduct.push(parseInt(productID));

        initBin();
        initRack();

        productRole = type;
        if (productRole == 'consumable') {
            $('li.transfer-reference').hide();
            consumableTable.ajax.reload(null, false);
        } else if (productRole == 'transfer') {
            $('li.consumable-reference').hide();
            transferTable.ajax.reload(null, false);
        }
    }

    const removeProduct = (that, productID) => {
        that.closest('.product-item').remove();
        if ($('#table-product > tbody > .product-item').length == 0) {
            var html = `<tr class="no-available-data">
                    <td colspan="7" class="text-center">No available data.</td>
                  </tr>`;
            $('#table-product > tbody').append(html);

            productRole = '';
        }
        issuedProduct.splice($.inArray(productID, issuedProduct), 1);
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

    const removeDoc = (that,id) => {
        var document   = that.closest('.document-item').find('.document-name'),
            file       = document.attr('data-file'),
            issuedID   = document.attr('data-issued-id'),
            type       = document.attr('data-type');

        that.closest('.document-item').remove();
        if($('#table-document > tbody > .document-item').length == 0){
            var html = `<tr class="no-available-data">
                            <td colspan="3" class="text-center">No available data.</td>
                        </tr>`;
                $('#table-document > tbody').append(html);
        }

        if(id){
            deletedDoc.push({
                id : id,            
                path : `assets/goodsissue/${issuedID}/document/${file}`
            });
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

    const removePhoto = (that,id) => {
        var document   = that.closest('.photo-item').find('.document-name'),
            file       = document.attr('data-file'),
            issuedID   = document.attr('data-issued-id'),
            type       = document.attr('data-type');

        that.closest('.photo-item').remove();
        if($('#table-photo > tbody > .photo-item').length == 0){
            var html = `<tr class="no-available-data">
                                <td colspan="3" class="text-center">No available data.</td>
                            </tr>`;
                $('#table-photo > tbody').append(html);
        }

        if(id){
        deletedDoc.push({
            id : id,            
            path : `assets/goodsissue/${issuedID}/image/${file}`
        });  
        }
    }  

    const onSubmit = (status) => {
        $('input[name=status]').val(status);
        $('#form').first().trigger('submit');
    }

</script>
@endsection