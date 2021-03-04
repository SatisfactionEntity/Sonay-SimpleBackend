$('.row').on('click', '.delete', function () {
    id = $(this).data('id');
    var data = {
        id: id,
        action: "deleteWord",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $('tbody tr[data-id="'+ id +'"]').fadeOut('slow');
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
        action: "loadWordList",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        html = '';
        for (i = 0; i < data.length; i++)
        {
            word = data[i];
            html += '<tr data-id="' + word.id + '"><td>' + word.id + '</td><td>' + word.key + '</td><td>' + word.replacement + '</td><td>' + word.hide + '</td><td>' + word.username + '</td><td><center><div class="delete" data-id="' + word.id + '"><i style="padding-top:4px;color:red" class="fa fa-trash"></i></div></center></td></tr>';
        }
        $("tbody").find("tr:gt(0)").empty();
        $('tbody').append(html);
    });
});

function addword()
{
    var data = {
        word: $("#word").val(),
        replacement: $("#replacement").val(),
        hide: $("#hide input[type='radio']:checked").val(),
        action: "addWord",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            if ($("tbody tr").length >= 25) {
                $("tbody tr:last").remove();
            }
            $("tbody tr").eq(0).after('<tr data-id="' + data.word.id + '"><td>' + data.word.id + '</td><td>' + data.word.key + '</td><td>' + data.word.replacement + '</td><td>' + data.word.hide + '</td><td>' + data.word.username + '</td><td><center><div class="delete" data-id="' + data.word.id + '"><i style="padding-top:4px;color:red" class="fa fa-trash"></i></div></center></td></tr>');
            $("#word").val('');
            $("#replacement").val('');
            $("#hide input[type='radio']:first").prop("checked",true);
            $(".alert-success").show().empty().html('<strong>'+data.message+'</strong>').delay(5000).fadeOut("slow");
        }
        else {
            $(".alert-danger").show().empty().html('<strong>'+data.message+'</strong>').delay(10000).fadeOut("slow");
        }
        $("html, body").animate({
            scrollTop: 0
        }, "slow");
    });
}

$("#add").click(function () {
    addword();
});