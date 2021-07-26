@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">Create {{ $menu_name }}</h1>
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
<section class="content" id="content">
  <div class="container-fluid">
    <form action="{{ route('equipment.store') }}" method="post" role="form" enctype="multipart/form-data" autocomplete="off" id="form">
      @csrf
      <div class="card">
        <div class="card-body">
          <span class="title">
            <hr>
            <h5 class="text-md text-dark text-bold">{{ $menu_name }} Information</h5>
          </span>
          <div class="mt-5"></div>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group row">
                <label for="site_id" class="control-label col-md-3">Unit</label>
                <select name="site_id" id="site_id" class="form-control select col-md-8" required></select>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group row">
                <label for="area_id" class="control-label col-md-3">Area</label>
                <select name="area_id" id="area_id" class="form-control select col-md-8" required></select>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group row">
                <label for="equipment_name" class="control-label col-md-3">Asset</label>
                <input type="text" name="equipment_name" id="equipment_name" class="form-control col-md-8" placeholder="Asset Name">
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group row">
                <label for="remark" class="control-label col-md-3">Remark</label>
                <textarea class="form-control summernote col-md-8 d-none" name="remark" id="remark" rows="4" placeholder="Remark..."></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm" form="form">
            <b><i class="fas fa-save"></i></b> Submit
          </button>
          <a href="{{ route('area.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
            <b><i class="fas fa-times"></i></b> Cancel
          </a>
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
    $('.note-editor').addClass('col-md-8 p-0');
    
    $('#form').validate({
      rules: {
        site_id: {
          required: true,
        },
        area_id: {
          required: true,
        },
        equipment_name: {
          required: true,
        },
      },
      messages: {
        site_id: {
          required: "This field is required",
        },
        area_id: {
          required: "This field is required",
        },
        equipment_name: {
          required: "This field is required",
        },
      },
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

    $('#site_id').select2({
      placeholder: "Choose Unit...",
      ajax: {
        url: "{{ route('site.select') }}",
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
    }).on('select2:clear', function(e) {
      $('#area_id').val(null).trigger('change');
    }).on('select2:close', function(e) {
      var data    = $(this).find('option:selected').val();
      var area_id = $('#area_id').select2('data');

      if (area_id[0] && area_id[0].site.id != data) {
        $('#area_id').val(null).trigger('change');
      }
    });

    $('#area_id').select2({
      placeholder: "Choose Area...",
      ajax: {
        url: "{{ route('area.select') }}",
        type: 'GET',
        dataType: 'json',
        data: function(params) {
          return {
            site_id: $('#site_id').val(),
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
              site: item.site,
            });
          });
          return { results: option, more: more, };
        },
      },
      allowClear: true,
    }).on('select2:clear', function(e){
      $('#site_id').val(null).trigger('change');
    }).on('select2:select', function(e) {
      var data    = e.params.data;

      $('#site_id').select2('trigger', 'select', {
        data: {
          id: `${data.site ? data.site.id : null}`,
          text: `${data.site ? data.site.name : ''}`,
        }
      });
    });
  })
</script>
@endsection