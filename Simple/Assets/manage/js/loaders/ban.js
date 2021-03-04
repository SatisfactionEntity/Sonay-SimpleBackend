$('.row').on('click', '.delete', function () {
    id = $(this).data('id');
    var data = {
        id: id,
        action: "deleteBan",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $('tbody tr[data-id="'+ id +'"]').fadeOut('slow');
        }
    });
});

$("#search").click(function () {
    var data = {
        username: $('#username').val(),
        strict: $("#strict input[type='radio']:checked").val(),
        ip: $('#ip').val(),
        action: "searchBans",
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
            for (i = 0; i < data.bans.length; i++) {
                ban = data.bans[i];
                html += '<tr data-id="' + ban.id + '"><td>' + ban.id + '</td><td>' + ban.username + '</td><td>' + ban.type + '</td><td>' + ban.staff_username + '</td><td>' + ban.ban_expire + '</td><td>' + ban.ban_reason + '</td><td><center><div class="delete" data-id="' + ban.id + '"><i style="padding-top:4px;color:red" class="fa fa-trash"></i></div></center></td></tr>';
            }
            $("tbody:first").find("tr:gt(0)").empty();
            $('tbody:first').append(html);
            $('#searchresults').fadeIn('slow');
            $("#username").val('');
            $("#ip").val('');
        }
    });
});

$("#close").click(function () {
    $('#searchresults').fadeOut('slow');
});

$("#searchfield input").keyup(function (event) {
    if (event.keyCode == 13) {
        $("#search").click();
    }
});

$("#ban").click(function () {
    var data = {
        username: $("#banusername").val(),
        type: $("#type").find(":selected").data('id'),
        expire:  parseInt($("#time").val()) * parseInt($("#format").find(":selected").data('time')),
        reason: $("#reason").val(),
        action: "banPlayer",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            if ($("tbody:last tr").length >= 25) {
                $("tbody:last tr:last").remove();
            }
            $("tbody:last tr").eq(0).after('<tr data-id="' + data.ban.id + '"><td>' + data.ban.id + '</td><td>' + data.ban.username + '</td><td>' + data.ban.type + '</td><td>' + data.ban.staff_username + '</td><td>' + data.ban.ban_expire + '</td><td>' + data.ban.ban_reason + '</td><td><center><div class="delete" data-id="' + data.ban.id + '"><i style="padding-top:4px;color:red" class="fa fa-trash"></i></div></center></td></tr>');
            $("#username").val('');
            $('#type option:first').prop('selected', true);
            $('#format option:first').prop('selected', true);
            $("#time").val('10');
            $("#reason").val('');
            $("#banfield .alert-success").show().empty().html('<strong>'+data.message+'</strong>').delay(10000).fadeOut("slow");
        }
        else {
            $("#banfield .alert-danger").show().empty().html('<strong>'+data.message+'</strong>').delay(10000).fadeOut("slow");
        }
    });
});

$('.row').on('click', '.page-link', function () {

    id = $(this).data('id');

    if (id == '+') {
        id = $('.page-item.active').data('id') +1;
    }
    else if (id == '-') {
        id = $('.page-item.active').data('id') -1;
    }

    if (id == 0 || id > $('.page-item').length - 2)
        return;

    $('.page-item.disabled').removeClass('disabled');
    $('.page-item.active').removeClass('active');
    $('.page-item[data-id='+ id +']').addClass('active');

    if (id == 1) {
        $('.page-item:first').addClass('disabled');
    }
    else if (id == $('.page-item').length - 2) {
        $('.page-item:last').addClass('disabled');
    }

    var data = {
        load: id - 1,
        action: "loadBans",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        html = '';
        for (i = 0; i < data.length; i++)
        {
            ban = data[i];
            html += '<tr data-id="' + ban.id + '"><td>' + ban.id + '</td><td>' + ban.username + '</td><td>' + ban.type + '</td><td>' + ban.staff_username + '</td><td>' + ban.ban_expire + '</td><td>' + ban.ban_reason + '</td><td><center><div class="delete" data-id="' + ban.id + '"><i style="padding-top:4px;color:red" class="fa fa-trash"></i></div></center></td></tr>';
        }
        $("tbody:last").find("tr:gt(0)").empty();
        $('tbody:last').append(html);
    });
});