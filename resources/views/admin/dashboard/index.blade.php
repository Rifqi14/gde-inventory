@extends('admin.layouts.app')

@section('stylesheets')
<style>
    .card {
        box-shadow: none !important;
    }

    .card-title {
        font-weight: 550;
        font-size: 1.3rem !important;
    }

    .card-header {
        border-bottom: 1.5px solid black;
    }

    #welcome {
        font-size: 4rem;
        font-weight: 500;
        margin-bottom: 0;
        color: #afb6b6 !important;
    }

    #fullname {
        font-size: 4rem;
        font-weight: 600;
        color: #030356;
        margin-top: -30px;
    }

    #ps {
        font-style: italic;
        font-size: 1.15rem !important;
    }

    #link {
        color: #030356 !important;
    }

    #logo {
        width: 25%;
    }

    #bgks {
        margin-top: 5rem;
    }
</style>
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
                            Have you got the User's Manual? You can always download it <a id="link" href="#"
                                target="_blank" download>here</a>
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