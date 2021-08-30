@extends('admin.layouts.app')
@section('title', $external->document_title)

@section('stylesheets')
<style>
  select[readonly].select2-hidden-accessible+.select2-container {
    pointer-events: none;
    touch-action: none;
  }

  select[readonly].select2-hidden-accessible+span .select2-selection {
    background: #e9ecef;
    box-shadow: none;
  }

  input[type=file][readonly] {
    pointer-events: none;
    touch-action: none;
  }

  input[type=file][readonly]+label.custom-file-label {
    background: #e9ecef;
  }
</style>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">{{ $external->document_title }}</h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name }}</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
      <li class="breadcrumb-item">{{ $external->document_title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <form action="{{ route('documentcenterexternal.update', ['id' => $external->id]) }}" method="POST" role="form" enctype="multipart/form-data" autocomplete="off" id="form">
      @csrf
      @method('PUT')
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-7 pr-5">
              <span class="title p-0">
                <hr>
                <h5 class="text-md text-dark text-bold">Document Properties</h5>
              </span>
              <div class="mt-4"></div>
              <div class="form-group row">
                <label for="document_number" class="col-form-label col-md-3">Drawing & Document No.</label>
                <div class="col-md-9 p-0">
                  <input type="text" name="document_number" id="document_number" class="form-control lock" placeholder="Drawing & Document No..." value="{{ $external->document_number }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="document_title" class="col-form-label col-md-3">Document Title</label>
                <div class="col-md-9 p-0">
                  <textarea name="document_title" id="document_title" rows="5" class="form-control lock" placeholder="Document Title ...">{{ $external->document_title }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="site_code_id" class="col-form-label col-md-3">Site Code</label>
                <div class="col-md-3 p-0">
                  <select name="site_code_id" id="site_code_id" class="form-control select2 lock" data-select_name="site_code" data-sub_url="sitecode"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="discipline_code_id" class="col-form-label col-md-3">Discipline Code</label>
                <div class="col-md-3 p-0">
                  <select name="discipline_code_id" id="discipline_code_id" class="form-control select2 lock" data-select_name="discipline_code" data-sub_url="disciplinecode"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="kks_category_id" class="col-form-label col-md-3">KKS Category</label>
                <div class="col-md-3 p-0">
                  <select name="kks_category_id" id="kks_category_id" class="form-control select2 lock"></select>
                </div>
                <div class="col-md-6 pr-0"><input type="text" name="kks_category_label" id="kks_category_label" readonly class="form-control"></div>
              </div>
              <div class="form-group row">
                <label for="kks_code_id" class="col-form-label col-md-3">KKS Code</label>
                <div class="col-md-3 p-0">
                  <select name="kks_code_id" id="kks_code_id" class="form-control select2 lock"></select>
                </div>
                <div class="col-md-6 pr-0"><input type="text" name="kks_code_label" id="kks_code_label" readonly class="form-control"></div>
              </div>
              <div class="form-group row">
                <label for="document_type_id" class="col-form-label col-md-3">Document Type</label>
                <div class="col-md-3 p-0">
                  <select name="document_type_id" id="document_type_id" class="form-control select2 lock" data-select_name="document_type" data-sub_url="documenttypeext"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="originator_code_id" class="col-form-label col-md-3">Originator Code</label>
                <div class="col-md-3 p-0">
                  <select name="originator_code_id" id="originator_code_id" class="form-control select2 lock" data-select_name="originator_code" data-sub_url="originatorcode"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="phase_code_id" class="col-form-label col-md-3">Phase Code</label>
                <div class="col-md-3 p-0">
                  <select name="phase_code_id" id="phase_code_id" class="form-control select2" data-select_name="phase_code" data-sub_url="phasecode"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="document_sequence" class="col-form-label col-md-3">Document Sequence</label>
                <div class="col-md-9 p-0">
                  <input type="text" name="document_sequence" id="document_sequence" class="form-control text-right lock" placeholder="Document Sequence..." value="{{ $external->document_sequence }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="document_category_id" class="col-form-label col-md-3">Document Category</label>
                <div class="col-md-3 p-0">
                  <select name="document_category_id" id="document_category_id" class="form-control select2 lock" data-placeholder="Please choose data">
                    <option value=""></option>
                    @foreach (config('enums.document_category') as $key => $item)
                    <option value="{{ $key }}" @if ($external->document_category_id == $key) selected @endif data-label="{{ $item }}">{{ $key }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6 pr-0">
                  <input type="text" name="document_category_label" id="document_category_label" class="form-control" value="{{ config("enums.document_category.$external->document_category_id") }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="reviewer_matrix" class="col-form-label col-md-3">Reviewer Matrix</label>
                <div class="col-md-9 p-0">
                  <button type="button" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm lock" name="reviewer_matrix" id="reviewer_matrix" onclick="reviewerMatrix()">
                    <b><i class="fas fa-pencil-alt"></i></b> Reviewer Matrix
                  </button>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <span class="title p-0">
                <hr>
                <h5 class="text-md text-dark text-bold">General Information</h5>
              </span>
              <div class="mt-4"></div>
              <div class="form-group row">
                <label for="contract_document_no" class="col-form-label col-md-3">Contractor Document No</label>
                <div class="col-md-9 p-0">
                  <input type="text" name="contract_document_no" id="contract_document_no" class="form-control lock" placeholder="Contract Document No ..." value="{{ $external->contractor_document_number }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="contractor_name_id" class="col-form-label col-md-3">Contractor Name</label>
                <div class="col-md-9 p-0">
                  <select name="contractor_name_id" id="contractor_name_id" class="form-control select2 lock"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="contractor_group_id" class="col-form-label col-md-3">Contractor Group</label>
                <div class="col-md-9 p-0">
                  <select name="contractor_group_id" id="contractor_group_id" class="form-control select2 lock"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="planned_ifi_ifa_date" class="col-form-label col-md-3">Planned IFI/IFA Date</label>
                <div class="col-md-9 p-0">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" name="planned_ifi_ifa_date" id="planned_ifi_ifa_date" class="form-control text-right datepicker lock" value="{{ date('d/m/Y', strtotime($external->planned_ifi_ifa_date)) }}">
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="planned_ifc_ifu_date" class="col-form-label col-md-3">Planned IFC/IFU Date</label>
                <div class="col-md-9 p-0">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" name="planned_ifc_ifu_date" id="planned_ifc_ifu_date" class="form-control text-right datepicker lock" value="{{ date('d/m/Y', strtotime($external->planned_ifc_ifu_date)) }}">
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="planned_afc_date" class="col-form-label col-md-3">Planned AFC Date</label>
                <div class="col-md-9 p-0">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" name="planned_afc_date" id="planned_afc_date" class="form-control text-right datepicker lock" value="{{ date('d/m/Y', strtotime($external->planned_afc_date)) }}">
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="planned_ab_date" class="col-form-label col-md-3">Planned AB Date</label>
                <div class="col-md-9 p-0">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" name="planned_ab_date" id="planned_ab_date" class="form-control text-right datepicker lock" value="{{ date('d/m/Y', strtotime($external->planned_ab_date)) }}">
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="created_by" class="col-form-label col-md-3">Created by</label>
                <div class="col-md-9 p-0">
                  <input type="text" name="created_by" id="created_by" class="form-control" value="{{ $external->createdby->name }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="created_at" class="col-form-label col-md-3">Created Date</label>
                <div class="col-md-9 p-0">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" name="created_at" id="created_at" class="form-control text-right datepicker" value="{{ date('d/m/Y', strtotime($external->created_at)) }}" disabled>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="updated_by" class="col-form-label col-md-3">Last Modified by</label>
                <div class="col-md-9 p-0">
                  <input type="text" name="updated_by" id="updated_by" class="form-control" value="{{ auth()->user()->name }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="updated_at" class="col-form-label col-md-3">Last Modified Date</label>
                <div class="col-md-9 p-0">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" name="updated_at" id="updated_at" class="form-control text-right datepicker" disabled>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="remark" class="control-label col-md-3">Document Remarks</label>
                <textarea class="form-control summernote col-md-8 d-none lock" name="remark" id="remark" rows="4" placeholder="Remark...">{{ $external->document_remark }}</textarea>
              </div>
              <input type="hidden" name="page" value="{{ $page }}">
              <input type="hidden" name="edit_status">
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <div class="unlock d-none">
            <button type="button" class="btn bg-danger color-palette btn-labeled legitRipple text-sm btn-sm" onclick="lockedFormButton('unlock')">
              <b><i class="fas fa-edit"></i></b> Edit
            </button>
          </div>
          <div class="locked d-none">
            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm" form="form">
              <b><i class="fas fa-save"></i></b> Submit
            </button>
            <a href="javascript:void(0);" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm" onclick="lockedFormButton('locked')">
              <b><i class="fas fa-times"></i></b> Cancel
            </a>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header text-right">
          <button type="button" class="btn btn-labeled text-md btn-md btn-success btn-flat legitRipple" id="revision-btn" onclick="revisionModal('create')">
            <b><i class="fas fa-plus"></i></b> Create
          </button>
        </div>
      </div>
      <div class="card-body table-responsive p-0">
        <table id="table-revision" class="table table-striped datatable" width="100%">
          <thead>
            <tr>
              <th width="7%">Revision No.</th>
              <th width="10%">Status</th>
              <th width="10%">Issue Status</th>
              <th width="20%">Transmittal Status</th>
              <th width="20%">Issued By</th>
              <th width="20%">File List</th>
              <th width="10%">Comment Status</th>
              <th width="5%">Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </form>
  </div>
</section>

<div class="modal fade" id="modal-reviewer-matrix">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Reviewer Matrix</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-reviewer-matrix" action="{{ route('documentcenterexternal.updatematrix', ['id' => $external->id]) }}" autocomplete="off" method="POST">
          @csrf
          @method('PUT')
          <div class="row">
            <input type="hidden" name="page" value="{{ $page }}">
            <input type="hidden" name="matrix_id" value="{{ $external->id }}">
            <input type="hidden" name="crud_status" value="update">
            @foreach (config('enums.reviewer_matrix') as $key => $matrix)
            <div class="col-md-12">
              <div class="row">
                <input type="hidden" name="reviewer_matrix[{{ $key }}][label]" value="{{ $matrix['label'] }}">
                <div class="col-md-6 mr-4">
                  <div class="form-group row">
                    <label for="{{ $key }}" class="col-form-label col-md-3">{{ $matrix['label'] }}</label>
                    <select name="reviewer_matrix[{{ $key }}][group][]" id="{{ $key }}" class="select2 form-control col-md-9 @if ($matrix['required']) required @endif" data-max_tag="{{ $matrix['group']['max_tag'] }}">
                    </select>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group row">
                    <label for="{{ $key }}_sla" class="col-form-label col-md-2">SLA</label>
                    <div class="icheck-primary col-md-1">
                      <input type="checkbox" name="reviewer_matrix[{{ $key }}][sla]" id="{{ $key }}_sla" onclick="checkedSLA($(this))">
                      <label for="{{ $key }}_sla"></label>
                    </div>
                    <div class="col-md-9">
                      <div class="input-group col-md-12">
                        <input type="text" name="reviewer_matrix[{{ $key }}][days]" id="{{ $key }}_days" class="form-control text-right" placeholder="Calendar Days ...">
                        <div class="input-group-append">
                          <span class="input-group-text">Calendar Days</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <button type="submit" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" form="form-reviewer-matrix">
          <b><i class="fas fa-save"></i></b>
          Save
        </button>
        <button type="button" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" data-dismiss="modal">
          <b><i class="fas fa-times"></i></b>
          Close
        </button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-revision" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modal-revision" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Create a Revision</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('revision.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data" id="form-revision">
          @csrf
          <input type="hidden" name="_method">
          <input type="hidden" name="id">
          <input type="hidden" name="document_external_id" value="{{ $external->id }}">
          <input type="hidden" name="revision_crud_status">
          <input type="hidden" name="status">
          <div class="row">
            <div id="document-properties" class="col-md-12 row">
              <div class="col-md-12">
                <span class="title">
                  <hr>
                  <h5 class="text-md text-dark text-bold">Document Properties</h5>
                </span>
                <div class="mt-5"></div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="document_number" class="col-form-label">Document No.</label>
                  <input type="text" name="document_number" id="document_number" class="form-control" placeholder="Document Number" value="{{ $external->document_number }}" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="title" class="col-form-label">Document Title</label>
                  <input type="text" name="title" id="title" class="form-control" placeholder="Document Title" value="{{ $external->document_title }}" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="document_category" class="col-form-label">Document Category</label>
                  <div class="row">
                    <div class="col-md-3">
                      <input type="text" id="document_category" class="form-control" placeholder="Document Category" readonly value="{{ $external->document_category_id }}">
                    </div>
                    <div class="col-md-9">
                      <input type="text" id="document_category_label" class="form-control" placeholder="Document Category" readonly value="{{ config("enums.document_category.{$external->document_category_id}") }}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="contractor_document_no" class="col-form-label">Contractor Document No</label>
                  <input type="text" id="contractor_document_no" class="form-control" placeholder="Contractor Document No" readonly value="{{ $external->contractor_document_number }}">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="contractor_name" class="col-form-label">Contractor Name</label>
                  <input type="text" name="contractor_name" id="contractor_name" class="form-control" placeholder="Contractor Name" readonly value="{{ $external->contractorname->name }}">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="discipline_code" class="col-form-label">Discipline Code</label>
                  <div class="row">
                    <div class="col-md-3">
                      <input type="text" id="discipline_code" class="form-control" placeholder="Discipline Code" readonly value="{{ $external->disciplinecode->code }}">
                    </div>
                    <div class="col-md-9">
                      <input type="text" id="discipline_code_label" class="form-control" placeholder="Discipline Name" readonly value="{{ $external->disciplinecode->name }}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="document_remark" class="control-label col-md-3">Document Remarks</label>
                  <textarea class="form-control summernote col-md-8 d-none" name="document_remark" id="document_remark" rows="4" placeholder="Remark...">{{ $external->document_remark }}</textarea>
                </div>
              </div>
            </div>
            <div id="revision-properties" class="col-md-12 row">
              <div class="col-md-12">
                <div class="mt-5"></div>
                <span class="title">
                  <hr>
                  <h5 class="text-md text-dark text-bold">Revision Properties</h5>
                </span>
                <div class="mt-3"></div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="revision_number" class="col-form-label">Revision No.</label>
                  <input type="text" name="revision_number" id="revision_number" class="form-control" readonly placeholder="Revision No.">
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label for="file_upload" class="col-form-label">File</label>
                  <div class="init-input">
                    <div class="input-group">
                      <input type="text" name="document_name[]" class="form-control lock-status" placeholder="Document Name">
                      <div class="custom-file ml-3">
                        <input type="file" class="custom-file-input lock-status" name="document_upload[]" onchange="initInputFile()">
                        <label class="custom-file-label form-control" for="document_upload">Attach a document</label>
                      </div>
                      <button class="btn btn-transparent text-md lock-status" type="button" id="button-add-form" onclick="addFormUpload(this)" data-document_number="1"><i class="fas fa-plus text-green color-palette"></i></button>
                    </div>
                  </div>
                  <div class="init-data"></div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="revision_remark" class="col-form-label">Revision Remark</label>
                  <textarea name="revision_remark" id="revision_remark" rows="5" class="form-control summernote lock-status" placeholder="Revision Remark..."></textarea>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="contractor_revision_no" class="col-form-label">Contractor Revision No</label>
                  <input type="text" name="contractor_revision_no" id="contractor_revision_no" class="form-control lock-status" placeholder="Contractor Revision No">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="issue_status" class="col-form-label">Issue Status</label>
                  <div class="row">
                    <div class="col-md-3">
                      <input type="text" name="issue_status" id="issue_status" class="form-control" readonly>
                    </div>
                    <div class="col-md-9">
                      <input type="text" name="issue_status_label" id="issue_status_label" class="form-control" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="nos_of_pages" class="col-form-label">Nos of Pages</label>
                  <input type="text" name="nos_of_pages" id="nos_of_pages" class="form-control lock-status" placeholder="Nos of Pages">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="sheet_size" class="col-form-label">Sheet Size</label>
                  <div class="row">
                    <div class="col-md-3">
                      <select name="sheet_size" id="sheet_size" class="form-control select2 lock-status"></select>
                    </div>
                    <div class="col-md-9">
                      <input type="text" name="sheet_size_label" id="sheet_size_label" class="form-control" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="supersede-properties" class="col-md-12 row d-none">
              <div class="col-md-12">
                <div class="mt-5"></div>
                <span class="title">
                  <hr>
                  <h5 class="text-md text-dark text-bold">Supersede Properties</h5>
                </span>
                <div class="mt-3"></div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="supersede_site_code" class="col-form-label">Site Code</label>
                  <select name="supersede_site_code" id="supersede_site_code" class="form-control select2" data-select_name="site_code" data-sub_url="sitecode" data-placeholder="Please choose option..."></select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="supersede_discipline_code" class="col-form-label">Discipline Code</label>
                  <select name="supersede_discipline_code" id="supersede_discipline_code" class="form-control select2" data-select_name="discipline_code" data-sub_url="disciplinecode" data-placeholder="Please choose option..."></select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="supersede_kks_category" class="col-form-label">KKS Category</label>
                  <select name="supersede_kks_category" id="supersede_kks_category" class="form-control select2" data-select_name="kks_category" data-sub_url="kkscategory" data-placeholder="Please choose option..."></select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="supersede_kks_code" class="col-form-label">KKS Code</label>
                  <select name="supersede_kks_code" id="supersede_kks_code" class="form-control select2" data-select_name="kks_code" data-sub_url="kkscode" data-placeholder="Please choose option..."></select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="supersede_document_type" class="col-form-label">Document Type</label>
                  <select name="supersede_document_type" id="supersede_document_type" class="form-control select2" data-select_name="document_type" data-sub_url="documenttypeext" data-placeholder="Please choose option..."></select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="supersede_originator_code" class="col-form-label">Originator Code</label>
                  <select name="supersede_originator_code" id="supersede_originator_code" class="form-control select2" data-select_name="originator_code" data-sub_url="originatorcode" data-placeholder="Please choose option..."></select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="supersede_phase_code" class="col-form-label">Phase Code</label>
                  <select name="supersede_phase_code" id="supersede_phase_code" class="form-control select2" data-select_name="phase_code" data-sub_url="phasecode" data-placeholder="Please choose option..."></select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="supersede_document_category" class="col-form-label">Document Category</label>
                  <select name="supersede_document_category" id="supersede_document_category" class="form-control select2" data-placeholder="Please choose option...">
                    <option value=""></option>
                    @foreach (config('enums.document_category') as $key => $item)
                    <option value="{{ $key }}">{{ "$key - $item" }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="supersede_document_no" class="col-form-label">Document No</label>
                  <select name="supersede_document_no" id="supersede_document_no" class="form-control select2" data-select_name="document_no" data-sub_url="documentcenterexternal" data-placeholder="Please choose option..."></select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="supersede_remark" class="col-form-label">Supersede Remarks</label>
                  <textarea class="form-control summernote d-none" name="supersede_remark" id="supersede_remark" rows="4" placeholder="Remark..."></textarea>
                </div>
              </div>
            </div>
            <div id="void-properties" class="col-md-12 row d-none">
              <div class="col-md-12">
                <div class="mt-5"></div>
                <span class="title">
                  <hr>
                  <h5 class="text-md text-dark text-bold">Void Properties</h5>
                </span>
                <div class="mt-3"></div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="void_remark" class="col-form-label">Void Remarks</label>
                  <textarea class="form-control summernote d-none" name="void_remark" id="void_remark" rows="4" placeholder="Remark..."></textarea>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <button type="button" id="submit" class="btn btn-labeled text-md btn-md btn-success btn-flat legitRipple" data-status="WAITING" onclick="saveRevision($(this))">
          <b><i class="fas fa-check-circle"></i></b> Submit
        </button>
        <button type="button" id="save" class="btn btn-labeled text-md btn-md bg-olive btn-flat legitRipple" data-status="DRAFT" onclick="saveRevision($(this))">
          <b><i class="fas fa-save"></i></b> Save
        </button>
        <button type="button" class="btn btn-labeled text-md btn-md btn-secondary btn-flat legitRipple" data-dismiss="modal">
          <b><i class="fas fa-times"></i></b> Cancel
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-reason" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="reason-modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Reason</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('revision.storelog') }}" method="post" autocomplete="off" enctype="multipart/form-data" id="form-reason">
          @csrf
          <input type="hidden" name="_method">
          <input type="hidden" name="document_revision_id">
          <input type="hidden" name="status">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label" for="attachment_name">Attachment Name</label>
                <input type="text" class="form-control" name="attachment_name" placeholder="Attachment Name">
              </div>
            </div>
            <div class="col-md-6">
              <label class="control-label" for="attachment">Attachment</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" onchange="initInputFile()" name="attachment" id="attachment">
                  <label class="custom-file-label" for="attachment">Attach a file</label>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label" for="reason">Reason</label>
                <textarea class="form-control summernote" name="reason" id="reason" rows="4" placeholder="Revise Reason"></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <button class="btn btn-labeled text-md btn-md btn-success btn-flat legitRipple" type="submit" form="form-reason">
          <b><i class="fas fa-save"></i></b> Submit
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  var reviewerMatrixData;
  var actionmenu      = @json(json_encode($actionmenu));
  var routeRoleSelect = "{{ route('role.select') }}";
  var base            = "{{ url('admin') }}";
  var externalUrl     = "{{ url('admin/documentcenterexternal') }}";
  var baseUrl         = "{{ url('admin/docexternalproperties') }}";
  var phaseCode       = @json($phase);
  var needSelect2Tag  = $("#form").find("[data-sub_url]");
  var matrixData      = @json($external->matrix()->with(['groups'])->get());
  var matrixEnums     = @json(config('enums.reviewer_matrix'));
  var documentCategory= $("[name=document_category_id]").val();
  var issueStatus     = @json(config('enums.issue_status'));
  var token           = `{{ csrf_token() }}`;
  var global_status   = @json(config('enums.global_status'));
  var document_id     = {{ $external->id }};
  var revisionData;
  var supersedeSelect2;
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
</script>
<script src="{{ asset('js/document_external.js') }}"></script>
<script>
  $(function(){
    $.each(needSelect2Tag, function(index, item){
      switch ($(item).data('sub_url')) {
        case 'sitecode':
          @if ($external->sitecode)
          $(item).select2('trigger', 'select', {
            data: {
              id: {!! $external->sitecode->id !!},
              text: `{!! $external->sitecode->code !!}`,
              name: `{!! $external->sitecode->name !!}`,
            }
          });
          @endif   
          break;
        case 'disciplinecode':
          @if ($external->disciplinecode)
          $(item).select2('trigger', 'select', {
            data: {
              id: {!! $external->disciplinecode->id !!},
              text: `{!! $external->disciplinecode->code !!}`,
              name: `{!! $external->disciplinecode->name !!}`,
            }
          });
          @endif   
          break;
        case 'documenttypeext':
          @if ($external->documenttype)
          $(item).select2('trigger', 'select', {
            data: {
              id: {!! $external->documenttype->id !!},
              text: `{!! $external->documenttype->code !!}`,
              name: `{!! $external->documenttype->name !!}`,
            }
          });
          @endif
          break;
        case 'originatorcode':
          @if ($external->originatorcode)
          $(item).select2('trigger', 'select', {
            data: {
              id: {!! $external->originatorcode->id !!},
              text: `{!! $external->originatorcode->code !!}`,
              name: `{!! $external->originatorcode->name !!}`,
            }
          });
          @endif   
          break;
      
        default:
          break;
      }
    });

    @if ($external->kkscategory)
      $('#kks_category_id').select2('trigger', 'select', {
        data: {
          id: {!! $external->kkscategory->id !!},
          text: `{!! $external->kkscategory->code !!}`,
          name: `{!! $external->kkscategory->name !!}`,
        }
      });
    @endif
    @if ($external->kkscode)
      $('#kks_code_id').select2('trigger', 'select', {
        data: {
          id: {!! $external->kkscode->id !!},
          text: `{!! $external->kkscode->code !!}`,
          name: `{!! $external->kkscode->name !!}`,
          category: {!! $external->kkscode->category !!},
        }
      });
    @endif
    @if ($external->contractorname)
      $('#contractor_name_id').select2('trigger', 'select', {
        data: {
          id: `{!! $external->contractorname->name !!}`,
          text: `{!! $external->contractorname->name !!}`,
        }
      });
      $('#contractor_group_id').select2('trigger', 'select', {
        data: {
          id: {!! $external->contractorname->role_id !!},
          text: `{!! $external->contractorname->role->name !!}`,
        }
      });
    @endif
  });
</script>
@endsection