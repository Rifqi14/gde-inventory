$('#reservation').daterangepicker();
  $('.select2').select2();
  $('.select-mom').select2();

$("input[data-bootstrap-switch]").each(function(){
    $(this).bootstrapSwitch('state', $(this).prop('checked'));
});

//Initialize Select2 Elements
$('.select2bs4').select2({
theme: 'bootstrap4'
});

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

// $('#form-data').submit(function(e) {
//     e.preventDefault();

//     Swal.fire({
//       title: 'Are you sure?',
//       text: "Please check the data again !",
//       icon: 'warning',
//       showCancelButton: true,
//       confirmButtonColor: '#3d9970',
//       cancelButtonColor: '#d81b60',
//       confirmButtonText: "Submit"
//     }).then((result) => {
//       if (result.value) {
//         $.ajax({
//           url: $(this).attr('action'),
//           method: 'post',
//           data: new FormData($('#form-data')[0]),
//           processData: false,
//           contentType: false,
//           dataType: 'json',
//           beforeSend:function(){
//             blockMessage('body', 'Please Wait . . . ', '#fff');
//           }
//         }).done(function(response) {
//           $('body').unblock();
//           window.location.href = './businesstrip/application';
//           return;
//         }).fail(function(response) {
//           var response = response.responseJSON;
//           $('body').unblock();
//           return;
//         });
//       }
//     });
// });

function onSubmit(status){
  let mom = $('#mom').val()

  if(mom.length == 0){
    toastr.warning("Please fill Activities.");
    $('#mom').focus();
    return;
  }

  Swal.fire({
    title: 'Are you sure?',
    text: "Please check the data again !",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3d9970',
    cancelButtonColor: '#d81b60',
    confirmButtonText: "Submit"
  }).then((result) => {
    if (result.value) {
      $.ajax({
        url: $('#form-data').attr('action'),
        method: 'post',
        data: `${$('#form-data').serialize()}&status=${status}`,
        dataType: 'json',
        beforeSend:function(){
          blockMessage('body', 'Please Wait . . . ', '#fff');
        }
      }).done(function(response) {
        $('body').unblock();
        window.location.href = './businesstrip';
        return;
      }).fail(function(response) {
        var response = response.responseJSON;
        $('body').unblock();
        return;
      });
    }
  });
}

function onSubmitReport(status){
  let mom = $('#mom').val()

  if(mom.length == 0){
    toastr.warning("Please fill Meetings.");
    $('#mom').focus();
    return;
  }

  Swal.fire({
    title: 'Are you sure?',
    text: "Please check the data again !",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3d9970',
    cancelButtonColor: '#d81b60',
    confirmButtonText: "Submit"
  }).then((result) => {
    if (result.value) {
      $.ajax({
        url: $('#form-data').attr('action'),
        method: 'post',
        data: `${$('#form-data').serialize()}&status=${status}`,
        dataType: 'json',
        beforeSend:function(){
          blockMessage('body', 'Please Wait . . . ', '#fff');
        }
      }).done(function(response) {
        $('body').unblock();
        window.location.href = './businesstrip/report';
        return;
      }).fail(function(response) {
        var response = response.responseJSON;
        $('body').unblock();
        return;
      });
    }
  });
}

$('#add-depart').on('click', function(e) {
    e.preventDefault();
    var no = $(this).data('urutan') + 1,
        html = `<div class="row item-depart" id="depart-${no}">
                    <div class="col-md-2 edit">
                        <div class="form-group">
                        <input type="hidden" name="type[]" value="depart" />
                        <select class="form-control" name="type_transportation[]">
                            <option value="flight" selected>Flight</option>
                            <option value="others">Others</option>
                        </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <input type="text" name="trans_description[]" class="form-control" placeholder="Please enter description..." value="" />
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
                            <input type="number" name="trans_price[]" class="form-control" placeholder="Enter price price..." value="0">
                        </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-transparent text-md" onclick="removeDepart(this)" data-urutan=${no}><i class="fas fa-trash text-maroon color-palette"></i></button>
                    </div>
                </div>`;

    if (no > 2) {
      $('#depart-'+(no-1)).find('.col-md-1').hide();
      $('#depart-'+(no-1)).find('.edit').removeClass('col-md-2').addClass('col-md-3');
    }
    $(this).data('urutan', no);
    $('#form-depart').append(html);
  });

  let removeDepart = (me) => {
    var no = $('#add-depart').data('urutan');

    if (no == $('.item-depart').length) {
      $('#depart-'+(no-1)).find('.col-md-1').show();
      $('#depart-'+(no-1)).find('.edit').removeClass('col-md-3').addClass('col-md-2');
      $('#add-depart').data('urutan', (no-1));
      $(me).parent().parent().remove();
    }
  }

$('#add-return').on('click', function(e) {
    e.preventDefault();
    var no = $(this).data('urutan') + 1,
        html = `<div class="row item-return" id="return-${no}">
                    <div class="col-md-2 edit">
                        <div class="form-group">
                        <input type="hidden" name="type[]" value="return" />
                        <select class="form-control" name="type_transportation[]">
                            <option value="flight">Flight</option>
                            <option value="others" selected>Others</option>
                        </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <input type="text" name="trans_description[]" class="form-control" placeholder="Please enter description..." value="" />
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
                            <input type="number" name="trans_price[]" class="form-control" placeholder="Enter price..." value="0">
                        </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-transparent text-md" onclick="removeReturn(this)" data-urutan=${no}><i class="fas fa-trash text-maroon color-palette"></i></button>
                    </div>
                </div>`;

    if (no > 2) {
      $('#return-'+(no-1)).find('.col-md-1').hide();
      $('#return-'+(no-1)).find('.edit').removeClass('col-md-2').addClass('col-md-3');
    }
    $(this).data('urutan', no);
    $('#form-return').append(html);
  });

  let removeReturn = (me) => {
    var no = $('#add-return').data('urutan');

    if (no == $('.item-return').length) {
      $('#return-'+(no-1)).find('.col-md-1').show();
      $('#return-'+(no-1)).find('.edit').removeClass('col-md-3').addClass('col-md-2');
      $('#add-return').data('urutan', (no-1));
      $(me).parent().parent().remove();
    }
  }

$('#add-lodging').on('click', function(e) {
    e.preventDefault();
    var no = $(this).data('urutan') + 1,
        html = `<div class="row item-lodging" id="item-${no}">
                    <div class="col-md-5">
                        <div class="form-group">
                            <input type="text" name="place_lodging[]" class="form-control" placeholder="Enter where lodging..." value="">
                        </div>
                    </div>
                    <div class="col-md-4">
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
                        <input type="number" name="night_lodging[]" class="form-control" placeholder="Enter night lodging..." value="1">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-transparent text-md" onclick="removeLodging(this)" data-urutan=${no}><i class="fas fa-trash text-maroon color-palette"></i></button>
                    </div>
                </div>`;

    if (no > 2) {
      $('#item-'+(no-1)).find('.col-md-1').hide();
      $('#item-'+(no-1)).find('.col-md-5').removeClass('col-md-5').addClass('col-md-6');
    }
    $(this).data('urutan', no);
    $('#form-lodging').append(html);
  });

  let removeLodging = (me) => {
    var no = $('#add-lodging').data('urutan');

    if (no == $('.item-lodging').length) {
      $('#item-'+(no-1)).find('.col-md-1').show();
      $('#item-'+(no-1)).find('.col-md-6').removeClass('col-md-6').addClass('col-md-5');
      $('#add-lodging').data('urutan', (no-1));
      $(me).parent().parent().remove();
    }
  }

  $('#add-others').on('click', function(e) {
    e.preventDefault();
    var no = $(this).data('urutan') + 1,
        html = `<div class="row item-others" id="item-${no}">
                    <div class="col-md-5">
                        <div class="form-group">
                          <input type="text" name="others_desc[]" class="form-control" placeholder="Enter description..." value="">
                        </div>
                    </div>
                    <div class="col-md-4">
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
                      <input type="number" name="others_qty[]" class="form-control" placeholder="Enter qty..." value="1">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-transparent text-md" onclick="removeOthers(this)" data-urutan=${no}><i class="fas fa-trash text-maroon color-palette"></i></button>
                    </div>
                </div>`;

    if (no > 2) {
      $('#item-'+(no-1)).find('.col-md-1').hide();
      $('#item-'+(no-1)).find('.col-md-5').removeClass('col-md-5').addClass('col-md-6');
    }
    $(this).data('urutan', no);
    $('#form-others').append(html);
  });

  let removeOthers = (me) => {
    var no = $('#add-others').data('urutan');

    if (no == $('.item-others').length) {
      $('#item-'+(no-1)).find('.col-md-1').show();
      $('#item-'+(no-1)).find('.col-md-6').removeClass('col-md-6').addClass('col-md-5');
      $('#add-others').data('urutan', (no-1));
      $(me).parent().parent().remove();
    }
  }