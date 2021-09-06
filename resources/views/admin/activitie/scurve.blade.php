@extends('admin.layouts.app')
@section('title',$menu_name)

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

@section('stylesheets')
<link rel="stylesheet" href="{{ asset('assets/plugins/collapsible-datagrid/css/jquery.treegrid.css') }}">
<style>
    .treegrid-container, .tabhead {
		width: max-content;
	}
	.maks {
		width: max-content;
	}
	.progred {
		background-color: #c9302c;
		color: #fff;
		border-color: #ac2925;
		cursor: pointer;
	}
	.linku {
		cursor: pointer;
	}
</style>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form class="custom-form-progress" action="{{ url('admin/activitie/scurve') }}/{{ $location }}" enctype="multipart/form-data" method="GET" >
                            <div class="row">
                                <div class="col-6">
                                    <label>Start Date:</label>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <select id="startYear" name="startYear" class="select2" required style="width: 100%;" onchange="getStartMonth(this)">
                                                    <option value="">Pick a Starting Year</option>
                                                    @foreach($ranges_year as $i)
                                                        <option value="{{ $i }}" {{ (@$get['startYear'] == $i)?'selected':''  }} > {{ $i }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="form-group">
                                                <input type="hidden" name="query1" value="1" >
                                                <input type="hidden" name="act" value="{{ @$get['act'] }}">
                                                <select id="startMonth" name="startMonth" class="select2" required style="width: 100%;" disabled onchange="getFinishMonth(this)">
                                                    <option value="">Pick a Starting Month</option>
                                                    @for($i=1;$i<=12;$i++)
                                                    <option value="{{ sprintf("%02d", $i) }}" {{ (@$get['startMonth'] == $i)?'selected':'' }} > {{ getFullMonth($i) }} </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label>Finish Date:</label>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <select id="finishYear" name="finishYear" class="select2" required style="width: 100%;" onchange="getFinishMonth(this)">
                                                    <option value="">Pick a Finished Year</option>
                                                    @foreach($ranges_year as $i)
                                                    <option value="{{ $i }}" {{ (@$get['finishYear'] == $i)?'selected':''  }} > {{ $i }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="form-group">
                                                <select id="finishMonth" name="finishMonth" class="select2" required style="width: 100%;" disabled>
                                                    <option value="">Pick a Finished Month</option>
                                                    @for($i=1;$i<=12;$i++)
                                                    <option value="{{ sprintf("%02d", $i) }}" {{ (@$get['finishMonth'] == $i)?'selected':'' }} > {{ getFullMonth($i) }} </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($filter)
                                <button type="button" class="btn bg-yellow color-platte btn-labeled legitRipple text-sm" data-toggle="modal" data-target="#form-filter">
                                <b><i class="fas fa-search"></i></b>
                                Activities
                                </button>
                                @endif
                                <button type="submit" class="btn bg-olive color-platte btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-save"></i></b>
                                Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @if($filter)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">S-Curve {{ ucwords($location) }}</h3>
                        <div class="text-right">
                            <button type="button" class="btn btn-labeled btn-md text-sm bg-maroon color-pallete btn-flat legitRipple" onclick="changeTable(this)" type-tab="expand">
                            <b><i class="fas fa-plus"></i></b>Expand All
                            </button>
                            <button type="button" data-toggle="modal" id="scurve" data-target="#scurve-modal" class="btn btn-labeled btn-md text-sm btn-success btn-flat legitRipple">
                            <b><i class="fas fa-chart-line"></i></b>S-Curve
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card document-engineering elevation-0">
                            <div class="card-header p-0" style="background-color: #f4f6f9; border: none;">
                                <ul class="nav nav-tabs tabs-engineering" id="tabs-mom" role="tablist">
                                    @if($type)
                                        @foreach ($type as $typ)
                                        <li class="nav-item">
                                            <a class="nav-link {{ ($typ->type=='actual')?'active' :'' }}" id="scurve-tab-{{ seo($typ->type) }}" data-toggle="pill" href="#tab-{{ seo($typ->type) }}" role="tab" aria-controls="tab-{{ seo($typ->type) }}" data-type="{{ ucwords($typ->type) }}">{{ ucwords($typ->type) }}</a>
                                        </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <div class="tab-content" id="tabs-mom-tabContent">
                                @if($type)
                                    @foreach ($type as $typ)
                                    <div class="tab-pane fade {{ ($typ->type=='actual')?'show active' :'' }}" id="tab-{{ seo($typ->type) }}" role="tabpanel" aria-labelledby="scurve-tab-{{ seo($typ->type) }}">
                                        <table class="table table-striped tree table-bordered table-actual" id="table-{{ seo($typ->type) }}">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="vertical-align: middle;" >Activities</th>
                                                    @if($typ->type=='actual')
                                                    <th rowspan="2" style="vertical-align: middle; text-align: center;">
                                                        <div class="tabhead">Start Date</div>
                                                    </th>
                                                    <th rowspan="2" style="vertical-align: middle; text-align: center;">
                                                        <div class="tabhead">Last Update</div>
                                                    </th>
                                                    <th rowspan="2" style="vertical-align: middle; text-align: center;">Current Progress</th>
                                                    <th rowspan="2"	 style="vertical-align: middle; text-align: center;">Action</th>
                                                    @else
                                                    <th rowspan="2" style="vertical-align: middle; text-align: center;"><div class="tabhead">Start Date</div></th>
                                                    <th rowspan="2" style="vertical-align: middle; text-align: center;"><div class="tabhead">Finish Date</div></th>
                                                    <th rowspan="2" style="vertical-align: middle; text-align: center;">Current Progress</th>
                                                    @endif 
                                                    @foreach($ranges as $range)
                                                    @php $pln = explode('/', $range); @endphp
                                                    <th id="{{ seo($typ->type) }}-{{ $pln[0] }}-{{ $pln[1] }}" style="text-align: center;"></th>
                                                    @endforeach
                                                </tr>                 
                                                <tr>
                                                    @foreach($ranges as $range)
                                                    <th style="text-align: center;">{{ $range }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($plan as $key => $val)
                                    	        @if($val->type==$typ->type)
                                                <tr id="act-{{ $val->id }}" 
                                                class="{{ ($val->child > 0) ? 'treegrid-'.$val->id:'treegrid' }} {{ ($val->parent_id) ? 'treegrid-parent-'.$val->parent_id:'' }} {{ ($key == 0) ? 'expanded':'' }}" 
                                                data-parent="{{ $val->parent_id }}" 
                                                data-child="{{ $val->child }}">
                                                    <td>
                                                        @if($val->parent_id)
                                                        {{ $val->activity }}
                                                        @else
                                                        <b>{{ $val->activity }}</b>
                                                        @endif
                                                    </td>
                                                    @if($val->type=='actual')
                                                    <td class="text-center">
                                                        <div class="maks">
                                                            {{ ($val->start_update)?$val->start_update:$val->start_date }}
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="maks">
                                                            {{ $val->last_update }}
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ $val->current }}</td>
                                                    <td class="text-center">
                                                        @if($val->child == 0)
                                                        <button type="button" class="btn btn-xs btn-success btn-flat legitRipple" data-id="{{ $val->id }}" data-path='{{ $val->path }}' data-type="actual" onclick="updateProgress(this)" >
                                                            Update
                                                        </button>
                                                        @endif
                                                    </td>
                                                    @else
                                                    <td class="text-center"><div class="maks">{{ $val->start_date }}</div></td>
                                                    <td class="text-center"><div class="maks">{{ $val->finish_date }}</div></td>
                                                    <td class="text-center">{{ $val->current }}</td>
                                                    @endif
                                                    @foreach($val->details as $detail)
                                                    <td data-id="{{ $detail['id'] }}" onclick="{{ ($val->type=='actual')?(($detail['progress']) ? 'viewProgress(this)':''):'' }}" class="text-center {{ ($detail['progress']) ? 'progred':'' }}" data-file='{{ $detail['file'] }}' data-date="{{ $detail['fulldate'] }}" data-progress="{{ $detail['progress'] }}" >{{ $detail['progress'] }}</td>
                                                    @endforeach
                                                </tr>
                                                @endif 
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endforeach
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="form-progress">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Progress</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-data-update" class="custom-form-progress" action="{{ url('admin/schedule/update') }}" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Updated By:</label>
                        <input type="text" class="form-control" value="{{ $real_name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Activity :</label>
                        <input type="hidden" name="activities_id" />
                        <input type="hidden" name="type" />
                        <div id="parent-act" >
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Date :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="date" class="datepicker2 form-control text-right" placeholder="Date" autocomplete="off" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Progress :</label>
                                <div class="input-group">
                                    <input type="text" name="progress" class="form-control" placeholder="Enter progress..." autocomplete="off" value="0"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text text-bold text-sm">
                                        %
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" class="form control summernote" ></textarea>
                    </div>
                    <div id="form-file">
                        <div class="row mt-3 item-file">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Attachment:</label>
                                    <div class="input-group">
                                        <div class="custom-file">   
                                            <input type="file" class="custom-file-input" name="attachment[]" onchange="changePath(this)">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-1">
                        <button id="add-file" data-urutan="1" type="button" class="btn btn-success btn-labeled legitRipple text-sm">
                        <b><i class="fas fa-plus"></i></b>
                        Add
                        </button>
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

<div class="modal fade" id="form-view">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Progress</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-8">
                        <div class="form-group" style="margin-bottom:0px;">
                            <label id="lu">Date : </label>
                            <text></text>
                        </div>
                        <div class="form-group" style="margin-bottom:0px;">
                            <label id="vprog">Progress : </label>
                            <text></text>
                        </div>
                    </div>
                    <div class="col-4">
                        <button id="editcl" onclick="editProgress(this)" type="button" class="btn btn-labeled btn-sm text-sm bg-success btn-flat legitRipple btn-block">
                        <b><i class="fas fa-edit"></i></b>Edit
                        </button>
                        <button id="progcl" onclick="clearProgress(this)" type="button" class="btn btn-labeled btn-sm text-sm bg-maroon btn-flat legitRipple btn-block">
                        <b><i class="fas fa-trash"></i></b>Clear
                        </button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <table id="listDl" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="scurve-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">S-Curve</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <figure class="highcharts-figure">
                    <div id="splineChart"></div>
                </figure>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeScurve()" class="btn btn-secondary color-platte btn-labeled legitRipple text-sm btn-send">
                <b><i class="fas fa-times"></i></b>
                Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="form-edit">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Progress</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-data-edit" class="custom-form-progress" action="{{ url('admin/schedule/edit_progress') }}" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Updated By:</label>
                        <input id="upt_name" type="text" class="form-control" value="{{ $real_name }}" readonly>
                    </div>
                    <input type="hidden" name="id" />
                    <input type="hidden" name="type" />
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Date :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="date" class="datepicker2 form-control text-right" placeholder="Date" autocomplete="off" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Progress :</label>
                                <div class="input-group">
                                    <input type="text" name="progress" class="form-control" placeholder="Enter progress..." autocomplete="off" value="0"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text text-bold text-sm">
                                        %
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" class="form control edit-txt" ></textarea>
                    </div>
                    <div id="form-editfile">
                        <div class="row mt-3 item-editfile">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Attachment:</label>
                                    <div class="input-group">
                                        <div class="custom-file">   
                                            <input type="file" class="custom-file-input" name="attachment[]" onchange="changePath(this)">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-1">
                        <button id="add-editfile" data-urutan="1" type="button" class="btn btn-success btn-labeled legitRipple text-sm">
                        <b><i class="fas fa-plus"></i></b>
                        Add
                        </button>
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

<div class="modal fade" id="form-filter">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Activities</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-act" action="{{ url('admin/activitie/scurve') }}/{{ $location }}" enctype="multipart/form-data" method="GET">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Filter :</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="startYear" value="{{ @$get['startYear'] }}">
                            <input type="hidden" name="query1" value="1" >
                            <input type="hidden" name="startMonth" value="{{ @$get['startMonth'] }}">
                            <input type="hidden" name="finishYear" value="{{ @$get['finishYear'] }}">
                            <input type="hidden" name="finishMonth" value="{{ @$get['finishMonth'] }}">
                            <select name="act" class="select2"  style="width: 100%;">
                                <option value="">-- All Activities</option>
                                @foreach($act_parent as $act)
                                <option value="{{ $act->activity }}" {{ (strtolower($act->activity)==strtolower(@$get['act']))?'selected':'' }} >{{ $act->activity }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn bg-olive color-platte btn-labeled legitRipple text-sm">
                        <b><i class="fas fa-save"></i></b>
                        Submit
                        </button>
                        <button type="button" data-dismiss="modal" class="btn bg-secondary color-platte btn-labeled legitRipple text-sm">
                        <b><i class="fas fa-ban"></i></b>
                        Cancel
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
<script src="{{ asset('assets/plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('assets/plugins/treegrid/bootstrap-table-treegrid.min.js') }}"></script>
<script src="{{ asset('assets/plugins/fixed-columns/bootstrap-table-fixed-columns.js') }}"></script>
<script src="{{ asset('assets/plugins/collapsible-datagrid/js/jquery.treegrid.js') }}"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js'></script>
<script type="text/javascript">

	$('#form-data-create').submit(function(e) {
		e.preventDefault();
		$.ajax({
			url: $(this).attr('action'),
			method: 'post',
			data: new FormData($('#form-data-create')[0]),
			processData: false,
			contentType: false,
			dataType: 'json',
			beforeSend:function(){
				blockMessage('#form-create', 'Please Wait . . . ', '#fff');
			}
		}).done(function(response) {
			$('#form-create').unblock();
			location.reload()
			return;
		}).fail(function(response) {
			$('#form-create').unblock();
			location.reload()
			return;
		});
	});
	$('#form-data-update').submit(function(e) {
		e.preventDefault();
		let actid = $('input[name="activities_id"]').val()
		let progress = $('input[name="progress"]').val()
		$.ajax({
			url: $(this).attr('action'),
			method: 'post',
			data: new FormData($('#form-data-update')[0]),
			processData: false,
			contentType: false,
			dataType: 'json',
			beforeSend:function(){
				blockMessage('#form-progress', 'Please Wait . . . ', '#fff');
			}
		}).done(function(response) {
			$('#form-progress').unblock();
			$('.summernote').summernote('reset')
			location.reload()			
			return;
		}).fail(function(response) {
			$('#form-progress').unblock();
			$('.summernote').summernote('reset')
			location.reload()
			return;
		});
	});
	function closeScurve() {
		$('#scurve-modal').modal('hide');
	}

	$(function() {
        if($('.tree').length){
            $('.tree').treegrid();
        }
		let startMonth = '{{ @$get['startMonth'] }}',
			startYear = '{{ @$get['startYear']  }}',
			finishMonth = '{{ @$get['finishMonth'] }}',
			finishYear = '{{ @$get['finishYear'] }}',
			acti = '{{ @$get['act'] }}'

		$.ajax({
            url: "{{ route('scurve.chart') }}",
            method: 'post',
            dataType: 'json',
            data: {
                _token: "{{ csrf_token() }}",
                location: '{{ $location }}',
				startMonth: startMonth,
				startYear: startYear,
				finishMonth: finishMonth,
				finishYear: finishYear,
				act: acti,
            },
            beforeSend:function() {
                blockMessage('#splineChart', 'Get Data . . .', '#fff');
            },
            success:function(response) {
				$('#splineChart').unblock();
				// console.log(response)
				var type = response.series.type
				var activities = []
				$.each(type, function(key, val){
					let act = {
						name: (val.type).toUpperCase(),
						data: response.series[`${val.type}_line`]
					}
					activities.push(act)
				})

				Highcharts.chart('splineChart', {
					chart: {
						type: 'spline'
					},
					title: {
						text: 'S-Curve'
					},		    
					xAxis: {
						categories: response.series.categories
					},
					yAxis: {
						title: {
							text: '% Proggres'
						},
						labels: {
							format: '{value} %'
						},
						accessibility: {
							rangeDescription: 'Range: 0 to 100 %.'
						},
						maxPadding: 0.05,
						showLastLabel: true,
						max: 100

					},
					tooltip: {
						crosshairs: true,
						shared: true,			                
						pointFormat: '{series.name} : {point.y}%<br>'

					},
					plotOptions: {
						spline: {
							marker: {
								radius: 4,
								lineColor: '#666666',
								lineWidth: 1
							}
						}
					},
					credits: {
						enabled: false
					},
					series: activities,
					// series: [
					// 	{
					// 		name: 'Plan',
					// 		data: response.series.plan_line
					// 	},
					// 	{
					// 		name: 'Actual',
					// 		data: response.series.actual_line

					// 	}
					// ],
				});

				// let actual = response.series.actual
				// actual.forEach((val, key)=>{
				// 	let date = (val.date).split('/')
				// 	$(`#actual-${date[0]}-${date[1]}`).html(`${val.past}%<br>${val.now}%`)
				// })

				// let plan = response.series.plan
				// plan.forEach((val, key)=>{
				// 	let date = (val.date).split('/')
				// 	$(`#plan-${date[0]}-${date[1]}`).html(`${val.past}%<br>${val.now}%`)
				// })
				$.each(type, function(kuy, vul){
					let plan = response.series[vul.type]
					plan.forEach((val, key)=>{
						let date = (val.date).split('/')
						$(`#${vul.type}-${date[0]}-${date[1]}`).html(`${val.past}%<br>${val.now}%`)
					})
				})
            },
            error:function(response) {
				console.log('failed')
            }
        });

		$('.select2').select2();

		$('.datepicker2').daterangepicker({
			singleDatePicker: true,
			timePicker: false,
			locale: {
				format: 'DD/MM/YYYY'
			}
		});

		summernote('.summernote')

		getStartMonth(this)
		getFinishMonth(this)

		$('#form-edit').on('hidden.bs.modal', function (e) {
			$('#form-data-edit').find('input, textarea').val('')
			$('.edit-txt').summernote('reset');
		})

	});

	function updateProgress(that) {
		let id = $(that).data('id')
		let type = $(that).data('type')
		let path = $(that).data('path')
			path = path.split('->')
		
		let parent = ''
		path.forEach((val, index) => {
			let act = val.trim()
			parent += `<input type="text"  class="form-control" autocomplete="off" value="${act}" readonly style="margin-bottom:10px;">`
		});
		$('input[name="activities_id"]').val(id)
		$('input[name="type"]').val(type)
		$('#parent-act').html(parent)

		$('#form-progress').modal('toggle')
	}

	function viewProgress(that) {
		let id = $(that).data('id')	
		let date = $(that).data('date')
		let progress = $(that).data('progress')
		let file = $(that).data('file')
			file = JSON.stringify(file)	
			file = JSON.parse(file)	
			let option = ''
			$.each(file, function( index, value ) {
				let name = value.split('/')
				if(name[name.length-1] != "."){
					option += `<tr>
									<td>
										${name[name.length-1]}
									</td>
									<td>
										<div class="input-group-append linku">
											<a class="btn btn-sm text-bold text-sm bg-olive" target="_blank" href="{{ url('/') }}${value}" download>
												<i class="fas fa-download"></i>
											</a>
										</div>
									</td>		
								</tr>`
				}
			});
			$('#listDl').find('tbody').html(option)
			$('#lu').next().html(date)
			$('#vprog').next().html(progress)

		$('#progcl').attr('data-id', id)
		$('#editcl').attr('detil-id', id)
		$('#form-view').modal('toggle')
	}

	function clearProgress(that) {
		let id = $(that).data('id')	
		$.post(`{{ url('/') }}admin/schedule/delete/{{ $location }}/${id}`, function( data ) {
			location.reload();
		});
	}

	$('#add-file').on('click', function(e) {
		e.preventDefault();
		var no = $(this).data('urutan') + 1,
			html = `<div class="row item-file" id="item-${no}">
					<div class="col-md-11">
						<div class="form-group"> 
							<div class="input-group">
								<div class="custom-file">   
									<input type="file" class="custom-file-input" name="attachment[]" onchange="changePath(this)" >
									<label class="custom-file-label" for="exampleInputFile">Choose file</label>
								</div>
								<div class="input-group-append">
									<span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-1">
						<button type="button" class="btn btn-transparent text-md" onclick="removeFile(this)" data-urutan=${no}><i class="fas fa-trash text-maroon color-palette"></i></button>
					</div>
					</div>`;

		if (no > 2) {
		$('#item-'+(no-1)).find('.col-md-1').hide();
		$('#item-'+(no-1)).find('.col-md-11').removeClass('col-md-11').addClass('col-md-12');
		}
		$(this).data('urutan', no);
		$('#form-file').append(html);
	});

	let removeFile = (me) => {
		var no = $('#add-file').data('urutan');

		if (no == $('.item-file').length) {
		$('#item-'+(no-1)).find('.col-md-1').show();
		$('#item-'+(no-1)).find('.col-md-12').removeClass('col-md-12').addClass('col-md-11');
		$('#add-file').data('urutan', (no-1));
		$(me).parent().parent().remove();
		}
	}

	function changePath(that) {
		let filename = $(that).val()	
		$(that).next().html(filename)
	}

	function getStartMonth(that){
		let year = $('select[name=startYear]').val()
		let range = '{!! json_encode($ranges_data) !!}'
			range = JSON.parse(range)
		if(year){
			$('select[name=startMonth]').removeAttr('disabled')
		} else {
			$('select[name=startMonth]').attr('disabled','disabled')
		}
		$('#finishYear').find(`option`).removeAttr('disabled')
		if(year){
			let yeard = $("#finishYear>option").map(function() { if($(this).val()){return $(this).val();}  }).get();
			yeard.forEach((val,key)=>{
				if(val < year){
					$('#finishYear').find(`option[value=${val}]`).attr('disabled','disabled')
				}
			})
		}
		
		$('select[name=startMonth]').find('option').attr('disabled', 'disabled')
		range.forEach((val,key)=>{
			let sp = val.split('/')
			if(sp[0] == year){
				$('select[name=startMonth]').find(`option[value=${sp[1]}]`).removeAttr('disabled')
			}
		})

	}

	function getFinishMonth(that){
		let months = $('select[name=startMonth]').val()
		let years = $('select[name=startYear]').val()
		let year = $('select[name=finishYear]').val()
		let range = '{!! json_encode($ranges_data) !!}'
			range = JSON.parse(range)
		if(year){
			$('select[name=finishMonth]').removeAttr('disabled')
		} else {
			$('select[name=finishMonth]').attr('disabled','disabled')
		}
		$('select[name=finishMonth]').find('option').attr('disabled', 'disabled')
		range.forEach((val,key)=>{
			let sp = val.split('/')
			if(years == year && sp[1] > months){
				$('select[name=finishMonth]').find(`option[value=${sp[1]}]`).removeAttr('disabled')
			}
			else if(years < year && sp[0] == year){
				$('select[name=finishMonth]').find(`option[value=${sp[1]}]`).removeAttr('disabled')
			}
		})
	}

	function changeTable(that){
		let type = $(that).attr('type-tab')
		let tr_actual = $('.table-actual > tbody').find('tr')

		if(type == 'expand'){
			$(that).attr('type-tab', 'hide')
			$(that).html('<b><i class="fas fa-minus"></i></b>Hide All')
		} else {
			$(that).attr('type-tab', 'expand')
			$(that).html('<b><i class="fas fa-plus"></i></b>Expand All')
		}

		$.each(tr_actual, function(key, val){
			let id = $(this).attr('id')
			let ortu = $(this).data('parent')
			let anak = $(this).data('child')

			if(anak > 0){
				if(type == 'expand'){
					$(this).removeClass('expanded').addClass('expanded')
					$(this).removeAttr('style')
					$(this).find('span').removeClass("treegrid-expander-expanded treegrid-expander-collapsed").addClass('treegrid-expander-expanded')
				} else {
					$(this).removeClass('expanded')
					$(this).find('span').removeClass("treegrid-expander-expanded treegrid-expander-collapsed").addClass('treegrid-expander-collapsed')
				}
			}

			if(ortu > 0){
				if(type == 'expand'){
					$(this).removeClass('expanded').addClass('expanded')
					$(this).removeAttr('style')
				} else {
					$(this).removeClass('expanded')
					$(this).css('display', "none")
				}
			}
		})

	}

	function editProgress(that){
		let id = $(that).attr('detil-id')

		$.ajax({
			url: '{{ route("scurve.get_progress") }}',
			method: 'post',
			data: {id:id,_token: "{{ csrf_token() }}"},
			dataType: 'json',
			beforeSend:function(){
				blockMessage('#form-view', 'Please Wait . . . ', '#fff');
			}
		}).done(function(response) {
			$('#form-view').unblock();
			let data = response.data
			console.log(data)

			$('#form-edit').find('#upt_name').val('{{ $real_name }}')
			$('#form-edit').find('input[name="id"]').val(data.id)
			$('#form-edit').find('input[name="type"]').val(data.type)
			$('#form-edit').find('input[name="date"]').val(data.date)
			$('#form-edit').find('input[name="progress"]').val(data.progress)
			$('#form-edit').modal('toggle')
			summernote('.edit-txt')
			$('.edit-txt').summernote('code', data.description);
			
			return;
		}).fail(function(response) {
			$('#form-view').unblock();

			return;
		});

	}

	$('#form-data-edit').submit(function(e) {
		e.preventDefault();
		
		$.ajax({
			url: $(this).attr('action'),
			method: 'post',
			data: new FormData($('#form-data-edit')[0]),
			processData: false,
			contentType: false,
			dataType: 'json',
			beforeSend:function(){
				blockMessage('#form-edit', 'Please Wait . . . ', '#fff');
			}
		}).done(function(response) {
			$('#form-edit').unblock();
			$('.edit-txt').summernote('reset')
			location.reload()
			
			return;
		}).fail(function(response) {
			$('#form-edit').unblock();
			$('.edit-txt').summernote('reset')
			location.reload()
			
			return;
		});
	});

	$('#add-editfile').on('click', function(e) {
		e.preventDefault();
		var no = $(this).data('urutan') + 1,
			html = `<div class="row item-editfile" id="item-edit-${no}">
					<div class="col-md-11">
						<div class="form-group"> 
							<div class="input-group">
								<div class="custom-file">   
									<input type="file" class="custom-file-input" name="attachment[]" onchange="changePath(this)" >
									<label class="custom-file-label" for="exampleInputFile">Choose file</label>
								</div>
								<div class="input-group-append">
									<span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-1">
						<button type="button" class="btn btn-transparent text-md" onclick="removeEditFile(this)" data-urutan=${no}><i class="fas fa-trash text-maroon color-palette"></i></button>
					</div>
					</div>`;

		if (no > 2) {
		$('#item-edit-'+(no-1)).find('.col-md-1').hide();
		$('#item-edit-'+(no-1)).find('.col-md-11').removeClass('col-md-11').addClass('col-md-12');
		}
		$(this).data('urutan', no);
		$('#form-editfile').append(html);
	});

	let removeEditFile = (me) => {
		var no = $('#add-editfile').data('urutan');

		if (no == $('.item-editfile').length) {
		$('#item-edit-'+(no-1)).find('.col-md-1').show();
		$('#item-edit-'+(no-1)).find('.col-md-12').removeClass('col-md-12').addClass('col-md-11');
		$('#add-editfile').data('urutan', (no-1));
		$(me).parent().parent().remove();
		}
	}

	function summernote(cls){
		$(cls).summernote({
			height:150,
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

</script>
@endsection