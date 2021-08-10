$('#area_id').on('change', function() {
  $("#choose-equipment").select2("trigger", "select", {
    data: {id:'', text:''}
  });
});

function changeUnit(me){
  $("#area_id").select2("trigger", "select", {
    data: {id:'', text:''}
  });
  $("#choose-equipment").select2("trigger", "select", {
    data: {id:'', text:''}
  });
}

$('#reservation').daterangepicker();
$('.select2').select2();

$('.datepicker').daterangepicker({
    singleDatePicker: true,
    timePicker: false,
    timePickerIncrement: 30,
    locale: {
        format: 'DD/MM/YYYY'
    }
}, function(start, end, label) {

});

$("#area_id").select2({
  ajax: {
    url: base_url + 'api/area/read',
    type:'POST',
    dataType: 'json',
    data: function (params) {
      return {
        name:params.term,
        page:params.page,
        limit:30,
        unit:$('#unit').val(),
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

$( "#choose-equipment" ).select2({
  ajax: {
    url: base_url + 'admin/documentcenter/engineering_action/getequip',
    type:'POST',
    dataType: 'json',
    data: function (params) {
      return {
        query:params.term,
        page:params.page,
        limit:30,
        area:$('#area_id').val(),
      };
    },
    processResults: function (data,params) {
      var more = (params.page * 30) < data.total;
      var option = [];
      $.each(data.rows,function(index,item){
        option.push({
          id:item.id,  
          text:item.equipment_name,
          area:item.area_name,
        });
      })
      return {
        results: option, more: more,
      };
    },
  },
  allowClear: true,
});

$("input[data-bootstrap-switch]").each(function(){
    $(this).bootstrapSwitch('state', $(this).prop('checked'));
});

//Initialize Select2 Elements
$('.select2bs4').select2({
    theme: 'bootstrap4'
});

$('#form-data').submit(function(e) {
    e.preventDefault();

    Swal.fire({
      title: '<text style="font-size:24px;">Are you sure?<text>',
      text: "Data activity can't edited after save !",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3d9970',
      cancelButtonColor: '#d81b60',
      confirmButtonText: "<b>I AM SURE</b>",
      cancelButtonText: "<b>CANCEL</b>",
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
          // console.log(response.data);
          window.location.href = './engineering/designcriteria';
          return;
        }).fail(function(response) {
          var response = response.responseJSON;
          $('body').unblock();
          return;
        });
      }
    });
});

$('#add-activity').on('click', function(e) {
    e.preventDefault();
    var no = $(this).data('urutan') + 1,
        html = `<div class="row item-activity" id="item-${no}">
                  <div class="col-md-1">
                    <span class="text-bold text-sm text-center">
                        ${no}.
                    </span>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <textarea class="form-control summernote" name="desc[]" rows="1" style="resize: none;" placeholder="Enter system & Equipment Desc..."></textarea>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="custom-file">   
                          <input type="file" class="custom-file-input" name="attachment[]" id="exampleInputFile" >
                          <label class="custom-file-label" for="exampleInputFile">Attach a file</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="del col-md-1">
                    <button type="button" class="btn btn-transparent text-md" onclick="removeActivity(this)" data-urutan=${no}><i class="fas fa-trash text-maroon color-palette"></i></button>
                  </div>
                </div>`;

    if (no > 2) {
      $('#item-'+(no-1)).find('.del').hide();
      $('#item-'+(no-1)).find('.col-md-7').removeClass('col-md-7').addClass('col-md-8');
    }
    $(this).data('urutan', no);
    $('#form-activity').append(html);
    bsCustomFileInput.init();
    summernote()
  });

  let removeActivity = (me) => {
    var no = $('#add-activity').data('urutan');

    if (no == $('.item-activity').length) {
      $('#item-'+(no-1)).find('.del').show();
      $('#item-'+(no-1)).find('.col-md-8').removeClass('col-md-8').addClass('col-md-7');
      $('#add-activity').data('urutan', (no-1));
      $(me).parent().parent().remove();
    }
  }

  function summernote(type=null){
    $('.summernote').summernote({
			height:225,
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
     if(type){  
      $.each($('.summernote'), function(kuy, val){
          $(this).summernote('disable');
      })
     }
  }