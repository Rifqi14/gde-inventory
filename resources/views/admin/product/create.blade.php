@extends('admin.layouts.app')
@section('title', 'Product')
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
            Create Product
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Product</li>
            <li class="breadcrumb-item">Create</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <form id="form" role="form" action="{{route('product.store')}}" method="post" enctype="multipart/form-data">
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
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Product Name" required="" aria-required="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label mt-1" for="description">Product Description</label>
                                <div class="col-sm-6 controls">
                                    <textarea id="description" name="description" placeholder="Description..." class="form-control summernote" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label mt-1" for="product_category_id">Product Category <b class="text-danger">*</b></label>
                                <div class="col-sm-6 controls">
                                    <select name="product_category_id" data-placeholder="Product Category" style="width: 100%;" required class="select2 form-control" id="product_category_id">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label mt-1" for="sku">SKU</label>
                                <div class="col-sm-6 controls">
                                    <input type="text" class="form-control" id="sku" name="sku" placeholder="SKU" aria-required="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label mt-1" for="is_serial">Has Serial Number</label>
                                <div class="col-sm-6 controls">
                                    <div class="icheck-primary">
                                        <input type="checkbox" name="is_serial" id="is_serial">
                                        <label for="is_serial"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label mt-1" for="merek">Merek</label>
                                <div class="col-sm-6 controls">
                                    <input type="text" class="form-control" id="merek" name="merek" placeholder="Merek" aria-required="true">
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
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="image" accept="image/*" onchange="changePath(this)">
                                            <label class="custom-file-label" for="exampleInputFile">Attach Image</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5"></div>
                            <span class="title">
                                <hr />
                                <h5 class="text-md text-dark text-bold">Product UOM</h5>
                            </span>
                            <div class="mt-5"></div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label mt-1" for="uom">UOM</label>
                                <div class="col-sm-6 controls">
                                    <select data-placeholder="UOM" style="width: 100%;" class="select2 form-control" id="uom">
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" id="add-uom" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="addUom()">
                                        <b><i class="fas fa-plus"></i></b> Add
                                    </button>
                                </div>
                            </div>
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
                                                    <th class="text-center">#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="empty">
                                                    <td colspan="4" class="text-center">UOM Not Available</td>
                                                </tr>
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
                                                @if(count($sites) > 0)
                                                @foreach ($sites as $site)
                                                <tr>
                                                    <td width="100">
                                                        <div class="mb-2"></div>
                                                        <input type="hidden" name="minmax_site[]" value="{{ $site->id }}">
                                                        <b>{{ $site->name }}</b>
                                                    </td>
                                                    <td width="100">
                                                        <div class="form-group mb-0">
                                                            <input type="text" class="form-control" name="minimum[]" id="minimum{{ $site->id }}" placeholder="Minimum" aria-required="true">
                                                        </div>
                                                    </td>
                                                    <td width="100">
                                                        <div class="form-group mb-0">
                                                            <input type="text" class="form-control" name="maximum[]" id="maximum{{ $site->id }}" placeholder="Maximum" aria-required="true">
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
                            {{ csrf_field() }}
                            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
                                <b><i class="fas fa-save"></i></b>
                                Save
                            </button>
                            <a href="{{route('product.index')}}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
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
@endsection

@section('scripts')
<script>
    const addUom = () => {
        var uom_id = $("#uom").val();
        var uom = $("#uom").select2('data')[0].text;
        var ratio = $("#uom").select2('data')[0].ratio;
        var html = `
        <tr data-id="${uom_id}">
            <td width="200">
                <div class="mb-1"></div>
                <input type="hidden" name="uom_id[]" value="${uom_id}">
                ${uom}
            </td>
            <td width="200">
                <input type="text" class="form-control" id="ratio${uom_id}" name="ratio[]" placeholder="Ratio" aria-required="true" value="${(ratio)?ratio:0}">
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
    	]
       });
    }

    $(function(){
        summernote();
        $("#form").validate({
            rules: {
				"minimum[]": {
					number: true,
				},
                "maximum[]": {
					number: true,
				},
                "ratio[]": {
					number: true,
				}
			},
			messages: {
				"minimum[]": {
					number: "only number please.",
				},
                "maximum[]": {
					number: "only number please.",
				},
                "ratio[]": {
					number: "only number please.",
				}
			},
            errorElement: 'span',
            errorClass: 'help-block',
            focusInvalid: false,
            highlight: function (e) {
                $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function (e) {
                $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
                $(e).remove();
            },
            errorPlacement: function (error, element) {
                if(element.is(':file')) {
                    error.insertAfter(element.parent().parent().parent());
                }else if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                }else if (element.attr('type') == 'checkbox') {
                    error.insertAfter(element.parent());
                }else if (element.hasClass('select2')) {
                    error.insertAfter(element.parent().find('.select2-container'));
                }else{
                    error.insertAfter(element);
                }
            },
            submitHandler: function() { 
                $.ajax({
                    url:$('#form').attr('action'),
                    method:'post',
                    data: new FormData($('#form')[0]),
                    processData: false,
                    contentType: false,
                    dataType: 'json', 
                    beforeSend:function(){
                        blockMessage('#content', 'Loading', '#fff');
                    }
                }).done(function(response){
                    $('#content').unblock();
                    if(response.status){
                        document.location = response.results;
                    }else{	
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
                        }
                        toastr.warning(response.message);
                    }
                    return;
                }).fail(function(response){
                    $('#content').unblock();
                    var response = response.responseJSON;
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
                    }
                    toastr.warning(response.message);
                })	
            }
        });

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
                        text: item.name,
                        ratio: item.ratio
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