getGiftData();


if ($('#news').length)
{
    $(".news").mouseover(function () {
        $(".newscontainer .top").fadeIn('slow');
    });
    $(".news").mouseleave(function () {
        $(".newscontainer .top").fadeOut('slow');
    });

    amount = $("#news div").length;
    currentarticle = -1;
    articles = {};

    for (i = 0; i < amount; i++)
    {
        articles[i] = $("#news div").eq(i).data();
    }

    function shownews(first)
    {
        currentarticle++;

        if (currentarticle >= amount) {
            currentarticle = 0;
        }

        if (first)
        {
            $(".newscontainer").css("background", "url(/Simple/Assets/manage/img/newsimages/" + articles[currentarticle].image + ')');
            update();
        }
        else
        {
            $(".newscontainer").fadeOut("slow", function () {
                $(this).css("background", 'url(/Simple/Assets/manage/img/newsimages/' + articles[currentarticle].image + ')').fadeIn('slow');
                update();
            });
        }

        function update()
        {
            $("#title").empty().text(articles[currentarticle].title);
            $("#summary").empty().text(articles[currentarticle].summary);
            $(".readmore").attr('href', '/news/'+articles[currentarticle].href);
            setTimeout(shownews, 5000)
        }
    }

    shownews(true);

    $(".top p").click(function()
    {
        clicked = $(this).attr("class");

        if (clicked == 'top-left')
        {
            if (currentarticle <= 0) {
                currentarticle = amount-1;
            }
            else {
                currentarticle--;
            }
        }
        else
        {
            if (currentarticle >= amount-1) {
                currentarticle = 0;
            }
            else {
                currentarticle++;
            }
        }

        $(".newscontainer").css("background", "url(/Simple/Assets/manage/img/newsimages/" + articles[currentarticle].image + ')');
        $("#title").empty().text(articles[currentarticle].title);
        $("#summary").empty().text(articles[currentarticle].summary);
        $(".readmore").attr('href', '/news/'+articles[currentarticle].href);

    });
}

$('.head.first').mouseover(function () {
    $("[data-id='" + $(this).data('id') + "'].first").hide();
    $("[data-id='" + $(this).data('id') + "'].second").show();
    if ($("[data-id='" + $(this).data('id') + "'].second").css('background-image') == 'none') {
        $("[data-id='" + $(this).data('id') + "'].second").css('background-image', 'url('+ SimpleCMS.avatar +'?figure='+ $("[data-id='" + $(this).data('id') + "'].first").data('look') +'&direction=3&head_direction=3&gesture=sml&action=wav)');
    }
});

$('#diamonds').click(function () {
    $(".line.left[data-type='5'] .part .first").each(function() {
        if ($(this).css('background-image') == 'none') {
            $(this).css('background-image', 'url('+ SimpleCMS.avatar +'?figure='+ $(this).data('look') +'&direction=3&head_direction=3&gesture=sml)');
        }
    });
});

$('.head.second').mouseleave(function () {
    $("[data-id='" + $(this).data('id') + "'].second").hide();
    $("[data-id='" + $(this).data('id') + "'].first").show();
});

$("#addtag").click(function () {
    taghandler('addTag', $(".tag-name").val())
});

$('.row').on('click', '.tag-remove-link', function () {
    taghandler('deleteTag', $(this).data('name'));
});

$(".tag-name").keyup(function (event) {
    if (event.keyCode == 13) {
        $("#addtag").click();
    }
});

function taghandler(type, tag) {
    var data = {
        action: type,
        controller: "Tags",
        tag: tag
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            if (type == 'addTag') {
                $(".tag-list.make-clickable").append('<li class="tag" data-identifier="' + tag + '"><a class="tag" style="font-size: 10px;">' + tag.toLowerCase() + '</a>' + '<a class="tag-remove-link" data-name="' + tag + '"></a></li>');
                $(".tag-name").val('');
                $(".taghelp").empty().append('<center><em>' + data.message + '</em></center>');
                if ($("li.tag").length >= SimpleCMS.maxtags) {
                    $(".panel-body.panel-mytags div").eq(1).hide();
                    $(".panel-body.panel-mytags div").eq(2).show();
                    $("#addtagpart").hide();
                }
            }
            else {
                $('.tag-list.make-clickable [data-identifier="' + tag + '"]').remove();
                if ($("li.tag").length < SimpleCMS.maxtags && $("#addtagpart").css('display') == 'none') {
                    $(".panel-body.panel-mytags div").eq(1).show();
                    $(".panel-body.panel-mytags div").eq(2).hide();
                    $("#addtagpart").show();
                }
            }
        }
        else {
            $('.TagError').show().text(data.error).delay(5000).fadeOut("slow");
        }
    });
}