@extends('admin.layouts.app')
@section('title', $menu_name)

@section('stylesheets')
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Create {{$menu_name}}
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{ $parent_name }}</li>
            <li class="breadcrumb-item">{{ $menu_name }}</li>
            <li class="breadcrumb-item">Create</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <form id="form" role="form" action="{{route('warehouse.store')}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr />
                                <h5 class="text-md text-dark text-uppercase">Warehouse Information</h5>
                            </span>
                            <div class="mt-5"></div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="code">Code <b style="color: red;">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <input type="text" class="form-control" id="code" name="code" placeholder="Code" required="" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="name">Name <b style="color: red;">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" required="" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="site_id">Unit <b style="color: red;">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <select data-placeholder="Choose Unit" class="select2 form-control" id="site_id" name="site_id" data-placeholder="Select Site">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="province_id">Province <b style="color: red;">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <select data-placeholder="Choose Province" style="width: 100%;" required class="select2 form-control" id="province_id" name="province_id" data-placeholder="Select Province">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="name">Region <b style="color: red;">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <select data-placeholder="Choose Region" style="width: 100%;" required class="select2 form-control" id="region_id" name="region_id" data-placeholder="Select Region">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="district_id">Distric <b style="color: red;">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <select data-placeholder="Choose Distric" style="width: 100%;" required class="select2 form-control" id="district_id" name="district_id" data-placeholder="Select Distric">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="subdistrict_id">Sub Distric <b style="color: red;">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <select data-placeholder="Choose Sub Distric" style="width: 100%;" required class="select2 form-control" id="village_id" name="subdistrict_id" data-placeholder="Select Sub Distric">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="postal_code">Postal Code <b style="color: red;">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Postal Code" required="" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xs-12 control-label" for="address">Address <b style="color: red;">*</b></label>
                                        <div class="col-sm-12 controls">
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Address..." required="" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr />
                                <h5 class="text-md text-dark text-uppercase">Other Information</h5>
                            </span>
                            <div class="mt-5"></div>
                            <div class="form-group row">
                                <label class="col-md-12 col-xs-12 control-label" for="status">Status <b style="color: red;">*</b></label>
                                <div class="col-sm-12 controls">
                                    <select name="status" id="status" class="form-control select2" required data-placeholder="Select Status">
                                        <option value=""></option>
                                        @foreach(config('enums.warehouse_status') as $key => $status)
                                        <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-12 col-xs-12 control-label" for="description">Description</label>
                                <div class="col-sm-12 controls">
                                    <textarea class="form-control summernote" name="description" id="description" rows="4" placeholder="Description..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
                                <b><i class="fas fa-save"></i></b>
                                Save
                            </button>
                            <a href="{{route('warehouse.index')}}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
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
    
    $(function() {
        $(".select2").select2({
            allowClear: true
        });

        $('.summernote').summernote({
            height: 145,
            toolbar: [
                ['style', ['style']],
                ['font-style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['font', ['fontname']],
                ['font-size', ['fontsize']],
                ['font-color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['misc', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $("#form").validate({
            rules: {
                code: {
                    required: true
                },
                name: {
                    required: true
                },
                site_id: {
                    required: true
                },
                province_id: {
                    required: true
                },
                region_id: {
                    required: true
                },
                district_id: {
                    required: true
                },
                subdistrict_id: {
                    required: true
                },
                postal_code: {
                    required: true
                },
                address: {
                    required: true
                }
            },
            message: {
                code: {
                    required: 'This field is required.'
                },
                name: {
                    required: 'This field is required.'
                },
                site_id: {
                    required: 'This field is required.'
                },
                province_id: {
                    required: 'This field is required.'
                },
                region_id: {
                    required: 'This field is required.'
                },
                district_id: {
                    required: 'This field is required.'
                },
                subdistrict_id: {
                    required: 'This field is required.'
                },
                postal_code: {
                    required: 'This field is required.'
                },
                address: {
                    required: 'This field is required.'
                }
            },
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            highlight: function(e) {
                $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
                $(e).remove();
            },
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group .controls').append(error);

                if (element.is(':file')) {
                    error.insertAfter(element.parent().parent().parent());
                } else if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.attr('type') == 'checkbox') {
                    error.insertAfter(element.parent());
                } else
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.closest('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function() {
                $.ajax({
                    url: $('#form').attr('action'),
                    method: 'post',
                    data: new FormData($('#form')[0]),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function() {
                        blockMessage('#content', 'Loading', '#fff');
                    }
                }).done(function(response) {
                    $('#content').unblock();
                    if (response.status) {
                        toastr.success('Data has been saved.');
                        document.location = response.results;
                    } else {
                        toastr.warning(response.message);
                    }
                    return;
                }).fail(function(response) {
                    $('#content').unblock();
                    var response = response.responseJSON;
                    toastr.warning(response.message);
                })
            }
        });

        $("#site_id").select2({
            ajax: {
                url: "{{ route('site.select') }}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    return {
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        });

        $("#province_id").select2({
            ajax: {
                url: "{{ route('province.select') }}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    return {
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:close', function(e) {
            var data = $(this).find('option:selected').val();
            var city = $('#region_id').select2('data');

            if (city[0] && city[0].province_id != data) {
                $('#region_id').val(null).trigger('change');
                $('#district_id').val(null).trigger('change');
                $('#village_id').val(null).trigger('change');
            }
        }).on('select2:clearing', function() {
            $('#region_id').val(null).trigger('change');
            $('#district_id').val(null).trigger('change');
            $('#village_id').val(null).trigger('change');
        });

        $("#region_id").select2({
            ajax: {
                url: "{{ route('region.select') }}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var province_id = $('#province_id').find('option:selected').val();
                    province_id = province_id ? province_id : '';
                    return {
                        province_id: province_id,
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:close', function(e) {
            var data = $(this).find('option:selected').val();
            var district = $('#district_id').select2('data');

            if (district[0] && district[0].region_id != data) {
                $('#district_id').val(null).trigger('change');
                $('#village_id').val(null).trigger('change');
            }
        }).on('select2:clearing', function() {
            $('#district_id').val(null).trigger('change');
            $('#village_id').val(null).trigger('change');
        });

        $("#district_id").select2({
            ajax: {
                url: "{{ route('district.select') }}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var region_id = $('#region_id').find('option:selected').val();
                    region_id = region_id ? region_id : '';
                    return {
                        region_id: region_id,
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:close', function(e) {
            var data = $(this).find('option:selected').val();
            var village = $('#village_id').select2('data');

            if (village[0] && village[0].region_id != data) {
                $('#village_id').val(null).trigger('change');
            }
        }).on('select2:clearing', function() {
            $('#village_id').val(null).trigger('change');
        });

        $("#village_id").select2({
            ajax: {
                url: "{{ route('village.select') }}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var district_id = $('#district_id').find('option:selected').val();
                    district_id = district_id ? district_id : '';
                    return {
                        district_id: district_id,
                        name: params.term,
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        });
    });
</script>
@endsection