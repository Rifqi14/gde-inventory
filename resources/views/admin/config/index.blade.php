@extends('admin.layouts.app')
@section('title', $menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 class="m-0 text-dark">
      {{ $menu_name }}
    </h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb">{{ $parent_name }}</li>
      <li class="breadcrumb">{{ $menu_name }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="section">
  <div class="container-fluid">
    <form action="{{ route('config.store') }}" class="form-horizontal no-margin" id="form" method="post">
      @csrf
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-dark text-bold text-md">{{ $menu_name }}</h5>
              </span>
              <div class="row mt-4">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="app_name" class="control-label">Application Name</label>
                    <input type="text" name="app_name" id="app_name" class="form-control" placeholder="Application Name" value="{{ config('configs.app_name') }}">
                  </div>
                  <div class="form-group">
                    <label for="app_copyright" class="control-label">Copyright</label>
                    <input type="text" name="app_copyright" id="app_copyright" class="form-control" placeholder="Copyright" value="{{ config('configs.app_copyright') }}">
                  </div>
                  <div class="form-group">
                    <label for="company_name" class="control-label">Company Name</label>
                    <input type="text" name="company_name" id="company_name" class="form-control" placeholder="Company Name" value="{{ config('configs.company_name') }}">
                  </div>
                  <div class="form-group">
                    <label for="company_email" class="control-label">Company Email</label>
                    <input type="text" name="company_email" id="company_email" class="form-control" placeholder="Company Email" value="{{ config('configs.company_email') }}">
                  </div>
                  <div class="form-group">
                    <label for="company_phone" class="control-label">Company Phone</label>
                    <input type="text" name="company_phone" id="company_phone" class="form-control" placeholder="Company Phone" value="{{ config('configs.company_phone') }}">
                  </div>
                  <div class="form-group">
                    <label for="company_address" class="control-label">Company Address</label>
                    <input type="text" name="company_address" id="company_address" class="form-control" placeholder="Company Address" value="{{ config('configs.company_address') }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="app_logo" class="control-label">Logo</label>
                    <div class="controls text-center upload-image">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="upload-preview-wrapper">
                            <a class="remove"><i class="fa fa-trash"></i></a>
                            <img src="{{ config('configs.app_logo') }}" />
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="upload-btn-wrapper">
                            <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Upload a Picture</a>
                            <input class="form-control" type="file" name="app_logo" id="app_logo" accept="image/*" value="{{ config('configs.app_logo') }}" />
                          </div>
                          <p class="text-sm text-muted">File must be no more than 2 MB</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="app_icon" class="control-label">Icon</label>
                    <div class="controls text-center upload-icon">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="upload-preview-wrapper">
                            <a class="remove"><i class="fa fa-trash"></i></a>
                            <img src="{{ config('configs.app_icon') }}" />
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="upload-btn-wrapper">
                            <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Upload a Picture</a>
                            <input class="form-control" type="file" name="app_icon" id="app_icon" accept="image/*" value="{{ config('configs.app_icon') }}" />
                          </div>
                          <p class="text-sm text-muted">File must be no more than 2 MB</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="login_background" class="control-label">Login Background</label>
                    <div class="controls text-center upload-login">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="upload-preview-wrapper">
                            <a class="remove"><i class="fa fa-trash"></i></a>
                            <img src="{{ config('configs.login_background') }}" />
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="upload-btn-wrapper">
                            <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Upload a Picture</a>
                            <input class="form-control" type="file" name="login_background" id="login_background" accept="image/*" value="{{ config('configs.login_background') }}" />
                          </div>
                          <p class="text-sm text-muted">File must be no more than 2 MB</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm"><b><i class="fas fa-save"></i></b> Save</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection

@section('scripts')
<script>
  $(function() {
        $("#form").validate({
            rules: {
                app_name: {
                    required: true,
                },
                app_copyright: {
                    required: true
                },
                company_name: {
                    required: true
                },
                company_email: {
                    required: true
                },
                company_phone: {
                    required: true
                },
                company_address: {
                    required: true
                }
            },
            messages: {
                app_name: {
                    required: "This field is required.",
                },
                app_copyright: {
                    required: "This field is required.",
                },
                company_name: {
                    required: "This field is required.",
                },
                company_email: {
                    required: "This field is required.",
                },
                company_phone: {
                    required: "This field is required.",
                },
                company_address: {
                    required: "This field is required.",
                }
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group .controls').append(error);

                if (element.is(':file')) {
                    error.insertAfter(element.parent().parent().parent());
                } else
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else
                if (element.attr('type') == 'checkbox') {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function() {
                var post = new FormData($('#form')[0]);


                $.ajax({
                    url: $('#form').attr('action'),
                    method: 'post',
                    data: post,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function() {
                        blockMessage('body', 'Please Wait . . . ', '#fff');
                    }
                }).done(function(response) {                    
                    $('body').unblock();
                    console.log({response :response});
                    if (response.status) {
                        toastr.success('Data has been saved.');
                        document.location = response.results;
                    } else {
                        toastr.warning(`${response.message}`);
                    }
                    return;
                }).fail(function(response) {
                    $('body').unblock();
                    var response = response.responseJSON,
                        message  = response.message?response.message:'Failed to insert data.';

                    toastr.warning(message);
                    console.log({
                        errorMessage: message
                    });
                });
            }
        });

        $('.upload-image').find('input:file').change(function() {
            var image = $(this).closest('.upload-image').find('img');
            var remove = $(this).closest('.upload-image').find('.remove');
            var fileReader = new FileReader();
            fileReader.onload = function() {
                var data = fileReader.result;
                image.attr('src', data);
                remove.css('display', 'block');                
            };            
            fileReader.readAsDataURL($(this).prop('files')[0]);
        });

        $('.upload-image').on('click', '.remove', function() {
            var image = $(this).closest('.upload-image').find('img');
            var file = $(this).closest('.upload-image').find('input:file');            
            file.val('');
            image.attr('src', "{{asset('assets/img/no-image.png')}}");
            $(this).css('display', 'none');
        });
        $('.upload-icon').find('input:file').change(function() {
            var image = $(this).closest('.upload-icon').find('img');
            var remove = $(this).closest('.upload-icon').find('.remove');
            var fileReader = new FileReader();
            fileReader.onload = function() {
                var data = fileReader.result;
                image.attr('src', data);
                remove.css('display', 'block');                
            };            
            fileReader.readAsDataURL($(this).prop('files')[0]);
        });

        $('.upload-icon').on('click', '.remove', function() {
            var image = $(this).closest('.upload-icon').find('img');
            var file = $(this).closest('.upload-icon').find('input:file');            
            file.val('');
            image.attr('src', "{{asset('assets/img/no-image.png')}}");
            $(this).css('display', 'none');
        });
        $('.upload-login').find('input:file').change(function() {
            var image = $(this).closest('.upload-login').find('img');
            var remove = $(this).closest('.upload-login').find('.remove');
            var fileReader = new FileReader();
            fileReader.onload = function() {
                var data = fileReader.result;
                image.attr('src', data);
                remove.css('display', 'block');                
            };            
            fileReader.readAsDataURL($(this).prop('files')[0]);
        });

        $('.upload-login').on('click', '.remove', function() {
            var image = $(this).closest('.upload-login').find('img');
            var file = $(this).closest('.upload-login').find('input:file');            
            file.val('');
            image.attr('src', "{{asset('assets/img/no-image.png')}}");
            $(this).css('display', 'none');
        });
    })
</script>
@endsection