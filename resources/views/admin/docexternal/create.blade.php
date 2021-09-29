@extends('admin.layouts.app')
@section('title', 'Create Directory')

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">Create Directory</h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name }}</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
      <li class="breadcrumb-item">Create Directory</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <form action="{{ route('documentcenterexternal.store') }}" method="POST" role="form" enctype="multipart/form-data" autocomplete="off" id="form">
      @csrf
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
                  <input type="text" name="document_number" id="document_number" class="form-control" placeholder="Drawing & Document No...">
                </div>
              </div>
              <div class="form-group row">
                <label for="document_title" class="col-form-label col-md-3">Document Title</label>
                <div class="col-md-9 p-0">
                  <textarea name="document_title" id="document_title" rows="5" class="form-control" placeholder="Document Title ..."></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="site_code_id" class="col-form-label col-md-3">Site Code</label>
                <div class="col-md-3 p-0">
                  <select name="site_code_id" id="site_code_id" class="form-control select2" data-select_name="site_code" data-sub_url="sitecode"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="discipline_code_id" class="col-form-label col-md-3">Discipline Code</label>
                <div class="col-md-3 p-0">
                  <select name="discipline_code_id" id="discipline_code_id" class="form-control select2" data-select_name="discipline_code" data-sub_url="disciplinecode"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="kks_category_id" class="col-form-label col-md-3">KKS Category</label>
                <div class="col-md-3 p-0">
                  <select name="kks_category_id" id="kks_category_id" class="form-control select2"></select>
                </div>
                <div class="col-md-6 pr-0"><input type="text" name="kks_category_label" id="kks_category_label" readonly class="form-control"></div>
              </div>
              <div class="form-group row">
                <label for="kks_code_id" class="col-form-label col-md-3">KKS Code</label>
                <div class="col-md-3 p-0">
                  <select name="kks_code_id" id="kks_code_id" class="form-control select2"></select>
                </div>
                <div class="col-md-6 pr-0"><input type="text" name="kks_code_label" id="kks_code_label" readonly class="form-control"></div>
              </div>
              <div class="form-group row">
                <label for="document_type_id" class="col-form-label col-md-3">Document Type</label>
                <div class="col-md-3 p-0">
                  <select name="document_type_id" id="document_type_id" class="form-control select2" data-select_name="document_type" data-sub_url="documenttypeext"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="originator_code_id" class="col-form-label col-md-3">Originator Code</label>
                <div class="col-md-3 p-0">
                  <select name="originator_code_id" id="originator_code_id" class="form-control select2" data-select_name="originator_code" data-sub_url="originatorcode"></select>
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
                  <input type="text" name="document_sequence" id="document_sequence" class="form-control text-right" placeholder="Document Sequence...">
                </div>
              </div>
              <div class="form-group row">
                <label for="document_category_id" class="col-form-label col-md-3">Document Category</label>
                <div class="col-md-3 p-0">
                  <select name="document_category_id" id="document_category_id" class="form-control select2" data-placeholder="Please choose data">
                    <option value=""></option>
                    @foreach (config('enums.document_category') as $key => $item)
                    <option value="{{ $key }}" data-label="{{ $item }}">{{ $key }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6 pr-0">
                  <input type="text" name="document_category_label" id="document_category_label" class="form-control" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="reviewer_matrix" class="col-form-label col-md-3">Reviewer Matrix</label>
                <div class="col-md-9 p-0">
                  <button type="button" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm" name="reviewer_matrix" id="reviewer_matrix" onclick="reviewerMatrix()">
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
                  <input type="text" name="contract_document_no" id="contract_document_no" class="form-control" placeholder="Contract Document No ...">
                </div>
              </div>
              <div class="form-group row">
                <label for="contractor_name_id" class="col-form-label col-md-3">Contractor Name</label>
                <div class="col-md-9 p-0">
                  <select name="contractor_name_id" id="contractor_name_id" class="form-control select2"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="contractor_group_id" class="col-form-label col-md-3">Contractor Group</label>
                <div class="col-md-9 p-0">
                  <select name="contractor_group_id" id="contractor_group_id" class="form-control select2"></select>
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
                    <input type="text" name="planned_ifi_ifa_date" id="planned_ifi_ifa_date" class="form-control text-right datepicker">
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
                    <input type="text" name="planned_ifc_ifu_date" id="planned_ifc_ifu_date" class="form-control text-right datepicker">
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
                    <input type="text" name="planned_afc_date" id="planned_afc_date" class="form-control text-right datepicker">
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
                    <input type="text" name="planned_ab_date" id="planned_ab_date" class="form-control text-right datepicker">
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="created_by" class="col-form-label col-md-3">Created by</label>
                <div class="col-md-9 p-0">
                  <input type="text" name="created_by" id="created_by" class="form-control" value="{{ auth()->user()->name }}" readonly>
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
                    <input type="text" name="created_at" id="created_at" class="form-control text-right datepicker" disabled>
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
                <textarea class="form-control summernote col-md-8 d-none" name="remark" id="remark" rows="4" placeholder="Remark..."></textarea>
              </div>
              <input type="hidden" name="page" value="{{ $page }}">
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm" form="form">
            <b><i class="fas fa-save"></i></b> Submit
          </button>
          <a href="{{ route('documentcenterexternal.index', ['page' => $page]) }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
            <b><i class="fas fa-times"></i></b> Cancel
          </a>
        </div>
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
        <form id="form-reviewer-matrix" autocomplete="off" method="POST">
          @csrf
          <div class="row">
            <input type="hidden" name="crud_status" value="create">
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
                        <input type="text" name="reviewer_matrix[{{ $key }}][days]" id="{{ $key }}_days" class="form-control text-right numberfield" placeholder="Calendar Days ...">
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
@endsection

@section('scripts')
<script>
  var reviewerMatrixData;
  var dataForm  = [];
  var routeRoleSelect = "{{ route('role.select') }}";
  var routeUserSelect = "{{ route('user.select') }}";
  var baseUrl         = "{{ url('admin/docexternalproperties') }}";
  var phaseCode       = @json($phase);
  var needSelect2Tag = $("#form").find("[data-sub_url]");
  var matrixData;
  var matrixEnums;
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
@endsection
