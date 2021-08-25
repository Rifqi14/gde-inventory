@extends('admin.layouts.app')
@section('title',$menu_name)

@section('breadcrumb')
<div class="row mb-3 mt-3">
    <div class="col-sm-4">
        <h1 id="title-branch" class="m-0 text-dark">{{ $menu_name }}</h1>
    </div>
    <div class="col-sm-8">
        <ol class="breadcrumb float-sm-right text-danger mr-2 text-sm">
            <li class="breadcrumb-item">{{ $parent_name }}</li>
            <li class="breadcrumb-item">{{ $menu_name }}</li>
            <li class="breadcrumb-item">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('stylesheets')
<style>
    .row-form {
        padding-left:30px !important;
    }
    .other {
        display: none;
        padding-left:40px !important;
    }
    #form-idm {
        display: none;
    }
</style>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <form id="form-data" action="{{route('grievance.update',['id' => $data->id])}}" role="form" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <span class="title">
                                <hr />
                                <h5 class="text-md text-dark text-bold">Complaint Form</h5>
                            </span>
                            <div class="form-group mt-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="complainant">Complainant:</label>
                                        <input type="text" class="form-control" name="complainant"
                                            placeholder="Complainant..." required
                                            value="{{ $data->complainant }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="id_number">ID Number:</label>
                                        <input type="text" class="form-control" name="id_number"
                                            placeholder="ID Number..." required value="{{ $data->id_number }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="gender">Gender:</label>
                                        <div class="mt-2">
                                            <div class="icheck-success d-inline ml-2">
                                                <input type="radio" value="male" name="gender" id="gender1" {{ ($data->gender == 'male')?'checked':'' }} >
                                                <label for="gender1"></label>
                                                <span class="text">Male</span>
                                            </div>
                                            <div class="icheck-success d-inline ml-2">
                                                <input type="radio" value="female" name="gender" id="gender2" {{ ($data->gender == 'female')?'checked':'' }} >
                                                <label for="gender2"></label>
                                                <span class="text">Female</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <textarea class="form-control" name="address" rows="3" placeholder="Address..." required>
                                    {{ $data->address }}
                                </textarea>
                            </div>
                            <div id="input-list-checkbox" class="form-group row mt-1">
                                <div class="col-md-4">
                                    <div class="icheck-success d-inline ">
                                        <input type="checkbox" value="Phone" id="todoCheckphone" {{ ($data->phone)?'checked':'' }} >
                                        <label for="todoCheckphone"></label>
                                        <span class="text">Phone:</span>
                                    </div>
                                    <div class="row-form mt-1">
                                        <div class="row mb-1">
                                            <div class="col-12">
                                                <input type="text" class="form-control " placeholder="Phone Number..."
                                                    name="phone" value="{{ $data->phone }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="icheck-success d-inline ">
                                        <input type="checkbox" value="FAX" id="todoCheckfax" {{ ($data->fax)?'checked':'' }} >
                                        <label for="todoCheckfax"></label>
                                        <span class="text">FAX:</span>
                                    </div>
                                    <div class="row-form mt-1">
                                        <div class="row mb-1">
                                            <div class="col-12">
                                                <input type="text" class="form-control " placeholder="Fax Number..."
                                                    name="fax" value="{{ $data->fax }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="icheck-success d-inline">
                                        <input type="checkbox" value="Email" id="todoCheckemail" {{ ($data->email)?'checked':'' }} >
                                        <label for="todoCheckemail"></label>
                                        <span class="text">Email:</span>
                                    </div>
                                    <div class="row-form mt-1">
                                        <div class="row mb-1">
                                            <div class="col-12">
                                                <input type="text" class="form-control " placeholder="E-mail Address..."
                                                    name="email" value="{{ $data->email }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="affiliation">Affiliation:</label>
                                <div class="row">
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Community" name="affiliation[]"
                                                id="affiliation0" {{ (in_array('Community', $data->affiliation))?'checked':'' }} >
                                            <label for="affiliation0"></label>
                                        </div>
                                        <span class="text">Community</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="NGO" name="affiliation[]" id="affiliation1" {{ (in_array('NGO', $data->affiliation))?'checked':'' }} >
                                            <label for="affiliation1"></label>
                                        </div>
                                        <span class="text">NGO</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Institution" name="affiliation[]"
                                                id="affiliation2" {{ (in_array('Institution', $data->affiliation))?'checked':'' }}>
                                            <label for="affiliation2"></label>
                                        </div>
                                        <span class="text">Institution</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Worker" name="affiliation[]" id="affiliation3"
                                                {{ (in_array('Worker', $data->affiliation))?'checked':'' }} >
                                            <label for="affiliation3"></label>
                                        </div>
                                        <span class="text">Worker</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Intermediary" name="affiliation[]"
                                                id="affiliation4" {{ (in_array('Intermediary', $data->affiliation))?'checked':'' }} >
                                            <label for="affiliation4"></label>
                                        </div>
                                        <span class="text">Intermediary on behalf of community</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Other" name="affiliation[]" id="affiliation5" {{ (in_array('Other', $data->affiliation))?'checked':'' }} >
                                            <label for="affiliation5"></label>
                                        </div>
                                        <span class="text">Other Stakeholder</span>
                                    </div>
                                </div>
                            </div>
                            <div id="form-idm">
                                <span class="title">
                                    <hr />
                                </span>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="complainant">Name of Represented:</label>
                                            <input type="text" class="form-control" name="idm_name"
                                                placeholder="Name of Represented..."
                                                value="{{ $data->idm_name }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="idm_id_number">ID Number:</label>
                                            <input type="text" class="form-control" name="idm_id_number"
                                                placeholder="ID Number ...." value="{{ $data->idm_id_number }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="idm_address">Address:</label>
                                    <textarea class="form-control" name="idm_address" rows="3"
                                        placeholder="Address...">{{ $data->idm_address }}</textarea>
                                </div>
                                <div id="input-list-checkbox" class="form-group row mt-1">
                                    <div class="col-md-4">
                                        <div class="icheck-success d-inline ">
                                            <input type="checkbox" value="Phone" id="todoCheckphone2" {{ ($data->idm_phone)?'checked':'' }} >
                                            <label for="todoCheckphone2"></label>
                                            <span class="text">:</span>
                                        </div>
                                        <div class="row-form mt-1">
                                            <div class="row mb-1">
                                                <div class="col-12">
                                                    <input type="text" class="form-control "
                                                        placeholder="Phone Number..." name="idm_phone"
                                                        value="{{ $data->idm_phone }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="icheck-success d-inline ">
                                            <input type="checkbox" value="FAX" id="todoCheckfax2" {{ ($data->idm_fax)?'checked':'' }} >
                                            <label for="todoCheckfax2"></label>
                                            <span class="text">FAX:</span>
                                        </div>
                                        <div class="row-form mt-1">
                                            <div class="row mb-1">
                                                <div class="col-12">
                                                    <input type="text" class="form-control " placeholder="Fax Number..."
                                                        name="idm_fax" value="{{ $data->idm_fax }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="icheck-success d-inline">
                                            <input type="checkbox" value="Email" id="todoCheckemail2" {{ ($data->idm_email)?'checked':'' }} >
                                            <label for="todoCheckemail2"></label>
                                            <span class="text">Email:</span>
                                        </div>
                                        <div class="row-form mt-1">
                                            <div class="row mb-1">
                                                <div class="col-12">
                                                    <input type="text" class="form-control "
                                                        placeholder="E-mail Address..." name="idm_email"
                                                        value="{{ $data->idm_email }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="idm_attach">Attachment:</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="idm_attach"
                                                accept="image/*" onchange="changePath(this)">
                                            <label class="custom-file-label" for="exampleInputFile">Attach a
                                                File</label>
                                        </div>
                                    </div>
                                </div>
                                <span class="title">
                                    <hr />
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="toc">Type of Complaint:</label>
                                <div class="row">
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Air Pollution" name="type[]" id="type0" {{ (in_array('Air Pollution', $data->complaint_type))?'checked':'' }} >
                                            <label for="type0"></label>
                                        </div>
                                        <span class="text">Air Pollution</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Vibration" name="type[]" id="type1" {{ (in_array('Vibration', $data->complaint_type))?'checked':'' }} >
                                            <label for="type1"></label>
                                        </div>
                                        <span class="text">Vibration</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Water Pollution" name="type[]" id="type2"
                                                {{ (in_array('Water Pollution', $data->complaint_type))?'checked':'' }} >
                                            <label for="type2"></label>
                                        </div>
                                        <span class="text">Water Pollution</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Noise" name="type[]" id="type3" {{ (in_array('Noise', $data->complaint_type))?'checked':'' }} >
                                            <label for="type3"></label>
                                        </div>
                                        <span class="text">Noise</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Compensation" name="type[]" id="type4" {{ (in_array('Compensation', $data->complaint_type))?'checked':'' }} >
                                            <label for="type4"></label>
                                        </div>
                                        <span class="text">Compensation</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Traffic disruption" name="type[]" id="type5"
                                                {{ (in_array('Traffic disruption', $data->complaint_type))?'checked':'' }} >
                                            <label for="type5"></label>
                                        </div>
                                        <span class="text">Traffic Disruption</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Labour relations" name="type[]" id="type6"
                                                {{ (in_array('Labour relations', $data->complaint_type))?'checked':'' }} >
                                            <label for="type6"></label>
                                        </div>
                                        <span class="text">Labour Relations</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Labour standards" name="type[]" id="type7"
                                                {{ (in_array('Labour standards', $data->complaint_type))?'checked':'' }} >
                                            <label for="type7"></label>
                                        </div>
                                        <span class="text">Labour Standards</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Occupational health and safety" name="type[]"
                                                id="type8" {{ (in_array('Occupational health and safety', $data->complaint_type))?'checked':'' }} >
                                            <label for="type8"></label>
                                        </div>
                                        <span class="text">Occupational Health and Safety</span>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Other" name="type[]" id="type9" {{ (in_array('Other', $data->complaint_type))?'checked':'' }} >
                                            <label for="type9"></label>
                                        </div>
                                        <span class="text">Other (Describe)</span>
                                        <div class="row-form mt-1 other">
                                            <div class="row mb-1">
                                                <div class="col-12">
                                                    <input type="text" class="form-control "
                                                        placeholder="Enter describe" name="other_type"
                                                        value="{{ $data->complaint_type_other }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location">Detailed Location:</label>
                                <input type="text" class="form-control" name="location"
                                    placeholder="Detailed Location..." value="{{ $data->location }}">
                            </div>
                            <div class="form-group">
                                <label for="description">Complaint Description:</label>
                                <textarea class="form-control summernote" name="description" rows="4"
                                    placeholder="Complaint Description...">{{ $data->complaint_desc }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="focal">Focal Point:</label>
                                <select class="select2" name="focal[]" id="focal" data-placeholder="Tag Focal Point"
                                    style="width: 100%;" required multiple></select>
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
                                <h5 class="text-md text-dark text-bold">General Information</h5>
                            </span>
                            <div class="form-group mt-4">
                                <label for="number">Number:</label>
                                <input type="text" class="form-control" placeholder="Enter number ...."
                                    value="{{ $data->number }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="number">Date and Time:</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id=""><i
                                                        class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="text" class="form-control datepicker text-right" id="date"
                                                name="date" placeholder="Date" required
                                                value="{{ date("d/m/Y", strtotime($data->date)) }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="time" class="form-control text-right" id="time" name="time"
                                            placeholder="Time" required value="{{ $data->time }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="media">Media:</label>
                                <div class="row">
                                    <div class="col-md-4 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="SMS" name="media[]" id="todoCheck0" {{ (in_array('SMS', $data->media))?'checked':'' }} >
                                            <label for="todoCheck0"></label>
                                        </div>
                                        <span class="text">SMS</span>
                                    </div>
                                    <div class="col-md-4 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Phone" name="media[]" id="todoCheck1" {{ (in_array('Phone', $data->media))?'checked':'' }} >
                                            <label for="todoCheck1"></label>
                                        </div>
                                        <span class="text">Phone</span>
                                    </div>
                                    <div class="col-md-4 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Email" name="media[]" id="todoCheck2" {{ (in_array('Email', $data->media))?'checked':'' }} >
                                            <label for="todoCheck2"></label>
                                        </div>
                                        <span class="text">Email</span>
                                    </div>
                                    <div class="col-md-4 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Letter" name="media[]" id="todoCheck3" {{ (in_array('Letter', $data->media))?'checked':'' }} >
                                            <label for="todoCheck3"></label>
                                        </div>
                                        <span class="text">Letter</span>
                                    </div>
                                    <div class="col-md-4 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Visit" name="media[]" id="todoCheck4" {{ (in_array('Visit', $data->media))?'checked':'' }} >
                                            <label for="todoCheck4"></label>
                                        </div>
                                        <span class="text">Visit</span>
                                    </div>
                                    <div class="col-md-4 mb-1">
                                        <div class="icheck-success d-inline ml-2">
                                            <input type="checkbox" value="Others" name="media[]" id="todoCheck5" {{ (in_array('Others', $data->media))?'checked':'' }} >
                                            <label for="todoCheck5"></label>
                                        </div>
                                        <span class="text">Others</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="unit">Unit:</label>
                                <select class="select2" id="unit_id" name="unit" data-placeholder="Choose Unit" style="width: 100%;" required>
                                    
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="attach">Attachment:</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="attach" accept="image/*"
                                            onchange="changePath(this)">
                                        <label class="custom-file-label" for="exampleInputFile">Attach a File</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Application Status:</label><br />
                                @if($data->status == 'waiting')
                                    <span class="badge bg-warning text-sm">Waiting</span>
                                @elseif($data->status == 'revise')
                                    <span class="badge bg-maroon color-platte text-sm">Revise</span>
                                @elseif($data->status == 'approved')
                                    <span class="badge bg-success text-sm">Approved</span>
                                @elseif($data->status == 'declined')
                                    <span class="badge bg-danger text-sm">Declined</span>
                                @else
                                    <span class="badge bg-gray text-sm">Draft</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Complaint Status:</label><br />
                                @if($data->approval_status == 'queue')
                                    <span class="badge bg-warning text-sm">Queue</span>
                                @elseif($data->approval_status == 'declined')
                                    <span class="badge bg-danger text-sm">Declined</span>
                                @elseif($data->approval_status == 'active')
                                    <span class="badge bg-success text-sm">Active</span>
                                @elseif($data->approval_status == 'cleared')
                                    <span class="badge bg-info text-sm">Cleared</span>
                                @else
                                    <span class="badge bg-gray text-sm">Registered</span>
                                @endif
                            </div>
                            <div class="text-right">
                                <button type="button"
                                    class="btn btn-sm text-sm bg-yellow color-platte btn-flat legitRipple"
                                    onclick="comment(this)">
                                    <b><i class="fas fa-eye"></i></b>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" onclick="onSubmit('waiting')"
                            class="btn btn-success btn-labeled legitRipple text-sm">
                            <b><i class="fas fa-check-circle"></i></b>
                            Submit
                        </button>
                        <button type="button" onclick="onSubmit('draft')"
                            class="btn bg-olive color-palette btn-labeled legitRipple text-sm">
                            <b><i class="fas fa-save"></i></b>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<div class="modal fade" id="view-comment">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
                <h5 class="text-lg text-dark text-bold">Comment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                {{ $data->comment }}
            
                @if($data->attachment_comment)
                    <a href="{{ url($data->attachment_comment) }}" target="_blank" class="btn bg-success color-palette btn-labeled legitRipple text-sm btn-sm">
                        <b><i class="fas fa-download"></i></b>
                        Attachment
                    </a>    
                @endif
			</div>
		</div>
	<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
@endsection

@section('scripts')
<script>
$(function () {
    var focal = '{!! json_encode($data->focal) !!}'
        unit_id = {{ $site->id }};
        unit_name = '{{ $site->name }}';
    focal = JSON.parse(focal)
    summernote()
    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        timePicker: false,
        timePickerIncrement: 30,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });
    $('.select2').select2();

    $("#input-list-checkbox input[type='checkbox']").change(function () {
        let checked = $(this).is(":checked");
        if (checked) {
            $(this).parent().parent().children(".row-form").slideDown("fast");
            $(this).parent().parent().children(".row-form").find("input").attr('required', 'required');
        } else {
            $(this).parent().parent().children(".row-form").slideUp("fast");
            $(this).parent().parent().children(".row-form").find("input").removeAttr('required');
        }
    });
    $("#input-list-checkbox input[type='checkbox']").trigger('change')

    $("#affiliation4").change(function () {
        let checked = $(this).is(":checked");
        if (checked) {
            $('#form-idm').slideDown("fast");
            $('#form-idm').find("input[name='idm_id_number'], textarea, input[type='file']").attr('required', 'required');
        } else {
            $('#form-idm').slideUp("fast");
            $('#form-idm').find("input[name='idm_id_number'], textarea, input[type='file']").removeAttr('required');
        }
    });
    $("#affiliation4").trigger('change')

    $("#type9").change(function () {
        let checked = $(this).is(":checked");
        if (checked) {
            $(this).parent().parent().children(".row-form").slideDown("fast");
            $(this).parent().parent().children(".row-form").find("input").attr('required', 'required');
        } else {
            $(this).parent().parent().children(".row-form").slideUp("fast");
            $(this).parent().parent().children(".row-form").find("input").removeAttr('required');
        }
    });
    $("#type9").trigger('change')

    $("#focal").select2({
    	ajax: {
    		url: '{{ route("user.select") }}',
    		type:'GET',
    		dataType: 'json',
    		data: function (params) {
    			return {
    				name:params.term,
    				page:params.page,
    				limit:30,
    				exception_id:'{{ $userid }}',
    			};
    		},
    		processResults: function (data,params) {
    		 var more = (params.page * 30) < data.total;
    		 var option = [];
    		 $.each(data.rows,function(index,item){
    			option.push({
    				id:item.id,  
    				text: item.name
    			});
    		 });
    		  return {
    			results: option, more: more,
    		  };
    		},
    	},
    	allowClear: true,
    });

    $.each(focal, function (key, val) {
        $("#focal").select2("trigger", "select", {
            data: { id: val.user_id, text: val.realname }
        });
    })

    $("#unit_id").select2({
    	ajax: {
    		url: '{{ route("site.select") }}',
    		type:'GET',
    		dataType: 'json',
    		data: function (params) {
    			return {
    				name:params.term,
    				page:params.page,
    				limit:30,
    			};
    		},
    		processResults: function (data,params) {
    		 var more = (params.page * 30) < data.total;
    		 var option = [];
    		 $.each(data.rows,function(index,item){
    			option.push({
    				id:item.id,  
    				text: item.name
    			});
    		 });
    		  return {
    			results: option, more: more,
    		  };
    		},
    	},
    	allowClear: true,
    });

    $("#unit_id").select2("trigger", "select", {
        data: { id: unit_id, text: unit_name }
    });

})

function onSubmit(status) {
    let data = $('#form-data')[0]
    let formData = new FormData(data)
    formData.append('status', status);

    Swal.fire({
        title: '<text style="font-size:24px;">Are you sure?<text>',
        html: '<text style="font-size:21px;font-weight:bold;">WARNING: This Process cannot be Undone<text>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3d9970',
        cancelButtonColor: '#d81b60',
        confirmButtonText: "<b>I AM SURE</b>",
        cancelButtonText: "<b>CANCEL</b>",
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: $('#form-data').attr('action'),
                method: 'post',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function () {
                    blockMessage('#bd', 'Please Wait . . . ', '#fff');
                }
            }).done(function (response) {
                $('#bd').unblock();
                window.location.href = response.results;
                return;
            }).fail(function (response) {
                var response = response.responseJSON;
                $('#bd').unblock();
                window.location.href = response.results;
                return;
            });
        }
    })
}

function summernote() {
    $('.summernote').summernote({
        height: 225,
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
}

function changePath(that) {
    let filename = $(that).val()
    $(that).next().html(filename)
}
function comment(that) {
    $('#view-comment').modal('show')
}
</script>
@endsection