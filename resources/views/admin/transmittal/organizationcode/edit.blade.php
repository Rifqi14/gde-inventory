@extends('admin.layouts.app')
@section('title', "Edit $menu_name")

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">Edit {{ $menu_name }}</h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name }}</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
      <li class="breadcrumb-item">Edit</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <form action="{{ route('transmittalproperties.organizationcode.update', ['id' => $data->id]) }}" id="form-organizationcode" method="POST" autocomplete="off" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-7">
              <span class="title p-0">
                <hr>
                <h5 class="text-md text-dark text-bold">Category Contractor Properties</h5>
              </span>
              <div class="mt-5"></div>
              <div class="form-group row">
                <label for="code" class="col-form-label col-sm-3">Code</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="code" id="code" class="form-control" placeholder="Please insert code only uppercase..." value="{{ $data->code }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="name" class="col-form-label col-sm-3">Name</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="name" id="name" class="form-control" placeholder="Please inser name..." value="{{ $data->name }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="tagged_group_id" class="col-form-label col-sm-3">Associated User Group</label>
                <div class="col-sm-9 p-0">
                  <select name="tagged_group_id[]" id="tagged_group_id" class="form-control" data-select_url="role" multiple="multiple" data-eliminate="false"></select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm" form="form-organizationcode"><b><i class="fas fa-save"></i></b> Submit</button>
          <a href="{{ route('transmittalproperties.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm"><b><i class="fas fa-times"></i></b> Cancel</a>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection

@section('scripts')
<script>
  var actionmenu  = @json(json_encode($actionmenu));
  var base = "{{ url('admin') }}"
</script>
<script src="{{ asset("js/transmittal.js") }}"></script>
<script>
  var groups      = @json($data->groups);
  $(function(){
    var option = [];
    $.each(groups, function(index, item){
      option.push(new Option(item.name, item.id, true, true))
    });
    $('#tagged_group_id').append(option).trigger('change');
  })
</script>
@endsection