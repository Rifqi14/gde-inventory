@extends('admin.layouts.app')
@section('title', @$menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">
      Create {{ @$menu_name }}
    </h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ @$menu_parent }}</li>
      <li class="breadcrumb-item">{{ @$menu_name }}</li>
      <li class="breadcrumb-item">Create</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section id="content" class="content">
  <div class="container-fluid">
    <form action="{{ route('consumable.store') }}" id="form" role="form" method="POST" enctype="multipart/form-data">
      {{ csrf_field() }}
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
                    <input type="text" id="consumable_number" class="form-control" placeholder="Automatically generated" readonly>
                  </div>
                </div>
                <div class="col-sm-6">
                  <!-- Site -->
                  <div class="form-group">
                    <label for="site">Site</label>
                    <select name="site" id="site" class="form-control select2" data-placeholder="Choose Site">
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <!-- Product Category -->
                  <div class="form-group">
                    <label for="product_category_id">Product Category</label>
                    <select name="product_category" id="product-category-id" class="form-control select2" data-placeholder="Choose Product Category">
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <!-- Warehouse -->
                  <div class="form-group">
                    <label for="warehouse">Warehouse</label>
                    <select name="warehouse" id="warehouse" class="form-control select2" data-placeholder="Choose Warehouse">
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <!-- Date Issued -->
                  <div class="form-group">
                    <label for="date_issued">Date Issued</label>
                    <input type="datepicker" name="date_issued" id="date-issued" class="form-control datepicker text-right" placeholder="Date Issued">
                  </div>
                </div>
                <div class="col-sm-6">
                  <!-- Status -->
                  <div class="form-group">
                    <label for="status">Status</label>
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
                <label for="description" class="control-label">Purpose</label>
                <textarea name="description" id="description" rows="4" class="form-control summernote" placeholder="Purpose ... "></textarea>
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
                <label for="product_id" class="control-label">Product</label>
                <select id="product" class="form-control select2" data-placeholder="Choose Product"></select>
                <br>
                <button type="button" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple" onclick="addProduct()">
                  Add
                </button>
              </div>
              <table id="table-products" class="table table-striped datatable" width="100%">
                <thead>
                  <tr>
                    <th width="200">Product Name</th>
                    <th width="15" class="text-center">UOM</th>
                    <th width="15" class="text-center">Current Stock</th>
                    <th width="10" class="text-center">Qty Consume</th>
                    <th width="10" class="text-center">Action</th>
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
                  <div class="form-group mt-3">
                    <div class="form-group mt-3">
                      <button type="button" onclick="addDocument()" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple">
                        Add Document
                      </button>
                    </div>
                  </div>
                  <table id="table-document" class="table table-striped datatable" width="100%">
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
                <!-- Photo -->
                <div class="tab-pane fade show" id="photo" role="tabpanel" aria-labelledby="photo-tab">
                  <div class="form-group mt-3">
                    <button type="button" onclick="addPhoto()" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple">
                      Add Photo
                    </button>
                  </div>
                  <table id="table-photo" class="table table-striped datatable" width="100%">
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

  $(function() {
    $('.summernote').summernote({
      height: 120,
      toolbar: [
        ['style', ['style']],
        ['font-style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
        ['font', ['fontname']],
        ['font-size', ['fontsize']],
        ['font-color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video', 'hr']],
        ['misc', ['fullscreen', 'codeview', 'help']],
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
    });

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
        url: "{{route('product.select')}}",
        type: 'GET',
        dataType: 'json',
        data: function(params) {
          var productCategory = $('#product-category-id').select2('val');
          var products = [];

          $.each($('#table-products > tbody > .product-item'), function(index, value) {
            var product = $(this).find('.item-product'),
              product_id = product.val();

            products.push(product_id);

          });
          return {
            name: params.term,
            product_category_id: productCategory,
            products: products,
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
        var data          = new FormData($('#form')[0]),            
            consumeDate   = $('#form').find('#date-issued').data('daterangepicker').startDate.format('YYYY-MM-DD'),
            products      = [],
            zeroValue     = false;

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

        if(products.length == 0){
            toastr.warning('Select product first.');
            return false;
        }else if(zeroValue){
            toastr.warning("Minimum value of qty consume is 1.");
            return false
        }

        data.append('products',JSON.stringify(products));        
        data.append('consumedate',consumeDate);        

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
            message = response.message ? response.message : 'Failed to insert data.';

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

  const removeDoc = (that) => {
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

  const onSubmit = (status) => {
    $('input[name=status]').val(status);
    $('form').first().trigger('submit');
  }
</script>
@endsection