function tesCek(that){
  $("#btn-submit").attr('disabled', 'disabled')
  var cklur = [];
  $(':checkbox:checked').each(function(i){
    cklur[i] = $(this).val();
  });
  cktot = cklur.length
  actot = $('input[name^=activity]').length
  if(cktot == actot){
    $("#btn-submit").removeAttr('disabled')
  }
}

function tesCek2(that){
  $("#btn-submit").attr('disabled', 'disabled')
  var cklur = [];
  $(':checkbox:checked').each(function(i){
    cklur[i] = $(this).val();
  });
  cktot = cklur.length
  actot = $('input[name^=name]').length
  if(cktot == actot){
    $("#btn-submit").removeAttr('disabled')
  }
}

function onSubmit(status){
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
        beforeSend:function(){
          blockMessage('body', 'Please Wait . . . ', '#fff');
        }
      }).done(function(response) {
        $('body').unblock();
        window.location.href = './activities/sitework';
        return;
      }).fail(function(response) {
        var response = response.responseJSON;
        $('body').unblock();
        return;
      });
    }
  });
}

$('#form-data').submit(function(e) {
    e.preventDefault();

    Swal.fire({
      title: 'Are you sure?',
      text: "Please check field again !",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3d9970',
      cancelButtonColor: '#d81b60',
      confirmButtonText: "Yes, i am sure"
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: $(this).attr('action'),
          method: 'post',
          data: new FormData($('#form-data')[0]),
          processData: false,
          contentType: false,
          dataType: 'json',
          beforeSend:function(){
            blockMessage('body', 'Please Wait . . . ', '#fff');
          }
        }).done(function(response) {
          $('body').unblock();
          window.location.href = './activities/sitework';
          return;
        }).fail(function(response) {
          var response = response.responseJSON;
          $('body').unblock();
          return;
        });
      }
    });
});

$('.datepicker').daterangepicker({
    // singleDatePicker: true,
    singleDatePicker: false,
    timePicker: false,
    timePickerIncrement: 30,
    locale: {
      format: 'DD/MM/YYYY'
    }
  }, function(start, end, label) {
    
});

  $('#check-status').on('switchChange.bootstrapSwitch', function (e, data) {
    if ($(this).bootstrapSwitch('state') === true) {
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
        if (!result.value) {
           $(this).bootstrapSwitch('state', !data, false);
        }
      });
    }
  }); 

  $('#add-activity').on('click', function(e) {
    e.preventDefault();
    var no = $(this).data('urutan') + 1,
        key = $(this).data('key') + 1,
        html = `<div class="row item-activity" id="item-${no}">
                  <div class="col-md-6">
                    <div class="form-group"> 
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text text-bold text-sm">
                            ${no}
                          </span>
                        </div>
                        <input type="text" name="activity[]" class="form-control form-activity" placeholder="Activity Desciprtion..." autocomplete="off" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <div class="row-target-${key}">
                        <div class="row items-target-${key}">
                          <div class="col-md-10">
                            <div class="input-group mb-1">
                              <div class="input-group-prepend">
                                <span class="input-group-text">
                                  <i class="far fa-calendar-alt"></i>
                                </span>
                              </div>
                              <input type="text" name="target_${key}[]" class="form-control input-md text-right text-md datepicker" placeholder="Enter target..." autocomplete="off"/>
                            </div>
                          </div>
                          <div class="col-md-2">
                            <button id="target-${key}" onclick="addTarget(this)" data-key="${key}" data-urutan="1" type="button" class="btn btn-success legitRipple text-sm">
                              <b><i class="fas fa-plus"></i></b>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <button type="button" class="btn btn-transparent text-md" onclick="removeActivity(this)" data-urutan=${no}><i class="fas fa-times text-maroon color-palette"></i></button>
                  </div>
                </div>`;

    if (no > 2) {
      $('#item-'+(no-1)).find('.col-md-1').hide();
      $('#item-'+(no-1)).find('.col-md-6').removeClass('col-md-6').addClass('col-md-7');
    }
    $(this).data('urutan', no);
    $(this).data('key', key);
    $('#form-activity').append(html);
    $('.datepicker').daterangepicker({
        // singleDatePicker: true,
        singleDatePicker: false,
        timePicker: false,
        timePickerIncrement: 30,
        locale: {
          format: 'DD/MM/YYYY'
        }
      }, function(start, end, label) {
      
    });
  });

  let removeActivity = (me) => {
    var no = $('#add-activity').data('urutan');
    var key = $('#add-activity').data('key');

    if (no == $('.item-activity').length) {
      $('#item-'+(no-1)).find('.col-md-1').show();
      $('#item-'+(no-1)).find('.col-md-7').removeClass('col-md-7').addClass('col-md-6');
      $('#add-activity').data('urutan', (no-1));
      $('#add-activity').data('key', (key-1));
      $(me).parent().parent().remove();
    }
  }

  $('#btn-next-step').on('click', function(e) {
    e.preventDefault();

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
      "timeOut": "10000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

    if (!$('input[name=subject]').val()) {
      toastr.warning("Please fill subject.");
      $('input[name=subject]').focus();
      return;
    } else if (!$('input[name^=activity]').get(0).value || !$('input[name^=target]').get(0).value) {
      toastr.warning("Please input one activity or more and fill completely this form to the next step.");
      $('input[name^=activity]').get(0).focus();
      return;
    }
    // show box result
    $('.first-card').fadeOut("fast");
    $('.first-card').addClass('d-none');
    $('.result-activity').removeClass('d-none');
    $('.result-activity').fadeIn('fast');
    $(this).hide();
    $('#btn-prev').fadeIn("fast");
    $('#btn-save').fadeIn("fast");
    $('#btn-print').fadeIn("fast");
    $('#btn-submit').fadeIn("fast");
    var html = '',
        form = '';
    $.each($('input[name^=activity]'), function (key, value) {
      // var target = $('input[name^=target]').get(key).value;
      var target = $(`input[name^=target_${key}]`);
      let target_val = '';
      $.each(target, function(kuy, val){
          target_val += val.value+'<br>'
      })
      if (this.value && target!="") {
        // <td width="5%">${(key+1)}</td>
        html += `<tr>
                <td width="15%">${this.value}</td>
                <td width="30%">${target_val}</td>
                <td width="15%">
                  <div class="row-calendar-${key}">
                    <div class="row items-calendar-${key}">
                      <div class="col-12">
                        <div class="input-group mb-1">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <i class="far fa-calendar-alt"></i>
                            </span>
                          </div>
                          <input type="text" name="date_act_${key}[]" autocomplete="off" class="form-control input-sm text-right text-sm datepicker" placeholder="Choose date">
                        </div>
                      </div>
                    </div>
                  </div>
                  <button type="button" onclick="addCalendar(this)" data-urutan="1" data-key="${key}" class="btn btn-xs bg-success color-palette btn-labeled legitRipple text-sm float-left mt-1 ">
                    <b><i class="fa fa-plus"></i></b>
                    Add
                  </button>
                </td>
                <td width="25%">
                  <textarea name="reason[]" class="form-control" placeholder="Remarks" rows="1" ></textarea>
                </td>
                <td width="5% class="text-center">
                  <button type="button" class="btn btn-xs bg-warning color-palette legitRipple text-sm" data-toggle="modal" data-target="#image-${key}">
                    <b><i class="fa fa-image"></i></b>
                  </button>
                  <div class="modal fade" id="image-${key}">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Upload</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="custom-file">   
                                <input type="file" class="custom-file-input" name="attach_${key}" onchange="changePath(this)">
                                <label class="custom-file-label" for="exampleInputFile">Attach a file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div
                    </div>
                  </div>
                </td>
                <td width="5%" class="text-center mt-1">
                  <div class="icheck-success d-inline">
                    <input type="checkbox" id="status${key}" name="status_${key}" onclick="tesCek(this)">
                    <label for="status${key}">
                    </label>
                  </div>
                </td>
              </tr>`;
      }
    });

    $('#content-list-activity').html(html);

    $('.datepicker').daterangepicker({
      // singleDatePicker: true,
      singleDatePicker: false,
      timePicker: false,
      timePickerIncrement: 30,
      locale: {
        format: 'DD/MM/YYYY'
      }
    }, function(start, end, label) {
      
    });
  });

  $('#add-act').on('click', function(e) {
    e.preventDefault();
    var no = $(this).data('urutan') + 1,
        key = $(this).data('key') + 1,
        html = `<tr class="item-activity" id="item-${no}">
        <td width="25%">
          <input type="text" name="name[]" class="form-control form-activity" placeholder="Activity Description..." autocomplete="off" required >
        </td>
        <td width="20%">
          <div class="row-target-${key}">
            <div class="row items-target-${key}">
              <div class="col-12">
                <div class="input-group mb-1">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" name="target_${key}[]" autocomplete="off" class="form-control input-sm text-right text-sm datepicker" placeholder="Choose date" />
                </div>
              </div>
            </div>
          </div>
          <button type="button" data-act="update" onclick="addTarget(this)" data-urutan="1" data-key="${key}" class="btn btn-xs bg-success color-palette btn-labeled legitRipple text-sm float-left mt-1 ">
            <b><i class="fa fa-plus"></i></b>
            Add
          </button>
        </td>
        <td width="20%">
            <div class="row-calendar-${key}">
              <div class="row items-calendar-${key}">
                <div class="col-12">
                  <div class="input-group mb-1">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" name="date_act_${key}[]" autocomplete="off" class="form-control input-sm text-right text-sm datepicker" placeholder="Choose date" />
                  </div>
                </div>
              </div>
            </div>
            <button type="button" onclick="addCalendar(this)" data-urutan="1" data-key="${key}" class="btn btn-xs bg-success color-palette btn-labeled legitRipple text-sm float-left mt-1 ">
              <b><i class="fa fa-plus"></i></b>
              Add
            </button>
        </td>
        <td width="20%">
          <textarea name="reason[]" class="form-control" placeholder="Remarks" rows="1" ></textarea>
        </td>
        <td width="5%" class="text-center mt-1">
          <div class="icheck-success d-inline">
            <input type="checkbox" id="status${key}" name="status_${key}" onclick="tesCek2(this)">
            <label for="status${key}">
            </label>
          </div>
        </td>
        <td width="5%" class="text-center">
          <button type="button" class="btn btn-transparent text-md del" onclick="removeAct(this)" data-urutan=${no}><i class="fas fa-times text-maroon color-palette"></i></button>
        </td>
      </tr>`
    if (no > 2) {
      $('#item-'+(no-1)).find('.del').hide();
    }
    $(this).data('urutan', no);
    $(this).data('key', key);
    $('#content-list-activity').append(html);
    $('.datepicker').daterangepicker({
      // singleDatePicker: true,
      singleDatePicker: false,
      timePicker: false,
      timePickerIncrement: 30,
      locale: {
        format: 'DD/MM/YYYY'
      }
    }, function(start, end, label) {
      
    });
  });

  let removeAct = (me) => {
    var no = $('#add-act').data('urutan'),
        key = $('#add-act').data('key')
    console.log(no,$('.item-activity').length)

    if (no == $('.item-activity').length) {
      $('#item-'+(no-1)).find('.del').show();
      $('#add-act').data('urutan', (no-1));
      $('#add-act').data('key', (key-1));
      $(me).parent().parent().remove();
    }
  }

  let addCalendar = (me) => {
    var no   = $(me).data('urutan') + 1,
        key  = $(me).data('key'),
        html = `<div class="row items-calendar-${key}" id="calendar-${key}-${no}">
                  <div class="col-10 pr-0">
                    <div class="input-group mb-1">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="far fa-calendar-alt"></i>
                        </span>
                      </div>
                      <input type="text" name="date_act_${key}[]" class="form-control input-sm text-right text-sm datepicker" autocomplete="off" placeholder="Choose date">
                    </div>
                  </div>
                  <div class="col-1 pl-0">
                    <button type="button" class="btn btn-transparent text-md" onclick="removeCalendar(this)" data-urutan="${no}" data-key="${key}"><i class="fas fa-trash text-maroon color-palette"></i></button>
                  </div>
                </div>`;

    if (no > 2) {
      $('#calendar-'+key+'-'+(no-1)).find('.col-1').removeClass('pl-0').hide();
      $('#calendar-'+key+'-'+(no-1)).find('.col-10').removeClass('col-10').removeClass('pr-0').addClass('col-12');
    }

    $(me).data('urutan', no);
    $(me).prev('.row-calendar-'+key).append(html);
    $('.datepicker').daterangepicker({
      // singleDatePicker: true,
      singleDatePicker: false,
      timePicker: false,
      timePickerIncrement: 30,
      locale: {
        format: 'DD/MM/YYYY'
      }
    }, function(start, end, label) {
      
    });
  }

  let removeCalendar = (me) => {
    var key = $(me).data('key'),
        no  = $(me).data('urutan'),
        add = $(me).parent().parent('.items-calendar-'+key).parent().next('button'),
        keyAdd = add.data('key');

    if (no == $('.items-calendar-' + key).length) {
      $("#calendar-"+key+"-"+(no-1)).find('.col-1').addClass('pl-0').show();
      $("#calendar-"+key+"-"+(no-1)).find('.col-12').removeClass('col-12').addClass('col-10').addClass('pr-0');
      add.data('urutan', (no-1));
      $(me).parent().parent().remove();
    }
  }

  let addTarget = (me) => {
    let act  = $(me).data('act')
    let clas = (act=='update')?'input-sm text-sm':'input-md text-md'
    var no   = $(me).data('urutan') + 1,
        key  = $(me).data('key'),
        html = `<div class="row items-target-${key}" id="target-${key}-${no}">
                  <div class="col-md-10 pr-0">
                    <div class="input-group mb-1">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="far fa-calendar-alt"></i>
                        </span>
                      </div>
                      <input type="text" name="target_${key}[]" class="form-control ${clas} text-right datepicker" autocomplete="off" placeholder="Choose date">
                    </div>
                  </div>
                  <div class="col-md-2 pl-0">
                    <button type="button" class="btn btn-transparent text-md" onclick="removeTarget(this)" data-act="${act}" data-urutan="${no}" data-key="${key}"><i class="fas fa-trash text-maroon color-palette"></i></button>
                  </div>
                </div>`;

    if (no > 2) {
      $('#target-'+key+'-'+(no-1)).find('.col-md-2').removeClass('pl-0').hide();
      if(act=='update'){
        $('#target-'+key+'-'+(no-1)).find('.col-md-10').removeClass('col-md-10').removeClass('pr-0').addClass('col-md-12');
      }
    }

    $(me).data('urutan', no);
    if(act == 'update'){
      $(me).prev().append(html);
    } else {
      $(me).parents('div.row-target-'+key).append(html);
    }
    $('.datepicker').daterangepicker({
        // singleDatePicker: true,
        singleDatePicker: false,
        timePicker: false,
        timePickerIncrement: 30,
        locale: {
          format: 'DD/MM/YYYY'
        }
      }, function(start, end, label) {
      
    });
  }

  let removeTarget = (me) => {
    var key = $(me).data('key'),
        no  = $(me).data('urutan'),
        act  = $(me).data('act');
        // keyAdd = add.data('key');
    if(act == 'update'){
      var add = $(me).parent().parent('.items-target-'+key).parent().next('button')
    } else {
      var add = $('#target-'+key)
    }

        console.log(no, $('.items-target-' + key).length)

    if (no == $('.items-target-' + key).length) {
      $("#target-"+key+"-"+(no-1)).find('.col-md-2').addClass('pl-0').show();
      if(act=='update'){
        $("#target-"+key+"-"+(no-1)).find('.col-md-12').removeClass('col-md-12').addClass('col-md-10').addClass('pr-0');
      }
      add.data('urutan', (no-1));
      $(me).parent().parent().remove();
    }
  }

  $('#btn-prev').on('click', function(e) {
    e.preventDefault();
    $('.result-activity').fadeOut('fast');
    $('.result-activity').addClass('d-none');
    $('.first-card').fadeIn("fast");
    $('.first-card').removeClass('d-none');
    $(this).hide(); 
    $('#btn-next-step').fadeIn("fast");
    $('#btn-prev').fadeOut("fast");
    $('#btn-save').fadeOut("fast");
    $('#btn-print').fadeOut("fast");
    $('#btn-submit').fadeOut("fast");
  });

  $('.select2').select2();
  $('.select-person').select2({
    tags: true
  });

  $("input[data-bootstrap-switch]").each(function(){
    $(this).bootstrapSwitch('state', $(this).prop('checked'));
  });

  $("#group_id").select2({
    ajax: {
      url: base_url + 'api/group/read',
      type:'POST',
      dataType: 'json',
      data: function (params) {
        return {
          group_description:params.term,
          page:params.page,
          limit:30
        };
      },
      processResults: function (data,params) {
        var more = (params.page * 30) < data.total;
        var option = [];
        $.each(data.rows,function(index,item){
        option.push({
          id:item.id,  
          text: item.group_description
        });
        });
        return {
        results: option, more: more,
        };
      },
    },
    allowClear: true,
  });

  function changePath(that) {
		let filename = $(that).val()
		$(that).next().html(filename)
	}