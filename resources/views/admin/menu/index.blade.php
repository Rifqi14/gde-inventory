@extends('admin.layouts.app')

@section('stylesheets')
<link rel="stylesheet" href="{{ asset('assets/css/nestable/nestable.css') }}">
<style type="text/css">
    .overlay-wrapper {
        position: relative;
    }
    .dd-handle {
        display: block;
        height: auto;
        padding: 8px 10px;
        text-decoration: none;
        font-weight: 500;
        border: 1px solid #ccc;
        border-radius: 3px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .dd-handle span {
        font-weight: 500;
    }
    .dd-item>button {
        margin: 10px 0;
    }
    .item_actions {
        position: absolute;
        top: 8px;
        right: 10px;
    }
    .dd-handle {
        background: #fff;
        box-shadow: 0px 0px 9px -5px rgb(0 0 0 / 50%);
    }
    .dd-item .item_actions button {
        position: relative;
        width: 40px;
        height: 40px;
        border-radius: 50% !important;
        display: inline-block;
        margin-top: 1px;
    }
    .dd-item>button[data-action=collapse], .dd-item>button[data-action=expand] {
        font-size: 16px;
        margin: 0px;
        width: 25px;
        height: 25px;
        border: 1px solid #aaa;
        border-radius: 50%;
        line-height: 1;
        background-color: #f8f9fa;
        border-color: #ddd;
        position: relative;
        margin: 7px;
        transition-duration: 0.3s;
    }
    .dd-item>button[data-action=collapse]:hover, 
    .dd-item>button[data-action=expand]:hover{
        background-color: #e9ecef;
    }
</style>
@endsection

@section('button')
<button type="button" id="add-menu" class="btn btn-labeled text-sm btn-md btn-success btn-flat legitRipple" onclick="showCreate()">
    <b><i class="fas fa-plus"></i></b> Create
</button>
<button type="button" id="save-menu" class="btn btn-labeled text-sm btn-md btn-warning btn-flat legitRipple updateorder">
    <b><i class="fas fa-save"></i></b> Save
</button>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Menu
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Menu</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="content">
    <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body p-0">
            <div class="dd">
                {!!buildDD($menus);!!}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="form-create">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-bold">Create Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" />
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="menu_name" class="form-control" placeholder="Menu Name..." autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label>Route:</label>
                        <input type="text" name="menu_route" class="form-control" placeholder="Menu Route..." autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label>icon:</label>
                        <input type="text" name="menu_icon" class="form-control" placeholder="Menu Icon..." autocomplete="off" required>
                    </div>
                    <div class="text-right mt-4">
                        <button type="submit" class="btn bg-olive color-platte btn-labeled legitRipple text-sm">
                            <b><i class="fas fa-save"></i></b>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection

@section('scripts')
    <script>
        var create_url = '{{ url("") }}';
        var menu_store = '{{ route("menu.store") }}';
        var token = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('assets/js/nestable/jquery.nestable.js') }}"></script>
    <script src="{{ asset('js/menu.js') }}"></script>
@endsection