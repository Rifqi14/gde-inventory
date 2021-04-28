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
                <div class="row mt-4 item-depart">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Depart:</label>
                      <input type="hidden" name="type[]" value="depart" />
                      <select class="form-control" name="type_transportation[]">
                        <option value="flight" selected>Flight</option>
                        <option value="others">Others</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Description:</label>
                      <input type="text" name="trans_description[]" class="form-control" placeholder="Please enter description..." value="" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Price:</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            Rp.
                          </span>
                        </div>
                        <input type="number" name="trans_price[]" class="form-control" placeholder="Enter price..." value="0">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <button type="button" id="add-depart" data-urutan="1" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple">
                  <b><i class="fas fa-plus"></i></b> Add
                </button>
              </div>
              <div id="form-return">
                <div class="row mt-4 item-return">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Return:</label>
                      <input type="hidden" name="type[]" value="return" />
                      <select class="form-control" name="type_transportation[]">
                        <option value="flight">Flight</option>
                        <option value="others" selected>Others</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Description:</label>
                      <input type="text" name="trans_description[]" class="form-control" placeholder="Please enter description..." value="" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Price:</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            Rp.
                          </span>
                        </div>
                        <input type="number" name="trans_price[]" class="form-control" placeholder="Enter price..." value="0">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <button type="button" id="add-return" data-urutan="1" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple">
                  <b><i class="fas fa-plus"></i></b> Add
                </button>
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
                <div class="row mt-4 item-lodging">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Place:</label>
                      <input type="text" name="place_lodging[]" class="form-control" placeholder="Enter where lodging..." value="">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label>Price:</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Rp.
                        </span>
                      </div>
                      <input type="number" name="price_lodging[]" class="form-control" placeholder="Enter price lodging..." value="0">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <label>Night:</label>
                    <input type="number" name="night_lodging[]" class="form-control" placeholder="Enter price lodging..." value="1">
                  </div>
                </div>
              </div>

              <div class="text-right">
                <button type="button" id="add-lodging" data-urutan="1" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple">
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
                <div class="row mt-4 item-others">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Description:</label>
                      <input type="text" name="others_desc[]" class="form-control" placeholder="Enter description..." value="">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label>Price:</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Rp.
                        </span>
                      </div>
                      <input type="number" name="others_price[]" class="form-control" placeholder="Enter price..." value="0">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <label>Qty:</label>
                    <input type="number" name="others_qty[]" class="form-control" placeholder="Enter qty..." value="1">
                  </div>
                </div>
              </div>

              <div class="text-right">
                <button type="button" id="add-others" data-urutan="1" class="btn btn-labeled labeled-sm btn-md text-xs btn-success btn-flat legitRipple">
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
              <div class="form-group mt-4">
                <label>Issued by:</label>
                <input type="hidden" name="participant[]" value="" />
                <select class="select2" data-placeholder=" -Select WBS- " style="width: 100%;" disabled>
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
                  <input type="text" class="form-control float-right" id="reservation" name="date">
                </div>
              </div>
              <div class="form-group">
                <label>Purpose:</label>
                <input type="text" class="form-control" name="description" placeholder="Enter your purpose..." value="">
              </div>
              <div class="form-group">
                <label>Location:</label>
                <input type="text" class="form-control" name="location" placeholder="Enter your location..." value="">
              </div>
              <div class="form-group">
                <label>Rate:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      Rp.
                    </span>
                  </div>
                  <input type="number" name="rate" class="form-control" placeholder="Enter rate..." value="0">
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

</script>
@endsection