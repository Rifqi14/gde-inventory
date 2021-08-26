@extends('admin.layouts.app')
@section('title', 'Role')
@section('stylesheets')
<style>
    input[type=checkbox]{
        z-index: 999;
        width: 22px;
        height: 22px;
        cursor: pointer;
    }
</style>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">
            View Role
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Role</li>
            <li class="breadcrumb-item">View</li> 
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
    <div class="container-fluid">
        <form id="form" role="form">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr />
                                <h5 class="text-md text-dark text-bold">Role Information</h5>
                            </span>
                            <div class="form-group row mt-5">
                                <label class="col-md-2 col-xs-12 control-label" for="code">Name <b class="text-danger">*</b></label>
                                <div class="col-sm-6 controls">
                                    {{ $role->code }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-xs-12 control-label" for="name">Display Name <b class="text-danger">*</b></label>
                                <div class="col-sm-6 controls">
                                    {{ $role->name }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <a href="{{route('role.index')}}" class="btn btn-xs btn-secondary color-palette btn-labeled legitRipple text-sm">
                                        <b><i class="fas fa-reply"></i></b>
                                        Back
                                    </a>
                                </div>
                            </div>

                            <span class="title mt-5">
                                <hr />
                                <h5 class="text-md text-dark text-bold">Access</h5>
                            </span>
                            <div class="form-group row mt-5">
                                <table class="table table-bordered table-striped" id="table-access">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center" width="10">#</th>
                                            <th width="150">Name Menu</th>
                                            <th width="50" style="text-align:center">Show</th>
                                            <th width="50" style="text-align:center">Create</th>
                                            <th width="50" style="text-align:center">Read</th>
                                            <th width="50" style="text-align:center">Update</th>
                                            <th width="50" style="text-align:center">Delete</th>
                                            <th width="50" style="text-align:center">Import</th>
                                            <th width="50" style="text-align:center">Export</th>
                                            <th width="50" style="text-align:center">Print</th>
                                            <th width="50" style="text-align:center">Approval</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $no = 1;
                                        @endphp
                                        @foreach($rolemenus as $rolemenu)
                                            <tr>
                                                <td style="text-align:center">{{$no++}}</td>
                                                <td>
                                                    @if($rolemenu->parent_id)
                                                        &nbsp;&nbsp;&nbsp;&nbsp;{{$rolemenu->menu_name}}
                                                    @else
                                                        <b>{{$rolemenu->menu_name}}</b>
                                                    @endif
                                                </td>
                                                <td style="text-align:center" >
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" value="{{$rolemenu->id}}" class="access" @if($rolemenu->role_access) checked @endif autocomplete="off" />
                                                        <label for="{{$rolemenu->id}}"></label>
                                                    </div>
                                                </td>
                                                @if($rolemenu->parent_id)
                                                <td style="text-align:center" >
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" value="{{$rolemenu->id}}" class="create" @if($rolemenu->create) checked @endif autocomplete="off" />
                                                        <label for="{{$rolemenu->id}}"></label>
                                                    </div>
                                                </td>
                                                <td style="text-align:center" >
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" value="{{$rolemenu->id}}" class="read" @if($rolemenu->read) checked @endif autocomplete="off" />
                                                        <label for="{{$rolemenu->id}}"></label>
                                                    </div>
                                                </td>
                                                <td style="text-align:center" >
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" value="{{$rolemenu->id}}" class="update" @if($rolemenu->update) checked @endif autocomplete="off" />
                                                        <label for="{{$rolemenu->id}}"></label>
                                                    </div>
                                                </td>
                                                <td style="text-align:center" >
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" value="{{$rolemenu->id}}" class="delete" @if($rolemenu->delete) checked @endif autocomplete="off" />
                                                        <label for="{{$rolemenu->id}}"></label>
                                                    </div>
                                                </td>
                                                <td style="text-align:center" >
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" value="{{$rolemenu->id}}" class="import" @if($rolemenu->import) checked @endif autocomplete="off" />
                                                        <label for="{{$rolemenu->id}}"></label>
                                                    </div>
                                                </td>
                                                <td style="text-align:center" >
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" value="{{$rolemenu->id}}" class="export" @if($rolemenu->export) checked @endif autocomplete="off" />
                                                        <label for="{{$rolemenu->id}}"></label>
                                                    </div>
                                                </td>
                                                <td style="text-align:center" >
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" value="{{$rolemenu->id}}" class="print" @if($rolemenu->print) checked @endif autocomplete="off" />
                                                        <label for="{{$rolemenu->id}}"></label>
                                                    </div>
                                                </td>
                                                <td style="text-align:center" >
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" value="{{$rolemenu->id}}" class="approval" @if($rolemenu->approval) checked @endif autocomplete="off" />
                                                        <label for="{{$rolemenu->id}}"></label>
                                                    </div>
                                                </td>
                                                @else
                                                <td colspan=""></td>
                                                <td colspan=""></td>
                                                <td colspan=""></td>
                                                <td colspan=""></td>
                                                <td colspan=""></td>
                                                <td colspan=""></td>
                                                <td colspan=""></td>
                                                <td colspan=""></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/jquery.inputmask.js') }}"></script>
<script>
    $(function(){
        $('body table tbody input[type=checkbox]').on('change', function () {
            var type = $(this).attr('class');
            $.ajax({
                url: "{{ route('rolemenu.update') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: this.value,
                    type: type,
                    checked: this.checked ? 1 : 0
                },
                type: 'POST',
                dataType: 'json',
                beforeSend: function () {
                    blockMessage('#table-access', 'Loading', '#fff');
                }
            }).done(function (response) {
                $('#table-access').unblock();
                if (response.status) {
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
                    toastr.success(response.message);
                }
                else {
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
            }).fail(function (response) {
                var response = response.responseJSON;
                $('#table-access').unblock();
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
        });
    });
</script>
@endsection