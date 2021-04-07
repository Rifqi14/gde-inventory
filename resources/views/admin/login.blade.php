@extends('admin.layouts.blank')

@section('title')
Login Application
@endsection

@section('stylesheets')
<style>
    #title-branch {
        font-size: 4rem;
        font-weight: 600;
        text-transform: none;
        color: #030356 !important;
    }
    #bg-kiri {
        background-color: #030356;
    }
    .ml-100 {
        margin-left:100px;;
    }
    .lp-height {
        height: 100vh;
    }
    .txt {
        position: relative;
        top: 40%;
        transform: translateY(-50%);
        margin-left: 20px;
    }
    .txt p {
        color: #fff;
        font-size: 2rem;
        font-weight: 600;
    }
    #bgks-kiri {
        height: 100%;
        position: relative;
        top: 0px;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper ml-0">
    <section class="content">
        <div class="row lp-height">
            <div id="bg-kiri" class="col-7">
                <div id="bgks-kiri">
                    <div class="txt">
                        <p>Project Management.</p>
                        <p>Documentation.</p>
                        <p>All in One Place.</p>
                        <p>One System.</p>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="text-right">
                    <a href="{{ url('/') }}"><img src="{{asset('assets/img/pmu.png')}}" width="100" height="100" /></a>
                </div>
                <div class="login-box mt-5 ml-100">
                    <!-- /.login-logo -->
                    <div class="card login-card mt-5">
                        <div class="card-body login-card-body">
                            <h1 id="title-branch" >Log In</h1>
                            <form action="#" id="signin-form" novalidate="novalidate" class="mt-5">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input type="text" placeholder="Username" required="" value="" name="username" id="username"
                                            class="form-control">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-envelope"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input type="password" placeholder="Password" required="" value="" name="password" id="password"
                                            class="form-control">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <button class="btn btn-danger text-uppercase btn-block btn-with-loader text-bold mb-1" data-style="slide-down">
                                        <i class="fas fa-spinner"></i>
                                        <span>
                                        Login
                                        </span>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ url('admin/registration') }}" class="btn bg-gray color-platte text-uppercase btn-block btn-with-loader text-bold mb-1" data-style="slide-down">
                                        <span>
                                        Register
                                        </span>
                                        </a>
                                    </div>
                                    <!-- /.col -->
                                </div>
                            </form>
                        </div>
                        <!-- /.login-card-body -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    var base_url = "{{ url('admin') }}";
    var image_url = "{{ url('assets/img') }}";
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
    				document.location = '{{ url('admin') }}/login/logout';
    			}
    		}
    	});
    	return false;
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
    	$.validator.setDefaults({
    		submitHandler: function () {
           $.ajax({
               url: base_url + "/login",
               dataType: "json",
               type: "GET",
               data: $("#signin-form").serialize(),
               beforeSend: function () {
    				// $("#signin-form .btn-with-loader").addClass("loading");
               },
               success: function (e) {
                   $("#signin-form .btn-with-loader").removeClass("loading");
                   if (e.success) {
                       document.location = base_url + e.redirect;
                   } else {
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
                     toastr.warning("Login Failed.");
                   }
               },
               error: function (e) {
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
                 toastr.warning("Login Failed.");
               }
           });
    		}
    	});
    	$('#signin-form').validate({
    		rules: {
    			username: {
    				required: true,
    			},
    			password: {
    				required: true,
    			}
    		},
    		messages: {
    			username: {
    				required: "This field is required.",
    			},
    			password: {
    				required: "This field is required.",
    			}
    		},
    		errorElement: 'span',
    		errorPlacement: function (error, element) {
    			error.addClass('invalid-feedback');
    			element.closest('.form-group').append(error);
    		},
    		highlight: function (element, errorClass, validClass) {
    			$(element).addClass('is-invalid');
    		},
    		unhighlight: function (element, errorClass, validClass) {
    			$(element).removeClass('is-invalid');
    		}
    	});
    });
</script>
<script src='{{asset('assets/js/main/login.js')}}'></script>
@endsection


