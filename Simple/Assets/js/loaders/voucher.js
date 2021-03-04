$("#claim").click(function () {
    ClaimVoucher($('.lock').val());
});

$("#finish").click(function () {
    MakeVoucher();
});

$(".lock").keyup(function (event) {
    if (event.keyCode == 13) {
        $("#claim").click();
    }
});

function MakeVoucher()
{
    string = '';
    $('#create .form-control').each(function() {
        if ($(this).val() > 0) {
            if (string) {
                string += ';';
            }
            string += $(this).data('id')+':'+$(this).val();
        }
    });
    var data = {
        action: "makeVoucher",
        controller: "Shop",
        data: string
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.texts) {

            $("html, body").animate({
                scrollTop: 0
            }, "slow");

            texts = data.texts.split('|');
            $('body').append('<div id="overlay"></div><div class="box-overlay"><h2 class="orange">'+ texts[0] +'</h2><p style="padding:20px">'+ texts[1] +'</p><div class="box showitems"><ul id="items"><li></li><li></li></ul><div style="clear:both"></div></div><p><button style="margin-left:5px;float:right;" onclick="stop();" class="btn btn-danger">'+ texts[2] +'</button></p></div>');

            content = string.split(';');
            for (var i = 0; i < content.length; i++) {
                parms = content[i].split(':');
                $('#items li').eq(i).html('<img src="/Simple/Assets/img/' + parms[0] + '.gif"><i class="times">' + parms[1] + '</i>');
            }
        }
        else {
            $("#err2").show().empty().text(data.message).delay(5000).fadeOut("slow");
        }
    });
}
function ClaimVoucher(code) {
    $('.lock').val('');
    var data = {
        action: "claimVoucher",
        controller: "Shop",
        code: code
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.rewards)
        {
            $("html, body").animate({
                scrollTop: 0
            }, "slow");

            config = data.config.split('|');
            texts = data.texts.split('|');

            $('body').append('<div id="overlay"></div><div class="box-overlay"><h2 class="orange">'+ texts[0] +'</h2><p style="padding:20px">'+ texts[1] +'</p><div class="box showitems"><ul id="items"><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li></ul><div style="clear:both"></div></div><p><button style="margin-left:5px;float:right;" onclick="stop();" class="btn btn-danger">'+ texts[2] +'</button></p></div>');

            content = data.rewards.split(';');
            for (var i = 0; i < content.length; i++) {
                parms = content[i].split(':');
                if (parms[2])
                {
                    $('#items li').eq(i).html('<img src="'+ config[0] +'/' + parms[0] + '_icon.png"><i class="times">' + parms[2] + 'x</i>');
                }
                else if (parms[1])
                {
                    $('#items li').eq(i).html('<img src="/Simple/Assets/img/' + parms[0] + '.gif"><i class="times">' + parms[1] + '</i>');
                }
                else
                {
                    $('#items li').eq(i).html('<img style="position:static" src="'+ config[1] +'/' + parms[0] + '.gif">');
                }
            }
        }
        else
        {
            $("#err1").show().empty().text(data.message).delay(5000).fadeOut("slow");
        }
    });
}
