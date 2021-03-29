$(function(){
    $("#signin-form").validate({
        errorElement: "span",
        errorClass: "help-block",
        focusInvalid: !1,
        rules: {
            username: {
                required: !0,
            },
            password: {
                required: !0
            }
        },
        highlight: function (e) {
            $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
        },
        success: function (e) {
            $(e).closest(".form-group").removeClass("has-error").addClass("has-success"), $(e).remove()
        },
        submitHandler: function () {
            $.ajax({
                url: base_url + "login/auth_login",
                dataType: "json",
                type: "POST",
                data: $("#signin-form").serialize(),
                beforeSend: function () {
                    $('.ladda-button').ladda().ladda('start');
                },
                success: function (e) {
                    if (e.success) {
                        document.location = base_url + "dashboard";
                    } else {
                        $('.ladda-button').ladda().ladda('stop');
                        $.gritter.add({
                            title: "Login Failed",
                            text: "Username and Password Not Found"
                        });
                        $("#signin-form").effect("shake");
                    }
                },
                error: function (e) {
                    $('.ladda-button').ladda().ladda('stop');
                }
            });
        }
    });
});