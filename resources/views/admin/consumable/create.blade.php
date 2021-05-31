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
      @csrf
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">{{ @$menu_name }}</h5>
              </span>
              <div class="mt-5"></div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="consumable_number">Number</label>
                    <input type="text" name="consumable_number" id="consumable_number" class="form-control" placeholder="Consumable Number">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="site_id">Site</label>
                    <select name="site_id" id="site_id" class="form-control select2" data-placeholder="Choose Site">
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="product_category_id">Product Category</label>
                    <select name="product_category_id" id="product_category_id" class="form-control select2" data-placeholder="Choose Product Category">
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="warehouse_id">Warehouse</label>
                    <select name="warehouse_id" id="warehouse_id" class="form-control select2" data-placeholder="Choose Warehouse">
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="date_issued">Date Issued</label>
                    <input type="text" name="date_issued" id="date_issued" class="form-control" placeholder="Date Issued">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="issued_by">Issued By</label>
                    <input type="hidden" name="issued_by" id="issued_by" class="form-control" value="{{ Auth::guard('admin')->user()->id }}">
                    <input type="text" name="issued_by_preview" id="issued_by_preview" class="form-control" value="{{ Auth::guard('admin')->user()->name }}" disabled>
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
                <h5 class="text-md text-dark text-bold">Other</h5>
              </span>
              <div class="mt-5"></div>
              <div class="form-group">
                <label for="description" class="control-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-control summernote" placeholder="Description ... "></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">Product</h5>
              </span>
              <div class="mt-5"></div>
              <div class="form-group">
                <label for="product_id" class="control-label">Product</label>
                <select name="product_id" id="product_id" class="form-control select2" aria-placeholder="Choose Product"></select>
              </div>
              <button id="add-product" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple" onclick="addProduct()">Add</button>
              <table id="table-product" class="table table-striped datatable" width="100%">
                <thead>
                  <tr>
                    <th>Product Name</th>
                    <th>UOM</th>
                    <th>Qty System</th>
                    <th>Qty Consume</th>
                    <th>#</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">Supporting Document</h5>
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
                  <div class="mt-3"></div>
                  <button type="button" id="add-document" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple" onclick="addDocument()">Add</button>
                  <table id="table-document" class="table table-striped datatable" width="100%">
                    <thead>
                      <tr>
                        <th width="10%">Document Name</th>
                        <th width="10%">File</th>
                        <th width="10%"></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane fade show" id="photo" role="tabpanel" aria-labelledby="photo-tab">
                  <div class="mt-3"></div>
                  <button type="button" id="add-photo" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple" onclick="addPhoto()">Add</button>
                  <table id="table-photo" class="table table-striped datatable" width="100%">
                    <thead>
                      <tr>
                        <th width="10%">Photo Name</th>
                        <th width="10%">File</th>
                        <th width="10%">#</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
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
  const summernote = () => {
    $('.summernote').summernote({
      height: 120,
      toolbar: [
        ['style', ['style']],
    		['font-style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
    		['font', ['fontname']],
    		['font-size',['fontsize']],
    		['font-color', ['color']],
    		['para', ['ul', 'ol', 'paragraph']],
    		['table', ['table']],
    		['insert', ['link', 'picture', 'video', 'hr']],
    		['misc', ['fullscreen', 'codeview', 'help']],
      ]
    });
  }

  const addDocument = () => {
    var number  = $('#table-document').find('tr:last').data('number') ? $('#table-document').find('tr:last').data('number') + 1 : 1;
    var html = `
    <tr data-number="${number}">
      <td><input type="text" class="form-control" id="document_name_${number}" name="document_name[]" placeholder="Document Name" aria-required="true"></td>
      <td>
        <div class="input-group">
          <div class="custom-file">
            <input type="file" class="custom-file-input" name="image" accept="image/*" onchange="changePath(this)">
            <label class="custom-file-label" for="exampleInputFile">Attach Image</label>
          </div>
        </div>
      </td>
      <td>
        <button type="button" class="btn btn-transparent text-md" onclick="removeDocument($(this))" data-document=${number}>
          <i class="fas fa-trash text-maroon color-palette"></i>
        </button>
      </td>
    </tr>
    `;
    $("#table-document tbody").append(html);
  }

  const removeDocument = (a) => {
    var number = a.attr('data-document');
    $("#table-document tbody").find("tr[data-number="+number+"]").remove();
  }

  const addPhoto = () => {
    var number  = $('#table-photo').find('tr:last').data('number') ? $('#table-photo').find('tr:last').data('number') + 1 : 1;
    var html = `
    <tr data-number="${number}">
      <td><input type="text" class="form-control" id="photo_name_${number}" name="photo_name[]" placeholder="Photo Name" aria-required="true"></td>
      <td>
        <div class="input-group">
          <div class="custom-file">
            <input type="file" class="custom-file-input" name="image" accept="image/*" onchange="changePath(this)">
            <label class="custom-file-label" for="exampleInputFile">Attach Image</label>
          </div>
        </div>
      </td>
      <td>
        <button type="button" class="btn btn-transparent text-md" onclick="removePhoto($(this))" data-photo=${number}>
          <i class="fas fa-trash text-maroon color-palette"></i>
        </button>
      </td>
    </tr>
    `;
    $("#table-photo tbody").append(html);
  }

  const removePhoto = (a) => {
    var number = a.attr('data-photo');
    $("#table-photo tbody").find("tr[data-number="+number+"]").remove();
  }

  $(function() {
    summernote();
    $("#site_id").select2({
      ajax: {
        url: "{{ route('site.select') }}",
        type: "GET",
        dataType: "JSON",
        data: function ( params ) {
          return {
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function ( data, params ) {
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function ( index, item ) {
            option.push({
              id: item.id,
              text: item.name
            });
          });
          return {
            results: option, more: more,
          };
        },
      },
      allowClear: true,
    }).on('select2:close', function(e){
      var data = $(this).find('option:selected').val();
      var warehouse = $('#warehouse_id').select2('data');

      if (warehouse[0] && warehouse[0].site_id != data) {
        $('#warehouse_id').val(null).trigger('change');
      }
    }).on('select2:clearing', function(){
      $('#warehouse_id').val(null).trigger('change');
    });

    $("#warehouse_id").select2({
      ajax: {
        url: "{{ route('warehouse.select') }}",
        type: "GET",
        dataType: "JSON",
        data: function ( params ) {
          return {
            site_id: $('#site_id').find('option:selected').val(),
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function ( data, params ) {
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function ( index, item ) {
            option.push({
              id: item.id,
              text: item.name,
              site_id: item.site_id,
            });
          });
          return {
            results: option, more: more,
          };
        },
      },
      allowClear: true,
    });

    $("#product_category_id").select2({
      ajax: {
        url: "{{ route('productcategory.select') }}",
        type: "GET",
        dataType: "JSON",
        data: function ( params ) {
          return {
            site_id: $('#site_id').find('option:selected').val(),
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function ( data, params ) {
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function ( index, item ) {
            option.push({
              id: item.id,
              text: item.name,
            });
          });
          return {
            results: option, more: more,
          };
        },
      },
      allowClear: true,
      escapeMarkup: function (text) { return text; },
    });

    $("#product_id").select2({
      ajax: {
        url: "{{ route('product.select') }}",
        type: "GET",
        dataType: "JSON",
        data: function ( params ) {
          return {
            site_id: $('#warehouse_id').find('option:selected').val(),
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function ( data, params ) {
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function ( index, item ) {
            option.push({
              id: item.id,
              text: item.name,
            });
          });
          return {
            results: option, more: more,
          };
        },
      },
      allowClear: true,
      escapeMarkup: function (text) { return text; },
    });
  });
</script>
@endsection