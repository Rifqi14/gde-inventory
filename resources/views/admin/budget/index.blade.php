@extends('admin.layouts.app')

@section('title')
Budgetary Data
@endsection

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Budgetary Data
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Preferences</li>
            <li class="breadcrumb-item">Budgetary Data</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div id="chartTemplate" class="row">

            <!-- DPR RI -->
            <div class="col-lg-12">
                <div class="card dashboard-item-overview">
                    <div class="card-header border-0">
                        <h3 class="card-title val"></h3>
                    </div>
                    <div class="card-body" style="padding-top: 0px;">
                        <div class="row">
                            <div class="position-relative mb-12 col-md-12" id="parent-pieChart1">
                                <div id="stackChart"
                                    style="min-height: 318px; height: 415px; max-height: 415px; max-width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- END DPR RI -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="text-right">
                            <a class="btn btn-labeled btn-sm text-sm btn-default btn-flat legitRipple  float-right ml-1"
                                onclick="filter()">
                                <b><i class="fas fa-search"></i></b> Search
                            </a>
                            <a href="{{ route('budgetary.create') }}"
                                class="btn btn-labeled btn-sm text-sm btn-success btn-flat legitRipple  float-right ml-1">
                                <b><i class="fas fa-plus"></i></b> Create
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="card document-engineering elevation-0">
                            <div class="card-header p-0" style="background-color: #f4f6f9; border: none;">
                                <ul class="nav nav-tabs tabs-engineering" id="tabs-mom" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link {{($type=='dieng')?'active':''}}" id="budget-tab-dieng"
                                            data-toggle="pill" href="#tab-dieng" role="tab" aria-controls="tab-dieng"
                                            aria-selected="true" onClick="changeTab('dieng')">Dieng</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{($type=='patuha')?'active':''}}" id="budget-tab-patuha"
                                            data-toggle="pill" href="#tab-patuha" role="tab" aria-controls="tab-patuha"
                                            onClick="changeTab('patuha')">Patuha</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{($type=='ho')?'active':''}}" id="budget-tab-ho"
                                            data-toggle="pill" href="#tab-ho" role="tab" aria-controls="tab-ho"
                                            onClick="changeTab('ho')">Head Office</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <div class="tab-content" id="engineering-tab-tabContent">
                                <div class="tab-pane fade show active" id="tab-mechanical" role="tabpanel"
                                    aria-labelledby="engineering-tab-mechanical">
                                    <input name="type" type="hidden" value="{{$type}}">
                                    <table class="table table-striped ajaxTable" style="width: 100%" id="user-table">
                                        <thead>
                                            <tr>
                                                <th width="5%">No.</th>
                                                <th width="15%">Budget Name</th>
                                                <th width="20%">Budget Description</th>
                                                <th width="15%" class="text-right">Budget Amount</th>
                                                <th width="15%">Type</th>
                                                <th width="10%" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->

<div id="add-filter" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="filter-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Advance Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-search" method="POST">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="police_number">Budget Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Budget Name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="police_number">Budget Description</label>
                                    <input type="text" name="description" class="form-control"
                                        placeholder="Budget Description">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-labeled  btn-danger btn-sm btn-sm btn-flat legitRipple"
                    data-dismiss="modal"><b><i class="fas fa-times"></i></b> Cancel</button>
                <button type="submit" form="form-search"
                    class="btn btn-labeled  btn-default  btn-sm btn-flat legitRipple"><b><i
                            class="fas fa-search"></i></b> Search</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    let unit = $('input[name=type]').val()
    $(function() {
        stackChart(unit)
        $('.select2').select2();
		dataTable = $('#user-table').DataTable({
            processing: true,
            language: {
                processing: `<div class="p-2 text-center">
                            <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...
                            </div>`
            },
            serverSide: true,
            aaSorting: [],
            filter: false,
            responsive: true,
            lengthChange: false, 
            order: [[ 1, "asc" ]],
            ajax: {
                url: "{{route('budgetary.read')}}",
                type: "GET",
                data: function(data){
                    var type = $('input[name=type]').val();
                    var name = $('#form-search').find('input[name=name]').val();
                    var desc = $('#form-search').find('input[name=description]').val();
                    data.type = type
                    data.name = name
                    data.desc = desc
                    if(type =='dieng'){
                        var title = 'Dieng'
                    } else if(type =='patuha'){
                        var title = 'Patuha'
                    } else {
                        var title = 'Head Office'
                    }
                    $('.card-title').text(title)
                }
            },
            columns: [
                { "data": "no", "name": "no", width:10, className:"text-center" },
                {  
                    "data": "name", 
                    "name": "name",
                    width: 100,
                    sortable: false,
                    render: function (data, type, full, meta) {
                        return `<a href="{{url('admin/budgetary/show')}}/${full.id}">
                                    <div class="text-md text-info text-bold">
                                        ${full.name}
                                    </div>
                                </a>`;
                    }
                },
                { "data": "description", "name": "description", width:200, sortable:false },
                {  
                    width: 100,
                    sortable: false,
                    className:'text-right',
                    render: function (data, type, full, meta) {
                            if(full.currency == 'yen'){
                                var curr = "¥ "
                            } else if(full.currency == 'dollar'){
                                var curr = "$ "
                            } else if(full.currency == 'euro'){
                                var curr = "€ "
                            } else {
                                var curr = "Rp "
                            }

                            return accounting.formatMoney(full.amount, curr, 2, ".", ",");
                        }
                    },
                {  
                    width: 100,
                    sortable: false,
                    className:'text-center',
                    render: function (data, type, full, meta) {
                        var type=''
                        $.each(full.detail, function(index, item) {
                            var ind = ind + 1
                            if(item.type == 'kasinternal'){
                                type += `<text class="small">Kas Internal ${item.weight}%</text>`
                            } else if(item.type == 'budget'){
                                type += `<text class="small">Budget ${item.weight}%</text>`
                            } else {
                                type += `<text class="small">${(item.type).toUpperCase()} ${item.weight}%</text>`
                            }
                            if(ind != (full.detail).length){
                                type += `<br>`
                            }
                        });
                        return type
                    }
                },
                { 
                    width:30, 
                    className:"text-center", 
                    sortable: false,
                    render: function( data, type, full, meta ) {
                        return `<div class="btn-group">
                                    <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="edit(${full.id})">
                                            <i class="far fa-edit"></i>Update Data
                                        </a>
                                        <a class="dropdown-item " href="javascript:void(0);" onclick="destroy(${full.id})">
                                            <i class="far fa-trash-alt"></i> Delete Data
                                        </a>
                                    </div>
                                </div>`;
                    }
                }
            ]
        });
		$('#form-search').submit(function(e) {
			e.preventDefault();
			dataTable.draw();
			$('#add-filter').modal('hide');
		});

	});

    function edit(id)
	{
		window.location.href = `{{url('admin/budgetary/edit')}}/${id}`;
	}

    function changeTab(type){
        $('input[name="type"]').val(type);
        stackChart(type)
        dataTable.draw();
    }

    function stackChart(type){
        $.ajax({
        url: "{{route('budgetary.stack_chart')}}",
        method: 'get',
        dataType: 'json',
        data: {
            unit: type,
        },
        beforeSend:function() {
            blockMessage('#stackChart', 'Get Data . . .', '#fff');
        },
        success:function(response) {
            $('#stackChart').unblock();
            Highcharts.setOptions({
            lang: {
                thousandsSep: '.'
            }
            });
            var stackChart = Highcharts.chart('stackChart', {
                    chart: {
                        type: 'column'
                    },
                    title: false,
                    xAxis: {
                        categories: response.series.categories,
                        labels: {
                            rotation: -90
                        }
                    },
                    yAxis: {
                        type: 'logarithmic',
                        title: {
                            text: ''
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: ( // theme
                                    Highcharts.defaultOptions.title.style &&
                                    Highcharts.defaultOptions.title.style.color
                                ) || 'gray'
                            }
                        },
                        labels: {
                        formatter: function() {
                            if(this.value%1e12 == 0){
                            return this.value / 1e12 + 'T';
                            } else if(this.value%1e9 == 0){
                            return this.value / 1e9 + 'B';
                            } else {
                            return (this.value / 1e9).toFixed(2) + 'B';
                            }
                        }
                        }
                    },
                    legend: {
                        align: 'right',
                        x: -30,
                        verticalAlign: 'top',
                        y: 10,
                        floating: true,
                        backgroundColor:
                            Highcharts.defaultOptions.legend.backgroundColor || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        headerFormat: '<b>{point.x}</b><br/>',
                        pointFormat: `{series.name}: Rp {point.y}<br/>Total: Rp {point.stackTotal:,.2f}`
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            pointPadding: 0,
                            groupPadding: 0,
                            pointWidth : 40,
                            padding:5,
                            dataLabels: {
                                enabled: false
                            }
                        }
                    },
                    credit:false,
                    exporting:false,
                    series: response.series.series
            });
            $('text').find('tspan.highcharts-text-outline').css('display', 'none')
            $('text').find('tspan.highcharts-text-outline').next().css('display', 'none')
        }
        })
    }

	function filter(){
		$('#add-filter').modal('show');
	}

	function destroy(id)
	{
		Swal.fire({
			title: 'Hapus',
			text: "Apa Anda Yakin Akan Menghapus Data ?",
			icon: 'error',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Hapus!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.value) {
                var data = {
                    _token: "{{ csrf_token() }}"
                };
				$.ajax({
					url: `{{url('admin/budgetary/delete')}}/${id}`,
					dataType: 'json', 
					data:data,
					type:'GET',
					success:function(response){
						if(response.status){
                            dataTable.ajax.reload(null, false);
                            stackChart(unit)
						}
						else{
							Swal.fire(
								'Error!',
								'Data Gagal Di Hapus.',
								'error'
							)
						}
				}});
				
			}
		});
	}
</script>
@endsection