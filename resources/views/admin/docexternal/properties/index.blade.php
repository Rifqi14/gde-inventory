@extends('admin.layouts.app')
@section('title', $menu_name)

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-properties" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="createData()">
  <b><i class="fas fa-plus"></i></b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-properties" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
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
          <div class="card-header p-0">
            <ul class="nav nav-tabs tabs-properties" id="properties-tab" role="tablist">
              <li class="nav-item">
                <button type="button" onclick="activeTab('sitecode')" class="nav-link active pl-4 pr-4" id="sitecode-tab" data-toggle="tab" data-target="#sitecode" role="tab" aria-controls="document" aria-selected="true"><b>Site Code</b></button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="activeTab('disciplinecode')" class="nav-link pl-4 pr-4" id="disciplinecode-tab" data-toggle="tab" data-target="#disciplinecode" role="tab" aria-controls="document" aria-selected="false"><b>Discipline Code</b></button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="activeTab('documenttype')" class="nav-link pl-4 pr-4" id="documenttype-tab" data-toggle="tab" data-target="#documenttype" role="tab" aria-controls="document" aria-selected="false"><b>Document Type</b></button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="activeTab('originatorcode')" class="nav-link pl-4 pr-4" id="originatorcode-tab" data-toggle="tab" data-target="#originatorcode" role="tab" aria-controls="document" aria-selected="false"><b>Originator Code</b></button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="activeTab('phasecode')" class="nav-link pl-4 pr-4" id="phasecode-tab" data-toggle="tab" data-target="#phasecode" role="tab" aria-controls="document" aria-selected="false"><b>Phase Code</b></button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="activeTab('sheetsize')" class="nav-link pl-4 pr-4" id="sheetsize-tab" data-toggle="tab" data-target="#sheetsize" role="tab" aria-controls="document" aria-selected="false"><b>Sheet Size</b></button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="activeTab('kkscategory')" class="nav-link pl-4 pr-4" id="kkscategory-tab" data-toggle="tab" data-target="#kkscategory" role="tab" aria-controls="document" aria-selected="false"><b>KKS Category</b></button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="activeTab('kkscode')" class="nav-link pl-4 pr-4" id="kkscode-tab" data-toggle="tab" data-target="#kkscode" role="tab" aria-controls="document" aria-selected="false"><b>KKS Code</b></button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="activeTab('contractorname')" class="nav-link pl-4 pr-4" id="contractorname-tab" data-toggle="tab" data-target="#contractorname" role="tab" aria-controls="document" aria-selected="false"><b>Contractor Name</b></button>
              </li>
            </ul>
            <div class="tab-content" id="propertiesTabContent">
              @include('admin.docexternal.properties.sitecode.index')
              @include('admin.docexternal.properties.disciplinecode.index')
              @include('admin.docexternal.properties.documenttype.index')
              @include('admin.docexternal.properties.originatorcode.index')
              @include('admin.docexternal.properties.phasecode.index')
              @include('admin.docexternal.properties.sheetsize.index')
              @include('admin.docexternal.properties.kkscategory.index')
              @include('admin.docexternal.properties.kkscode.index')
              @include('admin.docexternal.properties.contracatorname.index')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@stack('filter')
@endsection

@section('scripts')
<script>
  var actionmenu  = @json(json_encode($actionmenu));
  var tab = 'sitecode';

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
  
  const activeTab = (tabs) => {
    tab = tabs;
    $('button[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
  }

  const createData = () => {
    window.location = `{{ url('admin/docexternalproperties') }}/${tab}/create`;
  }

  const filter = () => {
    $(`#form-filter-${tab}`).modal('show');
  }
</script>
@endsection