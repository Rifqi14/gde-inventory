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
    <form action="{{ route('contractreceipt.store') }}" id="form" role="form" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">{{ $menu_name }} Information</h5>
              </span>
              <div class="mt-5"></div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="contract" class="col-md-12 col-xs-12 control-label">Contract</label>
                    <div class="col-sm-12 controls">
                      <select name="contract" id="contract" class="form-control select2" required>
                        <option value="">Option from contract menu where contract type is product</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="warehouse" class="col-md-12 col-xs-12 control-label">Warehouse</label>
                    <div class="col-sm-12 controls">
                      <select name="warehouse" id="warehouse" class="form-control select2" required>
                        <option value="">Option from warehouse menu</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="contract_number" class="col-md-12 col-xs-12 control-label">Contract Number</label>
                    <div class="col-sm-12 controls">
                      <input type="text" name="contract_number" id="contract_number" class="form-control" readonly value="Fill from contract number just for info not for store">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="contract_date" class="col-md-12 col-xs-12 control-label">Contract Signing Date</label>
                    <div class="col-sm-12 controls">
                      <input type="text" name="contract_date" id="contract_date" class="form-control" readonly value="Fill from contract date just for info not for store">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="batch" class="col-md-12 col-xs-12 control-label">Batch</label>
                    <div class="col-sm-12 controls">
                      <select name="batch" id="batch" class="form-control select2" required>
                        <option value="">Batch from contract batch menu</option>
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
                <label class="col-md-12 col-xs-12 control-label" for="description">Description</label>
                <div class="col-sm-12 controls">
                  <textarea class="form-control summernote" name="description" id="description" rows="4" placeholder="Description..."></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-12 col-xs-12 control-label" for="status">Status <b class="text-danger">*</b></label>
                <div class="col-sm-12 controls">
                  <select name="status" id="status" class="form-control select2" required data-placeholder="Select Status">
                    <option value=""></option>
                    @foreach(config('enums.status_receipt') as $key => $status)
                    <option value="{{ $key }}">{{ $status }}</option>
                    @endforeach
                  </select>
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
                <h5 class="text-md text-dark text-bold">Document List</h5>
              </span>
              <div class="mt-5"></div>
              <button type="button" id="add-document" class="btn btn-labeled text-sm btn-sm btn-outline-primary btn-flat btn-block legitRipple" onclick="addDocument()">Add</button>
              <table id="table-document" class="table table-striped datatable" width="100%">
                <thead>
                  <tr>
                    <th width="5%" class="text-center">No.</th>
                    <th width="40%">Document Name</th>
                    <th width="40%">Upload Document</th>
                    <th width="10%" class="text-center">Upload Date</th>
                    <th width="5%" class="text-center">#</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="empty">
                    <td colspan="5" class="text-center">Document Not Available</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
                <b><i class="fas fa-save"></i></b>
                Save
              </button>
              <a href="{{route('contractreceipt.index')}}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
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
  const summernote = () =>{
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

  const addDocument = () => {
    var number  = $('table').find('tr:last').data('number') ? $('table').find('tr:last').data('number') + 1 : 1;
    var dateNow = moment().format('DD/MM/YYYY');
    var html = `
    <tr data-number="${number}">
      <td class="text-center align-middle">${number}</td>
      <td><input type="text" class="form-control" id="document_name_${number}" name="document_name[]" placeholder="Document Name" aria-required="true"></td>
      <td>
        <div class="input-group">
          <div class="custom-file">
            <input type="file" class="custom-file-input" name="image" accept="image/*" onchange="changePath(this)">
            <label class="custom-file-label" for="exampleInputFile">Attach Image</label>
          </div>
          <div class="input-group-append">
            <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
          </div>
          <button type="button" class="btn btn-transparent text-md" onclick="addUpload($(this))">
            <i class="fas fa-plus text-green color-palette"></i>
          </button>
        </div>
      </td>
      <td class="text-center align-middle">${dateNow}</td>
      <td class="text-center">
        <button type="button" class="btn btn-transparent text-md" onclick="removeDocument($(this))" data-document=${number}>
          <i class="fas fa-trash text-maroon color-palette"></i>
        </button>
      </td>
    </tr>
    `;
    $('#table-document tbody .empty').remove();
    $('#table-document tbody').append(html);
  }

  const changePath = (that) => {
    let filename = $(that).val();
    $(that).next().html(filename);
  }

  const removeDocument = (a) => {
    var number = a.attr('data-document');
    $("#table-document tbody").find("tr[data-number="+number+"]").remove();
    var is_empty  = !$.trim($("#table-document tbody").html());
    if (is_empty) {
      html  = `
          <tr class="empty">
            <td colspan="5" class="text-center">Document Not Available</td>
          </tr>
      `;
      $("#table-document tbody").append(html);
    }
  }

  $(function(){
    summernote();
    $(".select2").select2();

    $('#contract').select2({
      ajax: {
        url: "{{ route('contarctreceipt.selectcontract') }}",
        type: "GET",
        dataType: "json",
        data: function (params) {
          return {
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function (data, params) {
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item){
            option.push({
              id:item.id,
              text: item.title,
            });
          });
          return { results: option, more: more };
        },
      },
      allowClear: true,
    });

    $('#warehouse').select2({
      ajax: {
        url: "{{ route('warehouse.select') }}",
        type: "GET",
        dataType: "json",
        data: function (params) {
          return {
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function (data, params) {
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item){
            option.push({
              id:item.id,
              text: item.name,
            });
          });
          return { results: option, more: more };
        },
      },
      allowClear: true,
    });
  });
</script>
@endsection