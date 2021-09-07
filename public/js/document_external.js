$(function () {
    $(".select2").select2({
        allowClear: true,
    });
    $.each(needSelect2Tag, function (index, item) {
        if ($(item).attr("data-sub_url")) {
            $(item)
                .select2({
                    placeholder: "Please choose data ...",
                    ajax: {
                        url: `${baseUrl}/${$(item).attr(
                            "data-sub_url"
                        )}/select`,
                        type: "GET",
                        dataType: "json",
                        data: function (params) {
                            return {
                                code:
                                    $(item).attr("data-sub_url") == "phasecode"
                                        ? phaseCode[1]
                                        : params.term,
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
                                    text: `${val.code}`,
                                    name: `${val.name}`,
                                });
                            });
                            return { results: option, more: more };
                        },
                    },
                    allowClear: true,
                })
                .on("select2:select", function (e) {
                    $(item)
                        .parent()
                        .next()
                        .find($('input[type="text"]'))
                        .val($(this).select2("data")[0].name);
                    $(`#${$(item).attr("id")}-error`).remove();
                })
                .on("select2:clear", function (e) {
                    $(item)
                        .parent()
                        .next()
                        .find($('input[type="text"]'))
                        .val(null);
                });
            if ($(item).attr("data-select_name")) {
                var selectName = $(item).attr("data-select_name");
                var html = `<div class="col-md-6 pr-0"><input type="text" name="${selectName}_label" id="${selectName}_label" readonly class="form-control"></div>`;
                $(html).insertAfter($(item).parent());
            }
        }
    });
    $("#contractor_name_id")
        .select2({
            placeholder: "Please choose data ...",
            ajax: {
                url: `${baseUrl}/contractorname/select`,
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
                    params.page = params.page || 1;
                    var more = params.page * 30 < data.total;
                    var option = [];
                    $.each(data.rows, function (index, val) {
                        option.push({
                            id: `${val.name}`,
                            text: `${val.name}`,
                        });
                    });
                    return { results: option, more: more };
                },
            },
            allowClear: true,
        })
        .on("select2:select", function (e) {
            $("#contractor_name_id-error").remove();
        })
        .on("select2:clear", function (e) {
            $("#contractor_group_id").val(null).change();
        });

    $("#contractor_group_id")
        .select2({
            placeholder: "Please choose data ...",
            ajax: {
                url: `${baseUrl}/contractorname/select`,
                type: "GET",
                dataType: "json",
                data: function (params) {
                    return {
                        contractorname: $("#contractor_name_id").val(),
                        name: params.term,
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
                            id: `${val.role_id ?? null}`,
                            text: `${val.role_id ? val.role.name : null}`,
                        });
                    });
                    return { results: option, more: more };
                },
            },
            allowClear: true,
        })
        .on("select2:select", function (e) {
            $("#contractor_group_id-error").remove();
        });

    $("#kks_category_id")
        .select2({
            placeholder: "Please choose data ...",
            ajax: {
                url: `${baseUrl}/kkscategory/select`,
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
                    params.page = params.page || 1;
                    var more = params.page * 30 < data.total;
                    var option = [];
                    $.each(data.rows, function (index, val) {
                        option.push({
                            id: val.id,
                            text: `${val.code}`,
                            name: `${val.name}`,
                        });
                    });
                    return { results: option, more: more };
                },
            },
            allowClear: true,
        })
        .on("select2:select", function (e) {
            $(this)
                .parent()
                .next()
                .find($('input[type="text"]'))
                .val($(this).select2("data")[0].name);
            $("#kks_category_id-error").remove();
        })
        .on("select2:clear", function (e) {
            $(this).parent().next().find($('input[type="text"]')).val(null);
            $("#kks_code_id").val(null).trigger("change");
            $("#kks_code_label").val(null).trigger("change");
        })
        .on("select2:close", function (e) {
            var data = $(this).find("option:selected").val();
            var code = $("#kks_code_id").select2("data");

            if (code[0] && code[0].category.id != data) {
                $("#kks_code_id").val(null).trigger("change");
                $("#kks_code_label").val(null).trigger("change");
            }
        });

    $("#kks_code_id")
        .select2({
            placeholder: "Please choose data ...",
            ajax: {
                url: `${baseUrl}/kkscode/select`,
                type: "GET",
                dataType: "json",
                data: function (params) {
                    return {
                        category: $("#kks_category_id").val(),
                        name: params.term,
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
                            text: `${val.code}`,
                            name: `${val.name}`,
                            category: val.category,
                        });
                    });
                    return { results: option, more: more };
                },
            },
            allowClear: true,
        })
        .on("select2:select", function (e) {
            $(this)
                .parent()
                .next()
                .find($('input[type="text"]'))
                .val($(this).select2("data")[0].name);

            var data = e.params.data;

            if (data.category) {
                $("#kks_category_id").select2("trigger", "select", {
                    data: {
                        id: data.category.id,
                        text: `${data.category.code}`,
                        name: `${data.category.name}`,
                    },
                });
            }
            $("#kks_code_id-error").remove();
        })
        .on("select2:clear", function (e) {
            $(this).parent().next().find($('input[type="text"]')).val(null);
        });

    $("#document_category_id").change(function () {
        $("#document_category_label").val(
            $(this).find(":selected").data("label")
        );
    });

    if (phaseCode) {
        $("#phase_code_id").select2("trigger", "select", {
            data: {
                id: phaseCode.id,
                text: `${phaseCode.code}`,
                name: `${phaseCode.name}`,
            },
        });
        $("#phase_code_id").select2("destroy").attr("readonly", true);
    }

    $(".datepicker").daterangepicker({
        singleDatePicker: true,
        timePicker: false,
        timePickerIncrement: 30,
        locale: {
            format: "DD/MM/YYYY",
        },
    });

    $("#form").validate({
        rules: {
            document_number: {
                required: true,
            },
            document_title: {
                required: true,
            },
            site_code_id: {
                required: true,
            },
            discipline_code_id: {
                required: true,
            },
            kks_category_id: {
                required: true,
            },
            kks_code_id: {
                required: true,
            },
            document_type_id: {
                required: true,
            },
            originator_code_id: {
                required: true,
            },
            phase_code_id: {
                required: true,
            },
            document_sequence: {
                required: true,
                minlength: 4,
                maxlength: 4,
                number: true,
            },
            document_category_id: {
                required: true,
            },
            contractor_name_id: {
                required: true,
            },
            contractor_group_id: {
                required: true,
            },
            planned_ifi_ifa_date: {
                required: true,
            },
            planned_ifc_ifu_date: {
                required: true,
            },
        },
        messages: {
            document_sequence: {
                minlength: "Value must {0} digits number",
                maxlength: "Value must {0} digits number",
                number: "Value must be a number",
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
            $.ajax({
                url: $("#form").attr("action"),
                method: "post",
                data: $("#form, #form-reviewer-matrix").serialize(),
                beforeSend: function () {
                    blockMessage("body", "Loading...", "#fff");
                },
            })
                .done(function (response) {
                    $("body").unblock();
                    if (response.status) {
                        document.location = response.results;
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

    $("#form-reviewer-matrix").validate({
        errorElement: "span",
        errorClass: "help-block text-maroon offset-md-3",
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
            var crudStatus = $("[name=crud_status]").val();
            if (crudStatus == "create") {
                $("#modal-reviewer-matrix").modal("hide");
                return false;
            }
            $.ajax({
                url: $("#form-reviewer-matrix").attr("action"),
                method: "post",
                data: new FormData($("#form-reviewer-matrix")[0]),
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
                        toastr.success(response.message);
                        $("#modal-reviewer-matrix").modal("hide");
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

    $("#form-reviewer-matrix").on("submit", function (e) {
        e.preventDefault();
        var requiredSelect = $('select[name^="reviewer_matrix"]');

        requiredSelect
            .filter('select[name="[group]"].required')
            .each(function () {
                $(this).rules("add", {
                    required: true,
                    messages: {
                        required: "This field is required",
                    },
                });
            });
    });

    $("#form-revision").validate({
        errorElement: "span",
        errorClass: "help-block text-maroon offset-md-3",
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
                url: $("#form-revision").attr("action"),
                method: "post",
                data: revisionData,
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
                        toastr.success(response.message);
                        $("#modal-revision").modal("hide");
                        showCreateRevisionButton(document_id);
                        dataTable.draw();
                        location.reload();
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

    $("#form-reason").validate({
        errorElement: "span",
        errorClass: "help-block text-maroon offset-md-3",
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
                url: $("#form-reason").attr("action"),
                method: "post",
                data: new FormData($("#form-reason")[0]),
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
                        toastr.success(response.message);
                        $("#modal-reason").modal("hide");
                        showCreateRevisionButton(document_id);
                        dataTable.draw();
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

    dataTable = $("#table-revision").DataTable({
        processing: true,
        language: {
            processing: `<div class="p-2 text-center"><i class="fas fa-circle-notch fa-spin fa-fw"></i> Loading...</div>`,
        },
        serverSide: true,
        filter: false,
        responsive: true,
        lengthChange: false,
        ajax: {
            url: `${base}/revision/read`,
            type: "GET",
            data: function (data) {
                data.document_external_id = $("[name=matrix_id]").val();
            },
        },
        columnDefs: [
            { orderable: false, targets: [0, 7] },
            { className: "text-center", targets: [0, 7] },
            {
                render: function (data, type, row) {
                    var label = "",
                        text = "",
                        status = row.status;

                    $.each(global_status, function (index, value) {
                        if (index == status) {
                            label = value.badge;
                            text = value.text;
                        } else {
                            text = status;
                        }
                    });

                    return `<span class="badge bg-${label} text-sm">${text}</span>`;
                },
                targets: [1],
            },
            {
                render: function (data, type, row) {
                    var html = `${row.issue_status}<br>Return code: Waiting`;
                    return html;
                },
                targets: [2],
            },
            {
                render: function (data, type, row) {
                    var html = `<span class="badge bg-navy">${
                        row.updatedby.name ?? null
                    }</span><br>${row.modified_date}`;
                    return html;
                },
                targets: [4],
            },
            {
                render: function (data, type, row) {
                    var html = "";

                    $.each(row.files, function (indexFile, file) {
                        html += `<a href="${file.document_path}" target="_blank" class="text-md text-info text-bold">${file.document_name}</a> - `;
                        html += `${formatBytes(file.file_size, 2)}<br>`;
                    });
                    return html;
                },
                targets: [5],
            },
            {
                render: function (data, type, row) {
                    var button = "";
                    if (actionmenu.indexOf("update") > 0) {
                        button += `<a class="dropdown-item" href="javascript:void(0);" onclick="editRevision(${row.id})">
              <i class="far fa-edit"></i>Update Data
              </a>`;
                    }
                    if (actionmenu.indexOf("delete") > 0) {
                        button += `<a class="dropdown-item delete" href="javascript:void(0);" onclick="destroy(${row.id})">
              <i class="fa fa-trash-alt"></i> Delete Data
              </a>`;
                    }
                    if (
                        (row.status == "APPROVED" ||
                            row.status == "SUPERSEDE" ||
                            row.status == "VOID") &&
                        row.workflow != null
                    ) {
                        button += `<a class="dropdown-item" href="javascript:void(0);" onclick="workflowApproval(${row.workflow.id})">
              <i class="fas fa-sitemap"></i> Workflow Approval
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
                targets: [7],
            },
        ],
        columns: [
            { data: "revision_no" },
            { data: "status" },
            { data: "issue_status" },
            { data: "status" },
            { data: "created_by" },
            { data: "nos_of_pages" },
            { data: "revision_remark" },
            { data: "id" },
        ],
    });

    summernote();
    lockedFormButton("locked");
    showCreateRevisionButton(document_id);
});

const workflowApproval = (id) => (document.location = `${base}/workflow/${id}`);

const showCreateRevisionButton = (id) => {
    $.ajax({
        url: `${base}/revision/latest/${id}`,
        dataType: "json",
        type: "GET",
    })
        .done(function (response) {
            if (response.status) {
                if (response.data) {
                    switch (response.data.status) {
                        case "DRAFT":
                        case "WAITING":
                        case "REVISED":
                            $("button#revision-btn").addClass("d-none");
                            break;

                        default:
                            $("button#revision-btn").removeClass("d-none");
                            break;
                    }
                    return;
                }
                $("button#revision-btn").removeClass("d-none");
                return;
            } else {
                toastr.warning(response.message);
            }
        })
        .fail(function (response) {
            var response = response.responseJSON;
            toastr.warning(response.message);
        });
};

const initRevisionSelect = () => {
    $("#sheet_size")
        .select2({
            placeholder: "Please choose data ...",
            ajax: {
                url: `${baseUrl}/sheetsize/select`,
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
                    params.page = params.page || 1;
                    var more = params.page * 30 < data.total;
                    var option = [];
                    $.each(data.rows, function (index, val) {
                        option.push({
                            id: val.id,
                            text: `${val.code}`,
                            name: `${val.name}`,
                        });
                    });
                    return { results: option, more: more };
                },
            },
            allowClear: true,
        })
        .on("select2:select", function (e) {
            $(this)
                .parent()
                .next()
                .find($('input[type="text"]'))
                .val($(this).select2("data")[0].name);
        })
        .on("select2:clear", function (e) {
            $(this).parent().next().find($('input[type="text"]')).val(null);
        });
    $.each(supersedeSelect2, function (index, item) {
        $(item)
            .select2({
                placeholder: "Please choose data ...",
                ajax: {
                    url:
                        $(item).attr("data-sub_url") == "documentcenterexternal"
                            ? `${base}/${$(item).data("sub_url")}/select`
                            : `${baseUrl}/${$(item).data("sub_url")}/select`,
                    type: "GET",
                    dataType: "json",
                    data: function (params) {
                        return {
                            code:
                                $(item).attr("data-sub_url") == "phasecode"
                                    ? phaseCode[1]
                                    : params.term,
                            site_code: $("#supersede_site_code").val(),
                            discipline_code: $(
                                "#supersede_discipline_code"
                            ).val(),
                            kks_category: $("#supersede_kks_category").val(),
                            kks_code: $("#supersede_kks_code").val(),
                            document_type: $("#supersede_document_type").val(),
                            originator_code: $(
                                "#supersede_originator_code"
                            ).val(),
                            phase_code: $("#supersede_phase_code").val(),
                            document_category: $(
                                "#supersede_document_category"
                            ).val(),
                            menu: $("#form").find("[name=page]").val(),
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
                                text:
                                    $(item).attr("data-sub_url") ==
                                    "documentcenterexternal"
                                        ? `${val.document_number}`
                                        : `${val.code}`,
                                name: `${val.name}`,
                            });
                        });
                        return { results: option, more: more };
                    },
                },
                allowClear: true,
            })
            .on("select2:select", function (e) {
                $(item)
                    .parent()
                    .next()
                    .find($('input[type="text"]'))
                    .val($(this).select2("data")[0].name);
                $(`#${$(item).attr("id")}-error`).remove();
            })
            .on("select2:clear", function (e) {
                $(item).parent().next().find($('input[type="text"]')).val(null);
            });
    });
};

const editRevision = (e) => {
    $.ajax({
        url: `${base}/revision/${e}/edit`,
        method: "GET",
        dataType: "JSON",
        beforeSend: function () {
            blockMessage("body", "Loading...", "#fff");
        },
    })
        .done(function (response) {
            if (response.status) {
                $("body").unblock();
                toastr.success(response.message);
                initEditForm(response.data);
                return;
            }
            $("body").unblock();
            toastr.warning(response.message);
            return;
        })
        .fail(function (response) {
            $("body").unblock();
            var response = response.responseJSON;
            toastr.error(response.message);
        });
};

const initEditForm = (e) => {
    var modal = $("#modal-revision");
    modal.modal("show");
    supersedeSelect2 = $("#supersede-properties").find(`[data-sub_url]`);
    initRevisionSelect();
    // Change Title
    modal.find(".modal-title").text(`Revision (${e.revision_no})`);

    // Init edit form
    var form = modal.find("form#form-revision");
    form.attr("action", `${base}/revision/${e.id}`);
    form.find("[name=_method]").val("PUT");
    form.find("[name=revision_crud_status]").val("edit");
    form.find("[name=status]").val(e.status);
    form.find("[name=id]").val(e.id);
    form.find("[name=document_remark]").summernote("disable");
    form.find("[name=revision_number]").val(e.revision_no);
    var totalFile = e.files ? e.files.length : 0;
    form.find(".init-data").empty().append(initInputData(e.files));
    form.find("#button-add-form").data("document_number", 1);
    var grandTotalFile =
        parseInt($("#button-add-form").data("document_number")) + totalFile;
    form.find("#button-add-form").attr("data-document_number", grandTotalFile);
    form.find("[name=revision_remark]").summernote("code", e.revision_remark);
    form.find("[name=contractor_revision_no]").val(e.contractor_revision_no);
    form.find("[name=nos_of_pages]").val(e.nos_of_pages);
    form.find("[name=sheet_size]").select2("trigger", "select", {
        data: {
            id: e.sheetsize.id,
            text: `${e.sheetsize.code}`,
            name: `${e.sheetsize.name}`,
        },
    });
    form.find("[name=issue_status]").val(e.issue_status);
    form.find("[name=issue_status_label]").val(
        issueStatus[e.issue_status].label
    );
    initConditionForButtonRevision(e.status);
    supersedePropertiesShow(e);
};

const supersedePropertiesShow = (e) => {
    if (e.supersede != null) {
        $("#supersede-properties").removeClass("d-none");
        $("#void-properties").addClass("d-none");
        $("#supersede-properties")
            .find(".summernote")
            .summernote("code", e.supersede.supersede_remark);
        $("#supersede-properties")
            .find("[name=supersede_document_no]")
            .select2("trigger", "select", {
                data: {
                    id: e.supersede.id,
                    text: `${e.supersede.document.document_number}`,
                },
            });
        $("#supersede-properties").find(".summernote").summernote("disable");
        $("#supersede-properties")
            .find(".select2")
            .select2()
            .attr("readonly", true);
        return;
    }
    if (e.void != null) {
        $("#supersede-properties").addClass("d-none");
        $("#void-properties").find(".summernote").summernote("disable");
        $("#void-properties")
            .find(".summernote")
            .summernote("code", e.void.void_remark);
        $("#void-properties").removeClass("d-none");
        return;
    }
    return;
};

const initInputData = (e) => {
    var html = ``;

    $.each(e, function (index, value) {
        html += `<div class="input-group d-flex justify-content-between ${
            index > 0 ? "mt-2" : ""
        }"><a href="${
            value.document_path
        }" class="text-md text-info text-bold mt-1">${
            value.document_name
        }</a><button class="btn btn-transparent text-md lock-status" type="button" onclick="destroyFile($(this), ${
            value.id
        })"><i class="fas fa-trash text-maroon color-palette"></i></button></div>`;
    });

    return html;
};

const destroy = (id) => {
    bootbox.confirm({
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i>',
                className: "btn-primary btn-sm",
            },
            cancel: {
                label: '<i class="fa fa-undo"></i>',
                className: "btn-default btn-sm",
            },
        },
        title: "Delete data?",
        message: "Are you sure want to delete this data?",
        callback: function (result) {
            if (result) {
                var data = {
                    _token: token,
                };
                $.ajax({
                    url: `${base}/revision/destroy/${id}`,
                    data: data,
                    dataType: "json",
                    type: "DELETE",
                    beforeSend: function () {
                        blockMessage("body", "Loading", "#fff");
                    },
                })
                    .done(function (response) {
                        $("body").unblock();
                        if (response.status) {
                            toastr.success(response.message);
                            dataTable.draw();
                        } else {
                            toastr.warning(response.message);
                        }
                    })
                    .fail(function (response) {
                        var response = response.responseJSON;
                        $("body").unblock();
                        toastr.warning(response.message);
                    });
            }
        },
    });
};

const destroyFile = (e, id) => {
    $("#modal-revision").modal("hide");
    bootbox.confirm({
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i>',
                className: "btn-primary btn-sm",
            },
            cancel: {
                label: '<i class="fa fa-undo"></i>',
                className: "btn-default btn-sm",
            },
        },
        title: "Delete data?",
        message: "Are you sure want to delete this data?",
        callback: function (result) {
            if (result) {
                var data = {
                    _token: token,
                };
                $.ajax({
                    url: `${base}/revision/destroyfile/${id}`,
                    data: data,
                    dataType: "json",
                    type: "DELETE",
                    beforeSend: function () {
                        blockMessage("body", "Loading", "#fff");
                    },
                })
                    .done(function (response) {
                        $("body").unblock();
                        $("#modal-revision").modal("show");
                        if (response.status) {
                            removeFormUpload(e);
                            dataTable.draw();
                        } else {
                            toastr.warning(response.message);
                        }
                    })
                    .fail(function (response) {
                        var response = response.responseJSON;
                        $("body").unblock();
                        $("#modal-revision").modal("show");
                        toastr.warning(response.message);
                    });
            }
        },
    });
};

const formatBytes = (bytes, decimals = 2) => {
    if (bytes === 0) return "0 Bytes";

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
};

const initConditionForButtonRevision = (e = "DRAFT") => {
    var modal = $("#modal-revision");
    var revisedButton = `<button type="button" id="revised" class="btn btn-labeled text-md btn-md bg-info btn-flat legitRipple" data-status="REVISED" onclick="saveRevision($(this))">
    <b><i class="fas fa-pencil-alt"></i></b> Revised
  </button>`;

    switch (e) {
        case "DRAFT":
        case "REVISED":
            initLockRevisionForm(modal.find("#revision-properties"));
            modal.find("button#revised").remove();
            modal
                .find("#submit")
                .removeClass()
                .addClass(
                    "btn btn-labeled text-md btn-md btn-success btn-flat legitRipple"
                )
                .empty()
                .append(`<b><i class="fas fa-check-circle"></i></b> Submit`)
                .data("status", "WAITING");
            modal
                .find("#save")
                .removeClass()
                .addClass(
                    "btn btn-labeled text-md btn-md bg-olive btn-flat legitRipple"
                )
                .empty()
                .append(`<b><i class="fas fa-save"></i></b> Save`)
                .data("status", "DRAFT");
            break;
        case "WAITING":
            initLockRevisionForm(modal.find("#revision-properties"), "lock");
            modal.find("button#revised").remove();
            modal.find(".modal-footer").prepend(revisedButton);
            modal
                .find("#submit")
                .removeClass()
                .addClass(
                    "btn btn-labeled text-md btn-md btn-success btn-flat legitRipple"
                )
                .empty()
                .append(`<b><i class="fas fa-check-circle"></i></b> Issued`)
                .data("status", "APPROVED");
            modal
                .find("#save")
                .removeClass()
                .addClass(
                    "btn btn-labeled text-md btn-md bg-maroon btn-flat legitRipple"
                )
                .empty()
                .append(`<b><i class="fas fa-window-close"></i></b> Reject`)
                .data("status", "REJECTED");
            break;
        case "APPROVED":
            initLockRevisionForm(modal.find("#revision-properties"), "lock");
            modal.find("button#revised").remove();
            modal
                .find("#submit")
                .removeClass()
                .addClass(
                    "btn btn-labeled text-md btn-md bg-info btn-flat legitRipple"
                )
                .empty()
                .append(`<b><i class="fas fa-save"></i></b> Supersede`)
                .data("status", "SUPERSEDE");
            modal
                .find("#save")
                .removeClass()
                .addClass(
                    "btn btn-labeled text-md btn-md btn-success btn-flat legitRipple"
                )
                .empty()
                .append(`<b><i class="fas fa-save"></i></b> Void`)
                .data("status", "VOID");
            break;
        case "REJECTED":
            initLockRevisionForm(modal.find("#revision-properties"), "lock");
            modal.find("button#revised").remove();
            modal.find("button#submit").remove();
            modal.find("button#save").remove();
            break;
        case "SUPERSEDE":
        case "VOID":
            initLockRevisionForm(modal.find("#revision-properties"), "lock");
            modal.find("button#revised").remove();
            modal
                .find("#submit")
                .removeClass()
                .addClass(
                    "btn btn-labeled text-md btn-md bg-olive btn-flat legitRipple"
                )
                .empty()
                .append(`<b><i class="fas fa-save"></i></b> Save`)
                .data("status", "SAVE");
            modal
                .find("#save")
                .removeClass()
                .addClass(
                    "btn btn-labeled text-md btn-md bg-olive btn-flat legitRipple"
                )
                .empty()
                .append(`<b><i class="fas fa-undo"></i></b> Undo`)
                .data("status", "UNDO");
            break;

        default:
            toastr.warning("Status not been set");
            break;
    }
};

const initLockRevisionForm = (element, e = "unlock") => {
    if (e == "lock") {
        element.find("input.lock-status").prop("readonly", true);
        element.find("button.lock-status").prop("disabled", "disabled");
        element.find(".summernote.lock-status").summernote("disable");
        element.find(".select2.lock-status").select2().attr("readonly", true);
        return;
    }
    element.find("input.lock-status").prop("readonly", false);
    element.find("button.lock-status").prop("disabled", false);
    element.find(".summernote.lock-status").summernote("enable");
    initRevisionSelect();
    element.find(".select2.lock-status").select2().attr("readonly", false);
};

const saveRevision = (e) => {
    var status =
        e.data("status") == "SAVE"
            ? $("#form-revision").find("input[name=status]").val()
            : e.data("status");
    $("#form-revision").find("input[name=status]").val(status);
    var dataReason = {
        id: $("#form-revision").find("[name=id]").val(),
        status: e.data("status"),
    };
    switch (dataReason.status) {
        case "REVISED":
        case "APPROVED":
        case "REJECT":
            reasonModal(dataReason);
            break;
        case "VOID":
            $("#void-properties").removeClass("d-none");
            $("#supersede-properties").addClass("d-none");
            initConditionForButtonRevision(status);
            break;
        case "SUPERSEDE":
            $("#void-properties").addClass("d-none");
            $("#supersede-properties").removeClass("d-none");
            initConditionForButtonRevision(status);
            break;
        default:
            revisionData = new FormData($("#form-revision")[0]);
            $("#form-revision").trigger("submit");
            break;
    }
};

const reasonModal = (e) => {
    $("#modal-revision").modal("hide");
    $("#modal-reason").modal("show");
    $("#form-reason").find("[name=document_revision_id]").val(e.id);
    $("#form-reason").find("[name=status]").val(e.status);
    $("#modal-reason").find("modal-title").text(`${e.status} REASON`);
};

const lockedFormButton = (status) => {
    lockedFormInput(status);
    if (status == "locked") {
        $(".unlock").removeClass("d-none");
        $(".locked").addClass("d-none");
        return true;
    }
    $(".locked").removeClass("d-none");
    $(".unlock").addClass("d-none");
};

const lockedFormInput = (status) => {
    if (status == "locked") {
        $(".lock").attr("readonly", true);
        $("button.lock").prop("disabled", "disabled");
        $("textarea.lock.summernote").summernote("disable");
        return true;
    }
    $(".lock").attr("readonly", false);
    $("button.lock").prop("disabled", false);
    $("textarea.lock.summernote").summernote("enable");
};

const initReviewerMatrix = (e) => {
    $.ajax({
        url: `${externalUrl}/${e.find("input[name=page]").val()}/${e
            .find("input[name=matrix_id]")
            .val()}/readmatrix`,
        type: "GET",
        processData: false,
        contentType: false,
        dataType: "JSON",
        beforeSend: function () {
            blockMessage("body", "Loading...", "#fff");
        },
    })
        .done(function (response) {
            $("body").unblock();
            if (response.status) {
                $.each(matrixEnums, function (index, matrix) {
                    var ReviewerMatrixChecked = $.grep(
                        response.data,
                        function (e) {
                            return e.matrix_label === matrix.label;
                        }
                    );
                    if (ReviewerMatrixChecked.length > 0) {
                        var checked =
                            ReviewerMatrixChecked[0].matrix_sla == "true"
                                ? true
                                : false;
                        $(`#${index}_sla`).prop("checked", checked);
                        $(`#${index}_days`).val(
                            ReviewerMatrixChecked[0].matrix_days
                        );

                        if (ReviewerMatrixChecked[0].groups.length > 0) {
                            $.each(
                                ReviewerMatrixChecked[0].groups,
                                function (indexGroup, group) {
                                    $(`#${index}`).select2(
                                        "trigger",
                                        "select",
                                        {
                                            data: {
                                                id: group.id,
                                                text: `${group.name}`,
                                            },
                                        }
                                    );
                                }
                            );
                        }
                    }
                });
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

const checkedSLA = (e) => {
    // if (!e[0].checked) {
    //     e.parent()
    //         .next()
    //         .addClass("d-none")
    //         .find($('input[type="text"]'))
    //         .val(null);
    //     return false;
    // }
    e.parent().next().removeClass("d-none");
    return true;
};

const userGroupSelectInit = (e) => {
    e.select2({
        placeholder: "Choose User Group ...",
        multiple: true,
        maximumSelectionLength: e.attr("data-max_tag")
            ? e.attr("data-max_tag")
            : 1,
        ajax: {
            url: routeRoleSelect,
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
                params.page = params.page || 1;
                var more = params.page * 30 < data.total;
                var option = [];
                $.each(data.rows, function (index, item) {
                    option.push({
                        id: item.id,
                        text: `${item.name}`,
                    });
                });
                return { results: option, more: more };
            },
        },
        allowClear: true,
    })
        .on("select2:select", function (e) {
            $(this).next().next().remove();
        })
        .on("change.select2", function (e) {
            var value = $(this).find("option:selected").length;
            var checkbox = $(this)
                .parent()
                .parent()
                .next()
                .find("input[type=checkbox]");
            if (value == 0 && checkbox.prop("checked")) {
                checkbox.prop("checked", false).prop("disabled", true);
                return true;
            }
            if (value == 0 && !checkbox.prop("checked")) {
                checkbox.prop("checked", false).prop("disabled", true);
                return true;
            }
            $(this)
                .parent()
                .parent()
                .next()
                .find("input[type=checkbox]")
                .prop("disabled", false);
        })
        .trigger("change.select2");
};

const reviewerMatrix = () => {
    $("#modal-reviewer-matrix").modal("show");

    var status = $("#form-reviewer-matrix").find("[name=crud_status]").val();
    var select2Tag = $("#form-reviewer-matrix").find(".select2");
    $.each(select2Tag, function (index, item) {
        $(item).css("width", "75%");
        userGroupSelectInit($(item));
    });
    var checkboxData = $("#form-reviewer-matrix").find(
        "input[type='checkbox']"
    );
    $.each(checkboxData, function (index, item) {
        checkedSLA($(item));
    });
    if (status == "update") {
        initReviewerMatrix($("#form-reviewer-matrix"));
    }
};

const revisionModal = (e) => {
    $("#modal-revision").modal("show");
    var statusIssue = "IFI";
    initLockRevisionForm($("#revision-properties"));
    $("#form-revision").find("[name=revision_crud_status]").val(e);
    $("#form-revision").find("[name=document_remark]").summernote("disable");
    statusIssue = documentCategory == "IFI" ? "IFI" : "IFA";
    $("#form-revision").find("[name=issue_status]").val(statusIssue);
    $("#form-revision")
        .find("[name=issue_status_label]")
        .val(issueStatus[statusIssue].label);
    $("#form-revision").find(".init-data").remove();
    $("#form-revision").find("[name=contractor_revision_no]").val("");
    $("#form-revision").find("[name=nos_of_pages]").val("");
    $("#form-revision")
        .find("textarea[name=revision_remark]")
        .summernote("code", "");
    checkRevisionNo($("#form-revision"));
    initInputFile();
    initConditionForButtonRevision();
    initRevisionSelect();
    $("#sheet_size").val(null).trigger("change.select2");
};

const checkRevisionNo = (e) => {
    $.ajax({
        url: `${base}/revision/latestno`,
        data: {
            document_id: e.find("input[name=document_external_id]").val(),
            issue_status: e.find("input[name=issue_status]").val(),
        },
        type: "get",
        beforeSend: function () {
            blockMessage("body", "Loading...", "#fff");
        },
    })
        .done(function (response) {
            $("body").unblock();
            if (response.status) {
                e.find("input[name=revision_number]").val(response.data);
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

const initInputFile = () => {
    $(".custom-file-input").on("change", function () {
        let fileName = $(this).val().split("\\").pop();
        $(this).next(".custom-file-label").addClass("selected").html(fileName);
    });
};

const addFormUpload = (e) => {
    var number = $(e).attr("data-document_number");
    if (number <= 3) {
        var html = `
                  <div class="input-group mt-2 add-on">
                    <input type="text" name="document_name[]" class="form-control" placeholder="Document Name">
                    <div class="custom-file ml-3">
                      <input type="file" class="custom-file-input" name="document_upload[]" onchange="initInputFile()">
                      <label class="custom-file-label form-control" for="document_upload">Attach a document</label>
                    </div>
                    <button class="btn btn-transparent text-md" type="button" onclick="removeFormUpload($(this))"><i class="fas fa-trash text-maroon color-palette"></i></button>
                  </div>
      `;
        $(e).parents(".init-input").append(html);
        $(e).attr("data-document_number") <= 3
            ? $(e).attr(
                  "data-document_number",
                  parseInt($(e).attr("data-document_number")) + 1
              )
            : "";
    }
};

const removeFormUpload = (e) => {
    $("#button-add-form").attr(
        "data-document_number",
        parseInt($("#button-add-form").attr("data-document_number")) - 1
    );
    e.parent().remove();
};

const changeIssueStatus = (e) => {
    var data = e.find("option:selected").data();
    $("[name=issue_status_label]").val(data.label);
};
