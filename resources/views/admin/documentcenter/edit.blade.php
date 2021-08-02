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
                  <input type="hidden" name="category_menu" value="{{ $document->category->name }}">
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
                    <select name="document_type_id" id="document_type_id" class="document_type_id form-control select2 col-md-8 lock" data-placeholder="Choose Document Type...">
                    </select>
                  </div>
                  <div class="form-group row">
                    <label for="organization_code_id" class="control-label col-md-3">Organization Code:</label>
                    <select name="organization_code_id" id="organization_code_id" class="form-control select2 col-md-8 lock" data-placeholder="Choose Organization Code...">
                    </select>
                  </div>
                  <div class="form-group row">
                    <label for="unit_code_id" class="control-label col-md-3">Unit Code:</label>
                    <select name="unit_code_id" id="unit_code_id" class="form-control select2 col-md-8 lock" data-placeholder="Choose Unit Code...">
                    </select>
                  </div>
                  <div class="form-group row">
                    <label for="role_id" class="control-label col-md-3">Originator:</label>
                    <input type="hidden" name="category" value="{{ $document->category_id }}">
                    <select name="role_id[]" multiple="multiple" id="role_id" class="form-control select2 col-md-8 lock">
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
                <th width="20%">Issue Purpose</th>
                <th width="20%">Issue Status</th>
                <th width="27%">Transmittal Status</th>
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
<div class="modal fade" id="form-document">
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
          <input type="hidden" name="category_menu" value="{{ $document->category->name }}">
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
                  <select name="document_type" id="document_type_id" class="document_type_id form-control select2" disabled></select>
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
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="document_upload">
                    <label class="custom-file-label form-control" for="document_upload">Attach a document</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="revision_remark" class="col-form-label">Revision Remark</label>
                <textarea name="revision_remark" id="revision_remark" rows="5" class="form-control summernote-revise" placeholder="Revision Remark..."></textarea>
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
                <select name="issue_purpose" id="issue_purpose" class="form-control select2" data-placeholder="Choose Purpose...">
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
        </form>
      </div>
      <div class="modal-footer text-right">
        <button type="submit" class="btn btn-labeled text-md btn-md btn-success btn-flat legitRipple" form="form-upload">
          <b><i class="fas fa-save"></i></b> Submit
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  var actionmenu    = @json(json_encode($actionmenu));
  var global_status = JSON.parse(`{!! json_encode(config('enums.global_status')) !!}`);
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

  const documentModal = (type, document_id = null) => {
    $('#form-document').modal('show');
    $('#form-upload').attr('action', `{{ url('admin/centerdocument') }}`);
    $('#form-upload').find('input[name="_method"]').val('');
    summernoteDocument();
    initInputFile();
    $('.summernote-document').summernote('disable');
    $('textarea[name="revision_remark"]').summernote('code', '');
    $('#form-upload')[0].reset();
    $('select[name="issue_purpose"]').val('').trigger('change');
    $('#form-upload').find('.datepicker').daterangepicker({
      singleDatePicker: true,
      timePicker: false,
      timePickerIncrement: 30,
      locale: {
        format: 'DD/MM/YYYY'
      }
    });
  }

  const fillForm = (e) => {
    $('#form-upload').attr('action', `{{ url('admin/') }}`)
    $('#form-upload').find('input[name="_method"]').val('');
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
    $('#form-document').modal('show');
    $('#form-document .modal-title').text(`Revision ${e.data.revision}`);
    $('#form-upload').attr('action', `{{ url('admin/centerdocument') }}/${e.data.id}`);
    $('#form-upload').find('input[name="_method"]').val('PUT');
    $('#form-upload').find('input[name="revision_number"]').val(e.data.revision);
    $('#form-upload').find('textarea[name="revision_remark"]').text(e.data.remark);
    $('#form-upload').find('textarea[name="revision_remark"]').summernote('code', e.data.remark);
    $('#form-upload').find('select[name="issue_purpose"]').select2('trigger', 'select', {
      data: {
        id: `${e.data.issue_purpose}`,
        text: `For ${e.data.issue_purpose}`
      }
    });
    $('#form-upload').find('input[name="transmittal_number"]').val(e.data.transmittal_no);
    $('#form-upload').find('input[name="created_by"]').val(e.data.created_by.id);
    $('#form-upload').find('input[name="created_by_label"]').val(e.data.created_by.name);
    $('#form-upload').find('input[name="updated_by"]').val(e.data.updated_by.id);
    $('#form-upload').find('input[name="updated_by_label"]').val(e.data.updated_by.name);
    $('#form-upload').find('input[name="created_at"]').val(e.data.created_date);
    $('#form-upload').find('input[name="updated_at"]').val(e.data.last_modified);
    summernoteDocument();
    $('.summernote-document').summernote('disable');
    initInputFile();
  }
  
  $(function() {
    summernote();
    summernoteRevise();
    initInputFile();
    lock('lock');
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

    $('#form-upload').validate({
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
        { orderable: false, targets: [0, 5] },
        { className: "text-center", targets: [0, 4, 5] },
        { render: function (data, type, row) {
          return `For ${row.issue_purpose}`
        }, targets: [1] },
        { render: function (data, type, row) {
          var label     = '',
              text      = '',
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
              });
          
          return `<span class="badge bg-${label} text-sm">${text}</span>`;
        }, targets: [2] },
        { render: function (data, type, row) {
          return `<font class="text-md text-bold">${row.created_by ? row.created_by.name : ''}</font><div class="text-sm text-semibold">Date: <font class="text-info">${row.last_modified}</font></div>`;
        }, targets: [4] },
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
        }, targets: [5] },
      ],
      columns: [
        { data: "revision" },
        { data: "issue_purpose" },
        { data: "status"},
        { data: "transmittal_no" },
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

    $('#role_id').select2({
      placeholder: "Choose Group...",
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

    $('.document_type_id').select2({
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
              text: `${item.code} - ${item.name}`,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
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
              text: `${item.code} - ${item.name}`,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    }).on('select2:clear', function(e) {
      $('#unit_code_id').val(null).trigger('change');
    }).on('select2:close', function(e) {
      var data    = $(this).find('option:selected').val();
      var unit_code_id = $('#unit_code_id').select2('data');

      if (unit_code_id[0] && unit_code_id[0].organization.id != data) {
        $('#unit_code_id').val(null).trigger('change');
      }
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
              text: `${item.code} - ${item.name}`,
              organization: item.organization,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    }).on('select2:select', function(e) {
      var data    = e.params.data;

      if (data.organization) {
        var label = `${data.organization.code} - ${data.organization.name}`;
        $('#organization_code_id').select2('trigger', 'select', {
          data: {
            id: `${data.organization ? data.organization.id : null}`,
            text: `${data.organization ? label : ''}`,
          }
        });
      }
    });
    @if ($document->doctype)
      $('.document_type_id').select2('trigger', 'select', {
        data: {
          id: `{{ $document->doctype->id }}`,
          text: `{!! $document->doctype->code !!} - {!! $document->doctype->name !!}`
        }
      });
    @endif
    @if ($document->organization)
      $('#organization_code_id').select2('trigger', 'select', {
        data: {
          id: `{{ $document->organization->id }}`,
          text: `{!! $document->organization->code !!} - {!! $document->organization->name !!}`
        }
      });
    @endif
    @if ($document->unitcode)
      $('#unit_code_id').select2('trigger', 'select', {
        data: {
          id: `{{ $document->unitcode->id }}`,
          text: `{!! $document->unitcode->code !!} - {!! $document->unitcode->name !!}`,
          organization: @json($document->unitcode->organization),
        }
      });
    @endif
    @if ($document->informers)
      @foreach ($document->informers as $key => $value )
        $('#role_id').select2('trigger', 'select', {
          data: {
            id: `{{ $value->id }}`,
            text: `{!! $value->name !!}`,
          }
        });
      @endforeach
    @endif
  })
</script>
@endsection