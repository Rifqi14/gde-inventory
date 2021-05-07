@extends('admin.layouts.app')
@section('title', $menu_name)
@section('stylesheets')
<style>
    #table-document tbody tr td:nth-child(3) .input-group{
        margin-bottom: .25rem!important;
    }
    /* #table-document tbody tr td:nth-child(3) .input-group button{
        user-select: none;
        z-index: 0;
        opacity: 0;
        position: relative;
        cursor: default;
    } */
    #table-document tbody tr td:nth-child(3) .input-group:last-child{
        margin-bottom: 0px !important;
    }
    /* #table-document tbody tr td:nth-child(3) .input-group:last-child button{
        user-select: initial;
        z-index: 1;
        opacity: 1;
        position: relative;
    } */
    .custom-file-label {
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        padding-right: 70px;
    }
    .input-group.download{
        border-bottom: 1px dashed #ddd;
        padding: 5px 0px;
    }
    .input-group.download button{
        position: absolute;
        right: 13px;
    }
</style>
@endsection

@section('breadcrumb')
<div class="row mb-3 mt-3">
  <div class="col-sm-4">
    <h1 id="title-branch" class="m-0 text-dark">
      Create {{ $menu_name }}
    </h1>
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

@section('content')
<section id="content" class="content">
  <div class="container-fluid">
    <form action="{{route('contractreceipt.update',['id'=>$contractreceipt->id])}}" id="form" role="form" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="_method" value="put">
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">{{ $menu_name }} Information</h5>
              </span>
              <div class="mt-5"></div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="contract_id" class="col-md-12 col-xs-12 control-label">Contract</label>
                    <div class="col-sm-12 controls">
                      <select name="contract_id" id="contract_id" class="form-control select2" required data-placeholder="Select Contract">
                        
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="warehouse_id" class="col-md-12 col-xs-12 control-label">Warehouse</label>
                    <div class="col-sm-12 controls">
                      <select name="warehouse_id" id="warehouse_id" class="form-control select2" required data-placeholder="Select Warehouse">
                        
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="contract_number" class="col-md-12 col-xs-12 control-label">Contract Number</label>
                    <div class="col-sm-12 controls">
                      <input type="text" name="contract_number" id="contract_number" class="form-control" readonly placeholder="Contract Number" value="{{ $contractreceipt->contract->number }}">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="contract_date" class="col-md-12 col-xs-12 control-label">Contract Signing Date</label>
                    <div class="col-sm-12 controls">
                      <input type="text" name="contract_date" id="contract_date" class="form-control" readonly placeholder="Contract Signing Date" value="{{ date("d/m/Y", strtotime($contractreceipt->contract->contract_signing_date)) }}">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="batch_id" class="col-md-12 col-xs-12 control-label">Batch</label>
                    <div class="col-sm-12 controls">
                      <select name="batch" id="batch_id" class="form-control select2" required data-placeholder="Select Batch">

                      </select>
                    </div>
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
                <h5 class="text-md text-dark text-bold">Other</h5>
              </span>
              <div class="mt-5"></div>
              <div class="form-group row">
                <label class="col-md-12 col-xs-12 control-label" for="remarks">Description</label>
                <div class="col-sm-12 controls">
                  <textarea class="form-control summernote" name="remarks" id="remarks" rows="4" placeholder="Description...">
                      {{ $contractreceipt->remarks }}
                  </textarea>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-12 col-xs-12 control-label" for="status">Status <b class="text-danger">*</b></label>
                <div class="col-sm-12 controls">
                  <select name="status" id="status" class="form-control select2" required data-placeholder="Select Status">
                    <option value=""></option>
                    @foreach(config('enums.status_receipt') as $key => $status)
                    <option value="{{ $key }}" @if($contractreceipt->status == $key) selected @endif>{{ $status }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <span class="title">
                <hr>
                <h5 class="text-md text-dark text-bold">Document List</h5>
              </span>
              <div class="mt-5"></div>
              <button type="button" id="add-document" class="btn btn-labeled text-sm btn-sm btn-outline-primary btn-flat btn-block legitRipple" onclick="addDocument()">Add</button>
              <table id="table-document" class="table table-striped datatable" width="100%">
                <thead>
                  <tr>
                    <th width="5%" class="text-center">No.</th>
                    <th width="40%">Document Name</th>
                    <th width="40%">Upload Document</th>
                    <th width="10%" class="text-center">Upload Date</th>
                    <th width="5%" class="text-center">#</th>
                  </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= $contractreceipt->document_count; $i++)
                        <tr data-number="{{ $i }}">
                            <td class="text-center">
                                <div class="mb-1"></div>
                                {{ $i }}
                            </td>
                            <td>
                                <input type="hidden" name="contract_document_receipts[]" value="{{ $i }}">
                                <input type="hidden" name="contract_document_receipts_id[{{ $i }}]" value="{{ $contractreceipt->document[$i - 1]->id }}">
                                <input type="text" class="form-control" id="document_name_{{ $i }}" name="document_name[{{ $i }}]" placeholder="Document Name" aria-required="true" value="{{ $contractreceipt->document[$i - 1]->document_name }}">              
                            </td>
                            <td>
                                @php $n = 1; @endphp
                                @foreach ($contractreceipt->document[$i - 1]->detail as $key => $row)
                                    @if($row->source)
                                        <div class="input-group download">
                                            <a href="{{ asset($row->source) }}" class="" dl-id="44" download="" target="_blank">
                                                <b><i class="fas fa-download"></i></b> Download - File {{ $n++ }}
                                            </a>
                                            <button type="button" class="btn btn-transparent text-md p-0 pl-2 float-right" onclick="removeFile($(this))" data-doc="{{ $i }}" data-id="{{ $row->id }}">
                                                <i class="fas fa-trash text-maroon color-palette"></i>
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="hidden" name="file_contract[{{ $i }}][]">
                                        <input type="file" class="custom-file-input" name="file[{{ $i }}][]" onchange="changePath(this)">
                                        <label class="custom-file-label" for="exampleInputFile">Attach Image</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
                                    </div>
                                    <button type="button" class="btn btn-transparent text-md" onclick="addUpload($(this))" data-doc="{{ $i }}">
                                        <i class="fas fa-plus text-green color-palette"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="mb-1"></div>
                                @if($contractreceipt->document[$i - 1]->date_uploaded)
                                    {{ date("d/m/Y", strtotime($contractreceipt->document[$i - 1]->date_uploaded)) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                <input type="hidden" name="deleted_file_id[{{ $i }}]" value="[]">
                                {{-- <button type="button" class="btn btn-transparent text-md" onclick="removeDocument($(this))" data-document="{{ $i }}">
                                    <i class="fas fa-trash text-maroon color-palette"></i>
                                </button> --}}
                                <div class="mb-1"></div>
                                #
                            </td>
                        </tr>
                    @endfor
                </tbody>
              </table>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn bg-olive color-palette btn-labeled legitRipple text-sm btn-sm">
                <b><i class="fas fa-save"></i></b>
                Save
              </button>
              <a href="{{route('contractreceipt.index')}}" class="btn btn-sm btn-secondary color-palette btn-labeled legitRipple text-sm">
                <b><i class="fas fa-times"></i></b>
                Cancel
              </a>
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
  const summernote = () =>{
    $('.summernote').summernote({
    	height:145,
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

  const addDocument = () => {
    var number  = $('table').find('tr:last').data('number') ? $('table').find('tr:last').data('number') + 1 : 1;
    var dateNow = moment().format('DD/MM/YYYY');
    var html = `
    <tr data-number="${number}">
      <td class="text-center">
        <div class="mb-1"></div>
        ${number}
      </td>
      <td>
        <input type="hidden" name="contract_document_receipts[]" value="${number}">
        <input type="text" class="form-control" id="document_name_${number}" name="document_name[${number}]" placeholder="Document Name" aria-required="true">
      </td>
      <td>
        <div class="input-group">
          <div class="custom-file">
            <input type="hidden" name="file_contract[${number}][]">
            <input type="file" class="custom-file-input" name="file[${number}][]" onchange="changePath(this)">
            <label class="custom-file-label" for="exampleInputFile">Attach Image</label>
          </div>
          <div class="input-group-append">
            <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
          </div>
          <button type="button" class="btn btn-transparent text-md" onclick="addUpload($(this))" data-doc="${number}">
            <i class="fas fa-plus text-green color-palette"></i>
          </button>
        </div>
      </td>
      <td class="text-center">
        <div class="mb-1"></div>
        ${dateNow}
      </td>
      <td class="text-center">
        <button type="button" class="btn btn-transparent text-md" onclick="removeDocument($(this))" data-document=${number}>
          <i class="fas fa-trash text-maroon color-palette"></i>
        </button>
      </td>
    </tr>
    `;
    $('#table-document tbody .empty').remove();
    $('#table-document tbody').append(html);
  }

  const addUpload = (e) => {
    var number = e.attr("data-doc");
    var html = `
        <div class="input-group">
            <div class="custom-file">
                <input type="hidden" name="file_contract[${number}][]">
                <input type="file" class="custom-file-input" name="file[${number}][]" onchange="changePath(this)">
                <label class="custom-file-label" for="exampleInputFile">Attach Image</label>
            </div>
            <div class="input-group-append">
                <span class="input-group-text" id=""><i class="fa fa-upload"></i></span>
            </div>
            <button type="button" class="btn btn-transparent text-md" onclick="removeUpload($(this))">
                <i class="fas fa-trash text-maroon color-palette"></i>
            </button>
        </div>
    `;
    e.parents("td").append(html);
    // alert("oke");
  }

  const removeUpload = (e) => {
    e.parent().remove();
  }

  const removeFile = (e) => {
    var number = e.attr("data-doc");
    var id = e.attr("data-id");
    var deleted = JSON.parse($('input[name="deleted_file_id['+number+']"]').val());
    deleted.push(id);
    $('input[name="deleted_file_id['+number+']"]').val(JSON.stringify(deleted));
    e.parent().remove();
  }

  const changePath = (that) => {
    let filename = $(that).val();
    $(that).next().html(filename);
  }

  const removeDocument = (a) => {
    var number = a.attr('data-document');
    $("#table-document tbody").find("tr[data-number="+number+"]").remove();
    var is_empty  = !$.trim($("#table-document tbody").html());
    if (is_empty) {
      html  = `
          <tr class="empty">
            <td colspan="5" class="text-center">Document Not Available</td>
          </tr>
      `;
      $("#table-document tbody").append(html);
    }
  }

  $(function(){
    summernote();
    $(".select2").select2();

    $('#contract_id').select2({
      ajax: {
        url: "{{ route('contractreceipt.selectcontract') }}",
        type: "GET",
        dataType: "json",
        data: function (params) {
          return {
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function (data, params) {
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item){
            option.push({
              id:item.id,
              text: item.title,
              number: item.number,
              exp_status: item.exp_status,
              contract_date: item.contract_date,
            });
          });
          return { results: option, more: more };
        },
      },
      allowClear: true,
      templateSelection: selectionContract,
      templateResult: resultContract
    });
    $("#contract_id").select2("trigger", "select", {
        data: {
            id:'{{ $contractreceipt->contract_id }}', 
            text:'{{ $contractreceipt->contract->title }}',
            number: '{{ $contractreceipt->contract->number }}',
            exp_status: '{{ $contractreceipt->contract->exp_status }}',
            contract_date: '{{ $contractreceipt->contract->contract_signing_date }}',
        }
    });

    $('#warehouse_id').select2({
      ajax: {
        url: "{{ route('warehouse.select') }}",
        type: "GET",
        dataType: "json",
        data: function (params) {
          return {
            name: params.term,
            page: params.page,
            limit: 30,
          };
        },
        processResults: function (data, params) {
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item){
            option.push({
              id:item.id,
              text: item.name,
            });
          });
          return { results: option, more: more };
        },
      },
      allowClear: true,
    });
    $("#warehouse_id").select2("trigger", "select", {
        data: {
            id:'{{ $contractreceipt->warehouse_id }}', 
            text:'{{ $contractreceipt->warehouse->name }}',
        }
    });

    $('#batch_id').select2({
      ajax: {
        url: "{{ route('contractreceipt.selectbatch') }}",
        type: "GET",
        dataType: "json",
        data: function (params) {
          var contract_id = $("#contract_id").val();
          return {
            name: params.term,
            page: params.page,
            limit: 30,
            contract_id: contract_id,
          };
        },
        processResults: function (data, params) {
          var more    = (params.page * 30) < data.total;
          var option  = [];
          $.each(data.rows, function(index, item){
            option.push({
              id:item.no,
              text: "Batch "+item.no,
            });
          });
          return { results: option, more: more };
        },
      },
      allowClear: true,
    });
    $("#batch_id").select2("trigger", "select", {
        data: {
            id:'{{ $contractreceipt->batch }}',
            text: "Batch "+'{{ $contractreceipt->batch }}',
        }
    });

    $("#contract_id").on("change",() => {
        var contract_id = $("#contract_id").val();
        if(contract_id){
            var number = $("#contract_id").select2("data")[0].number;
            var contract_date = $("#contract_id").select2("data")[0].contract_date;
            $("#contract_number").val(number);
            $("#contract_date").val(contract_date);
            $("#batch_id").removeAttr("disabled");
        }else{
            $("#contract_number").val("");
            $("#contract_date").val("");
            $("#batch_id").attr("disabled","disabled");
        }
    })

    $("#form").validate({
        errorElement: 'span',
        errorClass: 'help-block',
        focusInvalid: false,
        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
            $(e).remove();
        },
        errorPlacement: function (error, element) {
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
                url:$('#form').attr('action'),
                method:'post',
                data: new FormData($('#form')[0]),
                processData: false,
                contentType: false,
                dataType: 'json', 
                beforeSend:function(){
                    blockMessage('#content', 'Loading', '#fff');
                }
            }).done(function(response){
                $('#content').unblock();
                if(response.status){
                    document.location = response.results;
                }else{	
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
                    toastr.warning(response.message);
                }
                return;
            }).fail(function(response){
                $('#content').unblock();
                var response = response.responseJSON;
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
                toastr.warning(response.message);
            })	
        }
    });

  });

    function resultContract(state){
        if (!state.id) {
            return state.text;
        }
        var $state = $(`
            <span>${state.text}</span><span class="float-right">${state.number}</span><br>
            <small>${state.exp_status}</small>
        `);
        return $state;
    }

    function selectionContract(state) {
        if (!state.id) {
            return state.text;
        }
        var $state = $(`<span>${state.text}</span> - <span>${state.number}</span>`);
        return $state;
    };
</script>
@endsection