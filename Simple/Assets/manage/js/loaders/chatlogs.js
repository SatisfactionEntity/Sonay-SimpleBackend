$("#searchfield input").keyup(function (event) {
    if (event.keyCode == 13) {
        $("#search").click();
    }
});

$("#close").click(function () {
    $('#searchresults').fadeOut('slow');
});

$("#cancelupdate").click(function () {
    $('#editplayer').fadeOut('slow');
});

$("#search").click(function () {
    var data = {
        username: $('#username').val(),
        strict: $("#strict input[type='radio']:checked").val(),
        ip: $('#ip').val(),
        action: "searchPlayers",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.message) {
            $("#searchfield .alert-danger").show().empty().html('<strong>'+data.message+'</strong>').delay(10000).fadeOut("slow");
        }
        else
        {
            html = '';
            for (i = 0; i < data.players.length; i++) {
                player = data.players[i];
                html += '<tr data-id="' + player.id + '"><td>' + player.id + '</td><td>' + player.username + '</td><td>' + player.mail + '</td><td>' + player.ip_current + '</td><td>' + player.last_online + '</td><td><center><div class="edit" data-id="' + player.id + '"><i style="padding-top:5px;color:green" class="fa fa-edit"></i></div></center></td><td><center><div class="login" data-id="' + player.id + '"><i style="padding-top:4px;color:red" class="fa fa-sign-in"></i></div></center></td></tr>';
            }
            $("tbody:first").find("tr:gt(0)").empty();
            $('tbody:first').append(html);
            $('#searchresults').fadeIn('slow');
            $("#username").val('');
            $("#ip").val('');
        }
    });
});