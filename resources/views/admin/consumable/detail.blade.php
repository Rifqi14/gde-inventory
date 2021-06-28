@extends('admin.layouts.app')
@section('title',@$menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">{{ @$menu_name }}</h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{ @$menu_parent }}</li>
            <li class="breadcrumb-item">{{ @$menu_name }}</li>
            <li class="breadcrumb-item">Detail</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <form action="{{route('consumable.update',['id' => $data->id])}}" id="form" method="POST" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr>
                                <h5 class="text-md text-dark text-uppercase">{{ @$menu_name }} Information</h5>
                            </span>
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Consumable Number -->
                                    <div class="form-group">
                                        <label for="consumable_number">{{ @$menu_name }} Number</label>
                                        <input type="text" id="consumable_number" class="form-control" value="{{$data->consumable_number}}" placeholder="Automatically generated" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- Site -->
                                    <div class="form-group">
                                        <label for="site">Site</label>
                                        <select name="site" id="site" class="form-control select2" data-placeholder="Choose Site" disabled>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- Product Category -->
                                    <div class="form-group">
                                        <label for="product_category_id">Product Category</label>
                                        <select name="product_category" id="product-category-id" class="form-control select2" data-placeholder="Choose Product Category" disabled>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- Warehouse -->
                                    <div class="form-group">
                                        <label for="warehouse">Warehouse</label>
                                        <select name="warehouse" id="warehouse" class="form-control select2" data-placeholder="Choose Warehouse" disabled>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- Date Issued -->
                                    <div class="form-group">
                                        <label for="date_issued">Date Issued</label>
                                        <input type="datepicker" name="date_issued" id="date-issued" class="form-control datepicker text-right" placeholder="Date Issued" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- Status -->
                                    <div class="form-group form-status">
                                        <label for="status">Status</label>                                    
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
                            <!-- Issued Name -->
                            <div class="form-group">
                                <label for="issued_by">Issued By</label>
                                <input type="hidden" name="issued_by" id="issued_by" class="form-control" value="{{ Auth::guard('admin')->user()->id }}">
                                <input type="text" name="issued_by_preview" id="issued_by_preview" class="form-control" value="{{ Auth::guard('admin')->user()->name }}" disabled>
                            </div>
                            <!-- Description -->
                            <div class="form-group">
                                <label for="description" class="control-label">Description</label>
                                <textarea name="description" id="description" rows="4" class="form-control summernote" placeholder="Description">{{$data->description}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr>
                                <h5 class="text-md text-dark text-uppercase">Product Information</h5>
                            </span>                            
                            <table id="table-products" class="table table-striped datatable" width="100%">
                                <thead>
                                    <tr>
                                        <th width="200">Product Name</th>
                                        <th width="15" class="text-center">UOM</th>
                                        <th width="15" class="text-center">Qty System</th>
                                        <th width="10" class="text-center">Qty Consume</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="no-available-data">
                                        <td colspan="5" class="text-center">No available data.</td>
                                    </tr>
                                </tbody>
                            </table>
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
                            <ul class="nav nav-tabs" id="suppDocumentTab" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active pl-4 pr-4" id="document-tab" data-toggle="tab" data-target="#document" role="tab" aria-controls="document" aria-selected="true"><b>Document</b></button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link pl-4 pr-4" id="photo-tab" data-toggle="tab" data-target="#photo" type="button" role="tab" aria-controls="photo" aria-selected="false"><b>Photo</b></button>
                                </li>
                            </ul>
                            <div class="tab-content" id="suppDocumentTabContent">
                                <!-- Document -->
                                <div class="tab-pane fade show active" id="document" role="tabpanel" aria-labelledby="document-tab">                                    
                                    <table id="table-document" class="table table-striped datatable" width="100%">
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
                                <!-- Photo -->
                                <div class="tab-pane fade show" id="photo" role="tabpanel" aria-labelledby="photo-tab">                                    
                                    <table id="table-photo" class="table table-striped datatable" width="100%">
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
                        <div class="card-footer text-right">                            
                            <a href="{{ route('consumable.index') }}" class="btn btn-secondary color-palette btn-labeled legitRipple text-sm">
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
    };

    var deletedDoc = [];

    $(function() {
        var getData     = @json($data),
            siteID      = {{$data->site_id}},
            warehouseID = {{$data->warehouse_id}},
            consumeDate = '{{$data->date_consumable}}',
            state       = '{{$data->status}}';

        initData();
        
        $('.summernote').summernote({
            height: 120,
            toolbar: []
        });
        $('.summernote').summernote('disable');

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

        $("#site").select2({
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
                    var siteID = $('#site').find('option:selected').val();
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
                $('#site').select2('trigger', 'select', {
                    data: {
                        id: data.site_id,
                        text: `${data.site}`
                    }
                });
            }
        });

        if(state){
            var badge = '';
            switch (state) {                            
                case 'waiting':
                    badge = 'badge-warning';
                    state = 'Waiting Approval';
                    break;
                case 'approved' :
                    badge = 'badge-info';                    
                    break;        
                case 'rejected' :
                    badge  : 'bg-red';                
                default:
                    state = 'draft';
                    badge = 'badge-secondary';
                    break;
            }
            $('input[name=status]').val(state);
            $('.form-status').append(`<span class="badge ${badge} text-sm" style="text-transform: capitalize;">${state}</span>`);
        }

        if (consumeDate) {
            $('#date-issued').data('daterangepicker').setStartDate(`${consumeDate}`);
            $('#date-issued').data('daterangepicker').setEndDate(`${consumeDate}`);
        }

        if(siteID){
            $('#site').select2('trigger','select',{
                data : {
                    id   : siteID,
                    text : `{{$data->site}}`
                }
            });
        }

        if (warehouseID) {
            $('#warehouse').select2('trigger','select',{
                data : {
                    id   : warehouseID,
                    text : `{{$data->warehouse}}`   
                }
            });
        }

        $("#product-category-id").select2({
            ajax: {
                url: "{{ route('productcategory.select') }}",
                type: "GET",
                dataType: "JSON",
                data: function(params) {
                    return {
                        site_id: $('#site_id').find('option:selected').val(),
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
        });

        $("#product").select2({
            ajax: {
                url: "{{route('product.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var productCategory = $('#product-category').select2('val');
                    var products = [];

                    $.each($('#table-products > tbody > .product-item'), function(index, value) {
                        var product = $(this).find('.item-product'),
                            product_id = product.val();

                        products.push(product_id);

                    });
                    return {
                        name: params.term,
                        product_category_id: productCategory,
                        page: params.page,
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

        $('#table-products').on('change','.qty-consume',function(){
            var qty = $(this) .val();
            $(this).parents('.product-item').find('.item-product').attr('data-qty-consume',qty);
        });

        $('#table-products').on('keyup','.qty-consume',function(){
            var qty = $(this) .val();
            $(this).parents('.product-item').find('.item-product').attr('data-qty-consume',qty);
        });

        $("#form").validate({
            rules: {
                site: {
                    required: true
                },
                warehouse: {
                    required: true
                }
            },
            messages: {
                site: {
                    required: 'This field is required.'
                },
                warehouse: {
                    required: 'This field is required.'
                }
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);

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
                var data        = new FormData($('#form')[0]),                    
                    consumeDate = $('#form').find('#date-issued').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                    documents   = [],
                    undocuments = [],
                    products    = [],
                    zeroValue   = false;

                    $.each($('#table-products > tbody > .product-item'), function (index, value) { 
                        var product      = $(this).find('.item-product'),
                            product_id   = product.val(),
                            categoryID   = product.attr('data-category-id'),
                            uomID        = product.attr('data-uom-id'),
                            qtySystem    = product.attr('data-qty-system'),
                            qtyConsume   = $(this).find('.qty-consume').val();

                        products.push({
                            product_id      : product_id,
                            category_id     : categoryID,
                            uom_id          : uomID,
                            qty_system      : qtySystem,
                            qty_consume      : qtyConsume
                        });

                        if(qtyConsume == 0 || qtyConsume == ''){                        
                            zeroValue = true;
                            return false;
                        }

                    });

                    $.each($('#table-document > tbody > .document-item').find('.doc-cell'), function (index, val) { 
                        var input      = $(this).parents('.document-item').find('.document-name'),
                            filename   = input.val(),
                            docID      = input.attr('data-id'),
                            consumeID  = input.attr('data-consume-id'),
                            file       = input.attr('data-file');

                        documents.push({
                            id          : docID,
                            consumeID   : consumeID,
                            docName     : filename,
                            type        : 'document',
                            file        : file
                        });
                    });   

                    $.each($('#table-photo > tbody > .photo-item').find('.doc-cell'), function (index, val) { 
                        var input      = $(this).parents('.photo-item').find('.document-name'),
                            filename   = input.val(),
                            docID      = input.attr('data-id'),
                            consumeID  = input.attr('data-consume-id'),
                            file       = input.attr('data-file');

                        documents.push({
                            id          : docID,
                            consumeID   : consumeID,
                            docName     : filename,
                            type        : 'image',
                            file        : file
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
                data.append('undocuments',JSON.stringify(deletedDoc));
                data.append('consumedate', consumeDate);                

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
                        document.location = "{{route('consumable.index')}}";
                    } else {
                        toastr.warning(`${response.message}`);
                    }
                    return;
                }).fail(function(response) {
                    $('body').unblock();
                    var response = response.responseJSON,
                        message = response.message ? response.message : 'Failed to update data.';

                    toastr.warning(message);
                    console.log({
                        errorMessage: message
                    });
                })
            }
        });


    });

    const initInputFile = () => {
        $('.custom-file-input').on('change', function() {
          let fileName = $(this).val().split('\\').pop();
          $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    }

    const initData = () => {
        var products = @json($data->products);
        var files    = @json($data->files);
        var images   = @json($data->images);        

        if(products.length > 0){
            var html    = '',
                table   = $('#table-products > tbody');

            $.each(products, function (index, value) { 
                var id           = value.product_id,
                     productName = value.product_name,
                     categoryID  = value.product_category_id,
                     uomID       = value.uom_id,
                     uom         = value.uom_name,
                     qtySystem   = value.qty_system,
                     qtyConsume  = value.qty_consume;
                     
                    html += `<tr class="product-item">
                                <input type="hidden" class="item-product" value="${id}" data-category-id="${categoryID}" data-uom-id="${uomID}" data-qty-system="${qtySystem}" data-qty-consume="${qtyConsume}">
                                <td width="100">${productName}</td>
                                <td class="text-center" width="15">${uom}</td>
                                <td class="text-center" width="15">${qtySystem}</td>
                                <td class="text-center" width="15">${qtyConsume}</td>                                
                            </tr>`;
            });

            table.find('.no-available-data').remove();
            table.append(html);
        }        

        if(files.length > 0){
            var html  = '',
                table = $('#table-document > tbody');

            $.each(files, function (index, value) { 
                var id         = value.id,
                    consumeID  = value.product_consumable_id,
                    filename   = value.document_name,
                    file       = value.file,
                    path       = `{{asset('assets/consumable/${consumeID}/document/${file}')}}`;

                html += `<tr class="document-item">
                            <td>${filename}</td>
                            <td class="doc-cell text-center">                       
                                <a href="${path}" target="_blank">
                                   <b><i class="fas fa-download"></i></b> Download File
                                </a>                             
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
                    consumeID  = value.product_consumable_id,
                    filename   = value.document_name,
                    file       = value.file,
                    path       = `{{asset('assets/consumable/${consumeID}/image/${file}')}}`;

                html += `<tr class="photo-item">
                            <td>${filename}</td>
                            <td class="doc-cell text-center">          
                                <a href="${path}" target="_blank">
                                   <b><i class="fas fa-download"></i></b> Download File
                                </a>                      
                            </td>                            
                        </tr>`;
            });

            table.find('.no-available-data').remove();
            table.append(html);
        }

    }

    const addProduct = () => {
        var product = $('#product').select2('data');

        if (product.length == 0) {
        toastr.warning('Select the product first.');
        return false;
        }

        product = product[0];
        var id = product.id,
        productName = product.text,
        categoryID = product.product_category_id,
        uomID = product.uom_id,
        uom = product.uom,
        qtySystem = product.qty_system,
        table = $('#table-products > tbody');

        if (table.find('.no-available-data').length > 0) {
        table.find('.no-available-data').remove();
        }

        var html = `<tr class="product-item">
                            <input type="hidden" class="item-product" value="${id}" data-category-id="${categoryID}" data-uom-id="${uomID}" data-qty-system="${qtySystem}" data-qty-consume="0">
                            <td width="100">${productName}</td>
                            <td class="text-center" width="15">${uom}</td>
                            <td class="text-center" width="15">${qtySystem}</td>
                            <td class="text-center" width="15">
                                <input type="number" name="qty_consume" class="form-control numberfield text-right qty-consume" placeholder="0" min="0" max="${qtySystem}" data-qty_system="${qtySystem}" required>
                            </td>
                            <td class="text-center" width="15">
                                <button class="btn btn-md text-xs btn-danger btn-flat legitRipple" onclick="removeProduct($(this))" type="button"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>`;

        table.append(html);
        $('#product').val(null).trigger('change');
    }

    const removeProduct = (that) => {
        that.closest('.product-item').remove();
        if ($('#table-products > tbody > .product-item').length == 0) {
        var html = `<tr class="no-available-data">
                            <td colspan="5" class="text-center">No available data.</td>
                        </tr>`;
        $('#table-products > tbody').append(html);
        }
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
            consumeID  = document.attr('data-consume-id'),
            type       = document.attr('data-type');

        that.closest('.document-item').remove();
        if ($('#table-document > tbody > .document-item').length == 0) {
            var html = `<tr class="no-available-data">
                                        <td colspan="3" class="text-center">No available data.</td>
                                    </tr>`;
            $('#table-document > tbody').append(html);
        }
        

        if(id){
            deletedDoc.push({
                id : id,            
                path : `assets/consumable/${consumeID}/document/${file}`
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
            consumeID  = document.attr('data-consume-id'),
            type       = document.attr('data-type');

        that.closest('.photo-item').remove();
        if ($('#table-photo > tbody > .photo-item').length == 0) {
        var html = `<tr class="no-available-data">
                                    <td colspan="3" class="text-center">No available data.</td>
                                </tr>`;
        $('#table-photo > tbody').append(html);
        }

        if(id){
            deletedDoc.push({
                id : id,            
                path : `assets/consumable/${consumeID}/image/${file}`
            });
        }
    }   
</script>
@endsection