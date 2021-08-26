@extends('admin.layouts.app')

@section('title')
Edit Budget Register
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
			<li class="breadcrumb-item active">Edit</li>
		</ol>
	</div>
</div>
@endsection

@section('content')
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<form role="form" id="form-data" action="{{route('budgetary.update', ['id' => $user->id])}}">
			<div class="row">
				<div class="col-md-8">
					<div class="card">
						<div class="card-body">
							<span class="title">
								<hr />
								<h5 class="text-md text-dark text-bold">Budgetary Information</h5>
							</span>
							{{ csrf_field() }}
							@method('PUT')
							<div class="form-group mt-4">
								<label>Budget Code:</label>
								<input type="text" name="name" placeholder="Budget Code..." class="form-control"
									value="{{$user->name}}" />
							</div>
							<div class="form-group mt-4">
								<label>Budget Description</label>
								<textarea class="form-control" rows="3" name="budget_description"
									placeholder="Budget Description...">{{$user->description}}</textarea>
							</div>
							<div class="form-group mt-4">
								<label>Budget Amount:</label>
								<div class="row">
									<div class="col-md-6">
										<div class="input-group">
											<input id="ba" type="text" class="form-control input-price2"
												placeholder="Budget Amount" value="{{$user->origin_amount}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group">
											<input type="text" class="form-control input-price"
												placeholder="Budget Amount" name="budget_amount"
												value="{{$user->amount}}" readonly>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>

					<div class="card">
						<div class="card-body">
							<div id="form-type">
								@foreach ($user->detail as $key => $detail)
								@php
								$num = $key +1;
								@endphp
								<div class="row item-type" {{($key>0)?'id="type-'.$num.'"':''}}>
									<div class="col-md-5">
										<div class="form-group">
											@if($key == 0)
											<label>Type:</label>
											@endif
											<select name="type[]" id="" class="form-control" placeholder="Choose a Type"
												required>
												<option value="">Choose a Type</option>
												<option value="adb" {{($detail->type == 'adb')?'selected':''}}>ADB
												</option>
												<option value="ctf" {{($detail->type == 'ctf')?'selected':''}}>CTF
												</option>
												<option value="pmn" {{($detail->type == 'pmn')?'selected':''}}>PMN
												</option>
												<option value="equity" {{($detail->type == 'equity')?'selected':''}}>
													Equity
												</option>
												<option value="unsigned"
													{{($detail->type == 'unsigned')?'selected':''}}>Unsigned
												</option>
											</select>
										</div>
									</div>
									<div class="col-md-{{($num>1)?(($num==count($user->detail))?'2':'3'):'3'}} percent">
										<div class="form-group">
											@if($key == 0)
											<label>Portion: </label>
											@endif
											<div class="input-group">
												<input type="text" name="weight[]" class="form-control text-right"
													placeholder="Enter progress..." autocomplete="off"
													value="{{$detail->weight}}" required onkeyup="getTotal(this)" />
												<div class="input-group-append">
													<span class="input-group-text text-bold text-sm">
														%
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											@if($key == 0)
											<label>Value:</label>
											@endif
											<div class="input-group">
												<input type="text" name="total[]"
													class="form-control text-right input-price"
													value="{{number_format($detail->total,'2','.','')}}" readonly />
											</div>
										</div>
									</div>
									@if ($num > 1)
									<div class="col-md-1"
										{{($num>1)?(($num==count($user->detail))?'':"style=display:none;"):"style=display:none;"}}>
										<button type="button" class="btn btn-transparent text-md"
											onclick="removeType(this)" data-urutan="{{$num}}"><i
												class="fas fa-trash text-maroon color-palette"></i></button>
									</div>
									@endif
								</div>
								@endforeach
							</div>
							<div class="text-right">
								<button id="add-type" data-urutan="{{(count($user->detail)>0)?count($user->detail):1}}"
									type="button" class="btn btn-success btn-labeled legitRipple text-sm">
									<b><i class="fas fa-plus"></i></b>
									Add
								</button>
							</div>
						</div>
					</div>
					<br>
				</div>
				<div class="col-md-4">
					<div class="card">
						<div class="card-body">
							<span class="title">
								<hr />
							</span>
							<div class="form-group mt-4">
								<label>Currency: </label>
								<select name="currency" id="" class="select2 form-control"
									data-placeholder="Choose a Currency">
									<option value="">Choose a Currency</option>
									<option value="rupiah" {{($user->currency == 'rupiah')?'selected':''}}>Rp</option>
									<option value="dollar" {{($user->currency == 'dollar')?'selected':''}}>$</option>
									<option value="yen" {{($user->currency == 'yen')?'selected':''}}>¥</option>
									<option value="euro" {{($user->currency == 'euro')?'selected':''}}>€</option>
								</select>
							</div>
							<div class="form-group mt-4">
								<label>Unit:</label>
								<select type="text" id="unit_id" class="form-control" name="site_id"
									data-placeholder="Choose Unit"></select>
							</div>
						</div>
					</div>
					<div class="text-right">
						<button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm">
							<b><i class="fas fa-save"></i></b>
							Save
						</button>
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
	$(function() {
		$('.select2').select2();
		$( "#unit_id" ).select2({
			ajax: {
				url: "{{ route('site.select') }}",
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
		});
		$("#unit_id").select2("trigger", "select", {
			data: {id:'{{$user->site_id}}', text:'{{$user->site_name}}'}
		});

		$('#form-data').submit(function(e) {
			e.preventDefault();

			$.ajax({
				url: $(this).attr('action'),
				method: 'post',
				data: new FormData($('#form-data')[0]),
				processData: false,
				contentType: false,
				dataType: 'json',
				beforeSend:function(){
					blockMessage('body', 'Please Wait . . . ', '#fff');
				}
			}).done(function(response) {
				$('body').unblock();
				window.location.href = "{{url('admin/budgetary')}}?unit="+ response.type;
				return;
			}).fail(function(response) {
				var response = response.responseJSON;
				$('body').unblock();
				window.location.href = "{{url('admin/budgetary')}}?unit="+ response.type;
				return;
			});
		});
		inputPrice()
	})

	$('#add-type').on('click', function(e) {
		e.preventDefault();
		var no = $(this).data('urutan') + 1,
			html = `<div class="row item-type" id="type-${no}">
				<div class="col-md-5">
					<div class="form-group">
					<select name="type[]" id="" class="select2 form-control" data-placeholder="Choose a Type">
						<option value="">-- Choose a Type</option>
						<option value="adb">ADB</option>
						<option value="ctf">CTF</option>
						<option value="pmn">PMN</option>
						<option value="equity" >Equity</option>
						<option value="unsigned" >Unsigned</option>
					</select>
					</div>
				</div>
				<div class="col-md-2 percent">
					<div class="form-group">
					<div class="input-group">
						<input type="text" name="weight[]" class="form-control text-right" placeholder="Enter Portion..." autocomplete="off" value="0" onkeyup="getTotal(this)"/>
						<div class="input-group-append">
						<span class="input-group-text text-bold text-sm">
							%
						</span>
						</div>
					</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					<div class="input-group">
						<input type="text" name="total[]" class="form-control input-price text-right" value="0" readonly/>
					</div>
					</div>
				</div>
				<div class="col-md-1">
					<button type="button" class="btn btn-transparent text-md" onclick="removeType(this)" data-urutan=${no}><i class="fas fa-trash text-maroon color-palette"></i></button>
				</div>
				</div>`;

		if (no > 2) {
			$('#type-'+(no-1)).find('.col-md-1').hide();
			$('#type-'+(no-1)).find('.col-md-2').removeClass('col-md-2').addClass('col-md-3');
		}
		$(this).data('urutan', no);
		$('#form-type').append(html);
		// $('.select2').select2();
		inputPrice()
	});

	function inputPrice(){
		$(".input-price").priceFormat({
			prefix: '',
			centsSeparator: ',',
			thousandsSeparator: '.',
			centsLimit: 2,
			clearOnEmpty: true
		});
	}

	let removeType = (me) => {
		var no = $('#add-type').data('urutan');

		if (no == $('.item-type').length) {
      $('#type-'+(no-1)).find('.col-md-1').show();
      $('#type-'+(no-1)).find('.col-md-3').removeClass('col-md-3').addClass('col-md-2');
      $('#add-type').data('urutan', (no-1));
      $(me).parent().parent().remove();
		}
	}

	function getTotal(that){
		let amount = $('input[name=budget_amount]').val()
			lol = (amount.replace(/\./g, '')).replace(/\,/g, '.')
			val = $(that).val()
			result = (lol*val)/100
			nesult = accounting.formatNumber(result, 2, ".",',')

		$(that).parents('.form-group').parent().next().find('input').val(nesult)
	}

	$('#ba').on('keyup', function() {
		let amount = $(this).val()
			lol = amount
			mount = accounting.formatNumber(lol, 2, ".",',')

		$(this).parents('.col-md-6').next().find('input').val(mount)
		$('.item-type').each(function(i, obj) {
		let val = $(obj).children('div.percent').find('input').val()
			result = (lol*val)/100
			nesult = accounting.formatNumber(result, 2, ".",',')
		
			$(obj).children('div.percent').next().find('input').val(nesult)
		});
	})
</script>
@endsection