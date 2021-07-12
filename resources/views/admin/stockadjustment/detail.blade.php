@extends('admin.layouts.app')
@section('title',$menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">Detail {{$menu_name}}</h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{$parent_name}}</li>
            <li class="breadcrumb-item">{{$menu_name}}</li>
            <li class="breadcrumb-item">Detail</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <form action="{{route('stockadjustment.update',['id' => $data->id])}}" class="form-horizontal" id="form" role="form" enctype="multipart/form-data">            
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
                                            <input type="text" class="form-control" id="adjusment-number" placeholder="Automatically Generated" value="{{$data->adjustment_number}}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- Issued By -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="issued-by" class="control-label col-md-12 col-xs-12">Issued By</label>
                                        <div class="controls col-md-12">
                                            <input type="text" class="form-control" value="{{$data->issuedby}}" readonly>                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- Adjusment Date -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-12 col-xs-12" for="date">Date</label>
                                        <div class="controls col-md-12">
                                            <div class="input-group">
                                                <input type="text" id="adjustment-date" class="form-control datepicker text-right" placeholder="Enter adjusment date" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                                <!-- Site -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-12 col-xs-12" for="site">Site</label>
                                        <div class="controls col-md-12">
                                            <select class="form-control select2" name="site" id="site" data-placeholder="Choose Site" disabled></select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Warehouse -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="warehouse" class="control-label col-md-12">Warehouse</label>
                                        <div class="controls col-md-12">
                                            <select name="warehouse" id="warehouse" class="form-control select2" data-placeholder="Choose Warehouse" disabled></select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Status -->
                                <div class="col-md-6">
                                    <div class="form-group row form-status">
                                        <label for="status" class="control-label col-md-12 col-xs-12">Status</label>
                                        <div class="controls col-md-12">                                            
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
                            <div class="form-group table-responsive">
                                <!-- Table Detail Product -->
                                <table class="table table-striped" id="table-product" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="200">Product Name</th>
                                            <th width="100">Product Category</th>
                                            <th width="50" class="text-center">Serial Number</th>
                                            <th width="30" class="text-center">UOM</th>
                                            <th width="30" class="text-right">Qty Before</th>                                            
                                            <th width="30" class="text-right">Qty After</th>                                            
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
@endsection

@section('scripts')
<script>
    var choosenProduct = [];

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
            toolbar: []
        });

        $('#description').summernote('disable');

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
                            id: item.id,
                            text: item.name,
                            category_id: item.product_category_id,
                            category: item.category,
                            uom_id: item.uom_id,
                            uom: item.uom,
                            stock_warehouse: item.qty_on_warehouse ? item.qty_on_warehouse : 0,
                            has_serial: item.is_serial
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

        initData();    

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
                        qtyAfter    = product.find('.qty-adjustment').val();

                    products.push({
                        product_id: productID,
                        uom_id: uomID,
                        qty_before: qtyBefore,
                        qty_after: qtyAfter
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
                        message = response.message ? response.message : 'Failed to update data.';

                    toastr.warning(message);
                    console.log({
                        errorMessage: message
                    });
                })
            }
        });
    });    

    const initData = () => {
        var siteID         = @json($data->site_id);
        var warehouseID    = @json($data->warehouse_id);
        var adjustmentDate = '{{$data->date_adjustment}}';
        var products       = @json($data->product);
        var adjustmentDate = '{{$data->date_adjustment}}';
        var status         = '{{$data->status}}';      
        
        
        $('#description').summernote('code',@json($data->description));

        if(siteID){
            $('#site').select2('trigger','select',{
                data:{
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

        if(adjustmentDate){
            var date = $('#adjustment-date').data('daterangepicker');
            date.setStartDate(adjustmentDate);
            date.setEndDate(adjustmentDate);
        }


        switch (status) {
            case 'draft':
                status = status;
                badge  = 'bg-gray';
                break;
            case 'waiting':
                status = 'Waiting Approval';
                badge  = 'badge-warning';
                break;
            case 'approved':
                status = status;
                badge  = 'badge-info';
                break;
            case 'rejected':
                status = status;
                badge  = 'bg-red';
                break;
            default:
                status = '';
                badge  = '';
                break;
        }

        $('.form-status').find('.controls').append(`<span class="badge ${badge} text-sm" style="text-transform: capitalized;">${status}</span>`);       

        if (products.length > 0) {
            var html = '';            

            $.each(products, function (index, value) { 
                var productID    = value.product_id,
                    product      = value.product,
                    category     = value.category,
                    uomID        = value.uom_id,
                    uom          = value.uom,
                    qtyBefore    = value.qty_before?parseInt(value.qty_before):0,
                    qtyAfter     = value.qty_after?parseInt(value.qty_after):qtyBefore,
                    currentStock = qtyAfter,                    
                    isSerial     = value.is_serial==1?true:false,
                    qtyAdjusment = isSerial?qtyAfter:0,
                    serial       = '';


                if (isSerial) {
                    icon = 'fas fa-check';
                    badge = 'badge-info';
                } else {
                    icon = 'fas fa-times';
                    badge = 'bg-red';
                }

                serial = `<span class="badge ${badge} text-md"><i class="${icon}" style="size: 2x;"></i></span>`;

                html += `<tr class="product-item" data-product-id="${productID}" data-product="${product}" data-uom-id="${uomID}" data-qty-before="${currentStock}">                                    
                            <td width="200">${product}</td>
                            <td width="100">${category}</td>
                            <td width="50" class="text-center">${serial}</td>
                            <td class="text-center" width="30">${uom}</td>
                            <td class="text-right" width="30">${qtyBefore}</td>                                                 
                            <td class="text-right" width="30">${qtyAfter}</td>                            
                        </tr>`;

                choosenProduct.push(productID);
            });

            var table = $('#table-product').find('tbody');

            if (table.find('.no-available-data').length > 0) {
                table.find('.no-available-data').remove();
            }

            table.append(html);
        }                
    }

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

        var input = input[0],
            productID = input.id,
            product = input.text,
            category = input.category,
            uomID = input.uom_id,
            uom = input.uom,
            currentStock = input.stock_warehouse,
            isSerial = input.has_serial == 1 ? true : false,
            qtyAdjusment = isSerial == true ? currentStock : 0,
            html = '',
            serial = '';

        if (isSerial) {
            icon = 'fas fa-check';
            badge = 'badge-info';
        } else {
            icon = 'fas fa-times';
            badge = 'bg-red';
        }

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
                    <button class="btn btn-md text-xs btn-warning btn-flat legitRipple" type="button" onclick=""><i class="fa fa-bars"></i></button>
                    <button class="btn btn-md text-xs btn-danger btn-flat legitRipple" type="button" onclick="removeProduct($(this),${productID})"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>`;

        if (table.find('.no-available-data').length > 0) {
            table.find('.no-available-data').remove();
        }

        table.append(html);

        choosenProduct.push(productID);
        $('#select-product').val(null).trigger('change');
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
    }

    const onSubmit = (status) => {
        $('input[name=status]').val(status);
        $('#form').trigger('submit');
    }
</script>
@endsection