var delay = (function () {
    var timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

$("#username, #password, #twosteps").keyup(function (event) {
    if (event.keyCode == 13) {
        $("#login").click();
    }
    else if ($(this).attr('id') == 'username')
    {
        delay(function () {
            var data = {
                action: 'getPlayerData',
                controller: "Auth",
                input: $('#username').val()
            };
            DataCall(data, function (data) {
                data = JSON.parse(data);
                if (data.player) {
                    $('#imager').css("background-image", "url(" + SimpleCMS.avatar + "?figure=" + data.player.look + "&action=sit&direction=2&gesture=spk)");
                }
                else {
                    $('#imager').css("background-image", "url(/Simple/Assets/img/ghost.png)");
                }
                if (data.player.twofactor) {
                    $("#twostepsbox").show();
                }
                else {
                    $('#twostepsbox').hide();
                }
            });
        }, 300);
    }
});

$('#login').click(function () {
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
            action: 'Login',
            controller: "Auth",
            username: $('#username').val(),
            password: $('#password').val(),
        };
        if ($('#twostepsbox').is(":visible")) {
            data.auth = $('#twosteps').val();
        }
        if ($('#captchabox').is(":visible")) {
            data.captcha = grecaptcha.getResponse(captcha);
        }
        DataCall(data, function (data) {
            data = JSON.parse(data);
            if (data.valid) {
                $(".alert").hasClass("alert-danger") && $(".alert").removeClass("alert-danger");
                $(".alert").addClass("alert-success").html(data.message).show();
                setTimeout(function () {
                    window.location.href = "/site";
                }, 750);
            }
            else
            {
                $(".alert").addClass("alert-danger").html(data.message).show().delay(5000).fadeOut("slow");
                if (data.auth) {
                    $("#twostepsbox").show();
                }
                if (data.captcha) {
                    $('#captchabox').show();
                }
                if ($('#captchabox').is(":visible")) {
                    data.captcha = grecaptcha.reset(captcha);
                }
            }
        });
    }
});

function ForgotPassword() {
    $("body").append('<div id="overlay"></div><div id="forgot" class="box-overlay"><h2 class="blue">Wachtwoord vergeten</h2><p style="padding:20px">Raak niet in paniek! In Habplay hebben we de optie om je wachtwoord te resetten. Hiervoor moet je wel toegang hebben tot de e-mail die je hebt opgegeven bij je registratie. Vul hieronder je gebruikersnaam in en dan ontvang je binnen 5 minuten een e-mail met de desbetreffende informatie om je account terug te krijgen!<br><br><b>Gebruikersnaam:</b><input class="password" id="forgotusername"><span class="error"></span><span class="success"></span></p><p><button style="margin-left:5px;float: right;" onclick="stop(); return false;" class="btn btn-danger">Sluiten</button><button style="margin-left:5px;float:right" onclick="SendMail(); return false;" class="btn btn-primary">Stuur mail</button></p></div>');
}

function SendMail() {
    var data = {
        action: 'sendMail',
        controller: "Auth",
        username: $('#forgotusername').val()
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $(".success").show().empty().css('display', 'block').append(data.response).delay(6000).fadeOut("slow");
        }
        else {
            $(".error").show().empty().css('display', 'block').append(data.response).delay(3000).fadeOut("slow");
        }
    });
}
function stop() {
    $("#overlay").remove();
    $(".box-overlay").remove();
}