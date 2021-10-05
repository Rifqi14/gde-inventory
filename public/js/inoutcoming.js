const filter = () => {
    $(`#modal-filter-${menu}`).modal("show");
};

const createData = () => {
    window.location = `${base}/outcoming/${tab}/create`;
};

const edit = (id) =>
    (document.location = `${base}/outcoming/${tab}/${id}/edit`);

const summernote = () => {
    $(".summernote").summernote({
        height: 145,
        toolbar: [
            ["style", ["style"]],
            [
                "font-style",
                [
                    "bold",
                    "italic",
                    "underline",
                    "strikethrough",
                    "superscript",
                    "subscript",
                    "clear",
                ],
            ],
            ["font", ["fontname"]],
            ["font-size", ["fontsize"]],
            ["font-color", ["color"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["table", ["table"]],
            ["insert", ["link", "picture", "video", "hr"]],
            ["misc", ["fullscreen", "codeview", "help"]],
        ],
    });
};

const activeTab = (tabs) => {
    tab = tabs;
};

const tabData = () => {
    $.ajax({
        url: `${base}/transmittalproperties/getall`,
        dataType: "JSON",
        data: {
            _token: token,
        },
        type: "GET",
        beforeSend: function () {
            // blockMessage("body", "Loading...", "#fff");
        },
    }).done(function (response) {
        // $("body").unblock();
        if (response.status == 200) {
            $.each(response.data, function (index, item) {
                var html = `
                <li class="nav-item">
                    <button class="nav-link ${
                        index == 0 ? "active" : ""
                    } pl-4 pr-4" id="${
                    item.code
                }" type="button" onclick="activeTab('${
                    item.code
                }')" data-toggle="tab" role="tab" aria-controls="document" aria-selected="${
                    index == 0 ? "true" : "false"
                }"><b>${item.code}</b></button>
                </li>
                `;

                $("#outcoming-tab").append(html);
            });
            activeTab(response.data[0].code);
            return true;
        }
        toastr.warning(response.message);
        return false;
    });
};

const getDefaultData = () => {
    $.ajax({
        url: `${base}/outcoming/defaultdata`,
        dataType: "JSON",
        type: "POST",
        data: {
            _token: token,
            tab: tab,
            id: user_id,
            contractor_group_id: $("#contractor_group_id").val(),
            outcoming_id: $("input[name=id]").val(),
        },
        beforeSend: function () {},
    }).done(function (response) {
        filledAutoFillData(response);
        if (response.status == 200) {
            return true;
        }
    });
};

const filledAutoFillData = (data) => {
    $("#transmittal_date").val(data.transmittal_date);
    $("#sender_id").val(data.sender);
    $("#sender_address").val(data.sender_address);
    $("#recipient_address").val(data.recipient_address);
    $("#issued_by").val(data.issued_by);
    $("input[name=sender_alias]").val(data.sender_alias);
    $("input[name=recipient_alias]").val(data.recipient_alias);
};

const addDocumentTag = () => {
    var lengthDocumentTag = $("#table-document").find(".document-tag").length;

    var html = `<tr class="document-tag" data-order="${++lengthDocumentTag}">
        <input type="hidden" name="revision" data-document_no="" data-revision="" data-issue_status="" data-issue_purpose="" data-sheet_size="" data-review_status="">
        <td>
            <select name="revision_id[]" class="form-control select2 revision" data-url="revision" onchange="setTableData($(this))"></select>
        </td>
        <td data-column="revision"></td>
        <td data-column="issue_status"></td>
        <td data-column="issue_purpose"></td>
        <td data-column="sheet_size"></td>
        <td data-column="review_status"></td>
        <td data-column="action">
            <button class="btn btn-transparent text-md" type="button"><i class="fas fa-trash text-maroon color-palette" onclick="removeDocumentTag($(this))"></i></button>
        </td>
    </tr>`;
    $(".document-tag-body").append(html);
    initSelect2();
};

const destroyDocument = (e) => {
    bootbox.confirm({
        buttons: {
            confirm: {
                label: `<i class="fa fa-check"></i>`,
                className: `btn-primary btn-sm`,
            },
            cancel: {
                label: `<i class="fa fa-undo"></i>`,
                className: `btn-default btn-sm`,
            },
        },
        title: `Delete document?`,
        message: `Are you sure want to delete this data?`,
        callback: function (result) {
            if (result) {
                var data = {
                    _token: token,
                    id: $(e).data("id"),
                    document_id: $(e).data("document_id"),
                };
                $.ajax({
                    url: `${base}/outcoming/destroydocument`,
                    dataType: `JSON`,
                    data: data,
                    type: "DELETE",
                    beforeSend: function () {
                        blockMessage(`body`, `Loading...`, `#FFF`);
                    },
                })
                    .done(function (response) {
                        $(`body`).unblock();
                        if (response.status) {
                            toastr.success(response.message);
                            removeDocumentTag($(e));
                            return true;
                        }
                        toastr.warning(response.message);
                    })
                    .fail(function (response) {
                        var response = response.responseJSON;
                        $(`body`).unblock();
                        toastr.warning(response.message);
                    });
            }
        },
    });
};

const removeDocumentTag = (e) => {
    $(e).parents(".document-tag").remove();
    var document = $("#table-document").find(".document-tag");
    $.each(document, function (index, item) {
        $(item).attr("data-order", ++index);
    });
};

const setTableData = (e) => {
    var tableRow = $(e).parents(".document-tag");
    $(e).on("select2:open", function (k) {
        var key = $(this).val();
        delete choosenOption[key];
    });
    $(e).on("select2:select", function (i) {
        var key = i.params.data.id;
        choosenOption[`${key}`] = `${i.params.data.text}`;
        clearTableData(tableRow);
        tableRow
            .find("[data-column='revision']")
            .append(i.params.data.revision);
        tableRow
            .find("[data-column='issue_status']")
            .append(i.params.data.issue_status);
        tableRow
            .find("[data-column='issue_purpose']")
            .append(i.params.data.issue_purpose);
        tableRow
            .find("[data-column='sheet_size']")
            .append(i.params.data.sheet_size);
        tableRow
            .find("[data-column='review_status']")
            .append(i.params.data.review_status);
    });
    $(e).on("select2:clear", function (j) {
        clearTableData(tableRow);
        var data = j.params.data;
        delete choosenOption[`${data[0].id}`];
    });
};

const clearTableData = (e) => {
    $(e).find("[data-column='revision']").empty();
    $(e).find("[data-column='issue_status']").empty();
    $(e).find("[data-column='issue_purpose']").empty();
    $(e).find("[data-column='sheet_size']").empty();
    $(e).find("[data-column='review_status']").empty();
};

const initSelect2 = () => {
    $(".revision").select2({
        placeholder: "Please choose option ...",
        ajax: {
            url: `${base}/outcoming/${$(".revision").data("url")}/select`,
            type: "GET",
            dataType: "JSON",
            data: function (params) {
                return {
                    name: params.term,
                    tab: tab,
                    category_contractor_id: $("#contractor_group_id").val(),
                    page: params.page,
                    limit: 30,
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                var more = params.page * 30 < data.total;
                var option = [];
                $.each(data.rows, function (index, val) {
                    if (val.revision) {
                        option.push({
                            id: val.revision.id,
                            text: val.document_number,
                            revision: val.revision.revision_no,
                            issue_status: val.revision.issue_status,
                            issue_purpose: val.document_category_id,
                            sheet_size: val.revision.sheetsize.name,
                            review_status: val.revision.workflow.return_code,
                            disabled:
                                val.revision.id in choosenOption ? true : false,
                        });
                    }
                });
                return { results: option, more: more };
            },
        },
        allowClear: true,
    });
    $.each(select2, function (index, item) {
        $(item).select2({
            placeholder: "Please choose option ...",
            ajax: {
                url:
                    $(item).data("outcoming") != "no"
                        ? `${base}/outcoming/${$(item).data("url")}/select`
                        : `${base}/${$(item).data("url")}/select`,
                type: "GET",
                dataType: "JSON",
                data: function (params) {
                    return {
                        name: params.term,
                        tab: tab,
                        category_contractor_id: $("#contractor_group_id").val(),
                        page: params.page,
                        limit: 30,
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    var more = params.page * 30 < data.total;
                    var option = [];
                    $.each(data.rows, function (index, val) {
                        option.push({
                            id: val.id,
                            text: val.name,
                        });
                    });
                    return { results: option, more: more };
                },
            },
            allowClear: true,
        });
    });
};

const saveOutcoming = (e) => {
    var status = $(e).data("status");
    $("#form").find("input[name=status]").val(status);
    $("#form").trigger("submit");
};

const initInputFile = () => {
    $(".custom-file-input").on("change", function () {
        let fileName = $(this).val().split("\\").pop();
        $(this).next(".custom-file-label").addClass("selected").html(fileName);
    });
};

$(function () {
    initInputFile();
    summernote();
    getDefaultData();
    if (window.location.href == `${base}/outcoming`) {
        tabData();
    }

    dataTable = $(".ajaxTable").DataTable({
        processing: true,
        language: {
            processing: `<div class="p-2 text-center"><i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...</div>`,
        },
        serverSide: true,
        filter: false,
        responsive: true,
        lengthChange: false,
        order: [[1, "asc"]],
        ajax: {
            url: `${base}/outcoming/read`,
            type: "GET",
            data: function (data) {
                data.transmittal_number = $("#transmittal_number").val();
                data.transmittal_title = $("#transmittal_title").val();
                data.attention_id = $("#attention_id").val();
                data.tab = tab;
            },
        },
        columnDefs: [
            { orderable: false, targets: [0, 5] },
            { className: "text-center", targets: [0, 4, 5] },
            {
                render: function (data, type, row) {
                    var html = "";

                    if (row.attentions) {
                        $.each(row.attentions, function (index, item) {
                            html += `<span class="badge badge-secondary">${item.name}</span>`;
                            html +=
                                index == row.attentions.length - 1 ? "" : " / ";
                        });
                    }
                    return html;
                },
                targets: [3],
            },
            {
                render: function (data, type, row) {
                    var html = "";
                    switch (row.status) {
                        case "ISSUED":
                            html += `<span class="badge badge-success">Issued</span>`;
                            break;

                        default:
                            html += `<span class="badge badge-secondary">Draft</span>`;
                            break;
                    }
                    return html;
                },
                targets: [4],
            },
            {
                render: function (data, type, row) {
                    var button = "";
                    if (actionmenu.indexOf("update") > 0) {
                        button += `<a class="dropdown-item" href="javascript:void(0);" onclick="edit(${row.id})">
                    <i class="far fa-edit"></i>Update Data
                    </a>`;
                    }
                    if (actionmenu.indexOf("delete") > 0) {
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
                },
                targets: [5],
            },
        ],
        columns: [
            { data: "no" },
            { data: "transmittal_no" },
            { data: "transmittal_title" },
            { data: "attentions" },
            { data: "status" },
            { data: "id" },
        ],
    });
    initSelect2();

    $("#form").validate({
        errorElement: "span",
        errorClass: "help-block text-maroon",
        focusInvalid: false,
        highlight: function (e) {
            $(e).removeClass("has-success").addClass("has-error");
        },
        unhighlight: function (e) {
            $(e).removeClass("has-error").addClass("has-success");
        },
        success: function (e) {
            $(e).remove();
        },
        errorPlacement: function (error, element) {
            if (element.is(":file")) {
                error.insertAfter(element.parent().parent().parent());
            } else if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else if (element.attr("type") == "checkbox") {
                error.insertAfter(element.parent());
            } else if (
                element.hasClass("select2") &&
                element.next(".select2-container").length
            ) {
                error.insertAfter(element.next(".select2-container"));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function () {
            $.ajax({
                url: $("#form").attr("action"),
                method: "post",
                data: new FormData($("#form")[0]),
                processData: false,
                contentType: false,
                dataType: "JSON",
                beforeSend: function () {
                    blockMessage("body", "Loading...", "#FFF");
                },
            })
                .done(function (response) {
                    $("body").unblock();
                    if (response.status) {
                        document.location = response.results;
                        return;
                    }
                    toastr.warning(response.message);
                    return;
                })
                .fail(function (response) {
                    $("body").unblock();
                    var response = response.responseJSON;
                    toastr.error(response.message);
                });
        },
    });

    $("#contractor_group_id").on("change", function () {
        getDefaultData();
        $("#attention_id").val("").trigger("change");
    });
});
