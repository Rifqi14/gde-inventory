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
                <h5 class="text-md text-dark text-bold">{{ $menu_name }}</h5>
              </span>
              <div class="mt-5"></div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="reference_number" class="col-md-12 col-xs-12 control-label">Reference Number</label>
                    <div class="col-sm-12 controls">
                      <input type="text" name="reference_number" id="reference_number" class="form-control" readonly placeholder="Auto number">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="receipt_date" class="col-md-12 col-xs-12 control-label">Receipt Date</label>
                    <div class="col-sm-12 controls">
                      <input type="text" name="receipt_date" id="receipt_date" class="form-control datepicker" placeholder="Date">
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group row">
                    <label for="warehouse_id" class="col-md-12 col-xs-12 control-label">Receipt Date</label>
                    <div class="col-sm-12 controls">
                      <select name="warehouse" id="warehouse" class="form-control select2" required>
                        <option value="">Option from warehouse menu</option>
                      </select>
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
                <h5 class="text-md text-dark text-bold">Other</h5>
              </span>
              <div class="mt-5"></div>
              <div class="form-group row">
                <label for="description" class="col-md-12 col-xs-12 control-label">Description</label>
                <div class="col-sm-12 controls">
                  <textarea class="form-control summernote" name="description" id="description" rows="4" placeholder="Description..."></textarea>
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
                <h5 class="text-md text-dark text-bold">Product</h5>
              </span>
              <div class="mt-5"></div>
              <button type="button" id="add-product" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple" onclick="addProduct()">Add</button>
              <table id="table-product" class="table table-striped datatable" width="100%">
                <thead>
                  <tr>
                    <th width="10%">Product Name</th>
                    <th width="10%">Reference</th>
                    <th width="10%">Qty Order</th>
                    <th width="10%">Qty Receipt</th>
                    <th width="10%">Rack</th>
                    <th width="10%">Bin</th>
                    <th width="10%">#</th>
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

<div class="modal fade" id="form-reference">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Add reference</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="card card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="reference-tab" role="tablist">
                  <li class="nav-item">
                    <a href="#add-contract-reference" class="nav-link active" id="add-contract-reference-tab" data-toggle="pill" role="tab" aria-controls="add-contract-reference" aria-selected="false">Contract</a>
                  </li>
                  <li class="nav-item">
                    <a href="#add-return" class="nav-link" id="add-return-tab" data-toggle="pill" role="tab" aria-controls="add-return" aria-selected="false">Return Rental Product</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="reference-tab">
                  <div class="tab-pane fade show active table-responsive" id="add-contract-reference" role="tabpanel" aria-labelledby="add-contract-reference-tab">
                    <table id="table-contract-reference" class="table table-striped datatable" width="100%">
                      <thead>
                        <tr>
                          <th width="5%">No.</th>
                          <th width="15%">Date</th>
                          <th width="20%">Contract Number</th>
                          <th width="20%">Produk</th>
                          <th width="10%">Qty</th>
                          <th width="15%">UOM</th>
                          <th width="15%">#</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  <div class="tab-pane fade show table-responsive" id="add-return" role="tabpanel" aria-labelledby="add-return-tab">
                    <table id="table-contract-reference" class="table table-striped datatable" width="100%">
                      <thead>
                        <tr>
                          <th width="5%">No.</th>
                          <th width="15%">Date</th>
                          <th width="20%">Rental Number</th>
                          <th width="20%">Produk</th>
                          <th width="10%">Qty</th>
                          <th width="15%">UOM</th>
                          <th width="15%">#</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  const summernote = () => {
    $('.summernote').summernote({
    	height:145,
    	toolbar: [
    		['style', ['style']],
    		['font-style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
    		['font', ['fontname']],
    		['font-size',['fontsize']],
    		['font-color', ['color']],
    		['para', ['ul', 'ol', 'paragraph']],
    		['table', ['table']],
    		['insert', ['link', 'picture', 'video', 'hr']],
    		['misc', ['fullscreen', 'codeview', 'help']]
    	]
    });
  }

  const addProduct = () => {
    $('#form-reference').modal('show');
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

  $(function(){
    $('.select2').select2();
  })
</script>
@endsection