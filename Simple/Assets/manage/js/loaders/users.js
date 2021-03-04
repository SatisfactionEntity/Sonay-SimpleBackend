$("#searchfield input").keyup(function (event) {
    if (event.keyCode == 13) {
        $("#search").click();
    }
});

$('.row').on('click', '.edit', function () {
    id = $(this).data('id');
    var data = {
        id: id,
        action: "getPlayerData",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.player.id) {
            $('#editusername').val(data.player.username);
            $('#editemail').val(data.player.mail);
            $('#editpassword').val('');
            $('#editcredits').val(data.player.credits);
            $('#editduckets').val(data.player.duckets);
            $('#editdiamonds').val(data.player.diamonds);
            $('#editcrowns').val(data.player.crowns);
            $('#editrank option[data-id="'+ data.player.rank +'"]').prop('selected', true);
            $('#update').data('id', data.player.id);
            if ($('#unban').length && data.player.ban_type) {
                $('#unban').data('id', data.player.id).show();
            } else {
                $('#unban').hide();
            }
            $('#editplayer').fadeIn();
            $("html, body").animate({
                scrollTop: 0
            }, "slow");
        }
    });
});

$('.row').on('click', '.login', function () {
    id = $(this).data('id');
    var data = {
        id: id,
        action: "loginWithPlayer",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            window.location.href = "/me";
        }
    });
});

$("#update").click(function () {
    id = $(this).data('id');
    var data = {
        id: id,
        username: $('#editusername').val(),
        email: $('#editemail').val(),
        password: $('#editpassword').val(),
        credits: $('#editcredits').val(),
        duckets: $('#editduckets').val(),
        diamonds: $('#editdiamonds').val(),
        crowns: $('#editcrowns').val(),
        rank: $("#editrank").find(":selected").data('id'),
        action: "editPlayer",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $("#editplayer .alert-success").show().empty().html('<strong>'+data.message+'</strong>').delay(10000).fadeOut("slow");
        }
        else {
            $("#editplayer .alert-danger").show().empty().html('<strong>'+data.message+'</strong>').delay(10000).fadeOut("slow");
        }
    });
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