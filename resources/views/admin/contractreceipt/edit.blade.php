@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')
<style>
  #table-document-user tbody tr td:nth-child(3) .input-group {
    margin-bottom: .25rem !important;
  }

  /* #table-document tbody tr td:nth-child(3) .input-group button{
        user-select: none;
        z-index: 0;
        opacity: 0;
        position: relative;
        cursor: default;
    } */
  #table-document-user tbody tr td:nth-child(3) .input-group:last-child {
    margin-bottom: 0px !important;
  }

  #table-document-safeguard tbody tr td:nth-child(3) .input-group {
    margin-bottom: .25rem !important;
  }

  /* #table-document tbody tr td:nth-child(3) .input-group button{
        user-select: none;
        z-index: 0;
        opacity: 0;
        position: relative;
        cursor: default;
    } */
  #table-document-safeguard tbody tr td:nth-child(3) .input-group:last-child {
    margin-bottom: 0px !important;
  }

  /* #table-document tbody tr td:nth-child(3) .input-group:last-child button{
        user-select: initial;
        z-index: 1;
        opacity: 1;
        position: relative;
    } */
  .custom-file-label {
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    padding-right: 70px;
  }

  .input-group.download {
    border-bottom: 1px dashed #ddd;
    padding: 5px 0px;
  }

  .input-group.download button {
    position: absolute;
    right: 13px;
  }
</style>
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
      <li class="breadcrumb-item">Edit</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section id="content" class="content">
  <div class="container-fluid">
    <form action="{{route('contractreceipt.update',['id'=>$contractreceipt->id])}}" id="form" role="form" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="_method" value="put">
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
                    <label for="contract_id" class="col-md-12 col-xs-12 control-label">Contract</label>
                    <div class="col-sm-12 controls">
                      <input type="hidden" name="contract_id" value="{{ $contractreceipt->contract_id }}">
                      <select name="contract" id="contract_id" class="form-control select2" required data-placeholder="Select Contract" disabled>

                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="warehouse_id" class="col-md-12 col-xs-12 control-label">Warehouse</label>
                    <div class="col-sm-12 controls">
                      <input type="hidden" name="warehouse_id" value="{{ $contractreceipt->warehouse->id }}">
                      <select name="warehouse" id="warehouse_id" class="form-control select2" required data-placeholder="Select Warehouse" disabled>

                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="contract_number" class="col-md-12 col-xs-12 control-label">Contract Number</label>
                    <div class="col-sm-12 controls">
                      <input type="text" name="contract_number" id="contract_number" class="form-control" readonly placeholder="Contract Number" value="{{ $contractreceipt->contract->number }}">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="contract_date" class="col-md-12 col-xs-12 control-label">Contract Signing Date</label>
                    <div class="col-sm-12 controls">
                      <input type="text" name="contract_date" id="contract_date" class="form-control" readonly placeholder="Contract Signing Date" value="{{ date("d/m/Y", strtotime($contractreceipt->contract->contract_signing_date)) }}">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="batch_id" class="col-md-12 col-xs-12 control-label">Batch</label>
                    <div class="col-sm-12 controls">
                      <input type="hidden" name="batch" value="{{ $contractreceipt->batch }}">
                      <select name="batch_id" id="batch_id" class="form-control select2" required data-placeholder="Select Batch" disabled>

                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="role_id" class="col-md-12 col-xs-12 control-label">User</label>
                    <div class="col-sm-12 controls">
                      <select name="role_id" id="role_id" class="form-control select2" required data-placeholder="Select User" disabled>
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
                <label class="col-md-12 col-xs-12 control-label" for="remarks">Description</label>
                <div class="col-sm-12 controls">
                  <textarea class="form-control summernote" name="remarks" id="remarks" rows="4" placeholder="Description...">
                      {{ $contractreceipt->remarks }}
                  </textarea>
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
                <h5 class="text-md text-dark text-bold">Safeguard Document</h5>
              </span>
              <div class="mt-5"></div>
              <button type="button" id="add-document" class="btn btn-labeled text-sm btn-sm btn-outline-primary btn-flat btn-block legitRipple" onclick="addDocument('safeguard')">Add</button>
              <table id="table-document-safeguard" class="table table-striped datatable" width="100%">
                <thead>
                  <tr>
                    <th width="35%">Document Name</th>
                    <th width="35%">Upload Document</th>
                    <th width="10%" class="text-center">Approval</th>
                    <th width="10%" class="text-center">Upload Date</th>
                    <th width="5%" class="text-center">#</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($contractreceipt->document)
                  @foreach ($contractreceipt->document->where('document_type', 'safeguard') as $key => $item)
                  <tr data-number="{{ ++$key }}">
                    <td>
                      <input type="hidden" name="contract_document_receipts_safeguard[]" value="{{ $key }}">
                      <input type="hidden" name="contract_document_receipts_id_safeguard[{{ $key }}]" value="{{ $item->id }}">
                      <input type="hidden" name="document_type_safeguard[]" value="{{ $item->document_type }}">
                      <input type="text" class="form-control" id="document_name_safeguard{{ $key }}" name="document_name_safeguard[{{ $key }}]" placeholder="Document Name" aria-required="true" value="{{ $item->document_name }}" readonly>
                    </td>
                    <td>
                      @foreach ($item->detail as $keys => $items)
                      <div class="input-group download">
                        <a href="{{ asset($items->source) }}" class="" dl-id="44" download="" target="_blank">
                          <b><i class="fas fa-download"></i></b> Download - Rev {{ ++$keys }}
                        </a>
                        <button type="button" class="btn btn-transparent text-md p-0 pl-2 float-right" onclick="removeFile($(this))" data-type="safeguard" data-doc="{{ $keys }}" data-id="{{ $items->id }}">
                          <i class="fas fa-trash text-maroon color-palette"></i>
                        </button>
                      </div>
                      @endforeach
                      @if ($item->detail->where('status', 'Approved')->count() == 0)
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="hidden" name="file_contract_safeguard[{{ $key }}][]">
                          <input type="file" class="custom-file-input" name="file_safeguard[{{ $key }}][]" onchange="changePath(this)">
                          <label class="custom-file-label" for="exampleInputFile">Attach Image</label>
                        </div>
                        <div class="input-group-append">
                          <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
                        </div>
                        <button type="button" class="btn btn-transparent text-md" onclick="addUpload($(this))" data-doc="{{ $key }}">
                          <i class="fas fa-plus text-green color-palette"></i>
                        </button>
                      </div>
                      @endif
                    </td>
                    <td class="text-center" style="padding-top: .5rem;">
                      @foreach ($item->detail as $keyApproval => $itemApproval)
                      @switch($itemApproval->status)
                      @case('Approved')
                      <div class="mt-2"></div>
                      <span class="badge bg-success">{{ $itemApproval->status }}</span>
                      <div class="mb-2"></div>
                      @break
                      @case('Rejected')
                      <div class="mt-2"></div>
                      <span class="badge bg-maroon">{{ $itemApproval->status }}</span>
                      <div class="mb-2"></div>
                      @break
                      @default
                      <div class="mt-1"></div>
                      <button type="button" class="btn text-sm btn-sm bg-green btn-flat legitRipple" onclick="approveDocument('Approved', {{ $itemApproval->id }})" data-toggle="tooltip" data-placement="top" title="Approved Request">
                        <b><i class="fas fa-check"></i></b>
                      </button>
                      <button type="button" class="btn text-sm btn-sm bg-red btn-flat legitRipple" onclick="approveDocument('Rejected', {{ $itemApproval->id }})" data-toggle="tooltip" data-placement="top" title="Reject Request">
                        <b><i class="fas fa-times"></i></b>
                      </button>
                      @endswitch
                      @endforeach
                    </td>
                    <td class="text-center">
                      @foreach ($item->detail as $keyDocument => $itemDocument)
                      <div class="mb-1"></div>
                      <span>{{ date("d/m/Y", strtotime($itemDocument->upload_date)) }}</span>
                      @endforeach
                      <div class="mt-3"></div>
                      <span>{{ date("d/m/Y") }}</span>
                    </td>
                    <td class="text-center">
                      {{-- <button type="button" class="btn btn-transparent text-md" onclick="removeDocument($(this))" data-document="{{ $i }}">
                      <i class="fas fa-trash text-maroon color-palette"></i>
                      </button> --}}
                      <input type="hidden" name="deleted_file_id_safeguard[{{ $key }}]" value="[]">
                      <div class="mb-1"></div>
                      #
                    </td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div>
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">User Document</h5>
              </span>
              <div class="mt-5"></div>
              <button type="button" id="add-document" class="btn btn-labeled text-sm btn-sm btn-outline-primary btn-flat btn-block legitRipple" onclick="addDocument('user')">Add</button>
              <table id="table-document-user" class="table table-striped datatable" width="100%">
                <thead>
                  <tr>
                    <th width="40%">Document Name</th>
                    <th width="40%">Upload Document</th>
                    <th width="10%" class="text-center">Upload Date</th>
                    <th width="5%" class="text-center">#</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($contractreceipt->document)
                  @foreach ($contractreceipt->document->where('document_type', 'user') as $key => $item)
                  <tr data-number="{{ ++$key }}" data-type="user_document">
                    <td>
                      <input type="hidden" name="contract_document_receipts_user[]" value="{{ $key }}">
                      <input type="hidden" name="contract_document_receipts_id_user[{{ $key }}]" value="{{ $item->id }}">
                      <input type="hidden" name="document_type_user[]" value="user">
                      <input type="text" class="form-control" id="document_name_user{{ $key }}" name="document_name_user[{{ $key }}]" placeholder="Document Name" aria-required="true" value="{{ $item->document_name }}">
                    </td>
                    <td>
                      @foreach ($item->detail as $keys => $items)
                      <div class="input-group download">
                        <a href="{{ asset($items->source) }}" class="" dl-id="44" download="" target="_blank">
                          <b><i class="fas fa-download"></i></b> Download - Rev {{ ++$keys }}
                        </a>
                        <button type="button" class="btn btn-transparent text-md p-0 pl-2 float-right" onclick="removeFile($(this))" data-type="user" data-doc="{{ $keys }}" data-id="{{ $items->id }}">
                          <i class="fas fa-trash text-maroon color-palette"></i>
                        </button>
                      </div>
                      @endforeach
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="hidden" name="file_contract_user[{{ $key }}][]">
                          <input type="file" class="custom-file-input" name="file_user[{{ $key }}][]" onchange="changePath(this)">
                          <label class="custom-file-label" for="exampleInputFile">Attach Image</label>
                        </div>
                        <div class="input-group-append">
                          <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
                        </div>
                        <button type="button" class="btn btn-transparent text-md" onclick="addUpload($(this))" data-doc="{{ $key }}">
                          <i class="fas fa-plus text-green color-palette"></i>
                        </button>
                      </div>
                    </td>
                    <td class="text-center">
                      <div class="mb-1"></div>
                      {{ date("d/m/Y") }}
                    </td>
                    <td class="text-center">
                      {{-- <button type="button" class="btn btn-transparent text-md" onclick="removeDocument($(this))" data-document="{{ $i }}">
                      <i class="fas fa-trash text-maroon color-palette"></i>
                      </button> --}}
                      <input type="hidden" name="deleted_file_id_user[{{ $key }}]" value="[]">
                      <div class="mb-1"></div>
                      #
                    </td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div>
            <div class="card-footer">
              <input type="hidden" name="status" value="{{ $contractreceipt->status }}">
              <button type="button" class="btn bg-success color-palette btn-labeled legitRipple text-sm btn-sm" onclick="submitTest('COMPLETED')">
                <b><i class="fas fa-check-circle"></i></b>
                Approved
              </button>
              <button type="button" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm" onclick="submitTest('CHECKSTATUS')">
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

  const submitTest = (status) => {
    if (status) {
      $('input[name=status]').val(status);
    }
    $("form").first().trigger("submit");
  }

  const approveDocument = (type, document_id) => {
    var title = type == 'Approved' ? 'Approve document?' : 'Reject document?';
    var message = type == 'Approved' ? 'Are you sure want to approve this document?' : 'Are you sure want to reject this document?';
    bootbox.confirm({
      buttons: {
        confirm: {
          label: `<i class="fa fa-check"></i>`,
          className: 'btn-primary btn-sm',
        },
        cancel: {
          label: '<i class="fa fa-undo"></i>',
          className: 'btn-default btn-sm',
        },
      },
      title: title,
      message: message,
      callback: function (result) {
          if (result) {
              var data = {
                  _token: "{{ csrf_token() }}",
                  type: type,
                  document_id: document_id,
              };
              $.ajax({
                  url: `{{route('contractreceipt.approval')}}`,
                  dataType: 'json',
                  data: data,
                  method: 'post',
                  beforeSend: function () {
                      blockMessage('#content', 'Loading', '#fff');
                  }
              }).done(function (response) {
                  $('#content').unblock();
                  if (response.status) {
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
                      }
                      toastr.success(response.message);
                      setTimeout(function(){location.reload()}, 3000);
                  }else {
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
                      }
                      toastr.warning(response.message);
                  }
              }).fail(function (response) {
                  var response = response.responseJSON;
                  $('#content').unblock();
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
                  }
                  toastr.warning(response.message);
              })
          }
      }
    });
  }

  const addDocument = (type) => {
    var number  = $(`#table-document-${type}`).find('tr:last').data('number') ? $(`#table-document-${type}`).find('tr:last').data('number') + 1 : 1;
    var dateNow = moment().format('DD/MM/YYYY');
    var html = `
    <tr data-number="${number}">
      <td>
        <input type="hidden" name="contract_document_receipts_${type}[]" value="${number}">
        <input type="hidden" name="document_type_${type}[]" value="${type}">
        <input type="text" class="form-control" id="document_name_${number}" name="document_name_${type}[${number}]" placeholder="Document Name" aria-required="true">
      </td>
      <td>
        <div class="input-group">
          <div class="custom-file">
            <input type="hidden" name="file_contract_${type}[${number}][]">
            <input type="file" class="custom-file-input" name="file_${type}[${number}][]" onchange="changePath(this)">
            <label class="custom-file-label" for="exampleInputFile">Attach Image</label>
          </div>
          <div class="input-group-append">
            <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
          </div>
          <button type="button" class="btn btn-transparent text-md" onclick="addUpload($(this))" data-doc="${number}">
            <i class="fas fa-plus text-green color-palette"></i>
          </button>
        </div>
      </td>
      <td class="text-center">
        <div class="mb-1"></div>
        ${dateNow}
      </td>
      <td class="text-center">
        <button type="button" class="btn btn-transparent text-md" onclick="removeDocument($(this))" data-document=${number} data-type="${type}">
          <i class="fas fa-trash text-maroon color-palette"></i>
        </button>
      </td>
    </tr>
    `;
    $(`#table-document-${type} tbody .empty`).remove();
    $(`#table-document-${type} tbody`).append(html);
  }

  const addUpload = (e) => {
    var number = e.attr("data-doc");
    var html = `
        <div class="input-group">
            <div class="custom-file">
                <input type="hidden" name="file_contract[${number}][]">
                <input type="file" class="custom-file-input" name="file[${number}][]" onchange="changePath(this)">
                <label class="custom-file-label" for="exampleInputFile">Attach Image</label>
            </div>
            <div class="input-group-append">
                <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
            </div>
            <button type="button" class="btn btn-transparent text-md" onclick="removeUpload($(this))">
                <i class="fas fa-trash text-maroon color-palette"></i>
            </button>
        </div>
    `;
    e.parents("td").append(html);
    // alert("oke");
  }

  const removeUpload = (e) => {
    e.parent().remove();
  }

  const removeFile = (e) => {
    var number = e.attr("data-doc");
    var type = e.attr("data-type");
    var id = e.attr("data-id");
    var deleted = type == 'safeguard' ? JSON.parse($(`input[name="deleted_file_id_safeguard[${number}]"]`).val()) : JSON.parse($(`input[name="deleted_file_id_user[${number}]"]`).val());
    deleted.push(id);
    type == 'safeguard' ? $(`input[name="deleted_file_id_safeguard[${number}]"]`).val(JSON.stringify(deleted)) : $(`input[name="deleted_file_id_user[${number}]"]`).val(JSON.stringify(deleted));
    e.parent().remove();
  }

  const removeFile2 = (e) => {
    var type = e.attr("data-type");
    var id = e.attr("data-id");
    bootbox.confirm({
      buttons: {
        confirm: {
          label: `<i class="fa fa-check"></i>`,
          className: 'btn-primary btn-sm',
        },
        cancel: {
          label: '<i class="fa fa-undo"></i>',
          className: 'btn-default btn-sm',
        },
      },
      title: "Delete document?",
      message: "Are you sure want to delete this document?",
      callback: function (result) {
          if (result) {
              var data = {
                  _token: "{{ csrf_token() }}",
                  type: type,
                  id: id,
              };
              $.ajax({
                  url: `{{route('contractreceipt.approval')}}`,
                  dataType: 'json',
                  data: data,
                  method: 'post',
                  beforeSend: function () {
                      blockMessage('#content', 'Loading', '#fff');
                  }
              }).done(function (response) {
                  $('#content').unblock();
                  if (response.status) {
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
                      }
                      toastr.success(response.message);
                      setTimeout(function(){location.reload()}, 3000);
                  }else {
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
                      }
                      toastr.warning(response.message);
                  }
              }).fail(function (response) {
                  var response = response.responseJSON;
                  $('#content').unblock();
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
                  }
                  toastr.warning(response.message);
              })
          }
      }
    });
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
    
    $('.summernote').summernote('disable');
    $(".select2").select2();

    $('#contract_id').select2({
      ajax: {
        url: "{{ route('contractreceipt.selectcontract') }}",
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
              number: item.number,
              exp_status: item.exp_status,
              contract_date: item.contract_date,
            });
          });
          return { results: option, more: more };
        },
      },
      allowClear: true,
      templateSelection: selectionContract,
      templateResult: resultContract
    });
    $("#contract_id").select2("trigger", "select", {
        data: {
            id:'{{ $contractreceipt->contract_id }}', 
            text:'{{ $contractreceipt->contract->title }}',
            number: '{{ $contractreceipt->contract->number }}',
            exp_status: '{{ $contractreceipt->contract->exp_status }}',
            contract_date: '{{ $contractreceipt->contract->contract_signing_date }}',
        }
    });

    $("#role_id").select2({
        ajax: {
            url: "{{route('role.select')}}",
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
    $('#role_id').select2('trigger', 'select', {
      data: {
        id: `{{ $contractreceipt->role_id }}`,
        text: `{{ $contractreceipt->role->name }}`
      }
    });

    $('#warehouse_id').select2({
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
    $("#warehouse_id").select2("trigger", "select", {
        data: {
            id:'{{ $contractreceipt->warehouse_id }}', 
            text:'{{ $contractreceipt->warehouse->name }}',
        }
    });

    $('#batch_id').select2({
      ajax: {
        url: "{{ route('contractreceipt.selectbatch') }}",
        type: "GET",
        dataType: "json",
        data: function (params) {
          var contract_id = $("#contract_id").val();
          return {
            name: params.term,
            page: params.page,
            limit: 30,
            contract_id: contract_id,
          };
        },
        processResults: function (data, params) {
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item){
            option.push({
              id:item.no,
              text: "Batch "+item.no,
            });
          });
          return { results: option, more: more };
        },
      },
      allowClear: true,
    });
    $("#batch_id").select2("trigger", "select", {
        data: {
            id:'{{ $contractreceipt->batch }}',
            text: "Batch "+'{{ $contractreceipt->batch }}',
        }
    });

    $("#contract_id").on("change",() => {
        var contract_id = $("#contract_id").val();
        if(contract_id){
            var number = $("#contract_id").select2("data")[0].number;
            var contract_date = $("#contract_id").select2("data")[0].contract_date;
            $("#contract_number").val(number);
            $("#contract_date").val(contract_date);
            $("#batch_id").removeAttr("disabled");
        }else{
            $("#contract_number").val("");
            $("#contract_date").val("");
            $("#batch_id").attr("disabled","disabled");
        }
    })

    $("#form").validate({
        errorElement: 'span',
        errorClass: 'help-block',
        focusInvalid: false,
        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
            $(e).remove();
        },
        errorPlacement: function (error, element) {
            if(element.is(':file')) {
                error.insertAfter(element.parent().parent().parent());
            }else if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            }else if (element.attr('type') == 'checkbox') {
                error.insertAfter(element.parent());
            }else{
                error.insertAfter(element);
            }
        },
        submitHandler: function() { 
            $.ajax({
                url:$('#form').attr('action'),
                method:'post',
                data: new FormData($('#form')[0]),
                processData: false,
                contentType: false,
                dataType: 'json', 
                beforeSend:function(){
                    blockMessage('#content', 'Loading', '#fff');
                }
            }).done(function(response){
                $('#content').unblock();
                if(response.status){
                    document.location = response.results;
                }else{	
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
                    }
                    toastr.warning(response.message);
                }
                return;
            }).fail(function(response){
                $('#content').unblock();
                var response = response.responseJSON;
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
                }
                toastr.warning(response.message);
            })	
        }
    });

  });

    function resultContract(state){
        if (!state.id) {
            return state.text;
        }
        var $state = $(`
            <span>${state.text}</span><span class="float-right">${state.number}</span><br>
            <small>${state.exp_status}</small>
        `);
        return $state;
    }

    function selectionContract(state) {
        if (!state.id) {
            return state.text;
        }
        var $state = $(`<span>${state.text}</span> - <span>${state.number}</span>`);
        return $state;
    };
</script>
@endsection