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
<link rel="stylesheet" href="{{ asset('assets/css/nestable/nestable.css') }}">
<style type="text/css">
    .dd-handle {
        display: block;
        height: 40px;
        padding: 8px 10px;
        /* color: #6a6c6f; */
        text-decoration: none;
        /* font-weight: 500; */
        border: 1px solid #ccc;
        background: #fafafa;
        border-radius: 3px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .dd-handle span {
        font-weight: 500;
    }

    .dd-item>button {
        margin: 10px 5px;
    }

    .item_actions {
        position: absolute;
        top: 5px;
        right: 10px;
    }
</style>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('activitie.index') }}" method="get">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <input type="hidden" name="loc" value="{{ $location }}">
                                                <select name="typ" class="custom-select form-control select2">
                                                    @foreach ($type as $val)
                                                        <option value="{{ $val->type }}" {{ ($val->type == $typ) ? 'selected' : '' }}>{{ ucwords($val->type) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="submit" class="btn btn-warning btn-md color-palette legitRipple">
                                                    <b><i class="fas fa-edit"></i></b>
                                                </button>
                                                <button type="button" class="btn btn-success btn-md color-palette legitRipple" data-toggle="modal" data-target="#create-type">
                                                    <b><i class="fas fa-plus"></i></b>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <div class="text-right">
                                    <a href="javascript:void(0)" onclick="create()" class="btn btn-success btn-sm color-palette btn-labeled legitRipple float-right" style="margin-left:5px;">
                                        <b><i class="fas fa-plus"></i></b>
                                        Add
                                    </a>
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#create-modal" class="btn btn-success btn-sm color-palette btn-labeled legitRipple float-right" style="margin-left:5px;">
                                        <b><i class="fas fa-file-excel"></i></b>
                                        Import
                                    </a>
                                    <a href="{{ route('activitie.index') }}/{{ ($location == 'dieng') ? '?loc=Patuha' : '' }}" class="btn bg-maroon btn-sm color-palette btn-labeled legitRipple float-right">
                                        <b><i class="fas fa-search"></i></b>
                                        {{ ($location == 'dieng') ? 'Patuha' : 'Dieng' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="dd">
                            {!! buildDD2($data) !!}
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ csrf_field() }}
                        <button type="button" class="btn bg-olive color-palette btn-labeled legitRipple text-sm updateorder">
                            <b><i class="fas fa-save"></i></b>
                            Save
                        </button>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- Row End -->
        </div>
    </div>
</section>

<div class="modal fade" id="create-type">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-data-type" class="custom-form-progress" action="{{ route('activitie.add.type') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Type:</label>
                                <input type="hidden" name="location" value="{{ $location }}">
                                <input type="text" name="type" class="form-control" placeholder="Input Type..." required>
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

<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal no-margin" id="form" action="#" method="post" />
                <div class="modal-header">
                    <h5 class="text-lg text-dark text-bold">Register an Activity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label>Activity Name: </label>
                        <input type="hidden" name="location" value="{{ $location }}">
                        <input type="hidden" name="type" value="{{ $typ }}">
                        <input type="text" class="form-control" id="activity" name="activity" placeholder="Enter Activity Name..." required />
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Start Date:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="start_date" class="form-control datepicker text-right" placeholder="Pick a Start Date" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Finish Date:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="finish_date" class="form-control datepicker text-right" placeholder="Pick a Finish Date" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-2">
                            <div class="form-group">
                                <label>W1:</label>
                                <input type="text" name="w1" class="form-control  text-right" placeholder="W1" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>W2:</label>
                                <input type="text" name="w2" class="form-control  text-right" placeholder="W2" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>W3:</label>
                                <input type="text" name="w3" class="form-control text-right" placeholder="W3" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>W4:</label>
                                <input type="text" name="w4" class="form-control  text-right" placeholder="W4" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>W5:</label>
                                <input type="text" name="w5" class="form-control  text-right" placeholder="W5" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" />
                    <input type="hidden" name="action" value="create" />
                    <button type="submit" class="btn btn-sm bg-olive color-palette btn-labeled legitRipple text-sm ">
                        <b><i class="fas fa-save"></i></b>
                        Save
                    </button>
                    <button type="button" data-dismiss="modal" class="btn btn-secondary btn-sm color-palette btn-labeled legitRipple ">
                        <b><i class="fa fa-times"></i></b>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="create-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Activity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-data-import" class="custom-form-progress" action="{{ route('activitie.import') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Type:</label>
                                <input type="hidden" name="location" value="{{ $location }}">
                                <select name="type" class="select2">
                                    @if(count($type) > 0)
                                        @foreach ($type as $val)
                                            <option value="{{ $val->type }}"> {{ ucwords($val->type) }} </option>
                                        @endforeach
                                    @else
                                        <option value="actual"> Actual </option>
                                        <option value="plan"> Plan </option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Data Excel:</label>
                                <select name="choose" class="select2">
                                    <option value="all"> All </option>
                                    <option value="value"> Value </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Attachment:</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="file" onchange="changePath(this)" required>
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
                                    </div>
                                </div>
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

@endsection

@section('scripts')
<script src="{{ asset('assets/js/nestable/jquery.nestable.js') }}"></script>
<script>
    $(function() {
        $('.dd').nestable({
            maxDepth: 5
        }).nestable('collapseAll');

        $('.select2').select2();
        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            timePicker: false,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        $('#form-data-import').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'post',
                data: new FormData($('#form-data-import')[0]),
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    blockMessage('#create-modal', 'Please Wait . . . ', '#fff');
                }
            }).done(function(response) {
                $('#create-modal').unblock();
                // console.log(response)
                window.location.href = '{{ route("activitie.index") }}/?loc={{ $location }}&typ={{ $typ }}';
                return;
            }).fail(function(response) {
                $('#create-modal').unblock();
                window.location.href = '{{ route("activitie.index") }}/?loc={{ $location }}&typ={{ $typ }}';
                return;
            });
        });

        $('#form-data-type').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'post',
                data: new FormData($('#form-data-type')[0]),
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    blockMessage('#create-type', 'Please Wait . . . ', '#fff');
                }
            }).done(function(response) {
                $('#create-type').unblock();
                // console.log(response)
                window.location.href = '{{ route("activitie.index") }}/?loc={{ $location }}&typ={{ $typ }}';
                return;
            }).fail(function(response) {
                $('#create-type').unblock();
                window.location.href = '{{ route("activitie.index") }}/?loc={{ $location }}&typ={{ $typ }}';
                return;
            });
        });

        $("#form").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            focusInvalid: false,
            highlight: function(e) {
                $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
            },

            success: function(e) {
                $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
                $(e).remove();
            },
            submitHandler: function() {
                var url = $('#form').find('input[name=action]').val() == 'create' ? '{{ route("activitie.store") }}' : '{{ route("activitie.updateact") }}';
                let data = $('#form')[0]
                let formData = new FormData(data)
                formData.append('_token', "{{ csrf_token() }}");
                $.ajax({
                    url: url,
                    dataType: 'json',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        if (result.success) {
                            if ($('#form').find('input[name=action]').val() == 'create') {
                                console.log("ok");
                                setTimeout(function() {
                                    waitingDialog.hide();
                                }, 1000);
                                $('#create').modal('hide');
                                $('.dd-list').append(`
                            <li class="dd-item" data-id="${result.data.id}">
                                <div class="item_actions">
                                    <a class="btn btn-sm text-blue-800 edit" data-id="${result.data.id}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a class="btn btn-sm delete text-danger-400" data-id="${result.data.id}">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                                <div class="dd-handle">
                                    <span>${result.data.activity}</span> 
                                </div> 
                            </li>`);
                            } else {
                                setTimeout(function() {
                                    waitingDialog.hide();
                                }, 1000);
                                $('#create').modal('hide');
                                $('li[data-id=' + $('#form input[name=id]').val() + '] > .dd-handle > span').html(result.data.activity);
                            }

                        } else {
                            setTimeout(function() {
                                waitingDialog.hide();
                            }, 1000);
                            $.gritter.add({
                                title: 'Warning',
                                text: 'Gagal menyimpan data',
                            });
                            $('.ladda-button').ladda().ladda('stop');
                        }
                    },
                    beforeSend: function() {
                        waitingDialog.show('Loading');
                    }
                });
            }
        });

        $(document).on('click', '.edit', function() {
            $('#form')[0].reset();
            $('#create .text-dark').html('Edit an Activity');
            $('#create').modal('show');
            $.ajax({
                url: '{{ route("activitie.get.detail") }}',
                method: 'post',
                dataType: 'json',
                data: {
                    id: $(this).data('id'),
                    _token: "{{ csrf_token() }}"
                },
                success: function(result) {
                    if (result.success) {
                        $('#form input[name=activity]').attr('value', result.data.activity);
                        $('#form input[name=start_date]').attr('value', result.data.start_date);
                        $('#form input[name=finish_date]').attr('value', result.data.finish_date);
                        $('#form input[name=w1]').attr('value', result.data.w1);
                        $('#form input[name=w2]').attr('value', result.data.w2);
                        $('#form input[name=w3]').attr('value', result.data.w3);
                        $('#form input[name=w4]').attr('value', result.data.w4);
                        $('#form input[name=w5]').attr('value', result.data.w5);
                        $('#form input[name=action]').attr('value', 'update');
                        $('#form input[name=id]').attr('value', result.data.id);
                    }
                }
            })
        })

        $(document).on('click', '.delete', function() {
            var id = $(this).data('id');
            bootbox.confirm({
                buttons: {
                    confirm: {
                        label: 'Confirm',
                        className: `btn btn-sm bg-navy`
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-secondary btn-sm'
                    },
                },
                title: 'Delete Activity',
                message: 'Are you sure want to delete this activity?',
                callback: function(result) {

                    if (result) {
                        waitingDialog.show('Delete Data');
                        var data = {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        };
                        $.ajax({
                            url: '{{ route("activitie.destroyact") }}',
                            dataType: 'json',
                            data: data,
                            type: 'POST',
                            success: function(response) {
                                if (response.success) {
                                    setTimeout(function() {
                                        waitingDialog.hide();
                                    }, 1000);
                                    $('li[data-id=' + id + ']').remove();
                                } else {
                                    //alert("Can't Delete This Group");
                                    setTimeout(function() {
                                        waitingDialog.hide();
                                    }, 1000);
                                    $.gritter.add({
                                        title: 'Warning',
                                        text: 'Failed deleting menu',
                                    });
                                    $('.ladda-button').ladda().ladda('stop');

                                }
                            }
                        });
                    }
                }
            });
        })

        $('.updateorder').on('click', function() {
            bootbox.confirm({
                buttons: {
                    confirm: {
                        label: 'Confirm',
                        className: 'btn btn-sm bg-navy'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-secondary btn-sm'
                    },
                },
                title: 'Save Activity',
                message: 'Are you sure ?',
                callback: function(result) {

                    if (result) {
                        waitingDialog.show('Loading');
                        var data = {
                            order: JSON.stringify($('.dd').nestable('serialize')),
                            location: '{{ $location }}',
                            _token: "{{ csrf_token() }}"
                        };
                        $.ajax({
                            url: '{{ route("activitie.order") }}',
                            dataType: 'json',
                            data: data,
                            type: 'POST',
                            success: function(response) {
                                if (response.success) {
                                    setTimeout(function() {
                                        waitingDialog.hide();
                                    }, 1000);
                                    $.gritter.add({
                                        title: 'Success',
                                        text: 'Activity Edited',
                                    });
                                    $('.ladda-button').ladda().ladda('stop');
                                    location.reload()
                                } else {
                                    //alert("Can't Delete This Group");
                                    setTimeout(function() {
                                        waitingDialog.hide();
                                    }, 1000);
                                    $.gritter.add({
                                        title: 'Warning',
                                        text: 'Failed re-ordering menu',
                                    });
                                }
                            }
                        });
                    }
                }
            });
        });
    });

    function create() {
        $('#form')[0].reset();
        $('#form input').val('');
        $('#form input[name=action]').attr('value', 'create');
        $('#form input[name=location]').attr('value', '{{ $location }}');
        $('#form input[name=type]').attr('value', '{{ $typ }}');
        $('#create .text-dark').html('Register an Activity');
        $('#create').modal('show');
    }

    function changePath(that) {
        let filename = $(that).val()
        $(that).next().html(filename)
    }
</script>
@endsection