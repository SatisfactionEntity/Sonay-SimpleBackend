$(document).ready(function () {
    ViewPage($('.box .options ul li:first').data('id'));
});

$(".box .options ul li").click(function () {
    ViewPage($(this).data('id'));
});

function ViewPage(id) {
    var data = {
        action: "viewValuePage",
        controller: "Shop",
        id: id
    };
    DataCall(data, function (data) {

        $('.options li').css('font-weight', '');
        $('.options li[data-id="'+ id +'"]').css('font-weight', 'bolder');

        data = JSON.parse(data);
        html =  '';
        if (data.values.length)
        {
            for (var i = 0; i < data.values.length; i++)
            {
                item = data.values[i];
                html += '<div class="col-sm-4"><div class="rare_head">» '+ item.display_name +'</div><div class="rare_body" style="background-image: url('+ data.furniiconmap +'/'+ item.item_name +'_icon.png);"><div id="furni_overlay"></div><div id="furni_price">'+ item.price +' SS</div></div></div>';
            }
        }
        else {
            html += '<div class="col-sm-11"><div class="box"><h2 class="red">'+ $('.options li#'+id).text() +'</h2><div class="inner">'+ data.message;
        }
        $(".col-sm-9").empty().append(html);
        $("html, body").animate({
            scrollTop: 0
        }, "slow");
    });
}