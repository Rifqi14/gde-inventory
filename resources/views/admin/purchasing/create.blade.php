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
            Create Purchasing
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Procurment</li>
            <li class="breadcrumb-item">Purchasing</li>
            <li class="breadcrumb-item">Create</li> 
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <form role="form" id="form" action="{{route('purchasing.store')}}">
            {{ csrf_field() }}
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
                                <input type="text" name="number" placeholder="Procurement Number..." class="form-control" autocomplete="false" required="true" />
                            </div>
                            <div class="form-group mt-4">
                                <label for="complainant">Subject:</label>
                                <input type="text" name="subject" class="form-control" placeholder="Procurement Subject...">
                            </div>
                            <div class="form-group mt-3">
                                <div class="row">
                                    <div class="col-4">
                                        <label>Rule:</label>
                                        <select name="rule" id="" class="form-control" onchange="selectRule(this)" required>
                                            <option value="">Select Rule</option>
                                            @foreach(config('enums.rule') as $key => $rule)
                                                <option value="{{ $key }}">{{ $rule }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-8">
                                        <label>Estimated Value:</label>
                                        <div class="row">
                                            <div class="col-3">
                                                <select name="est_currency" class="form-control" required>
                                                    <option value="">Choose a Currency</option>
                                                    @foreach(config('enums.currency') as $key => $currency)
                                                        <option value="{{ $key }}">{{ $currency }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-9">
                                                <input type="text" name="est_value" class="form-control input-price" placeholder="Estimated Value">
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
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
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
                                                <input type="text" class="form-control input-price" name="technical" placeholder="Technical Portion..." autocomplete="off" required onkeyup="getPortion(this)" />
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
                                                <input type="text" class="form-control" name="financial" placeholder="Financial Portion..." autocomplete="off" required readonly />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="choose-adb" class="form-group row hidden">
                                        <label class="col-4 col-form-label">ADB Guideline:</label>
                                        <div class="col-8">
                                            <select name="choose_adb" id="choose_adb" class="form-control select2" onchange="chooseSchedule(this)">
                                                <option value="">Tag ADB Guideline</option>
                                                @foreach(config('enums.adb_guideline') as $key => $adb_guideline)
                                                    <option value="{{ $key }}">{{ $adb_guideline }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="schedule-gde" class="form-group hidden">
                    <label>Schedule:</label>
                    <div class="row">
                      <div class="col-6">
                        <ul class="todo-list" data-widget="todo-list" id="input-list-checkbox">
                          <li>
                            <div class="icheck-success d-inline ml-2">
                              <input type="checkbox" value="Advertisement" name="schedule_name_1" id="todoCheck0">
                              <label for="todoCheck0"></label>
                            </div>
                            <span class="text">Advertisement</span>
                            <div class="row-form">
                              <div class="row mb-1">
                                <div class="col-12">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control date datepicker2 text-right" placeholder="" name="gde_date_1">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          <li>
                            <div class="icheck-success d-inline ml-2">
                              <input type="checkbox" value="Aanwijzing" name="schedule_name_2" id="todoCheck1">
                              <label for="todoCheck1"></label>
                            </div>
                            <span class="text">Aanwijzing</span>
                            <div class="row-form">
                              <div class="row mb-1">
                                <div class="col-12">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control date datepicker2 text-right" placeholder="" name="gde_date_2">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          <li>
                            <div class="icheck-success d-inline ml-2">
                              <input type="checkbox" value="Site Visit" name="schedule_name_3" id="todoCheck2">
                              <label for="todoCheck2"></label>
                            </div>
                            <span class="text">Site Visit</span>
                            <div class="row-form">
                              <div class="row mb-1">
                                <div class="col-12">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control date datepicker2 text-right" placeholder="" name="gde_date_3">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          <li>
                            <div class="icheck-success d-inline ml-2">
                              <input type="checkbox" value="Envelope Submission" name="schedule_name_4" id="todoCheck3">
                              <label for="todoCheck3"></label>
                            </div>
                            <span class="text">Envelope Submission</span>
                            <div class="row-form">
                              <div class="row mb-1">
                                <div class="col-12">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control date datepicker2 text-right" placeholder="" name="gde_date_4">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          <li>
                            <div class="icheck-success d-inline ml-2">
                              <input type="checkbox" value="Technical Evaluation" name="schedule_name_5" id="todoCheck4">
                              <label for="todoCheck4"></label>
                            </div>
                            <span class="text">Technical Evaluation</span>
                            <div class="row-form">
                              <div class="row mb-1">
                                <div class="col-12">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control date datepicker2 text-right" placeholder="" name="gde_date_5">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          <li>
                            <div class="icheck-success d-inline ml-2">
                              <input type="checkbox" value="Financial Evaluation" name="schedule_name_6" id="todoCheck5">
                              <label for="todoCheck5"></label>
                            </div>
                            <span class="text">Financial Evaluation</span>
                            <div class="row-form">
                              <div class="row mb-1">
                                <div class="col-12">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control date datepicker2 text-right" placeholder="" name="gde_date_6">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                        </ul>
                      </div>
                      <div class="col-6">
                        <ul class="todo-list" data-widget="todo-list" id="input-list-checkbox">
                          <li>
                            <div class="icheck-success d-inline ml-2">
                              <input type="checkbox" value="Clarification" name="schedule_name_7" id="todoCheck7">
                              <label for="todoCheck7"></label>
                            </div>
                            <span class="text">Clarification</span>
                            <div class="row-form">
                              <div class="row mb-1">
                                <div class="col-12">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control date datepicker2 text-right" placeholder="" name="gde_date_7">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          <li>
                            <div class="icheck-success d-inline ml-2">
                              <input type="checkbox" value="Winner Announcement" name="schedule_name_8" id="todoCheck8">
                              <label for="todoCheck8"></label>
                            </div>
                            <span class="text">Winner Announcement</span>
                            <div class="row-form">
                              <div class="row mb-1">
                                <div class="col-12">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control date datepicker2 text-right" placeholder="" name="gde_date_8">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          <li>
                            <div class="icheck-success d-inline ml-2">
                              <input type="checkbox" value="Contract Negotiation" name="schedule_name_9" id="todoCheck9">
                              <label for="todoCheck9"></label>
                            </div>
                            <span class="text">Contract Negotiation</span>
                            <div class="row-form">
                              <div class="row mb-1">
                                <div class="col-12">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control date datepicker2 text-right" placeholder="" name="gde_date_9">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          <li>
                            <div class="icheck-success d-inline ml-2">
                              <input type="checkbox" value="Physical Check" name="schedule_name_10" id="todoCheck10">
                              <label for="todoCheck10"></label>
                            </div>
                            <span class="text">Physical Check</span>
                            <div class="row-form">
                              <div class="row mb-1">
                                <div class="col-12">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control date datepicker2 text-right" placeholder="" name="gde_date_10">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          <li>
                            <div class="icheck-success d-inline ml-2">
                              <input type="checkbox" value="Contract Signing" name="schedule_name_11" id="todoCheck11">
                              <label for="todoCheck11"></label>
                            </div>
                            <span class="text">Contract Signing</span>
                            <div class="row-form">
                              <div class="row mb-1">
                                <div class="col-12">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control date datepicker2 text-right" placeholder="" name="gde_date_11">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>
                                    </div>
                                    <div id="schedule-adb" class="form-group hidden">
                                        <label>Schedule:</label>
                                        <div class="row">
                                            <div class="col-12">
                                                <ul class="todo-list first_data" data-widget="todo-list" id="input-list-checkbox">
                                                
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
                                    <div class="row item-budget">
                                        <div class="col-md-6">
                                            <select type="text" class="select2 form-control" id="budget" name="budget_id[]" data-placeholder="Tag Budget..." >
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="budget_val[]" class="form-control input-price" placeholder="Enter Budget Value">
                                        </div>
                                        <div class="col-md-2">
                                            <button id="add-budget" data-urutan="1" type="button" class="btn btn-success legitRipple text-sm">
                                                <b><i class="fas fa-plus"></i></b>
                                            </button>
                                        </div>
                                    </div>
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
                                    <input id="sum_days" type="text" class="form-control text-right" name="duration" placeholder="Enter Duration Target..." autocomplete="off" readonly />
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