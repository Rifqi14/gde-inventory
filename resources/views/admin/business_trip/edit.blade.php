@extends('admin.layouts.app')

@section('title')
Edit {{ @$menu_name }} Request
@endsection

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
	<div class="col-sm-4">
		<h1 id="title-branch" class="m-0 text-dark">
			{{ @$menu_name }} Request
		</h1>
	</div>
	<div class="col-sm-8">
		<ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
			<li class="breadcrumb-item">{{ @$parent_name }}</li>
			<li class="breadcrumb-item">{{ @$menu_name }}</li>
			<li class="breadcrumb-item active">Edit</li>
		</ol>
	</div>
</div>
@endsection

@section('content')
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<form role="form" id="form" action="{{route('businesstrip.update',['id' => $data->id])}}">
			{{ csrf_field() }}
			@method('PUT')
			<div class="row">
				<div class="col-md-8">
					<div class="card">
						<div class="card-body">
							<span class="title">
								<hr />
								<h5 class="text-md text-dark text-uppercase">Transportation</h5>
							</span>
							<!-- DEPART -->
							<div id="form-depart">
								<div class="row mt-4 mb-0">
									<div class="col-md-2">
										<label>Depart:</label>
									</div>
									<div class="col-md-6">
										<label>Description:</label>
									</div>
									<div class="col-md-3">
										<label>Price:</label>
									</div>
								</div>
							</div>
							@if($data->status != 'approved')
							<div class="text-right">
								<button type="button" id="add-depart" data-urutan="1" onclick="addDepart()" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple">
									<b><i class="fas fa-plus"></i></b> Add
								</button>
							</div>
							@endif
							<!-- RETURN -->
							<div id="form-return">
								<div class="row mt-4 mb-0">
									<div class="col-md-2">
										<label>Return:</label>
									</div>
									<div class="col-md-6">
										<label>Description:</label>
									</div>
									<div class="col-md-3">
										<label>Price:</label>
									</div>
								</div>
							</div>
							@if($data->status != 'approved')
							<div class="text-right">
								<button type="button" data-urutan="1" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple" onclick="addReturn()">
									<b><i class="fas fa-plus"></i></b> Add
								</button>
							</div>
							@endif
						</div>
					</div>
					<div class="card">
						<div class="card-body">
							<span class="title">
								<hr>
								<h5 class="text-md text-dark text-uppercase">Location</h5>
							</span>
							<div id="form-location">
								<div class="row mt-4 mb-0">
									<div class="col-md-10">
										<label>Location:</label>
									</div>
								</div>
							</div>
							@if($data->status != 'approved')
							<div class="text-right">
								<button type="button" data-urutan="1" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple" onclick="addLocation()">
									<b><i class="fas fa-plus"></i></b> Add
								</button>
							</div>
							@endif
						</div>
					</div>
					<div class="card">
						<div class="card-body">
							<!-- REQUEST VEHICLE -->
							<span class="title">
								<hr />
								<h5 class="text-md text-dark text-uppercase">Request Vehicle</h5>
							</span>
							<div id="form-request-vehicle">
							</div>
							@if($data->status != 'approved')
							<div class="text-right">
								<button type="button" id="add-return" data-urutan="1" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple" onclick="addVehicle()">
									<b><i class="fas fa-plus"></i></b> Add
								</button>
							</div>
							@endif
						</div>
					</div>
					<div class="card">
						<div class="card-body">
							<span class="title">
								<hr />
								<h5 class="text-md text-dark text-uppercase">Lodging</h5>
							</span>
							<!-- LODGING -->
							<div id="form-lodging">
								<div class="row mt-4 mb-0">
									<div class="col-md-6">
										<label>Place:</label>
									</div>
									<div class="col-md-3">
										<label>Price:</label>
									</div>
									<div class="col-md-2">
										<label>Night:</label>
									</div>
								</div>
							</div>
							@if($data->status != 'approved')
							<div class="text-right">
								<button type="button" id="add-lodging" data-urutan="1" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple" onclick="addLodging()">
									<b><i class="fas fa-plus"></i></b> Add
								</button>
							</div>
							@endif
						</div>
					</div>
					<div class="card">
						<div class="card-body">
							<span class="title">
								<hr />
								<h5 class="text-md text-dark text-uppercase">Others</h5>
							</span>
							<!-- OTHERS  -->
							<div id="form-others">
								<div class="row mt-4 mb-0">
									<div class="col-md-6">
										<div class="form-group">
											<label>Description:</label>
										</div>
									</div>
									<div class="col-md-4">
										<label>Price:</label>
									</div>
									<div class="col-md-2">
										<label>Qty:</label>
									</div>
								</div>
							</div>
							@if($data->status != 'approved')
							<div class="text-right">
								<button type="button" id="add-others" data-urutan="1" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple" onclick="addOthers()">
									<b><i class="fas fa-plus"></i></b> Add
								</button>
							</div>
							@endif
						</div>
					</div>
					<!-- /.card -->
				</div>
				<div class="col-md-4">
					<div class="card">
						<div class="card-body">
							<span class="title">
								<hr />
								<h5 class="text-md text-dark text-uppercase">General Information</h5>
							</span>
							<div class="form-group">
								<label for="business-trip-number">Request Number</label>
								<input type="text" class="form-control" name="business_trip_number" id="business-trip-number" placeholder="Auto Generate Number" value="{{$data->business_trip_number}}" readonly>
							</div>
							<div class="form-group mt-4">
								<label>Issued by:</label>
								<select class="form-control select2" name="issued" id="issued" data-placeholder=" -Select WBS- " style="width: 100%;" disabled>
									@if(Auth::guard('admin')->user()->id)
									<option value="{{Auth::guard('admin')->user()->id}}" selected>{{Auth::guard('admin')->user()->name}}</option>
									@endif
								</select>
							</div>
							<div class="form-group row">
								<div class="col-md-6">
									<label>Departure Date:</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="far fa-calendar-alt"></i>
											</span>
										</div>
										<input type="datepicker" class="form-control datepicker text-right departure-date" id="departure-date" onchange="employee()" required>
									</div>
								</div>
								<div class="col-md-6">
									<label for="arrived-date">Arrived Date:</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="far fa-calendar-alt"></i>
											</span>
										</div>
										<input type="datepicker" class="form-control datepicker text-right arrived-date" id="arrived-date" onchange="employee()" required>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="purpose" class="control-label">Purpose</label>
								<input type="text" class="form-control" name="purpose" id="purpose" value="{{$data->purpose}}">
							</div>
							<div class="form-group">
								<label>Rate:</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text currency_rate">
										</span>
									</div>
									<input type="text" class="form-control input-price text-right" id="rate" name="rate" placeholder="Enter rate" value="{{$data->rate?$data->rate:0}}" maxlength="14" readonly>
								</div>
							</div>
							<div class="form-group">
								<label for="total-cost">Estimated Cost:</label>
								@foreach ($currencies as $currency)
								@if ($total[$currency->id] > 0)
								<div class="input-group mt-2">
									<div class="input-group-prepend">
										<span class="input-group-text">{{ $currency->symbol }}</span>
									</div>
									<input type="text" class="form-control input-price text-right" id="total-cost-{{ $currency->id }}" name="total_cost-{{ $currency->id }}" placeholder="Enter total cost" value="{{$total[$currency->id]}}" readonly>
								</div>
								@endif
								@endforeach
							</div>
							<div class="form-group">
								<label>Approval Status : </label>
								<br>
								<input type="hidden" name="status" id="status" value="{{$data->status}}">
								@if($data->status == 'draft')
								<span class="badge bg-gray text-sm">Draft</span>
								@endif
								@if($data->status == 'waiting')
								<span class="badge bg-warning text-sm">Waiting</span>
								@endif
								@if($data->status == 'approved')
								<span class="badge bg-info text-sm">Approved</span>
								@endif
							</div>
						</div>
					</div>
					<div class="text-right">
						@if($data->status != 'approved')
						<button type="button" onclick="submitTest(`approved`)" class="btn btn-success btn-labeled legitRipple text-sm">
							<b><i class="fas fa-check-circle"></i></b>
							Approved
						</button>
						<button type="button" onclick="submitTest(`waiting`)" class="btn bg-olive color-palette btn-labeled legitRipple text-sm">
							<b><i class="fas fa-save"></i></b>
							Submit
						</button>
						@endif
						<a href="{{ route('businesstrip.index') }}" class="btn btn-secondary color-palette btn-labeled legitRipple text-sm">
							<b><i class="fas fa-times"></i></b>
							Cancel
						</a>
					</div>
				</div>
			</div>
		</form>
	</div>
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script type="text/javascript">
	var employeeID = "{{Auth::guard('admin')->user()->employee_id}}",
		userID = "{{Auth::guard('admin')->user()->id}}",
		username = "{{Auth::guard('admin')->user()->name}}",
		businesstrip = @json($data);

	var currencies = @json($currencies);
		
	const formatCountry = (currency) => {
		console.log(currency);
		if (!currency.code) { return currency.text; }
			var $currency = $(
			'<span class="flag-icon flag-icon-'+ currency.code.toLowerCase() +' flag-icon-squared"></span>' +
			'&nbsp;&nbsp;&nbsp;<span class="flag-text">'+ currency.text+"</span>"
			);
		return $currency;
  }

	$(function() {					
		initData();

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

		$('.departure-date').data('daterangepicker').setStartDate(moment(new Date()));
		$('.arrived-date').data('daterangepicker').setStartDate(moment(new Date()).add(6, 'days'));

		$('input[name=rate]').change(function (e) { 
			e.preventDefault();
			sumRate();
		});

		$('input[name=rate]').keyup(function (e) { 
			sumRate();
		});

		@if($data->departure_date)
		$('.departure-date').data('daterangepicker').setStartDate("{{date('d/m/Y',strtotime($data->departure_date))}}");
		@endif

		@if($data->arrived_date)
		$('.arrived-date').data('daterangepicker').setStartDate("{{date('d/m/Y',strtotime($data->arrived_date))}}");
		@endif

		initSelect2();
		initRequestVehicle();
		initRemarks();
		initInputPrice();
		sumRate();

		// FORM DEPART METHOD

		$('#form-depart').on('click', '.remove', function() {
			if ($('.item-depart').length > 1) {
				$(this).parents('.item-depart').remove();
				$('#form-depart').find('.item-depart').first().removeClass('mt-2');
			}
			sumRate();
		});

		$('#form-depart').on('change', '.depart-type', function() {
			var type = $(this).val();
			$(this).parents('.item-depart').find('input[class=depart]').attr('data-type', type);
		});
		$('#form-depart').on('change', '.depart-currency', function() {
			var currency_id = $(this).val();
			$(this).parents('.item-depart').find('input[class=depart]').attr('data-currency_id', currency_id);
			sumRate();
		});

		$('#form-depart').on('keyup', '.depart-description', function() {
			var description = $(this).val();
			$(this).parents('.item-depart').find('input[class=depart]').attr('data-description', description);
		});

		$('#form-depart').on('keyup', '.depart-price', function() {
			var price = $(this).val();
			$(this).parents('.item-depart').find('input[class=depart]').attr('data-price', price);
			sumRate();
		});

		// FORM RETURN METHOD

		$('#form-return').on('click', '.remove', function() {
			if ($('.item-return').length > 1) {
				$(this).parents('.item-return').remove();
				$('#form-return').find('.item-return').first().removeClass('mt-2');
			}
			sumRate();      
		});

		$('#form-return').on('change', '.returning-type', function() {
			var type = $(this).val();
			$(this).parents('.item-return').find('input[class=returning]').attr('data-type', type);
		});
		$('#form-return').on('change', '.return-currency', function() {
			var currency_id = $(this).val();
			$(this).parents('.item-return').find('input[class=returning]').attr('data-currency_id', currency_id);
			sumRate();
		});

		$('#form-return').on('keyup', '.returning-description', function() {
			var description = $(this).val();
			$(this).parents('.item-return').find('input[class=returning]').attr('data-description', description);      
		});

		$('#form-return').on('keyup', '.returning-price', function() {
			var price = $(this).val();
			$(this).parents('.item-return').find('input[class=returning]').attr('data-price', price);
			sumRate();
		});

		// FORM LOCATION METHOD 
		$('#form-location').on('click','.remove', function(){
			if ($('.item-location').length > 1) {
				$(this).parents('.item-location').remove();
				$('#form-location').find('.item-location').first().removeClass('mt-2');
			}
		});

		$('#form-location').on('keyup', '.input-location', function() {
			var location = $(this).val();
			$(this).parents('.item-location').find('input[class=location]').val(location);
		});

		// FORM REQUEST VEHICLE METHOD
		$('#form-request-vehicle').on('click', '.remove', function() {
			$(this).parents('.item-request-vehicle').remove();      
		});

		// FORM LODGING METHOD

		$('#form-lodging').on('click', '.remove', function() {
			if ($('.item-lodging').length > 1) {
				$(this).parents('.item-lodging').remove();
				$('#form-lodging').find('.item-lodging').first().removeClass('mt-2');
			}
			sumRate();
		});

		$('#form-lodging').on('keyup', '.place-lodging', function() {
			var place = $(this).val();
			$(this).parents('.item-lodging').find('input[class=lodging]').attr('data-place', place);
		});

		$('#form-lodging').on('keyup', '.price-lodging', function() {
			var price = $(this).val();
			$(this).parents('.item-lodging').find('input[class=lodging]').attr('data-price', price);
			sumRate();
		});

		$('#form-lodging').on('keyup', '.days-lodging', function() {
			var days = $(this).val();
			$(this).parents('.item-lodging').find('input[class=lodging]').attr('data-days', days);
			sumRate();
		});

		$('#form-lodging').on('change', '.days-lodging', function() {
			var days = $(this).val();
			$(this).parents('.item-lodging').find('input[class=lodging]').attr('data-days', days);
			sumRate();
		});
		$('#form-lodging').on('change', '.lodging-currency', function() {
			var currency_id = $(this).val();
			$(this).parents('.item-lodging').find('input[class=lodging]').attr('data-currency_id', currency_id);
			sumRate();
		});

		// FORM OTHERS METHOD

		$('#form-others').on('click', '.remove', function() {
			$(this).parents('.item-others').remove();
			sumRate();
		});

		$('#form-others').on('keyup', '.others-description', function() {
			var description = $(this).val();
			$(this).parents('.item-others').find('input[class=others-data]').attr('data-description', description);
		});

		$('#form-others').on('keyup', '.others-price', function() {
			var price = $(this).val();
			$(this).parents('.item-others').find('input[class=others-data]').attr('data-price', price);
			sumRate();
		});

		$('#form-others').on('keyup', '.others-qty', function() {
			var qty = $(this).val();
			$(this).parents('.item-others').find('input[class=others-data]').attr('data-qty', qty);
			sumRate();
		});

		$('#form-others').on('change', '.others-qty', function() {
			var qty = $(this).val();
			$(this).parents('.item-others').find('input[class=others-data]').attr('data-qty', qty);
			sumRate();
		});
		$('#form-others').on('change', '.others-currency', function() {
			var currency_id = $(this).val();
			$(this).parents('.item-others').find('input[class=others-data]').attr('data-currency_id', currency_id);
			sumRate();
		});

		employee();

		$("#form").validate({
			rules: {
				business_trip_number: {
					required: true
				},
				purpose: {
					required: true
				},
				location: {
					required: true
				},
				rate: {
					required: true
				},
				depart_type: {
					required: true
				}
			},
			messages: {
				business_trip_number: {
					required: 'This field is required.'
				},
				purpose: {
					required: 'This field is required.'
				},
				location: {
					required: 'This field is required.'
				},
				rate: {
					required: 'This field is required.'
				}
			},
			errorElement: 'div',
			errorPlacement: function(error, element) {
				error.addClass('invalid-feedback');
				element.closest('.form-group .controls').append(error);

				if (element.is(':file')) {
					error.insertAfter(element.parent().parent().parent());
				} else
				if (element.parent('.input-group').length) {
					error.insertAfter(element.parent());
				} else
				if (element.attr('type') == 'checkbox') {
					error.insertAfter(element.parent());
				} else {
					error.insertAfter(element);
				}
			},
			highlight: function(element, errorClass, validClass) {
				$(element).addClass('is-invalid');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).removeClass('is-invalid');
			},
			submitHandler: function() {
				var data 		  = new FormData($('#form')[0]),
					departureDate = $('.departure-date').data('daterangepicker').startDate.format('DD/MM/YYYY'),
					arrivedDate   = $('.arrived-date').data('daterangepicker').startDate.format('DD/MM/YYYY'),
					departure 	  = [],
					returning 	  = [],
					location 	  = [],
					vehicle 	  = [],
					lodging 	  = [],
					others 		  = [];

				$.each($('#form-depart > .item-depart').find('input[class=depart]'), function(index, value) {
					var type = $(this).attr('data-type'),
						description = $(this).attr('data-description'),
						price = $(this).attr('data-price'),
						currency_id = $(this).attr('data-currency_id');

					departure.push({
						type: type,
						description: description,
						price: price,
						currency_id: currency_id,
					});
				});

				$.each($('#form-return > .item-return').find('input[class=returning]'), function(index, value) {
					var type = $(this).attr('data-type'),
						description = $(this).attr('data-description'),
						price = $(this).attr('data-price'),
						currency_id = $(this).attr('data-currency_id');

					returning.push({
						type: type,
						description: description,
						price: price,
						currency_id: currency_id,
					});
				});

				$.each($('#form-location > .item-location').find('input[class=location]'), function (index, value) { 
					var locate = $(this).val();
					location.push({              
						location : locate
					});
				});       

				$.each($('#form-request-vehicle').find('input[class=vehicles]'), function (index, value) { 
					 var request_id = $(this).val();
					 vehicle.push({
						 request_id : request_id
					 });
				});				

				$.each($('#form-lodging > .item-lodging').find('input[class=lodging]'), function(index, value) {
					var place = $(this).attr('data-place'),
						price = $(this).attr('data-price'),
						days = $(this).attr('data-days'),
						currency_id = $(this).attr('data-currency_id');

					lodging.push({
						place: place,
						price: price,
						days: days,
						currency_id: currency_id,
					});
				});

				$.each($('#form-others > .item-others').find('input[class=others-data]'), function(index, value) {
					var description = $(this).attr('data-description'),
						price = $(this).attr('data-price'),
						qty = $(this).attr('data-qty'),
						currency_id = $(this).attr('data-currency_id');

					others.push({
						description: description,
						price: price,
						qty: qty,
						currency_id: currency_id,
					});
				});

				data.append('issued', userID);
				data.append('departure_date', changeDateFormat(departureDate));
				data.append('arrived_date', changeDateFormat(arrivedDate));
				data.append('departure', JSON.stringify(departure));
				data.append('location', JSON.stringify(location));
				data.append('vehicle', JSON.stringify(vehicle));	
				data.append('returning', JSON.stringify(returning));
				data.append('lodging', JSON.stringify(lodging));
				data.append('others', JSON.stringify(others));

				$.ajax({
					url: $('#form').attr('action'),
					method: 'post',
					data: data,
					processData: false,
					contentType: false,
					dataType: 'json',
					beforeSend: function() {
						blockMessage('body', 'Please Wait . . . ', '#fff');
					}
				}).done(function(response) {
					$('body').unblock();
					console.log({
						response: response
					});
					if (response.status) {
						toastr.success('Data has been saved.');
						document.location = "{{route('businesstrip.index')}}";
					} else {
						toastr.warning(`${response.message}`);
					}
					return;
				}).fail(function(response) {
					$('body').unblock();
					var response = response.responseJSON,
						message = response.message ? response.message : 'Failed to insert data.';

					toastr.warning(message);
					console.log({
						errorMessage: message
					});
				})
			}
		});

	});
	
	const dateDiff = () => {
    var expDepartureDate  = $('#departure-date').val().split("/");
    var expArrivedDate    = $('#arrived-date').val().split("/");
    var departureDate     = new Date(expDepartureDate[2], expDepartureDate[1] - 1, expDepartureDate[0], 0, 0, 0);
    var arrivedDate       = new Date(expArrivedDate[2], expArrivedDate[1] - 1, expArrivedDate[0], 23, 59, 59);
    var diff              = Math.abs(arrivedDate - departureDate);
    var days              = diff / 1000 / 60 / 60 / 24;
    return Math.round(days);
  }
  
  const employee = () => {
    var days = dateDiff();
    $.ajax({
      type: "GET",
      url: `{{route('employee.dig')}}`,
      data: {
        employee_id : employeeID
      },
      dataType: "json",
      success: function (response) {
        if(response.status){
          var data = response.data;          
          employeeRate = data.rate_business_trip
					currency	= data.ratecurrency;
          
          $('input[name=rate]').val(employeeRate);
					$('.currency_rate').text(currency.symbol);
          initInputPrice();
          $('input[name=rate]').trigger('change');
        }else{
          console.log({
            'message' : response.message
          });
        }
      },
      error :  function(){
        console.log({
          message : 'Failed to get employee data.'
        });
      }
    });
    
  }

	function initSelect2() {
		$('.select2').select2({
			allowClear: true
		});
		
		$(".currency_id").select2({
				placeholder: "Sym ...",
        ajax: {
            url: "{{route('currency.select')}}",
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
                params.page = params.page || 1;
                var more = (params.page * 30) < data.total;
                var option = [];
                $.each(data.rows, function(index, item) {
                    option.push({
                        id: item.id,
                        text: item.symbol,
                        code: item.country ? item.country.code : item.country_id,
                    });
                });
                return {
                    results: option,
                    pagination: {
                        more: more,
                    }
                };
            },
        },
        templateResult: formatCountry,
        allowClear: true,
    });
	}

	function initRequestVehicle() {
		$(".request-vehicle").select2({
		ajax: {
			url: "{{route('requestvehicle.select')}}",
			type: 'GET',
			dataType: 'json',
			data: function(params) {
			return {
				employee_id: userID,
				search: params.term,
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
				text: item.vehicle_name + ' | ' + item.date_request,
				notes: item.remarks,
				daterequest: item.date_request
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
			var element = $(this).parents('.item-request-vehicle');

			element.find('.date-request-vehicle').val(data.daterequest);
			element.find('input[class=vehicles]').val(data.id);
			
			if (data.notes) {				
				element.find('.remarks').summernote('code', data.notes);				
			}			
		}).on('select2:clearing', function() {
			var element = $(this).parents('.item-request-vehicle');

			element.find('.remarks').summernote('reset');
			element.find('.date-request-vehicle').val('');
			element.find('input[class=vehicles]').val('');
		});
	}

	function initData() {
		var departs  = @json($data->departs),
			returns  = @json($data->returns),
			locations = @json($data->location),
			vehicles = @json($data->vehicles),
			lodgings = @json($data->lodgings),
			others   = @json($data->others),
			state  	 = '{{$data->status}}',
			readonly = state=='approved'?'readonly':'',
			disabled = state=='approved'?'disabled':''; 			

		if(departs.length > 0){
			var html     = '',				
				remove   = `<div class="col-md-1">
								<div class="form-group" style="margin-top: 2px;">                                            
								<button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove"type="button"><i class="fas fa-trash"></i></button>
								</div>
							</div>`;
			$.each(departs, function (index, value) { 
				var type  = value.type,
					desc  = value.description,
					price = value.price?value.price:0,
					currency_id = value.currency_id ? value.currency_id : null,
					currency_symbol = value.transport_currency ? value.transport_currency.symbol : null
					mt    = index>0?'mt-2':'';

				html += `<div class="row ${mt} item-depart">
                  <input type="hidden" class="depart" name="depart[]" data-type="${type}" data-description="${desc}" data-price="${price}" data-currency_id="${currency_id}" data-currency_symbol="${currency_symbol}"/>
                  <div class="col-md-2">
                    <div class="form-group">                                          
                      <select class="form-control select2 depart-type" name="depart_type" id="depart-type" data-placeholder="Depart" ${disabled}>                        
                        <option value="flight" ${type=='flight'?'selected':''}>Flight</option>
                        <option value="others" ${type=='others'?'selected':''}>Others</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">                      
                      <input type="text" name="depart_description" class="form-control depart-description" id="depart-description" placeholder="Enter description" value="${desc}" required ${readonly}/>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">                      
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <select name="transport_currency_id" class="form-control select2 currency_id depart-currency" aria-placeholder="Sym" data-currency_id="${currency_id}" data-currency_symbol="${currency_symbol}">
														<option>Sym</option>
                          </select>
                        </div>
                        <input type="text" name="depart_price" class="form-control input-price text-right depart-price" id="depart-price" placeholder="Enter price" value="${price}" maxlenght="14" required ${readonly}>
                      </div>
                    </div>
                  </div>                  
									${remove}
                </div>`;
			});			
			$('#form-depart').append(html);
			initSelect2();
			$.each($('#form-depart > .item-depart').find('.depart-currency'), function(index, value) {
				var currency_id			= $(this).attr('data-currency_id'),
						currency_symbol	= $(this).attr('data-currency_symbol');
				if (currency_id) {
					$(this).select2('trigger', 'select', {
						data: {
							id: currency_id,
							text: currency_symbol,
						}
					});
				}
			});
		}else{
			addDepart();
		}		

		if(returns.length > 0){
			var html   = '',				
				remove = `<div class="col-md-1">
								<div class="form-group" style="margin-top: 2px;">
								<button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
								</div>
							</div>`;

			$.each(returns, function (index, value) { 
				var type  = value.type,
					desc  = value.description,
					price = value.price?value.price:0,
					currency_id = value.currency_id ? value.currency_id : null,
					currency_symbol = value.transport_currency ? value.transport_currency.symbol : null
					mt    = index>0?'mt-2':'';
				html += `<div class="row ${mt} item-return">
                  <input type="hidden" class="returning" name="returning[]" data-type="${type}" data-description="${desc}" data-price="${price}" data-currency_id="${currency_id}" data-currency_symbol="${currency_symbol}"/>
                  <div class="col-md-2">
                    <div class="form-group">                                        
                      <select class="form-control select2 returning-type" id=returning-type"" name="returning_type[]" data-placeholder="Return" ${disabled}>
                        <option value="flight" ${type=='flight'?'selected':''}>Flight</option>
                        <option value="others" ${type=='others'?'selected':''}>Others</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">                      
                      <input type="text" class="form-control returning-description" id="returning-description" name="returning_description[]" placeholder="Enter description" value="${desc}" required ${readonly}/>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">                      
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <select name="return_currency_id" class="form-control select2 currency_id return-currency" aria-placeholder="Sym" data-currency_id="${currency_id}" data-currency_symbol="${currency_symbol}">
														<option>Sym</option>
                          </select>
                        </div>
                        <input type="text" class="form-control input-price text-right returning-price" id="returning-price" name="returning_price[]" placeholder="Enter price" maxlength="14" value="${price}" required ${readonly}>
                      </div>
                    </div>
                  </div>                  
				  ${remove}
                </div>`;
			});
			$('#form-return').append(html);
			initSelect2();
			$.each($('#form-return > .item-return').find('.return-currency'), function(index, value) {
				var currency_id			= $(this).attr('data-currency_id'),
						currency_symbol	= $(this).attr('data-currency_symbol');
				if (currency_id) {
					$(this).select2('trigger', 'select', {
						data: {
							id: currency_id,
							text: currency_symbol,
						}
					});
				}
			});
		}else{
			addReturn();
		}

		if(locations){
			var html   = '',				
				remove = `<div class="col-md-1">
								<div class="form-group" style="margin-top: 2px;">
								<button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
								</div>
							</div>`

			$.each(JSON.parse(locations), function (index, value) { 
				 var location = value.location;

				 html += `<div class="row item-location">
							<input type="hidden" class="location" value="${location}">
							<div class="col-md-11">
								<input type="text" class="form-control input-location" name="location[]" placeholder="Enter location" value="${location}">
							</div>                  
							<div class="col-md-1">
								<div class="form-group" style="margin-top: 2px;">
								<button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
								</div>
							</div>
						</div>`;
			});

			$('#form-location').append(html);
		}else{
			addLocation();
		}

		if(vehicles.length > 0){			
			var html   = '',
				remove = `<div class="col-md-1">
							<div class="form-group" style="margin-top: 30px;">
								<button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
							</div>
						  </div>`;			
			$.each(vehicles, function (index, value) { 
				var request_id 	  = value.request_vehicle_id,
					vehicle       = value.vehicle_name,
					description   = value.remarks,
					startRequest  = value.start_request,
					finishRequest = value.finish_request;								
				
				html += `<div class="row item-request-vehicle">        
                  <input type="hidden" class="vehicles" name="vehicles[]" value=""/>
                  <div class="col-12">
                    <div class="form-group row">
                      <div class="col-md-5">
                        <div class="row">
                          <label for="request-vehicle">Request Vehicle</label>
                          <select name="request_vehicle" class="form-control select2 request-vehicle" data-placeholder="Request Vehicle" data-request-id="${request_id}" data-vehicle="${vehicle}" data-description="${description}" data-start-request="${startRequest}" data-finish-request="${finishRequest}" ${disabled}></select>
                        </div>
                        <div class="row mt-2">
                          <label for="date-request-vehicle">Date Request Vehicle</label>
                          <div class="input-group">
                            <div class="input-group-append">
                              <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control date-request-vehicle" name="date_request_vehicle" placeholder="Date Request Vehicle" readonly>
                          </div>
                        </div>                                                
                      </div>                      
                      <div class="col-md-6">
                        <label for="remarks">Notes</label>
                        <textarea class="form-control remarks" name="remarks" rows="3" placeholder="Notes" disabled></textarea>
                      </div>
                      ${remove}
                    </div>
                  </div>
                </div>`;				
			});			
			$('#form-request-vehicle').html(html);		
			initRequestVehicle();
			initRemarks();
			$.each($('#form-request-vehicle > .item-request-vehicle').find('.request-vehicle'), function (index, value) { 
				 var request_id = $(this).attr('data-request-id'),
					 vehicle 	= $(this).attr('data-vehicle'),
					 notes 		= $(this).attr('data-description'),
					 startReq 	= $(this).attr('data-start-request'),
					 finishReq  = $(this).attr('data-finish-request');								
					 				
				$(this).select2('trigger','select',{
					data : {
						id 	 		: request_id,
						text 		: `${vehicle} | ${startReq} - ${finishReq}`,
						notes 		: `${notes}`,
						daterequest : `${startReq} - ${finishReq}`
					}
				});
			});			
		}

		if(lodgings.length > 0){
			var html   = '',
				remove = `<div class="col-md-1">
							<div class="form-group" style="margin-top: 2px;">
								<button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
							</div>
						  </div>`;
			$.each(lodgings, function (index, value) {
				var place = value.place,
					price = value.price?value.price:0,
					days  = value.night?value.night:1,
					currency_id = value.currency_id ? value.currency_id : null,
					currency_symbol = value.lodging_currency ? value.lodging_currency.symbol : null
					mt    = index>0?'mt-2':'';

				html  += `<div class="row ${mt} item-lodging">
                  <input type="hidden" class="lodging" name="lodging[]" data-place="${place}" data-price="${price}" data-days="${days}" data-currency_id="${currency_id}" data-currency_symbol="${currency_symbol}">
                  <div class="col-md-6">
                    <div class="form-group">                      
                    <input type="text" class="form-control place-lodging" id="place-loging" name="place_lodging" placeholder="Enter where lodging" value="${place}" required ${readonly}>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <div class="input-group-prepend">
												<select name="lodging_currency_id" class="form-control select2 currency_id lodging-currency" aria-placeholder="Sym" data-currency_id="${currency_id}" data-currency_symbol="${currency_symbol}">
													<option>Sym</option>
												</select>
                      </div>
                      <input type="text" class="form-control input-price text-right price-lodging" id="price-lodging" name="price_lodging" placeholder="Enter price" value="${price}" maxlength="14" required ${readonly}>
                    </div>
                  </div>
                  <div class="col-md-2">
                  <input type="number" class="form-control text-right days-lodging" id="days-lodging" name="days_lodging" placeholder="Enter qty" value="${days}" ${readonly}>
                  </div>     
				  ${remove}             
                </div>`;				
			});
			$('#form-lodging').append(html);
			initSelect2();
			$.each($('#form-lodging > .item-lodging').find('.lodging-currency'), function(index, value) {
				var currency_id			= $(this).attr('data-currency_id'),
						currency_symbol	= $(this).attr('data-currency_symbol');
				if (currency_id) {
					$(this).select2('trigger', 'select', {
						data: {
							id: currency_id,
							text: currency_symbol,
						}
					});
				}
			});
		}else{
			addLodging();
		}

		if(others.length > 0){
			var html 	= '',
				remove 	= `<div class="col-md-1">
							<div class="form-group" style="margin-top: 2px;">
								<button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
							</div>
						  </div>`;

			$.each(others, function (index, value) { 
				var desc   = value.description,
					price  = value.price?value.price:0,
					qty    = value.qty?value.qty:1,
					currency_id = value.currency_id ? value.currency_id : null,
					currency_symbol = value.others_currency ? value.others_currency.symbol : null
					mt     = index>0?'mt-2':'';

				 html += `<div class="row ${mt} item-others">
                  <input type="hidden" class="others-data" name="others_data[]" data-description="${desc}" data-price="${price}" data-qty="${qty}" data-currency_id="${currency_id}" data-currency_symbol="${currency_symbol}">
                  <div class="col-md-6">
                    <div class="form-group">
                      <input type="text" class="form-control others-description" id="others-description" name="others_description" placeholder="Enter description" value="${desc}" required ${readonly}>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <div class="input-group-prepend">
												<select name="others_currency_id" class="form-control select2 currency_id others-currency" aria-placeholder="Sym" data-currency_id="${currency_id}" data-currency_symbol="${currency_symbol}">
													<option>Sym</option>
												</select>
                      </div>
                      <input type="text" class="form-control input-price text-right others-price" id="others-price" name="others_price" placeholder="Enter price" value="${price}" maxlength="14" required ${readonly}>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <input type="number" class="form-control text-right mr-2 others-qty" id="others-qty" name="others_qty" placeholder="Enter qty" value="${qty}" required ${readonly}>
                  </div>
                  ${remove}
                </div>`;
			});
			$('#form-others').append(html);
			initSelect2();
			$.each($('#form-others > .item-others').find('.others-currency'), function(index, value) {
				var currency_id			= $(this).attr('data-currency_id'),
						currency_symbol	= $(this).attr('data-currency_symbol');
				if (currency_id) {
					$(this).select2('trigger', 'select', {
						data: {
							id: currency_id,
							text: currency_symbol,
						}
					});
				}
			});
		} else {
			addOthers();
		}
	}

	function initInputPrice() {
		$(".input-price").priceFormat({
			prefix: '',
			centsSeparator: ',',
			thousandsSeparator: '.',
			centsLimit: 0,
			clearOnEmpty: false
		});
	}

	function initRemarks() {
		$('.remarks').summernote({
			height: 100,
			toolbar: []
		});
		$('.remarks').next().find(".note-editable").attr("contenteditable", false);
	}

	function addDepart() {
		var html = `<div class="row mt-2 item-depart">
                  <input type="hidden" class="depart" name="depart[]" data-type="flight" data-description="" data-price="0" data-currency_id="" data-currency_symbol=""/>
                  <div class="col-md-2">
                    <div class="form-group">                                          
                      <select class="form-control select2 depart-type" name="depart_type" data-placeholder="Depart">                        
                        <option value="flight" selected>Flight</option>
                        <option value="others">Others</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">                      
                      <input type="text" name="depart_description" class="form-control depart-description" placeholder="Enter description" required/>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">                      
                      <div class="input-group">
                        <div class="input-group-prepend">
													<select name="depart_currency_id" class="form-control select2 currency_id depart-currency" aria-placeholder="Sym">
														<option value="">Sym</option>
													</select>
                        </div>
                        <input type="text" name="depart_price" class="form-control input-price text-right depart-price" placeholder="Enter price" value="0" maxlenght="14" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group" style="margin-top: 2px;">                                            
                    <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove"type="button"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>`;
		$('#form-depart').append(html);
		initSelect2();
		initInputPrice();
	}

	function addLodging() {
    var length = $('#form-lodging').find('.item-lodging').length,
      mt = '';
    if (length > 0) {
      mt = 'mt-2'
    }

    var html = `<div class="row ${mt} item-lodging">
                  <input type="hidden" class="lodging" name="lodging[]" data-place="" data-price="0" data-days="1" data-currency_id="" data-currency_symbol="">
                  <div class="col-md-6">
                    <div class="form-group">                      
                    <input type="text" class="form-control place-lodging" id="place-loging" name="place_lodging" placeholder="Enter where lodging" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <select name="lodging_currency_id" class="form-control select2 currency_id lodging-currency" aria-placeholder="Sym">
                          <option value="">Sym</option>
                        </select>
                      </div>
                      <input type="text" class="form-control input-price text-right price-lodging" id="price-lodging" name="price_lodging" placeholder="Enter price" value="0" maxlength="14" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                  <input type="number" class="form-control text-right days-lodging" id="days-lodging" name="days_lodging" placeholder="Enter qty" value="1">
                  </div>
                  <div class="col-md-1">
                    <div class="form-group" style="margin-top: 2px;">
                      <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>`;
    $('#form-lodging').append(html);
    initSelect2();
    initInputPrice();
  }

	function addReturn() {
		var html = `<div class="row mt-2 item-return">
                  <input type="hidden" class="returning" name="returning[]" data-type="others" data-description="" data-price="0" data-currency_id="" data-currency_symbol=""/>
                  <div class="col-md-2">
                    <div class="form-group">                                        
                      <select class="form-control select2 returning-type" name="returning_type" data-placeholder="Return">
                        <option value="flight">Flight</option>
                        <option value="others" selected>Others</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">                      
                      <input type="text" class="form-control returning-description" name="returning_description" placeholder="Enter description" required/>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">                      
                      <div class="input-group">
                        <div class="input-group-prepend">
													<select name="return_currency_id" class="form-control select2 currency_id return-currency" aria-placeholder="Sym">
														<option value="">Sym</option>
													</select>
                        </div>
                        <input type="text" class="form-control input-price text-right returning-price" name="returning_price" placeholder="Enter price" value="0" maxlength="14" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group" style="margin-top: 2px;">
                      <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>`;
		$('#form-return').append(html);
		initSelect2();
		initInputPrice();
	}

	const addLocation = () => {
		var length = $('#form-location').find('.item-location').length,
		mt = '';
		if (length > 0) {
		mt = 'mt-2'
		}

		var html = `<div class="row item-location ${mt}">
					<input type="hidden" class="location" value="">
					<div class="col-md-11">
						<input type="text" class="form-control input-location" name="location[]" placeholder="Enter location">
					</div>                  
					<div class="col-md-1">
						<div class="form-group" style="margin-top: 2px;">
						<button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
						</div>
					</div>
					</div>`;
		$('#form-location').append(html);
	}

	function addVehicle() {
		var length = $('#form-request-vehicle').find('.item-request-vehicle').length,
			mt = '';
		if (length > 0) {
			mt = 'mt-2'
		}

		var html = `<div class="row item-request-vehicle ${mt}">        
                  <input type="hidden" class="vehicles" name="vehicles[]" value=""/>
                  <div class="col-12">
                    <div class="form-group row">
                      <div class="col-md-5">
                        <div class="row">
                          <label for="request-vehicle">Request Vehicle</label>
                          <select name="request_vehicle" class="form-control select2 request-vehicle" data-placeholder="Request Vehicle"></select>
                        </div>
                        <div class="row mt-2">
                          <label for="date-request-vehicle">Date Request Vehicle</label>
                          <div class="input-group">
                            <div class="input-group-append">
                              <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control date-request-vehicle" name="date_request_vehicle" placeholder="Date Request Vehicle" readonly>
                          </div>
                        </div>                                                
                      </div>                      
                      <div class="col-md-6">
                        <label for="remarks">Notes</label>
                        <textarea class="form-control remarks" name="remarks" rows="3" placeholder="Notes" disabled></textarea>
                      </div>
                      <div class="col-md-1">
                        <div class="form-group" style="margin-top: 30px;">
                          <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>`;

		$('#form-request-vehicle').append(html);
		initRequestVehicle();
		initRemarks();

	}

	function othersLodging() {
		var html = `<div class="row mt-2 item-lodging">
                  <input type="hidden" class="lodging" name="lodging[]" data-place="" data-price="0" data-days="1" data-currency_id="" data-currency_symbol="">
                  <div class="col-md-6">
                    <div class="form-group">                      
                    <input type="text" class="form-control place-lodging" name="place_lodging" placeholder="Enter where lodging" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <select name="others_currency_id" class="form-control select2 currency_id others-currency" aria-placeholder="Sym">
                          <option value="">Sym</option>
                        </select>
                      </div>
                      <input type="text" class="form-control input-price text-right price-lodging" name="price_lodging" placeholder="Enter price" value="0" maxlength="14" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                  <input type="number" class="form-control text-right days-lodging" name="days_lodging" placeholder="Enter qty" value="1">
                  </div>
                  <div class="col-md-1">
                    <div class="form-group" style="margin-top: 2px;">
                      <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>`;
		$('#form-lodging').append(html);
		initInputPrice();
	}

	function addOthers() {
		var length = $('#form-others').find('.item-others').length,
			mt = '';
		if (length > 0) {
			mt = 'mt-2'
		}
		var html = `<div class="row ${mt} item-others">
                  <input type="hidden" class="others-data" name="others_data[]" data-description="" data-price="0" data-qty="1" data-currency_id="" data-currency_symbol="">
                  <div class="col-md-6">
                    <div class="form-group">
                      <input type="text" class="form-control others-description" name="others_description" placeholder="Enter description" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <select name="others_currency_id" class="form-control select2 currency_id others-currency" aria-placeholder="Sym">
                          <option value="">Sym</option>
                        </select>
                      </div>
                      <input type="text" class="form-control input-price text-right others-price" name="others_price" placeholder="Enter price" value="0" maxlength="14" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <input type="number" class="form-control text-right mr-2 others-qty" name="others_qty" placeholder="Enter qty" value="1" required>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group" style="margin-top: 2px;">
                      <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>`;
		$('#form-others').append(html);
		initSelect2();
		initInputPrice();
	}

	function sumRate() {
		var total 	  = [],
			departure = 0,
			returning = 0,
			lodging   = 0,
			others 	  = 0,
			rate 	  = intCurrency($('input[name=rate]').val());

		$.each(currencies, function(index, value) {
			total[value.id]	= 0;
			$.each($('#form-depart > .item-depart').find('input[class=depart]'), function(indexDepart, valueDepart) {
				var price = intCurrency($(this).attr('data-price'));
				var currency_id = $(this).attr('data-currency_id');
				if (value.id == currency_id) {
					total[value.id]	+= price;
				}
			});
	
			$.each($('#form-return > .item-return').find('input[class=returning]'), function(indexReturn, valueReturn) {
				var price = intCurrency($(this).attr('data-price'));
				var currency_id = $(this).attr('data-currency_id');
				if (value.id == currency_id) {
					total[value.id]	+= price;
				}
			});
	
			$.each($('#form-lodging > .item-lodging').find('input[class=lodging]'), function(indexLodging, valueLodging) {
				var price = intCurrency($(this).attr('data-price')),
					days  = intCurrency($(this).attr('data-days')),
					subs  = (price*days);	
				var currency_id = $(this).attr('data-currency_id');
				if (value.id == currency_id) {
					total[value.id]	+= subs;
				}
			});
	
			$.each($('#form-others > .item-others').find('input[class=others-data]'), function(indexOthers, valueOthers) {
				var price = intCurrency($(this).attr('data-price')),
					qty   = intCurrency($(this).attr('data-qty')),
					subs  = (price*qty);
				others += subs;
				var currency_id = $(this).attr('data-currency_id');
				if (value.id == currency_id) {
					total[value.id]	+= subs;
				}
			});
			$(`input[name=total_cost-${value.id}]`).val(total[value.id]);
		});

		// total = departure + returning + lodging + others + rate;

		initInputPrice();
	}

	function submitTest(status) {
		if (status) {
			$('input[name=status]').val(status);
		}

		$("form").first().trigger("submit");
	}
	function changeDateFormat(date) {
		var newdate = '';
		if (date) {
			var piece = date.split('/');
			newdate = piece[2] + '-' + piece[1] + '-' + piece[0];
		}

		return newdate;
	}
	function intCurrency(value) {
		var newCurrency = '';
		if(value){
			var currency = value.split('.');			
			$.each(currency, function (index,value) { 
				 newCurrency += value;
			});
		}
		
		return parseInt(newCurrency);
	}	
</script>
@endsection