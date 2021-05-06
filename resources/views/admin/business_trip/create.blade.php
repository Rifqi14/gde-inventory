@extends('admin.layouts.app')

@section('title')
Create Business Trips
@endsection

@section('stylesheets')

@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">
      Business Trips
    </h1>
  </div>
  <div class="col-sm-8">
    <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
      <li class="breadcrumb-item">Preferences</li>
      <li class="breadcrumb-item">Activities</li>
      <li class="breadcrumb-item">Business Trips</li>
      <li class="breadcrumb-item active">Create</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <form role="form" id="form-data" action="{{route('businesstrip.store')}}">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr />
                <h5 class="text-md text-dark text-uppercase">Transportation</h5>
              </span>
              <div id="form-depart">
                <div class="row mt-4 mb-0">
                  <div class="col-md-2">
                    <label>Depart:</label>
                  </div>
                  <div class="col-md-6">
                    <label>Description:</label>
                  </div>
                  <div class="col-md-3">
                    <label>Price:</label>
                  </div>
                </div>
                <div class="row item-depart">
                  <input type="hidden" class="depart" name="depart[]" data-trans-type="flight" data-description=""/>
                  <div class="col-md-2">
                    <div class="form-group">                      
                      <select class="form-control select2" class="trans-type" id="trans-type" name="trans_type" data-placeholder="Depart">
                        <option value="flight" selected>Flight</option>
                        <option value="others">Others</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input type="text" name="trans_description" class="form-control" placeholder="Enter description" value="" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            Rp.
                          </span>
                        </div>
                        <input type="text" name="trans_price" class="form-control input-price text-right" placeholder="Enter price" value="0" maxlength="14">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group" style="margin-top: 2px;">
                      <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <button type="button" id="add-depart" data-urutan="1" onclick="addDepart()" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple">
                  <b><i class="fas fa-plus"></i></b> Add
                </button>
              </div>
              <div id="form-return">
                <div class="row mt-4 mb-0">
                  <div class="col-md-2">
                    <label>Return:</label>
                  </div>
                  <div class="col-md-6">
                    <label>Description:</label>
                  </div>
                  <div class="col-md-3">
                    <label>Price:</label>
                  </div>
                </div>
                <div class="row item-return">
                  <div class="col-md-2">
                    <div class="form-group">
                      <input type="hidden" name="type[]" value="return" />
                      <select class="form-control select2" name="type_transportation[]" data-placeholder="Return">
                        <option value="flight">Flight</option>
                        <option value="others" selected>Others</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input type="text" name="trans_description[]" class="form-control" placeholder="Enter description" value="" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            Rp.
                          </span>
                        </div>
                        <input type="text" name="trans_price[]" class="form-control input-price text-right" placeholder="Enter price" value="0" maxlength="14">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group" style="margin-top: 2px;">
                      <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <button type="button" id="add-return" data-urutan="1" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple" onclick="addReturn()">
                  <b><i class="fas fa-plus"></i></b> Add
                </button>
              </div>
              <div id="form-request-vehicle">
                <div class="form-group row">
                  <div class="col-md-6">
                    <label for="request-vehicle">Request Vehicle</label>
                    <select name="request_vehicle" id="request-vehicle" class="form-control select2" data-placeholder="Request Vehicle"></select>
                  </div>
                  <div class="col-md-6">
                    <label for="date-request-vehicle">Date Request Vehicle</label>
                    <div class="input-group">
                      <div class="input-group-append">
                        <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                      </div>
                      <input type="text" class="form-control" id="date-request-vehicle" name="date_request_vehicle" placeholder="Date Request Vehicle" readonly>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <textarea class="form-control" name="remarks" id="remarks" rows="4" placeholder="Notes" readonly></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr />
                <h5 class="text-md text-dark text-uppercase">Lodging</h5>
              </span>
              <div id="form-lodging">
                <div class="row mt-4 mb-0">
                  <div class="col-md-6">
                    <label>Place:</label>
                  </div>
                  <div class="col-md-3">
                    <label>Price:</label>
                  </div>
                  <div class="col-md-2">
                    <label>Night:</label>
                  </div>
                </div>
                <div class="row item-lodging">
                  <div class="col-md-6">
                    <div class="form-group">
                      <input type="text" name="place_lodging[]" class="form-control" placeholder="Enter where lodging" value="">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Rp.
                        </span>
                      </div>
                      <input type="text" name="price_lodging[]" class="form-control input-price text-right" placeholder="Enter price" value="0" maxlength="14">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <input type="number" name="night_lodging[]" class="form-control text-right" placeholder="Enter qty" value="1">
                  </div>
                  <div class="col-md-1">
                    <div class="form-group" style="margin-top: 2px;">
                      <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <button type="button" id="add-lodging" data-urutan="1" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple" onclick="addLodging()">
                  <b><i class="fas fa-plus"></i></b> Add
                </button>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr />
                <h5 class="text-md text-dark text-uppercase">Others</h5>
              </span>
              <div id="form-others">
                <div class="row mt-4">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Description:</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label>Price:</label>
                  </div>
                  <div class="col-md-2">
                    <label>Qty:</label>
                  </div>
                </div>
                <div class="row mt-2 item-others">
                  <div class="col-md-6">
                    <div class="form-group">
                      <input type="text" name="others_desc[]" class="form-control" placeholder="Enter description" value="">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Rp.
                        </span>
                      </div>
                      <input type="text" name="others_price[]" class="form-control input-price text-right" placeholder="Enter price" value="0" maxlength="14">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <input type="number" name="others_qty[]" class="form-control text-right mr-2" placeholder="Enter qty" value="1">
                  </div>
                  <div class="col-md-1">
                    <div class="form-group" style="margin-top: 2px;">
                      <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="text-right">
                <button type="button" id="add-others" data-urutan="1" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple" onclick="addOthers()">
                  <b><i class="fas fa-plus"></i></b> Add
                </button>
              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr />
                <h5 class="text-md text-dark text-uppercase">General Information</h5>
              </span>
              <div class="form-group">
                <label for="business-trip-number">Business Trip Number</label>
                <input type="text" class="form-control" name="business_trip_number" id="business-trip-number" placeholder="Enter Business Trip Number" required>
              </div>
              <div class="form-group mt-4">
                <label>Issued by:</label>                
                <select class="form-control select2" name="issued" id="issued" data-placeholder=" -Select WBS- " style="width: 100%;" disabled>
                  @if(Auth::guard('admin')->user()->id)
                    <option value="{{Auth::guard('admin')->user()->id}}" selected>{{Auth::guard('admin')->user()->name}}</option>
                  @endif
                </select>
              </div>
              <div class="form-group">
                <label>Schedule:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="datepicker" class="form-control datepicker text-right" id="reservation" name="date">
                </div>
              </div>
              <div class="form-group">
                <label>Purpose:</label>
                <input type="text" class="form-control" name="description" placeholder="Enter purpose" value="">
              </div>
              <div class="form-group">
                <label>Location:</label>
                <input type="text" class="form-control" name="location" placeholder="Enter location" value="">
              </div>
              <div class="form-group">
                <label>Rate:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      Rp.
                    </span>
                  </div>
                  <input type="text" name="rate" class="form-control input-price text-right" placeholder="Enter rate" value="0" maxlength="14">
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Approval Status:</label><br />
                    <span class="badge bg-gray text-sm">Draft</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="text-right">
            <button type="button" onclick="onSubmit('publish')" class="btn btn-success btn-labeled legitRipple text-sm">
              <b><i class="fas fa-check-circle"></i></b>
              Submit
            </button>
            <button type="button" onclick="onSubmit('draft')" class="btn bg-olive color-palette btn-labeled legitRipple text-sm">
              <b><i class="fas fa-save"></i></b>
              Save
            </button>
            <button type="button" class="btn bg-gray btn-labeled legitRipple text-sm">
              <b><i class="fas fa-print"></i></b>
              Print
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script type="text/javascript">
  var employeeID = "{{Auth::guard('admin')->user()->employee_id}}",
      userID     = "{{Auth::guard('admin')->user()->id}}",
      username   = "{{Auth::guard('admin')->user()->name}}";  
  
  $(function() {    

    initSelect2();
    initInputPrice();        

    $('.summernote').summernote({
      height: 145,
      toolbar: [
        ['style', ['style']],
        ['font-style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
        ['font', ['fontname']],
        ['font-size', ['fontsize']],
        ['font-color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video', 'hr']],
        ['misc', ['fullscreen', 'codeview', 'help']]
      ]
    });

    $('textarea[id=remarks]').summernote({
      height: 145,
      toolbar: []
    });

    $('textarea[id=remarks]').summernote('disable');

    $('.datepicker').daterangepicker({
      singleDatePicker: true,
      timePicker: false,
      timePickerIncrement: 30,
      drops: 'auto',
      opens: 'center',
      locale: {
        format: 'DD/MM/YYYY'
      },
      startDate: moment(new Date())
    });

    $('#form-depart').on('click', '.remove', function() {
      if ($('.item-depart').length > 1) {
        $(this).parents('.item-depart').remove();
      }
    });    

    $('#form-return').on('click', '.remove', function() {
      if ($('.item-return').length > 1) {
        $(this).parents('.item-return').remove();
      }
    });

    $('#form-lodging').on('click', '.remove', function() {
      if ($('.item-lodging').length > 1) {
        $(this).parents('.item-lodging').remove();
      }
    });

    $('#form-others').on('click', '.remove', function() {
      if ($('.item-others').length > 1) {
        $(this).parents('.item-others').remove();
      }
    });     

    var transType = $('.trans-type').val();
    console.log({transType : transType});

  });

  function initSelect2() {
    $('.select2').select2({
      allowClear: true
    });

    $("#request-vehicle").select2({
      ajax: {
        url: "{{route('requestvehicle.select')}}",
        type: 'GET',
        dataType: 'json',
        data: function(params) {
          return {
            employee_id: employeeID,
            search: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function(data, params) {
          var more = (params.page * 30) < data.total;
          var option = [];
          $.each(data.rows, function(index, item) {
            option.push({
              id: item.id,
              text: item.vehicle_name + ' | ' + item.date_request,
              notes: item.remarks,
              daterequest: item.date_request
            });
          });
          return {
            results: option,
            more: more,
          };
        },
      },
      allowClear: true,
    }).on('select2:select', function(e) {
      var data = e.params.data;
      if (data.notes) {
        $('textarea[id=remarks]').summernote('reset');
        $('textarea[id=remarks]').summernote('pasteHTML', data.notes);
      }
      $('#date-request-vehicle').val(data.daterequest);
    }).on('select2:clearing', function() {
      $('textarea[id=remarks]').summernote('reset');
      $('#date-request-vehicle').val('');
    });
    
  }

  function initInputPrice() {
    $(".input-price").priceFormat({
      prefix: '',
      centsSeparator: ',',
      thousandsSeparator: '.',
      centsLimit: 0,
      clearOnEmpty: false
    });
  }

  function addDepart() {
    var html = `<div class="row mt-2 item-depart">
                  <div class="col-md-2">
                    <div class="form-group">                    
                      <input type="hidden" name="type[]" value="depart" />
                      <select class="form-control select2" name="type_transportation[]" data-placeholder="Depart">                        
                        <option value="flight" selected>Flight</option>
                        <option value="others">Others</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">                      
                      <input type="text" name="trans_description[]" class="form-control" placeholder="Enter description" value="" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">                      
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            Rp.
                          </span>
                        </div>
                        <input type="text" name="trans_price[]" class="form-control input-price text-right" placeholder="Enter price" value="0" maxlenght="14">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group" style="margin-top: 2px;">                                            
                    <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove"type="button"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>`;
    $('#form-depart').append(html);
    initSelect2();
    initInputPrice();
  }

  function addReturn() {
    var html = `<div class="row mt-2 item-return">
                  <div class="col-md-2">
                    <div class="form-group">                      
                      <input type="hidden" name="type[]" value="return" />
                      <select class="form-control select2" name="type_transportation[]" data-placeholder="Return">
                        <option value="flight">Flight</option>
                        <option value="others" selected>Others</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">                      
                      <input type="text" name="trans_description[]" class="form-control" placeholder="Enter description" value="" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">                      
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            Rp.
                          </span>
                        </div>
                        <input type="text" name="trans_price[]" class="form-control input-price text-right" placeholder="Enter price" value="0" maxlength="14">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group" style="margin-top: 2px;">
                      <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>`;
    $('#form-return').append(html);
    initSelect2();
    initInputPrice();
  }

  function addLodging() {
    var html = `<div class="row mt-2 item-lodging">
                  <div class="col-md-6">
                    <div class="form-group">                      
                      <input type="text" name="place_lodging[]" class="form-control" placeholder="Enter where lodging" value="">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Rp.
                        </span>
                      </div>
                      <input type="text" name="price_lodging[]" class="form-control input-price text-right" placeholder="Enter price" value="0" maxlength="14">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <input type="number" name="night_lodging[]" class="form-control text-right" placeholder="Enter qty" value="1">
                  </div>
                  <div class="col-md-1">
                    <div class="form-group" style="margin-top: 2px;">
                      <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>`;
    $('#form-lodging').append(html);
    initInputPrice();
  }

  function addOthers() {
    var html = `<div class="row mt-2 item-others">
                  <div class="col-md-6">
                    <div class="form-group">                      
                      <input type="text" name="others_desc[]" class="form-control" placeholder="Enter description" value="">
                    </div>
                  </div>
                  <div class="col-md-3">                    
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Rp.
                        </span>
                      </div>
                      <input type="text" name="others_price[]" class="form-control input-price text-right" placeholder="Enter price" value="0" maxlength="14">
                    </div>
                  </div>
                  <div class="col-md-2">                    
                    <input type="number" name="others_qty[]" class="form-control text-right" placeholder="Enter qty" value="1">
                  </div>
                  <div class="col-md-1">
                    <div class="form-group" style="margin-top: 2px;">
                      <button class="btn btn-md text-xs btn-danger btn-flat legitRipple remove" type="button"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>`;
    $('#form-others').append(html);
    initInputPrice();
  }

  function submitTest() {
    var departure = [],
        returning = [],
        lodging   = [],
        others    = [],
        hasNull   = false;

    $.each($('#form-depart').find('input[name^=depart]'), function (index, value) { 
      var depart = $(this),
          type   = $(this).attr('data-trans-type'),
          desc   = $(this).attr('data-desc'),
          price  = $(this).attr('price');
      
      departure.push({
        type : type,
        desc : desc,
        price : price
      });

    });

    console.log({
      departure : departure,
      returning : returning,
      lodging : lodging,
      others  : others,
      hasNull : hasNull
    });
  }
</script>
@endsection