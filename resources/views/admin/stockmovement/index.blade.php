@extends('admin.layouts.app')
@section('title',$menu_name)

@section('button')
@if (in_array('create', $actionmenu))
<button type="button" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="windowLocation('{{ route('stockmovement.create') }}')">
    <b>
        <i class="fas fa-plus"></i>
    </b> Create
</button>
@endif
@if (in_array('read', $actionmenu))
<button type="button" class="btn btn-labeled text-sm btn-sm btn-default btn-flat legitRipple" onclick="filter()">
    <b>
        <i class="fas fa-search"></i>
    </b> Search
</button>
@endif
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">{{$menu_name}}</h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{$parent_name}}</li>
            <li class="breadcrumb-item">{{$menu_name}}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="cotainer-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped" id="table-movement" width="100%">
                            <thead>
                                <th class="text-center">No</th>
                                <th class="text-center">Date</th>
                                <th>Movement Number</th>
                                
                                <th class="text-center">Action</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
</script>
@endsection