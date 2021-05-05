@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

@section('button')
@if (in_array('read', $actionmenu))
<button type="button" id="filter-product_serial" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
  <b>
    <i class="fas fa-search"></i>
  </b> Search
</button>
@endif
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">{{ $menu_name }}</h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ $parent_name }}</li>
      <li class="breadcrumb-item">{{ $menu_name }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section id="content" class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header"></div>
          <div class="card-body table-responsive p-0">
            <table id="table-product_serial" class="table tables-striped datatable">
              <thead>
                <tr>
                  <th width="5%">No.</th>
                  <th width="30%">Nama Asset</th>
                  <th width="30%">Nomor Serial</th>
                  <th width="30%">Last Warehouse</th>
                  <th width="5%">#</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td><b><a href="#">Galon Aqua</a></b><br>SKU Number</td>
                  <td>SKU Number Auto Increment</td>
                  <td>Warehouse Patuha 1</td>
                  <td>
                    <div class="btn-group">
                      <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-bars"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);">
                          <i class="far fa-eye"></i> View Data
                        </a>
                        <a class="dropdown-item delete" href="javascript:void(0);">
                          <i class="fas fa-qrcode"></i> QR Code
                        </a>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection