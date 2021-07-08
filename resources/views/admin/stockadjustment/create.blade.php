@extends('admin.layouts.app')
@section('title', 'Stock Adjustment')
@section('stylesheets')
    <link href="{{ asset("component/bootstrap-fileinput/css/fileinput.min.css") }}" rel="stylesheet">
    <link href="{{ asset("component/bootstrap-fileinput/themes/explorer/theme.min.css") }}" rel="stylesheet">
    <style>
        .label-square {
            width: 21px;
            height: 21px;
            line-height: 19px;
            padding: 0px;
        }
        .label-square i{
            font-size: 10px;
        }
        .btn.disabled, .btn:disabled {
            cursor: not-allowed;
            user-select: none;
            pointer-events: none;
        }
        table tr td{
            vertical-align: middle !important;
        }
        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
            font-weight: 700;
            color: #212529;
        }
        .card-body{
            min-height: 300px;
        }
        .nav-link > i{
            width: 14px;
            text-align: center;
            margin-right: .1rem;
        }
    </style>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Stock Adjustment
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Inventory</li>
            <li class="breadcrumb-item">Stock Adjustment</li>
            <li class="breadcrumb-item">Create</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <form id="form" role="form" action="{{route('stockadjustment.store')}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="c1-tab" data-toggle="pill" href="#c1" role="tab" aria-controls="c1" aria-selected="true">
                                <i class="fa fa-info-circle"></i>
                                Adjustment Information
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="c2-tab" data-toggle="pill" href="#c2" role="tab" aria-controls="c2" aria-selected="false">
                                <i class="fa fa-boxes"></i>
                                Adjustment Items
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="c3-tab" data-toggle="pill" href="#c3" role="tab" aria-controls="c3" aria-selected="false">
                                <i class="fa fa-file"></i>
                                Adjustment Assets
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="custom-content-below-tabContent">
                        <div class="tab-pane fade show active" id="c1" role="tabpanel" aria-labelledby="c1-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            {{-- <span class="title">
                                                <hr />
                                                <h5 class="text-md text-dark text-bold">Adjustment Information</h5>
                                            </span> --}}
                                            {{-- <div class="mt-5"></div> --}}
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 control-label" for="number">No</label>
                                                        <div class="col-sm-6 controls">
                                                            <input type="text" class="form-control" placeholder="Automatic" aria-required="true" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 control-label" for="adjustment_date">Date <b class="text-danger">*</b></label>
                                                        <div class="col-sm-6 controls">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                                                                </div>
                                                                <input type="text" class="form-control datepicker" id="adjustment_date" name="adjustment_date" placeholder="Adjustment Date" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12"></div>
                                                <div class="col-sm-12">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 control-label" for="warehouse_id">Site <b class="text-danger">*</b></label>
                                                        <div class="col-sm-6 controls">
                                                            <select class="form-control" id="site_id" name="site_id" data-placeholder="Site" required="" aria-required="true">

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 control-label" for="warehouse_id">Warehouse <b class="text-danger">*</b></label>
                                                        <div class="col-sm-6 controls">
                                                            <select class="form-control" id="warehouse_id" name="warehouse_id" data-placeholder="Warehouse" required="" aria-required="true">

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12"></div>
                                                <div class="col-sm-12">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 control-label" for="code">Status <b class="text-danger">*</b></label>
                                                        <div class="col-sm-6 controls">
                                                            <select class="form-control select2" id="status" name="status" data-placeholder="Status" required="" aria-required="true">
                                                                @foreach(config('enums.status_adjustment') as $key => $status)
                                                                    <option value="{{ $key }}">{{ $status }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12"></div>
                                                <div class="col-sm-12">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 control-label" for="description">Description</label>
                                                        <div class="col-sm-6 controls">
                                                            <textarea class="form-control summernote" name="description" id="description" rows="4" placeholder="Description..."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="c2" role="tabpanel" aria-labelledby="c2-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            {{-- <span class="title">
                                                <hr />
                                                <h5 class="text-md text-dark text-bold">Adjustment Items</h5>
                                            </span> --}}
                                            {{-- <div class="mt-5"></div> --}}
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group row">
                                                                <label class="col-sm-12 col-form-label">Category Product :</label>
                                                                <div class="col-sm-12">
                                                                    <select data-placeholder="Select Category Product" style="width: 100%;" required class="select2 form-control" id="category_product_id">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group row">
                                                                <label class="col-sm-12 col-form-label">Product :</label>
                                                                <div class="col-sm-12">
                                                                    <select data-placeholder="Choose Product (Select Warehouse First!)" style="width: 100%;" required class="select2 form-control disabled" id="product_id">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group row">
                                                                <label class="col-sm-12 col-form-label">&nbsp;</label>
                                                                <div class="col-sm-12">
                                                                    <button type="button" id="add-product" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple disabled" onclick="addproduct()">
                                                                        <b><i class="fas fa-plus"></i></b> Add
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="wrapper-table">
                                                        <input type="hidden" value="[]" name="adjustment_item" id="adjustment_item">
                                                        <table class="table table-striped datatable mb-0" width="100%" id="table-product" style="border: 1px solid #ddd;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Product</th>
                                                                    <th class="text-center">Serial No</th>
                                                                    <th class="text-center">UOM</th>
                                                                    <th class="text-center">Qty onSystem</th>
                                                                    <th class="text-center">Qty Adjusted</th>
                                                                    <th class="text-center" style="width: 10%">#</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="6" class="text-center" id="product-not-found">Data Not Found</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="c3" role="tabpanel" aria-labelledby="c3-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            {{-- <span class="title">
                                                <hr />
                                                <h5 class="text-md text-dark text-bold">Adjustment Assets</h5>
                                            </span> --}}
                                            {{-- <div class="mt-5"></div> --}}
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group row">
                                                        <label class="col-md-12 col-xs-12 control-label" for="code">Document</label>
                                                        <div id="wrapper-add-doc" class="col-12">
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <input type="text" class="form-control" name="doc_name[]" id="doc_name1" placeholder="Description" aria-required="true">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <div class="input-group">
                                                                                <div class="custom-file">
                                                                                    <input type="file" class="custom-file-input" name="doc[]" onchange="changePath(this)">
                                                                                    <label class="custom-file-label" for="exampleInputFile" style="border-top-right-radius: .25rem;border-bottom-right-radius: .25rem;">Adjustment Document</label>
                                                                                </div>
                                                                                <div class="input-group-append button-trasparent button-add-doc hover-up" onclick="addDoc(this)">
                                                                                    <span class="input-group-text" id=""><i class="fa fa-plus text-green color-palette"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row doc-item">
                                                                <div class="col-md-5">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <input type="text" class="form-control" name="doc_name[]" placeholder="Description" aria-required="true">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <div class="input-group">
                                                                                <div class="custom-file">
                                                                                    <input type="file" class="custom-file-input" name="doc[]" onchange="changePath(this)">
                                                                                    <label class="custom-file-label" for="exampleInputFile" style="border-top-right-radius: .25rem;border-bottom-right-radius: .25rem;">Adjustment Document</label>
                                                                                </div>
                                                                                <div class="input-group-append button-trasparent button-add-doc hover-up" onclick="deleteDoc($(this))">
                                                                                    <span class="input-group-text" id=""><i class="fas fa-trash text-maroon color-palette"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row doc-item">
                                                                <div class="col-md-5">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <input type="text" class="form-control" name="doc_name[]" placeholder="Description" aria-required="true">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <div class="input-group">
                                                                                <div class="custom-file">
                                                                                    <input type="file" class="custom-file-input" name="doc[]" onchange="changePath(this)">
                                                                                    <label class="custom-file-label" for="exampleInputFile" style="border-top-right-radius: .25rem;border-bottom-right-radius: .25rem;">Adjustment Document</label>
                                                                                </div>
                                                                                <div class="input-group-append button-trasparent button-add-doc hover-up" onclick="deleteDoc($(this))">
                                                                                    <span class="input-group-text" id=""><i class="fas fa-trash text-maroon color-palette"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row doc-item">
                                                                <div class="col-md-5">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <input type="text" class="form-control" name="doc_name[]" placeholder="Description" aria-required="true">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <div class="input-group">
                                                                                <div class="custom-file">
                                                                                    <input type="file" class="custom-file-input" name="doc[]" onchange="changePath(this)">
                                                                                    <label class="custom-file-label" for="exampleInputFile" style="border-top-right-radius: .25rem;border-bottom-right-radius: .25rem;">Adjustment Document</label>
                                                                                </div>
                                                                                <div class="input-group-append button-trasparent button-add-doc hover-up" onclick="deleteDoc($(this))">
                                                                                    <span class="input-group-text" id=""><i class="fas fa-trash text-maroon color-palette"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row doc-item">
                                                                <div class="col-md-5">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <input type="text" class="form-control" name="doc_name[]" placeholder="Description" aria-required="true">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <div class="input-group">
                                                                                <div class="custom-file">
                                                                                    <input type="file" class="custom-file-input" name="doc[]" onchange="changePath(this)">
                                                                                    <label class="custom-file-label" for="exampleInputFile" style="border-top-right-radius: .25rem;border-bottom-right-radius: .25rem;">Adjustment Document</label>
                                                                                </div>
                                                                                <div class="input-group-append button-trasparent button-add-doc hover-up" onclick="deleteDoc($(this))">
                                                                                    <span class="input-group-text" id=""><i class="fas fa-trash text-maroon color-palette"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row doc-item">
                                                                <div class="col-md-5">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <input type="text" class="form-control" name="doc_name[]" placeholder="Description" aria-required="true">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <div class="input-group">
                                                                                <div class="custom-file">
                                                                                    <input type="file" class="custom-file-input" name="doc[]" onchange="changePath(this)">
                                                                                    <label class="custom-file-label" for="exampleInputFile" style="border-top-right-radius: .25rem;border-bottom-right-radius: .25rem;">Adjustment Document</label>
                                                                                </div>
                                                                                <div class="input-group-append button-trasparent button-add-doc hover-up" onclick="deleteDoc($(this))">
                                                                                    <span class="input-group-text" id=""><i class="fas fa-trash text-maroon color-palette"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row doc-item">
                                                                <div class="col-md-5">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <input type="text" class="form-control" name="doc_name[]" placeholder="Description" aria-required="true">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12 controls">
                                                                            <div class="input-group">
                                                                                <div class="custom-file">
                                                                                    <input type="file" class="custom-file-input" name="doc[]" onchange="changePath(this)">
                                                                                    <label class="custom-file-label" for="exampleInputFile" style="border-top-right-radius: .25rem;border-bottom-right-radius: .25rem;">Adjustment Document</label>
                                                                                </div>
                                                                                <div class="input-group-append button-trasparent button-add-doc hover-up" onclick="deleteDoc($(this))">
                                                                                    <span class="input-group-text" id=""><i class="fas fa-trash text-maroon color-palette"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group row">
                                                        <label class="col-md-12 col-xs-12 control-label" for="code">Photo</label>
                                                        {{-- <div class="col-sm-12 controls">
                                                            <input type="file" class="form-control" name="images[]" multiple id="images" accept="image/*" />
                                                        </div> --}}
                                                        <div class="col-sm-12 controls">
                                                            <div class="form-group row">
                                                                <div class="col-sm-12 controls">
                                                                    <div class="row">
                                                                        <div class="col-sm-4 controls text-center upload-image mb-3">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="upload-preview-wrapper">
                                                                                        <a class="remove"><i class="fa fa-trash"></i></a>
                                                                                        <img src="{{ asset("/") }}assets/img/no-image.png" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="upload-btn-wrapper">
                                                                                        <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Foto 1</a>
                                                                                        <input type="file" data-name="item_image_1" id="item_image_1" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4 controls text-center upload-image mb-3">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="upload-preview-wrapper">
                                                                                        <a class="remove"><i class="fa fa-trash"></i></a>
                                                                                        <img src="{{ asset("/") }}assets/img/no-image.png" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="upload-btn-wrapper">
                                                                                        <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Foto 1</a>
                                                                                        <input type="file" data-name="item_image_2" id="item_image_2" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4 controls text-center upload-image mb-3">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="upload-preview-wrapper">
                                                                                        <a class="remove"><i class="fa fa-trash"></i></a>
                                                                                        <img src="{{ asset("/") }}assets/img/no-image.png" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="upload-btn-wrapper">
                                                                                        <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Foto 1</a>
                                                                                        <input type="file" data-name="item_image_3" id="item_image_3" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4 controls text-center upload-image mb-3">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="upload-preview-wrapper">
                                                                                        <a class="remove"><i class="fa fa-trash"></i></a>
                                                                                        <img src="{{ asset("/") }}assets/img/no-image.png" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="upload-btn-wrapper">
                                                                                        <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Foto 1</a>
                                                                                        <input type="file" data-name="item_image_4" id="item_image_4" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4 controls text-center upload-image mb-3">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="upload-preview-wrapper">
                                                                                        <a class="remove"><i class="fa fa-trash"></i></a>
                                                                                        <img src="{{ asset("/") }}assets/img/no-image.png" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="upload-btn-wrapper">
                                                                                        <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Foto 1</a>
                                                                                        <input type="file" data-name="item_image_5" id="item_image_5" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4 controls text-center upload-image mb-3">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="upload-preview-wrapper">
                                                                                        <a class="remove"><i class="fa fa-trash"></i></a>
                                                                                        <img src="{{ asset("/") }}assets/img/no-image.png" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="upload-btn-wrapper">
                                                                                        <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Foto 1</a>
                                                                                        <input type="file" data-name="item_image_6" id="item_image_6" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-footer">
                            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
                                <b><i class="fas fa-save"></i></b>
                                Save
                            </button>
                            <a href="{{route('stockadjustment.index')}}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-times"></i></b>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

<div class="modal fade" id="serialModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="rows m-0">
                    <div class="cols-sm-6 serial-modal-left">
                        <div class="cst-header">
                            <button type="button" class="back-button" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            Adjustment Item
                        </div>
                        <div class="scroll-wrapper">
                            <div class="product-info clearfix">
                                <button type="button" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="addnewserial()">
                                    <b><i class="fas fa-plus"></i></b>
                                    Add
                                </button>
                                <h1>Product Name</h1>
                                <p>SKU-001-001</p>
                            </div>
                            <div class="product-items">
                                {{-- <div class="product-item clearfix">
                                    <h1>(Auto Generate)</h1>
                                    <p>m</p>
                                    <button type="button" class="btn btn-transparent delete text-md p-0" onclick="" data-urutan="7">
                                        <i class="fas fa-trash text-maroon color-palette"></i>
                                    </button>
                                    <button type="button" class="btn btn-transparent edit text-md p-0" onclick="" data-urutan="7" data-target="#toolsModal" data-toggle="modal">
                                        <i class="fas fa-edit color-palette"></i>
                                    </button>
                                </div> --}}
                            </div>
                        </div>
                        <div class="cst-footer">
                            <button type="button" class="btn btn-labeled text-sm btn-sm btn-danger bg-red btn-flat legitRipple" data-dismiss="modal" aria-label="Close">
                                <b><i class="fas fa-times"></i></b>
                                Close
                            </button>
                        </div>
                    </div>
                    <div class="cols-sm-6 serial-modal-right">
                        <h1 class="empty-content"><i class="fa fa-box"></i></h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="noSerialModal">
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
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Code</label>
                                <input type="text" name="code" class="form-control" placeholder="Code">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Name</label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="Name">
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
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection

@section('scripts')
<script src="{{ asset("component/bootstrap-fileinput/js/fileinput.min.js") }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.2/themes/fas/theme.min.js"
    integrity="sha512-/NeWygjEalLr1Yk/Qe0aYaZ73Y5aIbPFF6J825FyprdqAoZNqUzCGf/4wIwVDsVwuJmS9SrF/tYWmRRn4r7qPA=="
    crossorigin="anonymous"></script>
<script>
    let base_url  = "{{ asset('/') }}";
    let urlSelectSite = "{{ route('site.select') }}";
    let urlSelectWarehouse = "{{ route('warehouse.select') }}";
    let urlSelectProduct = "{{ route('stockadjustment.selectproduct') }}";
    let urlSelectProductCategory = "{{ route('productcategory.select') }}";
    let urlgetItemSerial = "{{ route('stockadjustment.getitemserial') }}";
    let urlgetUOMProduct = "{{ route('stockadjustment.getuomproduct') }}";
    let urlgetDetailSerial = "{{ route('stockadjustment.getdetailserial') }}";
</script>
<script src="{{ asset("js/stockadjustment.js") }}"></script>
@endsection