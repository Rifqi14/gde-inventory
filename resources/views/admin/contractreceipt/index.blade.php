@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')

@endsection

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" id="add-contract_receipt" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('contractreceipt.create') }}')">
  <b>
    <i class="fas fa-plus"></i>
  </b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" id="filter-contract_receipt" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
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
            <table id="table-contract_receipt" class="table table-striped datatable" width="100%">
              <thead>
                <tr>
                  <th width="5">No.</th>
                  <th width="100">Contract</th>
                  <th width="100">Warehouse</th>
                  <th width="100">Batch</th>
                  <th width="100">Document Uploaded</th>
                  <th width="100">Status</th>
                  <th width="20">#</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td><b><a href="#">101-BTBG-000001</a></b><br>02/07/2020</td>
                  <td>Gudang A</td>
                  <td>1 of 3</td>
                  <td>5 of 11</td>
                  <td><span class="badge bg-warning">Waiting</span></td>
                  <td>
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
                  <td>2</td>
                  <td><b><a href="#">101-BTBG-000001</a></b><br>02/07/2020</td>
                  <td>Gudang A</td>
                  <td>1 of 3</td>
                  <td>5 of 11</td>
                  <td><span class="badge bg-info">In Progress</span></td>
                  <td>
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
                  <td>3</td>
                  <td><b><a href="#">101-BTBG-000001</a></b><br>02/07/2020</td>
                  <td>Gudang A</td>
                  <td>1 of 3</td>
                  <td>5 of 11</td>
                  <td><span class="badge bg-success">Completed</span></td>
                  <td>
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
        <h5 class="modal-title text-bold">Filter Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-search" method="post" autocomplete="off">
          @csrf
          <input type="hidden" name="_method" />
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label" for="contract">Contract</label>
                <select name="contract" id="contract" class="form-control select2">
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label" for="status">Status</label>
                <select name="status" id="status" class="form-control select2">
                  <option value="">Select Type</option>
                  @foreach(config('enums.status_receipt') as $key => $type)
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
    })
  })
</script>
@endsection