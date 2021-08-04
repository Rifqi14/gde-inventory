@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">
      Detail {{ $menu_name }}
    </h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name }}</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
      <li class="breadcrumb-item">Detail</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section id="content" class="content">
  <div class="container-fluid">
    <form id="form" role="form" method="POST" enctype="multipart/form-data">
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
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="receipt-number" class="col-md-12 col-xs-12 control-label">Receipt Number</label>
                    <div class="col-sm-12 controls">
                      <input type="text" id="receipt-number" class="form-control" placeholder="Automatically generated" value="{{$data->good_receipt_no}}" readonly>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="receipt_date" class="col-md-12 col-xs-12 control-label">Receipt Date</label>
                    <div class="col-sm-12 controls">
                      <input type="text" name="receipt_date" id="receipt_date" class="form-control datepicker text-right" placeholder="Receipt date" value="{{$data->date_receipt}}" readonly>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label for="issued-by" class="cl-md-12 col-xs-12 control-label">Issued By</label>
                    <input type="text" class="form-control" placeholder="Issued by" value="{{$data->issued}}" readonly>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="unit">Site</label>
                    <select name="site" id="site" class="form-control select2" data-placeholder="Unit" disabled></select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="warehouse">Warehouse</label>
                    <select name="warehouse" id="warehouse" class="form-control select2" data-placeholder="Warehouse" disabled></select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group form-status">
                    <label for="state" class="control-label">Status</label>
                    <div class="controls"></div>
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
                <table id="table-product" class="table table-striped datatable" width="100%">
                  <thead>
                    <tr>
                      <th width="100">Product</th>
                      <th width="100">Category</th>
                      <th width="30" class="text-center">Has Serial</th>                      
                      <th width="40" class="text-center" style="white-space: nowrap;">Qty Order</th>
                      <th width="40" class="text-center" style="white-space: nowrap;">Qty Receipt</th>
                      <th width="100">Rack</th>
                      <th width="100">Bin</th>                      
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
                  <div class="form-group">
                    <table id="table-document" class="table table-striped datatable" width="100%">
                      <thead>
                        <tr>
                          <th width="50%">Document Name</th>
                          <th width="50%">File</th>
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
                <div class="tab-pane fade show" id="photo" role="tabpanel" aria-labelledby="photo-tab">
                  <div class="form-group">
                    <table id="table-photo" class="table table-striped datatable" width="100%">
                      <thead>
                        <tr>
                          <th width="50%">Photo Name</th>
                          <th width="50%">File</th>
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
    var siteID       = @json($data->site_id),
        warehouseID  = @json($data->warehouse_id),
        state        = '{{$data->status}}';

    initData();

    switch (state) {
      case 'approved':
        badge = 'bg-info';
        break;
      case 'rejected':
        badge = 'bg-red';
        break
      default:
        state = null;
        badge = '';
        break;
    }

    if(state){
      $('.form-status > .controls').html(`<span class="badge ${badge} text-sm" style="text-transform: capitalize;">${state}</span>`);
    }

    $('.select2').select2({
      allowClear: true
    });

    $('.summernote').summernote({
      height: 145,
      toolbar: []
    });    
    
    $('.summernote').summernote('disable');
    $('#description').summernote('code',@json($data->description));

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

    if(siteID){
      $('#site').select2('trigger','select',{
        data : {
          id : siteID,
          text : '{{$data->site}}'
        }
      });
    }

    if(warehouseID){
      $('#warehouse').select2('trigger','select',{
        data : {
          id : warehouseID,
          text : '{{$data->warehouse}}'
        }
      });
    }
  });

  const initData = () => {
    var products = @json(count($data->contractref) > 0?$data->contractref:$data->borrowingref);
    var files    = @json($data->files);
    var images   = @json($data->images);

    if(products.length > 0){
      var html  = '',
          table = $('#table-product > tbody');

      $.each(products, function (index, value) { 
        var productID    = value.product_id,
            product      = value.product,
            category     = value.category,
            referenceID  = value.reference_id,
            reference    = value.reference,
            uomID        = value.uom_id,
            isSerial     = value.is_serial,
            order        = value.qty_order,
            receipt      = value.qty_receipt?value.qty_receipt:0,
            rackID       = value.rack_id,
            rack         = value.rack,
            binID        = value.bin_id,
            bin          = value.bin,
            type         = value.type;

            if (isSerial == '1') {
              disable = type == 'contract'?'disabled':'';
              icon    = 'fas fa-check';
              badge   = 'badge-info';            

            } else {
              icon  = 'fas fa-times';
              badge = 'bg-red';
            }

            var serial = `<span class="badge ${badge} text-md"><i class="${icon}" style="size: 1x;"></i></span>`;

            html += `<tr class="product-item">                   
                      <td width="100"><p>${product}<br><b>${reference}</b></p></td>
                      <td width="100">${category}</td>
                      <td width="30" class="text-center">${serial}</td>
                      <td class="text-right" width="30">${order}</td>
                      <td class="text-right" width="30">${receipt}</td>
                      <td width="100">${rack}</td>
                      <td width="100">${bin}</td>                                             
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
                receiptID  = value.goods_receipt_id,
                filename   = value.document_name,
                file       = value.file,
                path       = `{{asset('assets/goodsreceipt/${receiptID}/document/${file}')}}`;

            html += `<tr class="document-item">
                        <td>${filename}</td>
                        <td class="doc-cell">                       
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
                receiptID  = value.goods_receipt_id,
                filename   = value.document_name,
                file       = value.file,
                path       = `{{asset('assets/goodsreceipt/${receiptID}/image/${file}')}}`;

            html += `<tr class="photo-item">
                        <td>${filename}</td>
                        <td class="doc-cell">          
                            <a href="${path}" target="_blank">
                               <b><i class="fas fa-download"></i></b> Download File
                            </a>                      
                        </td>                        
                    </tr>`;
        });

        table.find('.no-available-data').remove();
        table.append(html);
    }

    console.log({
      products :  products,
      contractref :files,
      images    : images
    });
  }  
</script>
@endsection