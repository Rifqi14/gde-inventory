@extends('admin.layouts.app')
@section('title', "Create $menu_name")

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
    <form action="{{ route('transmittalproperties.categorycontractor.store') }}" method="post" id="form-categorycontractor" autocomplete="off" enctype="multipart/form-data">
      @csrf
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-7 pr-4">
              <span class="title p-0">
                <hr>
                <h5 class="text-md text-dark text-bold">Category Contractor Properties</h5>
              </span>
              <div class="mt-5"></div>
              <div class="form-group row">
                <label for="code" class="col-form-label col-sm-3">Code</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="code" id="code" class="form-control" placeholder="Please insert code only uppercase...">
                </div>
              </div>
              <div class="form-group row">
                <label for="name" class="col-form-label col-sm-3">Name</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="name" id="name" class="form-control" placeholder="Please inser name...">
                </div>
              </div>
              <div class="form-group row">
                <label for="tagged_group_id" class="col-form-label col-sm-3">Tagged Group</label>
                <div class="col-sm-9 p-0">
                  <select name="tagged_group_id[]" id="tagged_group_id" class="form-control" data-select_url="role" multiple="multiple" data-eliminate="true"></select>
                </div>
              </div>
            </div>
            <div class="col-sm-5">
              <span class="title p-0">
                <hr>
                <h5 class="text-md text-dark text-bold">Other Properties</h5>
              </span>
              <div class="mt-5"></div>
              <div class="form-group row">
                <label for="address" class="col-form-label col-sm-3">Address</label>
                <div class="col-sm-9 p-0">
                  <input type="text" name="address" id="address" class="form-control" placeholder="Please insert address...">
                </div>
              </div>
              <div class="form-group row">
                <label for="app_logo" class="col-form-label col-sm-3">Logo</label>
                <div class="col-sm-9 p-0">
                  <div class="controls text-center upload-image">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="upload-preview-wrapper">
                          <a class="remove"><i class="fa fa-trash"></i></a>
                          <img src="#" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="upload-btn-wrapper">
                          <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Upload a Picture</a>
                          <input class="form-control" type="file" name="app_logo" id="app_logo" accept="image/*" />
                        </div>
                        <p class="text-sm text-muted">File must be no more than 10 MB</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm" form="form-categorycontractor"><b><i class="fas fa-save"></i></b> Submit</button>
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
@endsection