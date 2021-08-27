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
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('stylesheets')
<style>
    
</style>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <form class="form-horizontal no-margin" role="form" id="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr/>
                                <h5 class="text-md text-dark text-bold">Incident Information</h5>
                            </span>
                            <div class="form-group row mt-5">
                                <label class="col-md-4 col-xs-12 control-label" for="reporter">Reporter:</label>
                                <div class="col-sm-8 controls">
                                    <input type="text" class="form-control" id="reporter" name="reporter" placeholder="Reporter..." required readonly value="{{ $data->reporter }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="type">Incident Type:</label>
                                <div class="col-sm-8 controls">
                                    <select class="select2" id="type" name="type" data-placeholder="Pick an Incident Type" style="width: 100%;" required {{ ($type=='view')?'disabled':'' }} >
                                        <option></option>
                                        <option value="fatality" {{ ($data->type=='fatality')?'selected':'' }} >Fatality</option>
                                        <option value="major" {{ ($data->type=='major')?'selected':'' }}>Major</option>
                                        <option value="minor" {{ ($data->type=='minor')?'selected':'' }}>Minor</option>
                                        <option value="near miss" {{ ($data->type=='near miss')?'selected':'' }} >Near Miss</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="subject">Subject:</label>
                                <div class="col-sm-8 controls">
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject..." required value="{{ $data->subject }}" {{ ($type=='view')?'disabled':'' }} />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="loss_time">Loss Time:</label>
                                <div class="col-sm-8 controls">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="loss_time" name="loss_time" placeholder="Loss Time.." required value="{{ $data->loss_time }}" {{ ($type=='view')?'disabled':'' }} />
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="">Hours</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Attachment:</label>
                                @if($type=='edit')
                                <div id="form-file">
                                    <div class="row item-file">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="custom-file">   
                                                        <input type="file" class="custom-file-input" name="attachment[]" onchange="changePath(this)">
                                                        <label class="custom-file-label" for="exampleInputFile">Attach a File</label>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button id="add-file" data-urutan="1" type="button" class="btn btn-success btn-labeled legitRipple text-sm">
                                    <b><i class="fas fa-plus"></i></b>
                                    Add
                                    </button>
                                </div>
                                @else
                                <div class="row">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>File</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data->attachments as $key => $file)
                                            <tr>
                                                <td>
                                                    {{ "Attachment ".$key=$key+1 }}
                                                </td>
                                                <td>
                                                    <div class="input-group-append">
                                                        <a class="btn btn-sm text-bold text-sm bg-olive" target="_blank" href="{{ ($file->attachment)?url($file->attachment):"" }}" download>
                                                        <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                            <div class="form-group row">
                                <label for="remarks">Remarks:</label>
                                <textarea name="remarks" class="form-control summernote" rows="3">{{ $data->remarks }}</textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{ csrf_field() }}
                            @method('PUT')
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr/>
                            </span>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="subject">Number:</label>
                                <div class="col-sm-8 controls">
                                    <input type="text" class="form-control" id="number" placeholder="Number" readonly value="{{ $data->number }}" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="unit">Unit:</label>
                                <div class="col-sm-8 controls">
                                    <select class="select2" id="unit_id" name="unit" data-placeholder="Choose Unit" style="width: 100%;" required disabled>
                                    
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="date">Date:</label>
                                <div class="col-sm-8 controls">
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" class="form-control datepicker text-right" id="date" name="date" placeholder="Date" required value="{{ $data->date }}" {{ ($type=='view')?'disabled':'' }} />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="time">Time:</label>
                                <div class="col-sm-8 controls">
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id=""><i class="fa fa-clock"></i></span>
                                        </div>
                                        <input type="time" class="form-control text-right" id="time" name="time" placeholder="Time" required value="{{ $data->time }}" {{ ($type=='view')?'disabled':'' }} />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="area_id">Location:</label>
                                <div class="col-sm-8 controls">
                                    <select class="select2" name="area_id" id="area_id" data-placeholder="Choose Location" style="width: 100%;" required {{ ($type=='view')?'disabled':'' }} ></select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="witness">Witness:</label>
                                <div class="col-sm-8 controls">
                                    <select class="select2" name="witness[]" id="witness" data-placeholder="Choose Witness" style="width: 100%;" required multiple {{ ($type=='view')?'disabled':'' }} ></select>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-sm text-sm bg-yellow color-platte btn-flat legitRipple" onclick="comment(this)">
                                <b><i class="fas fa-eye"></i></b>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mb-4">
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        @if($type=='edit')
                            <button type="button" onclick="onSubmit('waiting')" class="btn btn-success btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-check-circle"></i></b>
                                Submit
                            </button>
                            <button type="button" onclick="onSubmit('draft')" class="btn bg-olive color-palette btn-labeled legitRipple text-sm">
                                <b><i class="fas fa-save"></i></b>
                                Save
                            </button>
                            <a href="{{ route('hseincident.index') }}" class="btn bg-maroon color-palette legitRipple text-sm">
                                <b><i class="fas fa-arrow-left"></i></b>
                            </a>
                        @else
                            @if($data->status == 'waiting')
                                <button type="button" onclick="onApproved('revise')" class="btn bg-maroon color-palette btn-labeled legitRipple text-sm" id="btn-submit">
                                    <b><i class="fas fa-edit"></i></b>
                                    Revise
                                </button>
                                <button type="button" onclick="onApproved('approved')" class="btn bg-success color-palette btn-labeled legitRipple text-sm">
                                    <b><i class="fas fa-save"></i></b>
                                    Approved
                                </button>
                                <button type="button" onclick="onApproved('declined')" class="btn bg-red color-palette btn-labeled legitRipple text-sm" id="btn-submit">
                                    <b><i class="fas fa-times"></i></b>
                                    Declined
                                </button>
                            @endif
                            <a href="{{ route('hseincident.index') }}" class="btn bg-maroon color-palette legitRipple text-sm" >
                            <b><i class="fas fa-arrow-left"></i></b>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<div class="modal fade" id="form-edit">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
			<!-- <h5 class="modal-title">Edit Status</h5> -->
			<h5 class="text-lg text-dark text-bold">Edit Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
			<form id="form-data-edit" class="custom-form-progress" enctype="multipart/form-data">
                {{ csrf_field() }}
				<input type="hidden" name="id" value="{{ $data->id }}">
				<input type="hidden" name="status" />
				<div class="form-group">
					<label>Comment:</label>
					<textarea name="comment" class="form control edit-txt" ></textarea>
				</div>
				<div class="form-group">
					<label>Attachment:</label>
					<div class="input-group">
						<div class="custom-file">   
							<input type="file" class="custom-file-input" name="attachment" onchange="changePath(this)">
							<label class="custom-file-label" for="exampleInputFile">Attach a File</label>
						</div>
						<div class="input-group-append">
							<span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
						</div>
					</div>
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

<div class="modal fade" id="view-comment">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="text-lg text-dark text-bold">Comment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				{!! $data->comment !!}
				
				@if($data->attachment)
				<a href="{{ ($data->attachment)?url($data->attachment):"" }}" target="_blank" class="btn bg-success color-palette btn-labeled legitRipple text-sm btn-sm">
					<b><i class="fas fa-download"></i></b>
					Attachment
				</a>
				@endif
			</div>
		</div>
	<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
@endsection

@section('scripts')
<script>
$(function () {
    var typ = '{{ $type }}'
    var witness = '{!! json_encode($data->witness) !!}';
        unit_id = {{ $site->id }};
        unit_name = '{{ $site->name }}';
    witness = JSON.parse(witness)
    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        timePicker: false,
        timePickerIncrement: 30,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });
    $('.select2').select2();
    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4',
        disabled: 'readonly'
    });

    $('#form').submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: '{{route('hseincident.update',['id' => $data->id])}}',
            method: 'post',
            data: new FormData($('#form')[0]),
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function () {
                blockMessage('body', 'Please Wait . . . ', '#fff');
            }
        }).done(function (response) {
            $('body').unblock();
            document.location = '{{ route('hseincident.index') }}';
            return;
        }).fail(function (response) {
            var response = response.responseJSON;
            $('body').unblock();
            document.location = '{{ route('hseincident.index') }}';
            return;
        });
    });

    $('#form-data-edit').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route('hseincident.index') }}/approved',
            method: 'post',
            data: new FormData($('#form-data-edit')[0]),
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function () {
                blockMessage('#form-edit', 'Please Wait . . . ', '#fff');
            }
        }).done(function (response) {
            $('#form-edit').unblock();
            document.location = '{{ route('hseincident.index') }}';
            return;
        }).fail(function (response) {
            var response = response.responseJSON;
            $('#form-edit').unblock();
            document.location = '{{ route('hseincident.index') }}';
            return;
        });
    });

    $("#area_id").select2({
        ajax: {
            url: '{{ route('area.select') }}',
            type: 'GET',
            dataType: 'json',
            data: function (params) {
                return {
                    name: params.term,
                    page: params.page,
                    limit: 30,
                    unit: $('#unit').val(),
                };
            },
            processResults: function (data, params) {
                var more = (params.page * 30) < data.total;
                var option = [];
                $.each(data.rows, function (index, item) {
                    option.push({
                        id: item.id,
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

    // $("#area_id").select2("trigger", "select", {
    //     data: { id: {{ $data->area_id }}, text: '{{ $data->name }}'}
	// });

    $("#unit_id").select2({
    	ajax: {
    		url: '{{ route("site.select") }}',
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
        data: { id: unit_id, text: unit_name }
    });

$("#witness").select2({
    ajax: {
        url: '{{ route("user.select") }}',
        type: 'GET',
        dataType: 'json',
        data: function (params) {
            return {
                name: params.term,
                page: params.page,
                limit: 30,
                exception_id:'{{ $userid }}',
            };
        },
        processResults: function (data, params) {
            var more = (params.page * 30) < data.total;
            var option = [];
            $.each(data.rows, function (index, item) {
                option.push({
                    id: item.id,
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

$.each(witness, function (key, val) {
    $("#witness").select2("trigger", "select", {
        data: { id: val.user_id, text: val.user.name }
    });
})

summernote('.summernote')
if (typ == 'view') {
    $('.summernote').summernote('disable')
} 
	});
$('#add-file').on('click', function (e) {
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
        $('#item-' + (no - 1)).find('.col-md-1').hide();
        $('#item-' + (no - 1)).find('.col-md-11').removeClass('col-md-11').addClass('col-md-12');
    }
    $(this).data('urutan', no);
    $('#form-file').append(html);
});

let removeFile = (me) => {
    var no = $('#add-file').data('urutan');

    if (no == $('.item-file').length) {
        $('#item-' + (no - 1)).find('.col-md-1').show();
        $('#item-' + (no - 1)).find('.col-md-12').removeClass('col-md-12').addClass('col-md-11');
        $('#add-file').data('urutan', (no - 1));
        $(me).parent().parent().remove();
    }
}
function changePath(that) {
    let filename = $(that).val()
    split = filename.split("\\")
    $(that).next().html(split[split.length - 1])
}
function changeUnit(me) {
    $("#area_id").select2("trigger", "select", {
        data: { id: '', text: '' }
    });
}
function summernote(cls) {
    $(cls).summernote({
        height: 200,
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
}

function onApproved(status) {
    $('#form-data-edit').find('input[name="status"]').val(status)
    if (status == 'revise') {
        var stat = 'Revise'
    } else if (status == 'approved') {
        var stat = 'Approved'
    } else {
        var stat = 'Declined'
    }
    $('#form-edit').find('.text-lg').html(stat)
    $('#form-edit').modal('toggle')
    summernote('.edit-txt')
}

function comment(that) {
    $('#view-comment').modal('toggle')
}

function onSubmit(status) {
    let data = $('#form')[0]
    let formData = new FormData(data)
    formData.append('status', status);

    Swal.fire({
        title: '<text style="font-size:24px;">Are you sure?<text>',
        html: '<text style="font-size:21px;font-weight:bold;">WARNING: This Process cannot be Undone<text>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3d9970',
        cancelButtonColor: '#d81b60',
        confirmButtonText: "<b>I AM SURE</b>",
        cancelButtonText: "<b>CANCEL</b>",
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '{{route('hseincident.update',['id' => $data->id])}}',
                method: 'post',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function () {
                    blockMessage('body', 'Please Wait . . . ', '#fff');
                }
            }).done(function (response) {
                $('body').unblock();
                document.location = '{{ route('hseincident.index') }}';
                return;
            }).fail(function (response) {
                var response = response.responseJSON;
                $('body').unblock();
                document.location = '{{ route('hseincident.index') }}';
                return;
            });
        }
    })
}
</script>
@endsection