@extends('admin/layouts.app')
@section('title','Product Transfer')

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            {{ @$menu_name }}
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{ @$parent_name }}</li>
            <li class="breadcrumb-item">{{ @$menu_name }}</li>
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <form class="form-horizontal no-margin" action="{{route('producttransfer.update',['id' => $data->id])}}" id="form" enctype="multipart/form-data">
            {{csrf_field()}}
            @method('PUT')
            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <!-- TRANSFER INFORMATION -->
                        <div class="card-body">
                            <span class="title">
                                <hr>
                                <h5 class="text-md text-dark text-uppercase">Transfer Information</h5>
                            </span>
                            <div class="row mt-4">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="transfer-number" class="control-label">Transfer Number</label>
                                        <input type="text" class="form-control" placeholder="Automatically generated" value="{{$data->transfer_number}}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="origin-unit" class="control-label">Origin Unit</label>
                                        <select name="origin_unit" id="origin-unit" class="form-control site editable" data-placeholder="Choose origin unit" disabled></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="origin-warehouse" class="control-label">Origin Warehouse</label>
                                        <select name="origin_warehouse" id="origin-warehouse" class="form-control select2 editable" data-placeholder="Choose origin warehouse" disabled></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="vehicle_type" class="control-label">Vehicle Type</label>
                                        <select name="vehicle_type" id="vehicle_type" class="form-control select2 editable" data-placeholder="Choose Vehicle Type" disabled>
                                            <option value=""></option>
                                            <option value="Rent" @if ($data->vehicle_type == 'Rent') selected @endif>Rent</option>
                                            <option value="Internal" @if ($data->vehicle_type == 'Internal') selected @endif>Internal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="date-issued" class="control-label">Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="datepicker" class="form-control datepicker text-right editable" id="transfer-date" placeholder="Enter date issued" disabled required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="destination-unit" class="control-label">Destination Unit</label>
                                        <select name="destination_unit" id="destination-unit" class="form-control site editable" data-placeholder="Choose destination unit" disabled></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="destination-warehouse" class="control-label">Destination Warehouse</label>
                                        <select name="destination_warehouse" id="destination-warehouse" class="form-control select2 editable" data-placeholder="Choose destination warehouse" disabled></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="police_number" class="control-label">Police Number</label>
                                        <input type="text" class="form-control editable" placeholder="Police Number" name="police_number" value="{{$data->police_number}}" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="product_category_id" class="control-label">Product Category</label>
                                        <select name="product_category_id" id="product_category_id" class="form-control select2 editable" data-placeholder="Choose Product Category" disabled></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <!-- OTHER INFORMTION -->
                        <div class="card-body">
                            <span class="title">
                                <hr>
                                <h5 class="text-md text-dark text-uppercase">Other Information</h5>
                            </span>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="issued-by" class="control-label">Issued By</label>
                                        <input type="text" class="form-control" value="{{Auth::guard('admin')->user()->name}}" readonly>
                                        <input type="hidden" name="issued_by" value="{{Auth::guard('admin')->user()->id}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="control-label">Description</label>
                                        <textarea name="description" id="description" class="form-control summernote editable" placeholder="Enter description" disabled>{{$data->description}}</textarea>
                                    </div>
                                    <div class="form-group form-status">
                                        <label for="status" class="control-label">Status</label>
                                        <input type="hidden" name="status" id="status" value="">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <!-- PRODUCT INFORMTION -->
                        <div class="card-body">
                            <span class="title">
                                <hr>
                                <h5 class="text-md text-dark text-uppercase">Product Information</h5>
                            </span>
                            <div class="form-group">
                                <label for="product" class="control-label">Product</label>
                                <select name="product" id="product" class="form-control select2 editable" data-placeholder="Choose Product" disabled></select>
                                <br>
                                <button type="button" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple editable" onclick="addProduct()" disabled>
                                    Add
                                </button>
                            </div>
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-product" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="200">Product Name</th>
                                                <th width="15" class="text-center">UOM</th>
                                                <th width="15" class="text-center">Qty System</th>
                                                <th width="10" class="text-center">Qty Transfer</th>
                                                <th width="10" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="no-available-data">
                                                <td colspan="5" class="text-center">No data available.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr>
                                <h5 class="text-md text-dark text-uppercase">Supporting Document</h5>
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
                                        <button type="button" onclick="addDocument()" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple editable" disabled>
                                            Add Document
                                        </button>
                                    </div>
                                    <!-- TABLE DOCUMENT -->
                                    <table id="table-document" class="table table-striped datatable mt-3" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="45%">Document Name</th>
                                                <th width="45%" class="text-center">File</th>
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
                                <div class="tab-pane fade show" id="photo" role="tabpanel" aria-labelledby="photo-tab">
                                    <div class="form-group mt-3">
                                        <button type="button" onclick="addPhoto()" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple editable" disabled>
                                            Add Photo
                                        </button>
                                    </div>
                                    <!-- TABLE PHOTO -->
                                    <table id="table-photo" class="table table-striped datatable mt-3" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="45%">Photo Name</th>
                                                <th width="45%" class="text-center">File</th>
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
                        <div class="card-footer text-right">
                            @if (in_array('approval', $actionmenu))
                            <button type="button" onclick="onSubmit('approved')" class="btn btn-success btn-labeled legitRipple text-sm btn-approve">
                                <b><i class="fas fa-check-circle"></i></b>
                                Approve
                            </button>
                            <button type="button" onclick="onSubmit('waiting')" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-submit">
                                <b><i class="fas fa-save"></i></b>
                                Submit
                            </button>
                            @endif
                            <button type="button" onclick="editable(this)" data-action="open" class="btn bg-danger color-palette btn-labeled legitRipple text-sm btn-edit">
                                <b><i class="fas fa-pencil-alt"></i></b>
                                Edit
                            </button>
                            <a href="{{ route('producttransfer.index') }}" class="btn btn-secondary color-palette btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-arrow-left"></i></b>
                                Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
    }

    var deletedDoc = [];

    const editable = (e) => {
        if ($(e).data('action') == 'open') {
            $('#form .editable').attr('readonly', false);
            $('#form .editable').prop('disabled', false);
            $('.editable').attr('readonly', false);
            $('.editable').prop('disabled', false);
            $('.btn-approve').removeClass('d-none');
            $('.btn-submit').removeClass('d-none');
            $('.summernote').summernote('enable');
            $(e).data('action', 'close');
            $(e).removeClass('bg-danger');
            $(e).addClass('bg-secondary');
            $(e).html('<b><i class="fas fa-ban"></i></b> Cancel');
        } else {
            $('#form .editable').attr('readonly', true);
            $('#form .editable').attr('readonly', true);
            $('.editable').prop('disabled', true);
            $('.editable').prop('disabled', true);
            $('#save-button').addClass('d-none');
            $('.btn-approve').addClass('d-none');
            $('.btn-submit').addClass('d-none');
            $('.summernote').summernote('disable');
            $(e).data('action', 'open');
            $(e).removeClass('bg-secondary');
            $(e).addClass('bg-danger');
            $(e).html('<b><i class="fas fa-edit"></i></b> Edit');
        }
    }

    $(function() {
        var originSiteID = {{$data->origin_site_id?$data->origin_site_id:null}},
            destSiteID   = {{$data->destination_site_id?$data->destination_site_id:null}},
            originWareID = {{$data->origin_warehouse_id?$data->origin_warehouse_id:null}},
            destWareID   = {{$data->destination_warehouse_id?$data->destination_warehouse_id:null}},
            transferDate = '{{$data->transfer_date?$data->transfer_date:null}}',
            state        = '{{$data->status}}',
            badgeCol     = '';                    

        initInputFile();
        initData();
        editable();

        switch (state) {
            case 'draft' : 
                badgeCol = 'bg-gray';
                break;
            case 'waiting':
                badgeCol = 'badge-warning';
                break;
            case 'approved': 
                badgeCol = 'badge-info'    ;
                break;
            case 'archived' : 
            badgeCol = 'bg-red'
            default:
                badgeCol = '';
                break;
        }

        $('#status').val(state);

        $('.form-status').append(`<span class="badge ${badgeCol} text-sm" style="text-transform: capitalize;">${state}</span>`);

        $('.select2').select2({
            allowClear: true
        });

        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            timePicker: false,
            timePickerIncrement: 30,
            drops: 'auto',
            opens: 'center',
            locale: {
                format: 'DD/MM/YYYY'
            },
        });        

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

        

        $("#product_category_id").select2({
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
            escapeMarkup: function(text) {
                return text;
            },
        }).on('select2:close', function(e) {
            var data    = $(this).find('option:selected').val();
            var product = $('#product').select2('data');

            if (product[0] && product[0].product_category_id != data) {
                $('#product').val(null).trigger('change');
            }
        }).on('select2:clearing', function() {
            $('#product').val(null).trigger('change');
        });

        $("#product").select2({
            ajax: {
                url: "{{route('productborrowing.selectproduct')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var productCategory = $('#product_category_id').select2('val');
                    var products = [];

                    $.each($('#table-product > tbody > .product-item'), function(index, value) {
                        var product = $(this).find('.item-product'),
                        product_id = product.val();

                        products.push(product_id);

                    });
                    return {
                        name: params.term,
                        page: params.page,
                        product_category_id: productCategory,
                        products: products,
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
                            uom_id: item.uom_id,
                            uom: item.uom,
                            product_category_id: item.product_category_id,
                            qty_system: item.qty_system
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        });

        $("#origin-unit").select2({
            ajax: {
                url: "{{route('site.select')}}",
                type: 'GET',
                dataType: 'json',
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
        }).on('select2:clear', function() {
            $('#origin-warehouse').val(null).trigger('change');
        });

        $("#destination-unit").select2({
            ajax: {
                url: "{{route('site.select')}}",
                type: 'GET',
                dataType: 'json',
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
        }).on('select2:clear', function(e) {
            $('#destination-warehouse').val(null).trigger('change');
        });

        $("#origin-warehouse").select2({
            ajax: {
                url: "{{route('warehouse.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var originSite = $('#origin-unit').find('option:selected').val(),
                        destination = $('#destination-warehouse').find('option:selected').val();
                    return {
                        name: params.term,
                        page: params.page,
                        site_id: originSite ? originSite : '',
                        exception_id: destination ? destination : null,
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
                $('#origin-unit').select2('trigger', 'select', {
                    data: {
                        id: data.site_id,
                        text: `${data.site}`
                    }
                });
            }
        });

        $("#destination-warehouse").select2({
            ajax: {
                url: "{{route('warehouse.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var originSite = $('#destination-unit').find('option:selected').val(),
                        destination = $('#origin-warehouse').find('option:selected').val();
                    return {
                        name: params.term,
                        page: params.page,
                        site_id: originSite ? originSite : '',
                        exception_id: destination ? destination : null,
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
                $('#destination-unit').select2('trigger', 'select', {
                    data: {
                        id: data.site_id,
                        text: `${data.site}`
                    }
                });
            }
        });

        if(transferDate){
            $('#transfer-date').data('daterangepicker').setStartDate(`${transferDate}`);
            $('#transfer-date').data('daterangepicker').setEndDate(`${transferDate}`);
        }

        if(originSiteID){
            $('#origin-unit').select2('trigger','select',{
                data :{
                    id : originSiteID,
                    text : `{{$data->originsites->origin_site}}`
                }
            });
        }

        if(originWareID){
            $('#origin-warehouse').select2('trigger','select',{
                data : {
                    id : originWareID,
                    text : `{{$data->originwarehouses->origin_warehouse}}`
                }
            });
        }

        if(destSiteID){
            $('#destination-unit').select2('trigger','select',{
                data : {
                    id : destSiteID,
                    text : `{{$data->destinationsites->destination_site}}`
                }
            });
        }

        if(destWareID){
            $('#destination-warehouse').select2('trigger','select',{
                data : {
                    id : destWareID,
                    text : `{{$data->destinationwarehouses->destination_warehouse}}`
                }
            });
        }

        $('#table-product').on('change', '.qty-transfer', function() {
            var qty = $(this).val();
            $(this).parents('.product-item').find('.item-product').attr('data-qty-transfer', qty);
        });

        $('#table-product').on('keyup', '.qty-transfer', function() {
            var qty = $(this).val();
            $(this).parents('.product-item').find('.item-product').attr('data-qty-transfer', qty);
        });

        $("#form").validate({
            rules: {},
            messages: {},
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
                var data         = new FormData($('#form')[0]),
                    transferDate = $('#form').find('#transfer-date').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                    products     = [],
                    documents    = [],
                    zeroValue    = false;

                $.each($('#table-product > tbody > .product-item'), function(index, value) {
                    var product = $(this).find('.item-product'),
                        product_id = product.val(),
                        categoryID = product.attr('data-category-id'),
                        uomID = product.attr('data-uom-id'),
                        qtySystem = product.attr('data-qty-system'),
                        qtyTransfer = $(this).find('.qty-transfer').val();

                    products.push({
                        product_id: product_id,
                        category_id: categoryID,
                        uom_id: uomID,
                        qty_system: qtySystem,
                        qty_transfer: qtyTransfer
                    });

                    if (qtyTransfer == 0 || qtyTransfer == '') {
                        zeroValue = true;
                        return false;
                    }

                });

                $.each($('#table-document > tbody > .document-item').find('.doc-cell'), function (index, val) { 
                     var input      = $(this).parents('.document-item').find('.document-name'),
                         filename   = input.val(),
                         docID      = input.attr('data-id'),
                         transferID = input.attr('data-transfer-id'),
                         file       = input.attr('data-file');

                    documents.push({
                        id          : docID,
                        transferID  : transferID,
                        docName     : filename,
                        type        : 'document',
                        file        : file
                    });
                });   

                $.each($('#table-photo > tbody > .photo-item').find('.doc-cell'), function (index, val) { 
                     var input      = $(this).parents('.photo-item').find('.document-name'),
                         filename   = input.val(),
                         docID      = input.attr('data-id'),
                         transferID = input.attr('data-transfer-id'),
                         file       = input.attr('data-file');

                    documents.push({
                        id          : docID,
                        transferID  : transferID,
                        docName     : filename,
                        type        : 'image',
                        file        : file
                    });
                });    
                                

                if (products.length == 0) {
                    toastr.warning('Select product first.');
                    return false;
                } else if (zeroValue) {
                    toastr.warning("Minimum value of qty transfer is 1.");
                    return false
                }

                data.append('products', JSON.stringify(products));
                data.append('documents',JSON.stringify(documents));
                data.append('undocuments',JSON.stringify(deletedDoc));
                data.append('transferdate', transferDate);

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
                    console.log({
                        response: response
                    });
                    if (response.status) {
                        toastr.success('Data has been saved.');
                        document.location = "{{route('producttransfer.index')}}";
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

    const initData = () => {
        var products = @json($data->products);
        var files    = @json($data->files);
        var images   = @json($data->images);

        console.log({
            products : products,
            files : files,
            images : images
        });

        if(products.length > 0){
            var html    = '',
                table   = $('#table-product > tbody');

            $.each(products, function (index, value) { 
                var id          = value.product_id,
                     productName = value.product_name,
                     categoryID  = value.product_category_id,
                     uomID       = value.uom_id,
                     uom         = value.uom_name,
                     qtySystem   = value.qty_system,
                     qtyRequest  = value.qty_requested;
                     
                    html += `<tr class="product-item">
                                <input type="hidden" class="item-product" value="${id}" data-category-id="${categoryID}" data-uom-id="${uomID}" data-qty-system="${qtySystem}" data-qty-transfer="${qtyRequest}">
                                <td width="100">${productName}</td>
                                <td class="text-center" width="15">${uom}</td>
                                <td class="text-right" width="15">${qtySystem}</td>
                                <td class="text-center" width="15">
                                    <input type="number" name="qty_transfer" class="form-control numberfield text-right qty-transfer editable" placeholder="0" value="${qtyRequest}" required readonly>
                                </td>
                                <td class="text-center" width="15">
                                    <button class="btn btn-md text-xs btn-danger btn-flat legitRipple editable" type="button" onclick="removeProduct($(this))" disabled><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>`;
            });

            table.find('.no-available-data').remove();
            table.append(html);
        }

        console.log(files);

        if(files.length > 0){
            var html  = '',
                table = $('#table-document > tbody');

            $.each(files, function (index, value) { 
                var id         = value.id,
                    transferID = value.product_transfer_id,
                    filename   = value.document_name,
                    file       = value.file,
                    path       = `{{asset('assets/producttransfer/${transferID}/document/${file}')}}`;

                html += `<tr class="document-item">
                            <td>
                                <input type="text" class="form-control document-name" data-id="${id}" data-transfer-id="${transferID}" data-type="file" data-file="${file}" placeholder="Enter document name" value="${filename}" required readonly>                                
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
                    transferID = value.product_transfer_id,
                    filename   = value.document_name,
                    file       = value.file,
                    path       = `{{asset('assets/producttransfer/${transferID}/image/${file}')}}`;

                html += `<tr class="photo-item">
                            <td>
                            <input type="text" class="editable form-control document-name" data-id="${id}" data-transfer-id="${transferID}" data-type="photo" data-file="${file}" placeholder="Enter photo name" value="${filename}" required  readonly>                            
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

    const addProduct = () => {
        var product = $('#product').select2('data');

        if(product.length == 0){
            toastr.warning('Select the product first.');
            return false;
        }

        product = product[0];

        var id          = product.id,
            productName = product.text,
            categoryID  = product.product_category_id,
            uomID       = product.uom_id,
            uom         = product.uom,
            qtySystem   = product.qty_system,
            table       = $('#table-product > tbody');

        if (table.find('.no-available-data').length > 0) {
            table.find('.no-available-data').remove();
        }

        var html = `<tr class="product-item">
                        <input type="hidden" class="item-product" value="${id}" data-category-id="${categoryID}" data-uom-id="${uomID}" data-qty-system="${qtySystem}" data-qty-transfer="0">
                        <td width="100">${productName}</td>
                        <td class="text-center" width="15">${uom}</td>
                        <td class="text-right" width="15">${qtySystem}</td>
                        <td class="text-center" width="15">
                            <input type="number" name="qty_transfer" class="form-control numberfield text-right qty-transfer" placeholder="0" required>
                        </td>
                        <td class="text-center" width="15">
                            <button class="btn btn-md text-xs btn-danger btn-flat legitRipple" type="button" onclick="removeProduct($(this))"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;

        table.append(html);
        $('#product').val(null).trigger('change');

    }

    const removeProduct = (that) => {
        that.closest('.product-item').remove();
        if($('#table-product > tbody > .product-item').length == 0){
            var html = `<tr class="no-available-data">
                                <td colspan="5" class="text-center">No available data.</td>
                            </tr>`;
                $('#table-product > tbody').append(html);                
        }        
    }

    const initInputFile = () => {
        $('.custom-file-input').on('change', function() {
          let fileName = $(this).val().split('\\').pop();
          $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
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
                            <button class="btn btn-md text-xs btn-danger btn-flat legitRipple" type="button" onclick="removeDoc($(this))"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;
        $('#table-document > tbody').append(html);
        initInputFile();
    }

    const removeDoc = (that,id) => {
        var document   = that.closest('.document-item').find('.document-name'),
            file       = document.attr('data-file'),
            transferID = document.attr('data-transfer-id'),
            type       = document.attr('data-type');

        that.closest('.document-item').remove();
        if($('#table-document > tbody > .document-item').length == 0){
            var html = `<tr class="no-available-data">
                                <td colspan="3" class="text-center">No available data.</td>
                            </tr>`;
                $('#table-document > tbody').append(html);
        }

        deletedDoc.push({
            id : id,            
            path : `assets/producttransfer/${transferID}/document/${file}`
        });                                   
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
            transferID = document.attr('data-transfer-id'),
            type       = document.attr('data-type');

        that.closest('.photo-item').remove();
        if($('#table-photo > tbody > .photo-item').length == 0){
            var html = `<tr class="no-available-data">
                                <td colspan="3" class="text-center">No available data.</td>
                            </tr>`;
                $('#table-photo > tbody').append(html);
        }

        deletedDoc.push({
            id : id,            
            path : `assets/producttransfer/${transferID}/image/${file}`
        });

        console.log({ deleted : deletedDoc});
    }   

    const onSubmit = (status) => {
        $('input[name=status]').val(status);
        $('form').first().trigger('submit');
    }
</script>
@endsection