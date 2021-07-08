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
};

$(function () {
    $("#product_id").prop("disabled", true);
    summernote();
    $('.select2').select2();
    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        timePicker: false,
        timePickerIncrement: 30,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    let optionsFile = {
        browseClass: "btn btn-primary",
        showRemove: true,
        showUpload: false,
        allowedFileExtensions: ["png", "jpg", "jpeg"],
        dropZoneEnabled: true,
        theme: 'fas',
        uploadAsync: false,
    }

    $("#images").fileinput(optionsFile);

    $('#site_id').select2({
        ajax: {
            url: urlSelectSite,
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
                var more = (params.page * 30) < data.total;
                var option = [];
                $.each(data.rows, function (index, item) {
                    option.push({
                        id: item.id,
                        text: item.name,
                    });
                });
                return { results: option, more: more };
            },
        },
        allowClear: true,
    });

    $('#warehouse_id').select2({
        ajax: {
            url: urlSelectWarehouse,
            type: "GET",
            dataType: "json",
            data: function (params) {
                return {
                    name: params.term,
                    page: params.page,
                    site_id: $('#site_id').val(),
                    limit: 30,
                };
            },
            processResults: function (data, params) {
                var more = (params.page * 30) < data.total;
                var option = [];
                $.each(data.rows, function (index, item) {
                    option.push({
                        id: item.id,
                        text: item.name,
                    });
                });
                return { results: option, more: more };
            },
        },
        allowClear: true,
    });

    $("#product_id").select2({
        ajax: {
            url: urlSelectProduct,
            type: 'GET',
            dataType: 'json',
            data: function (params) {
                return {
                    contract: `1`,
                    category: $('#category_product_id').find('option:selected').val(),
                    warehouse_id: $('#warehouse_id').find('option:selected').val(),
                    selected_product_id: $('input[name="product_id[]"]').map(function () { return $(this).val(); }).get(),
                    name: params.term,
                    page: params.page,
                    limit: 30,
                };
            },
            processResults: function (data, params) {
                var more = (params.page * 30) < data.total;
                var option = [];
                $.each(data.rows, function (index, item) {
                    option.push({
                        id: item.id,
                        text: item.name,
                        product_name: item.name,
                        uoms: item.uoms,
                        uom_id: item.uom_id,
                        uom_name: item.uom_name,
                        sku: item.sku,
                        qty: item.qty,
                        stock: item.stock,
                        is_serial: item.is_serial,
                        items: item.items
                    });
                });
                return {
                    results: option, more: more,
                };
            },
        },
        allowClear: true,
        templateResult: productTemplateResult
    });

    $("#category_product_id").select2({
        ajax: {
            url: urlSelectProductCategory,
            type: 'GET',
            dataType: 'json',
            data: function (params) {
                return {
                    name: params.term,
                    page: params.page,
                    limit: 30,
                };
            },
            processResults: function (data, params) {
                var more = (params.page * 30) < data.total;
                var option = [];
                $.each(data.rows, function (index, item) {
                    option.push({
                        id: item.id,
                        text: item.name,
                    });
                });
                return {
                    results: option, more: more,
                };
            },
        },
        allowClear: true,
        escapeMarkup: function (text) { return text; }
    }).on('select2:close', function (e) {
        var data = $(this).find('option:selected').val();
        var product = $('#product_id').select2('data');

        if (product[0] && product[0].category_product_id != data) {
            $('#product_id').val(null).trigger('change');
        }
    }).on('select2:clearing', function () {
        $('#product_id').val(null).trigger('change');
    });

    $('#serial_uom_id').select2({
        ajax: {
            url: urlgetUOMProduct,
            type: "GET",
            dataType: "json",
            data: function (params) {
                var product_id = $('#serial_uom_id').attr("data-productid");
                return {
                    name: params.term,
                    page: params.page,
                    product_id: product_id,
                    limit: 30,
                };
            },
            processResults: function (data, params) {
                var more = (params.page * 30) < data.total;
                var option = [];
                $.each(data.rows, function (index, item) {
                    option.push({
                        id: item.uom_id,
                        text: item.uom.name,
                    });
                });
                return { results: option, more: more };
            },
        },
        allowClear: true,
    });

    $("#site_id").on("change", function () {
        $("#warehouse_id").val("").trigger("change");
        $("#warehouse_id").empty();
    });

    $("#warehouse_id").on("change",function(){
        var warehouse_id = $(this).val();
        if (warehouse_id){
            $("#product_id").prop("disabled", false);
            $("#add-product").removeClass("disabled");
        }else{
            $("#product_id").prop("disabled", true);
            $("#add-product").addClass("disabled");
            clearProduct();
        }
    });

    $(document).on("change", ".upload-image input:file", function () {
        var image = $(this).closest('.upload-image').find('img');
        var remove = $(this).closest('.upload-image').find('.remove');
        var fileReader = new FileReader();
        fileReader.onload = function () {
            var data = fileReader.result;
            image.attr('src', data);
            remove.css('display', 'block');
        };
        fileReader.readAsDataURL($(this).prop('files')[0]);
        console.log($(this).val());
    });

    $('.upload-image').on('click', '.remove', function () {
        var image = $(this).closest('.upload-image').find('img');
        var file = $(this).closest('.upload-image').find('input:file');
        file.val('');
        image.attr('src', base_url+'assets/img/no-image.png');
        $(this).css('display', 'none');
    });

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
            if (element.is(':file')) {
                error.insertAfter(element.parent().parent().parent());
            } else if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else if (element.attr('type') == 'checkbox') {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function () {
            $.ajax({
                url: $('#form').attr('action'),
                method: 'post',
                data: new FormData($('#form')[0]),
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function () {
                    blockMessage('#content', 'Loading', '#fff');
                }
            }).done(function (response) {
                $('#content').unblock();
                if (response.status) {
                    toastr.success('Data has been saved.');
                    document.location = response.results;
                } else {
                    toastr.warning(response.message);
                }
                return;
            }).fail(function (response) {
                $('#content').unblock();
                toastr.warning(response.message);
            })
        }
    });
});

function productTemplateResult(state) {
    if (!state.id) {
        return state.product_name;
    }

    var main = `<span>${state.product_name}</span>`;
    var secondary = `<br><small>${state.sku}</small>`;

    var $state = $(
        `${main}
        <span style="float:right"><i>qty : ${state.stock} ${state.uom_name}</i></span>
        ${secondary}`
    );
    return $state;
}

function addDoc(e) {
    var html = `<div class="row doc-item">
            <div class="col-md-5">
                <div class="form-group row">
                    <div class="col-sm-12 controls">
                        <input type="text" class="form-control" name="doc_name[]" placeholder="Description" aria-required="true">
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group row">
                    <div class="col-sm-12 controls">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="doc[]" onchange="changePath(this)">
                                <label class="custom-file-label" for="exampleInputFile" style="border-top-right-radius: .25rem;border-bottom-right-radius: .25rem;">Adjustment Document</label>
                            </div>
                            <div class="input-group-append button-trasparent button-add-doc" onclick="deleteDoc($(this))">
                                <span class="input-group-text" id=""><i class="fas fa-trash text-maroon color-palette"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

    e.parents("#wrapper-add-doc").append(html);
}

function deleteDoc(e) {
    e.parents(".doc-item").remove();
}

function changePath(that) {
    let filename = $(that).val()
    $(that).next().html(filename)
}

function summernote() {
    $('.summernote').summernote({
        height: 145,
        toolbar: [
            ['font-style', ['bold', 'italic', 'underline', 'strikethrough']],
            ['font-size', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
        ]
    });
}


/**
 * 
 * @var adjustment_item
 * [
    {
        "product_id":1,
        "has_serial": true,
        "edited": false,
        "items": [
            {
                "serial_number": "sku-001-001",
                "uom_id": 1,
                "description": "deskripsi product",
                "images": [
                    "img/img1.jpg",
                    "img/img2.jpg",
                    "img/img3.jpg"
                ],
                "docs": [
                    "doc/document1.pdf",
                    "doc/document2.pdf",
                    "doc/document3.pdf"
                ]
            }
        ],
        "added_serial": [
            {
                "serial_number": null,
                "uom_id": 1,
                "description" : "deskripsi product",
                "asset_id": 1
            }
        ],
        "deleted_serial": [
            "sku-001-002",
            "sku-001-003",
            "sku-001-004"
        ]
    }
]
 */

function addproduct() {
    var product_id = $('#product_id').val();
    if (product_id == '' || product_id == null) {
        toastr.warning("Select Product First!!!");
        return;
    }
    removeEmptyTable();
    var product_name = $('#product_id').select2('data')[0].product_name;
    var qty_before = $('#product_id').select2('data')[0].qty;
    var uom_id = $('#product_id').select2('data')[0].uom_id;
    var uom_name = $('#product_id').select2('data')[0].uom_name;
    var has_serial_no = $('#product_id').select2('data')[0].is_serial;
    var serial_no = $('#product_id').select2('data')[0].serial_no;
    var product_sku = $('#product_id').select2('data')[0].sku;
    var stock = $('#product_id').select2('data')[0].stock;
    var items = $('#product_id').select2('data')[0].items;

    var exist = false;
    $('input[name^=product_id]').each(function () {
        if (this.value == product_id) {
            exist = true;
        }
    });
    if (exist) {
        $.gritter.add({
            title: 'Warning',
            text: 'Product already selected!',
        });
        return;
    }

    // add Adjustment Item 
    addAdjustmentItem(product_id, has_serial_no, items, stock);

    // add Table
    var label_has_serial = `<span class="btn bg-maroon btn-xs label-square">
                <i class="fas fa-times"></i>
            </span>`;
        input_stock = `<div class="input-group input-group-sm">
                <a onclick="minproduct($(this), ${product_id})" class="input-group-prepend cursor-pointer">
                    <span class="input-group-text"><i class="fa fa-minus"></i></span>
                </a>
                <input placeholder="Qty" name="product_qty_after[]" class="form-control numberfield text-center" required="" value="${stock}">
                <a onclick="plusproduct($(this), ${product_id})" class="input-group-append cursor-pointer">
                    <span class="input-group-text"><i class="fa fa-plus"></i></span>
                </a>
            </div>`;
    if (has_serial_no == 1){
        label_has_serial = `<span class="btn bg-success btn-xs label-square">
                <i class="fas fa-check"></i>
            </span>`;
        input_stock = stock;
    }

    var html = `<tr class="item-product">
        <td>
            <div class="form-group  mb-0">
                <p class="form-control-static mb-0">
                    <input type="hidden" name="product_item[]">
                    <input type="hidden" name="product_id[]" value="${product_id}">
                    <input type="hidden" name="product_name[]" value="${product_name}">
                    <b>${product_name}</b> <br>
                    <small>${product_sku}</small>
                </p>
            </div>
        </td>
        <td class="text-center">
            <input type="hidden" name="has_serial_no[]" value="${has_serial_no}">
            ${label_has_serial}
        </td>
        <td class="text-center">
            <div class="form-group  mb-0">
                <p class="form-control-static m-0">${uom_name}</p>
            </div>
        </td>
        <td class="text-center">
            <div class="form-group  mb-0">
                <input class="hidden d-none" name="product_qty_before[]" value="${stock}">
                <p class="form-control-static m-0">${stock}</p>
            </div>
        </td>
        <td class="text-center" width="150">
            <div class="form-group  mb-0 product-qty-after">
                ${input_stock}
            </div>
        </td>
        <td class="text-center">
            <input type="hidden" name="product_sku[]" value="${product_sku}">
            <button type="button" class="btn btn-transparent btn-custom-circle edit text-md p-0" onclick="editProduct($(this))" data-id="${product_id}" id="button-edit-item">
                <i class="fas fa-edit color-palette"></i>
            </button>
            <button type="button" class="btn btn-transparent btn-custom-circle delete text-md p-0" onclick="removeProduct($(this))" data-id="${product_id}">
                <i class="fas fa-trash text-maroon color-palette"></i>
            </button>
        </td>
    </tr>`;

    $('#table-product').append(html);
    $("#product_id").empty();
}

function editProduct(e){
    var tr = e.parents("tr");
    var has_serial_no = tr.find('input[name="has_serial_no[]"]').val();
    var product_id = tr.find('input[name="product_id[]"]').val();
    var warehouse_id = $("#warehouse_id").val();
    if (has_serial_no == 1){
        processProductSerialModal(product_id, warehouse_id);
        $("#button-edit-item").removeClass("active");
        e.addClass("active");
    }else{
        $("#noSerialModal").modal("show");
    }
}

function addAdjustmentItem(product_id, has_serial, items, stock){
    var adjustment_item = $("#adjustment_item").val();
    adjustment_item = JSON.parse(adjustment_item);

    var data = new Object();
    data.product_id = product_id;
    data.has_serial = has_serial;
    data.edited = false;
    data.items = items;
    data.qty_before = stock;
    data.qty_after = stock;
    data.added_serial = [];
    data.deleted_serial = [];
    adjustment_item.push(data);

    adjustment_item = JSON.stringify(adjustment_item);
    $("#adjustment_item").val(adjustment_item);
}

function updateQtyNonSerial(product_id, qty){
    console.log(product_id, qty);
    var adjustment_item = $("#adjustment_item").val();
    adjustment_item = JSON.parse(adjustment_item);

    $.each(adjustment_item, function (key, row) {
        if (row.product_id == product_id) {
            adjustment_item[key].qty_after = qty;
        }
    });

    adjustment_item = JSON.stringify(adjustment_item);
    $("#adjustment_item").val(adjustment_item);
}

function removeAdjustmentItem(product_id) {
    var adjustment_item = $("#adjustment_item").val();
    adjustment_item = JSON.parse(adjustment_item);

    adjustment_item = adjustment_item.filter(function (e) { return e.product_id !== product_id });

    adjustment_item = JSON.stringify(adjustment_item);
    $("#adjustment_item").val(adjustment_item);
}

function setActiveModal(product_id){
    var adjustment_item = $("#adjustment_item").val();
    adjustment_item = JSON.parse(adjustment_item);

    $.each(adjustment_item, function (key, row) {
        if (row.product_id === product_id) {
            adjustment_item[key].edited = true;
        }
    });

    adjustment_item = JSON.stringify(adjustment_item);
    $("#adjustment_item").val(adjustment_item);
}

function setNonActiveModal() {
    var adjustment_item = $("#adjustment_item").val();
    adjustment_item = JSON.parse(adjustment_item);

    $.each(adjustment_item, function (key, row) {
        if (row.edited) {
            adjustment_item[key].edited = false;
        }
    });

    adjustment_item = JSON.stringify(adjustment_item);
    $("#adjustment_item").val(adjustment_item);
}

//global variable asset id

// push new serial
function pushNewSerial(){
    var adjustment_item = $("#adjustment_item").val();
    adjustment_item = JSON.parse(adjustment_item);
    id = makeid(10);

    $.each(adjustment_item, function (key, row) {
        if (row.edited) {
            var new_serial = new Object();
            new_serial.serial_number = null;
            new_serial.uom_id = null;
            new_serial.uom_name = null;
            new_serial.description = null;
            new_serial.asset_id = id;
            adjustment_item[key].added_serial.push(new_serial);
        }
    });

    adjustment_item = JSON.stringify(adjustment_item);
    $("#adjustment_item").val(adjustment_item);

    return id;
}

// append new serial
function appendNewSerial(asset_id, product_serial_id, serial_number, uom_name){
    var wrapper_items = $("#serialModal .product-items");

    if (!asset_id) {
        asset_id = "";
    }

    if (!product_serial_id) {
        product_serial_id = "";
    }

    if (!serial_number){
        serial_number = "(Auto Generate)";
    }

    if (!uom_name){
        uom_name = "";
    }

    var html = `<div class="product-item clearfix">
                    <h1>${serial_number}</h1>
                    <p>${uom_name}</p>
                    <button type="button" class="btn btn-transparent delete text-md p-0 button-delete-serial" onclick="deleteSerial($(this))" data-serialid="${product_serial_id}" data-assetid="${asset_id}">
                        <i class="fas fa-trash text-maroon color-palette"></i>
                    </button>
                    <button type="button" class="btn btn-transparent edit text-md p-0" onclick="editSerial($(this))" data-serialid="${product_serial_id}" data-assetid="${asset_id}">
                        <i class="fas fa-edit color-palette"></i>
                    </button>
                </div>`;

    wrapper_items.append(html);
}

// delete new serial
function deleteNewSerial(new_id){
    var adjustment_item = $("#adjustment_item").val();
    adjustment_item = JSON.parse(adjustment_item);

    $.each(adjustment_item, function (key, row) {
        if (row.edited) {
            dataserial = [];
            $.each(row.added_serial, function (key_serial, row_serial) {
                if(row_serial.asset_id != new_id){
                    dataserial.push(row_serial);
                }
            });
            adjustment_item[key].added_serial = dataserial;
        }
    });

    adjustment_item = JSON.stringify(adjustment_item);
    $("#adjustment_item").val(adjustment_item);
}

function addnewserial() {
    var asset_id = pushNewSerial();
    appendNewSerial(asset_id, "", "", "");
}

function deleteSerial(e) {
    var parent = e.parents(".product-item");
    new_id = e.attr("data-assetid");
    is_active = e.hasClass("active");

    // clear edit
    if(is_active){
        emptyEditSerial();
    }

    // new serial
    if (new_id){
        deleteNewSerial(new_id);
    }else{

    }
    parent.remove();
}

function editAddedSerial(){
    
}

function emptyEditSerial(){
    var parent = $("#serialModal .serial-modal-right");
    parent.empty();
    parent.append(`<h1 class="empty-content"><i class="fa fa-box"></i></h1>`);
}

function emptyListSerial(){
    $("#serialModal .product-items").empty();
}

function getActiveModal(){
    var product_id;

    var adjustment_item = $("#adjustment_item").val();
    adjustment_item = JSON.parse(adjustment_item);

    $.each(adjustment_item, function (key, row) {
        if (row.edited) {
            product_id = row.product_id;
        }
    });

    return product_id;
}

function processProductSerialModal(product_id, warehouse_id){
    var data = {
        product_id: product_id,
        warehouse_id: warehouse_id,
    }
    $.ajax({
        url: urlgetItemSerial,
        dataType: 'json',
        type: 'GET',
        data: data,
        // processData: false,
        contentType: false,
        success: function (response) {
            $('#content').unblock();
            if (response.status) {
                toastr.success(response.message);
                
                // database data
                for(row in response.data){
                    appendNewSerial("", row.id, row.serial_number, row.uom.name);
                }
                
                // current addesd data
                var current_added = getItemSerials(product_id);
                $.each(current_added, function (key, row) {
                    appendNewSerial(row.asset_id, "", "", row.uom_name);
                });

                setActiveModal(product_id);
                $("#serialModal").modal("show");
            } else {
                toastr.warning(response.message);
            }
        },
        beforeSend: function () {
            blockMessage('#content', 'Loading', '#fff');
        }
    }).fail(function (response) {
        var response = response.responseJSON;
        $('#content').unblock();
        toastr.warning(response.message);
    });
}

function getItemSerials(product_id){
    var adjustment_item = $("#adjustment_item").val();
    adjustment_item = JSON.parse(adjustment_item);

    data = [];
    $.each(adjustment_item, function (key, row) {
        if (row.product_id == product_id) {
            $.each(row.added_serial, function (key_serial, row_serial) {
                data.push(row_serial);
            });
        }
    });

    return data;
}

$('#serialModal').on('hide.bs.modal', function (event) {
    updateQTYAdjust();
});

$('#serialModal').on('hidden.bs.modal', function (event) {
    setNonActiveModal();
    emptyEditSerial();
    emptyListSerial();
});

function removeEmptyTable() {
    $("#product-not-found").parents("tr").remove();
}

function addEmptyTable(){
    var html = `<tr>
        <td colspan="6" class="text-center" id="product-not-found">Data Not Found</td>
    </tr>`;
    $('#table-product').append(html);
}

function clearProduct(){
    $('#table-product tbody').empty();
    $("#adjustment_item").val("[]");
    addEmptyTable();
}

function removeProduct(e){
    var product_id = e.attr("data-id");
    removeAdjustmentItem(product_id);

    e.parents("tr").remove();
    var isEmpty = $(".item-product").length;
    if (!isEmpty){
        addEmptyTable();
    }
}

function editSerial(e){
    var asset_id = e.attr("data-assetid");
    var product_serial_id = e.attr("data-serialid");
    if (asset_id){
        editNewSerial(e);
    }
    if (product_serial_id) {
        editOldSerial(e);
    }
}

function editNewSerial(e){
    // add active delete
    $("#serialModal .button-delete-serial").removeClass("active");
    e.parent().find(".button-delete-serial").addClass("active");

    blockMessage('#serialModal .serial-modal-right', 'Loading', '#fff');
    var adjustment_item = $("#adjustment_item").val();
    adjustment_item = JSON.parse(adjustment_item);
    id = e.attr("data-assetid");
    parent = $("#serialModal .serial-modal-right");
    asset_id = id;
    product_serial_id = "";
    product_id = getActiveModal();
    serial_number = "";
    uom_id = "";
    uom_name = "";
    description = "";

    $.each(adjustment_item, function (key, row) {
        if (row.edited) {
            $.each(row.added_serial, function (key_serial, row_serial) {
                if (row_serial.asset_id == id) {
                    uom_id = row_serial.uom_id;
                    uom_name = row_serial.uom_name;
                    description = row_serial.description;
                }
            });
        }
    });

    // hapus empty
    parent.empty();

    html = getHTMLEditSerial(asset_id, product_serial_id, product_id, serial_number, uom_id, uom_name, description);

    //append content
    parent.append(html);
    reloadUOMSerial();
    $("#serialModal .serial-modal-right").unblock();
}

function editOldSerial(e){
    emptyEditSerial();
    var asset_id = e.attr("data-assetid");
    var product_serial_id = e.attr("data-serialid");

    // add active delete
    $("#serialModal .button-delete-serial").removeClass("active");
    e.parent().find(".button-delete-serial").addClass("active");

    var data = {
        product_serial_id: e.attr("data-serialid"),
    }
    $.ajax({
        url: urlgetDetailSerial,
        dataType: 'json',
        type: 'GET',
        data: data,
        // processData: false,
        contentType: false,
        success: function (response) {
            $("#serialModal .serial-modal-right").unblock();
            if (response.status) {
                var parent = $("#serialModal .serial-modal-right");
                product_id = getActiveModal();
                asset_id = "";
                product_serial_id = response.data.id;
                serial_number = response.data.serial_number;
                uom_id = response.data.uom_id;
                uom_name = response.data.uom.name;
                description = response.data.description;

                // hapus empty
                parent.empty();

                html = getHTMLEditSerial(asset_id, product_serial_id, product_id, serial_number, uom_id, uom_name, description);

                //append content
                parent.append(html);
                reloadUOMSerial();
            } else {
                toastr.warning(response.message);
            }
        },
        beforeSend: function () {
            blockMessage('#serialModal .serial-modal-right', 'Loading', '#fff');
        }
    }).fail(function (response) {
        var response = response.responseJSON;
        $('#serialModal .serial-modal-right').unblock();
        toastr.warning(response.message);
    });
}

function saveSerial(e){
    var asset_id = e.attr("data-assetid");
    var product_serial_id = e.attr("data-productserialid");
    if (asset_id){
        // console.log(asset_id);
        var description = $("#serialModal .serial-modal-right #serial_description").val();
        uom = $("#serialModal .serial-modal-right #serial_uom_id").select2('data')[0];
        uom_id = "";
        uom_name = "";
        if(uom){
            uom_id = uom.id;
            uom_name = uom.text;
        }
        updateNewSerial(asset_id, uom_id, uom_name, description);
        updateUOMList(uom_name);
        toastr.success("Success Saved");
    }
    if (product_serial_id) {

    }
}

function updateUOMList(uom_name){
    var parent = $("#serialModal").find(".button-delete-serial.active").parent();
    parent.find("p").html(uom_name);
}

function updateNewSerial(id, uom_id, uom_name, description){
    var adjustment_item = $("#adjustment_item").val();
    adjustment_item = JSON.parse(adjustment_item);

    $.each(adjustment_item, function (key, row) {
        if (row.edited) {
            dataserial = [];
            $.each(row.added_serial, function (key_serial, row_serial) {
                if (row_serial.asset_id == id) {
                    var edited_serial = new Object();
                    edited_serial.serial_number = null;
                    edited_serial.uom_id = uom_id;
                    edited_serial.uom_name = uom_name;
                    edited_serial.description = description;
                    edited_serial.asset_id = id;

                    dataserial.push(edited_serial);
                }else{
                    dataserial.push(row_serial);
                }
            });
            adjustment_item[key].added_serial = dataserial;
        }
    });

    adjustment_item = JSON.stringify(adjustment_item);
    $("#adjustment_item").val(adjustment_item);
}

function reloadUOMSerial(){
    $('#serial_uom_id').select2({
        ajax: {
            url: urlgetUOMProduct,
            type: "GET",
            dataType: "json",
            data: function (params) {
                var product_id = $('#serial_uom_id').attr("data-productid");
                return {
                    name: params.term,
                    page: params.page,
                    product_id: product_id,
                    limit: 30,
                };
            },
            processResults: function (data, params) {
                var more = (params.page * 30) < data.total;
                var option = [];
                $.each(data.rows, function (index, item) {
                    option.push({
                        id: item.uom_id,
                        text: item.uom.name,
                    });
                });
                return { results: option, more: more };
            },
        },
        allowClear: true,
    });
}

function getHTMLEditSerial(asset_id, product_serial_id, product_id, serial_number, uom_id, uom_name, description) {
    // uom
    seleted_uom = "";
    if (uom_id) {
        seleted_uom = `<option value="${uom_id}">${uom_name}</option>`;
    }

    // description
    if (!description){
        description = "";
    }

    var html = `
        <div class="form-wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="number">Serial Number</label>
                        <div class="col-sm-9 controls">
                            <input type="text" class="form-control" placeholder="Serial Number (Auto Generate)" aria-required="true" disabled value="${serial_number}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="number">UOM</label>
                        <div class="col-sm-9 controls">
                            <select data-placeholder="Select UOM" style="width: 100%;" required class="select2 form-control" id="serial_uom_id" data-productid="${product_id}">
                                ${seleted_uom}
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="number">Description</label>
                        <div class="col-sm-9 controls">
                            <textarea class="form-control" rows="5" id="serial_description">${description}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="number">Photo</label>
                        <div class="col-sm-9 controls">
                            <div class="row">
                                <div class="col-sm-6 controls text-center upload-image mb-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-preview-wrapper">
                                                <a class="remove"><i class="fa fa-trash"></i></a>
                                                <img src="${base_url}assets/img/no-image.png" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-btn-wrapper">
                                                <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Foto 1</a>
                                                <input type="file" data-name="item_image_1" id="item_image_1" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 controls text-center upload-image mb-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-preview-wrapper">
                                                <a class="remove"><i class="fa fa-trash"></i></a>
                                                <img src="${base_url}assets/img/no-image.png" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-btn-wrapper">
                                                <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Foto 1</a>
                                                <input type="file" data-name="item_image_2" id="item_image_2" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 controls text-center upload-image mb-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-preview-wrapper">
                                                <a class="remove"><i class="fa fa-trash"></i></a>
                                                <img src="${base_url}assets/img/no-image.png" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-btn-wrapper">
                                                <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Foto 1</a>
                                                <input type="file" data-name="item_image_3" id="item_image_3" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 controls text-center upload-image mb-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-preview-wrapper">
                                                <a class="remove"><i class="fa fa-trash"></i></a>
                                                <img src="${base_url}assets/img/no-image.png" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-btn-wrapper">
                                                <a class="btn btn-sm btn-default btn-block"><i class="fa fa-image"></i> Foto 1</a>
                                                <input type="file" data-name="item_image_4" id="item_image_4" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group row mb-0">
                        <label class="col-md-3 col-xs-12 control-label" for="code">Document</label>
                        <div id="wrapper-add-doc" class="col-md-9">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group row">
                                        <div class="col-sm-12 controls">
                                            <input type="text" class="form-control" name="doc_name[]" id="doc_name1" placeholder="Description" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group row">
                                        <div class="col-sm-12 controls">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="doc[]" onchange="changePath(this)">
                                                    <label class="custom-file-label" for="exampleInputFile" style="border-top-right-radius: .25rem;border-bottom-right-radius: .25rem;">Adjustment Document</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row doc-item">
                                <div class="col-md-5">
                                    <div class="form-group row">
                                        <div class="col-sm-12 controls">
                                            <input type="text" class="form-control" name="doc_name[]" placeholder="Description" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group row">
                                        <div class="col-sm-12 controls">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="doc[]" onchange="changePath(this)">
                                                    <label class="custom-file-label" for="exampleInputFile" style="border-top-right-radius: .25rem;border-bottom-right-radius: .25rem;">Adjustment Document</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row doc-item">
                                <div class="col-md-5">
                                    <div class="form-group row">
                                        <div class="col-sm-12 controls">
                                            <input type="text" class="form-control" name="doc_name[]" placeholder="Description" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group row">
                                        <div class="col-sm-12 controls">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="doc[]" onchange="changePath(this)">
                                                    <label class="custom-file-label" for="exampleInputFile" style="border-top-right-radius: .25rem;border-bottom-right-radius: .25rem;">Adjustment Document</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row doc-item">
                                <div class="col-md-5">
                                    <div class="form-group row">
                                        <div class="col-sm-12 controls">
                                            <input type="text" class="form-control" name="doc_name[]" placeholder="Description" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-12 controls">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="doc[]" onchange="changePath(this)">
                                                    <label class="custom-file-label" for="exampleInputFile" style="border-top-right-radius: .25rem;border-bottom-right-radius: .25rem;">Adjustment Document</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cst-footer text-right">
            <button type="button" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="saveSerial($(this))" data-assetid="${asset_id}" data-productserialid="${product_serial_id}">
                <b><i class="fas fa-save"></i></b>
                Save
            </button>
        </div>
    `;

    return html;
}

function updateQTYAdjust(){
    var adjustment_item = $("#adjustment_item").val();
    adjustment_item = JSON.parse(adjustment_item);
    qty = 0;
    id = getActiveModal();

    $.each(adjustment_item, function (key, row) {
        if (row.edited) {
            $.each(row.items, function (key_serial, row_serial) {
                qty++;
            });

            $.each(row.added_serial, function (key_serial, row_serial) {
                qty++;
            });

            // upadate qty after
            adjustment_item[key].qty_after = qty;
        }
    });

    wrapper_qty = $("table#table-product button[data-id='" + id + "']").parents("tr").find(".product-qty-after");
    wrapper_qty.empty();
    wrapper_qty.html(qty);

    adjustment_item = JSON.stringify(adjustment_item);
    $("#adjustment_item").val(adjustment_item);
}

function minproduct(e, product_id) {
    var qty = $(e).closest('.input-group').find('input');
    if (qty.val() > 0) {
        qty.val(qty.val() - 1);
        updateQtyNonSerial(product_id, qty.val());
    }
}

function plusproduct(e, product_id) {
    var qty = $(e).closest('.input-group').find('input');
    qty.val(parseFloat(qty.val()) + 1);
    updateQtyNonSerial(product_id, parseFloat(qty.val()));
}

function makeid(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() *
            charactersLength));
    }
    return result;
}