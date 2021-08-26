const showCreate = () => {
    $('#form')[0].reset();
    $('#form').attr('action', menu_store);
    $('#form input[name=_method]').attr('value', 'POST');
    $('#form input[name=menu_name]').attr('value', '');
    $('#form input[name=menu_route]').attr('value', '');
    $('#form input[name=menu_icon]').attr('value', '');
    $('#form .invalid-feedback').each(function () { $(this).remove(); });
    $('#form .form-group').removeClass('has-error').removeClass('has-success');
    $('#form-create .modal-title').html('Create Menu');
    $("#form-create").modal('show');
}

$(function(){
    $('.dd').nestable({
        maxDepth: 2
    }).nestable('collapseAll');

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
            }else if (element.attr('type') == 'checkbox') {
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
                    blockMessage('#form-create .modal-content', 'Loading', '#fff');
                }
            }).done(function (response) {
                console.log(response);
                $('#form-create .modal-content').unblock();
                if (response.status) {
                    if ($('#form').attr('action') == menu_store) {
                        $('#form-create').modal('hide');
                        $('.dd-list').append(`
                            <li class="dd-item" data-id="${response.data.id}">
                                <div class="item_actions">
                                        <button class="btn btn-xs btn-default edit" data-id="${response.data.id}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-xs btn-default delete" data-id="${response.data.id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <div class="dd-handle">
                                    <span>${response.data.menu_name}</span> <br>
                                    <small class="url">- ${response.data.menu_route}</small>
                                </div> 
                            </li>`);
                    }else {
                        $('#form-create').modal('hide');
                        $('li[data-id=' + response.data.id + '] > .dd-handle > span').html(response.data.menu_name);
                        $('li[data-id=' + response.data.id + '] > .dd-handle > small').html(response.data.menu_route);
                    }
                }
                else {
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

            }).fail(function (response) {
                var response = response.responseJSON;
                $('#form-create .modal-content').unblock();
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

    $(document).on('click', '.edit', function () {
        var id = $(this).data('id');
        $.ajax({
            url: menu_store+`/${id}/edit`,
            method: 'GET',
            dataType: 'json',
            beforeSend: function () {

            },
        }).done(function (response) {
            if (response.status) {
                $('#form-create .modal-title').html('Edit Menu');
                $('#form-create').modal('show');
                $('#form')[0].reset();
                $('#form .invalid-feedback').each(function () { $(this).remove(); });
                $('#form .form-group').removeClass('has-error').removeClass('has-success');
                $('#form input[name=_method]').attr('value', 'PUT');
                $('#form input[name=menu_name]').attr('value', response.data.menu_name);
                $('#form input[name=menu_route]').attr('value', response.data.menu_route);
                $('#form input[name=menu_icon]').attr('value', response.data.menu_icon);
                $('#form').attr('action', menu_store+`/${response.data.id}`);
            }
        }).fail(function (response) {
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
    })

    $(document).on('click', '.delete', function () {
        var id = $(this).data('id');
        bootbox.confirm({
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i>',
                    className: 'btn-primary btn-sm'
                },
                cancel: {
                    label: '<i class="fa fa-undo"></i>',
                    className: 'btn-default btn-sm'
                },
            },
            title: 'Delete data?',
            message: 'Are you sure want to delete this menu?',
            callback: function (result) {
                if (result) {
                    var data = {
                        _token: token
                    };
                    $.ajax({
                        url: menu_store+`/${id}`,
                        dataType: 'json',
                        data: data,
                        type: 'DELETE',
                        beforeSend: function () {
                            blockMessage('.dd', 'Loading', '#fff');
                        }
                    }).done(function (response) {
                        if (response.status) {
                            $('.dd').unblock();
                            $('.dd li[data-id=' + id + ']').remove();
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
                            toastr.success(response.message);
                        }
                        else {
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
                    }).fail(function (response) {
                        var response = response.responseJSON;
                        $('.dd').unblock();
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
            }
        });
    });

    $('.updateorder').on('click', function () {
        var data = {
            _token: token,
            order: JSON.stringify($('.dd').nestable('serialize'))
        };
        $.ajax({
            url: menu_store + "/order",
            dataType: 'json',
            data: data,
            type: 'POST',
            beforeSend: function () {
                blockMessage('.dd', 'Loading', '#fff');
            }
        }).done(function (response) {
            if (response.status) {
                $('.dd').unblock();
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
                toastr.success(response.message);
            }
            else {
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
        }).fail(function (response) {
            var response = response.responseJSON;
            $('.dd').unblock();
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
    })

});