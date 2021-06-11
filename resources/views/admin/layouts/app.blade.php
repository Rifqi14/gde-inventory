<!DOCTYPE html>
<html lang="en" class="">

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
    <link href='https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css' rel='stylesheet' media='screen'>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700' rel='stylesheet' media='screen'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css' rel='stylesheet' media='screen'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css' rel='stylesheet' media='screen'>
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
    <link href='{{asset('assets/css/custom.css')}}' rel='stylesheet' media='screen'>
    @yield('stylesheets')
</head>

<body class="sidebar-mini layout-fixed sidebar-collapse text-sm">
    <div id="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-blue navbar-dark navbar-red">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link linku" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- SEARCH FORM -->
            <!-- <form class="form-inline ml-3">
                    <div class="input-group input-group-sm">
                      <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                      <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                    </form> -->
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('admin/logout') }}"><i class="fas fa-power-off"></i></a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-2 sidebar-light-danger">
            <!-- Brand Logo -->
            <a href="{{ url('') }}" class="brand-link">
                <div class="row">
                    <div class="col-md-2">
                        <img src="{{ config('configs.app_icon') }}" alt="Project Management Unit Logo" class="brand-image img-circle elevation-1 ml-0 p-1" style="opacity: .8">
                    </div>
                    <div class="col-md-10">
                        <div class="development mt-2">
                            <h6 class="brand-text text-sm text-center text-semibold font-italic">{{ config('configs.app_name') }}
                                &reg;</h6>
                        </div>
                    </div>
                </div>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{asset(Auth::guard('admin')->user()->employees()->first()->photo ? Auth::guard('admin')->user()->employees()->first()->photo : 'assets/img/dev.jpg')}}" class="img-circle elevation-1 img-pp" alt="Development Application">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{Auth::guard('admin')->user()->name}}</a>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        {!!buildMenuAdmin($menuaccess)!!}
                    </ul>
                </nav>
            </div>
            <!-- /.sidebar -->
        </aside>
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="card col-md-12">
                            @yield('breadcrumb')
                        </div>
                    </div>
                </div>
            </section>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-right">
                                @yield('button')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @yield('content')
        </div>
        <!-- Content Wrapper. Contains page content -->
        <!-- /.content-wrapper -->
        <footer class="main-footer accent-danger">
            <strong>Copyright &copy; {{ date('Y') }} <a href="">Project Management Unit</a>.</strong>
            All rights reserved.
        </footer>
    </div>
    {{-- Script --}}
    <script src='{{asset('assets/plugins/jquery/jquery.min.js')}}'></script>
    <script src='{{asset('assets/plugins/jquery-validation/jquery.validate.js')}}'></script>
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
    <script src='{{asset('assets/plugins/bootbox/bootbox.min.js')}}'></script>
    <script src='{{asset('assets/js/accounting.min.js')}}'></script>
    <script>
        $(function() {
            $(".nav-sidebar").find("a[href='{{@$menu_active}}']").addClass("active");
            $(".nav-sidebar").find("a[href='{{@$menu_active}}']").closest('.has-treeview').addClass("menu-open");
            $(".nav-sidebar").find("a[href='{{@$menu_active}}']").parents('.has-treeview').addClass("menu-open");
            $(".nav-sidebar").find("a[href='{{@$menu_active}}']").parents('.has-treeview').children('a').addClass("active");
        });
    </script>
    <script type="text/javascript">
        var csfrData = {};
            csfrData['ci_csrf_token'] = '';
            $.ajaxSetup({
            	data: csfrData
            });
            function logout(){
            	bootbox.confirm({
            		buttons: {
            			confirm: {
            				label: 'Confirm',
            				className: 'bg-danger-400'
            			},
            		},
            		message:'Yakin Akan Keluar?',
            		callback: function(result) {
            			if(result) {
            				document.location = '{{ url('admin/login/logout') }}';
            			}
            		}
            	});
            	return false;
            }
    </script>
    <script>
        $(function(){
                $('.linku').css('display', 'none')
            })
    </script>
    <script src='https://code.highcharts.com/highcharts.js'></script>
    <script src='https://code.highcharts.com/gantt/modules/gantt.js'></script>
    <script src='https://code.highcharts.com/gantt/modules/exporting.js'></script>
    @yield('scripts')
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>