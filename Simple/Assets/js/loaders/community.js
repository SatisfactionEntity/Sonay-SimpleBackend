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