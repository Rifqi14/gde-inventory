@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')
<style>
  input[type=file][readonly] {
    pointer-events: none !important;
    background-color: red;
    /* touch-action: none; */
  }
</style>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">Edit {{ $menu_name }}</h1>
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
<section class="content" id="content">
  <div class="container-fluid">
    <form action="{{ route('outcoming.store') }}" method="post" id="form" autocomplete="off" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id" value="{{ $data->id }}">
      <input type="hidden" name="status" value="{{ $data->status }}">
      <input type="hidden" name="sender_alias" value="{{ $data->sender_alias }}">
      <input type="hidden" name="recipient_alias" value="{{ $data->recipient_alias }}">
      <input type="hidden" name="tab" value="{{ request()->code }}">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6 pr-4">
              <span class="title p-0">
                <hr>
                <h5 class="text-md text-dark text-bold">Transmittal Properties</h5>
              </span>
              <div class="mt-5"></div>
              <div class="form-group row">
                <label for="transmittal_no" class="col-form-label col-sm-3">Transmittal No.</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="transmittal_no" id="transmittal_no" class="form-control" placeholder="Auto fill data" value="{{ $data->transmittal_no }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="transmittal_date" class="col-form-label col-sm-3">Transmittal Date</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="transmittal_date" id="transmittal_date" class="form-control" placeholder="Auto fill data" value="{{ date("d/m/Y", strtotime($data->transmittal_date)) }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="gde_contract_no" class="col-form-label col-sm-3">GDE Contract No.</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="gde_contract_no" id="gde_contract_no" class="form-control" placeholder="Please input GDE Contract No..." value="{{ $data->gde_contract_no }}" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="gde_contract_title" class="col-form-label col-sm-3">GDE Contract Title</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="gde_contract_title" id="gde_contract_title" class="form-control" placeholder="Please input GDE Contract Title..." value="{{ $data->gde_contract_title }}" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="transmittal_title" class="col-form-label col-sm-3">Transmittal Title</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="transmittal_title" id="transmittal_title" class="form-control" placeholder="Please input Transmittal Title..." value="{{ $data->transmittal_title }}" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="transmittal_remark" class="col-form-label col-sm-3">Transmittal Remark</label>
                <div class="col-sm-9 p-0">
                  <textarea name="transmittal_remark" id="transmittal_remark" rows="5" class="form-control summernote" placeholder="Please input Transmittal Remark...">{{ $data->transmittal_remark }}</textarea>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <span class="title p-0">
                <hr>
                <h5 class="text-md text-dark text-bold">General Information</h5>
              </span>
              <div class="mt-5"></div>
              <div class="form-group row">
                <label for="sender_id" class="col-form-label col-sm-3">Sender</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="sender_id" id="sender_id" class="form-control" placeholder="Auto fill data" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="contractor_group_id" class="col-form-label col-sm-3">Contractor Group</label>
                <div class="col-sm-9 p-0">
                  <select name="contractor_group_id" id="contractor_group_id" class="form-control select2 ajax-select" data-url="contractor" required></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="sender_address" class="col-form-label col-sm-3">Sender Address</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="sender_address" id="sender_address" class="form-control" placeholder="Auto fill data" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="recipient_address" class="col-form-label col-sm-3">Recipient Address</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="recipient_address" id="recipient_address" class="form-control" placeholder="Auto fill data" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="attention_id" class="col-form-label col-sm-3">Attention</label>
                <div class="col-sm-9 p-0">
                  <select name="attention_id[]" id="attention_id" class="form-control select2 ajax-select" data-url="attention" multiple required></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="cc" class="col-form-label col-sm-3">CC</label>
                <div class="col-sm-9 p-0">
                  <select name="cc[]" id="cc" class="form-control select2 ajax-select" data-url="cc" multiple required></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="issued_by" class="col-form-label col-sm-3">Issued By</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="issued_by" id="issued_by" class="form-control" value="{{ $data->issuedby->name }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="status" class="col-form-label col-sm-3">Status</label>
                <div class="col-sm-9 py-2">
                  @switch($data->status)
                  @case('ISSUED')
                  <span class="badge badge-success">Issued</span>
                  @break
                  @default
                  <span class="badge badge-secondary">Draft</span>
                  @endswitch
                </div>
              </div>
            </div>
            <div class="col-sm-12">
              <span class="title p-0">
                <hr>
                <h5 class="text-md text-dark text-bold">Tag Document</h5>
              </span>
              <div class="mt-5"></div>
              <div class="table-responsive">
                <table class="table table-striped datatable" id="table-document" width="100%">
                  <thead>
                    <tr>
                      <th width="20%">Drawing/Document No</th>
                      <th width="20%">Revision</th>
                      <th width="20%">Issue Status</th>
                      <th width="10%">Issue Purpose</th>
                      <th width="10%">Sheet Size</th>
                      <th width="10%">Review Status</th>
                      <th width="10%">Action</th>
                    </tr>
                  </thead>
                  <tbody class="document-tag-body">
                    <tr class="document-tag" data-order="1">
                      <input type="hidden" name="revision" data-document_no="" data-revision="" data-issue_status="" data-issue_purpose="" data-sheet_size="" data-review_status="">
                      <td>
                        <select name="revision_id[]" class="form-control select2 revision" data-url="revision" onchange="setTableData($(this))"></select>
                      </td>
                      <td data-column="revision"></td>
                      <td data-column="issue_status"></td>
                      <td data-column="issue_purpose"></td>
                      <td data-column="sheet_size"></td>
                      <td data-column="review_status"></td>
                      <td data-column="action">
                        <button class="btn btn-transparent text-md" type="button"><i class="fas fa-plus text-green color-palette" onclick="addDocumentTag()"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-sm-12">
              <span class="title p-0">
                <hr>
                <h5 class="text-md text-dark text-bold">Document Upload</h5>
              </span>
              <div class="mt-5"></div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="sender_signed_copy" class="col-form-label col-sm-3">Sender Signed Copy</label>
                    <div class="col-sm-3 pl-0">
                      <input type="text" class="form-control" name="sender_signed_copy_name" placeholder="File Name" value="{{ $data->sender_file_name }}">
                    </div>
                    <div class="col-sm-4 pl-0">
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name="sender_signed_copy" id="sender_signed_copy" onchange="initInputFile()">
                          <label class="custom-file-label" for="sender_signed_copy">Attach a file</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2 pl-0">
                      <a href="{{ $data->sender_signed_copy }}" target="_blank" class="btn btn-md btn-primary color-palette btn-labeled legitRipple text-sm"><b><i class="fas fa-download"></i></b> Downdload</a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 @if ($data->status == 'DRAFT') d-none @endif">
                  <div class="form-group row">
                    <label for="recipient_signed_copy" class="col-form-label col-sm-3">Recipient Signed Copy</label>
                    <div class="col-sm-5 pl-0">
                      <input type="text" class="form-control" name="recipient_signed_copy_name" placeholder="File Name">
                    </div>
                    <div class="col-sm-4 pl-0">
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name="recipient_signed_copy" id="recipient_signed_copy" onchange="initInputFile()">
                          <label class="custom-file-label" for="recipient_signed_copy">Attach a file</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <a href="{{ route('outcoming.generatepdf', ['id' => $data->id]) }}" target="_self" class="btn bg-primary color-palette btn-labeled legitRipple text-sm btn-sm d-none generate-pdf"><b><i class="fas fa-file-pdf"></i></b> Generate PDF</a>
          <button type="button" data-status="DRAFT" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm" form="form" onclick="saveOutcoming($(this))"><b><i class="fas fa-save"></i></b> Draft</button>
          <button type="button" data-status="ISSUED" class="btn bg-success color-palette btn-labeled legitRipple text-sm btn-sm" form="form" onclick="saveOutcoming($(this))"><b><i class="fas fa-check-circle"></i></b> Issued</button>
          <a href="{{ route('outcoming.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm"><b><i class="fas fa-times"></i></b> Cancel</a>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection

@section('scripts')
<script>
  var actionmenu  = @json(json_encode($actionmenu));
  var base        = "{{ url('admin') }}";
  var token       = "{{ csrf_token() }}";
  var user_id     = "{{ auth()->user()->id }}";
  var tab         = "{{ request()->code }}";
  var select2     = $('select.ajax-select');
  var choosenOption = {};
  var lastSelected;
  var data        = @json($data);
</script>
<script src="{{ asset("js/inoutcoming.js") }}"></script>
<script>
  const initDocument = data => {
    var html = ``;
    var no = 1;
    $.each(data, function(index, item) {
      html += `<tr class="document-tag" data-order="${++no}">
                <input type="hidden" name="revision" data-document_no="${item.document.document_number}" data-revision="${item.revision_no}" data-issue_status="${item.issue_status}" data-issue_purpose="${item.document.document_category_id}" data-sheet_size="${item.sheetsize.name}" data-review_status="${item.workflow.return_code}">
                <td>
                  ${item.document.document_number}
                </td>
                <td data-column="revision">${item.revision_no}</td>
                <td data-column="issue_status">${item.issue_status}</td>
                <td data-column="issue_purpose">${item.document.document_category_id}</td>
                <td data-column="sheet_size">${item.sheetsize.name}</td>
                <td data-column="review_status">${item.workflow.return_code}</td>
                <td data-column="action">
                  <button class="btn btn-transparent text-md" type="button" data-id="${'{{ $data->id }}'}" data-document_id="${item.id}" onclick="destroyDocument($(this))"><i class="fas fa-trash text-maroon color-palette"></i></button>
                </td>
              </tr>`;
    });
    $('.document-tag-body').append(html);
  };
  $(function() {
    if (data.status == 'DRAFT') {
      $(`.generate-pdf`).removeClass('d-none');
    }
    if (data.contractorgroup) {
      $("#contractor_group_id").select2('trigger', 'select', {
        data: {
          id: data.contractorgroup.id,
          text: `${data.contractorgroup.name}`,
        }
      });
    }
    if (data.attentions) {
      var selected = [];
      $.each(data.attentions, function(index, item){
        selected.push({
          id: item.id,
          text: `${item.name}`,
          selected: true,
        });
      });
      $("#attention_id").select2({
        data: selected,
      });
    }
    if (data.ccs) {
      var selected = [];
      $.each(data.ccs, function(index, item) {
        selected.push({
          id: item.id,
          text: `${item.name}`,
          selected: true,
        });
      });
      $("#cc").select2({
        data: selected
      });
    }
    if (data.documents) {
      initDocument(data.documents);
    }
    initSelect2();
  });
</script>
@endsection