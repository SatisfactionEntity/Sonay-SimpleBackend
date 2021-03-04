if ($('.class-1').length)
    $('.class-1[data-id="1"]').css('font-weight', 'bold');

$('.col-sm-8').on('click', '.class-1', function () {
    loadreactions($(this).data('id'));
});

$('.col-sm-4').on('click', '#more', function () {
    id = $(this).data('id');
    var data = {
        action: "loadArticles",
        controller: "News",
        load: id
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        html = '<h2>Nieuws</h2><ul>';
        for (i = 0; i < data.news.length; i++)
        {
            article = data.news[i];
            html += '<li><a href="/news/'+ article.id +'/'+ article.slug +'">'+ article.title +' »</a></li>';
        }
        $('#article-archive').empty().append(html);
    });
    $("html, body").animate({
        scrollTop: 0
    }, "slow");
});

$('.sendcomment').click(function () {
    var data = {
        action: "addReaction",
        controller: "News",
        article: $('#title').data('id'),
        reaction: $('#give-comment').val()
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $('#give-comment').val('');
            $(".success").empty().show().append(data.message).delay(10000).fadeOut("slow");
        }
        else {
            $(".error").empty().show().append(data.message).delay(5000).fadeOut("slow");
        }
    });
});

function loadreactions(load) {
    var data = {
        action: "loadReactions",
        controller: "News",
        article: $('#title').data('id'),
        load: load - 1
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        html = '';
        for (var i = 0; i < data.reaction.length; i++) {
            reaction = data.reaction[i];
            html += '<div id="' + reaction.id + '" class="reaction ' + (i % 2 == 0 ? 'odd' : 'even') + '"><img class="duck" src="/Simple/Assets/img/duck_' + (reaction.online == "1" ? 'online' : 'offline') + '.gif"><div class="plate"><img src="'+ SimpleCMS.avatar +'?figure=' + reaction.look + '&direction=2&head_direction=3&action=wlk&gesture=sml" style="-webkit-filter: drop-shadow(0 1px 0 #FFFFFF) drop-shadow(0 -1px 0 #FFFFFF) drop-shadow(1px 0 0 #FFFFFF) drop-shadow(-1px 0 0 #FFFFFF);margin-top: -20px;margin-left: 25px;"></div><div class="message">' + reaction.message + '</div><div class="timestamp">'+ reaction.writtenby +'</div></div>';
        }
        $(".guestbook").empty().append(html);
        $('.class-1').css('font-weight', '');
        $('.class-1[data-id="' + load + '"]').css('font-weight', 'bold');
    });

}