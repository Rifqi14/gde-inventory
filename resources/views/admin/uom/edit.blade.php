@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

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
      <li class="breadcrumb-item">Create</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <form action="{{ route('uom.update', ['id' => $uom->id]) }}" role="form" method="POST" enctype="multipart/form-data" autocomplete="off" id="form">
      @csrf
      @method('PUT')
      <div class="card">
        <div class="card-body">
          <span class="title">
            <hr>
            <h5 class="text-md text-dark text-bold">{{ $menu_name }} Information</h5>
          </span>
          <div class="mt-5"></div>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label" for="name">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $uom->name }}">
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label" for="category">Category</label>
                <select data-placeholder="Category" style="width: 100%;" class="select2 form-control" id="category" name="category">
                </select>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group row">
                <label class="col-md-12 col-xs-12 control-label" for="type">Type</label>
                <div class="col-sm-12 controls">
                  <select name="type" id="type" class="form-control select2" onchange="changeType(this)">
                    @foreach(config('enums.uom_type') as $key => $type)
                    <option value="{{ $key }}" @if($uom->type == $key) selected @endif>{{ $type }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label" for="ratio">Ratio</label>
                <input type="text" name="ratio" id="ratio" value="{{ $uom->ratio }}" readonly class="form-control" placeholder="Ratio">
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
            <b><i class="fas fa-save"></i></b> Save
          </button>
          <a href="{{ route('attendancemachine.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
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
  $(function(){
    $('.select2').select2();
    $("#category").select2({
        ajax: {
          url: "{{ route('uomcategory.select') }}",
          type:'GET',
          dataType: 'json',
          data: function (params) {
              return {
                  name:params.term,
                  page:params.page,
                  limit:30,
              };
          },
          processResults: function (data,params) {
            var more = (params.page * 30) < data.total;
            var option = [];
            $.each(data.rows,function(index,item){
                option.push({
                    id:item.id,  
                    text: item.name
                });
            });
            return {
                results: option, more: more,
            };
          },
        },
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
    $("#category").select2('trigger', 'select', {
      data: {id: `{{ $uom->uom_category_id }}`, text: `{{ $uom->category->name }}`}
    });
    $('#type').trigger('change');
  });

  function changeType(params) {
    if (params.value == 'REFERENCE') {
      $('#ratio').val(1);
      $('#ratio').attr('readonly', true);
      return true;
    }
    $('#ratio').attr('readonly', false);
  }
</script>
@endsection