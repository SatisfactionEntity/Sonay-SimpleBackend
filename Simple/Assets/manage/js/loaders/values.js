$('.items').on('click', '.fa-trash', function () {
    id = $(this).data('id');
    category_id = $(".col-md-8:last").data('id');
    var data = {
        id: id,
        category_id: category_id,
        action: "deleteValueItem",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $('.col-sm-3[data-id="' + id + '"]').fadeOut("normal", function() {
                $(this).remove();
                value = parseInt($('.list-group a[data-id="'+ category_id +'"] .badge').text()) -1;
                $('.list-group a[data-id="'+ category_id +'"] .badge').empty().text(value);
            });
        }
    });
});

$(".panel-heading:last").keyup(function (event) {
    id = $(".col-md-8:last").data('id');
    name = $(this).text();
    if (event.keyCode == 13) {
        // TODO block
    }
    else {
        delay(function () {
            var data = {
                id: id,
                name: name,
                action: "updateValuePage",
                controller: "Manage"
            };
            DataCall(data, function (data) {
                data = JSON.parse(data);
                if (data.valid) {
                        $('.panel-heading:last').animate({
                            backgroundColor: 'rgb(100, 189, 99)',
                            color: 'white',
                        });
                        $('.panel-heading:last').animate({
                            backgroundColor: 'white',
                            color: 'black',
                        });
                        $(".panel-body a[data-id='" + id + "'] .name").text(name);
                    }
                    else {
                        $('.panel-heading:last').animate({
                            backgroundColor: 'rgb(193, 43, 43);',
                            color: 'white',
                        });
                        $('.panel-heading:last').animate({
                            backgroundColor: 'white',
                            color: 'black',
                        });
                    }
            });
        }, 300);
    }
});

$('.col-md-8:last button:last').click(function () {
    id = $(".col-md-8:last").data('id');
    var data = {
        id: id,
        action: "deleteValuePage",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            $(".col-md-8:not(.col-md-8:first)").fadeOut('slow');
            $('.list-group a[data-id="'+ id +'"]').fadeOut("normal", function() {
                $(this).remove();
            });
            $('.col-md-8:last button:first').show();
            $("html, body").animate({
                scrollTop: 0
            }, "slow");
        }
    });
});

$('.col-md-8:last button:first').click(function () {
    $(this).hide();
    $('.col-md-8:eq(1)').fadeIn('normal');
    $("html, body").animate({
        scrollTop: 0
    }, "slow");
});

$('.col-md-8:eq(1) button:last').click(function () {
    $('.col-md-8:last button:first').show();
    $('.col-md-8:eq(1)').fadeOut('normal');
});

$('.col-md-8:eq(1) button:first').click(function () {
    if (inputscanner('.col-md-8:eq(1)'))
    {
        displayname = $('.col-md-8:eq(1) input:eq(0)').val();
        itemname = $('.col-md-8:eq(1) input:eq(1)').val();
        price = $('.col-md-8:eq(1) input:eq(2)').val();
        id = $(".col-md-8:last").data('id');
        var data = {
            displayname: displayname,
            itemname: itemname,
            price: price,
            id: id,
            action: "addValueItem",
            controller: "Manage"
        };
        DataCall(data, function (data) {
            data = JSON.parse(data);
            if (data.valid) {
                $(".col-md-8:eq(1) .alert-success").show().empty().html('<strong>' + data.message + '</strong>').delay(5000).fadeOut("slow");
                if (!$('.items .col-sm-3')[0]) {
                    $('.items').empty();
                }
                $('.items').append('<div style="cursor:pointer" class="col-sm-3" data-id="' + data.id + '"><div class="rare_head">» ' + displayname + '</div><div class="rare_body" style="background-image: url(' + SimpleCMS.furni + '/' + itemname.replace("*", '_') + '_icon.png);"><i data-id="' + data.id + '"  style="margin-right:4px;margin-top:4px;color:red;float:right" class="fa fa-trash"></i><div id="furni_overlay"></div><div id="furni_price">' + price + ' SS</div></div></div>');
                value = parseInt($('.list-group a[data-id="'+ id +'"] .badge').text()) +1;
                $('.list-group a[data-id="'+ id +'"] .badge').empty().text(value);
                $('.col-md-8:eq(1) input').val('');
            }
            else {
                $(".col-md-8:eq(1) .alert-danger").show().empty().html('<strong>' + data.message + '</strong>').delay(10000).fadeOut("slow");
            }
        });
    }
});

$('.col-md-8:first button').click(function () {
    if (inputscanner('.col-md-8:first')) {
        name = $('.col-md-8:first input').val();
        var data = {
            name: name,
            action: "addValuePage",
            controller: "Manage"
        };
        DataCall(data, function (data) {
            data = JSON.parse(data);
            if (data.valid) {
                $(".col-md-8:first .alert-success").show().empty().html('<strong>' + data.message + '</strong>').delay(5000).fadeOut("slow");
                $('.list-group').append('<a href="#" data-id="' + data.id + '" class="list-group-item ui-sortable-handle"><span class="name">' + name + '</span><span class="badge badge-default badge-pill">0</span></a>');
                $('.col-md-8:first input').val('');
            }
            else {
                $(".col-md-8:first .alert-danger").show().empty().html('<strong>' + data.message + '</strong>').delay(10000).fadeOut("slow");
            }
        });
    }
});

$('.list-group').on('click', 'a', function () {
    id = $(this).data("id");
    var data = {
        id: id,
        action: "viewValuePage",
        controller: "Manage"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        html =  '';
        if (data.values.length)
        {
            for (var i = 0; i < data.values.length; i++)
            {
                item = data.values[i];
                html += '<div style="cursor:pointer" class="col-sm-3" data-id="'+ item.id +'"><div class="rare_head">» '+ item.display_name +'</div><div class="rare_body" style="background-image: url('+ SimpleCMS.furni +'/'+ item.item_name +'_icon.png);"><i data-id="'+ item.id +'"  style="margin-right:4px;margin-top:4px;color:red;float:right" class="fa fa-trash"></i><div id="furni_overlay"></div><div id="furni_price">'+ item.price +' SS</div></div></div>';
            }
        }
        else {
            html += data.message;
        }

        $(".panel-heading:last").empty().append($(".list-group a[data-id='"+ id + "'] .name").text());
        $(".items").empty().append(html);
        $(".col-md-8:last").data('id', id).show();
    });
});

$(".list-group, .items").sortable();
$(".list-group, .items").disableSelection();

$('.list-group, .items').sortable({
    start: function(e, ui) {
        $(this).attr('current', ui.item.index());
    },
    update: function(e, ui) {
        updateFrom = $(this).attr('current');
        updateTo = ui.item.index();
        $(this).removeAttr('current');
        page = $(this).attr("class").split(' ')[0] == 'list-group';
        item = this;
        var data = {
            from: updateFrom,
            to: updateTo,
            action: "updateValue" + (page ? 'Page' : 'Item'),
            controller: "Manage"
        };
        if (!page) {
            data.category_id = $('.col-md-8:last').data('id');
        }
        DataCall(data, function (data) {
            data = JSON.parse(data);
            if (data.valid)
            {

            }
            else {
                $(item).sortable("cancel");
            }
        });
    }
});

var delay = (function () {
    var timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();