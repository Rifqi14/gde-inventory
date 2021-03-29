@extends('admin.layouts.app')

@section('stylesheets')

@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- /.content-header -->
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
</div>
@endsection

@section('script')

@endsection