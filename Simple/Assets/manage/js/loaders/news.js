CKEDITOR.replace('longstory');

$('.row').on('click', 'select', function () {
    $('#image').attr('src', '/Simple/Assets/manage/img/newsimages/' + $(this).find('option:selected').text());
    $('#image').data('image', $(this).find('option:selected').text());
});

$('.row').on('click', '.edit', function () {
    id = $(this).data('id');
    var data = {
        id: id,
        action: "getNews",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid)
        {
            $(".panel-heading div").eq(0).hide();
            $(".panel-heading div").eq(1).show();
            $("#buttons button").eq(0).hide();
            $("#buttons button").eq(1).show();
            $("#buttons button").eq(2).show();
            $("#title").val(data.news.title);
            $("#shortstory").val(data.news.shortstory);
            $("#reactions input[type='radio']").eq((data.news.comments == '1' ? '0' : '1')).prop("checked",true);
            CKEDITOR.instances['longstory'].setData(data.news.longstory);
            $('#image').attr('src', '/Simple/Assets/manage/img/newsimages/'+ data.news.image);
            $('#image').data('image', data.news.image);
            $('#update').data('id', data.news.id);
            $("html, body").animate({
                scrollTop: 0
            }, "slow");
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
        action: "loadNewsList",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        html = '';
        for (i = 0; i < data.length; i++)
        {
            article = data[i];
            html += '<tr data-id="' + article.id + '"><td>' + article.id + '</td><td>' + article.title + '</td><td>' + article.shortstory + '</td><td>' + article.username + '</td><td>' + article.published + '</td><td><center><div class="edit" data-id="' + article.id + '"><i style="padding-top:5px;color:green" class="fa fa-edit"></i></div></center></td><td><center><div class="delete" data-id="' + article.id + '"><i style="padding-top:4px;color:red" class="fa fa-trash"></i></div></center></td></tr>';
        }
        $("tbody").find("tr:gt(0)").empty();
        $('tbody').append(html);
    });
});

$('.row').on('click', '.delete', function () {
    id = $(this).data('id');
    var data = {
        id: id,
        action: "deleteNews",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $('tbody tr[data-id="'+ id +'"]').fadeOut('slow');
            if ($("#update").data("id") == id) {
                clearnews(true);
            }
        }
    });
});

function clearnews(update) {
    if (update) {
        $(".panel-heading div").eq(0).show();
        $(".panel-heading div").eq(1).hide();
        $("#buttons button").eq(0).show();
        $("#buttons button").eq(1).hide();
        $("#buttons button").eq(2).hide();
    }
    $("#title").val('');
    $("#shortstory").val('');
    $("#reactions input[type='radio']:first").prop("checked",true);
    CKEDITOR.instances['longstory'].setData('');
    $('#image').attr('src', '/Simple/Assets/manage/img/newsimages/choose.gif');
    $('#image').data('image', 'choose.gif');
}

function placenews(update)
{
    var data = {
        title: $("#title").val(),
        shortstory: $("#shortstory").val(),
        comments: $("#reactions input[type='radio']:checked").val(),
        image: $("#image").data('image'),
        longstory: CKEDITOR.instances['longstory'].getData(),
        action: "addNews",
        controller: "Manage"
    };
    if (update) {
        data.update = update;
    }
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            if (update) {
                $('tbody tr[data-id="'+ update +'"] td').eq(1).empty().text($("#title").val());
                $('tbody tr[data-id="'+ update +'"] td').eq(2).empty().text($("#shortstory").val());
            }
            else {
                if ($("tbody tr").length >= 25) {
                    $("tbody tr:last").remove();
                }
                $("tbody tr").eq(0).after('<tr data-id="' + data.news.id + '"><td>' + data.news.id + '</td><td>' + data.news.title + '</td><td>' + data.news.shortstory + '</td><td>' + data.news.username + '</td><td>' + data.news.published + '</td><td><center><div class="edit" data-id="' + data.news.id + '"><i style="padding-top:5px;color:green" class="fa fa-edit"></i></div></center></td><td><center><div class="delete" data-id="' + data.news.id + '"><i style="padding-top:4px;color:red" class="fa fa-trash"></i></div></center></td></tr>');
            }
            clearnews(update);
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

$("#place").click(function () {
    placenews();
});

$("#update").click(function () {
    placenews($(this).data('id'));
});

$("#cancel").click(function () {
    clearnews(true);
    $("html, body").animate({
        scrollTop: 0
    }, "slow");
});