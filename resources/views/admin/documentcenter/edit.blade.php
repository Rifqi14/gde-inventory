@extends('admin.layouts.app')
@section('title', $document->title)
@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">{{ $document->title }}</h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name }}</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
      <li class="breadcrumb-item">{{ $document->title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <form action="{{ route('documentcenter.update', ['id' => $document->id]) }}" method="post" role="form" enctype="multipart/form-data" autocomplete="off" id="form">
      @csrf
      @method('PUT')
      <div class="card">
        <div class="row">
          <div class="col-md-8">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">Document Properties</h5>
              </span>
              <div class="mt-5"></div>
              <div class="row">
                <div class="col-md-12">
                  <input type="hidden" name="menu" value="{{ $page }}">
                  <input type="hidden" name="category_menu" value="{{ $document->category->doctype->code }}">
                  <div class="form-group row">
                    <label for="number" class="control-label col-md-3">Document No:</label>
                    <input type="text" name="number" id="number" class="form-control col-md-8 lock" placeholder="Document No..." value="{{ $document->document_number }}">
                  </div>
                  <div class="form-group row">
                    <label for="title" class="control-label col-md-3">Document Title:</label>
                    <textarea name="title" id="title" class="form-control col-md-8 lock" cols="30" rows="4" placeholder="Document Title...">{{ $document->title }}</textarea>
                  </div>
                  <div class="form-group row">
                    <label for="document_type_id" class="control-label col-md-3">Document Type:</label>
                    <div class="col-md-3 pl-0">
                      <select name="document_type_id" id="document_type_id" class="form-control select2 lock" data-placeholder="Choose Document Type...">
                      </select>
                    </div>
                    <div class="col-md-5 pr-0">
                      <input type="text" name="doctype_label" id="doctype_label" class="form-control" placeholder="Document Type..." readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="organization_code_id" class="control-label col-md-3">Organization Code:</label>
                    <div class="col-md-3 pl-0">
                      <select name="organization_code_id" id="organization_code_id" class="form-control select2 lock" data-placeholder="Choose Organization Code...">
                      </select>
                    </div>
                    <div class="col-md-5 pr-0">
                      <input type="text" id="orgcode_label" class="form-control" placeholder="Organization Code..." readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="unit_code_id" class="control-label col-md-3">Unit Code:</label>
                    <div class="col-md-3 pl-0">
                      <select name="unit_code_id" id="unit_code_id" class="form-control select2 lock" data-placeholder="Choose Unit Code...">
                      </select>
                    </div>
                    <div class="col-md-5 pr-0">
                      <input type="text" id="unitcode_label" class="form-control" placeholder="Unit Code..." readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="originator_id" class="control-label col-md-3">Originator:</label>
                    <input type="hidden" name="category" value="{{ $document->category_id }}">
                    <select name="originator_id" id="originator_id" class="form-control select2 col-md-8 lock">
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">General Information</h5>
              </span>
              <div class="mt-5"></div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label for="created_user" class="control-label col-md-4">Created By:</label>
                    <input type="hidden" name="created_user" id="created_user" readonly value="{{ $document->createdBy->id }}">
                    <input type="text" name="issued_by" id="issued_by" class="form-control col-md-8" readonly value="{{ $document->createdBy->name }}">
                  </div>
                  <div class="form-group row">
                    <label for="created_at" class="control-label col-md-4">Created Date:</label>
                    <div class="col-md-8 p-0">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" name="created_at" class="form-control text-right datepicker" id="created_at" value="{{ date('d/m/Y', strtotime($document->created_at)) }}" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="updated_user" class="control-label col-md-4">Last Modified By:</label>
                    <input type="hidden" name="updated_user" id="updated_user" readonly value="{{ @$document->updatedBy->id }}">
                    <input type="text" name="updated_by" id="updated_by" class="form-control col-md-8" readonly value="{{ @$document->updatedBy->name }}">
                  </div>
                  <div class="form-group row">
                    <label for="updated_at" class="control-label col-md-4">Last Modified Date:</label>
                    <div class="col-md-8 p-0">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" name="updated_at" class="form-control text-right datepicker" id="updated_at" value="{{ date('d/m/Y', strtotime($document->updated_at)) }}" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="remark" class="control-label col-md-3">Remark</label>
                    <textarea class="form-control summernote col-md-8 d-none" name="remark" id="remark" rows="4" placeholder="Remark...">{{ $document->remark }}</textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <input type="hidden" name="locked_status" value="lock">
            <div class="card-footer text-right">
              <div class="locked">
                <a href="javascript:void(0);" class="btn btn-md btn-danger color-palette btn-labeled legitRipple text-sm" onclick="lock('unlock')">
                  <b><i class="fas fa-edit"></i></b> Edit
                </a>
              </div>
              <div class="unlocked d-none">
                <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-md" form="form">
                  <b><i class="fas fa-save"></i></b> Submit
                </button>
                <a href="javascript:void(0);" class="btn btn-md btn-secondary color-palette btn-labeled legitRipple text-sm" onclick="lock('lock')">
                  <b><i class="fas fa-times"></i></b> Cancel
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header text-right">
          <button type="button" class="btn btn-labeled text-md btn-md btn-success btn-flat legitRipple" onclick="documentModal('create')">
            <b><i class="fas fa-plus"></i></b> Create
          </button>
        </div>
        <div class="card-body table-responsive p-0">
          <table id="table-document" class="table table-striped datatable" width="100%">
            <thead>
              <tr>
                <th width="7%">Revision No.</th>
                <th width="10%">Issue Purpose</th>
                <th width="10%">Issue Status</th>
                <th width="20%">Transmittal Status</th>
                <th width="20%">File List</th>
                <th width="10%">File Size</th>
                <th width="20%">Issued By</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </form>
  </div>
</section>
<div class="modal fade" id="form-document" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="reason-modal" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Create a Revision</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('centerdocument.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data" id="form-upload">
          @csrf
          <input type="hidden" name="_method">
          <input type="hidden" name="menu" value="{{ $page }}">
          <input type="hidden" name="category_menu" value="{{ $document->category->doctype->code }}">
          <input type="hidden" name="document_center_document_id" id="document_center_document_id">
          <div class="row">
            <div class="col-md-12">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">Document Properties</h5>
              </span>
              <div class="mt-5"></div>
            </div>
            <div class="col-md-5">
              <div class="form-group row">
                <input type="hidden" name="document_id" value="{{ $document->id }}">
                <label for="document_number" class="col-form-label col-md-4">Document No.</label>
                <div class="col-md-8">
                  <input type="text" name="document_number" id="document_number" class="form-control" placeholder="Document Number" value="{{ $document->document_number }}" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-7">
              <div class="form-group row">
                <label for="title" class="col-form-label col-md-4">Document Title</label>
                <div class="col-md-8">
                  <input type="text" name="title" id="title" class="form-control" placeholder="Document Title" value="{{ $document->title }}" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group row">
                <label for="document_type" class="col-form-label col-md-4">Document Type</label>
                <div class="col-md-8">
                  <input type="hidden" name="orgcode" value="{{ $document->organization ? $document->organization->code : '' }}">
                  <div class="row">
                    <div class="col-md-3">
                      <select name="document_type" id="document_type_id_revision" class="document_type_id form-control select2" disabled></select>
                    </div>
                    <div class="col-md-9">
                      <input type="text" id="doctype_label_revision" class="form-control" placeholder="Document Type" readonly value="{{ $document->doctype ? $document->doctype->name : '' }}">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-7">
              <div class="form-group row">
                <label for="remark" class="col-form-label col-md-4">Document Remark</label>
                <div class="col-md-8">
                  <textarea name="remark" id="remark" rows="5" class="form-control summernote-document">{{ $document->remark }}</textarea>
                </div>
              </div>
            </div>
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
                <input type="text" name="revision_number" id="revision_number" class="form-control" placeholder="Revision No." value="{{ $document->documents()->latest()->first() ? $document->documents()->latest()->first()->revision + 1 : 0 }}" readonly>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label for="document_upload" class="col-form-label">Document</label>
                <div class="init-data"></div>
                <div class="initInput">
                  <div class="input-group">
                    <input type="text" name="document_name[]" class="form-control" placeholder="Document Name">
                    <div class="custom-file ml-3">
                      <input type="file" class="custom-file-input lock-revision" name="document_upload[]" onchange="initInputFile()">
                      <label class="custom-file-label form-control" for="document_upload">Attach a document</label>
                    </div>
                    <button class="btn btn-transparent text-md lock-revision" type="button" id="button-add-form" onclick="addFormUpload(this)" data-document_number="1"><i class="fas fa-plus text-green color-palette"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="revision_remark" class="col-form-label">Revision Remark</label>
                <textarea name="revision_remark" id="revision_remark" rows="5" class="form-control summernote-revise lock-revision" placeholder="Revision Remark..."></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="transmittal_number" class="col-form-label">Transmittal No.</label>
                <input type="text" name="transmittal_number" id="transmittal_number" class="form-control" placeholder="Auto Generate Number" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="issue_purpose" class="col-form-label">Issue Purpose</label>
                <select name="issue_purpose" id="issue_purpose" class="form-control select2 lock-revision" data-placeholder="Choose Purpose...">
                  <option value=""></option>
                  <option value="Information">For Information</option>
                  <option value="Approval">For Approval</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="created_by" class="col-form-label">Created by</label>
                <input type="hidden" name="created_by" id="created_by" class="form-control" placeholder="Created by" value="{{ Auth::guard('admin')->user()->id }}">
                <input type="text" name="created_by_label" id="created_by_label" class="form-control" placeholder="Created by" value="{{ Auth::guard('admin')->user()->name }}" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="created_at" class="col-form-label">Created Date</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" name="created_at" class="form-control text-right datepicker" id="created_at" disabled>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="updated_by" class="col-form-label">Last Modified By</label>
                <input type="hidden" name="updated_by" id="updated_by" class="form-control" placeholder="Last Modified By" value="{{ Auth::guard('admin')->user()->id }}">
                <input type="text" name="updated_by_label" id="updated_by_label" class="form-control" placeholder="Last Modified By" value="{{ Auth::guard('admin')->user()->name }}" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="updated_at" class="col-form-label">Last Modified Date</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" name="updated_at" class="form-control text-right datepicker" id="updated_at" disabled>
                </div>
              </div>
            </div>
          </div>
          <div class="row additional-form">
            <input type="hidden" name="document_type_supersede" id="document_type_supersede">
            <div class="col-md-12 supersede-form d-none">
              <input type="hidden" name="supersede_id" id="supersede_id">
              <div class="mt-5"></div>
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">Supersede Properties</h5>
              </span>
              <div class="mt-3"></div>
              <div class="form-group row">
                <label for="document_type_id_supersede" class="control-label col-md-3">Document Type:</label>
                <div class="col-md-3 pl-0">
                  <select name="document_type_id_supersede" id="document_type_id_supersede" class="form-control select2 lock-supersede" data-placeholder="Choose Document Type...">
                  </select>
                </div>
                <div class="col-md-5 pr-0">
                  <input type="text" name="doctype_label_supersede" id="doctype_label_supersede" class="form-control" placeholder="Document Type..." readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="organization_code_id_supersede" class="control-label col-md-3">Organization Code:</label>
                <div class="col-md-3 pl-0">
                  <select name="organization_code_id_supersede" id="organization_code_id_supersede" class="form-control select2 lock-supersede" data-placeholder="Choose Organization Code...">
                  </select>
                </div>
                <div class="col-md-5 pr-0">
                  <input type="text" id="orgcode_label_supersede" class="form-control" placeholder="Organization Code..." readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="unit_code_id_supersede" class="control-label col-md-3">Unit Code:</label>
                <div class="col-md-3 pl-0">
                  <select name="unit_code_id_supersede" id="unit_code_id_supersede" class="form-control select2 lock-supersede" data-placeholder="Choose Unit Code...">
                  </select>
                </div>
                <div class="col-md-5 pr-0">
                  <input type="text" id="unitcode_label_supersede" class="form-control" placeholder="Unit Code..." readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="document_center_id_supersede" class="col-form-label col-md-3">Document Number</label>
                <div class="col-md-9 pl-0">
                  <select name="document_center_id_supersede" id="document_center_id_supersede" class="form-control select2 lock-supersede" data-placeholder="Choose Document Number"></select>
                </div>
              </div>
              <div class="form-group">
                <label for="remark" class="col-form-label">Supersede Remark</label>
                <textarea class="form-control summernote-supersede d-none lock-supersede" name="supersede_remark" id="supersede_remark" rows="4" placeholder="Supersede Remark..."></textarea>
              </div>
            </div>
            <div class="col-md-12 void-form d-none">
              <div class="mt-5"></div>
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">Void Properties</h5>
              </span>
              <div class="mt-3"></div>
              <input type="hidden" name="void_id" id="void_id">
              <div class="form-group">
                <label for="remark" class="col-form-label">Void Remark</label>
                <textarea class="form-control summernote-void d-none lock-supersede" name="void_remark" id="void_remark" rows="4" placeholder="Void Remark..."></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <div class="create-button">
          <button class="btn btn-labeled text-md btn-md btn-success btn-flat legitRipple" onclick="saveRevision($('#form-upload'), 'WAITING')">
            <b><i class="fas fa-check-circle"></i></b> Submit
          </button>
          <button type="button" class="btn btn-labeled text-md btn-md bg-olive btn-flat legitRipple" onclick="saveRevision($('#form-upload'), 'DRAFT')">
            <b><i class="fas fa-save"></i></b> Save
          </button>
        </div>
        <div class="edit-button d-none">
          <button type="button" class="btn btn-labeled text-md btn-md bg-info btn-flat legitRipple" onclick="reasonModal('Revised', $('#document_center_document_id').val())">
            <b><i class="fas fa-pencil-alt"></i></b> Revised
          </button>
          <button class="btn btn-labeled text-md btn-md btn-success btn-flat legitRipple" onclick="reasonModal('Issued', $('#document_center_document_id').val())">
            <b><i class="fas fa-check-circle"></i></b> Issued
          </button>
          <button type="button" class="btn btn-labeled text-md btn-md bg-maroon btn-flat legitRipple" onclick="reasonModal('Reject', $('#document_center_document_id').val())">
            <b><i class="fas fa-window-close"></i></b> Reject
          </button>
        </div>
        <div class="supersede-button d-none">
          <button type="button" class="btn btn-labeled text-md btn-md bg-info btn-flat legitRipple" onclick="supersedeButton('SUPERSEDE')">
            <b><i class="fas fa-save"></i></b> Supersede
          </button>
          <button class="btn btn-labeled text-md btn-md btn-success btn-flat legitRipple" onclick="supersedeButton('VOID')">
            <b><i class="fas fa-save"></i></b> Void
          </button>
          <button type="button" class="btn btn-labeled text-md btn-md bg-red btn-flat legitRipple d-none" onclick="supersedeButton('EDIT')">
            <b><i class="fas fa-pencil-alt"></i></b> Edit
          </button>
        </div>
        <div class="create-supersede-button d-none">
          <button type="button" class="btn btn-labeled text-md btn-md bg-olive btn-flat legitRipple" onclick="saveSupersede($('#form-upload'), $('#document_type'))">
            <b><i class="fas fa-save"></i></b> Save
          </button>
        </div>
        <div class="edit-supersede-button d-none">
          <button type="button" class="btn btn-labeled text-md btn-md bg-olive btn-flat legitRipple" onclick="saveSupersede($('#form-upload'), $('#document_type_supersede'), 'UNDO')">
            <b><i class="fas fa-undo"></i></b> Undo
          </button>
        </div>
        <button type="button" class="btn btn-labeled text-md btn-md btn-secondary btn-flat legitRipple" data-dismiss="modal">
          <b><i class="fas fa-times"></i></b> Cancel
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="form-reason" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="reason-modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Reason</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('documentlog.store') }}" method="post" autocomplete="off" enctype="multipart/form-data" id="reason-form">
          @csrf
          <input type="hidden" name="_method">
          <input type="hidden" name="menu" value="{{ $page }}">
          <input type="hidden" name="category_menu" value="{{ $document->category->doctype->code }}">
          <input type="hidden" name="document_center_document_id">
          <input type="hidden" name="status">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label" for="attachment_name">Attachment Name</label>
                <input type="text" class="form-control" name="attachment_name">
              </div>
            </div>
            <div class="col-md-6">
              <label class="control-label" for="attachment">Attachment</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" name="attachment" id="attachment">
                  <label class="custom-file-label" for="attachment">Attach a file</label>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label" for="reason">Reason</label>
                <textarea class="form-control summernote-reason" name="reason" id="reason" rows="4" placeholder="Revise Reason"></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <button class="btn btn-labeled text-md btn-md btn-success btn-flat legitRipple" type="submit" form="reason-form">
          <b><i class="fas fa-save"></i></b> Submit
        </button>
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

  const summernoteRevise = () => {
    $('.summernote-revise').summernote({
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

  const summernoteDocument = () => {
    $('.summernote-document').summernote({
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

  const summernoteReason = () => {
    $('.summernote-reason').summernote({
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

  const summernoteVoid = () => {
    $('.summernote-void').summernote({
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

  const summernoteSupersede = () => {
    $('.summernote-supersede').summernote({
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
</script>
<script>
  var page          = `{{ $page }}`;
  var actionmenu    = @json(json_encode($actionmenu));
  var global_status = JSON.parse(`{!! json_encode(config('enums.global_status')) !!}`);
  var status        = `{{ $document->status }}`;
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
  
  const documentModal = (type, document_id = null) => {
    initApprovalButton(status);
    $('#form-document').modal('show');
    $('#form-upload').attr('action', `{{ url('admin/centerdocument') }}`);
    $('#form-upload').find('input[name="_method"]').val('');
    summernoteDocument();
    initInputFile();
    $('.summernote-document').summernote('disable');
    $('textarea[name="revision_remark"]').summernote('code', '');
    $('#form-upload')[0].reset();
    $('select[name="issue_purpose"]').val('').trigger('change');
    $('.add-on').remove();
    $('.init-data').empty();
    $('#button-add-form').attr('data-document_number', 1);
    $('#form-upload').find('.datepicker').daterangepicker({
      singleDatePicker: true,
      timePicker: false,
      timePickerIncrement: 30,
      locale: {
        format: 'DD/MM/YYYY'
      }
    });
    lockRevisionProperties('DRAFT');
  }

  const addFormUpload = (e) => {
    var number = $(e).attr('data-document_number');
    if (number <= 3) { 
      var html = `
                  <div class="input-group mt-2 add-on">
                    <input type="text" name="document_name[]" class="form-control" placeholder="Document Name">
                    <div class="custom-file ml-3">
                      <input type="file" class="custom-file-input" name="document_upload[]" onchange="initInputFile()">
                      <label class="custom-file-label form-control" for="document_upload">Attach a document</label>
                    </div>
                    <button class="btn btn-transparent text-md" type="button" onclick="removeFormUpload($(this))"><i class="fas fa-trash text-maroon color-palette"></i></button>
                  </div>
      `;
      $(e).parents('.initInput').append(html);
      $(e).attr('data-document_number') <= 3 ? $(e).attr('data-document_number', parseInt($(e).attr('data-document_number')) + 1) : '';
    }
  }

  const removeFormUpload = (e) => {
    $('#button-add-form').attr('data-document_number', parseInt($('#button-add-form').attr('data-document_number')) - 1);
    e.parent().remove();
  }

  const initInputData = (e) => {
    var html = ``;

    $.each(e, function(index, value) {
      html += `<div class="input-group d-flex justify-content-between mt-2"><a href="${value.document_path}" class="text-md text-info text-bold mt-1">${value.document_name}</a><button class="btn btn-transparent text-md lock-revision" type="button" onclick="destroyDocument($(this), ${value.id})"><i class="fas fa-trash text-maroon color-palette"></i></button></div>`
    });

    return html;
  }

  const lock = (e) => {
    $('input[name="locked_status"]').val(e);
    if (e == 'lock') {
      $('.lock').prop('disabled', true);
      $('.summernote').summernote('disable');
      $('.unlocked').addClass('d-none');
      $('.locked').removeClass('d-none')
    } else {
      $('.lock').prop('disabled', false);
      $('.summernote').summernote('enable');
      $('.locked').addClass('d-none');
      $('.unlocked').removeClass('d-none');
    }
  }

  const initInputFile = () => {
      $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
      });
  }

  const edit = (e) => {
    $.ajax({
      url: `{{ url('admin/centerdocument') }}/${e}/edit`,
      method: 'GET',
      dataType: 'JSON',
      beforeSend: function() {
        blockMessage('body', 'Loading...', '#fff');
      }
    }).done(function(response) {
      if (response.status) {
        $('body').unblock();
        toastr.success(response.message);
        fillEditForm(response);
        return;
      }
      $('body').unblock();
      toastr.warning(response.message);
      return
    }).fail(function(response) {
      $('body').unblock();
      var response  = response.responseJSON;
      toastr.error(response.message);
    });
  }

  const fillEditForm = (e) => {
    var data_total  = e.data.docdetail ? e.data.docdetail.length : 0;
    $('#form-document').modal('show');
    $('#form-document .modal-title').text(`Revision ${e.data.revision}`);
    $('#form-upload').attr('action', `{{ url('admin/centerdocument') }}/${e.data.id}`);
    $('#form-upload').find('input[name="_method"]').val('PUT');
    $('#form-upload').find('input[name="document_center_document_id"]').val(e.data.id);
    $('#form-upload').find('input[name="revision_number"]').val(e.data.revision);
    $('#form-upload').find('textarea[name="revision_remark"]').text(e.data.remark);
    $('#form-upload').find('textarea[name="revision_remark"]').summernote('code', e.data.remark);
    $('#form-upload').find('select[name="issue_purpose"]').select2('trigger', 'select', {
      data: {
        id: `${e.data.issue_purpose}`,
        text: `For ${e.data.issue_purpose}`
      }
    });
    $('#form-upload').find('.init-data').empty();
    $('#form-upload').find('.init-data').append(initInputData(e.data.docdetail));
    $('.add-on').remove();
    $('#button-add-form').attr('data-document_number', 1);
    $('#form-upload').find('#button-add-form').attr('data-document_number', parseInt($('#button-add-form').attr('data-document_number')) + data_total);
    $('#form-upload').find('input[name="transmittal_number"]').val(e.data.transmittal_no);
    $('#form-upload').find('input[name="created_by"]').val(e.data.created_by.id);
    $('#form-upload').find('input[name="created_by_label"]').val(e.data.created_by.name);
    $('#form-upload').find('input[name="updated_by"]').val(e.data.updated_by.id);
    $('#form-upload').find('input[name="updated_by_label"]').val(e.data.updated_by.name);
    $('#form-upload').find('input[name="created_at"]').val(e.data.created_date);
    $('#form-upload').find('input[name="updated_at"]').val(e.data.last_modified);
    lockRevisionProperties(e.data.status);
    initApprovalButton(e.data.status);
    if (e.data.document_type) {
      if (e.data.document_type == 'SUPERSEDE') {
        $('#supersede_id').val(e.data.supersede.id);
      } else {
        $('#void_id').val(e.data.void.id);
      }
      $('.lock-supersede').prop('disabled', true);
      $('.summernote-supersede').summernote('disable');
      $('.summernote-void').summernote('disable');
      fillEditSupersedeForm(e.data);
    } else {
      $('.lock-supersede').prop('disabled', false);
      $('.summernote-supersede').summernote('enable');
      $('.summernote-void').summernote('enable');
    }
    supersedeButton(e.data.document_type, e.data.status);
    summernoteDocument();
    $('.summernote-document').summernote('disable');
    initInputFile();
  }

  const fillEditSupersedeForm = (e = null) => {
    if (e) {
      if (e.document_type == 'SUPERSEDE' && e.supersede) {
        $('#document_center_id_supersede').select2('trigger', 'select', {
          data: {
            id: e.supersede.docno.id,
            text: `${e.supersede.docno.document_number}`
          }
        });
        $('#form-upload').find('textarea[name="supersede_remark"]').text(e.supersede.supersede_remark);
        $('#form-upload').find('textarea[name="supersede_remark"]').summernote('code', e.supersede.supersede_remark);
        summernoteSupersede();
      } else if (e.document_type == 'VOID' && e.void) {
        $('#form-upload').find('textarea[name="void_remark"]').text(e.void.void_remark);
        $('#form-upload').find('textarea[name="void_remark"]').summernote('code', e.void.void_remark);
        summernoteVoid();
      }
    } else {
      $('#form-upload').find('textarea[name="void_remark"]').text('');
      $('#form-upload').find('textarea[name="void_remark"]').summernote('code', '');
      $('#form-upload').find('textarea[name="supersede_remark"]').text('');
      $('#form-upload').find('textarea[name="supersede_remark"]').summernote('code', '');
      $('#document_center_id_supersede').val(null).trigger('change');
    }
  }

  const saveRevision = (e, status) => {
    var formData  = new FormData(e[0]);
    formData.append('status', status);
    $.ajax({
      url: e.attr('action'),
      method: 'post',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      beforeSend: function() {
        blockMessage('body', 'Loading...', '#fff');
      }
    }).done(function(response) {
      $('body').unblock();
      if (response.status) {
        toastr.success(response.message);
        dataTable.draw();
        location.reload();
      } else {
        toastr.warning(response.message);
      }
      return;
    }).fail(function(response){
      $('body').unblock();
      var response  = response.responseJSON;
      toastr.error(response.message);
    });
  }

  const saveSupersede = (e, status, undo = null) => {
    var formData  = new FormData(e[0]);
    formData.append('document_type', status);
    formData.append('undo', undo);
    $.ajax({
      url: e.attr('action'),
      method: 'post',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      beforeSend: function() {
        blockMessage('body', 'Loading...', '#fff');
      }
    }).done(function(response) {
      $('body').unblock();
      if (response.status) {
        toastr.success(response.message);
        dataTable.draw();
        $('#form-document').modal('hide');
      } else {
        toastr.warning(response.message);
      }
      return;
    }).fail(function(response){
      $('body').unblock();
      var response  = response.responseJSON;
      toastr.error(response.message);
    });
  }

  const formatBytes = (bytes, decimals = 2) => {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
  }

  const destroyDocument = (e, id) => {
    $('#form-document').modal('hide');
    bootbox.confirm({
      buttons: {
        confirm: {
          label: '<i class="fa fa-check"></i>',
          className: 'btn-primary btn-sm'
        },
        cancel: {
          label: '<i class="fa fa-undo"></i>',
          className: 'btn-default btn-sm'
        },
      },
      title: 'Delete data?',
      message: 'Are you sure want to delete this data?',
      callback: function (result) {
        if (result) {
          var data = {
            _token: "{{ csrf_token() }}"
          };
          $.ajax({
            url: `{{route('documentdetail.index')}}/${id}`,
            dataType: 'json',
            data: data,
            type: 'DELETE',
            beforeSend: function () {
              blockMessage('body', 'Loading', '#fff');
            }
          }).done(function (response) {
            $('body').unblock();
            $('#form-document').modal('show');
            if (response.status) {
              removeFormUpload(e);
              dataTable.draw();
            }else {
              toastr.warning(response.message);
            }
          }).fail(function (response) {
            var response = response.responseJSON;
            $('body').unblock();
            $('#form-document').modal('show');
            toastr.warning(response.message);
          });
        }
      }
    });
  }

  const initApprovalButton = (status) => {
    if (status == 'WAITING') {
      $('.create-button').addClass('d-none');
      $('.edit-button').removeClass('d-none');
      $('.supersede-button').addClass('d-none');
    } else if (status == 'APPROVED') {
      $('.create-button').addClass('d-none');
      $('.edit-button').addClass('d-none');
      $('.supersede-button').removeClass('d-none');
    } else if (status == 'DRAFT' || status == 'REVISED') {
      $('.create-button').removeClass('d-none');
      $('.edit-button').addClass('d-none');
      $('.supersede-button').addClass('d-none');
    } else {
      $('.create-button').addClass('d-none');
      $('.edit-button').addClass('d-none');
      $('.supersede-button').addClass('d-none');
    }
  }

  const lockRevisionProperties = (status) => {
    if (status == 'DRAFT' || status == 'REVISED') {
      $('.lock-revision').prop('disabled', false);
      $('.summernote-revise').summernote('enable');
      summernoteRevise();
    } else {
      $('.summernote-revise').summernote('disable');
      $('.lock-revision').prop('disabled', true);
    }
  }

  const reasonModal = (status, id) => {
    $('#form-document').modal('hide');
    $('#form-reason').modal('show');
    summernoteReason();
    $('#form-reason .modal-title').html(`${status} Reason`);
    $('#reason-form').find('input[name="document_center_document_id"]').val(id);
    $('#reason-form').find('input[name="status"]').val(status);
  }

  const supersedeButton = (status, status_document = null) => {
    $('.supersede-button').addClass('d-none');
    if (status == 'SUPERSEDE') {
      $('.supersede-form').removeClass('d-none');
      $('.void-form').addClass('d-none');
      $('#document_type_supersede').val(status);
      summernoteSupersede();
      if ($('#supersede_id').val() !== "") {
        $('.edit-supersede-button').removeClass('d-none');
        $('.create-supersede-button').addClass('d-none');
      } else {
        $('.edit-supersede-button').addClass('d-none');
        $('.create-supersede-button').removeClass('d-none');
      }
    } else if (status == 'VOID') {
      $('.supersede-form').addClass('d-none');
      $('.void-form').removeClass('d-none');
      $('#document_type_supersede').val(status);
      summernoteVoid();
      if ($('#void_id').val() !== "") {
        $('.edit-supersede-button').removeClass('d-none');
        $('.create-supersede-button').addClass('d-none');
      } else {
        $('.edit-supersede-button').addClass('d-none');
        $('.create-supersede-button').removeClass('d-none');
      }
    } else {
      $('.supersede-form').addClass('d-none');
      $('.void-form').addClass('d-none');
      $('.edit-supersede-button').addClass('d-none');
      $('.create-supersede-button').addClass('d-none');
      initApprovalButton(status_document);
    }
  }

  const documentReference = (id) => {
    document.location = `{{ url('admin/documentcenter') }}/${page}/${id}/edit`;
  }
  
  $(function() {
    summernote();
    summernoteRevise();
    initInputFile();
    lock('lock');
    initApprovalButton(status);
    $('.select2').select2({
      allowClear: true,
    });
    
    $('#form').validate({
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
          url: $('#form').attr('action'),
          method: 'post',
          data: new FormData($('#form')[0]),
          processData: false,
          contentType: false,
          dataType: 'json',
          beforeSend: function() {
            blockMessage('body', 'Loading...', '#fff');
          }
        }).done(function(response) {
          $('body').unblock();
          if (response.status) {
            document.location = response.results;
          } else {
            toastr.warning(response.message);
          }
          return;
        }).fail(function(response){
          $('body').unblock();
          var response  = response.responseJSON;
          toastr.error(response.message);
        });
      }
    });

    $('#reason-form').validate({
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
          url: $('#reason-form').attr('action'),
          method: 'post',
          data: new FormData($('#reason-form')[0]),
          processData: false,
          contentType: false,
          dataType: 'json',
          beforeSend: function() {
            blockMessage('body', 'Loading...', '#fff');
          }
        }).done(function(response) {
          $('body').unblock();
          if (response.status) {
            $('#form-reason').modal('hide');
            dataTable.draw();
          } else {
            toastr.warning(response.message);
          }
          return;
        }).fail(function(response){
          $('body').unblock();
          var response  = response.responseJSON;
          toastr.error(response.message);
        });
      }
    });

    $('#form-upload').validate({
      errorElement: 'span',
      errorClass: 'help-block',
      focusInvalid: false,
      highlight: function (err) {
        $(err).closest('.form-group').removeClass('has-success').addClass('has-error');
      },
      success: function (err) {
        $(err).closest('.form-group').removeClass('has-error').addClass('has-success');
        $(err).remove();
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
          url: $('#form-upload').attr('action'),
          method: 'post',
          data: new FormData($('#form-upload')[0]),
          processData: false,
          contentType: false,
          dataType: 'json',
          beforeSend: function() {
            blockMessage('body', 'Loading...', '#fff');
          }
        }).done(function(response) {
          $('body').unblock();
          if (response.status) {
            toastr.success(response.message);
            location.reload();
          } else {
            toastr.warning(response.message);
          }
          return;
        }).fail(function(response){
          $('body').unblock();
          var response  = response.responseJSON;
          toastr.error(response.message);
        });
      }
    });
    
    dataTable = $('#table-document').DataTable({
      processing: true,
      language: {
        processing: `<div class="p-2 text-center"><i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...</div>`
      },
      serverSide: true,
      filter: false,
      responsive: true,
      lengthChange: false,
      order: [[1, "asc"]],
      ajax: {
        url: "{{ route('centerdocument.read') }}",
        type: "GET",
        data: function(data){
          data.document_id = `{{ $document->id }}`;
        }
      },
      columnDefs: [
        { orderable: false, targets: [0, 7] },
        { className: "text-center", targets: [0, 6, 7] },
        { render: function (data, type, row) {
          return row.issue_purpose ? `For ${row.issue_purpose}` : '';
        }, targets: [1] },
        { render: function (data, type, row) {
          var label     = '',
              text      = '',
              docRef    = '',
              status    = row.status;

              $.each(global_status, function(index, value) {
                if (index == status) {
                  label   = value.badge;
                  text    = value.text;
                }
                if (status == 'REVISED') {
                  label   = 'secondary';
                  text    = 'Draft';
                }

                if (status == 'APPROVED') {
                  label   = value.badge;
                  text    = 'Issued';
                }

                if (row.document_type) {
                  label   = 'info';
                  String.prototype.ucwords = function() {
                    str = this.toLowerCase();
                    return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
                      function($1){
                          return $1.toUpperCase();
                      });
                  }

                  if (row.supersede.docno) {
                    docRef  = `<a href="javascript:void(0);" onclick="documentReference(${row.supersede.docno.id})"><div class="text-md text-info text-bold">${row.supersede.docno.document_number}</div></a>`
                  }

                  var document_type = row.document_type;
                  text    = document_type.ucwords();
                }
              });
          
          return `${docRef}<span class="badge bg-${label} text-sm">${text}</span>`;
        }, targets: [2] },
        { render: function (data, type, row) {
          var status  = '',
              label   = '';
              
              if (row.transmittal_status == 'Waiting for Issue') {
                status  = 'Waiting for Issue';
                label   = 'bg-info';
              } else {
                status  = 'Issued';
                label   = 'bg-success';
              }
          return `<font class="text-md text-bold">${row.transmittal_no}</font><div class="text-sm text-semibold"><span class="badge ${label}">${row.transmittal_status}</span></div>`;
        }, targets: [3] },
        { render: function (data, type, row) {
          var html  = '';

          $.each(row.docdetail, function(index, value) {
            html += `<a href="${value.document_path}" target="_blank" class="text-md text-info text-bold">${value.document_name}</a><br>`
          });
          return html;
        }, targets: [4] },
        { render: function (data, type, row) {
          var html  = '';

          $.each(row.docdetail, function(index, value) {
            html += `${formatBytes(value.file_size, 2)}<br>`
          });
          return html;
        }, targets: [5] },
        { render: function (data, type, row) {
          return `<font class="text-md text-bold">${row.created_by ? row.created_by.name : ''}</font><div class="text-sm text-semibold">Date: <font class="text-info">${row.last_modified}</font></div>`;
        }, targets: [6] },
        { render: function (data, type, row) {
          var button = '';
          if (actionmenu.indexOf('read') > 0 && row.document_path) {
            button += `<a class="dropdown-item" href="${row.document_path}"><i class="fas fa-download"></i> Download</a>`
          }
          if (actionmenu.indexOf('update') > 0) {
            button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
            <i class="far fa-edit"></i>Update Data
            </a>`;
          }
          if (actionmenu.indexOf('delete') > 0) {
            button += `<a class="dropdown-item delete" href="javascript:void(0);" onclick="destroy(${row.id})">
            <i class="fa fa-trash-alt"></i> Delete Data
            </a>`;
          }
          return `<div class="btn-group">
            <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-bars"></i>
            </button>
            <div class="dropdown-menu">
                ${button}
            </div>
          </div>`;
        }, targets: [7] },
      ],
      columns: [
        { data: "revision" },
        { data: "issue_purpose" },
        { data: "status"},
        { data: "transmittal_no" },
        { data: "id" },
        { data: "id", className: "text-right" },
        { data: "created_by" },
        { data: "id" },
      ]
    });

    $('.datepicker').daterangepicker({
      singleDatePicker: true,
      timePicker: false,
      timePickerIncrement: 30,
      locale: {
        format: 'DD/MM/YYYY'
      }
    });

    $('#originator_id').select2({
      placeholder: "Choose Originator...",
      ajax: {
        url: "{{ route('role.select') }}",
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
          params.page = params.page || 1;
          var more  = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item) {
            option.push({
              id: item.id,
              text: item.name,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
      tags: true,
    });

    $('#document_type_id').select2({
      ajax: {
        url: "{{ route('documenttype.select') }}",
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
          params.page = params.page || 1;
          var more  = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item) {
            option.push({
              id: item.id,
              text: `${item.code}`,
              name: `${item.name}`,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    }).on('select2:select', function(e) {
      $('#doctype_label').val($(this).select2('data')[0].name);
    }).on('select2:clear', function(e) {
      $('#doctype_label').val(null);
    });

    $('#document_type_id_revision').select2({
      ajax: {
        url: "{{ route('documenttype.select') }}",
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
          params.page = params.page || 1;
          var more  = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item) {
            option.push({
              id: item.id,
              text: `${item.code}`,
              name: `${item.name}`,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    }).on('select2:select', function(e) {
      $('#doctype_label_revision').val($(this).select2('data')[0].name);
    }).on('select2:clear', function(e) {
      $('#doctype_label_revision').val(null);
    });

    $('#document_type_id_supersede').select2({
      ajax: {
        url: "{{ route('documenttype.select') }}",
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
          params.page = params.page || 1;
          var more  = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item) {
            option.push({
              id: item.id,
              text: `${item.code}`,
              name: `${item.name}`,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    }).on('select2:select', function(e) {
      $('#doctype_label_supersede').val($(this).select2('data')[0].name);
    }).on('select2:clear', function(e) {
      $('#doctype_label_supersede').val(null);
    });

    $('#organization_code_id').select2({
      ajax: {
        url: "{{ route('organization.select') }}",
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
          params.page = params.page || 1;
          var more  = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item) {
            option.push({
              id: item.id,
              text: `${item.code}`,
              name: `${item.name}`,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    }).on('select2:clear', function(e) {
      $('#unit_code_id').val(null).trigger('change');
      $('#orgcode_label').val(null);
      $('#unitcode_label').val(null);
    }).on('select2:close', function(e) {
      var data    = $(this).find('option:selected').val();
      var unit_code_id = $('#unit_code_id').select2('data');

      if (unit_code_id[0] && unit_code_id[0].organization.id != data) {
        $('#unit_code_id').val(null).trigger('change');
      }
    }).on('select2:select', function(e) {
      $('#orgcode_label').val($(this).select2('data')[0].name);
    });

    $('#organization_code_id_supersede').select2({
      ajax: {
        url: "{{ route('organization.select') }}",
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
          params.page = params.page || 1;
          var more  = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item) {
            option.push({
              id: item.id,
              text: `${item.code}`,
              name: `${item.name}`,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    }).on('select2:clear', function(e) {
      $('#unit_code_id_supersede').val(null).trigger('change');
      $('#orgcode_label_supersede').val(null);
      $('#unitcode_label_supersede').val(null);
    }).on('select2:close', function(e) {
      var data    = $(this).find('option:selected').val();
      var unit_code_id = $('#unit_code_id_supersede').select2('data');

      if (unit_code_id[0] && unit_code_id[0].organization.id != data) {
        $('#unit_code_id_supersede').val(null).trigger('change');
      }
    }).on('select2:select', function(e) {
      $('#orgcode_label_supersede').val($(this).select2('data')[0].name);
    });

    $('#unit_code_id').select2({
      ajax: {
        url: "{{ route('unitcode.select') }}",
        type: 'GET',
        dataType: 'json',
        data: function(params) {
          return {
            organization_id: $('#organization_code_id').val(),
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function(data, params) {
          params.page = params.page || 1;
          var more  = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item) {
            option.push({
              id: item.id,
              text: `${item.code}`,
              name: `${item.name}`,
              organization: item.organization,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    }).on('select2:select', function(e) {
      var data    = e.params.data;

      $('#unitcode_label').val(data.name);

      if (data.organization) {
        $('#orgcode_label').val(data.organization.name);
        var label = `${data.organization.code}`;
        $('#organization_code_id').select2('trigger', 'select', {
          data: {
            id: `${data.organization ? data.organization.id : null}`,
            text: `${data.organization ? label : ''}`,
            name: `${data.organization.name}`,
          }
        });
      }
    }).on('select2:clear', function(e) {
      $('#unitcode_label').val(null);
    });

    $('#unit_code_id_supersede').select2({
      ajax: {
        url: "{{ route('unitcode.select') }}",
        type: 'GET',
        dataType: 'json',
        data: function(params) {
          return {
            organization_id: $('#organization_code_id_supersede').val(),
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function(data, params) {
          params.page = params.page || 1;
          var more  = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item) {
            option.push({
              id: item.id,
              text: `${item.code}`,
              name: `${item.name}`,
              organization: item.organization,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    }).on('select2:select', function(e) {
      var data    = e.params.data;

      $('#unitcode_label_supersede').val(data.name);

      if (data.organization) {
        $('#orgcode_label_supersede').val(data.organization.name);
        var label = `${data.organization.code}`;
        $('#organization_code_id_supersede').select2('trigger', 'select', {
          data: {
            id: `${data.organization ? data.organization.id : null}`,
            text: `${data.organization ? label : ''}`,
            name: `${data.organization.name}`,
          }
        });
      }
    }).on('select2:clear', function(e) {
      $('#unitcode_label_supersede').val(null);
    });

    $('#document_center_id_supersede').select2({
      ajax: {
        url: "{{ route('documentcenter.select') }}",
        type: 'GET',
        dataType: 'json',
        data: function(params) {
          return {
            organization_code_id: $('#organization_code_id_supersede').val(),
            unit_code_id: $('#unit_code_id_supersede').val(),
            document_type_id: $('#document_type_id_supersede').val(),
            menu: `{{ $page }}`,
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function(data, params) {
          params.page = params.page || 1;
          var more  = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item) {
            option.push({
              id: item.id,
              text: `${item.document_number}`,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    });

    @if ($document->doctype)
      $('#document_type_id').select2('trigger', 'select', {
        data: {
          id: `{{ $document->doctype->id }}`,
          text: `{!! $document->doctype->code !!}`,
          name: `{!! $document->doctype->name !!}`,
        },
      });
      $('#document_type_id_revision').select2('trigger', 'select', {
        data: {
          id: `{{ $document->doctype->id }}`,
          text: `{!! $document->doctype->code !!}`,
          name: `{!! $document->doctype->name !!}`,
        },
      });
    @endif
    @if ($document->organization)
      $('#organization_code_id').select2('trigger', 'select', {
        data: {
          id: `{{ $document->organization->id }}`,
          text: `{!! $document->organization->code !!}`,
          name: `{!! $document->organization->name !!}`,
        }
      });
    @endif
    @if ($document->unitcode)
      $('#unit_code_id').select2('trigger', 'select', {
        data: {
          id: `{{ $document->unitcode->id }}`,
          text: `{!! $document->unitcode->code !!}`,
          name: `{!! $document->unitcode->name !!}`,
          organization: @json($document->unitcode->organization),
        }
      });
    @endif
    @if ($document->originator)
      $('#originator_id').select2('trigger', 'select', {
        data: {
          id: `{{ $document->originator->id }}`,
          text: `{!! $document->originator->name !!}`,
        }
      });
    @endif
  })
</script>
@endsection