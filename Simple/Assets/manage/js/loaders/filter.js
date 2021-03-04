$('.row').on('click', '.delete', function () {
    var id = $(this).data('id');
    var data = {
        id: id,
        action: "removeipfromfilter",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $('tbody tr[data-id="'+ id +'"]').fadeOut('slow');
        }
    });
});

$(".btn-success").click(function () {

    var username = $("#filterusername").val();
    var ip = $("#filterip").val();

    var data = {
        username: username,
        ip: ip,
        action: "addiptofilter",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $("tbody:last tr").eq(0).after('<tr data-id="' + data.id + '"><td>' + ip + '</td><td>' + username + '</td><td>' + data.added_by + '</td><td><center><div class="delete" data-id="' + data.id + '"><i style="padding-top:4px;color:red" class="fa fa-trash"></i></div></center></td></tr>');
            $("#filterusername").val('');
            $("#filterip").val('');
            $("#filterfield .alert-success").show().empty().html('<strong>'+data.message+'</strong>').delay(10000).fadeOut("slow");
        }
        else {
            $("#filterfield .alert-danger").show().empty().html('<strong>'+data.message+'</strong>').delay(10000).fadeOut("slow");
        }
    });
});