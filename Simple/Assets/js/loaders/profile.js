$(".box .options ul li").click(function () {
    loaddata(this.id);
});

function loaddata(click) {
    if ($('.box .options .active#' + click).length) {
        return;
    }
    var data = {
        action: "view" + click,
        controller: "Settings"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        texts = data.texts.split('|');
        switchery = false;
        html = '<div class="box">';
        $('.box .options li').removeClass('active');
        $('.box .options #' + click).addClass('active');
        if (click == "Dashboard") {
            html += '<div class="inner"><h1>' + texts[0] + '</h1><img class="frank-key" src="/Simple/Assets/img/frank_12.gif">' + texts[1];
        }
        if (click == "Hotel") {
            if (data.hasOwnProperty('block_friendrequests')) {
                switchery = true;
            } else {
                switchery = 2;
            }
            html += '<div class="inner"> <h1>' + texts[0] + '</h1> <img class="school" src="/Simple/Assets/img/school.png"> <p>' + texts[1] + '</p><br><p class="alert blue">' + texts[2] + '</p></div></div><div class="box"><div class="inner">' + (switchery == 2 ? '<div class="error" style="display:inline">Je kan deze instellingen pas aanpassen zodra je in het hotel bent geweest!</div><br><br>' : '') + '<div class="question-div"> <b class="question">' + texts[3] + '</b><br><p class="question-text">' + texts[4] + '</p><input type="checkbox" id="friendrequests" class="js-switch" ' + (data.block_friendrequests == "1" ? 'checked /' : '') + '></div><div class="question-div"><b class="question">' + texts[5] + '</b><br><p class="question-text">' + texts[6] + '</p><input type="checkbox" id="roominvites" class="js-switch" ' + (data.block_roominvites == "1" ? 'checked /' : '') + '> </div><div class="question-div"><b class="question">' + texts[7] + '</b><br><p class="question-text">' + texts[8] + '</p><input type="checkbox" id="following" class="js-switch" ' + (data.block_following == "1" ? 'checked /' : '') + '></div>';
        }
        if (click == "General") {
            switchery = true;
            html += '<div class="inner"> <h1>' + texts[0] + '</h1> <img class="school" src="/Simple/Assets/img/shout_out.gif" style="margin-top:-20px"> <p>' + texts[1] + '</p><br><p class="alert blue">' + texts[2] + '</p></div></div><div class="box"><div class="inner"><b class="question">' + texts[3] + '</b><br>' + texts[4] + '<input type="text" class="motto autosave" name="motto" maxlength="38" value="' + data.motto + '"><div class="question-div"><b class="question">' + texts[5] + '</b><br><p class="question-text">' + texts[6] + '</p><input type="checkbox" id="home" class="js-switch" ' + (data.home == "1" ? 'checked /' : '') + '></div><div class="question-div"><b class="question">' + texts[7] + '</b><br><p class="question-text">' + texts[8] + '</p><input type="checkbox" id="radio" class="js-switch" ' + (data.radio == "1" ? 'checked /' : '') + '>';
        }
        if (click == "Email") {
            html += '<div class="inner"><h1>' + texts[0] + '</h1><img class="school" src="/Simple/Assets/img/email.gif"><p>' + texts[1] + '</p><br><div style="clear:both"></div></div><div style="clear:both"></div></div><div class="box"><div class="inner"><div class="success"></div><div class="error"></div><b class="question">' + texts[2] + '</b><br>' + texts[3] + '<input type="text" class="email click" name="email" value="' + data.email + '"><b class="question">' + texts[4] + '</b><br>' + texts[5] + '<input type="password" class="password click" name="password"><button style="float: right;" class="btn btn-success">' + texts[6] + '</button><div style="clear: both;">';
        }
        if (click == "Password") {
            html += '<div class="inner"><h1>' + texts[0] + '</h1><img class="school" src="/Simple/Assets/img/page_0.png"><p>' + texts[1] + '</p><br><div style="clear:both"></div></div><div style="clear:both"></div></div><div class="box"><div class="inner"><div class="success"></div>' + (data.password ? '<div style="display:block" class="error">' + data.password + '</div><div style="display:none" id="passbox">' : '<div class="error"></div><div id="passbox">') +'<b class="question">' + texts[2] + '</b><br>' + texts[3] + '<input type="password" class="password click" id="oldpassword" name="oldpassword"></div><b class="question">' + texts[4] + '</b><br>' + texts[5] + '<input type="password" class="password click" id="newpassword" name="newpassword"><b class="question">' + texts[6] + '</b><br>' + texts[7] + '<input type="password" class="password click" id="repassword" name="repassword"><button style="float:right" class="btn btn-success">' + texts[8] + '</button><div style="clear: both;">';
        }
        if (click == "Security") {
            html += '<div class="inner"><h1>' + texts[0] + '</h1><img class="school" src="/Simple/Assets/img/page_0.png" style="margin-top:-40px"><p>' + texts[1] + '</p><br><p class="alert blue">' + texts[2] + '</p></div><div style="clear: both;"></div></div><div style="clear: both;"></div></div><div class="box"><div class="inner"><div class="error"></div><div class="success"></div><b class="question">' + texts[3] + '</b><br><p class="question-text">' + texts[4] + '</p><div style="clear:both"></div><br><button class="btn btn-' + (data.auth ? 'danger">Tweestapsvertificatie uitschakelen' : 'success">Tweestapsvertficatie inschakelen') + '</button><div style="clear:both">';
        }
        if (click == "Look") {
            html += '<div class="inner"><h1>' + texts[0] + '</h1><img class="school" src="/Simple/Assets/img/email.gif"><p>' + texts[1] + '</p><br><div style="clear: both;"></div></div><div style="clear: both;"></div></div><div class="box"><div class="error"></div><div class="success"></div><div id="' + data.look + '&gender=' + data.gender + '" class="avatareditor"> <div class="types" id="nav"> <ul> <li class="active"><a href="#" data-navigate="hd" data-subnav="gender"><img src="/Simple/Assets/img/avatar/body.png"/> </a> </li><li> <a href="#" data-navigate="hr" data-subnav="hair"><img src="/Simple/Assets/img/avatar/hair.png"/> </a> </li><li> <a href="#" data-navigate="ch" data-subnav="tops"><img src="/Simple/Assets/img/avatar/tops.png"/> </a> </li><li> <a href="#" data-navigate="lg" data-subnav="bottoms"><img src="/Simple/Assets/img/avatar/bottoms.png"/> </a> </li></ul> </div><div class="types" id="nav"> <ul id="gender" class="display"> <li> <a href="#" id="M" data-gender="M">Mannelijk</a> </li><li> <a href="#" id="F" data-gender="F">Vrouwelijk</a> </li></ul> <ul id="hair" class="hidden"> <li> <a href="#" class="hair" data-navigate="hr">Haar</a> </li><li> <a href="#" class="hats" data-navigate="ha">Mutsen</a> </li><li> <a href="#" class="hair-accessories" data-navigate="he">Hoofdtooi</a> </li><li> <a href="#" class="glasses" data-navigate="ea">Brillen</a> </li><li> <a href="#" class="moustaches" data-navigate="fa">Maskers</a> </li></ul> <ul id="tops" class="hidden"> <li> <a href="#" class="tops" data-navigate="ch">Tops</a> </li><li> <a href="#" class="chest" data-navigate="cp">Sticker</a> </li><li> <a href="#" class="jackets" data-navigate="cc">Jassen</a> </li><li> <a href="#" class="accessories" data-navigate="ca">Juwelen</a> </li></ul> <ul id="bottoms" class="hidden"> <li> <a href="#" class="bottoms" data-navigate="lg">Broeken</a> </li><li> <a href="#" class="shoes" data-navigate="sh">Schoenen</a> </li><li> <a href="#" class="belts" data-navigate="wa">Riemen</a> </li></ul> </div><div style="float:right;right:5%;position:relative"> <img id="avatar" value="" src=""> <div style="clear: both"></div><input type="hidden" name="figure" id="avatar-code"> </div><div id="clothes-colors" style="margin-bottom:20px;margin-top:40px;margin-left:12px"> <div id="clothes"></div><div style="clear: both"></div><div id="palette"></div></div></div><div style="clear:both"></div><button type="submit" style="float:right" class="btn btn-success figureID">' + texts[2] + '</button><div style="clear:both">';
            $.getScript("/Simple/Assets/js/profile/avatar.js");
        }
        if (click == "Sessions") {
            html += '<div class="inner"><h1>' + texts[0] + '</h1><p>' + texts[1] + '</p><br><div style="clear: both;"></div></div><div style="clear: both;"></div></div>';
            if (data.count > 25)
            {
                html += '<div class="class-list right">';
                for (var i = 1; i <= Math.ceil(data.count / 25); i++) {
                    if (i == 20) {
                        break;
                    }
                    html += '<div id="sgoto" data-id="'+(i-1)+'" '+ (i == 1 ? 'style="font-weight:bold" ' : '') +'class="class-1">'+i+'</div>';
                }
                html += '</div><div style="clear:both"></div>';
            }
            html += '<div class="box"><div class="success"></div><table class="pure-table"><thead><tr><th>#</th><th>' + texts[2] + '</th><th>' + texts[3] + '</th><th>' + texts[4] + '</th><th>' + texts[5] + '</th></thead><tbody>';
            if (data.sessions) {
                for (var i = 0; i < data.sessions.length; i++) {
                    session = data.sessions[i];
                    html += '<tr' + (session.own ? ' class="table-green"' : '') + ' data-id="' + session.id + '"><td>' + (i + 1) + '</td><td>' + session.ip + '</td><td>' + session.created + '</td><td>' + session.expire + '</td><td id="slogout" class="view" data-id="' + session.id + '">' + texts[6] + '</td></tr>';
                }
            }
        }
        if (click == "Logs") {
            html += '<div class="inner"><h1>' + texts[0] + '</h1><img class="school" src="/Simple/Assets/img/hacked.png"><p>' + texts[1] + '</p><br><div style="clear:both"></div></div><div style="clear:both"></div></div>';
            if (data.count > 25)
            {
                html += '<div class="class-list right">';
                for (var i = 1; i <= Math.ceil(data.count / 25); i++) {
                    if (i == 20) {
                        break;
                    }
                    html += '<div id="lgoto" data-id="'+(i-1)+'" '+ (i == 1 ? 'style="font-weight:bold" ' : '') +'class="class-1">'+i+'</div>';
                }
                html += '</div><div style="clear:both"></div>';
            }
            html += '<div class="box"><table class="pure-table"><thead><tr><th>#</th><th>' + texts[2] + '</th><th>' + texts[3] + '</th><th>' + texts[4] + '</th></tr></thead><tbody>';
            if (data.logs) {
                for (var i = 0; i < data.logs.length; i++) {
                    log = data.logs[i];
                    html += '<tr data-id="' + (i+1) + '" data-ip="' + log.ip + '" data-info="' + log.data + '"><td>' + (i + 1) + '</td><td>' + log.action + '</td><td>' + log.date + '</td><td class="view lview" data-id="' + (i+1) + '">Bekijk</td></tr>';
                }
            }
        }
        $("#js").html(html);
        if (switchery) {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            if (switchery == 2) {
                elems.forEach(function (html) {
                    var switchery = new Switchery(html, {secondaryColor: '#E85C3F', disabled: true});
                });
            }
            else {
                elems.forEach(function (html) {
                    var switchery = new Switchery(html, {secondaryColor: '#E85C3F'});
                });
            }
        }
    });
}
$('#js').on('click', '.switchery', function () {
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    id = $('.box .options .active').attr('id');
    var data = {
        action: "saveData",
        controller: "Settings"
    };
    if (id == "General") {
        data.home = false;
        data.radio = false;
        elems.forEach(function (html) {
            if (html.id == "home") {
                data.home = html.checked;
            }
            else if (html.id == "radio") {
                data.radio = html.checked;
            }
        });
    }
    if (id == "Hotel") {
        data.friendrequests = false;
        data.roominvites = false;
        data.following = false;
        elems.forEach(function (html) {
            if (html.id == "friendrequests") {
                data.friendrequests = html.checked;
            } else if (html.id == "roominvites") {
                data.roominvites = html.checked;
            } else if (html.id == "following") {
                data.following = html.checked;
            }
        });
    }
    DataCall(data, function (data) {
    });
});
$('#js').on('keyup', '.click', function (event) {
    if (event.keyCode == 13) {
        $("#js .btn.btn-success").click();
    }
});
$('#js').on('click', '.btn.btn-success, .btn.btn-danger', function () {
    id = $('.box .options .active').attr('id');
    var data = {
        action: "saveData",
        controller: "Settings"
    }
    if (id == "Email") {
        data.email = $('.email').val();
        data.password = $('.password').val();
        DataCall(data, function (data) {
            data = JSON.parse(data);
            if (data.type == '1') {
                $('.email').animate({
                    backgroundColor: 'rgb(100, 189, 99)',
                    color: 'white',
                });
                $('.email').animate({
                    backgroundColor: 'white',
                    color: 'black',
                });
                $('.password').val('');
                $("#js .success").show().empty().text(data.message).delay(6000).fadeOut("slow");
            } else {
                if (data.type) {
                    $('.' + data.type).animate({
                        backgroundColor: 'rgb(193, 43, 43);',
                        color: 'white',
                    });
                    $('.' + data.type).animate({
                        backgroundColor: 'white',
                        color: 'black',
                    });
                }
                $("#js .error").show().empty().text(data.message).delay(3000).fadeOut("slow");
            }
        });
    }
    if (id == "Password") {
        data.oldpass = $('#oldpassword').val();
        data.newpass = $('#newpassword').val();
        data.newpassrepeat = $('#repassword').val();
        DataCall(data, function (data) {
            data = JSON.parse(data);
            if (data.valid == true) {
                $("#js .success").show().empty().text(data.message).delay(6000).fadeOut("slow");
                $('#oldpassword').val('');
                $('#newpassword').val('');
                $('#repassword').val('');
                if ($('#passbox').css('display') == 'none') {
                    $('#passbox').show();
                    $("#js .error").hide();
                }
            } else {
                if (data.type) {
                    $('#' + data.type).animate({
                        backgroundColor: 'rgb(193, 43, 43);',
                        color: 'white',
                    });
                    $('#' + data.type).animate({
                        backgroundColor: 'white',
                        color: 'black',
                    });
                }
                $("#js .error").show().empty().text(data.message).delay(3000).fadeOut("slow");
            }
        });
    }
    if (id == "Security") {
        var data = {
            action: "getAuthData",
            controller: "Me"
        }
        DataCall(data, function (data) {
            data = JSON.parse(data);
            texts = data.texts.split('|');
            $("html, body").animate({
                scrollTop: 0
            }, "slow");
            if (data.enabled) {
                $("body").append('<div id="overlay"></div><div class="box-overlay"><h2 class="orange">' + texts[0] + '</h2><p style="padding:20px">' + texts[1] + '<input id="authpassword" type="password" class="password"><b>' + texts[2] + '</b><input id="authcode" type="number" class="password"><span class="error"></span></p><p><button style="margin-left:5px;float: right;" onclick="stop(); return false;" class="btn btn-danger">' + texts[3] + '</button><button style="margin-left:5px;float:right" onclick="send(true); return false;" class="btn btn-primary">' + texts[4] + '</button></p></div><div class="clear">');
            }
            else {
                $("body").append('<div id="overlay"></div><div id="auth1" class="box-overlay"><h2 class="orange">' + texts[0] + '</h2><p style="padding:20px">' + texts[1] + '</p><p><button style="margin-left:5px;float: right;" onclick="stop(); return false;" class="btn btn-danger">' + texts[2] + '</button><button style="margin-left:5px;float:right" onclick="next(); return false;" class="btn btn-primary">' + texts[3] + '</button></p></div><div class="clear"></div><div id="auth2" style="display:none" class="box-overlay"><h2 class="orange">' + texts[4] + '</h2><img style="float:right" src="' + data.image + '"><p style="padding:20px">' + texts[5] + '<input type="password" class="password" id="authpassword"><b>' + texts[6] + '</b><input id="authcode" type="number" class="password"><span class="error"></span></p><p><button type="submit" style="margin-left:5px;float: right;" onclick="stop(); return false;" class="btn btn-danger">' + texts[2] + '</button><button style="margin-left:5px;float:right" onclick="send(); return false;" class="btn btn-primary">' + texts[7] + '</button></p></div>');
            }
        });
    }
    if (id == "Look") {
        data.look = $('#avatar-code').val();
        DataCall(data, function (data) {
            data = JSON.parse(data);
            if (data.valid == true) {
                $("#js .success").show().empty().text(data.message).delay(6000).fadeOut("slow");
            }
            else {
                $("#js .error").show().empty().text(data.message).delay(3000).fadeOut("slow");
            }
        });
    }
    return false;
});

var delay = (function () {
    var timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

$('#js').on('input', '.autosave', function () {
    var click = this;
    delay(function () {
        var data = {
            motto: $(click).val(),
            action: "saveData",
            controller: "Settings"
        };
        DataCall(data, function (data) {
            if (data == 1) {
                $('.motto').animate({
                    backgroundColor: 'rgb(100, 189, 99)',
                    color: 'white',
                });
                $('.motto').animate({
                    backgroundColor: 'white',
                    color: 'black',
                });
            } else {
                $('.' + data).animate({
                    backgroundColor: 'rgb(193, 43, 43);',
                    color: 'white',
                });
                $('.' + data).animate({
                    backgroundColor: 'white',
                    color: 'black',
                });
            }
        });
    }, 600);
    return false;
});

$('#js').on('click', '#slogout', function () {
    id = $(this).data('id');
    var data = {
        session: id,
        action: "deleteSession",
        controller: "Settings"
    };
    DataCall(data, function (data) {
        if (data) {
            if ($('tr[data-id="' + id + '"]').hasClass('table-green')) {
                window.location.href = "/index";
            }
            $('tr[data-id="' + id + '"]').remove();
            $("#js .success").show().empty().text(data);
        }
    });
});

$('body').on('click', '.lview', function () {
    $("html, body").animate({
        scrollTop: 0
    }, "slow");
    id = $(this).data('id');
    action = $('tr[data-id="' + id + '"] td').eq(1).text();
    date = $('tr[data-id="' + id + '"] td').eq(2).text();
    ip = $('tr[data-id="' + id + '"]').data('ip');
    info = $('tr[data-id="' + id + '"]').data('info');
    stop();
    $("body").append('<div id="overlay"></div><div class="box-overlay"> <h2 class="orange">Informatie over log: ' + id + '</h2> <p style="padding:20px"><b>Actie:</b> <br><input class="form-control" value="'+ action +'" type="text" disabled><br><b>Datum:</b> <br><input class="form-control" value="'+ date +'" type="text" disabled><br><b>IP van uitvoerder:</b> <br><input class="form-control" value="'+ ip +'" type="text" disabled><br><b>Bijgeleverde data:</b> <br><input class="form-control" value="'+ info +'" type="text" disabled> </p><p><button style="margin-left:5px;float:right" onclick="stop();" class="btn btn-danger">Sluiten</button>' + ($('tr[data-id="' + (id+1) + '"]')[0] ? '<button style="margin-left:5px;float:right" class="btn btn-primary lview" data-id="'+ (id+1) +'">Volgende</button>' : '') + ($('tr[data-id="' + (id-1) + '"]')[0] ? '<button style="margin-left:5px;float:right" class="btn btn-primary lview" data-id="'+ (id-1) +'">Vorige</button>' : ''));
});

$('#js').on('click', '#sgoto', function () {
    id = $(this).data('id');
    var data = {
        action: "viewSessions",
        controller: "Settings",
        load: id,
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.sessions) {
            html = '';
            for (var i = 0; i < data.sessions.length; i++) {
                session = data.sessions[i];
                html += '<tr' + (session.own ? ' class="table-green"' : '') + ' data-id="' + session.id + '"><td>' + (i+1+(id*25)) + '</td><td>' + session.ip + '</td><td>' + session.created + '</td><td>' + session.expire + '</td><td id="slogout" class="view" data-id="' + session.id + '">Log uit</td></tr>';
                $("#js tbody").empty().html(html);
            }
        }
        $('.class-1').css('font-weight', '');
        $('.class-1[data-id="' + id + '"]').css('font-weight', 'bold');
    });
});

$('#js').on('click', '#lgoto', function () {
    id = $(this).data('id');
    var data = {
        action: "viewLogs",
        controller: "Settings",
        load: id,
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.logs) {
            html = '';
            for (var i = 0; i < data.logs.length; i++) {
                log = data.logs[i];
                html += '<tr><td>' + (i+1+(id*25)) + '</td><td>' + log.action + '</td><td>' + log.date + '</td><td class="view lview" data-id="' + (i+1+(id*25)) + '" data-info="' + log.data + '" data-ip="' + log.ip + '">Bekijk</td></tr>';
                $("#js tbody").empty().html(html);
            }
        }
        $('.class-1').css('font-weight', '');
        $('.class-1[data-id="' + id + '"]').css('font-weight', 'bold');
    });
});

function next() {
    $("#auth1").remove();
    $("#auth2").show();
}

function send(remove) {
    var data = {
        code: $('#authcode').val(),
        password: $('#authpassword').val(),
        action: remove ? "deleteAuth" : "tryAuthCode",
        controller: "Me"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            stop();
            $("#js .success").show().empty().text(data.message).delay(10000).fadeOut("slow");
        }
        else {
            $(".box-overlay .error").show().empty().text(data.message).delay(5000).fadeOut("slow");
        }
    });
}
