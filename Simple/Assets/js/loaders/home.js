var data = {
    action: "getHomePage",
    controller: "PlayerData"
};
username = window.location.pathname.split('/')[2];
if (username) {
    data.username = username;
}
DataCall(data, function (data) {
    data = JSON.parse(data);
    texts = data.texts.split('|');
    if (data.found == true) {
        $("#home").empty().append('<div class="col-sm-7"><div class="box"><h2 class="green">' + texts[0] + '</h2><div class="inner"> ' + data.stats + '</div></div><div class="box rooms"><h2 class="red">' + texts[1] + '</h2><div class="inner">' + texts[3] + '</div></div></div><div class="col-sm-4"><div class="box"><h2 class="blue"></h2><div class="inner"><div style="width:128px;height:120px;float:right;background:url(' + SimpleCMS.avatar + '?figure=' + data.look + '&action=wav&gesture=sml&head_direction=3&direction=3&crr=3&size=l) no-repeat scroll 0px -40px transparent; margin: -30px -20px 0 0;"></div>' + data.about + '</div></div><div class="box badges"><h2 class="orange">' + texts[2] + '</h2><div id="badges"><div class="inner">' + texts[4]);

        if (data.rooms.length) {
            $(".rooms h2").append(' (' + data.rooms.length + ')');
            html = '<table border="0" cellspacing="1" style="width:100%" class="list_table"><tbody>';
            for (var i = 0; i < data.rooms.length; i++) {
                room = data.rooms[i];
                html += '<tr onclick="enterroom(' + room.id + ');"><td>' + room.name + '</tr></td>';
            }
            $(".rooms .inner").empty().append(html);
        }
        if (data.badges.length) {
            $(".badges h2").append(' (' + data.badges.length + ')');
            html = '<div class="container" style="top:10px;">';
            for (var i = 0; i < data.badges.length; i++) {
                badge = data.badges[i];
                html += '<div class="col-sm-2" style="height:50px;background:url(' + data.badgelocation + '/' + badge.badge_code + '.gif) no-repeat"></div>';
            }
            $("#badges").empty().append(html);
        }
    }
    else {
        $(".box").empty().append('<h2 class="red">' + texts[0] + '</h2><div class="inner">' + texts[1] + '</div>');
    }
});