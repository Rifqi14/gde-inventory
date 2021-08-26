@extends('admin.layouts.app')
@section('title',$menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">Create {{$menu_name}}</h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{$parent_name}}</li>
            <li class="breadcrumb-item">{{$menu_name}}</li>
            <li class="breadcrumb-item">Create</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <form action="{{route('stockadjustment.store')}}" class="form-horizontal" id="form" role="form" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <h5 class="text-md text-dark text-uppercase">Adjusment Information</h5>
                                <hr>
                            </span>
                            <div class="row">
                                <!-- Adjusment number -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-12 col-xs-12" for="adjusment-number">Adjusment Number</label>
                                        <div class="controls col-md-12">
                                            <input type="text" class="form-control" id="adjusment-number" placeholder="Automatically Generated" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- Issued By -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="issued-by" class="control-label col-md-12 col-xs-12">Issued By</label>
                                        <div class="controls col-md-12">
                                            <input type="text" class="form-control" value="{{Auth::guard('admin')->user()->name}}" readonly>
                                            <input type="hidden" name="issuedby" value="{{Auth::guard('admin')->user()->id}}">
                                        </div>
                                    </div>
                                </div>
                                <!-- Adjusment Date -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-12 col-xs-12" for="date">Date</label>
                                        <div class="controls col-md-12">
                                            <div class="input-group">
                                                <input type="text" id="adjustment-date" class="form-control datepicker text-right" placeholder="Enter adjusment date">
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
                                        <select id="product-category" class="form-control select2" data-placeholder="Choose Product Category"></select>
                                    </div>
                                </div>
                                <!-- Site -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-12 col-xs-12" for="site">Site</label>
                                        <div class="controls col-md-12">
                                            <select class="form-control select2" name="site" id="site" data-placeholder="Choose Site"></select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Warehouse -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="warehouse" class="control-label col-md-12">Warehouse</label>
                                        <div class="controls col-md-12">
                                            <select name="warehouse" id="warehouse" class="form-control select2" data-placeholder="Choose Warehouse"></select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Status -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="status" class="control-label col-md-12 col-xs-12">Status</label>
                                        <div class="controls col-md-12">
                                            <p><span class="badge bg-gray text-sm">Draft</span> / <span class="badge badge-warning text-sm">Waiting Approval</span></p>
                                        </div>
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
                                <h5 class="text-md text-dark text-uppercase">Other Information</h5>
                                <hr>
                            </span>
                            <div class="form-group row">
                                <label for="description" class="control-label col-md-12 col-xs-12">Notes</label>
                                <div class="controls col-md-12">
                                    <textarea name="description" id="description" cols="30" rows="5" class="form-control summernote"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <h5 class="text-md text-dark text-uppercase">Product Information</h5>
                                <hr>
                            </span>
                            <div class="form-group">
                                <div class="mb-3">
                                    <select id="select-product" class="select2 form-control" data-placeholder="Choose Product"></select>
                                </div>
                                <button type="button" onclick="addProduct()" class="btn btn-outline-primary btn-block btn-labeled legitRipple text-sm">
                                    Add
                                </button>
                            </div>
                            <div class="form-group table-responsive">
                                <!-- Table Detail Product -->
                                <table class="table table-striped" id="table-product" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="200">Product Name</th>
                                            <th width="100">Product Category</th>
                                            <th width="50" class="text-center">Serial Number</th>
                                            <th width="30" class="text-center">UOM</th>
                                            <th width="30" class="text-right">Current Stock</th>
                                            <th width="30" class="text-right">Qty Adjusment</th>
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
                        <div class="card-footer text-right">
                            <input type="hidden" name="status" value="draft">
                            <button type="button" onclick="onSubmit('waiting')" class="btn bg-success btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-check-circle"></i></b>
                                Submit
                            </button>
                            <button type="button" onclick="onSubmit('draft')" class="btn bg-olive color-palette btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-save"></i></b>
                                Save
                            </button>
                            <a href="{{ route('stockadjustment.index') }}" class="btn btn-secondary color-palette btn-labeled legitRipple text-sm">
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

<div id="modal-serial" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="filter-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Serial</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-right">
                    <button type="button" class="btn btn-labeled  btn-success btn-sm btn-sm btn-flat legitRipple" onclick="addSerial()"><b><i class="fas fa-plus"></i></b> Add</button>
                </div>
                <div class="table-responsive p-0">
                    <table class="table table-striped" id="table-serial" width="100%">
                        <thead>
                            <tr>
                                <th width="200">Serial Number</th>
                                <th width="50" class="text-center">UOM</th>
                                <th width="10" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="no-available-data">
                                <td class="text-center" colspan="3">No available data.</td>
                            </tr>
                        </tbody>
                    </table>
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
    var choosenProduct = [];
    var dataProducts   = [];    
    var alreadyProduct = 0; // product id
    var dataSerial     = [];

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
        }).on('change', function() {
            var table = $('#table-product >  tbody');
            table.find('.product-item').remove();
            if (table.find('.no-available-data').length == 0) {
                table.append(`<tr class="no-available-data"><td colspan="7" class="text-center">No available data.</td></tr>`);
            }

            choosenProduct = [];
            $('#select-product').empty().trigger('change');
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
            escapeMarkup: function(text) {
                return text;
            },
        });

        $("#select-product").select2({
            ajax: {
                url: "{{ route('product.select') }}",
                type: "GET",
                dataType: "JSON",
                data: function(params) {
                    var categoryID = $("#product-category").find('option:selected').val();
                    var warehouseID = $("#warehouse").find('option:selected').val();

                    return {
                        name: params.term,
                        page: params.page,
                        product_category_id: categoryID,
                        warehouse_id: warehouseID,
                        products: choosenProduct,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id              : item.id,
                            text            : item.name,
                            category_id     : item.product_category_id,
                            category        : item.category,
                            uom_id          : item.uom_id,                            
                            uom             : item.uom,
                            sku             : item.sku,  
                            last_key        : item.last_serial, 
                            stock_warehouse : item.stock_warehouse?item.stock_warehouse:0,
                            has_serial      : item.is_serial
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
            if (data.category_id) {
                $("#product-category").select2('trigger', 'select', {
                    data: {
                        id: data.category_id,
                        text: `${data.category}`
                    }
                });
            }
            $(this).focus();
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
                    adjustmentDate = $('#form').find('#adjustment-date').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                    products = [],
                    zeroValue = false;

                $.each($('#table-product > tbody > .product-item'), function(index, value) {
                    var product     = $(this),
                        productID   = product.attr('data-product-id'),
                        uomID       = product.attr('data-uom-id'),
                        qtyBefore   = product.attr('data-qty-before'),
                        qtyAfter    = product.find('.qty-adjustment').val(),
                        serials     = null;


                    $.each(dataProducts, function (key, row) { 
                         if(row.product_id == productID){
                            serials = JSON.stringify(row.serials);
                         }
                    });

                    products.push({
                        product_id  : productID,                        
                        uom_id      : uomID,
                        serial      : serials,
                        qty_before  : qtyBefore,
                        qty_after   : qtyAfter
                    })

                });                

                if(products.length == 0){
                    toastr.warning('Select at least one product.');
                    return false;
                }

                data.append('products', JSON.stringify(products));
                data.append('adjustmentdate', adjustmentDate);

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
                        document.location = "{{route('stockadjustment.index')}}";
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
        var input = $('#select-product').select2('data');
        var table = $('#table-product').find('tbody');
        var warehouse = $("#warehouse").find('option:selected').val();

        if (!warehouse) {
            toastr.warning('Select warehouse first.');
            return false;
        }

        if (input.length == 0) {
            toastr.warning('Select product first.');
            return false;
        }

        var input        = input[0],
            productID    = input.id,
            product      = input.text,
            category     = input.category,
            uomID        = input.uom_id,
            uom          = input.uom,
            sku          = input.sku,
            lastSerial   = input.last_key,
            currentStock = input.stock_warehouse,
            isSerial     = input.has_serial == 1 ? true : false,
            qtyAdjusment = isSerial == true ? currentStock : 0,
            html         = '',
            serial       = '';

        if (isSerial) {
            icon  = 'fas fa-check';
            badge = 'badge-info';

            addDataProduct({
                product_id    : parseInt(productID),                            
                uom           : uom,    
                start_key     : lastSerial,
                last_key      : lastSerial,
                stockwareouse : currentStock,                            
                sku           : sku,
                serials       : []
            });            

        } else {
            icon  = 'fas fa-times';
            badge = 'bg-red';
        }

        console.log({dataProducts : dataProducts, selected: input});

        serial = `<span class="badge ${badge} text-md"><i class="${icon}" style="size: 2x;"></i></span>`;

        html = `<tr class="product-item" data-product-id="${productID}" data-product="${product}" data-uom-id="${uomID}" data-qty-before="${currentStock}">                                    
                  <td width="200">${product}</td>
                  <td width="100">${category}</td>
                  <td width="50" class="text-center">${serial}</td>
                  <td class="text-center" width="30">${uom}</td>
                  <td class="text-right" width="30">${currentStock}</td>
                  <td width="30">
                    <input type="number" class="form-control text-right qty-adjustment" value="${qtyAdjusment}" placeholder="Enter qty" style="width: 100%;" ${isSerial==true?'readonly':''}>
                  </td>                     
                  <td class="text-center" width="10">
                    <button class="btn btn-md text-xs btn-warning btn-flat legitRipple ${isSerial==false?'disabled':''}" type="button" onclick="showSerial(${productID})"><i class="fa fa-bars"></i></button>
                    <button class="btn btn-md text-xs btn-danger btn-flat legitRipple" type="button" onclick="removeProduct($(this),${productID})"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>`;

        if (table.find('.no-available-data').length > 0) {
            table.find('.no-available-data').remove();
        }

        table.append(html);

        choosenProduct.push(productID);
        $('#select-product').empty().trigger('change');
    }

    const removeProduct = (that, productID) => {
        that.closest('.product-item').remove();
        if ($('#table-product > tbody > .product-item').length == 0) {
            var html = `<tr class="no-available-data">
                            <td colspan="7" class="text-center">No available data.</td>
                        </tr>`;
            $('#table-product > tbody').append(html);
        }
        choosenProduct.splice($.inArray(productID, choosenProduct), 1);
        dataProducts.splice($.inArray(productID,dataProducts),1);
    }

    const showSerial = (productID) => {        
        alreadyProduct = productID;  
        drawTableSerial(productID);       
        $('#modal-serial').modal('show');        
    }   

    // Add data product on local storage 
    const addDataProduct = (data) => {        
        if(dataProducts.length > 0 ){
            $.each(dataProducts, function (index, value) { 
                 if(dataProducts.product_id !== value.product_id){
                     dataProducts.push(data);
                 }                 
            });
        }else{
            dataProducts.push(data);
        }                    
    }

    const addSerial = () => {        
        console.log({alreadyProduct :alreadyProduct});
        $.each(dataProducts, function (index, value) { 
             if(value.product_id == alreadyProduct){                 
                 var data    = value[index],
                     sku     = value.sku,
                     uom     = value.uom,
                     lastkey = value.last_key,                     
                     lastkey = lastkey+1;
                    
                     var number = sku+'-'+generateNumber(lastkey);
                
                     value.serials.push({                        
                        number : number,
                        uom    : uom,
                        key    : lastkey                   
                    });   
                    
                    value.last_key = lastkey;                                                
                    drawTableSerial(value.product_id);

                    var tableProduct = $('#table-product > tbody').find(`.product-item[data-product-id='${value.product_id}']`);
                    var qtyAdjust = tableProduct.find('.qty-adjustment');                                            
                    
                    qtyAdjust.val(parseInt(qtyAdjust.val())+1);    
             }                                  
        });                             
    }         
    
    const removeSerial = (that,productID,serialNumber) => {                
        $.each(dataProducts, function (index, value) { 
             if(value.product_id == productID){
                var serials   = value.serials;                                  
                serials.splice($.inArray(serialNumber,serials),1);                               

                that.closest('tr').remove();

                if(value.serials.length == 0){
                    value.last_key = value.start_key;
                }

                var tableProduct = $('#table-product > tbody').find(`.product-item[data-product-id='${value.product_id}']`);
                var qtyAdjust = tableProduct.find('.qty-adjustment');                                            
                    
                qtyAdjust.val(parseInt(qtyAdjust.val())-1);                    
             }
        }); 

        var table = $('#table-serial > tbody');

        if(table.find('tr.serial-number').length == 0){            
            table.append(`<tr class="no-available-data">
                            <td class="text-center" colspan="3">No available data.</td>
                        </tr>`);
        }        
    }
    
    // Generating Serial Number then returned to addSerial Function
    const generateNumber = (number) => {
        var number = number.toString();
        var length = 4-number.length;        
        
        switch (length) {
            case 3:
                numberChar = '000';
                break;
            case 2: 
                numberChar = '00';
                break;
            case 1: 
                numberChar = '0';
                break;
            default:
                numberChar = '';
                break;
        }
                
        numberChar = numberChar + number;

        return numberChar;
    }   

    const drawTableSerial = (productID) => {
        var html = '';
        var table = $('#table-serial > tbody');

        table.find('tr').remove();

        $.each(dataProducts, function (index, value) { 
             if(value.product_id == productID){                  
                 if(value.serials.length == 0){
                    html += `<tr class="no-available-data">
                                <td class="text-center" colspan="3">No available data.</td>
                            </tr>`;
                 }else{
                    $.each(value.serials, function (ind, val) { 
                        var number = val.number,
                            uom    = val.uom;

                        html += `<tr class="serial-number">
                                    <td class="text-bold">${number}</td>
                                    <td class="text-center">${uom}</td>
                                    <td class="text-center">
                                        <button class="btn btn-md text-xs btn-danger btn-flat legitRipple" type="button" onclick="removeSerial($(this),${value.product_id},'${number}')"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>`;
                    });                                                       
                 }
             }
        });                                    

        table.html(html);   
    }

    const onSubmit = (status) => {
        $('input[name=status]').val(status);
        $('#form').trigger('submit');
    }
</script>
@endsection