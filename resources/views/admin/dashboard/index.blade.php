@extends('admin.layouts.app')

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <!-- <h5 class="m-0 ml-2 text-dark text-md breadcrumb">Grievance Redress &nbsp;<small class="font-uppercase"></small></h5> -->
        <h1 id="title-branch" class="m-0 text-dark">
            Dashboard				
        </h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Dashboard</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Per Tahun -->
                <div class="col-lg-12">
                    <div class="card dashboard-item-overview">
                        <div class="card-body">
                            <p id="welcome">WELCOME,</p>
                            <p id="fullname">DEVELOPMENT APPLICATION!</p>
                            <br>
                            <p id="ps" class="text-muted">
                                Thank you for accessing Project Management & Monitoring Systems.<br>
                                Have you got the User's Manual? You can always download it <a id="link" href="#" target="_blank" download>here</a>
                            </p>
                            <div id="bgks" class="text-right">
                                <img id="logo" src="{{asset('assets/logo.png')}}" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Per Tahun -->
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection

@section('script')

@endsection