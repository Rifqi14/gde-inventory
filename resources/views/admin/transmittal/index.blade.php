@extends('admin.layouts.app')
@section('title', $menu_name)

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-properties" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="createData()"><b><i class="fas fa-plus"></i></b> Create</button>
@endif
<button type="button" id="filter-properties" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
  <b><i class="fas fa-search"></i></b> Filter
</button>
@endsection

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
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header p-0">
            <ul class="nav nav-tabs tabs-properties" id="properties-tab" role="tablist">
              <li class="nav-item">
                <button class="nav-link active pl-4 pr-4" id="category-contractor" type="button" onclick="activeTab('categorycontractor')" data-toggle="tab" data-target="#categorycontractor" role="tab" aria-controls="document" aria-selected="true"><b>Category Contractor</b></button>
              </li>
              <li class="nav-item">
                <button class="nav-link pl-4 pr-4" id="organization-code" type="button" onclick="activeTab('organizationcode')" data-toggle="tab" data-target="#organizationcode" role="tab" aria-controls="document" aria-selected="false"><b>Organization Code</b></button>
              </li>
            </ul>
            <div class="tab-content" id="propertiesTabContent">
              <div class="tab-pane fade show active" id="categorycontractor" role="tabpanel" aria-labelledby="category-contractor">
                <div class="table-responsive">
                  <table class="table table-striped" id="table-category-contractor" width="100%">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Tagged Group</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade show" id="organizationcode" role="tabpanel" aria-labelledby="organization-code">
                <div class="table-responsive">
                  <table class="table table-striped" id="table-organization-code" width="100%">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Tagged Group</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="modal-filter-categorycontractor">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Filter Category Contractor</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="form-filter-categorycontractor" autocomplete="off">
          @csrf
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
              <input type="text" name="name" id="name" class="form-control" placeholder="Please input name...">
            </div>
          </div>
          <div class="form-group row">
            <label for="address" class="col-sm-2 col-form-label">Address</label>
            <div class="col-sm-10">
              <textarea class="form-control" id="address" name="address" rows="3" placeholder="Please input address..."></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label for="tagged_group_id" class="col-sm-2 col-form-label">Group</label>
            <div class="col-sm-10">
              <select name="tagged_group_id" id="tagged_group_id" class="form-control" data-select_url="role" data-eliminate="false"></select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <button type="submit" class="btn btn-labeled text-sm btn-default btn-flat legitRipple" form="form-filter-categorycontractor"><b><i class="fas fa-search"></i></b> Filter</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-filter-organizationcode">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Filter Organization Code</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="form-filter-organizationcode" autocomplete="off">
          @csrf
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
              <input type="text" name="name" id="name" class="form-control" placeholder="Please input name...">
            </div>
          </div>
          <div class="form-group row">
            <label for="tagged_group_id" class="col-sm-2 col-form-label">Group</label>
            <div class="col-sm-10">
              <select name="tagged_group_id" id="tagged_group_id" class="form-control"></select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <button type="submit" class="btn btn-labeled text-sm btn-default btn-flat legitRipple" form="form-filter-organizationcode"><b><i class="fas fa-search"></i></b> Filter</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  var actionmenu  = @json(json_encode($actionmenu));
  var tab         = 'categorycontractor';
  var token       = `{{ csrf_token() }}`;
  var base        = `{{ url('admin') }}`;
</script>
<script src="{{ asset('js/transmittal.js') }}"></script>
@endsection