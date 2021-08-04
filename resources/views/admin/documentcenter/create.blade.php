@extends('admin.layouts.app')
@section('title', 'Create Directory')
@section('stylesheets')

@endsection

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
    <form action="{{ route('documentcenter.store') }}" method="post" role="form" enctype="multipart/form-data" autocomplete="off" id="form">
      @csrf
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
                  <div class="form-group row">
                    <label for="number" class="control-label col-md-3">Document No:</label>
                    <input type="text" name="number" id="number" class="form-control col-md-8" placeholder="Document No...">
                  </div>
                  <div class="form-group row">
                    <label for="title" class="control-label col-md-3">Document Title:</label>
                    <textarea name="title" id="title" class="form-control col-md-8" cols="30" rows="4" placeholder="Document Title..."></textarea>
                  </div>
                  <div class="form-group row">
                    <label for="document_type_id" class="control-label col-md-3">Document Type:</label>
                    <div class="col-md-3 pl-0">
                      <select name="document_type_id" id="document_type_id" class="form-control select2" data-placeholder="Choose Document Type...">
                      </select>
                    </div>
                    <div class="col-md-5 pr-0">
                      <input type="text" name="doctype_label" id="doctype_label" class="form-control" placeholder="Document Type..." readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="organization_code_id" class="control-label col-md-3">Organization Code:</label>
                    <div class="col-md-3 pl-0">
                      <select name="organization_code_id" id="organization_code_id" class="form-control select2" data-placeholder="Choose Organization Code...">
                      </select>
                    </div>
                    <div class="col-md-5 pr-0">
                      <input type="text" id="orgcode_label" class="form-control" placeholder="Organization Code..." readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="unit_code_id" class="control-label col-md-3">Unit Code:</label>
                    <div class="col-md-3 pl-0">
                      <select name="unit_code_id" id="unit_code_id" class="form-control select2" data-placeholder="Choose Unit Code...">
                      </select>
                    </div>
                    <div class="col-md-5 pr-0">
                      <input type="text" id="unitcode_label" class="form-control" placeholder="Unit Code..." readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="originator_id" class="control-label col-md-3">Originator:</label>
                    <input type="hidden" name="category" value="{{ $request->category }}">
                    <select name="originator_id" id="originator_id" class="form-control select2 col-md-8">
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
                    <input type="hidden" name="created_user" id="created_user" readonly value="{{ Auth::guard('admin')->user()->id }}">
                    <input type="text" name="issued_by" id="issued_by" class="form-control col-md-8" readonly value="{{ Auth::guard('admin')->user()->name }}">
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
                        <input type="text" name="created_at" class="form-control text-right datepicker" id="created_at" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="updated_user" class="control-label col-md-4">Last Modified By:</label>
                    <input type="hidden" name="updated_user" id="updated_user" readonly value="{{ Auth::guard('admin')->user()->id }}">
                    <input type="text" name="updated_by" id="updated_by" class="form-control col-md-8" readonly value="{{ Auth::guard('admin')->user()->name }}">
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
                        <input type="text" name="updated_at" class="form-control text-right datepicker" id="updated_at" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="remark" class="control-label col-md-3">Document Remark</label>
                    <textarea class="form-control summernote col-md-8 d-none" name="remark" id="remark" rows="4" placeholder="Remark..."></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="card-footer text-right">
              <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm" form="form">
                <b><i class="fas fa-save"></i></b> Submit
              </button>
              <a href="{{ route('documentcenter.index', ['page' => $page]) }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                <b><i class="fas fa-times"></i></b> Cancel
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
  
  $(function() {
    summernote();
    $('.select2').select2();
    
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

    $('.datepicker').daterangepicker({
      singleDatePicker: true,
      timePicker: false,
      timePickerIncrement: 30,
      locale: {
        format: 'DD/MM/YYYY'
      }
    });

    $('#originator_id').select2({
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

    $('#contract_id').select2({
      tags: true,
    });
  })
</script>
@endsection