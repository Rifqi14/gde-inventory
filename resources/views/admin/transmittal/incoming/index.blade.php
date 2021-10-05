@extends('admin.layouts.app')
@section('title', $menu_name)

@section('button')
@if (in_array('create', $actionmenu))
<button class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" type="button" id="filter-incoming" onclick="filter()">
  <b><i class="fas fa-search"></i></b> Filter
</button>
@endif
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
          <div class="card-body p-0 pt-3">
            <div class="table-responsive">
              <table class="table table-striped" id="table-incoming" width="100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Transmittal Number</th>
                    <th>Transmittal Title</th>
                    <th>Attention</th>
                    <th>Status</th>
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
</section>
<div class="modal fade" id="modal-filter-incoming">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Filter Incoming</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form autocomplete="off" method="post" id="form-filter-incoming">
          @csrf
          <div class="form-group row">
            <label for="transmittal_number" class="col-sm-2 col-form-label">Transmittal Number</label>
            <div class="col-sm-10">
              <input type="text" name="transmittal_number" id="transmittal_number" class="form-control" placeholder="Please input transmittal number...">
            </div>
          </div>
          <div class="form-group row">
            <label for="transmittal_title" class="col-sm-2 col-form-label">Transmittal Title</label>
            <div class="col-sm-10">
              <input type="text" name="transmittal_title" id="transmittal_title" class="form-control" placeholder="Please input transmittal title...">
            </div>
          </div>
          <div class="form-group row">
            <label for="attention" class="col-sm-2 col-form-label">Attention</label>
            <div class="col-sm-10">
              <select name="attention_id" id="attention_id" class="form-control select2"></select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <button type="submit" class="btn btn-labeled text-sm btn-default btn-flat legitRipple" form="form-filter-incoming"><b><i class="fas fa-search"></i></b> Filter</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  var actionmenu      = @json($actionmenu);
  var token           = `{{ csrf_token() }}`;
  var base            = `{{ url('admin') }}`;
  var menu            = `incoming`;
  var select2         = $('select.ajax-select');
</script>
<script src="{{ asset('js/inoutcoming.js') }}"></script>
@endsection