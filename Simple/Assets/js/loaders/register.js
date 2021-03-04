$("#username, #password, #password2, #email").keyup(function (event) {
    if (event.keyCode == 13) {
        $("#register").click();
    }
});

$('#register').click(function () {
    valid = true;
    $('.col-md-4 :input:visible[required="required"]').each(function() {
        if (!this.validity.valid) {
            $(this).focus();
            $(this).animate({
                backgroundColor: 'rgb(193, 43, 43);',
                color: 'white',
            });
            $(this).animate({
                backgroundColor: 'white',
                color: 'black',
            });
            valid = false;
            return false;
        }
    });
    if (valid) {
        var data = {
            action: 'RegisterJson',
            controller: "Auth",
            username: $('#username').val(),
            password: $('#password').val(),
            retypedPassword : $('#password2').val(),
            email: $('#email').val(),
            look: $('#avatar-code').val()
        };
        if (typeof grecaptcha != 'undefined') {
            data.captcha = grecaptcha.getResponse(captcha);
        }
        DataCall(data, function (data) {
            data = JSON.parse(data);
            if (data.valid) {
                $(".alert").hasClass("alert-danger") && $(".alert").removeClass("alert-danger");
                $(".alert").addClass("alert-success").html(data.response).show();
                setTimeout(function () {
                    window.location.href = "/me";
                }, 750);
            }
            else {
                if (typeof grecaptcha != 'undefined') {
                    grecaptcha.reset(captcha);
                }
                $(".alert").addClass("alert-danger").html(data.response).show().delay(5000).fadeOut("slow");
            }
        });
    }
});