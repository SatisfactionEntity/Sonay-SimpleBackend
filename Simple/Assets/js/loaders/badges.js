$(document).ready(function () {
    ViewPage();
});

$('.mid').on('click', 'span', function () {
    ViewPage(this.id, $(this).data('title'));
});

$('.badges').on('click', '.btn-success, .btn-danger', function () {
    CartHandler($(this).data('id'), $(this).hasClass('btn-success') ? true : false);
});

$('#items').on('click', 'img', function () {
    CartHandler($(this).data('id'), false);
});

$(".buy").click(function () {
    BuyCart();
});

function BuyCart() {
    config = $('.badges').data('config').split('|');
    if (!$('#items img')[0]) {
        $(".error").show().empty().text(config[6]).delay(5000).fadeOut("slow");
    } else if (parseInt($('.total').text()) > parseInt($('.have').text())) {
        $(".error").show().empty().text(config[7]).delay(5000).fadeOut("slow");
    } else {
        badges = '';
        $('#items img').each(function (i) {
            if (i > 0) {
                badges += ';';
            }
            badges += $(this).data('id');
        });
        var data = {
            action: "buyBadges",
            controller: "Shop",
            badges: badges
        };
        DataCall(data, function (data) {
            data = JSON.parse(data);
            if (data.valid) {
                have = parseInt($('.have').text()) - parseInt($('.total').text());
                $(".have").empty().text(have);
                $('.price').empty().text(0);
                $('.discount').empty().text(0);
                $('.total').empty().text(0);
                $(".success").show().empty().text(data.message).delay(10000).fadeOut("slow");
                $('.badges .btn-danger').empty().append(config[5]).toggleClass('btn-danger btn-warning');
                $('#items img').fadeOut(500, function () {
                    $('#items img').remove();
                });
            }
            else {
                $(".error").show().empty().text(data.message).delay(5000).fadeOut("slow");
            }
        });
    }
}

function CartHandler(id, add) {
    config = $('.badges').data('config').split('|');
    if (add) {
        if ($('#items').find('li:empty:first')[0]) {
            price = $('.badges [data-id="' + id + '"]').data('price');
            totalprice = parseInt($('.price').text()) + parseInt(price);
            badge = $('.badges [data-id="' + id + '"]').data('badge');
            $('#items').find('li:empty:first').append('<img data-id="' + id + '" data-badge="' + badge + '" data-price="' + price + '" style="position:static" src="' + config[0] + '/' + badge + '.gif">');
            $('.add[data-id="' + id + '"]').empty().append(config[4]).toggleClass('btn-success btn-danger');
        } else {
            $(".error").show().empty().text(config[8]).delay(5000).fadeOut("slow");
            return;
        }
    } else {
        price = $('#items [data-id="' + id + '"]').data('price');
        totalprice = parseInt($('.price').text()) - parseInt(price);
        $('#items img[data-id="' + id + '"]').remove();
        $('.add[data-id="' + id + '"]').empty().append(config[3]).toggleClass('btn-danger btn-success');
        $('#items img').each(function (i) {
            $(this).remove();
            $('#items').find('li:empty:first').append('<img data-id="' + $(this).data('id') + '" data-badge="' + $(this).data('badge') + '" data-price="' + $(this).data('price') + '" style="position:static" src="' + config[0] + '/' + $(this).data('badge') + '.gif">');
        });
    }

    $('.price').empty().append(totalprice);

    discount = ($("#items img").length * config[1]) - config[1];
    $('.discount').empty().append(discount > config[2] ? config[2] : discount < 0 ? 0 : discount);
    $('.total').empty().append(Math.round((parseInt($('.price').text()) / 100) * (100 - parseInt($('.discount').text()))));
}

function ViewPage(id, title) {
    id = id ? id : 0;
    title = title ? title : '';
    var data = {
        action: "getBadgePage",
        controller: "Shop",
        id: id
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.pages) { // id = 0
            html = '';
            for (var i = 0; i < data.pages.length; i++) {
                page = data.pages[i];
                html += '<span data-title="' + page.title + '" id="' + page.id + '"><p' + (i + 1 == data.pages.length ? ' class="last"' : '') + '>' + page.name + '</p></span>';
                if (page.default == "1") {
                    id = page.id;
                    title = page.title;
                }
            }
            $('.badges').data('config', data.config);
            $('.mid').empty().append(html);
        }

        config = $('.badges').data('config').split('|');

        $('.mid span').removeClass('selected');
        $('.mid #' + id).addClass('selected');

        html = '';
        if (data.badges.length) {
            html = '<h2>' + title + '</h2>';
            for (var i = 0; i < data.badges.length; i++) {
                badge = data.badges[i];
                html += '<hr><div data-id="' + badge.id + '" data-badge="' + badge.badge + '" data-price="' + badge.price + '" class="badgeRow row"><div class="col-md-2"><img src="' + config[0] + '/' + badge.badge + '.gif"></div><div class="col-md-5">' + badge.desc + '</div><div class="col-md-2">' + badge.price + ' <img src="/Simple/Assets/img/5.gif"></div><div class="col-md-3"><button class="btn ' + (badge.user_id ? 'btn-warning' : ($('#items img[data-id="' + badge.id + '"]')[0] ? 'btn-danger' : 'btn-success')) + ' add" data-id="' + badge.id + '" style="width:100%">' + (badge.user_id ? config[5] : ($('#items img[data-id="' + badge.id + '"]')[0] ? config[4] : config[3])) + '</button></div></div>';
            }
        }
        $(".badges").empty().append(html);
    });
}