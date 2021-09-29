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
            <li class="breadcrumb-item">Create</li>
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
                                    <input type="text" class="form-control" id="reporter" name="reporter" placeholder="Reporter" required readonly value="{{ $realname }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="type">Incident Type:</label>
                                <div class="col-sm-8 controls">
                                    <select class="select2" id="type" name="type" data-placeholder="Pick an Incident Type" style="width: 100%;" required>
                                        <option></option>
                                        <option value="fatality">Fatality</option>
                                        <option value="major">Major</option>
                                        <option value="minor">Minor</option>
                                        <option value="near miss">Near Miss</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="subject">Subject:</label>
                                <div class="col-sm-8 controls">
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject..." required />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="loss_time">Loss Time:</label>
                                <div class="col-sm-8 controls">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="loss_time" name="loss_time" placeholder="Loss Time.." required />
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="">Hours</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Attachment:</label>
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
                            </div>
                            <div class="form-group row">
                                <label for="remarks">Remarks:</label>
                                <textarea name="remarks" class="form-control summernote" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{ csrf_field() }}
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr/>
                                <h5 class="text-md text-dark text-bold">General Information</h5>
                            </span>
                            <div class="form-group row mt-5">
                                <label class="col-md-4 col-xs-12 control-label" for="subject">Number:</label>
                                <div class="col-sm-8 controls">
                                    <input type="text" class="form-control" id="number" placeholder="Number" readonly value="{{ $number }}" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="unit">Unit:</label>
                                <div class="col-sm-8 controls">
                                    <select class="select2" id="unit_id" name="unit" data-placeholder="Choose Unit" style="width: 100%;" required>
                                    
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
                                        <input type="text" class="form-control datepicker text-right" id="date" name="date" placeholder="Date" required />
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
                                        <input type="time" class="form-control text-right" id="time" name="time" placeholder="Time" required />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="area_id">Location:</label>
                                <div class="col-sm-8 controls">
                                    <select class="select2" name="area_id" id="area_id" data-placeholder="Pick a Location" style="width: 100%;" required></select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-xs-12 control-label" for="witness">Witness:</label>
                                <div class="col-sm-8 controls">
                                    <select class="select2" name="witness[]" id="witness" data-placeholder="Tag or Enter a Witness" style="width: 100%;" required multiple></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mb-4">
                        <button type="button" onclick="onSubmit('waiting')" class="btn btn-success btn-labeled legitRipple text-sm">
                        <b><i class="fas fa-check-circle"></i></b>
                        Submit
                        </button>
                        <button type="button" onclick="onSubmit('draft')" class="btn bg-olive color-palette btn-labeled legitRipple text-sm">
                        <b><i class="fas fa-save"></i></b>
                        Save
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
$(function () {
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
            url: '{{ route('hseincident.store') }}',
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
            // document.location = '{{ route('hseincident.index') }}';
            return;
        }).fail(function (response) {
            var response = response.responseJSON;
            $('body').unblock();
            // document.location = '{{ route('hseincident.index') }}';
            return;
        });
    });

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

    $('.summernote').summernote({
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
									<label class="custom-file-label" for="exampleInputFile">Attach a File</label>
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

function onSubmit(status) {
    let data = $('#form')[0]
    let formData = new FormData(data)
    formData.append('status', status);

    Swal.fire({
        title: 'Are you sure?',
        text: "The data is corret ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3d9970',
        cancelButtonColor: '#d81b60',
        confirmButtonText: "Yes, i am sure"
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '{{ route('hseincident.store') }}',
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
                // document.location = '{{ route('hseincident.index') }}';
                return;
            }).fail(function (response) {
                var response = response.responseJSON;
                $('body').unblock();
                // document.location = '{{ route('hseincident.index') }}';
                return;
            });
        }
    })
}
</script>
@endsection