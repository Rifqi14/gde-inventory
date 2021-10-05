var select2Tag = $("body").find("[data-select_url]");

const activeTab = (tabs) => {
    tab = tabs;
    $("button[data-toggle='tab']").on("shown.bs.tab", function (e) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
};

const createData = () => {
    window.location = `${base}/transmittalproperties/${tab}/create`;
};

const filter = () => {
    $(`#modal-filter-${tab}`).modal("show");
};

const edit = (id) => {
    document.location = `${base}/transmittalproperties/${tab}/${id}/edit`;
};

const destroy = (id) => {
    bootbox.confirm({
        buttons: {
            confirm: {
                label: `<i class="fa fa-check"></i>`,
                className: `btn-primary btn-sm`,
            },
            cancel: {
                label: `<i class="fa fa-undo"></i>`,
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
                    url: `${base}/transmittalproperties/${tab}/${id}`,
                    dataType: "json",
                    data: data,
                    type: "DELETE",
                    beforeSend: function () {
                        blockMessage("body", "Loading", "#fff");
                    },
                })
                    .done(function (response) {
                        $("body").unblock();
                        if (response.status) {
                            toastr.success(response.message);
                            dataTableCategory.ajax.reload(null, false);
                            dataTableOrganization.ajax.reload(null, false);
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

$(function () {
    $(".upload-image")
        .find("input:file")
        .change(function () {
            var image = $(this).closest(".upload-image").find("img");
            var remove = $(this).closest(".upload-image").find(".remove");
            var fileReader = new FileReader();
            fileReader.onload = function () {
                var data = fileReader.result;
                image.attr("src", data);
                remove.css("display", "block");
            };
            fileReader.readAsDataURL($(this).prop("files")[0]);
        });

    $(".upload-image").on("click", ".remove", function () {
        var image = $(this).closest(".upload-image").find("img");
        var file = $(this).closest(".upload-image").find("input:file");
        file.val("");
        image.attr("src", "{{asset('assets/img/no-image.png')}}");
        $(this).css("display", "none");
    });

    $.each(select2Tag, function (index, item) {
        $(item).select2({
            placeholder: "Please choose data ...",
            ajax: {
                url: `${base}/${$(this).data("select_url")}/select`,
                type: "GET",
                dataType: "JSON",
                data: function (params) {
                    return {
                        eliminate: $(item).data("eliminate"),
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
                            text: `${val.name}`,
                        });
                    });
                    return { results: option, more: more };
                },
            },
            allowClear: true,
        });
    });

    $("#form-categorycontractor").validate({
        rules: {
            code: {
                required: true,
            },
            name: {
                required: true,
            },
            tagged_group_id: {
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
            $.ajax({
                url: $("#form-categorycontractor").attr("action"),
                method: "post",
                data: new FormData($("#form-categorycontractor")[0]),
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

    $("#form-organizationcode").validate({
        rules: {
            code: {
                required: true,
            },
            name: {
                required: true,
            },
            tagged_group_id: {
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
            $.ajax({
                url: $("#form-organizationcode").attr("action"),
                method: "post",
                data: new FormData($("#form-organizationcode")[0]),
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

    dataTableCategory = $("#table-category-contractor").DataTable({
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
            url: `${base}/transmittalproperties/readcontractor`,
            type: "GET",
            data: function (data) {
                data.name = $("#form-filter-categorycontractor")
                    .find("input[name=name]")
                    .val();
                data.address = $("#form-filter-categorycontractor")
                    .find("textarea[name=address]")
                    .val();
                data.tagged_group_id = $("#form-filter-categorycontractor")
                    .find("select[name=tagged_group_id]")
                    .val();
            },
        },
        columnDefs: [
            { orderable: false, targets: [0, 3, 4] },
            { className: "text-center", targets: [0, 4] },
            { width: "5%", targets: [0] },
            {
                width: "30%",
                render: function (data, type, row) {
                    html = `<div class="user-panel d-flex align-items-center">
                                <div class="image">
                                    <img src="${row.logo}" class="img-circle elevation-1 img-pp" alt="logo">
                                </div>
                                <div class="info">
                                    <b><a href="${base}/transmittalproperties/${tab}/${row.id}/edit" class="d-block">${row.name}</a></b>
                                    <small><b>${row.code}</b></small>
                                </div>
                            </div>`;
                    return html;
                },
                targets: [1],
            },
            {
                width: "20%",
                render: function (data, type, row) {
                    html = "";
                    $.each(row.groups, function (index, item) {
                        html += `<span class="badge badge-secondary">${item.name}</span>&nbsp;`;
                    });
                    return html;
                },
                targets: [3],
            },
            {
                width: "10%",
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
                targets: [4],
            },
        ],
        columns: [
            { data: "no" },
            { data: "name" },
            { data: "address" },
            { data: "id" },
            { data: "id" },
        ],
    });

    dataTableOrganization = $("#table-organization-code").DataTable({
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
            url: `${base}/transmittalproperties/readcode`,
            type: "GET",
            data: function (data) {
                data.name = $("#form-filter-organizationcode")
                    .find("input[name=name]")
                    .val();
                data.tagged_group_id = $("#form-filter-organizationcode")
                    .find("select[name=tagged_group_id]")
                    .val();
            },
        },
        columnDefs: [
            { orderable: false, targets: [0, 2, 3] },
            { className: "text-center", targets: [0, 3] },
            { width: "5%", targets: [0] },
            {
                width: "30%",
                render: function (data, type, row) {
                    html = `<div class="user-panel d-flex align-items-center">
                                <div class="info">
                                    <b><a href="${base}/transmittalproperties/${tab}/${row.id}/edit" class="d-block">${row.name}</a></b>
                                    <small><b>${row.code}</b></small>
                                </div>
                            </div>`;
                    return html;
                },
                targets: [1],
            },
            {
                width: "20%",
                render: function (data, type, row) {
                    html = "";
                    $.each(row.groups, function (index, item) {
                        html += `<span class="badge badge-secondary">${item.name}</span>&nbsp;`;
                    });
                    return html;
                },
                targets: [2],
            },
            {
                width: "10%",
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
                targets: [3],
            },
        ],
        columns: [
            { data: "no" },
            { data: "code" },
            { data: "id" },
            { data: "id" },
        ],
    });

    $("#form-filter-categorycontractor").submit(function (e) {
        e.preventDefault();
        dataTableCategory.draw();
        $("#modal-filter-categorycontractor").modal("hide");
    });
});
