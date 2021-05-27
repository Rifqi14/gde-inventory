@extends('admin.layouts.app')
@section('title','Detail Product Borrowing')

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
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <form class="form-horizontal no-margin" id="form" enctype="multipart/form-data">
            {{csrf_field()}}            
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
                                        <input type="text" class="form-control" name="borrowing_number" id="borrowing-number" placeholder="Enter Borrowing Number" value="{{$data->borrowing_number}}" readonly>
                                    </div>
                                    <!-- Product Category -->
                                    <div class="form-group">
                                        <label for="product-category" class="control-label">Product Category</label>
                                        <select name="product_category" id="product-category" class="form-control select2" data-placeholder="Choose Product Category" disabled>
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
                                            <input type="datepicker" class="form-control datepicker text-right" name="borrowing_date" id="borrowing-date" placeholder="Enter Borrowing Date" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Site -->
                                    <div class="form-group">
                                        <label for="site" class="control-label">Site</label>
                                        <select name="site" id="site" class="form-control select2" data-placeholder="Choose Site" disabled>
                                        </select>
                                    </div>
                                    <!-- Warehouse -->
                                    <div class="form-group">
                                        <label for="warehouse" class="control-label">Warehouse</label>
                                        <select name="warehouse" id="warehouse" class="form-control select2" data-placeholder="Choose Warehouse" disabled>
                                        </select>
                                    </div>
                                    <!-- Status -->
                                    <div class="form-group" id="form-status">
                                        <label for="status" class="control-label">Status</label>  
                                        <br>                                        
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
                                    <option value=""></option>
                                    @if($data->issued_by)
                                    <option value="{{$data->issued_by}}" selected>{{$data->issued_name}}</option>                                    
                                    @else if(Auth::guard('admin')->user()->id)
                                    <option value="{{Auth::guard('admin')->user()->id}}" selected>{{Auth::guard('admin')->user()->name}}</option>
                                    @endif
                                </select>
                            </div>
                            <!-- Description -->
                            <div class="form-group">
                                <label for="description" class="control-label">Description</label>
                                <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Enter Descripton" readonly>{{$data->description}}</textarea>
                            </div>
                            <input type="hidden" name="status" id="status" value="{{$data->status}}">
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
                            <!-- PRODUCTS -->
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-products" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="200">Product Name</th>
                                            <th width="15" class="text-center">UOM</th>
                                            <th width="15" class="text-right">Qty System</th>
                                            <th width="10" class="text-right">Qty Borrowing</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="no-available-data">
                                            <td colspan="4" class="text-center">No available data.</td>
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
                                <h5 class="text-md text-dark text-uppercase">Supporting Document</h5>
                            </span>
                            <div class="mt-5"></div>
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
                                    <!-- TABLE DOCUMENT -->
                                    <table id="table-document" class="table table-striped datatable mt-3" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="50%">Document Name</th>
                                                <th width="50%" class="text-center">File</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="no-available-data">
                                                <td colspan="2" class="text-center">No available data.</td>
                                            </tr>                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade show" id="photo" role="tabpanel" aria-labelledby="photo-tab">                                    
                                    <!-- TABLE PHOTO -->
                                    <table id="table-photo" class="table table-striped datatable mt-3" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="50%">Photo Name</th>
                                                <th width="50%" class="text-center">File</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="no-available-data">
                                                <td colspan="2" class="text-center">No available data.</td>
                                            </tr>                                    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">                        
                        <a href="{{ route('productborrowing.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                            <b><i class="fas fa-times"></i></b>
                            Cancel
                        </a>
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
        var dataBorrowing = @json($data),
            siteID        = {{$data->site_id}},
            categoryID    = {{$data->product_category_id}},
            warehouseID   = {{$data->warehouse_id}}
            borrowingDate = '{{$data->date_borrowing}}',
            deletedAt     = '{{$data->deleted_at}}',
            borrowingStatus = dataBorrowing.status,
            borrowingStatus = deletedAt?'archived':borrowingStatus,
            statusBadge     = '';
        
        switch (borrowingStatus) {
            case 'draft':
                statusBadge = 'bg-gray';
                break;
            case 'waiting' : 
                statusBadge = 'badge-warning';
                break;
            case 'approved' : 
                statusBadge = 'badge-info';
                break;                
            case 'archived' : 
                statusBadge = 'bg-red';
                break;
            default:
                statusBadge = '';
        }

        $('#form-status').append(`<span class="badge ${statusBadge} text-sm" style="text-transform: capitalize;">${borrowingStatus}</span>`);

        $('.select2').select2({
            allowClear: true
        });  
        
        initInputFile();
        initDocuments();

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

        if(borrowingDate){
           $('#borrowing-date').data('daterangepicker').setStartDate(borrowingDate);
        }

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

        if(siteID){
            $('#site').select2('trigger','select',{
                data : {
                    id : siteID,
                    text : '{{$data->site_name}}'
                }
            });
        }

        if(warehouseID){
            $('#warehouse').select2('trigger','select',{
                data : {
                    id : warehouseID,
                    text : '{{$data->warehouse_name}}'
                }
            })
        }

        if(categoryID){
            $('#product-category').select2('trigger','select',{
                data : {
                    id : categoryID,
                    text : '{{$data->category_name}}'
                }
            });
        }

        $("#product").select2({
            ajax: {
                url: "{{route('product.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var productCategory = $('#product-category').select2('val');
                    return {
                        name: params.term,
                        product_category_id : productCategory,
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
                            uom_id : item.uom_id,
                            uom: item.uom,
                            product_category_id : item.product_category_id,
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

        $('#table-products').on('change','.qty-request', function() {
            var qty     = $(this).val();
            $(this).parents('.product-item').find('.item-product').attr('data-qty-request',qty);        
        });

        $('#table-products > tbody').on('click', '.remove', function() {
            $(this).closest('.product-item').remove();
            if ($('#table-products > tbody > .product-item').length == 0) {
                var html = `<tr class="no-available-data">
                                <td colspan="5" class="text-center">No available data.</td>
                            </tr>`;
                $('#table-products > tbody').append(html);
            }
        });

        $('#table-document > tbody').on('click', '.remove', function() {
            $(this).closest('tr').remove();
            if ($('#table-document > tbody > .document-item').length == 0) {
                var html = `<tr class="no-available-data">
                                <td colspan="3" class="text-center">No available data.</td>
                            </tr>`;
                $('#table-document > tbody').append(html);
            }
        });

        $('#table-photo > tbody').on('click', '.remove', function() {
            $(this).closest('tr').remove();
            if ($('#table-photo > tbody > .photo-item').length == 0) {
                var html = `<tr class="no-available-data">
                                <td colspan="3" class="text-center">No available data.</td>
                            </tr>`;
                $('#table-photo > tbody').append(html);
            }
        });

        $("#form").validate({
            rules: {
                borrowing_number: {
                    required: true
                },               
                borrowing_date: {
                    required: true
                },
                product_category: {
                    required: true
                },
                site : {
                    required: true
                },
                warehouse: {
                    required: true
                }
            },
            messages: {
                borrowing_number: {
                    required: 'This field is required.'
                },
                borrowing_date: {
                    required: 'This field is required.'
                },
                product_category: {
                    required: 'This field is required.'
                },
                site : {
                    required: 'This field is required.'
                },
                warehouse: {
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
                    issuedBy      = $('#form').find('#issued-by').select2('val'),
                    borrowingDate = $('#form').find('#borrowing-date').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                    products      = [],
                    documents     = [],
                    zeroValue     = false;
                
                $.each($('#table-products > tbody > .product-item'), function (index, value) { 
                     var product      = $(this).find('.item-product'),
                         product_id   = product.val(),
                         categoryID   = product.attr('data-category-id'),
                         uomID        = product.attr('data-uom-id'),
                         qtySystem    = product.attr('data-qty-system'),
                         qtyRequested = $(this).find('.qty-request').val();
                                         
                    products.push({
                        product_id      : product_id,
                        category_id     : categoryID,
                        uom_id          : uomID,
                        qty_system      : qtySystem,
                        qty_requested   : qtyRequested
                    });

                    if(qtyRequested == 0 || qtyRequested == ''){                        
                        zeroValue = true;
                        return false;
                    }

                });                

                $.each($('#table-document > tbody > .document-item').find('.doc-cell'), function (index, val) { 
                     var input    = $(this).parents('.document-item').find('.document-name'),
                         filename = input.val(),
                         docID    = input.attr('data-id'),
                         path     = $(this).attr('data-path');

                    documents.push({
                        id      : docID,
                        docName : filename,
                        type    : 'document',
                        path    : path
                    });
                });

                if(products.length == 0){
                    toastr.warning('Select product first.');
                    return false;
                }else if(zeroValue){
                    toastr.warning("Minimum value of qty borrowing is 1.");
                    return false
                }

                data.append('products',JSON.stringify(products));
                data.append('documents',JSON.stringify(documents));
                data.append('issuedby',issuedBy);
                data.append('dateborrowing',borrowingDate);

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
                        document.location = "{{route('productborrowing.index')}}";
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

    function initInputFile(){
        $('.custom-file-input').on('change', function() {
          let fileName = $(this).val().split('\\').pop();
          $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    }    

    function addProduct() {
        var product = $('#product').select2('data');

        if (product.length == 0) {
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
            table       = $('#table-products > tbody');

        if (table.find('.no-available-data').length > 0) {
            table.find('.no-available-data').remove();
        }

        var html = `<tr class="product-item">
                        <input type="hidden" class="item-product" value="${id}" data-category-id="${categoryID}" data-uom-id="${uomID}" data-qty-system="${qtySystem}" data-qty-request="0">
                        <td width="100">${productName}</td>
                        <td class="text-center" width="15">${uom}</td>
                        <td class="text-right" width="15">${qtySystem}</td>
                        <td class="text-center" width="15">
                            <input type="number" name="qty_request" class="form-control numberfield text-right qty-request" placeholder="0" readonly>
                        </td>
                        <td class="text-center" width="15">
                            <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;

        table.append(html);
        $('#product').val(null).trigger('change');
    }   

    function initDocuments() {
       var  dataProducts  = @json($data->products),
            dataFiles     = @json($data->files),
            dataImages    = @json($data->images); 
            
        // Init Products
        if(dataProducts.length > 0){
            var html  = ``,        
                table = $('#table-products > tbody');

            $.each(dataProducts, function (index, value) { 
                 var id          = value.product_id,
                     productName = value.product_name,
                     categoryID  = value.product_category_id,
                     uomID       = value.uom_id,
                     uom         = value.uom_name,
                     qtySystem   = value.qty_system,
                     qtyRequest  = value.qty_requested;

                 html += `<tr class="product-item">
                            <input type="hidden" class="item-product" value="${id}" data-category-id="${categoryID}" data-uom-id="${uomID}" data-qty-system="${qtySystem}" data-qty-request="0">
                            <td width="100">${productName}</td>
                            <td class="text-center" width="15">${uom}</td>
                            <td class="text-right" width="15">${qtySystem}</td>
                            <td class="text-right" width="15">${qtyRequest}</td>                            
                        </tr>`;            
            });

            table.find('.no-available-data').remove();
            table.append(html);
        }
        
        // Init Files
        if(dataFiles.length > 0){
            var html  = ``,
                table = $('#table-document > tbody');
            $.each(dataFiles, function (index, value) { 
                var id          = value.id,
                    borrowingID = value.product_borrowing_id,
                    docName     = value.document_name,
                    docFile     = value.file,
                    path        = `{{asset('assets/productborrowing/${borrowingID}/document/${docFile}')}}`;

                html += `<tr class="document-item">
                            <td>${docName}</td>
                            <td class="doc-cell">
                                <div class="input-group download">
                                    <a href="${path}" target="_blank">
                                        <b><i class="fas fa-download"></i></b> Download File
                                    </a>                                
                                </div>
                            </td>                            
                        </tr>`;            
            });            
            
            table.find('.no-available-data').remove();
            table.append(html);            
        }

        // Init Images
        if(dataImages.length > 0){
            var html  = ``,
                table = $('#table-photo > tbody');

            $.each(dataImages, function (index, value) { 
                var borrowingID = value.product_borrowing_id,
                    imageName   = value.document_name,
                    imageFile   = value.file,
                    path        = `assets/productborrowing/${borrowingID}/image/${imageFile}`;

                html += `<tr class="photo-item">
                            <td>${imageName}</td>
                            <td class="doc-cell">
                                <div class="input-group download">
                                    <a href="${path}" target="_blank">
                                        <b><i class="fas fa-download"></i></b> Download File
                                    </a>                                
                                </div>
                            </td>                            
                        </tr>`;            
            });            
            
            table.find('.no-available-data').remove();
            table.append(html);            
        }                
    }    

    const editFile = (that,table) => {        
         var parent     = '',
                element    = '';
            if(table == 'document'){
                parent  = 'document-item' ;
                element = `<div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="document[]" required>
                                    <label class="custom-file-label" for="exampleInputFile">Attach a file</label>
                                </div> 
                                <div class="input-group-prepend ml-1">
                                    <button class="btn btn-md text-xs btn-warning btn-flat legitRipplet" onclick="undoEdit($(this))" data-table="document" type="button"><i class="fas fa-undo"></i></button>
                                </div>                               
                            </div>`;
                                        
            }else if(table == 'photo'){
                parent  = 'photo-item';
                element = `<div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="photo[]" required>
                                    <label class="custom-file-label" for="exampleInputFile">Attach a file</label>                                
                                </div>                                
                                <div class="input-group-prepend ml-1">
                                    <button class="btn btn-md text-xs btn-warning btn-flat legitRipplet" onclick="undoEdit($(this))" data-table="photo" type="button"><i class="fas fa-undo"></i></button>
                                </div>
                            </div>`;
            }
            var cell = that.parents(`.${parent}`).children('.doc-cell');                
            cell.html(element);
    }

    const undoEdit = (that) => {
        var tableName  = that.attr('data-table'),
            parent     = '',
            path       = '';
        
        if(tableName == 'document'){
            parent = 'document-item';                        
            path = that.parents(`.${parent}`).attr('data-path');
        }else{
            parent = 'photo-item';
            path = that.parents(`.${parent}`).attr('data-path');
        }    
        
        var cell = that.parents(`.${parent}`).find('.doc-cell'),
            path = cell.attr('data-path'),
            element = `<div class="input-group download">
                                    <a href="${path}" target="_blank">
                                        <b><i class="fas fa-download"></i></b> Download File
                                    </a>                                
                                </div>`;
        cell.html(element);        
    }

    function initPath() {
        var docFiles = $('#table-document > tbody > .document-item').find('input[type=file]');
        
        docFiles.next().html(docFiles.val());

    }        

    function onSubmit(status) {
        $('#form').find('input[name=status]').val(status);
        $('form').first().trigger('submit');
    }
</script>
@endsection