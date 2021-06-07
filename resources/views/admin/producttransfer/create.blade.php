@extends('admin.layouts.app')
@section('title','Product Transfer')

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Product Transfer
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Inventory</li>
            <li class="breadcrumb-item">Product Transfer</li>
            <li class="breadcrumb-item">Create</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <form action="{{$url}}" id="form" class="form-horizontal no-margin" enctype="multipart/form-data">
            {{csrf_field()}}
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
                                        <input type="text" class="form-control" placeholder="Automatically generated" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="origin-unit" class="control-label">Origin Unit</label>
                                        <select name="origin_unit" id="origin-unit" class="form-control site" data-placeholder="Choose origin unit"></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="origin-warehouse" class="control-label">Origin Warehouse</label>
                                        <select name="origin_warehouse" id="origin-warehouse" class="form-control select2" data-placeholder="Choose origin warehouse"></select>
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
                                            <input type="datepicker" class="form-control datepicker text-right" id="transfer-date" placeholder="Enter date issued" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="destination-unit" class="control-label">Destination Unit</label>
                                        <select name="destination_unit" id="destination-unit" class="form-control site" data-placeholder="Choose destination unit"></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="destination-warehouse" class="control-label">Destination Warehouse</label>
                                        <select name="destination_warehouse" id="destination-warehouse" class="form-control select2" data-placeholder="Choose destination warehouse"></select>
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
                                        <textarea name="description" id="description" class="form-control summernote" placeholder="Enter description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="status" class="control-label">Status</label>
                                        <input type="hidden" name="status" id="status" value="draft">
                                        <div class="row ml-1">
                                            <div class="col-2">Submit</div>
                                            <div class="col-1">:</div>
                                            <div class="col-7"><span class="badge badge-warning text-sm">Waiting</span></div>
                                        </div>
                                        <div class="row mt-2 ml-1">
                                            <div class="col-2">Save</div>
                                            <div class="col-1">:</div>
                                            <div class="col-7"><span class="badge bg-gray text-sm">Draft</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <!-- PRODUCT TABLE -->
                        <div class="card-body">
                            <span class="title">
                                <hr>
                                <h5 class="text-md text-dark text-uppercase">Product Information</h5>
                            </span>
                            <div class="form-group">
                                <label for="product" class="control-label">Product</label>
                                <select name="product" id="product" class="form-control select2" data-placeholder="Choose Product"></select>
                                <br>
                                <button type="button" class="btn btn-success color-palette btn-labeled legitRipple text-sm btn-block" onclick="addProduct()">
                                    <b><i class="fas fa-plus"></i></b>
                                    Add
                                </button>
                            </div>
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-striped" width="100%" id="table-product">
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
                                        <button type="button" onclick="addDocument()" class="btn btn-labeled labeled-sm btn-md btn-block text-xs btn-success btn-flat legitRipple">
                                            <b><i class="fas fa-plus"></i></b> Add Document
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
                                        <button type="button" onclick="addPhoto()" class="btn btn-labeled labeled-sm btn-md btn-block text-xs btn-success btn-flat legitRipple">
                                            <b><i class="fas fa-plus"></i></b> Add Photo
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
                        <button type="button" onclick="onSubmit('waiting')" class="btn btn-success btn-labeled legitRipple text-sm">
                            <b><i class="fas fa-check-circle"></i></b>
                            Submit
                        </button>
                        <button type="button" onclick="onSubmit('draft')" class="btn bg-olive color-palette btn-labeled legitRipple text-sm">
                            <b><i class="fas fa-save"></i></b>
                            Save
                        </button>
                        <a href="{{ route('producttransfer.index') }}" class="btn btn-secondary color-palette btn-labeled legitRipple text-sm">
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

    $(function() {
        initInputFile();
        
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
        }).on('select2:clear', function(){
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
        }).on('select2:clear',function(e){
            $('#destination-warehouse').val(null).trigger('change');
        });        

        $("#origin-warehouse").select2({
            ajax: {
                url: "{{route('warehouse.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var originSite  = $('#origin-unit').find('option:selected').val(),
                        destination = $('#destination-warehouse').find('option:selected').val();
                    return {
                        name         : params.term,
                        page         : params.page,
                        site_id      : originSite?originSite:'',
                        exception_id : destination?destination:null,
                        limit        : 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id      : item.id,
                            text    : item.name,
                            site_id : item.site_id,
                            site    : item.site
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:select', function (e) {
            var data = e.params.data;         
            if(data.site_id){
                $('#origin-unit').select2('trigger','select',{
                    data : {
                        id   : data.site_id,
                        text : `${data.site}`
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
                    var originSite  = $('#destination-unit').find('option:selected').val(),
                        destination = $('#origin-warehouse').find('option:selected').val();
                    return {
                        name: params.term,
                        page: params.page,
                        site_id : originSite?originSite:'',
                        exception_id : destination?destination:null,
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
                            site_id : item.site_id,
                            site : item.site
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:select', function (e) {
            var data = e.params.data;         
            if(data.site_id){
                $('#destination-unit').select2('trigger','select',{
                    data : {
                        id   : data.site_id,
                        text : `${data.site}`
                    }
                });
            }
        });

        $('#table-product').on('change','.qty-transfer',function(){
           var qty = $(this) .val();
           $(this).parents('.product-item').find('.item-product').attr('data-qty-transfer',qty);
        });

        $('#table-product').on('keyup','.qty-transfer',function(){
           var qty = $(this) .val();
           $(this).parents('.product-item').find('.item-product').attr('data-qty-transfer',qty);
        });
        
        $("#form").validate({
            rules: {                
            },
            messages: {                
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
                var data          = new FormData($('#form')[0]),                    
                    transferDate  = $('#form').find('#transfer-date').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                    products      = [],
                    zeroValue     = false;
                
                $.each($('#table-product > tbody > .product-item'), function (index, value) { 
                     var product      = $(this).find('.item-product'),
                         product_id   = product.val(),
                         categoryID   = product.attr('data-category-id'),
                         uomID        = product.attr('data-uom-id'),
                         qtySystem    = product.attr('data-qty-system'),
                         qtyTransfer  = $(this).find('.qty-transfer').val();
                                         
                    products.push({
                        product_id      : product_id,
                        category_id     : categoryID,
                        uom_id          : uomID,
                        qty_system      : qtySystem,
                        qty_transfer    : qtyTransfer
                    });

                    if(qtyTransfer == 0 || qtyTransfer == ''){                        
                        zeroValue = true;
                        return false;
                    }

                });

                if(products.length == 0){
                    toastr.warning('Select product first.');
                    return false;
                }else if(zeroValue){
                    toastr.warning("Minimum value of qty transfer is 1.");
                    return false
                }                

                data.append('products',JSON.stringify(products));                
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

    const removeDoc = (that) => {
        that.closest('.document-item').remove();
        if($('#table-document > tbody > .document-item').length == 0){
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
        if($('#table-photo > tbody > .photo-item').length == 0){
            var html = `<tr class="no-available-data">
                                <td colspan="3" class="text-center">No available data.</td>
                            </tr>`;
                $('#table-photo > tbody').append(html);
        }
    }

    const onSubmit = (status) => {
        $('input[name=status]').val(status);
        $('form').first().trigger('submit');
    }

</script>
@endsection