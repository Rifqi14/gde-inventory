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
    .stat {
        margin-left: 5px;
        font-style: italic;
    }
</style>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            View Purchasing
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Procurment</li>
            <li class="breadcrumb-item">Purchasing</li>
            <li class="breadcrumb-item">View</li> 
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div id="accordion">
                    <div class="timeline">
                        @php $show = 0; @endphp
                        @foreach($purchasing->step as $key => $val)
                            @if(!$val->status)
                                @php $show = $show + 1; @endphp
                            @endif
                            <!-- timeline time label -->
                            <div class="time-label">
                                @if(!$val->status)
                                    <span class="bg-gray">{{ $val->fulldate }}</span>
                                @else
                                    @if($val->delay > 0)
                                        <span class="bg-red">{{ $val->fulldate }}</span>
                                    @else
                                        <span class="bg-success">{{ $val->fulldate }}</span>
                                    @endif
                                @endif
                                <text class="stat">{{ @$val->stat }}</text>
                            </div>
                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                            <div>
                                <div class="timeline-item">
                                    <h3 class="timeline-header text-bold">
                                        @if(($group_code == 'PMU-PRC'))
                                            <a data-toggle="collapse" data-parent="#accordion" href="@if($show==1) #step-{{ $val->id }} @else @if($val->status) #step-{{ $val->id }} @else javascript:void(0); @endif @endif" aria-expanded="true" class="text-gray">
                                                @if($purchasing->rule == 'adb') {{ $val->schedule_name }} @else {{ $val->schedule }} @endif
                                            </a>
                                        @else
                                            <a data-toggle="collapse" data-parent="#accordion" href="@if($val->status) #step-{{ $val->id }} @else javascript:void(0); @endif" aria-expanded="true" class="text-gray">
                                                @if($purchasing->rule == 'adb') {{ $val->schedule_name }} @else {{ $val->schedule }} @endif
                                            </a>
                                        @endif
                                    </h3>
                                    <div id="step-{{ $val->id }}" class="panel-collapse collapse in @if($group_code == 'PMU-PRC') @if($show==1) show @endif @endif">
                                        <div class="timeline-body card-body">
                                            @if(!$val->status)
                                                <form id="formStep-{{ $val->id }}" >
                                                    <input type="hidden" name="schedule_id" value="{{ $purchasing->id }}" >
                                                    <div id="form-step-{{ $val->id }}">
                                                        <div class="form-group row item-step-{{ $val->id }}">
                                                            <div class="col-sm-4">
                                                                <input type="text" name="notes[]" class="form-control" placeholder="Progress Remarks..." required >
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="input-group">
                                                                    <div class="custom-file">   
                                                                        <input type="file" class="custom-file-input" name="attach[]" accept="image/*" onchange="changePath(this)">
                                                                        <label class="custom-file-label" for="exampleInputFile">Attach file</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">
                                                                            <i class="far fa-calendar-alt"></i>
                                                                        </span>
                                                                    </div>
                                                                    <input type="text" name="notes_date[]" class="form-control date datepicker text-right" placeholder="Pick a date" required >
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-1">
                                                                <button id="add-notes-{{ $val->id }}" data-step="{{ $val->id }}" data-urutan="1" type="button" class="btn btn-success legitRipple text-sm">
                                                                    <b><i class="fas fa-plus"></i></b>
                                                                </button>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm">
                                                            <b><i class="fas fa-check-circle"></i></b>
                                                            Proceed
                                                        </button>
                                                    </div>
                                                </form>
                                            @else
                                                @foreach ($val->notes as $note)
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            {{ $note->notes }}
                                                        </div>
                                                        <div class="col-sm-5">
                                                            {{ $note->date }}
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <a href="{{ url('/admin/'.$note->file) }}" class="btn btn-sm btn-success legitRipple text-sm" download target="_blank">
                                                                <b><i class="fas fa-download"></i></b>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-body">
                    <div class="progress">
                        @php $lock = 0; @endphp
                        @foreach ($purchasing->step as $val)
                            @if($val->status)
                                @php $lock = $lock + 1; @endphp
                            @endif
                        @endforeach
                        @php $res = ($lock/$purchasing->total_step)*100; @endphp
                        @php $res = round($res, 2); @endphp
                        @php $style = "style='width:".$res."%'"; @endphp
                        <div class="progress-bar bg-teal" {{ $style }} >
                            <span>{{ $res }}% Complete</span>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <span class="title">
                            <hr/>
                            <h5 class="text-md text-dark text-bold">Procurement Information</h5>
                        </span>
                        <div class="mt-4" style="margin-top: 2rem !important;">
                            <label class="text-semibold text-sm">Number:</label>
                            <span class="pull-right-sm text-sm">{{ $purchasing->number }}</span>
                        </div>
                        <div class="">
                            <label class="text-semibold text-sm">Subject:</label>
                            <span class="pull-right-sm text-sm">{{ $purchasing->subject }}</span>
                        </div>
                        <div class="">
                            <label class="text-semibold text-sm">Rule:</label>
                            <span class="pull-right-sm text-sm">{{ strtoupper($purchasing->rule) }}</span>
                        </div>
                        <div class="">
                            <label class="text-semibold text-sm">Estimated Value:</label>
                            <span class="pull-right-sm text-sm">
                                @php
                                    if($purchasing->est_currency == 'rp'){
                                        $kode = 'Rp ';
                                    } else if($purchasing->est_currency == 'dollar'){
                                        $kode = '$ ';
                                    } else if($purchasing->est_currency == 'euro'){
                                        $kode = '€ ';
                                    }else {
                                        $kode = '¥ ';
                                    }    
                                @endphp
                                {{ $kode.number_format($purchasing->est_value,'0',',','.') }}
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="text-semibold text-sm">User:</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="text-right text-sm">
                                    @foreach ($purchasing->group as $key => $val)
                                        @php $key = $key+1; @endphp
                                        {{ $val->group_description }}
                                        @if($key <= count($purchasing->group))
                                            <br>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="text-semibold text-sm">Delay:</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="text-right text-sm">
                                    @php 
                                        $step = $purchasing->step;
                                        function cmp($a, $b) {
                                            return strcmp($b->delay, $a->delay);
                                        }
                                        usort($step, "cmp");
                                    @endphp
                                    @foreach ($step as $key => $val)
                                        @if($val->delay > 0)
                                            @if($purchasing->rule == 'adb'){{ $val->schedule_name }}@else{{ $val->schedule }}@endif - {{ $val->delay }} Days
                                            <br>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('purchasing.index') }}" class="btn bg-maroon color-palette legitRipple text-sm" >
                        <b><i class="fas fa-arrow-left"></i></b>
                    </a>
                </div>
            </div>
        </div>
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
        const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000
		});

        getDatePicker()
        $('.datepicker').val("")

        $('form[id^=formStep-]').on('submit', function(e) {
            e.preventDefault()
            let step_id = $(this).find('input[type=hidden]').val()

            $.ajax({
                url: '{{ route("purchasing.addnotes") }}',
                method: 'post',
                data: new FormData($(this)[0]),
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend:function(){
                    blockMessage('#step-'+step_id, 'Please Wait . . . ', '#fff');
                }
            }).done(function(response) {
                $('#step-'+step_id).unblock();
                if(response.success){
                    location.reload()
                } else {
                    Toast.fire({
                        type: 'error',
                        title: response.message
                    })
                }
                return;
            }).fail(function(response) {
                $('#step-'+step_id).unblock();
                Toast.fire({
                    type: 'error',
                    title: 'Error update data'
                })
                return;
            });
        })

    });

    $('button[id^=add-notes-]').on('click', function(e) {
		e.preventDefault();
        var step_id = $(this).data('step')
		var no = $(this).data('urutan') + 1,
			html = `<div class="form-group row item-step-${step_id}" id="notes-${step_id}-${no}">
                <div class="col-sm-4">
                  <input type="text" name="notes[]" class="form-control" placeholder="Progress Remarks..." required>
                </div>
                <div class="col-sm-3">
                  <div class="input-group">
                    <div class="custom-file">   
                      <input type="file" class="custom-file-input" name="attach[]" accept="image/*" onchange="changePath(this)">
                      <label class="custom-file-label" for="exampleInputFile">Attach file</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" name="notes_date[]" class="form-control date datepicker text-right" placeholder="Choose date" required >
                  </div>
                </div>
                <div class="col-sm-1">
                  <button type="button" class="btn btn-transparent text-md" onclick="removeStep(this)" data-step="${step_id}" data-urutan=${no}><i class="fas fa-trash text-maroon color-palette"></i></button>
                </div>
              </div>`;

		if (no > 2) {
            $('#notes-'+step_id+'-'+(no-1)).find('.col-sm-1').hide();
		}
		$(this).data('urutan', no);
		$('#form-step-'+step_id).append(html);
        getDatePicker()
        $('#notes-'+step_id+'-'+no).find('input.datepicker').val('');
	});

    let removeStep = (me) => {
    var step_id = $(me).data('step')
		var no = $('#add-notes-'+step_id).data('urutan');

		if (no == $('.item-step-'+step_id).length) {
		$('#notes-'+step_id+'-'+(no-1)).find('.col-sm-1').show();
		$('#add-notes-'+step_id).data('urutan', (no-1));
		$(me).parent().parent().remove();
		}
	}

  function getDatePicker(){
    $('.datepicker').daterangepicker({
      singleDatePicker: true,
      timePicker: false,
      timePickerIncrement: 30,
      locale: {
        format: 'DD/MM/YYYY'
      }
    });
  }

  function changePath(that) {
		let filename = $(that).val()	
		$(that).next().html('file')
    }

</script>
@endsection