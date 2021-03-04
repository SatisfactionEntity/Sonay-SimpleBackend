function DataCall(vars, callback) {
    $.ajax({
        type: "POST",
        url: "/Data/DataController",
        data: vars,
        success: function (data) {
            callback(data);
        }
    });
}

function logout() {
    DataCall({
        action: "Logout",
        controller: "Me"
    }, function (data) {
        if (data == "success") {
            window.location.replace("/");
        }
    });
};

function stop() {
    $("#overlay").remove();
    $(".box-overlay").remove();
}

function tab(showid, hideid, showtab, hidetab) {
    $j("." + hidetab).hide();
    $j('.' + showtab).show();
    $j("#" + hideid).removeClass('tab').removeClass('selected').addClass('tab').addClass('can');
    $j("#" + showid).removeClass('tab').removeClass('can').addClass('tab').addClass('selected');
}

$(document).ready(function () {
    var i = 0;
    $('.menu-button .fa').click(function () {
        if (i == 0) {
            i = 1;
            $(".left").slideDown("slow");
            $(".right").slideDown("slow");
            $(".menu-button .fa.fa-bars").removeClass("fa-bars").addClass("fa-times");
        } else {
            i = 0;
            $(".left").slideUp("slow");
            $(".right").slideUp("slow");
            $(".menu-button .fa.fa-times").removeClass("fa-times").addClass("fa-bars");
        }
    });
    $('.wrap .logo').click(function () {
        window.location.href = '/';
    });
    $('h2 ul li:first-child').addClass("active");
    $('h2 ul li').click(function () {
        $("h2 ul ." + $(this).data('boxid')).removeClass("active");
        $(this).addClass("active");
        $("div." + $(this).data('boxid')).hide();
        $("div." + $(this).data('boxid') + "." + this.id).show();
    });
    $(window).scroll(function () {
        var scrollVal = $(this).scrollTop();
        if (scrollVal > 178) {
            $('.wrap').height(60).css({
                'position': 'fixed',
                'top': '0px'
            });
            $("nav").css({
                'top': '-15px'
            });
        } else {
            $('.wrap').css({
                'position': 'relative',
                'top': '91px'
            });
            $("nav").css({
                'top': '0px'
            });
        }
    });
    $(window).resize(function () {
        var wide = $(window).width();
        if (wide >= 795) {
            $(".left").show();
            $(".right").show();
        }
    });
});

function closeGiftData(overlay) {
    
    $("#overlay").remove();
    if ($('.open-gift-overlay').length) {
        $(".open-gift-overlay").remove();
    }
    else {
        $(".gift-overlay").remove();
    }
}

function getGiftData(clicked) {
    DataCall({
        action: "getGiftData",
        controller: "Me",
        clicked: clicked ? 'yes' : 'no'
    }, function (data) {
        data = $.parseJSON(data);
        if (clicked || data.presents > 0) {
            texts = data.texts.split('|');
            $('body').append('<div id="overlay"></div><div class="gift-overlay"><div class="gift-top"><p class="title">' + texts[0] + '</p><div onclick="closeGiftData()" class="close_button"></div></div><div class="gift-mid"><p class="gift-text" style="font-size:11px;">' + texts[1] + '</p><i class="gift-bottom-text">' + texts[2] + '</i></div><div class="gift-buttons">' + (!texts[3] ? '' : '<button onclick="claimGift()" class="gift-green-button">' + texts[3] + '</button>') + '<button onclick="closeGiftData()" class="gift-white-button">' + texts[4] + '</button></div></div>');
        }
    });
}

function claimGift(again) {
    DataCall({
        action: "claimGift",
        controller: "Me"
    }, function (data) {
        data = $.parseJSON(data);
        if (data.reward) {
            texts = data.texts.split('|');
            closeGiftData();
            $('body').append('<div id="overlay"></div><div class="open-gift-overlay"><div class="gift-top"><p class="title">' + texts[0] + '</p><div onclick="closeGiftData()" class="close_button"></div></div><div class="open-gift-mid"><img class="diamond" src="/Simple/Assets/img/present/diamonds.png"><p>' + texts[1] + '</p><button onclick="closeGiftData()" class="open-gift-white-button">' + texts[2] + '</button></div><button ' + (data.presentsleft == 0 ? 'class="gift-big-red-button' : 'onclick="claimGift(true)" class="gift-big-green-button') + '">' + texts[3] + '</button></div>');
            value = parseInt($(".my-currency .diamonds span:last-child").text()) + data.reward;
            $(".my-currency .diamonds span:last-child").empty().append(value);
        }
    });
}

function enterroom(id) {
    DataCall({
        action: "enterRoom",
        controller: "Game",
        id: id
    }, function (data) {
        data = $.parseJSON(data);
        if (data.online == "1") {
            alert(data.message);
        }
        else {
            window.open("/game");
        }
    });
}

function discord() {
  windowObjectReference = window.open(
    "https://discord.gg/H8TFPrg");
}




