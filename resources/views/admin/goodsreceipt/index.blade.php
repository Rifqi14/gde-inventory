@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-goods_receipt" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('goodsreceipt.create') }}')">
  <b>
    <i class="fas fa-plus"></i>
  </b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-goods_receipt" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
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
            <table id="table-goods_receipt" class="table table-striped datatable" width="100%">
              <thead>
                <tr>
                  <th width="5%" class="text-center">No.</th>
                  <th width="10%" class="text-center">Date</th>
                  <th width="30%">Receipt Number</th>
                  <th width="30%">Reference</th>
                  <th width="10%">Product</th>
                  <th width="10%" class="text-center">Status</th>
                  <th width="5%" class="text-center">#</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center">1</td>
                  <td class="text-center">20/03/2021</td>
                  <td>DIENG.000001</td>
                  <td><span class="badge bg-primary">DIENG.PO.00001</span></td>
                  <td>2 produk</td>
                  <td class="text-center"><span class="badge bg-success">Approved</span></td>
                  <td class="text-center">
                    <div class="btn-group">
                      <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-bars"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);">
                          <i class="far fa-eye"></i> View Data
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);">
                          <i class="far fa-edit"></i> Update Data
                        </a>
                        <a class="dropdown-item delete" href="javascript:void(0);">
                          <i class="far fa-trash-alt"></i> Delete Data
                        </a>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="text-center">1</td>
                  <td class="text-center">20/03/2021</td>
                  <td>DIENG.000001</td>
                  <td><span class="badge bg-primary">DIENG.PO.00001</span></td>
                  <td>2 produk</td>
                  <td class="text-center"><span class="badge bg-success">Approved</span></td>
                  <td class="text-center">
                    <div class="btn-group">
                      <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-bars"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);">
                          <i class="far fa-eye"></i> View Data
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);">
                          <i class="far fa-edit"></i> Update Data
                        </a>
                        <a class="dropdown-item delete" href="javascript:void(0);">
                          <i class="far fa-trash-alt"></i> Delete Data
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
<div class="modal fade" id="form-filter">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-bold">Filter {{ $menu_name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-search" method="post" autocomplete="off">
          @csrf
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label" for="date">Date</label>
                <input type="text" name="date" id="date" class="form-control datepicker" placeholder="Date">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label" for="receipt_number">Receipt Number</label>
                <input type="text" name="receipt_number" id="receipt_number" class="form-control datepicker" placeholder="Receipt Number">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label" for="status">Status</label>
                <select name="status" id="status" class="form-control select2">
                  <option value="">Select Status</option>
                  @foreach(config('enums.status_global') as $key => $type)
                  <option value="{{ $key }}">{{ $type }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="text-right mt-4">
            <button type="submit" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple">
              <b><i class="fas fa-search"></i></b>
              Filter
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  const filter = () => {
    $('#form-filter').modal('show');
  }

  $(function(){
    $('.select2').select2();
    $('#form-search').submit(function(e){
      e.preventDefault();
      dataTable.draw();
      $('#form-filter').modal('hide');
    });
  })
</script>
@endsection