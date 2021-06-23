@extends('admin.layouts.app')
@section('title','Request Vehicle')

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Request Vehicle
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">Request Vehicle</li>
            <li class="breadcrumb-item">Detail</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form class="form-horizontal no-margin" action="{{route('requestvehicle.update', ['id' => $data->id])}}" id="form">
                        {{ csrf_field() }}
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <span class="title">
                                        <hr>
                                        <h5 class="text-dark text-bold text-md">Request Information</h5>
                                    </span>
                                    <div class="row mt-4">
                                        <!-- Vehicle -->
                                        <div class="form-group col-6">
                                            <label class="control-label" for="vehicle">Vehicle</label>
                                            <select class="form-control" name="vehicle" id="vehicle" data-placeholder="Vehicle" disabled></select>
                                        </div>
                                        <!-- Borrower -->
                                        <div class="form-group col-6">
                                            <label class="control-label" for="borrower">Borrower</label>
                                            <input type="text" class="form-control" value="{{$data->employee_name?$data->employee_name:$data->user_name}}" readonly>
                                            <input type="hidden" name="issued_by" value="{{$data->issued_by}}">
                                        </div>
                                        <!-- Date Request -->
                                        <div class="form-group col-6">
                                            <label class="control-label" for="date-borrowed">Date</label>
                                            <input class="form-control datepicker" type="datepicker" name="date_borrowed" id="date-borrowed" placeholder="Start Date - Finish Date" autocomplete="off" readonly>
                                        </div>
                                        <!-- Status -->
                                        <div class="form-group col-6">
                                            <label class="control-label" for="status-request">Status</label>
                                            <select class="form-control select2" name="status" id="status-request" disabled>
                                                <option value="1" @if ($data->status == 1) selected @endif>Waiting</option>
                                                <option value="2" @if ($data->status == 2) selected @endif>Approved</option>
                                                <option value="3" @if ($data->status == 3) selected @endif>Completed</option>
                                                <option value="4" @if ($data->status == 4) selected @endif>Rejected</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <span class="title">
                                        <hr>
                                        <h5 class="text-dark text-bold text-md">Other</h5>
                                    </span>
                                    <!-- Notes -->
                                    <div class="form-group">
                                        <label class="control-label" for="notes">Notes</label>
                                        <textarea class="form-control summernote" name="notes" id="notes" rows="4" placeholder="Notes" disabled>{{$data->remarks}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('requestvehicle.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-times"></i></b>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
        var startDate = '{{$data->start_request}}',
            endDate    = '{{$data->finish_request}}';
        $('.select2').select2({
            allowClear: true
        });        

        $('.datepicker').daterangepicker({
            timePicker: false,
            timePickerIncrement: 30,
            drops: 'auto',
            opens: 'center',
            locale: {
                format: 'DD/MM/YYYY'
            },
            startDate: startDate?startDate:moment(new Date()),
            endDate: endDate?endDate:moment(new Date()).add(6, 'days')
        });

        $('.summernote').summernote({
            height: 150,
            toolbar:[]
        });
        $('.summernote').summernote('disable');

        $("#vehicle").select2({
            ajax: {
                url: "{{route('vehicle.select')}}",
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
                            text: `${item.vehicle_name} | ${item.police_number}`,
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            allowClear: true,
        }).on('select2:select', function(e){
            var data  = e.params.data,
                date  = $('#form').find('input[name=date_borrowed]').data('daterangepicker'),
                startDate = date.startDate.format('DD/MM/YYYY'),
                endDate   = date.endDate.format('DD/MM/YYYY');
                checkDateRequest(data.id);          
        });

        $("#borrower").select2({
            ajax: {
                url: "{{route('employee.select')}}",
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
                            text: item.name,
                        });
                    });
                    return {
                        results: option,
                        more: more,
                    };
                },
            },
            multiple: true,
            allowClear: true,
        });
        
        $('#date-borrowed').on('apply.daterangepicker',function(ev,picker){
            var vehicle_id = $('select[name=vehicle]').select2('val');
            if(vehicle_id){
                checkDateRequest(vehicle_id);
            }
        });
        

        @if($data->borrowers)        
            var borrowers    = @json($data->borrowers);                                                           
            $.each(borrowers, function (index, val) {                  
                $('#borrower').select2('trigger','select',{
                    data : {
                        id : val.employee_id,
                        text : `${val.name}`
                    }
                });
            });            
        @endif        

        @if($data->vehicle_id)
            $('#vehicle').select2('trigger','select',{
                data : {
                    id : {{$data->vehicle_id}},
                    text : `{{$data->vehicle_name}} | {{$data->police_number}}`
                }
            });
        @endif       
    });
    
</script>
@endsection