@extends('admin.layouts.app')
@section('title',@$menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Product Transfer
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
        <li class="breadcrumb-item">{{ @$parent_name }}</li>
            <li class="breadcrumb-item">{{ @$menu_name }}</li>
            <li class="breadcrumb-item">Detail</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <span class="title">
                            <hr>
                            <h5 class="text-md text-dark text-uppercase">Transfer Information</h5>
                        </span>
                        <div class="row mt-4">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="transfer-number" class="control-label">Transfer Number</label>
                                    <input type="text" class="form-control" placeholder="Automatically generated" value="{{$data->transfer_number}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="origin-unit" class="control-label">Origin Unit</label>
                                    <select name="origin_unit" id="origin-unit" class="form-control site" data-placeholder="Choose origin unit" disabled></select>
                                </div>
                                <div class="form-group">
                                    <label for="origin-warehouse" class="control-label">Origin Warehouse</label>
                                    <select name="origin_warehouse" id="origin-warehouse" class="form-control select2" data-placeholder="Choose origin warehouse" disabled></select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="date-issued" class="control-label">Date</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="datepicker" class="form-control datepicker text-right" id="transfer-date" placeholder="Enter date issued" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="destination-unit" class="control-label">Destination Unit</label>
                                    <select name="destination_unit" id="destination-unit" class="form-control site" data-placeholder="Choose destination unit" disabled></select>
                                </div>
                                <div class="form-group">
                                    <label for="destination-warehouse" class="control-label">Destination Warehouse</label>
                                    <select name="destination_warehouse" id="destination-warehouse" class="form-control select2" data-placeholder="Choose destination warehouse" disabled></select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <span class="title">
                            <hr>
                            <h5 class="text-md tetx-dark text-uppercase">Other Information</h5>
                        </span>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="issued-by" class="control-label">Issued By</label>
                                    <input type="text" class="form-control" value="{{Auth::guard('admin')->user()->name}}" readonly>
                                    <input type="hidden" name="issued_by" value="{{Auth::guard('admin')->user()->id}}">
                                </div>
                                <div class="form-group">
                                    <label for="description" class="control-label">Description</label>
                                    <textarea name="description" id="description" class="form-control summernote" placeholder="Enter description">{{$data->description}}</textarea>
                                </div>
                                <div class="form-group form-status">
                                    <label for="status" class="control-label">Status</label>
                                    <input type="hidden" name="status" id="status" value="">
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <span class="title">
                            <hr>
                            <h5 class="text-md text-dark text-uppercase">Product Information</h5>
                        </span>
                        <div class="row mt-4">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-product" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="200">Product Name</th>
                                            <th width="15" class="text-center">UOM</th>
                                            <th width="15" class="text-center">Qty System</th>
                                            <th width="10" class="text-center">Qty Transfer</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="no-available-data">
                                            <td colspan="4" class="text-center">No data available.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <span class="title">
                            <hr>
                            <h5 class="text-md text-dark text-uppercase">Supporting Document</h5>
                        </span>
                        <ul class="nav nav-tabs" id="suppDocumentTab" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active pl-4 pr-4" id="document-tab" data-toggle="tab" data-target="#document" role="tab" aria-controls="document" aria-selected="true"><b>Document</b></button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link pl-4 pr-4" id="photo-tab" data-toggle="tab" data-target="#photo" type="button" role="tab" aria-controls="photo" aria-selected="false"><b>Photo</b></button>
                            </li>
                        </ul>
                        <div class="tab-content" id="suppDocumentTabContent">
                            <div class="tab-pane fade show active" id="document" role="tabpanel" aria-labelledby="document-tab">                                
                                <!-- TABLE DOCUMENT -->
                                <table id="table-document" class="table table-striped datatable mt-3" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="45%">Document Name</th>
                                            <th width="45%" class="text-center">File</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="no-available-data">
                                            <td colspan="2" class="text-center">No available data.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade show" id="photo" role="tabpanel" aria-labelledby="photo-tab">                                
                                <!-- TABLE PHOTO -->
                                <table id="table-photo" class="table table-striped datatable mt-3" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="45%">Photo Name</th>
                                            <th width="45%" class="text-center">File</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="no-available-data">
                                            <td colspan="2" class="text-center">No available data.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('producttransfer.index') }}" class="btn btn-secondary color-palette btn-labeled legitRipple text-sm">
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
    $(function() {
        var originSiteID = {{$data->origin_site_id?$data->origin_site_id:null}},
            destSiteID   = {{$data->destination_site_id?$data->destination_site_id:null}},
            originWareID = {{$data->origin_warehouse_id?$data->origin_warehouse_id:null}},
            destWareID   = {{$data->destination_warehouse_id?$data->destination_warehouse_id:null}},
            transferDate = '{{$data->transfer_date?$data->transfer_date:null}}',
            state        = '{{$data->status}}',
            deleteAt     = '{{$data->deleted_at?$data->deleted_at:null}}',
            badgeCol     = ''; 

            
        switch (state) {
            case 'draft':
                badgeCol = 'bg-gray';
                break;
            case 'waiting':
                badgeCol = 'badge-warning';
                break;
            case 'approved':
                badgeCol = 'badge-info';
                break;
            case 'archived':
                badgeCol = 'bg-red'
            default:
                badgeCol = '';
                break;
        }

        if(deleteAt){
            state    = 'Archived';
            badgeCol = 'bg-red';
        }

        $('.form-status').append(`<span class="badge ${badgeCol} text-sm" style="text-transform: capitalize;">${state}</span>`);

        $('.select2').select2({
            allowClear: true
        });

        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            timePicker: false,
            timePickerIncrement: 30,
            drops: 'auto',
            opens: 'center',
            locale: {
                format: 'DD/MM/YYYY'
            },
        });

        $('.summernote').summernote({
            height: 145,
            toolbar: []
        });

        $('.summernote').summernote('disable');

        $("#origin-unit").select2({
            ajax: {
                url: "{{route('site.select')}}",
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
        }).on('select2:clear', function() {
            $('#origin-warehouse').val(null).trigger('change');
        });

        $("#destination-unit").select2({
            ajax: {
                url: "{{route('site.select')}}",
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
        }).on('select2:clear', function(e) {
            $('#destination-warehouse').val(null).trigger('change');
        });

        $("#origin-warehouse").select2({
            ajax: {
                url: "{{route('warehouse.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var originSite = $('#origin-unit').find('option:selected').val(),
                        destination = $('#destination-warehouse').find('option:selected').val();
                    return {
                        name: params.term,
                        page: params.page,
                        site_id: originSite ? originSite : '',
                        exception_id: destination ? destination : null,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name,
                            site_id: item.site_id,
                            site: item.site
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if (data.site_id) {
                $('#origin-unit').select2('trigger', 'select', {
                    data: {
                        id: data.site_id,
                        text: `${data.site}`
                    }
                });
            }
        });

        $("#destination-warehouse").select2({
            ajax: {
                url: "{{route('warehouse.select')}}",
                type: 'GET',
                dataType: 'json',
                data: function(params) {
                    var originSite = $('#destination-unit').find('option:selected').val(),
                        destination = $('#origin-warehouse').find('option:selected').val();
                    return {
                        name: params.term,
                        page: params.page,
                        site_id: originSite ? originSite : '',
                        exception_id: destination ? destination : null,
                        limit: 30,
                    };
                },
                processResults: function(data, params) {
                    var more = (params.page * 30) < data.total;
                    var option = [];
                    $.each(data.rows, function(index, item) {
                        option.push({
                            id: item.id,
                            text: item.name,
                            site_id: item.site_id,
                            site: item.site
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if (data.site_id) {
                $('#destination-unit').select2('trigger', 'select', {
                    data: {
                        id: data.site_id,
                        text: `${data.site}`
                    }
                });
            }
        });

        if (transferDate) {
            $('#transfer-date').data('daterangepicker').setStartDate(`${transferDate}`);
            $('#transfer-date').data('daterangepicker').setEndDate(`${transferDate}`);
        }

        if (originSiteID) {
            $('#origin-unit').select2('trigger', 'select', {
                data: {
                    id: originSiteID,
                    text: `{{$data->originsites->origin_site}}`
                }
            });
        }

        if (originWareID) {
            $('#origin-warehouse').select2('trigger', 'select', {
                data: {
                    id: originWareID,
                    text: `{{$data->originwarehouses->origin_warehouse}}`
                }
            });
        }

        if (destSiteID) {
            $('#destination-unit').select2('trigger', 'select', {
                data: {
                    id: destSiteID,
                    text: `{{$data->destinationsites->destination_site}}`
                }
            });
        }

        if (destWareID) {
            $('#destination-warehouse').select2('trigger', 'select', {
                data: {
                    id: destWareID,
                    text: `{{$data->destinationwarehouses->destination_warehouse}}`
                }
            });
        }
    });
</script>
@endsection