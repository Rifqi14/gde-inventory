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
                                        <input type="text" class="form-control" name="borrowing_number" id="borrowing-number" placeholder="Borrowing Number" value="{{$data->borrowing_number}}" readonly>
                                    </div>                                    
                                    <!-- Borrowing Date -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="borrowing-date" class="control-label">Borrowing Date</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="datepicker" class="form-control datepicker text-right" name="borrowing_date" id="borrowing-date" placeholder="Borrowing Date" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="return-date" class="control-label">Return Date</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="datepicker" class="form-control datepicker text-right" name="return_date" id="return-date" placeholder="Return Date" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Status -->
                                    <div class="form-group" id="form-status">
                                        <label for="status" class="control-label">Status</label>
                                        <br>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Site -->
                                    <div class="form-group">
                                        <label for="site" class="control-label">Site</label>
                                        <select name="site" id="site" class="form-control select2" data-placeholder="Site" disabled>
                                        </select>
                                    </div>
                                    <!-- Warehouse -->
                                    <div class="form-group">
                                        <label for="warehouse" class="control-label">Warehouse</label>
                                        <select name="warehouse" id="warehouse" class="form-control select2" data-placeholder="Warehouse" disabled>
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
                                <label for="description" class="control-label">Purpose</label>
                                <textarea name="description" id="description" class="form-control summernote" rows="5" placeholder="Descripton" readonly></textarea>
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
                            <!-- PRODUCTS -->
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-products" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="100">Product</th>
                                            <th width="100">Product Category</th>
                                            <th width="15" class="text-center">Has Serial</th>
                                            <th width="15" class="text-center">UOM</th>
                                            <th width="15" class="text-right">Current Stock</th>
                                            <th width="10" class="text-right">Qty Borrowing</th>
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

<div class="modal fade" id="modal-serial">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="text-transform: capitalize;">Product Serial</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive p-0">
            <table id="table-serial" class="table table-striped datatable" data-product-id="" width="100%">
                <thead>
                    <tr>                            
                        <th width="50%">Product</th>
                        <th width="50%">Serial Number</th>                        
                    </tr>
                </thead>
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
    var dataSerial = [];
    var selectedProduct = [];
    var setDataSerial = [];

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
        var selectedProducts = [];
        var dataSerial       = [];

        var dataBorrowing   = @json($data),
            siteID          = {{$data->site_id}},
            warehouseID     = {{$data->warehouse_id}}
            borrowingDate   = '{{$data->date_borrowing}}',
            returnDate      = '{{$data->date_return}}',
            deletedAt       = '{{$data->deleted_at}}',
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

        if (returnDate) {
            $('#return-date').data('daterangepicker').setStartDate(returnDate);
        }

        $('.summernote').summernote({
            height: 150,
            toolbar: []
        });
        
        $('#description').summernote('disable');
        $('#description').summernote('code',@json($data->description));

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

        initInputFile();
        initDocuments();

        tableSerial = $('#table-serial').DataTable({
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
            data: setDataSerial,
            columnDefs: [                                                         
                {                    
                    render: function(data, type, row) {                        
                        return `<b>${data}</b>`;
                    },                    
                    width : "50%",
                    targets: [1]
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
                }
            ]
        });                     
    });

    function initInputFile(){
        $('.custom-file-input').on('change', function() {
          let fileName = $(this).val().split('\\').pop();
          $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    }         

    function initDocuments() {
       var  dataProducts  = @json($data->products),
            dataFiles     = @json($data->files),
            dataImages    = @json($data->images); 
        
        console.log({products : dataProducts});
            
        // Init Products
        if(dataProducts.length > 0){
            var html  = ``,        
                table = $('#table-products > tbody');

            $.each(dataProducts, function (index, value) { 
                var id          = value.product_id,
                    productName = value.product,
                    categoryID  = value.product_category_id,
                    category    = value.category,
                    uomID       = value.uom_id,
                    uom         = value.uom,
                    isSerial    = value.is_serial=='1'?true:false,                     
                    qtySystem   = value.qty_system,
                    qtyRequest  = value.qty_requested;

                switch (isSerial) {
                    case true:
                        badge = 'badge-info';
                        icon  = 'fas fa-check';
                        break;
                
                    default:
                        badge = 'bg-red';
                        icon  = 'fas fa-times';
                        break;
                }

                 html += `<tr class="product-item">
                            <input type="hidden" class="item-product" value="${id}" data-category-id="${categoryID}" data-uom-id="${uomID}" data-has-serial="${isSerial}" data-qty-system="${qtySystem}" data-qty-request="0">
                            <td width="100">${productName}</td>
                            <td width="100">${category}</td>
                            <td width="15" class="text-center"><span class="badge ${badge} text-md"><i class="${icon}" style="size: 2x;"></i></span></td>
                            <td class="text-center" width="15">${uom}</td>
                            <td class="text-right" width="15">${qtySystem}</td>
                            <td class="text-right" width="15">${qtyRequest}</td>
                            <td class="text-center" width="15">
                                <button class="btn btn-sm text-xs btn-warning btn-flat legitRipple ${isSerial==true?'':'disabled'}" type="button" onclick="showSerial(${id})"><i class="fas fa-bars"></i></button>                                
                            </td>
                        </tr>`;             
                
                if(isSerial == true){
                    var productName  = `<b>${productName}</b><p style="margin-top: 1px;">${category}</p>`;
                    var serials      = []; 
                        
                    $.each(value.serials, function (index, value) { 
                        serials.push({
                            serial_id     : value.serial_id,
                            serial_number : value.serial_number
                        });
                    });
                        
                    dataSerial.push({
                        product_id : id,
                        product    : productName,
                        serials    : serials 
                    });
                }
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

    function initPath() {
        var docFiles = $('#table-document > tbody > .document-item').find('input[type=file]');
        
        docFiles.next().html(docFiles.val());

    }        

    const showSerial = (productID) =>{                
        $('#table-serial').attr('data-product-id',productID?productID:'');        
        drawRemoveSerial(productID);
        $('#modal-serial').modal('show');
    }

    const drawRemoveSerial = (productID) => {
        setDataSerial = [];
        
        if(productID){
            var product = dataSerial.filter(param => param.product_id == productID);    
                                    
            $.each(product[0].serials, function (ind, val) { 
                setDataSerial.push([product[0].product,val.serial_number]);
            });   
        }
        
        tableSerial.clear().draw();
        tableSerial.rows.add(setDataSerial); // Add new data
        tableSerial.draw(); // Redraw the DataTable        

        return true;
    }    
</script>
@endsection