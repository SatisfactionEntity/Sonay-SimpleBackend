var ws;
var reconnectTimer;
var reconnectAttempts = 0;
var reconnectTimestamp;

$(document).ready(function () {
    if (!FlashDetect.installed) {
        $("#disconnected, #hide-message,#client-support, #no-flash").show();
        $("#client").hide();
        if (bowser.chrome) {
            $("#client-reload, #hide-message, #client-support, #info-flash, #info-flash-extra").hide();
            $("#client-title").html('Flash toestaan in Chrome!');
            $("#info-allow").html('Het kan zo zijn dat Flash Player niet toegestaan is op je Google Chrome. Helaas heb je dit wel nodig om Habplay te spelen! Gelukkig is er hiervoor een oplossing. <br/><br/>Klik hieronder op "Flash inschakelen" om Habplay te spelen!<br/><br/>');
            $("#info-allow-button, #allow-flash-button-extern").show();
        } else if (bowser.msedge) {
            $("#client-reload, #hide-message, #client-support, #info-flash, #info-flash-extra").hide();
            $("#client-title").html('Flash toestaan in Edge!');
            $("#info-allow").html('Het kan zo zijn dat Flash Player niet toegestaan is op je Edge Browser. Helaas heb je dit wel nodig om Habplay te spelen! Gelukkig is er hiervoor een oplossing. <br/><br/>Klik hieronder op "Flash inschakelen" om Habplay te spelen!<br/><br/>');
            $("#info-allow-button, #allow-flash-button-extern").show();
        }
        $("#disconnected").show().css('z-index', 0);
        $("#flash-wrapper").remove();
    } else if ($("#room")[0]) {
        showAd();
    }
    $('#allow-flash-button-extern').click(function (event) {
        if (bowser.chrome) {
            $("#info-allow").html('Je krijgt nu een melding van Google Chrome linksboven in je browser. <br/><br/><b>Druk op "toestaan" om flash te activeren! </b><br/><br/>');
            event.stopPropagation();
        }
        if (bowser.msedge) {
            $("#info-allow").html('Je krijgt nu een melding van Edge rechtsboven in je browser. <br/><br/><b>Druk op "Altijd toestaan" om flash te activeren en Habplay te spelen! </b><br/><br/>');
            event.stopPropagation();
        }
        $("#allow-flash-button-extern").hide();
        $("#allow-flash-button-more").show();
    });
    $('#hide-message').click(function () {
        $("#disconnected").hide();
    });
});

var ConnectionHandler = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    DataController: function (e) {
        var t = "";
        var n, r, i;
        var s, o, u, a;
        var f = 0;
        e = e.replace(/[^A-Za-z0-9+/=]/g, "");
        while (f < e.length) {
            s = this._keyStr.indexOf(e.charAt(f++));
            o = this._keyStr.indexOf(e.charAt(f++));
            u = this._keyStr.indexOf(e.charAt(f++));
            a = this._keyStr.indexOf(e.charAt(f++));
            n = s << 2 | o >> 4;
            r = (o & 15) << 4 | u >> 2;
            i = (u & 3) << 6 | a;
            t = t + String.fromCharCode(n);
            if (u != 64) {
                t = t + String.fromCharCode(r)
            }
            if (a != 64) {
                t = t + String.fromCharCode(i)
            }
        }
        t = ConnectionHandler.MD5Parser(t);
        return t
    },
    MD5Parser: function (e) {
        var t = "";
        var n = 0;
        var r = c1 = c2 = 0;
        while (n < e.length) {
            r = e.charCodeAt(n);
            if (r < 128) {
                t += String.fromCharCode(r);
                n++
            } else if (r > 191 && r < 224) {
                c2 = e.charCodeAt(n + 1);
                t += String.fromCharCode((r & 31) << 6 | c2 & 63);
                n += 2
            } else {
                c2 = e.charCodeAt(n + 1);
                c3 = e.charCodeAt(n + 2);
                t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
                n += 3
            }
        }
        return t
    }
}
data = ConnectionHandler.DataController(HabboManager).replace(/%3A/g, ":").replace(/%2F/g, "/").replace(/%68/g, 'h').replace(/%74/g, 't').replace(/%70/g, 'p').replace(/%73/g, 's').split('%7C');
$("#client").empty().append('\
<script> \
    var Client = new SWFObject("' + data[0] + '/' + data[8] + '", "client", "100%", "100%", "10.0.0"); \
    Client.addVariable("client.allow.cross.domain", "1"); \
    Client.addVariable("client.notify.cross.domain", "1"); \
    Client.addVariable("connection.info.host", "' + data[11] + '"); \
    Client.addVariable("connection.info.port", "' + data[12] + '"); \
    Client.addVariable("site.url", "' + data[10] + '"); \
    Client.addVariable("url.prefix", "' + data[10] + '"); \
    Client.addVariable("client.reload.url", "' + data[10] + '/me"); \
    Client.addVariable("client.fatal.error.url", "' + data[10] + '/me"); \
    Client.addVariable("client.connection.failed.url", "' + data[10] + '/me"); \
    Client.addVariable("external.override.texts.txt", "' + data[6] + '"); \
    Client.addVariable("external.override.variables.txt", "' + data[7] + '"); \
    Client.addVariable("external.variables.txt", "' + data[2] + '"); \
    Client.addVariable("external.texts.txt", "' + data[1] + '");  \
    Client.addVariable("external.figurepartlist.txt", "' + data[3] + '"); \
    Client.addVariable("productdata.load.url", "' + data[4] + '"); \
    Client.addVariable("furnidata.load.url", "' + data[5] + '"); \
    Client.addVariable("use.sso.ticket", "1"); \
    Client.addVariable("sso.ticket", "' + data[13] + '"); \
    Client.addVariable("processlog.enabled", "0"); \
    Client.addVariable("client.starting", "Loading..."); \
    Client.addVariable("flash.client.url", "' + data[0] + '/"); \
    Client.addVariable("client.starting.revolving", "' + data[9] + '"); \
    Client.addVariable("user.hash", "' + data[13] + '"); \
    Client.addVariable("flash.client.origin", "popup"); \
    Client.addVariable("nux.lobbies.enabled", "true"); \
    Client.addVariable("country_code", "NL"); \
    Client.addParam("base", "' + data[0] + '/"); \
    Client.addParam("allowScriptAccess", "always"); \
    Client.addParam("menu", false); \
    Client.addParam("wmode", "opaque"); \
    Client.write("client");	\
    FlashExternalInterface.signoutUrl = "' + data[10] + '/me"; \
</script>');

if ($("#room")[0]) {
    $(document).ready(function () {
        adTimer.play();
    });

    var Interval = 1200000; // 20 minuten;
    var I = 30;
    var timer = $.timer(function () {
        I = 30;
        showAd();
        adTimer.play();
    });
    timer.set({
        time: Interval,
        autostart: true
    });

    var adClosing = $.timer(function () {
        closeAd();
        adClosing.stop();
    });

    adClosing.set({
        time: 30000,
        autostart: false
    });

    var adTimer = $.timer(function () {
        I--;
        $("#adTimer").html("Deze advertentie sluit over <b>" + I + "</b> seconden!");
        if (I <= 0)
            adTimer.stop();
    });

    adTimer.set({
        time: 1000,
        autostart: false
    });

    function showAd() {
        $("#room").fadeIn("slow");
        adClosing.play();
        timer.stop();
    }

    function closeAd() {
        $("#room").fadeOut("slow");
        timer.play();
    }
}

function Fullscreen() {
    if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement) {
        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
            document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
    }
}

function makeFullScreen(divObj) {
    if (divObj.requestFullscreen) {
        divObj.requestFullscreen();
    } else if (divObj.msRequestFullscreen) {
        divObj.msRequestFullscreen();
    } else if (divObj.mozRequestFullScreen) {
        divObj.mozRequestFullScreen();
    } else if (divObj.webkitRequestFullscreen) {
        divObj.webkitRequestFullscreen();
    } else {
        console.log("Fullscreen API is not supported");
    }
}

function DataCall(vars, callback) {
    $.ajax({
        type: "POST",
        url: "/Data/DataController",
        data: vars,
        success: function (data) {
            callback(data);
        }
    });
}

function sendMessage(json) {

    var jsonData = JSON.stringify(json);

    console.log('Verstuurd: ' + jsonData);

    if (ws.readyState === ws.OPEN) {
        ws.send(jsonData);
    }
}

function connect() {

    ws = new WebSocket('wss://socket.yabbis.nl:2023/');

    ws.onopen = function() {
        sendMessage({
            key: 'wants-vertification',
            argument: getcookie('simple_user_hash')
        });
    };
	
	

    ws.onmessage = function(e) {

        console.log('Ontvangen: ' + e.data);

        var message;

        try {
            message = JSON.parse(e.data);
        } catch (e) {
            return;
        }

        handleResponse(message);
    };

    ws.onclose = function() {
        reconnectTimestamp = moment().unix() + (5 + (reconnectAttempts * 10));
        clearInterval(reconnectTimer);
        reconnectTimer = setInterval(reconnect, 5000 + (reconnectAttempts * 10000));
    };
}

function reconnect() {
    reconnectAttempts++;
    ws.close();
    ws = undefined;
    connect();
}

function getcookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
}

function handleResponse(message) {
    switch (message.key) {

        case 'close-connection':

            ws.close();

            break;

        case 'welcome':

            $("#socket-stats #socket-status").text('Verbonden!').css("color", 'rgb(0, 128, 0)');

            break;

        case 'redirect':

            window.location.href = message.argument;

            break;

        case 'voice':

            var msg = new SpeechSynthesisUtterance(message.argument);
            msg.lang = 'nl_NL';
            window.speechSynthesis.speak(msg);

            break;

        case 'youtube-add':

            $('#jukebox-add').show();
            $('#jukebox-add-video-id').val('');

            break;

        case 'youtube-play':

            jukeboxPlayer.loadVideoById(message.videoId, message.startAt);

            break;

        case 'youtube-up-next':

            $('#jukebox-next .value').text(escapehtml(message.title));

            break;

        case 'youtube-stop':

            jukeboxPlayer.stopVideo();
            $('#jukebox-current').text('Geen video');

            break;

        case 'close-youtube-player':

            $('#music-box').hide();
            $('#jukebox-player-wrapper').hide();
            jukeboxPlayer.stopVideo();

            break;
        case 'hide-youtube-button':

            $('#youtube_close_button').hide();

            break;

        case 'open-youtube-player':

            $('#music-box').show();

            break;
        case 'show-youtube-button':

            $('#youtube_close_button').show();

            break;

        case 'online-count':

            $('#count').empty().text(message.argument);

            break;

        default:

            console.log("Waarschuwing: de key '" + message.key + "' kan niet worden gevonden!");
    }
}

// Jukebox / YouTube Player

var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var jukeboxPlayer;

function onYouTubeIframeAPIReady() {
    jukeboxPlayer = new YT.Player('jukebox-player', {
        height: '0',
        width: '0',
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        }
    });
}

// Jukebox / YouTube Player

var bbjMusicVolumeSaveTimer;

function setJukeboxVolume(volume) {
    if (jukeboxPlayer != null) {
        jukeboxPlayer.setVolume(volume);
        $('#jukebox-volume').val(volume);
    }
}

var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var jukeboxPlayer;
window.onYouTubeIframeAPIReady = function() {
    jukeboxPlayer = new YT.Player('jukebox-player', {
        width: '408',
        height: '236',
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        },
        playerVars: {
            controls: 0
        }
    });
}

function onPlayerReady(event) {}

function onPlayerStateChange(event) {
    var playing = jukeboxPlayer.getVideoData().title;

    if (playing != '') {
        $('#jukebox-current').text(playing);
    }

    if (event.data == YT.PlayerState.ENDED) {
        $('#jukebox-current').text('Nothing playing');
    }
}

// YouTube

$('#jukebox-play').click(function() {
    if ($(this).hasClass('fa-stop')) {
        jukeboxPlayer.mute();

        $('#jukebox-play').removeClass('fa-stop');
        $('#jukebox-play').addClass('fa-play');
    } else {
        jukeboxPlayer.unMute();

        $('#jukebox-play').removeClass('fa-play');
        $('#jukebox-play').addClass('fa-stop');
    }
});

$('#jukebox-volume').on('input', function() {
    setJukeboxVolume(parseInt($(this).val()));
});

$('#jukebox-queue-song-button').click(function() {
    sendMessage({
        key: 'youtube-request',
        data: $('#jukebox-add-video-id').val()
    });
    $('#jukebox-add-video-id').val('');
});

$('#jukebox-tv-toggle').click(function() {
    $('#jukebox-player-wrapper').toggle();
});

$(function() {

    $('.draggable').draggable({
        handle: '.header, .draggable-handler',
        containment: 'window'
    }).css("position", "absolute");

    $('.draggable-self').draggable({
        containment: 'window'
    }).css("position", "absolute");

    connect();
});

function escapehtml(text) {
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

$('.close').click(function() {
    $(this).closest('.closable').hide();
});




function updateCounter() {
	var data = {
        action: "getOnline",
        controller: "Game"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
			$('#count').text(data.online);
        }
    });
}

setInterval(function(){ updateCounter(); }, 2500);