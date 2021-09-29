@extends('admin.layouts.app')
@section('title', $menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">Workflow {{ $workflow->current_status }} Rev {{ $workflow->revision->revision_no }}</h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name }}</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
      <li class="breadcrumb-item">Workflow {{ $workflow->current_status }} Rev {{ $workflow->revision->revision_no }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <form action="" id="form"></form>
    <input type="hidden" name="id" value="{{ $workflow->id }}">
    <div class="card">
      <div class="card-body">
        <div id="document-properties">
          <div class="row">
            <div class="col-md-12">
              <span class="title p-0">
                <hr>
                <h5 class="text-md text-dark text-bold">Document Properties</h5>
              </span>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="document_number" class="col-form-label">Document No.</label>
                <input type="text" name="document_number" id="document_number" class="form-control" readonly value="{{ $workflow->document->document_number }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="document_title" class="col-form-label">Document Title</label>
                <input type="text" name="document_title" id="document_title" class="form-control" readonly value="{{ $workflow->document->document_title }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="planned_ifi_ifa_date" class="col-form-label">Planned IFI/IFA Date</label>
                <input type="text" name="planned_ifi_ifa_date" id="planned_ifi_ifa_date" class="form-control text-right" readonly value="{{ date('d-m-Y', strtotime($workflow->document->planned_ifi_ifa_date)) }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="planned_ifc_ifu_date" class="col-form-label">Planned IFC/IFU Date</label>
                <input type="text" name="planned_ifc_ifu_date" id="planned_ifc_ifu_date" class="form-control text-right" readonly value="{{ date('d-m-Y', strtotime($workflow->document->planned_ifc_ifu_date)) }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="planned_afc_date" class="col-form-label">Planned AFC Date</label>
                <input type="text" name="planned_afc_date" id="planned_afc_date" class="form-control text-right" readonly value="{{ date('d-m-Y', strtotime($workflow->document->planned_afc_date)) }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="planned_ab_date" class="col-form-label">Planned AB Date</label>
                <input type="text" name="planned_ab_date" id="planned_ab_date" class="form-control text-right" readonly value="{{ date('d-m-Y', strtotime($workflow->document->planned_ab_date)) }}">
              </div>
            </div>
          </div>
        </div>
        <div id="revision-properties">
          <div class="row">
            <div class="col-md-12">
              <span class="title p-0">
                <hr>
                <h5 class="text-md text-dark text-bold">Revision Properties</h5>
              </span>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="revision_no" class="col-form-label">Revision No.</label>
                <input type="text" name="revision_no" id="revision_no" class="form-control" readonly value="{{ $workflow->revision->revision_no }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="issue_status" class="col-form-label">Issue Status</label>
                <input type="text" name="issue_status" id="issue_status" class="form-control" readonly value="{{ $workflow->revision->issue_status }}">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="revision_remark" class="col-form-label">Revision No.</label>
                <textarea name="revision_remark" id="revision_remark" class="summernote form-control" rows="5" readonly>{{ $workflow->revision->revision_remark }}</textarea>
              </div>
            </div>
          </div>
        </div>
        <div id="workflow-table">
          <div class="py-3">
            <span class="title p-0">
              <hr>
              <h5 class="text-md text-dark text-bold">Workflow Table</h5>
            </span>
          </div>
          <div class="table-responsive p-0">
            <table id="table-workflow" class="table table-striped datatable" style="width: 100%">
              <thead>
                <tr class="workflow-header">
                  <th data-column="role_id" style="width: 10%">Group</th>
                  <th data-column="comment" style="width: 55%">Comment</th>
                  <th data-column="id" style="width: 20%">Action</th>
                  <th data-column="sla" style="width: 5%">SLA</th>
                  <th data-column="sla_dates" style="width: 10%">SLA Date</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div id="workflow-properties">
          <div class="py-3">
            <span class="title p-0">
              <hr>
              <h5 class="text-md text-dark text-bold">Workflow Properties</h5>
            </span>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="start_date" class="col-form-label">Workflow Start Date</label>
                <input type="text" name="start_date" id="start_date" class="form-control text-right" readonly value="{{ $workflow->start_date }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="document_category" class="col-form-label">Document Category</label>
                <input type="text" name="document_category" id="document_category" class="form-control" readonly value="{{ $workflow->document->document_category_id }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="complete_date" class="col-form-label">Workflow Complete Date</label>
                <input type="text" name="complete_date" id="complete_date" class="form-control text-right" readonly value="{{ $workflow->complete_date }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="return_code" class="col-form-label">Return Code</label>
                <input type="text" name="return_code" id="return_code" class="form-control" readonly value="{{ $workflow->document->return_code }}">
              </div>
            </div>
            <div class="col-md-6"></div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="next_status" class="col-form-label">Next Status</label>
                <input type="text" name="next_status" id="next_status" class="form-control" readonly value="{{ $workflow->document->next_status }}">
              </div>
            </div>
          </div>
        </div>
        <div id="workflow-file">
          <div class="py-3">
            <span class="title p-0">
              <hr>
              <h5 class="text-md text-dark text-bold">Workflow File</h5>
            </span>
          </div>
          <div class="form-group mt-3">
            <button type="button" onclick="addFile()" class="btn btn-labeled text-sm btn-lg btn-outline-primary btn-flat btn-block legitRipple">
              Add File
            </button>
          </div>
          <div class="table-responsive p-0">
            <table id="table-file" class="table table-striped datatable mt-3" style="width: 100%">
              <thead>
                <tr>
                  <th width="45%">File Name</th>
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
    </div>
  </div>
</section>

<div class="modal fade" id="modal-comment">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Comment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-comment" method="post" autocomplete="off">
          @csrf
          @method('PUT')
          <input type="hidden" name="id">
          <input type="hidden" name="status" value="COMMENT">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-form-label" for="nos_of_pages">Select Pages</label>
                <select name="nos_of_pages" id="nos_of_pages" class="select2 form-control">
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-form-label" for="comment">Comment</label>
                <textarea name="comment" id="comment" rows="10" class="form-control summernote"></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right mt-4">
        <button type="submit" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" form="form-comment">
          <b><i class="fas fa-save"></i></b>
          Save
        </button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>
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
  var base            = "{{ url('admin') }}";
  var token           = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/workflow.js') }}"></script>
@endsection