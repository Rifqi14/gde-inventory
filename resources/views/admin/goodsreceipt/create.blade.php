@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

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
<section id="content" class="content">
  <div class="container-fluid">
    <form action="{{ route('goodsreceipt.store') }}" id="form" role="form" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-uppercase">{{ $menu_name }} Information</h5>
              </span>
              <div class="row">
                <!-- Receipt Number -->
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="receipt-number" class="col-md-12 col-xs-12 control-label">Receipt Number</label>
                    <div class="col-sm-12 controls">
                      <input type="text" id="receipt-number" class="form-control" placeholder="Automatically generated" readonly>
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
                <!-- Receipt Date -->
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="receipt_date" class="col-md-12 col-xs-12 control-label">Receipt Date</label>
                    <div class="col-sm-12 controls">
                      <div class="input-group">
                        <input type="text" name="receipt_date" id="receipt-date" class="form-control datepicker text-right" placeholder="Enter receipt date">
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
                <!-- Unit -->
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="site" class="col-md-12 col-xs-12 control-label">Site</label>
                    <div class="col-sm-12 controls">
                      <select name="site" id="site" class="form-control" data-placeholder="Choose site"></select>
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
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-uppercase">Other Information</h5>
              </span>              
              <div class="form-group row">
                <label for="description" class="col-md-12 col-xs-12 control-label">Description</label>
                <div class="col-sm-12 controls">
                  <textarea class="form-control summernote" name="description" id="description" rows="4" placeholder="Description"></textarea>
                </div>
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
                      <th width="40" class="text-center" style="white-space: nowrap;">Qty Order</th>
                      <th width="40" class="text-center" style="white-space: nowrap;">Qty Receipt</th>
                      <th width="100">Rack</th>
                      <th width="100">Bin</th>
                      <th width="15" class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="no-available-data">
                      <td colspan="9" class="text-center">No available data.</td>
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
              <a href="{{ route('goodsreceipt.index') }}" class="btn btn-secondary color-palette btn-labeled legitRipple text-sm">
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
              <li class="nav-item contract-reference">
                <a href="#add-contract-reference" class="nav-link active" id="add-contract-reference-tab" data-toggle="pill" role="tab" aria-controls="add-contract-reference" aria-selected="false">Contract</a>
              </li>
              <li class="nav-item transfer-reference">
                <a href="#add-transfer" class="nav-link" id="add-transfer-tab" data-toggle="pill" role="tab" aria-controls="add-transfer" aria-selected="false">Product Transfer</a>
              </li>
              <li class="nav-item borrowing-reference">
                <a href="#add-return" class="nav-link" id="add-return-tab" data-toggle="pill" role="tab" aria-controls="add-return" aria-selected="false">Product Borrowing</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="reference-tab">
              <div class="tab-pane fade show active table-responsive" id="add-contract-reference" role="tabpanel" aria-labelledby="add-contract-reference-tab">
                <table id="table-contract" class="table table-striped datatable" width="100%">
                  <thead>
                    <tr>
                      <th width="3%" class="text-center">No</th>
                      <th width="5%" class="text-center">Date</th>                      
                      <th width="30%">Product</th>
                      <th width="50%">Product Category</th>
                      <th width="5%" class="text-center">Has Serial</th>
                      <th width="10%" class="text-center">UOM</th>
                      <th width="5%" class="text-right">Qty</th>                      
                      <th width="5%" class="text-center">Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
              <div class="tab-pane fade show active table-responsive" id="add-transfer" role="tabpanel" aria-labelledby="add-transfer-tab">
                <table id="table-transfer" class="table table-striped datatable" width="100%">
                  <thead>
                    <tr>
                      <th width="3%" class="text-center">No</th>
                      <th width="5%" class="text-center">Date</th>                      
                      <th width="30%">Product</th>
                      <th width="50%">Product Category</th>
                      <th width="5%" class="text-center">Has Serial</th>
                      <th width="10%" class="text-center">UOM</th>
                      <th width="5%" class="text-right">Qty</th>                      
                      <th width="5%" class="text-center">Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
              <div class="tab-pane fade show table-responsive" id="add-return" role="tabpanel" aria-labelledby="add-return-tab">
                <table id="table-borrowing" class="table table-striped datatable" width="100%">
                  <thead>
                    <tr>
                      <th width="3%" class="text-center">No</th>
                      <th width="5%" class="text-center">Date</th>                      
                      <th width="30%">Product</th>
                      <th width="30%">Product Category</th>
                      <th width="5%" class="text-center">Has Serial</th>
                      <th width="10%" class="text-center">UOM</th>
                      <th width="5%" class="text-right">Qty</th>                      
                      <th width="5%" class="text-center">Action</th>
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
                <table id="table-select-serial" class="table table-striped datatable" width="100%" data-product-id="" data-detail-id="">
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
                <table id="table-selected-serial" class="table table-striped datatable" data-product-id="" data-detail-id="" width="100%">
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
  var receiptProduct = [];
  var dataSerials    = [];
  var setDataSerial  = [];

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
    initSelect2();

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
    });

    contractTable = $('#table-contract').DataTable({
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
        url: "{{route('goodsreceipt.contractproducts')}}",
        type: "GET",
        data: function(data) {
          var exception = [];
          var item      = receiptProduct.filter(param => param.type == 'contract');

          $.each(item, function (index, value) { 
             exception.push(value.product_id);
          });
          
          data.except      = exception;
          data.category_id = $('#form').find('#product-category').find('option:selected').val();
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
            return `<p>${row.product}<br><b>${row.contract_number}</b></p>`;
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
            var referenceID   = row.contract_id,
                reference     = row.contract_number,
                productID     = row.product_id,
                product       = row.product,  
                detailID      = row.detail_id,
                sku           = row.sku,            
                isSerial      = row.is_serial,   
                last_key      = row.last_serial,           
                uomID         = row.uom_id,
                order         = row.qty ? row.qty : 0;            

            return `<button class="btn btn-sm text-xs btn-success btn-flat legitRipple btn-add-product" onclick="addProduct($(this),'contract')" type="button" data-reference-id="${referenceID}" data-reference="${reference}" data-product-id="${productID}" data-product="${product}" data-sku="${sku}" data-serial="${isSerial}" data-uom-id="${uomID}" data-detail-id="${detailID}" data-order="${order}" data-last-key="${last_key}">
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
          data: "signing_date"
        },        
        {
          data: "product"
        },
        {
          data: "category",
          className: 'product-category'
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
        url: "{{route('goodsreceipt.transferproducts')}}",
        type: "GET",
        data: function(data) {                    
          var exception = [];
          // var item      = receiptProduct.filter(param => param.type == 'transfer');

          // $.each(item, function (index, value) { 
          //    exception.push(value.detail_id);
          // });
          
          data.except       = exception;
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
            var referenceID   = row.reference_id,
                reference     = row.transfer_number,
                detailID      = row.detail_id,
                productID     = row.product_id,
                product       = row.product,
                category      = row.category,
                isSerial      = row.is_serial,
                uomID         = row.uom_id,
                order         = row.qty ? row.qty : 0;                

            return `<button class="btn btn-sm text-xs btn-success btn-flat legitRipple btn-add-product" onclick="addProduct($(this),'borrowing')" type="button" data-reference-id="${referenceID}" data-detail-id="${detailID}" data-reference="${reference}" data-product-id="${productID}" data-product="${product}" data-serial="${isSerial}" data-uom-id="${uomID}" data-order="${order}">
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
          data: "product"
        },
        {
          data: "category",
          className: 'product-category'
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
        url: "{{route('goodsreceipt.borrowingproducts')}}",
        type: "GET",
        data: function(data) {                    
          var exception = [];
          var item      = receiptProduct.filter(param => param.type == 'borrowing');

          $.each(item, function (index, value) { 
             exception.push(value.detail_id);
          });
          
          data.except       = exception;
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
            var referenceID   = row.reference_id,
                reference     = row.borrowing_number,
                detailID      = row.detail_id,
                productID     = row.product_id,
                product       = row.product,
                category      = row.category,
                isSerial      = row.is_serial,
                uomID         = row.uom_id,
                order         = row.qty ? row.qty : 0;                

            return `<button class="btn btn-sm text-xs btn-success btn-flat legitRipple btn-add-product" onclick="addProduct($(this),'transfer')" type="button" data-reference-id="${referenceID}" data-detail-id="${detailID}" data-reference="${reference}" data-product-id="${productID}" data-product="${product}" data-serial="${isSerial}" data-uom-id="${uomID}" data-order="${order}">
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
          data: "product"
        },
        {
          data: "category",
          className: 'product-category'
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
            [0, "asc"]
        ],
        ajax: {
            url: "{{route('goodsreceipt.readserial')}}",
            type: "GET",
            data: function(data) { 
                var detailID   =  $('#table-select-serial').attr('data-detail-id');
                var productID  =  $('#table-select-serial').attr('data-product-id');                                             
                var except     = [];

                var product = receiptProduct.filter(param => param.detail_id == detailID && param.product_id == productID);                

                if(product.length > 0 && product[0].serials.length > 0){
                  $.each(product[0].serials, function (index, value) { 
                    except.push(value.serial_id);
                  });
                }                

                data.detail_id  = detailID;
                data.product_id = productID;
                data.except     = except;         
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
                    return `<button class="btn btn-sm text-xs btn-success btn-flat legitRipple" onclick="selectSerial($(this))" data-detail-id="${row.detail_id}" data-product-id="${row.product_id}" data-serial-id="${row.serial_id}" data-serial-number="${row.serial_number}" type="button">
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

    tableSelecedSerial = $('#table-selected-serial').DataTable({
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
                    return `<b>${data}</b><p style="margin-top: 1px;">${row[1]}</p>`;
                }, 
                targets: [0]
              },          
              {                    
                  render: function(data, type, row) {                        
                      return `<b>${row[2]}</b>`;
                  },                                        
                  targets: [1]
              },                                
              {                    
                  render: function(data, type, row) {                              
                
                    var serialID = row[3],
                        detailID = row[4];                                                        

                    return `<button class="btn btn-sm text-xs btn-danger btn-flat legitRipple" onclick="removeSerial($(this),${serialID},${detailID})" type="button">
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
        site: {
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
        site: {
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
        }else 
        if(element.hasClass('select2-hidden-accessible')){
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
            receiptDate = $('#form').find('#receipt-date').data('daterangepicker').startDate.format('YYYY-MM-DD'),
            siteID      = $('#site').find('option:selected').val(),
            warehouseID = $('#warehouse').find('option:selected').val(),
            products    = [],
            zeroValue   = false;

        $.each($('#table-product > tbody > .product-item'), function(index, value) {
          var product     = $(this).find('.item-product'),
              productID   = product.val(),
              detailID    = product.parents('.product-item').attr('data-detail-id'),
              referenceID = product.attr('data-reference-id'),
              reference   = product.attr('data-reference'),
              uomID       = product.attr('data-uom-id'),
              sku         = product.attr('data-sku'),
              qtyOrder    = product.attr('data-qty-order'),
              qtyReceipt  = $(this).find('.qty-receipt').val(),
              rackID      = product.parents('.product-item').find('.rack-warehouse > option:selected').val(),
              binID       = product.parents('.product-item').find('.bin-warehouse > option:selected').val(),
              type        = product.attr('data-type'),
              hasSerial   = product.attr('data-has-serial')=='true'?true:false,              
              lastKey     = parseInt(product.attr('data-last-key')),
              serials     = null;

            if(type == 'contract' && hasSerial){
              var dataSerial = {
                warehouse_id : warehouseID,
                product_id   : productID, 
                sku          : sku,
                qty          : parseInt(qtyReceipt),
                lastkey      : lastKey                
              };

              serials = addSerial(dataSerial);
            }else if(type == 'borrowing' && hasSerial){

              serials  = receiptProduct.filter(param => param.detail_id == detailID && param.product_id == productID)[0].serials;                            
            }

          products.push({
            product_id    : productID,
            site_id       : siteID,
            warehouse_id  : warehouseID,
            reference_id  : detailID,
            reference     : reference,
            uom_id        : uomID,
            has_serial    : hasSerial,
            qty_order     : qtyOrder,
            qty_receipt   : qtyReceipt ? qtyReceipt : 0,
            rack_id       : rackID,
            bin_id        : binID,
            type          : type,
            serials       : serials,
            sku           : sku,
            last_key      : lastKey
          });

        });         

        if (products.length == 0) {
          toastr.warning('Select the product first. at least one product');
          return false;
        } else if (zeroValue) {
          toastr.warning("Minimum value of qty receipt is 1.");
          return false
        }

        data.append('products', JSON.stringify(products));
        data.append('receiptdate', receiptDate);

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
            document.location = "{{route('goodsreceipt.index')}}";
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
              id            : item.id,
              text          : item.name,
              site_id       : item.site_id,
              site          : item.site,
              warehouse_id  : item.warehouse_id,
              warehouse     : item.warehouse
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
      if(data.site_id){
        $('#form').find('#site').select2('trigger','select',{
          data: {
            id   : data.site_id,
            text : `${data.site}`
          }
        });
      }
      if(data.warehouse_id){
        $('#form').find('#warehouse').select2('trigger','select',{
          data : {
            id    : data.warehouse_id,
            text  : `${data.warehouse}`
          }
        });
      }      
    }).on('select2:clear', function() {
      $(this).parents('.product-item').find('.bin-warehouse').val(null).trigger('change');
    }).on('change', function(){
      $(this).focus();
    });
  }

  const initBin = () => {
    $(".bin-warehouse").select2({
      ajax: {
        url: "{{route('warehouse.selectbin')}}",
        type: 'GET',
        dataType: 'json',
        data: function(params) {
          var warehouseID = $('#form').find('#warehouse > option:selected').val();
          var rackID = $(this).parents('.product-item').find('.rack-warehouse > option:selected').val();

          return {
            name    : params.term,
            warehouse_id : warehouseID?warehouseID:null,
            rack_id : rackID ? rackID : null,
            page    : params.page,
            limit   : 30,
          };
        },
        processResults: function(data, params) {
          var more = (params.page * 30) < data.total;
          var option = [];
          $.each(data.rows, function(index, item) {
            option.push({
              id            : item.id,
              text          : item.name,
              rack_id       : item.rack_id,
              rack          : item.rack,
              site_id       : item.site_id,
              site          : item.site,
              warehouse_id  : item.warehouse_id,
              warehouse     : item.warehouse
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
      if(data.site_id){
        $('#form').find('#site').select2('trigger','select',{
          data: {
            id    : data.site_id,
            text  : `${data.site}`
          }
        });
      }
      if(data.warehouse_id){
        $('#form').find('#warehouse').select2('trigger','select',{
          data: {
            id    : data.warehouse_id,
            text  : `${data.warehouse}`
          }
        });
      }      
      if(data.rack_id){
        var rack = $(this).parents('.product-item').find('.rack-warehouse');
          rack.select2('trigger','select',{
              data : {
                  id   : data.rack_id,
                  text : `${data.rack}`
              }
          });        
      }
    }).on('change', function(){
      $(this).focus();
    });
  }

  const initSelect2 = () => {
    $('.select2').select2({
      allowClear: true
    });        
  }

  const initInputFile = () => {
    $('.custom-file-input').on('change', function() {
      let fileName = $(this).val().split('\\').pop();
      $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
  }

  const addReference = () => {
    if (productRole == 'contract') {
      contractTable.draw();
      $('li.borrowing-reference').hide();
      $('li.transfer-reference').hide();
    }else if(productRole == 'transfer'){
      transferTable.draw();
      $('li.borrowing-reference').hide();
      $('li.contract-reference').hide();
    } else if (productRole == 'borrowing') {
      borrowingTable.draw();
      $('li.contract-reference').hide();
      $('li.transfer-reference').hide();
    } else {
      contractTable.draw();
      transferTable.draw();
      borrowingTable.draw();
      $('li.borrowing-reference').show();
      $('li.transfer-reference').show();
      $('li.contract-reference').show();
    }

    $('#form-reference').modal('show');
  }

  const addProduct = (that, type) => {        
    var reference = that.attr('data-reference');    

    if(type ==  'borrowing'){
      origin = $('#table-borrowing > tbody');
    }else{
      origin = $('#table-contract > tbody');
    }
    
    $.each(origin.find(`button.btn-add-product[data-reference='${reference}']`), function (index, element) { 
      var button      = $(this);
      var reference   = button.attr('data-reference'),
          idReference = button.attr('data-reference-id'),
          productID   = button.attr('data-product-id'),
          product     = button.attr('data-product'),
          category    = button.parents('tr').find('td.product-category').html(),
          sku         = button.attr('data-sku'),
          isSerial    = button.attr('data-serial'),
          lastKey     = button.attr('data-last-key'),
          uomID       = button.attr('data-uom-id'),      
          order       = button.attr('data-order'),
          detailID    = button.attr('data-detail-id');          
          
      var obj = receiptProduct.filter(param  =>  param.detail_id == detailID && param.product_id == productID);

      if(obj.length == 0){
        receiptProduct.push({
          reference_id : idReference,
          reference    : reference,
          product_id   : productID,
          product      : product,
          category     : category,
          sku          : sku,
          is_serial    : isSerial=='1'?true:false,
          serials      : isSerial=='1'?[]:null,
          lastkey      : lastKey,
          uom_id       : uomID,
          order        : order,
          type         : type,
          detail_id    : detailID
        });
      }          

    });               
    drawProduct(type);        
  }

  const drawProduct = (type) => {
    var html = '';
    var item = receiptProduct.filter(param => param.type == type);
    var tableProduct = $('#table-product > tbody');
    
    $.each(item, function (index, value) {     
      var reference   = value.reference,
          referenceID = value.reference_id,
          productID   = value.product_id,
          product     = value.product,
          category    = value.category,
          sku         = value.sku,
          isSerial    = value.is_serial,
          lastKey     = value.lastkey,
          uomID       = value.uom_id,      
          order       = value.order,
          detailID    = value.detail_id,
          readonly    = '',
          disable     = 'disabled';             

      if (isSerial == true) {
        disable  = type == 'contract'?'disabled':'';
        readonly = type == 'borrowing'?'readonly':'';
        icon     = 'fas fa-check';
        badge    = 'badge-info';          
      } else {
          icon  = 'fas fa-times';
          badge = 'bg-red';
      }   

      var serial = `<span class="badge ${badge} text-md"><i class="${icon}" style="size: 1x;"></i></span>`;

      html += `<tr class="product-item" data-detail-id="${detailID}">
                <input type="hidden" class="item-product" value="${productID}" data-reference-id="${referenceID}" data-reference="${reference}" data-sku="${sku}" data-uom-id="${uomID}" data-qty-order="${order}" data-type="${type}" data-has-serial="${isSerial}" data-last-key="${lastKey}">                        
                <td width="100"><p>${product}<br><b style="font-size: 10pt;">${reference}</b></p></td>
                <td width="100">${category}</td>
                <td width="30" class="text-center">${serial}</td>                  
                <td class="text-center" width="30">${order}</td>
                <td class="text-center" width="30">
                  <input type="number" class="form-control numberfield text-right qty-receipt" placeholder="0" min="1" max="${order}" ${readonly}>
                </td>
                <td width="40">
                  <div class="form-group">
                    <div class="controls">
                      <select name="rack" class="form-control rack-warehouse" data-placeholder="Choose rack" style="width: 100%;" required></select>
                    </div>
                  </div>                    
                </td>
                <td width="40">
                  <div class="form-group">
                    <div class="controls">
                      <select name="bin" class="form-control bin-warehouse" data-placeholder="Choose bin" style="width: 100%;" required></select>
                    </div>
                  </div>                    
                </td>
                <td class="text-center" width="15" style="white-space: nowrap;">
                    <button class="btn btn-sm text-xs btn-warning btn-flat legitRipple ${disable}" type="button" onclick="showSerial($(this),${productID},${detailID})" ${disable}><i class="fas fa-bars"></i></button>
                    <button class="btn btn-sm text-xs btn-danger btn-flat legitRipple" type="button" onclick="removeProduct(${detailID},${productID},'${type}')"><i class="fas fa-trash"></i></button>
                </td>
              </tr>`;
    });    
      
    if(item.length > 0){
      tableProduct.find('.no-available-data').remove();
    }else{
      html = `<tr class="no-available-data">
                <td colspan="8" class="text-center">No available data.</td>
              </tr>`;
      type = '';
    }

    tableProduct.html(html);   
    

    initBin();
    initRack();

    productRole = type;
    if (productRole == 'contract') {
      $('li.borrowing-reference').hide();
      $('li.contract-reference').show();      
      contractTable.ajax.reload(null, false);
    } else if (productRole == 'borrowing') {
      $('li.borrowing-reference').show();
      $('li.contract-reference').hide();      
      borrowingTable.ajax.reload(null, false);
    }else{
      $('li.borrowing-reference').show();
      $('li.contract-reference').show();
      contractTable.ajax.reload(null, false);
      borrowingTable.ajax.reload(null, false);
    }    

  }

  const removeProduct = (detailID, productID, type) => {
    receiptProduct = receiptProduct.filter(param => param.detail_id != detailID && param.product_id != productID);
    drawProduct(type);    
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

  const addSerial = (param) => {    
    var serials = [];        
    var limit   = param.lastkey + param.qty;

    
    for (var index = param.lastkey; index < limit; index++) {
      serials.push({          
          warehouse_id  : param.warehouse_id,
          product_id    : param.product_id,
          serial_number : param.sku+'-'+generateNumber(index+1)
      });      
    }            

    return serials;
  }

  const selectSerial = (that) => {
    var product      = that.parents('tr').find('td.product').html(),
        productID    = that.attr('data-product-id'),
        detailID     = that.attr('data-detail-id'),
        serialID     = that.attr('data-serial-id'),
        serialNumber = that.attr('data-serial-number');

    var serials  = receiptProduct.filter(param => param.detail_id == detailID && param.product_id == productID)[0].serials;              

    if(serials.length == 0){
      serials.push({
        serial_id     : serialID,
        serial_number : serialNumber  
      });
    }else{      
      var idSerial = serials.filter(param => param.serial_id == serialID);                    
        
      if(idSerial.length == 0){
          serials.push({
              serial_id     : serialID,
              serial_number : serialNumber
          });
      }            
    }        

    countQtyReceipt(detailID,productID);

    tableSelectSerial.draw();     
    drawSelectedSerial(detailID,productID);    
  }

  const removeSerial = (that, serialID, detailID) => {
    var product   = receiptProduct.filter(param => param.detail_id == detailID);
    var productID = product[0].product_id;
    var serials   = product[0].serials;

    serials = serials.filter(param => param.serial_id != serialID);
    product[0].serials = serials;    

    countQtyReceipt(detailID, productID);

    drawSelectedSerial(detailID, productID); 
    tableSelectSerial.draw();
  }

  const drawSelectedSerial = (detailID,productID) => {      
      setDataSerial = [];
      var product   =  receiptProduct.filter(param => param.detail_id == detailID && param.product_id == productID)[0];                
      
      if(product.serials.length > 0){
        $.each(product.serials, function (ind, val) { 
          setDataSerial.push([product.product, product.category,val.serial_number,val.serial_id, product.detail_id]);
        });
      }
      
      tableSelecedSerial.clear().draw();
      tableSelecedSerial.rows.add(setDataSerial); // Add new data
      tableSelecedSerial.draw(); // Redraw the DataTable        

      return true;
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

  const showSerial = (that,productID,detailID) => {
    $('#table-select-serial').attr('data-product-id',productID).attr('data-detail-id',detailID);
    $('#table-selected-serial').attr('data-product-id',productID).attr('data-detail-id',detailID);
    tableSelectSerial.draw();
    drawSelectedSerial(detailID, productID);
    $('#modal-serial').modal('show');    
  }

  const countQtyReceipt = (detailID, productID) => {
    var product  = receiptProduct.filter(param => param.detail_id == detailID && param.product_id == productID)[0];              
    var serials  = product.serials;
    var row      = $("#table-product > tbody").find(`tr[data-detail-id=${detailID}]`);

    if(row){
      row.find('.qty-receipt').val(serials.length);
    }


    console.log({
      product : product,
      serials : serials
    });
  }

  const onSubmit = (status) => {
    $('input[name=status]').val(status);
    $('form').first().trigger('submit');
  }
</script>
@endsection