@extends('admin.layouts.app')
@section('title', @$menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">
      Edit {{ @$menu_name }}
    </h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">{{ @$parent_name }}</li>
      <li class="breadcrumb-item">{{ @$menu_name }}</li>
      <li class="breadcrumb-item">Edit</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<section class="content" id="content">
  <div class="container-fluid">
    <form action="{{ route('salaryreport.update', ['id' => $salary->id]) }}" role="form" enctype="multipart/form-data" method="post" autocomplete="off" id="form">
      @csrf
      @method('PUT')
      <input type="hidden" name="status">
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">Employee Data</h5>
              </span>
              <div class="mt-5"></div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="employee_name" class="control-label">Employee Name</label>
                    <input type="text" name="employee_name" id="employee_name" class="form-control" disabled value="{{ $salary->employee->name }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="role_name" class="control-label">Role</label>
                    <input type="text" name="role_name" id="role_name" class="form-control" disabled value="{{ $salary->employee->user->roles ? $salary->employee->user->roles->first()->name : '-' }}">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">Others</h5>
              </span>
              <div class="mt-5"></div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="period" class="control-label">Period</label>
                    <input type="text" name="period" id="period" class="form-control" disabled value="{{ $period }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label for="status" class="control-label col-md-12">Status</label>
                    <div class="col-md-12">
                      @switch($salary->status)
                      @case('REJECTED')
                      <span class="badge badge-danger">Rejected</span>
                      @break
                      @case('WAITING')
                      <span class="badge badge-warning">Waiting Approval</span>
                      @break
                      @case('APPROVED')
                      <span class="badge badge-success">Approved</span>
                      @break
                      @default
                      <span class="badge badge-secondary">Draft</span>
                      @endswitch
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-body text-right table-responsive">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">Additional Items</h5>
              </span>
              @if (in_array('create', $actionmenu) && $salary->status == "WAITING" || $salary->status == 'DRAFT')
              <button type="button" onclick="addDetail(1)" class="btn btn-success btn-flat legitRipple btn-sm text-sm">
                <b><i class="fas fa-plus"></i></b>
              </button>
              @endif
              <div class="mt-2"></div>
              <table id="table-additional" class="table table-striped datatable">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="60%">Item</th>
                    <th width="30%">Total</th>
                    <th width="5%">#</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-body text-right table-responsive">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">Deduction Items</h5>
              </span>
              @if (in_array('create', $actionmenu) && $salary->status == "WAITING" || $salary->status == 'DRAFT')
              <button type="button" onclick="addDetail(2)" class="btn btn-success btn-flat legitRipple btn-sm text-sm">
                <b><i class="fas fa-plus"></i></b>
              </button>
              @endif
              <div class="mt-2"></div>
              <table id="table-deduction" class="table table-striped datatable">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="60%">Item</th>
                    <th width="30%">Total</th>
                    <th width="5%">#</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="card-footer text-right">
              @if (in_array('approval', $actionmenu) && ($salary->employee->user->spv_id == Auth::guard('admin')->user()->id) && $salary->status == "WAITING" || $salary->status == 'DRAFT')
              <button type="button" onclick="submitTest(`approved`)" class="btn btn-success btn-labeled legitRipple btn-sm text-sm" data-type="approved">
                <b><i class="fas fa-check-circle"></i></b>
                Approved
              </button>
              <button type="button" onclick="submitTest(`rejected`)" class="btn bg-maroon btn-labeled legitRipple btn-sm text-sm" data-type="approved">
                <b><i class="fas fa-times"></i></b>
                Rejected
              </button>
              <button type="button" onclick="submitTest(`waiting`)" class="btn btn-success btn-labeled legitRipple btn-sm text-sm" data-type="approved">
                <b><i class="fas fa-save"></i></b>
                Save
              </button>
              @endif
              <a href="{{ route('salaryreport.index') }}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                <b><i class="fas fa-times"></i></b> Cancel
              </a>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
<div class="modal fade" id="add-item" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="edit-modal" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Salary Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('salarydetail.store') }}" id="form-edit" method="post">
          <div class="container-fluid">
            @csrf
            <input type="hidden" name="_method">
            <input type="hidden" name="type">
            <input type="hidden" name="salary_report_id" value="{{ $salary->id }}">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="item" class="control-label">Item</label>
                  <input type="text" name="item" id="item" class="form-control">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="currency_id" class="control-label">Currency</label>
                  <select name="currency_id" id="currency_id" class="form-control select2" aria-placeholder="Choose currency"></select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="total" class="control-label">Total</label>
                  <input type="text" name="total" id="total" class="form-control input-price text-right">
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <button type="submit" id="submit-edit" form="form-edit" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple">
          <b><i class="fas fa-save"></i></b>
          Save
        </button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="add-reason" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="reason-modal" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Reason</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('salaryreport.update', ['id' => $salary->id]) }}" id="form-reason" method="post">
          <div class="container-fluid">
            @csrf
            @method('PUT')
            <input type="hidden" name="status">
            <input type="hidden" name="salary_report_id" value="{{ $salary->id }}">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="request_reason">Reason</label>
                  <textarea class="form-control summernote" name="request_reason" id="request_reason" rows="4" placeholder="Request Reason..."></textarea>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-right">
        <button type="submit" id="submit-reason" form="form-reason" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple">
          <b><i class="fas fa-save"></i></b>
          Save
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  var status     = `{{ $salary->status }}`;
  var actionmenu = @json($actionmenu);

  const addDetail = (type) => {
    var typeString = type == 1 ? 'Additional' : 'Deduction';
    $('#add-item').modal('show');
    $('#form-edit')[0].reset();
    $('#form-edit').attr('action', `{{ route('salarydetail.store') }}`);
    $('#add-item input[name=_method]').attr('value', '');
    $('#add-item .modal-title').text(`Add Salary Item ${typeString}`);
    $('#add-item input[name=type]').val(type);
  }

  const initInputPrice = () => {
    $('.input-price').priceFormat({
      prefix: '',
      centsSeparator: ',',
      thousandsSeparator: '.',
      centsLimit: 0,
      clearOnEmpty: false
    });
  }

  const formatCountry = (currency) => {
    if (!currency.code) { return currency.text; }
      var currencies = $(
      '<span class="flag-icon flag-icon-'+ currency.code.toLowerCase() +' flag-icon-squared"></span>' +
      '&nbsp;&nbsp;&nbsp;<span class="flag-text">'+ currency.text+"</span>"
    );
    return currencies;
  }

  const edit = (id) => {
    var data = {
      _token: "{{ csrf_token() }}",
    };
    $.ajax({
      url: `{{ route('salarydetail.index') }}/${id}/edit`,
      method: 'get',
      data: data,
      dataType: 'json',
      beforeSend: function() {
        blockMessage('body', 'Please Wait ...', '#fff');
      }
    }).done(function(response) {
      $('body').unblock();
      if (response.status) {
        var typeString = response.data.type == 1 ? 'Additional' : 'Deduction';
        $('#add-item').modal('show');
        $('#form-edit')[0].reset();
        $('#add-item .modal-title').text(`Add Salary Item ${typeString}`);
        $('#add-item input[name=_method]').attr('value', 'PUT');
        $('#add-item input[name=type]').val(response.data.type);
        $('#add-item input[name=item]').val(response.data.description);
        $('#add-item input[name=total]').val(response.data.total);
        if (response.data.currency_id) {
          $('#add-item #currency_id').select2('trigger', 'select', {
            data: {id: response.data.currencies.id, text: response.data.currencies.symbol}
          });
        }
        $('#form-edit').attr('action', `{{ url('admin/salarydetail/') }}/${response.data.id}`);
        initInputPrice();
      } else {
        toastr.warning(response.message);
      }
      return;
    }).fail(function(response) {
      $('body').unblock();
      var response  = response.responseJSON,
          message   = response.message ? response.message : 'Failed to insert data';

      toastr.error(message);
      console.log({
        errorMessage: message
      });
    })
  }

  const destroy = (id) => {
    bootbox.confirm({
      buttons: {
        confirm: {
          label: '<i class="fa fa-check"></i>',
          className: 'btn-primary btn-sm',
        },
        cancel: {
          label: '<i class="fa fa-undo"></i>',
          className: 'btn-default btn-sm'
        },
      },
      title: 'Delete data?',
      message: 'Are you sure want to delete this data?',
      callback: function (result) {
        if (result) {
          var data = {
            _token: `{{ csrf_token() }}`
          };
          $.ajax({
            url: `{{ route('salarydetail.index') }}/${id}`,
            dataType: 'json',
            data: data,
            type: 'DELETE',
            beforeSend: function () {
              blockMessage('#content', 'Loading...', '#fff');
            }
          }).done(function (response) {
            $('#content').unblock();
            if (response.status) {
              toastr.success(response.message);
              dataTableAdditional.ajax.reload(null, false);
              dataTableDeduction.ajax.reload(null, false);
            } else {
              toastr.warning(response.message);
            }
          }).fail(function (response) {
            var response = response.responseJSON;
            $('#content').unblock();
            toastr.error(response.message);
          });
        }
      }
    });
  }

  const submitTest = (status) => {
    if (status == 'waiting') {
      $('input[name=status]').val(status);
      $('#form').first().trigger('submit');
    } else {
      var statusString = status == 'approved' ? 'Approved' : 'Reject';
      $('#add-reason').modal('show');
      $('#add-reason .modal-title').text(`Add ${statusString} Reason`);
      $('#add-reason input[name=status]').val(status);
    }
  }

  const summernote = () => {
    $('.summernote').summernote({
      height:145,
      contenteditable: false,
      toolbar: [
        ['style', ['style']],
        ['font-style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
        ['font', ['fontname']],
        ['font-size',['fontsize']],
        ['font-color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video', 'hr']],
        ['misc', ['fullscreen', 'codeview', 'help']]
      ]
    });
  }

  $(function() {
    summernote();
    initInputPrice();

    $('#currency_id').select2({
      ajax: {
        url: "{{ route('currency.select') }}",
        type: 'GET',
        dataType: 'json',
        data: function(params) {
          return {
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function ( data, params ) {
          params.page = params.page || 1;
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function ( index, item ) {
            option.push({
              id: item.id,
              text: item.symbol,
              code: item.country ? item.country.code : item.country_id,
            });
          });
          return { results: option, pagination: { more: more, } };
        },
      },
      templateResult: formatCountry,
      placeholder: "Choose currency",
      allowClear: true,
    });

    dataTableAdditional = $('#table-additional').DataTable({
      processing: true,
      language: {
        processing: `<div class="p-2 text-center">
                        <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading ...
                     </div>`
      },
      serverSide: true,
      filter: false,
      responsive: true,
      lengthChange: false,
      paging: false,
      order: [[ 1, 'asc' ]],
      ajax: {
        url: "{{ route('salarydetail.read') }}",
        type: "GET",
        data: function(data) {
          data.salary_report_id   = {{ $salary->id }};
          data.type               = 1;
        }
      },
      columnDefs: [
        { orderable: false, targets: [0, 3] },
        { className: "text-right", targets: [2] },
        { render: function ( data, type, row ) {
          var button = '';
          if (actionmenu.indexOf('update') > 0 && status == 'WAITING' || status == 'DRAFT') {
            button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
            <i class="far fa-edit"></i>Update Data
            </a>`;
          }
          if (actionmenu.indexOf('delete') > 0 && status == 'WAITING' || status == 'DRAFT') {
            button += `<a class="dropdown-item delete" href="javascript:void(0);" onclick="destroy(${row.id})">
            <i class="fa fa-trash-alt"></i> Delete Data
            </a>`;
          }
          return `<div class="btn-group">
                    <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="dropdown-menu">
                        ${button}
                    </div>
                  </div>`;
        }, targets: [3] },
      ],
      columns: [
        { data: "no", className: "text-center" },
        { data: "description", className: "text-left" },
        { data: "total" },
        { data: "id", className: "text-center" },
      ],
    });

    dataTableDeduction = $('#table-deduction').DataTable({
      processing: true,
      language: {
        processing: `<div class="p-2 text-center">
                        <i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading ...
                     </div>`
      },
      serverSide: true,
      filter: false,
      responsive: true,
      lengthChange: false,
      paging: false,
      order: [[ 1, 'asc' ]],
      ajax: {
        url: "{{ route('salarydetail.read') }}",
        type: "GET",
        data: function(data) {
          data.salary_report_id   = {{ $salary->id }};
          data.type               = 2;
        }
      },
      columnDefs: [
        { orderable: false, targets: [0, 3] },
        { className: "text-right", targets: [2] },
        { render: function ( data, type, row ) {
          var button = '';
          if (actionmenu.indexOf('update') > 0 && status == 'WAITING' || status == 'DRAFT') {
            button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
            <i class="far fa-edit"></i>Update Data
            </a>`;
          }
          if (actionmenu.indexOf('delete') > 0 && status == 'WAITING' || status == 'DRAFT') {
            button += `<a class="dropdown-item delete" href="javascript:void(0);" onclick="destroy(${row.id})">
            <i class="fa fa-trash-alt"></i> Delete Data
            </a>`;
          }
          return `<div class="btn-group">
                    <button type="button" class="btn btn-flat btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="dropdown-menu">
                        ${button}
                    </div>
                  </div>`;
        }, targets: [3] },
      ],
      columns: [
        { data: "no", className: "text-center" },
        { data: "description", className: "text-left" },
        { data: "total" },
        { data: "id", className: "text-center" },
      ],
    });

    $('#form-edit').validate({
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
      errorPlacement: function(error, element) {
        if(element.is(':file')) {
          error.insertAfter(element.parent().parent().parent());
        }else if(element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        }else if (element.attr('type') == 'checkbox') {
          error.insertAfter(element.parent());
        }else{
          error.insertAfter(element);
        }
      },
      submitHandler: function() {
        $.ajax({
          url: $('#form-edit').attr('action'),
          method:'post',
          data: new FormData($('#form-edit')[0]),
          processData: false,
          contentType: false,
          dataType: 'json', 
          beforeSend:function(){
              blockMessage('body', 'Loading', '#fff');
          }
        }).done(function(response) {
          $('body').unblock();
          if (response.status) {
            toastr.success(response.message);
            $('#add-item').modal('hide');
            dataTableAdditional.draw();
            dataTableDeduction.draw();
          } else {
            toastr.warning(response.message);
          }
        }).fail(function(response){
          $('body').unblock();
          var response  = response.responseJSON;
          toastr.error(response.message);
        });
      }
    });

    $('#form').validate({
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
      errorPlacement: function(error, element) {
        if(element.is(':file')) {
          error.insertAfter(element.parent().parent().parent());
        }else if(element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        }else if (element.attr('type') == 'checkbox') {
          error.insertAfter(element.parent());
        }else{
          error.insertAfter(element);
        }
      },
      submitHandler: function() {
        $.ajax({
          url: $('#form').attr('action'),
          method:'post',
          data: new FormData($('#form')[0]),
          processData: false,
          contentType: false,
          dataType: 'json', 
          beforeSend:function(){
              blockMessage('body', 'Loading', '#fff');
          }
        }).done(function(response) {
          $('body').unblock();
          if (response.status) {
            document.location = response.results;
          } else {
            toastr.warning(response.message);
          }
        }).fail(function(response){
          $('body').unblock();
          var response  = response.responseJSON;
          toastr.error(response.message);
        });
      }
    });

    $('#form-reason').validate({
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
      errorPlacement: function(error, element) {
        if(element.is(':file')) {
          error.insertAfter(element.parent().parent().parent());
        }else if(element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        }else if (element.attr('type') == 'checkbox') {
          error.insertAfter(element.parent());
        }else{
          error.insertAfter(element);
        }
      },
      submitHandler: function() {
        $.ajax({
          url: $('#form-reason').attr('action'),
          method:'post',
          data: new FormData($('#form-reason')[0]),
          processData: false,
          contentType: false,
          dataType: 'json', 
          beforeSend:function(){
              blockMessage('body', 'Loading', '#fff');
          }
        }).done(function(response) {
          $('body').unblock();
          if (response.status) {
            document.location = response.results;
          } else {
            toastr.warning(response.message);
          }
        }).fail(function(response){
          $('body').unblock();
          var response  = response.responseJSON;
          toastr.error(response.message);
        });
      }
    });
  });
</script>
@endsection