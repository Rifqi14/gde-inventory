@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')
<style>
    #input-list-checkbox .form-control {
        height: calc(1.9rem + 7.5px);
    }

    .wrapper-table table {
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
    }

    .wrapper-table {
        position: relative;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: .25rem;
    }
</style>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            {{$menu_name}}
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{ $parent_name }}</li>
            <li class="breadcrumb-item">{{ $menu_name }}</li>
            <li class="breadcrumb-item">Detail</li>
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
                    <div class="card-body">

                        <span class="title">
                            <hr />
                            <h5 class="text-md text-dark text-bold">Product Information</h5>
                        </span>
                        <div class="mt-5"></div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label mt-1" for="name">Product Name <b class="text-danger">*</b></label>
                            <div class="col-sm-6 controls">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Product Name" required="" aria-required="true" value="{{ $product->name }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label mt-1" for="description">Product Description</label>
                            <div class="col-sm-6 controls">
                                <textarea id="description" name="description" placeholder="Description..." class="form-control summernote" rows="5" disabled>{{ $product->description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label mt-1" for="product_category_id">Product Category <b class="text-danger">*</b></label>
                            <div class="col-sm-6 controls">
                                <select name="product_category_id" data-placeholder="Product Category" style="width: 100%;" required class="select2 form-control" id="product_category_id" disabled>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label mt-1" for="sku">SKU</label>
                            <div class="col-sm-6 controls">
                                <input type="text" class="form-control" id="sku" name="sku" placeholder="SKU" aria-required="true" value="{{ $product->sku }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label mt-1" for="is_serial">Has Serial Number</label>
                            <div class="col-sm-6 controls">
                                <div class="icheck-primary">
                                    <input type="checkbox" name="is_serial" id="is_serial" @if ($product->is_serial == '1') checked @endif disabled>
                                    <label for="is_serial"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label mt-1" for="merek">Merek</label>
                            <div class="col-sm-6 controls">
                                <input type="text" class="form-control" id="merek" name="merek" placeholder="Merek" aria-required="true" value="{{ $product->merek }}" disabled>
                            </div>
                        </div>

                        <div class="mt-5"></div>
                        <span class="title">
                            <hr />
                            <h5 class="text-md text-dark text-bold">Product Media</h5>
                        </span>
                        <div class="mt-5"></div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label mt-1" for="image">Photo</label>
                            <div class="col-sm-6 controls">
                                <img src="@if($product->image) {{ url('/') }}/{{ $product->image }} @else {{ asset('assets/img/no-image.png') }} @endif" class="img-responsive" style="max-width: 100px;">
                            </div>
                        </div>

                        <div class="mt-5"></div>
                        <span class="title">
                            <hr />
                            <h5 class="text-md text-dark text-bold">Product UOM</h5>
                        </span>
                        <div class="mt-5"></div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label mt-1" for="uom">Convertion</label>
                            <div class="col-sm-6 controls">
                                <div class="wrapper-table">
                                    <table class="table table-striped mb-0" id="uom-table">
                                        <thead style="border-bottom: 1px solid #ddd;">
                                            <tr>
                                                <th class="text-left">UOM</th>
                                                <th class="text-left">Ratio</th>
                                                <th class="text-center">Show</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($product->uoms) > 0)
                                            @foreach ($product->uoms as $uom)
                                            <tr data-id="{{ $uom->uom->id }}">
                                                <td width="200">
                                                    <div class="mb-1"></div>
                                                    <input type="hidden" name="uom_id[]" value="{{ $uom->uom->id }}">
                                                    {{ $uom->uom->name }}
                                                </td>
                                                <td width="200">
                                                    <div class="mb-1"></div>
                                                    {{ $uom->ratio }}
                                                </td>
                                                <td width="100" class="text-center">
                                                    <div class="mb-2"></div>
                                                    <div class="icheck-success d-inline ">
                                                        <input type="checkbox" name="show[]" value="show" id="{{ $uom->uom->id }}" @if($uom->is_show == "show") checked="" @endif disabled>
                                                        <label for="{{ $uom->uom->id }}">&nbsp;</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr class="empty">
                                                <td colspan="3" class="text-center">UOM Not Available</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5"></div>
                        <span class="title">
                            <hr />
                            <h5 class="text-md text-dark text-bold">Minimum & Maximum Stock</h5>
                        </span>
                        <div class="mt-5"></div>
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label mt-1" for="uom">Stock</label>
                            <div class="col-sm-6 controls">
                                <div class="wrapper-table">
                                    <table class="table table-striped mb-0" id="table-maxmin">
                                        <thead style="border-bottom: 1px solid #ddd;">
                                            <tr>
                                                <th class="text-left">Unit</th>
                                                <th class="text-left">Minimum</th>
                                                <th class="text-left">Maximum</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($product->minmax) > 0)
                                            @foreach ($product->minmax as $site)
                                            <tr>
                                                <td width="100">
                                                    <div class="mb-2"></div>
                                                    <input type="hidden" name="minmax_site[]" value="{{ $site->site_id }}">
                                                    <b>{{ $site->site->name }}</b>
                                                </td>
                                                <td width="100">
                                                    <div class="form-group mb-0">
                                                        <div class="mb-1"></div>
                                                        {{ $site->minimum }}
                                                    </div>
                                                </td>
                                                <td width="100">
                                                    <div class="form-group mb-0">
                                                        <div class="mb-1"></div>
                                                        {{ $site->maximum }}
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr class="empty">
                                                <td colspan="4" class="text-center">Site Not Available</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <a href="{{route('product.index')}}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                            <b><i class="fas fa-times"></i></b>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const addUom = () => {
        var uom_id = $("#uom").val();
        var uom = $("#uom").select2('data')[0].text;
        var html = `
        <tr data-id="${uom_id}">
            <td width="200">
                <div class="mb-1"></div>
                <input type="hidden" name="uom_id[]" value="${uom_id}">
                ${uom}
            </td>
            <td width="200">
                <input type="text" class="form-control" id="ratio${uom_id}" name="ratio[]" placeholder="Ratio" aria-required="true">
            </td>
            <td width="100" class="text-center">
                <div class="mb-2"></div>
                <div class="icheck-success d-inline ">
                    <input type="checkbox" name="show[]" value="show" id="${uom_id}" checked>
                    <label for="${uom_id}">&nbsp;</label>
                </div>
            </td>
            <td class="text-center">
                <div class="mb-1"></div>
                <button type="button" class="btn btn-transparent text-md" onclick="removeUom($(this))" data-uom="${uom_id}">
                    <i class="fas fa-trash text-maroon color-palette"></i>
                </button>
            </td>
        </tr>
        `;
        $("#uom-table tbody .empty").remove();
        $("#uom-table tbody").append(html);
        $( "#uom" ).empty();
    }

    const removeUom = (a) => {
        var uom_id = a.attr('data-uom');
        $("#uom-table tbody").find("tr[data-id="+uom_id+"]").remove();
        var is_empty = !$.trim($("#uom-table tbody").html());
        if(is_empty){
            html =  `
                <tr class="empty">
                    <td colspan="4" class="text-center">UOM Not Available</td>
                </tr>
            `;
            $("#uom-table tbody").append(html);
        }
    }

    const summernote = () => {
        $('.summernote').summernote({
            contenteditable: false,
            height:145,
            toolbar: [
                ['style', ['style']],
                ['font-style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['font', ['fontname']],
                ['font-size',['fontsize']],
                ['font-color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['misc', ['fullscreen', 'codeview', 'help']]
            ],
        })
    }

    $(function(){
        summernote();
        $('.summernote').summernote("disable");
        $( "#product_category_id" ).select2({
            ajax: {
                url: "{{ route('productcategory.select') }}",
                type:'GET',
                dataType: 'json',
                data: function (params) {
                    return {
                        name:params.term,
                        page:params.page,
                        limit:30,
                    };
                },
                processResults: function (data,params) {
                var more = (params.page * 30) < data.total;
                var option = [];
                $.each(data.rows,function(index,item){
                    option.push({
                        id:item.id,  
                        text: item.name
                    });
                });
                return {
                    results: option, more: more,
                };
                },
            },
            allowClear: true,
            escapeMarkup: function (text) { return text; }
        });
        $("#product_category_id").select2("trigger", "select", {
            data: {id:'{{$product->product_category_id}}', text:'{!! $product->category_name !!}'}
        });

        $( "#uom" ).select2({
            ajax: {
                url: "{{ route('uomcategory.select') }}",
                type:'GET',
                dataType: 'json',
                data: function (params) {
                    var selected = $("input[name='uom_id[]']").map(function(){return $(this).val();}).get();
                    return {
                        name:params.term,
                        page:params.page,
                        limit:30,
                        selected: selected,
                    };
                },
                processResults: function (data,params) {
                var more = (params.page * 30) < data.total;
                var option = [];
                $.each(data.rows,function(index,item){
                    option.push({
                        id:item.id,  
                        text: item.name
                    });
                });
                return {
                    results: option, more: more,
                };
                },
            },
            allowClear: true,
            escapeMarkup: function (text) { return text; }
        });
    });
    function changePath(that) {
        let filename = $(that).val()
        $(that).next().html(filename)
    }
</script>
@endsection