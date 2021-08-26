@extends('admin.layouts.app')
@section('title', $menu_name ? $menu_name : 'Product Category')
@section('stylesheets')
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">
      Product Category
    </h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name ? $parent_name : 'Inventory' }}</li>
      <li class="breadcrumb-item">{{ $menu_name ? $menu_name : 'Product Category' }}</li>
      <li class="breadcrumb-item">Edit</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <form class="form-horizontal no-margin" action="{{route('productcategory.update', ['id' => $data->id])}}" id="form">
            {{ csrf_field() }}
            @method('PUT')
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">Product Category Information</h5>
              </span>
              <div class="form-group row mt-4">
                <label class="col-md-2 col-xs-12 control-label" for="parent-category">Parent Category</label>
                <div class="col-md-6 controls">
                  <select class="form-control select2" name="parent_category" id="parent-category" data-placeholder="Parent Category"></select>
                </div>
              </div>
              <div class="form-grup row mt-4">
                <label class="col-md-2 col-xs-12 control-label" for="category-name">Category Name</label>
                <div class="col-md-6 controls">
                  <input class="form-control" type="text" id="category-name" name="name" placeholder="Category Name" value="{{$data->name}}" required>
                </div>
              </div>
              <div class="form-group row mt-4">
                <label class="col-md-2 col-xs-12 control-label" for="description">Description</label>
                <div class="col-md-6 controls">
                  <textarea class="form-control" name="description" id="description" rows="5" placeholder="Description">{{$data->description}}</textarea>
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm"><b><i class="fas fa-save"></i></b>Save</button>
              <a href="{{ route('productcategory.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm"><b><i class="fas fa-times"></i></b>Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
  $(function () {        
    $( "#parent-category" ).select2({
			ajax: {
				url: "{{route('productcategory.parentcategories')}}",
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

  @if($data->parent_id)    
    $("#parent-category").select2("trigger", "select", {
			data: {id:'{{$data->parent_id}}', text:'{{$data->parent->name}}'}
		});
  @endif

  $("#form").validate({
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        focusInvalid: false,
        highlight: function (e) {
          $(e).closest('.form-group').removeClass('has-success').addClass('was-validated has-error');
        },

        success: function (e) {
          $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
          $(e).remove();
        },
        errorPlacement: function (error, element) {
          if(element.is(':file')) {
            error.insertAfter(element.parent().parent().parent());
          }else
          if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
          }
          else
          if (element.attr('type') == 'checkbox') {
            error.insertAfter(element.parent());
          }
          else{
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
              blockMessage('body', 'Please Wait . . . ', '#fff');
            }
          }).done(function(response){
                $('body').unblock();
                if(response.status){
                  toastr.success('Data has been updated.');
                  document.location = "{{route('productcategory.index')}}";
                }
                else{                  
                  toastr.warning(`${response.message}`);
                }
                return;
          }).fail(function(response){
              $('body').unblock();
              var response = response.responseJSON;      
              toastr.warning('Failed to insert data.');
              console.log({errorMessage : response.message});                
          })
        }
      });
    });
</script>
@endsection