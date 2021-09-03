@extends('admin.layouts.app')
@section('title', 'Document Center Properties')

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
                <button type="button" onclick="activeTab('documenttype')" class="nav-link active pl-4 pr-4" id="documenttype-tab" data-toggle="tab" data-target="#documenttype" role="tab" aria-controls="document" aria-selected="true"><b>Document Type</b></button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="activeTab('organizationcode')" class="nav-link pl-4 pr-4" id="organizationcode-tab" data-toggle="tab" data-target="#organizationcode" role="tab" aria-controls="document" aria-selected="false"><b>Organization Code</b></button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="activeTab('unitcode')" class="nav-link pl-4 pr-4" id="unitcode-tab" data-toggle="tab" data-target="#unitcode" role="tab" aria-controls="document" aria-selected="false"><b>Unit Code</b></button>
              </li>
              <li class="nav-item">
                <button type="button" onclick="activeTab('dccategory')" class="nav-link pl-4 pr-4" id="dccategory-tab" data-toggle="tab" data-target="#dccategory" role="tab" aria-controls="document" aria-selected="false"><b>Document Category</b></button>
              </li>
            </ul>
            <div class="tab-content" id="propertiesTabContent">
              @include('admin.documenttype.index')
              @include('admin.organization.index')
              @include('admin.unitcode.index')
              @include('admin.dccategory.index')
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
  var tab = 'documenttype';

  const activeTab = tabs => {
    tab = tabs;
    buttonGroup(tab);
    $("button[data-toggle=tab]").on("shown.bs.tab", function(e) {
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
  }

  const buttonGroup = (tabs) => {
    switch (tabs) {
      case 'documenttype':
        $('#add-documenttype').removeClass('d-none');
        $('#filter-documenttype').removeClass('d-none');
        $('#add-organization').addClass('d-none');
        $('#filter-organization').addClass('d-none');
        $('#add-unitcode').addClass('d-none');
        $('#filter-unitcode').addClass('d-none');
        $('#add-dccategory').addClass('d-none');
        $('#filter-dccategory').addClass('d-none');
        break;
      case 'organizationcode':
        $('#add-documenttype').addClass('d-none');
        $('#filter-documenttype').addClass('d-none');
        $('#add-organization').removeClass('d-none');
        $('#filter-organization').removeClass('d-none');
        $('#add-unitcode').addClass('d-none');
        $('#filter-unitcode').addClass('d-none');
        $('#add-dccategory').addClass('d-none');
        $('#filter-dccategory').addClass('d-none');
        break;
      case 'dccategory':
        $('#add-documenttype').addClass('d-none');
        $('#filter-documenttype').addClass('d-none');
        $('#add-organization').addClass('d-none');
        $('#filter-organization').addClass('d-none');
        $('#add-unitcode').addClass('d-none');
        $('#filter-unitcode').addClass('d-none');
        $('#add-dccategory').removeClass('d-none');
        $('#filter-dccategory').removeClass('d-none');
        break;
    
      default:
        $('#add-documenttype').addClass('d-none');
        $('#filter-documenttype').addClass('d-none');
        $('#add-organization').addClass('d-none');
        $('#filter-organization').addClass('d-none');
        $('#add-unitcode').removeClass('d-none');
        $('#filter-unitcode').removeClass('d-none');
        $('#add-dccategory').addClass('d-none');
        $('#filter-dccategory').addClass('d-none');
        break;
    }
  }

  $(function() {
    buttonGroup(tab);
  });
</script>
@endsection