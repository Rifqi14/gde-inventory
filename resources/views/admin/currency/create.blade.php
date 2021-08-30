@extends('admin.layouts.app')
@section('title', $menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">
      {{ $menu_name }}
    </h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-dark mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name }}</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <form action="{{ route('currency.store') }}" role="form" method="POST" enctype="multipart/form-data" autocomplete="off" id="form">
      @csrf
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">{{ $menu_name }} Information</h5>
              </span>
              <div class="mt-5"></div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="symbol">Symbol</label>
                    <input type="text" name="symbol" class="form-control" placeholder="e.g: $ / Rp">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="code">Code</label>
                    <input type="text" name="code" class="form-control" placeholder="e.g: USD / IDR" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label" for="currency">Currency</label>
                    <input type="text" name="currency" class="form-control" placeholder="e.g: Dollar / Rupiah" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-12 col-xs-12 control-label" for="countries_id">Country</label>
                    <div class="col-sm-12 controls">
                      <select name="countries_id" id="countries_id" class="form-control select2" required>
                        <option value="">Select Country</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <button type="submit" class="btn bg-success color-palette btn-labeled legitRipple text-sm btn-sm">
                <b><i class="fas fa-save"></i></b> Save
              </button>
              <a href="{{ route('currency.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
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
  const formatCountry = (country) => {
    if (!country.code) { return country.text; }
    var $country = $(
      '<span class="flag-icon flag-icon-'+ country.code.toLowerCase() +' flag-icon-squared"></span>' +
      '&nbsp;&nbsp;&nbsp;<span class="flag-text">'+ country.text+"</span>"
    );
    return $country;
  }
  $(function(){
    $("#countries_id").select2({
      ajax: {
        url: "{{route('country.select')}}",
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
            var more = (params.page * 30) < data.total;
            var option = [];
            $.each(data.rows, function(index, item) {
                option.push({
                    id: item.id,
                    text: item.country,
                    code: item.code,
                });
            });
            return {
                results: option,
                pagination: {
                  more: more,
                }
            };
        },
      },
      templateResult: formatCountry,
      allowClear: true,
    });

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
        });
      }
    });
  })
</script>
@endsection