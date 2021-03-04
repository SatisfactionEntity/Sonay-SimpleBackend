function FacebookLogin() {
    FB.login(function (response) {
        if (response.status === 'connected') {
            SendData();
        }
    }, { scope: 'email' });
}

function SendData() {
    var data = {
        action: "FacebookLogin",
        controller: "Auth"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            if ($(".alert").length) {
                $(".alert").hasClass("alert-danger") && $(".alert").removeClass("alert-danger");
                $(".alert").addClass("alert-success").html(data.response).show();
                setTimeout(function () {
                    window.location.href = "/me";
                }, 750);
            }
            else {
                window.location.href = "/me";
            }

        }
        else {
            if ($(".alert").length) {
                $(".alert").addClass("alert-danger").html(data.response).show().delay(5000).fadeOut("slow");
            }
            else {
                alert(data.response.replace(/<br>/g, '\n'));
            }
        }
    });
}

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/nl_NL/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));