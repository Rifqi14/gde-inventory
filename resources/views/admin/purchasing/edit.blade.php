@extends('admin.layouts.app')
@section('title', 'Purchasing')
@section('stylesheets')
<style>
    .hidden {
      display: none;
    }
    .showed {
      display: block;
    }
    .choosed {
      display: flex;
    }
</style>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            Edit Purchasing
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Procurment</li>
            <li class="breadcrumb-item">Purchasing</li>
            <li class="breadcrumb-item">Edit</li> 
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <form role="form" id="form" action="{{route('purchasing.update',['id'=>$purchasing->id])}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="put">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr/>
                                <h5 class="text-md text-dark text-bold">Procurement Information</h5>
                            </span>
                            <div class="form-group mt-4">
                                <label for="complainant">Number:</label>
                                <input type="text" name="number" placeholder="Procurement Number..." class="form-control" autocomplete="false" required="true" value="{{ $purchasing->number }}"/>
                            </div>
                            <div class="form-group mt-4">
                                <label for="complainant">Subject:</label>
                                <input type="text" name="subject" class="form-control" placeholder="Procurement Subject..." value="{{ $purchasing->subject }}">
                            </div>
                            <div class="form-group mt-3">
                                <div class="row">
                                    <div class="col-4">
                                        <label>Rule:</label>
                                        <input type="text" class="form-control" value="{{ strtoupper($purchasing->rule) }}" readonly>
                                    </div>
                                    <div class="col-8">
                                        <label>Estimated Value:</label>
                                        <div class="row">
                                            <div class="col-3">
                                                <select name="est_currency" class="form-control" required>
                                                    <option value="">Choose a Currency</option>
                                                    @foreach(config('enums.currency') as $key => $currency)
                                                        <option value="{{ $key }}" @if($purchasing->est_currency==$key) selected @endif>{{ $currency }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-9">
                                                <input type="text" name="est_value" class="form-control input-price" placeholder="Estimated Value" value="{{ number_format($purchasing->est_value,'0',',','.') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label">User:</label>
                                <div class="col-8">
                                <select name="user[]" id="" class="form-control select2" multiple="multiple" data-placeholder="Tag User Group">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" @if(in_array($role->id, $purchasing->puser)) selected @endif >{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label">Portion:</label>
                                <div class="col-8">
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Technical:</label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-price" name="technical" placeholder="Technical Portion..." autocomplete="off" required onkeyup="getPortion(this)" value="{{ $purchasing->technical }}"/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Financial:</label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="financial" placeholder="Financial Portion..." autocomplete="off" required readonly value="{{ $purchasing->financial }}"/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div id="choose-adb" class="form-group row @if($purchasing->rule!='adb') hidden @endif">
                                        <label class="col-4 col-form-label">Choose:</label>
                                        <div class="col-8">
                                            <select name="choose_adb" id="choose_adb" class="form-control select2" onchange="chooseSchedule(this)" disabled>
                                                <option value="">Tag ADB Guideline</option>
                                                @foreach(config('enums.adb_guideline') as $key => $adb_guideline)
                                                    <option value="{{ $key }}" @if($purchasing->adb == $key) selected @endif >{{ $adb_guideline }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="schedule-adb" class="form-group">
                                        <label>Schedule:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <ul class="todo-list" data-widget="todo-list" id="input-list-checkbox">
                                                    @foreach($purchasing->step as $val)
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <span class="text"><?php echo ($purchasing->rule == 'adb') ? $val->schedule_name:$val->schedule ?></span>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="input-group mt-1">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">
                                                                                <i class="far fa-calendar-alt"></i>
                                                                            </span>
                                                                        </div>
                                                                        <input type="text" class="form-control date text-right" value="<?php echo date('d/m/Y', strtotime($val->date)) ?>" readonly >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
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
                                <hr/>
                                <h5 class="text-md text-dark text-bold"></h5>
                            </span>
                            <div class="form-group mt-4">
                                <label>Budget:</label>
                                <div id="form-budget">
                                    @php $num = 0; @endphp
                                    @foreach ($purchasing->budget as $key => $bud)
                                        @php $num++; @endphp
                                        <div class="row item-budget @if($num>1) mt-1 @endif" @if($num>1) id="rbudget-{{ $num }}" @endif >
                                            <div class="col-md-6">
                                                <select type="text" class="select2 form-control" name="budget_id[]" data-placeholder="Tag Budget..." >
                                                    @foreach($budgets as $budget)
                                                        <option value="{{ $budget->id }}" @if($budget->id==$bud->budget_id) selected @endif >{{ $budget->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="budget_val[]" class="form-control input-price" placeholder="Enter Budget Value" value="{{ number_format($bud->value,'0',',','.') }}">
                                            </div>
                                            <div class="col-md-2" @if($num!=count($purchasing->budget)) @if($num!=1) style="display:none;" @endif @endif >
                                                @if($num == 1)
                                                    <button id="add-budget" data-urutan="@if(count($purchasing->budget)>0){{count($purchasing->budget)}}@else 1 @endif" type="button" class="btn btn-success legitRipple text-sm">
                                                        <b><i class="fas fa-plus"></i></b>
                                                    </button>
                                                @endif
                                                @if($num > 1)
                                                    <button type="button" class="btn btn-transparent text-md" onclick="removeBudget(this)" data-urutan="{{ $num }}"><i class="fas fa-trash text-maroon color-palette"></i></button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label>TOR:</label>
                                <div class="input-group">
                                    <div class="custom-file">   
                                        <input type="file" class="custom-file-input" name="tor" accept="image/*" onchange="changePath(this)">
                                        <label class="custom-file-label" for="exampleInputFile">Attach TOR</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Procurement Duration Target:</label>
                                <div class="input-group">
                                    <input id="sum_days" type="text" class="form-control text-right" name="duration" placeholder="Enter Duration Target..." autocomplete="off" readonly value="{{ $purchasing->duration }}"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text">days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm">
                            <b><i class="fas fa-check-circle"></i></b>
                            Save
                        </button>
                        <a href="{{ route('purchasing.index') }}" class="btn bg-maroon color-palette legitRipple text-sm" >
                            <b><i class="fas fa-arrow-left"></i></b>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
    let removeBudget = (me) => {
		var no = $('#add-budget').data('urutan');

		if (no == $('.item-budget').length) {
            $('#rbudget-'+(no-1)).find('.col-md-2').show();
            // $('#rbudget-'+(no-1)).find('.col-md-10').removeClass('col-md-10').addClass('col-md-8');
            $('#add-budget').data('urutan', (no-1));
            $(me).parent().parent().remove();
		}
	}

    $(function(){
        $('.select2').select2();

        getBudget()
        //Date range picker with time picker
        getDatePicker3();

        $("#form").validate({
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

        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            timePicker: false,
            timePickerIncrement: 30,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        $('.datepicker').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $('.datepicker2').daterangepicker({
            singleDatePicker: true,
            timePicker: false,
            timePickerIncrement: 30,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        $('.datepicker2').val("");

        $('input[name^="gde_date_"]').on('apply.daterangepicker', function(ev, picker) {
            let date = picker.startDate.format('DD/MM/YYYY')
            $(this).val(date)
            getGdePeriod()
        });

        $("#input-list-checkbox input[type='checkbox']").change(function(){
            var checked = $(this).is(":checked");
            if(checked){
                $(this).parent().parent().children(".row-form").slideDown("fast");
                $(this).parent().parent().children(".row-form").find("input").attr('required', 'required');
            }else{
                $(this).parent().parent().children(".row-form").slideUp("fast");
                $(this).parent().parent().children(".row-form").find("input").removeAttr('required');
            }
        });

        inputPrice();

        $('#add-budget').on('click', function(e) {
            e.preventDefault();
            var no = $(this).data('urutan') + 1,
                html = `<div class="row item-budget mt-1" id="rbudget-${no}">
                    <div class="col-md-6">
                    <select type="text" class="select2 form-control" id="budget-${no}" name="budget_id[]" data-placeholder="Tag budget..." >
                    </select>
                    </div>
                    <div class="col-md-4">
                    <input type="text" name="budget_val[]" class="form-control input-price" placeholder="Enter Budget Value">
                    </div>
                    <div class="col-md-2">
                    <button type="button" class="btn btn-transparent text-md" onclick="removeBudget(this)" data-urutan=${no}><i class="fas fa-trash text-maroon color-palette"></i></button>
                    </div>
                </div>`;

            if (no > 2) {
                $('#rbudget-'+(no-1)).find('.col-md-2').hide();
                // $('#rbudget-'+(no-1)).find('.col-md-8').removeClass('col-md-8').addClass('col-md-10');
                $(`#budget-${no}`).css('width', '100%')
            }
            $(this).data('urutan', no);
            $('#form-budget').append(html);
            $(`#budget-${no}`).css('width', '100%')
            inputPrice()
            getBudget(no)
        });

    });

    function getDatePicker3(){
        $('.datepicker3').daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            timePicker: false,
            timePickerIncrement: 30,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
            }
        });
        $('#input_date').on('apply.daterangepicker', function(ev, picker) {
            let date = picker.startDate.format('DD/MM/YYYY')
            $(this).val(date)
            getPeriod(date)
        });

        $('#input_date').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    }

    function getGdePeriod(){
        var date = []
        for (let index = 1; index <= 11; index++) {
            let getdate = $(`input[name="gde_date_${index}"]`).val()
            if(getdate){
                let newdate = getdate.split('/')
                    newdate = newdate[2]+'-'+newdate[1]+'-'+newdate[0]
                date.push(newdate)
            }
        }
        
        $.ajax({
            url: "{{ route('purchasing.getgdeperiod') }}",
            method: 'post',
            dataType: 'json',
            data: {
                date: date,
            },
            success:function(response) {
                let days = response.count
                $('#sum_days').val(days)
            }
        })
    }

    function inputPrice(){
        $(".input-price").priceFormat({
            prefix: '',
            centsSeparator: ',',
            thousandsSeparator: '.',
            centsLimit: 0,
            clearOnEmpty: true
        });
    }

    function getBudget(id){
        if(id){
            var bid = `#budget-${id}`
        } else {
            var bid = '#budget'
        }
        $( bid ).select2({
			ajax: {
				url: '{{ route("budgetary.select") }}',
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
						text: `${item.name}` 
					});
				 });
				  return {
					results: option, more: more,
				  };
				},
			},
			allowClear: true,
		});
    }

    function selectRule(that) {
        let val = $(that).val()
        $('#schedule-adb').removeClass('showed').addClass('hidden')
        $("#choose_adb").select2("trigger", "select", {
            data: {id:"", text:"-- Choose"}
        });
        if(val =='gde'){
            $('#choose-adb').removeClass('choosed').addClass('hidden')
            $('#schedule-gde').removeClass('hidden').addClass('showed')
        } else if(val=='adb'){
            $('#choose-adb').removeClass('hidden').addClass('choosed')
            $('#schedule-gde').removeClass('showed').addClass('hidden')
        } else {
            $('#choose-adb').removeClass('choosed').addClass('hidden')
            $('#schedule-gde').removeClass('showed').addClass('hidden')
        }
    }

    function chooseSchedule(that){
        let type = $(that).val()
        $('#sum_days').val('')
        if(!type){
            $('.first_data li').remove()
            $('#schedule-adb').removeClass('showed').addClass('hidden')
            return false
        }
        $.ajax({
            url: "{{ route('purchasing.getadb') }}",
            method: 'get',
            dataType: 'json',
            data: {
                type: type
            },
            success:function(response) {
                let data = response
                let first = ''
                data.forEach((val, index) => {
                first += `<li id="period-${val.id}">
                            <div class="row">
                                <div class="col-md-8">
                                <input type="hidden" name="adb_id[]" value="${val.id}" >
                                <span class="text">${val.schedule_name}</span>
                                </div>
                                <div class="col-md-4">
                                <div class="input-group mt-1">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                    </div>
                                    <input ${(index==0)?'id="input_date"':''} type="text" name="adb_date[]" class="form-control date ${(index==0)?'datepicker3':''} text-right" placeholder="${(index==0)? 'Pick a Date...':'Automatic'}" ${(index!=0)?'readonly':'required'} >
                                </div>
                                </div>
                            </div>
                            </li>`
                })
                $('.first_data').html(first)
                $('#schedule-adb').removeClass('hidden').addClass('showed')
                getDatePicker3()
            }
        })
    }

    function getPeriod(date){
        let type = $('#choose_adb').val()
        $.ajax({
            url: "{{ route('purchasing.getperiod') }}",
            method: 'post',
            dataType: 'json',
            data: {
                date: date,
                type: type
            },
            success:function(response) {
                let data = response.data
                let days = response.count

                data.forEach((val, key) => {
                    if(key != 0){
                        $(`#period-${val.id}`).find('input.date').val(val.date)
                    }
                })
                $('#sum_days').val(days)
            }
        })
    }

    function getPortion(that) {
        let percent = 100
        let tech = $(that).val()
        let result = percent - tech*1
        
        $('input[name="financial"]').val(result)
    }

    function changePath(that) {
		let filename = $(that).val()	
		$(that).next().html(filename)
	}

</script>
@endsection