if ($(".present:first")[0]) {
    ViewPackageContent($(".present:first").attr('id'));
}

$(".show").click(function () {
    ViewPackageContent(this.id);
});

$(".buy").click(function () {
    BuyPacket(this.id);
});

function ViewPackageContent(id) {

    $('.arrow').remove();
    $('.present#' + id).append('<div class="arrow">');

    $('#items li').empty();

    content = $('.present#' + id).data('content').split(';');
    image = $('.present#' + id).data('image');
    currency_type = $('.present#' + id).data('currency_type');
    price = $('.present#' + id).data('price');
    mycurrency = $('.present_info .have').data(currency_type.toString());

    $('.present_info .price').empty().append('<b id="price">' + price + ' </b> <img src="/Simple/Assets/img/' + currency_type + '.gif">');
    $('.present_info .have').empty().append('<b id="mycurrency">' + mycurrency + '</b> <img src="/Simple/Assets/img/' + currency_type + '.gif">');
    $('.present_info .present').empty().append('<img style="margin-top:10px" src="/Simple/Assets/img/' + image + '">');

    $('.buy').attr('id', id);

    for (var i = 0; i < content.length; i++) {
        parameters = content[i].split(':');
        if (parameters[1]) {
            $('#items li').eq(i).html('<img src="' + SimpleCMS.icon + '/' + parameters[0] + '_icon.png"><i class="times">' + parameters[2] + 'x</i>');
        } else {
            $('#items li').eq(i).html('<img style="position:static" src="' + SimpleCMS.badge + '/' + content[i] + '.gif">');
        }
    }
}

function BuyPacket(id) {
    var data = {
        action: "buyPackage",
        controller: "Shop",
        id: id
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            newvalue = parseInt($('#mycurrency').text()) - parseInt($('#price').text());
            $("#mycurrency").empty().text(newvalue);
            $(".success").show().empty().text(data.message).delay(10000).fadeOut("slow");
        } else {
            $(".error").show().empty().text(data.message).delay(10000).fadeOut("slow");
        }
    });
}
