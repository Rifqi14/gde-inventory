<!DOCTYPE html>
<html>
    <head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="robots" content="noindex,nofollow" />
        <base href="{{ url('admin') }}" />
        <title>@yield('title')</title>
        <link rel="apple-touch-icon" sizes="57x57" href="{{asset('assets/img/favicon.ico')}}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{asset('assets/img/favicon.ico')}}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{asset('assets/img/favicon.ico')}}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/favicon.ico')}}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{asset('assets/img/favicon.ico')}}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{asset('assets/img/favicon.ico')}}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{asset('assets/img/favicon.ico')}}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{asset('assets/img/favicon.ico')}}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/img/favicon.ico')}}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{asset('assets/img/favicon.ico')}}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/img/favicon.ico')}}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/img/favicon.ico')}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/img/favicon.ico')}}">
        <link rel="manifest" href="{{asset('assets/img/manifest.json')}}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{asset('assets/img/ms-icon-144x144.png')}}">
        <meta name="theme-color" content="#ffffff">
		<link href='{{asset('assets/css/adminlte.min.css')}}' rel='stylesheet' media='screen'>
        <link href='{{asset('assets/plugins/toastr/toastr.min.css')}}' rel='stylesheet' media='screen'>
        <link href='https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css' rel='stylesheet' media='screen'>
        <link href='{{asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}' rel='stylesheet' media='screen'>
        <link href='{{asset('assets/plugins/select2/css/select2.min.css')}}' rel='stylesheet' media='screen'>
        <link href='{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}' rel='stylesheet' media='screen'>
        <link href='{{asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}' rel='stylesheet' media='screen'>
        <link href='{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}' rel='stylesheet' media='screen'>
        <link href='{{asset('assets/plugins/summernote/summernote-bs4.css')}}' rel='stylesheet' media='screen'>
        <link href='https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css' rel='stylesheet' media='screen'>
        <link href='https://cdn.datatables.net/rowgroup/1.1.1/css/rowGroup.bootstrap4.min.css' rel='stylesheet' media='screen'>
        <link href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css' rel='stylesheet' media='screen'>
        <link href='https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css' rel='stylesheet' media='screen'>
        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700' rel='stylesheet' media='screen'>
        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css' rel='stylesheet' media='screen'>
        <link href='{{asset('assets/css/custom.css')}}' rel='stylesheet' media='screen'>
		@yield('stylesheets')
    </head>
    <body class=" layout-fixed lp text-sm">
        @yield('content')
        
		<script src='{{asset('assets/plugins/jquery/jquery.min.js')}}'></script>
        <script src='{{asset('assets/plugins/jquery-validation/jquery.validate.min.js')}}'></script>
        <script src='{{asset('assets/plugins/jquery-validation/additional-methods.min.js')}}'></script>
        <script src='{{asset('assets/plugins/toastr/toastr.min.js')}}'></script>
        <script src='{{asset('assets/js/sweetalert2.all.min.js')}}'></script>
        <script src='{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>
        <script src='{{asset('assets/plugins/select2/js/select2.full.min.js')}}'></script>
        <script src='{{asset('assets/plugins/moment/moment.min.js')}}'></script>
        <script src='{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}'></script>
        <script src='{{asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}'></script>
        <script src='{{asset('assets/plugins/summernote/summernote-bs4.min.js')}}'></script>
        <script src='{{asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}'></script>
        <script src='{{asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}'></script>
        <script src='{{asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}'></script>
        <script src='{{asset('assets/js/jquery.dataTables.min.js')}}'></script>
        <script src='{{asset('assets/js/dataTables.bootstrap4.min.js')}}'></script>
        <script src='{{asset('assets/js/dataTables.rowGroup.min.js')}}'></script>
        <script src='{{asset('assets/js/bootstrap-datepicker.min.js')}}'></script>
        <script src='{{asset('assets/plugins/price-format/jquery.priceformat.min.js')}}'></script>
        <script src='{{asset('assets/js/adminlte.min.js')}}'></script>
        <script src='{{asset('assets/js/demo.js')}}'></script>
        <script src='{{asset('assets/js/helper.js')}}'></script>
        <script src='{{asset('assets/js/jquery.blockUI.min.js')}}'></script>
        <script src='{{asset('assets/js/popper.min.js')}}'></script>
        <script src='{{asset('assets/js/library.js')}}'></script>
        <script src='{{asset('assets/js/accounting.min.js')}}'></script>
		@yield('scripts')
    </body>
</html>