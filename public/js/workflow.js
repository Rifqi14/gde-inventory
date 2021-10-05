/**
 * Additional Section
 */
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

/**
 * Add File Section
 */
const addFile = () => {
    var noData = $("#table-file > tbody").find(".no-available-data");
    var fileItem = $("#table-file > tbody").find(".file-item");

    if (noData.length == 1) {
        noData.remove();
    }

    var html = `<tr class="file-item">
                        <td>
                            <input type="text" class="form-control document-name" name="file_name[]" placeholder="Enter file name" required>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file[]" required>
                                    <label class="custom-file-label" for="exampleInputFile">Attach a file</label>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-md text-xs btn-danger btn-flat legitRipple" type="button" onclick="removeFile(this)"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;
    if (fileItem.length == 4) {
        toastr.warning("You can only add maximum 4 file each workflow");
        return;
    }
    $("#table-file > tbody").append(html);
    initInputFile();
};

const removeFile = (e) => {
    $(e).parents(".file-item").remove();
    var noData = $("#table-file > tbody").find(".no-available-data");
    var fileItem = $("#table-file > tbody").find(".file-item");

    if (fileItem.length == 0) {
        $("#table-file > tbody").append(
            `<tr class="no-available-data"><td colspan="3" class="text-center">No available data.</td></tr>`
        );
    }
};

/**
 * Comment Section
 */
const noComment = (id, status = "NO COMMENT") => {
    var data = {
        status: status,
        _token: token,
    };
    $.ajax({
        url: `${base}/groupworkflow/${id}`,
        type: "put",
        data: data,
        dataType: "JSON",
        beforeSend: function () {
            blockMessage("body", "Loading...", "#fff");
        },
    })
        .done(function (response) {
            $("body").unblock();
            if (response.status) {
                datatable.draw();
            } else {
                toastr.warning(response.message);
            }
            return;
        })
        .fail(function (response) {
            $("body").unblock();
            var response = response.responseJSON;
            toastr.error(response.message);
        });
};

const approveComment = (id, status) => {
    var data = {
        status: status,
        _token: token,
    };
    $.ajax({
        url: `${base}/groupworkflow/${id}`,
        type: "put",
        data: data,
        dataType: "JSON",
        beforeSend: function () {
            blockMessage("body", "Loading...", "#fff");
        },
    })
        .done(function (response) {
            $("body").unblock();
            if (response.status) {
                datatable.draw();
            } else {
                toastr.warning(response.message);
            }
            return;
        })
        .fail(function (response) {
            $("body").unblock();
            var response = response.responseJSON;
            toastr.error(response.message);
        });
};

const modalComment = (id, pages) => {
    $("#modal-comment").modal("show");
    $("#modal-comment").find("button").prop("disabled", false);
    $("#form-comment").find("[name=id]").val(id);
    var html = `<option value=""></option>`;
    for (let index = 1; index <= pages; index++) {
        html += `<option value="${index}">${index}</option>`;
    }
    $("#form-comment")
        .find("[name=nos_of_pages]")
        .append(html)
        .select2({
            allowClear: true,
            placeholder: "Please choose option...",
        })
        .val(null)
        .trigger("change");
    $("#form-comment").attr("action", `${base}/groupworkflow/${id}`);
    summernote();
    $("#form-comment").find("[name=comment]").summernote("code", "");
    $("#form-comment").find("[name=comment]").summernote("enable");
};

const showComment = (id) => {
    $.ajax({
        url: `${base}/groupworkflow/${id}/edit`,
        type: "get",
        dataType: "JSON",
        beforeSend: function () {
            blockMessage("body", "Loading...", "#fff");
        },
    })
        .done(function (response) {
            $("body").unblock();
            if (response.status) {
                $("#modal-comment").modal("show");
                $("#modal-comment").find("button").prop("disabled", false);
                $("#form-comment").find("[name=id]").val(id);
                $("#form-comment").find("[name=nos_of_pages]").empty();
                $("#form-comment")
                    .find("[name=nos_of_pages]")
                    .append(
                        `<option value="${response.data.nos_of_pages}">${response.data.nos_of_pages}</option>`
                    )
                    .attr("readonly", true);
                $("#form-comment")
                    .find("[name=comment]")
                    .summernote("code", response.data.comment);
                $("#form-comment").find("[name=comment]").summernote("disable");
            } else {
                toastr.warning(response.message);
            }
            return;
        })
        .fail(function (response) {
            $("body").unblock();
            var response = response.responseJSON;
            toastr.error(response.message);
        });
};

$(function () {
    summernote();
    datatable = $("#table-workflow").DataTable({
        processing: true,
        language: {
            processing: `<div class="p-2 text-center"><i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...</div>`,
        },
        serverSide: true,
        filter: false,
        responsive: true,
        lengthChange: false,
        paging: false,
        ordering: false,
        info: false,
        ajax: {
            url: `${base}/workflow/read`,
            type: "GET",
            data: function (data) {
                data.id = $("[name=id]").val();
            },
        },
        columnDefs: [
            { orderable: false, targets: [0, 1, 2, 3, 4] },
            { className: "text-center align-middle", targets: [2, 3] },
            { className: "text-right align-middle", targets: [4] },
            { className: "align-middle", targets: [1] },
            {
                render: function (data, type, row) {
                    var html = `<button type="button" id="add-comment" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="modalComment(${row.id}, ${row.nos_of_pages})"><b><i class="fas fa-comment"></i></b> Comment</button>`;
                    if (
                        row.status == "COMMENT" ||
                        ((row.status == "APPROVED" ||
                            row.status == "REJECTED") &&
                            row.comment)
                    ) {
                        html = `<button type="button" id="show-comment" class="btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="showComment(${row.id})"><b><i class="fas fa-comment"></i></b> Show Comment</button>`;
                    }
                    return row.status == "NO COMMENT" ? null : html;
                },
                targets: [1],
            },
            {
                render: function (data, type, row, meta) {
                    var html = `<button type="button" id="no-comment" class="btn btn-labeled text-sm btn-sm btn-danger btn-flat legitRipple" onclick="noComment(${row.id})"><b><i class="fas fa-comment-slash"></i></b> No Comment</button>`;
                    if (row.need_approval) {
                        if (meta.settings.json.approve && row.label == 'Responsible Person' ) {

                            html = `<button type="button" id="approval" class="mr-3 btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="approveComment(${row.id}, 'APPROVE')"><b><i class="fas fa-check-circle"></i></b> Approved</button>`;
                            html += `<button type="button" id="reject" class="btn btn-labeled text-sm btn-sm bg-maroon btn-flat legitRipple" onclick="approveComment(${row.id}, 'REJECT')"><b><i class="fas fa-window-close"></i></b> Rejected</button>`;

                        }
                        else if(meta.settings.json.approve && row.label.indexOf("Approver") >= 0){
                            var index = data-2;
                            var label = meta.settings.json.data[index];

                            if (label.status) {
                                html = `<button type="button" id="approval" class="mr-3 btn btn-labeled text-sm btn-sm btn-success btn-flat legitRipple" onclick="approveComment(${row.id}, 'APPROVE')"><b><i class="fas fa-check-circle"></i></b> Approved</button>`;
                                html += `<button type="button" id="reject" class="btn btn-labeled text-sm btn-sm bg-maroon btn-flat legitRipple" onclick="approveComment(${row.id}, 'REJECT')"><b><i class="fas fa-window-close"></i></b> Rejected</button>`;
                            }else{
                                html = `<span type="button" class="mr-3 badge badge-labeled text-sm btn-sm btn-warning btn-flat legitRipple" >Waiting ${label.label}</span>`;
                            }
                        }
                        else{
                            html = `<span type="button" class="mr-3 badge badge-labeled text-sm btn-sm btn-warning btn-flat legitRipple" >Waiting Reviewer</span>`;
                        }
                    }
                    if (row.status) {
                        html = '';

                        if (row.status && row.need_approval) {
                            if (row.status.indexOf("APPROVE") >= 0) {
                                html = `<span type="button" class="mr-3 badge badge-labeled text-sm btn-sm btn-success btn-flat legitRipple" >Approved</span>`;
                            }
                            if (row.status.indexOf("REJECT") >= 0) {
                                html = `<span type="button" class="mr-3 badge badge-labeled text-sm btn-sm btn-danger btn-flat legitRipple" >Rejected</span>`;
                            }
                        }
                    }
                    return html;
                },
                targets: [2],
            },
        ],
        columns: [
            { data: "role_id" },
            { data: "comment", className: "comment" },
            { data: "id" },
            { data: "sla" },
            { data: "sla_dates" },
        ],
    });
    $("#form-comment").validate({
        rules: {
            nos_of_pages: {
                required: true,
            },
            comment: {
                required: true,
            },
        },
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
            console.log('sini');
            $.ajax({
                url: $("#form-comment").attr("action"),
                method: "post",
                data: new FormData($("#form-comment")[0]),
                processData: false,
                contentType: false,
                dataType: "json",
                beforeSend: function () {
                    blockMessage("body", "Loading...", "#fff");
                },
            })
                .done(function (response) {
                    $("body").unblock();
                    if (response.status) {
                        datatable.draw();
                        $("#modal-comment").modal("hide");
                    } else {
                        toastr.warning(response.message);
                    }
                    return;
                })
                .fail(function (response) {
                    $("body").unblock();
                    var response = response.responseJSON;
                    toastr.error(response.message);
                });
        },
    });
    $("#revision_remark").summernote("disable");
});

/**
 * Init Library Section
 */
const initInputFile = () => {
    $(".custom-file-input").on("change", function () {
        let fileName = $(this).val().split("\\").pop();
        $(this).next(".custom-file-label").addClass("selected").html(fileName);
    });
};
