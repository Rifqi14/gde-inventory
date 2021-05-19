@extends('admin.layouts.app')
@section('title','Create Product Borrowing')

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Product Borrowing
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Inventory</li>
            <li class="breadcrumb-item">Product Borrowing</li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <form class="form-horizontal no-margin" id="form">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr>
                                <h5 class="text-md text-dark text-uppercase">Borrowing Information</h5>
                            </span>
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Borrowing Number -->
                                    <div class="form-group">
                                        <label for="borrowing-number" class="control-label">Borrowing Number</label>
                                        <input type="text" class="form-control" name="borrowing_number" id="borrowing-number" placeholder="Enter Borrowing Number" required>
                                    </div>
                                    <!-- Product Category -->
                                    <div class="form-group">
                                        <label for="product-category" class="control-label">Product Category</label>
                                        <select name="product_category" id="product-category" class="form-control select2" data-placeholder="Choose Product Category">
                                        </select>
                                    </div>
                                    <!-- Borrowing Date -->
                                    <div class="form-group">
                                        <label for="borrowing-date" class="control-label">Borrowing Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="datepicker" class="form-control datepicker text-right" name="borrowing_date" id="borrowing-date" placeholder="Enter Borrowing Date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Site -->
                                    <div class="form-group">
                                        <label for="site" class="control-label">Site</label>
                                        <select name="site" id="site" class="form-control select2" data-placeholder="Choose Site">
                                        </select>
                                    </div>
                                    <!-- Warehouse -->
                                    <div class="form-group">
                                        <label for="warehouse" class="control-label">Warehouse</label>
                                        <select name="warehouse" id="warehouse" class="form-control select2" data-placeholder="Choose Warehouse">
                                        </select>
                                    </div>
                                    <!-- Status -->
                                    <div class="form-group">
                                        <label for="status" class="control-label">Status</label>
                                        <select name="status" id="status" class="form-control select2" data-placeholder="Status" disabled>
                                            <option value="draft" selected>Draft</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr>
                                <h5 class="text-md text-dark text-uppercase">Other Information</h5>
                            </span>
                            <!-- Issued By -->
                            <div class="form-group">
                                <label for="issued-by" class="control-label">Issued By</label>
                                <select name="issued_by" id="issued-by" class="form-control select2" data-placeholder="Issued By" disabled>
                                    @if(Auth::guard('admin')->user()->id)
                                    <option value="{{Auth::guard('admin')->user()->id}}" selected>{{Auth::guard('admin')->user()->name}}</option>
                                    @endif
                                </select>
                            </div>
                            <!-- Description -->
                            <div class="form-group">
                                <label for="description" class="control-label">Description</label>
                                <textarea name="description" id="description" cols="30" rows="10" class="form-control summernote" placeholder="Enter Descripton"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr>
                                <h5 class="text-md text-dark text-uppercase">Products Information</h5>
                            </span>
                            <div class="form-group">
                                <label for="product" class="control-label">Product</label>
                                <select name="product" id="product" class="form-control select2" data-placeholder="Choose Product"></select>
                            </div>
                            <div class="form-group">
                                <button type="button" onclick="addProduct()" class="btn btn-labeled labeled-sm btn-md btn-block text-xs btn-success btn-flat legitRipple">
                                    <b><i class="fas fa-plus"></i></b> Add Product
                                </button>
                            </div>
                            <!-- PRODUCTS -->
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-products" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="200">Product Name</th>
                                            <th width="15" class="text-center">UOM</th>
                                            <th width="15" class="text-right">Qty System</th>
                                            <th width="10" class="text-right">Qty Borrowing</th>
                                            <th width="10" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="no-available-data">
                                            <td colspan="5" class="text-center">No available data records.</td>
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
                                <hr>
                                <h5 class="text-md text-dark text-uppercase">Supporting Documents</h5>
                            </span>
                            <div class="card">
                                <div class="card-header p-0" style="background-color: #f4f6f9; border: none;">
                                    <ul class="nav nav-tabs tabs-documents" id="tabs-documents" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="document-tab-file" data-toggle="pill" href="#tab-file" role="tab" aria-controls="tab-file" aria-selected="true" data-type="document">Document</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="document-tab-photo" data-toggle="pill" href="#tab-photo" role="tab" aria-controls="tab-photo" aria-selected="" data-type="photo">Photo</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="tabs-document-tabContent">
                                        <div class="tab-pane fade show active" id="tab-file" role="tabpanel" aria-labelledby="document-tab-file">
                                            <div class="form-group">
                                                <button type="button" onclick="addDocument()" class="btn btn-labeled labeled-sm btn-md btn-block text-xs btn-success btn-flat legitRipple">
                                                    <b><i class="fas fa-plus"></i></b> Add Document
                                                </button>
                                            </div>
                                            <div id="form-document">
                                                <div class="form-group row document-item">
                                                    <div class="col-md-6">
                                                        <label for="document-name" class="control-label">Document Name</label>
                                                        <input type="text" class="form-control document-name" name="document_name[]" placeholder="Enter Document Name">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label for="document-file" class="control-label">File</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" name="attachment[]">
                                                                <label class="custom-file-label" for="exampleInputFile">Attach a file</label>
                                                            </div>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" style="margin-top: 30px;" type="button"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-content fade" id="tab-photo" role="tabpanel" aria-labelledby="document-tab-photo">
                                            <div class="form-group">
                                                <button type="button" onclick="addPhoto()" class="btn btn-labeled labeled-sm btn-md btn-block text-xs btn-success btn-flat legitRipple">
                                                    <b><i class="fas fa-plus"></i></b> Add Photo
                                                </button>
                                            </div>
                                            <div id="form-photo">
                                                <div class="form-group row photo-item">
                                                    <div class="col-md-6">
                                                        <label for="photo-name" class="control-label">Document Name</label>
                                                        <input type="text" class="form-control" id="photo-name" name="photo_name">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label for="photo">Photo</label>
                                                        <div class="controls text-center upload-image">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="upload-preview-wrapper">
                                                                        <a class="remove"><i class="fa fa-trash"></i></a>
                                                                        <img src="{{asset('assets/img/no-image.png')}}" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="upload-btn-wrapper">
                                                                        <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Upload a Picture</a>
                                                                        <input class="form-control" type="file" name="photo[]" id="photo" accept="image/*" />
                                                                    </div>
                                                                    <p class="text-sm text-muted">File must be no more than 2 MB</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" style="margin-top: 30px;" type="button"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="button" onclick="submitTest(`waiting`)" class="btn btn-success btn-labeled legitRipple text-sm">
                                        <b><i class="fas fa-check-circle"></i></b>
                                        Submit
                                    </button>
                                    <button type="button" onclick="submitTest(`draft`)" class="btn bg-olive color-palette btn-labeled legitRipple text-sm">
                                        <b><i class="fas fa-save"></i></b>
                                        Save
                                    </button>
                                </div>
                            </div>
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
    };

    $(function() {
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
            height: 150,
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

        $('#form-document').on('click', '.remove', function() {
            $(this).parents('.document-item').remove();
        });

        $('#form-photo').on('click', '.remove', function() {
            $(this).parents('.photo-item').remove();
        });

        $("#site").select2({
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
        });

        $("#warehouse").select2({
            ajax: {
                url: "{{route('warehouse.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var site_id = $('#site').select2('val');
                    return {
                        name: params.term,
                        site_id: site_id,
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
            console.log({
                warehouse: data
            });
            if (data.site_id) {
                $('#site').select2('trigger', 'select', {
                    data: {
                        id: data.site_id,
                        text: `${data.site}`,
                    }
                });
            }
        }).on('select2:clearing', function() {
            $('#site').val(null).trigger('change');
        });

        $("#product-category").select2({
            ajax: {
                url: "{{route('productcategory.select')}}",
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
        });

        $("#product").select2({
            ajax: {
                url: "{{route('product.select')}}",
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
                            text: item.name,
                            uom: item.uom,
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

        $('#table-products > tbody').on('click', '.remove', function() {
            $(this).closest('.product-item').remove();
            if ($('#table-products > tbody > .product-item').length == 0) {
                var html = `<tr class="no-available-data">
                                <td colspan="5" class="text-center">No available data records.</td>
                            </tr>`;
                $('#table-products > tbody').append(html);
            }
        });

        $('#table-products').on('keyup', '.qty-system', function() {
            var qty = $(this).val();
            $(this).parents('.product-item').find('.item-product').attr('data-qty-borrow', qty);
        });

        $('#table-products').on('change', '.qty-system', function() {
            var qty = $(this).val();
            $(this).parents('.product-item').find('.item-product').attr('data-qty-borrow', qty);
        });

    });

    function addProduct() {
        var product = $('#product').select2('data');

        if (product.length == 0) {
            toastr.warning('Select the product first.');
            return false;
        }

        product = product[0];
        var id = product.id,
            productName = product.text,
            uom = product.uom,
            qtySystem = product.qty_system,
            table = $('#table-products > tbody');

        if (table.find('.no-available-data').length > 0) {
            table.find('.no-available-data').remove();
        }

        var html = `<tr class="product-item">
                        <input type="hidden" class="item-product" value="${id}" data-qty-system="${qtySystem}" data-qty-borrow="0">
                        <td width="100">${productName}</td>
                        <td class="text-center" width="15">${uom}</td>
                        <td class="text-right" width="15">${qtySystem}</td>
                        <td class="text-center" width="15">
                            <input type="number" name="qty_system" class="form-control numberfield text-right qty-system" placeholder="0">
                        </td>
                        <td class="text-center" width="15">
                            <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;

        table.append(html);
    }

    function addDocument() {
        var html = `<div class="form-group row document-item">
                                            <div class="col-md-6">
                                                <label for="document-name" class="control-label">Document Name</label>
                                                <input type="text" class="form-control document-name" name="document_name[]" placeholder="Enter Document Name">
                                            </div>
                                            <div class="col-md-5">
                                                <label for="document-file" class="control-label">File</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="attachment[]">
                                                        <label class="custom-file-label" for="exampleInputFile">Attach a file</label>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" style="margin-top: 30px;" type="button"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>`;
        $('#form-document').append(html);
    }

    function addPhoto() {
        var html = `<div class="form-group row photo-item">
                        <div class="col-md-6">
                            <label for="photo-name" class="control-label">Document Name</label>
                            <input type="text" class="form-control" id="photo-name" name="photo_name[]" placeholder="Enter Document Name">
                        </div>
                        <div class="col-md-5">
                            <label for="photo">Photo</label>
                            <div class="controls text-center upload-image">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="upload-preview-wrapper">
                                            <a class="remove"><i class="fa fa-trash"></i></a>
                                            <img src="{{asset('assets/img/no-image.png')}}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="upload-btn-wrapper">
                                            <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Upload a Picture</a>
                                            <input class="form-control" type="file" name="photo[]" id="photo" accept="image/*" />
                                        </div>
                                        <p class="text-sm text-muted">File must be no more than 2 MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" style="margin-top: 30px;" type="button"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>`;

        $('#form-photo').append(html);
    }
</script>
@endsection