var clipboard = new Clipboard('.btn');

$(".btn-success").click(function () {
    ClaimReferralReward($(this).data('id'), $(this).data('present'));
});

$('body').on('click', '#handle', function () {
    stop();
    $('#packages tr[data-id="' + $(this).data('id') + '"]').fadeOut("normal", function() {
        $('#packages tr[data-id="' + $(this).data('id') + '"]').remove();
        $('#packages .button:hidden:first').show();
        $('#packages tr:hidden:first').fadeIn( "normal");
    });
});

function ClaimReferralReward(id, present) {
    var data = {
        action: "claimReferralReward",
        controller: "Shop"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.rewards)
        {
            $("html, body").animate({
                scrollTop: 50
            }, "slow");

            config = data.config.split('|');
            texts = data.texts.split('|');

            $('body').append('<div id="overlay"></div><div class="box-overlay"><h2 class="orange">'+ texts[0] +'</h2><p style="padding:20px">'+ texts[1] +'</p><div class="box showitems"><div class="col-md-8"><ul id="items"><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li></ul></div><div class="col-md-4 present_info"> <p>'+ texts[2] +'</p><div class="present"><img style="margin-top:10px" src="/Simple/Assets/img/'+ present +'"></div></div><div style="clear:both"></div></div><p><button data-id="'+ id +'" style="margin-left:5px;float: right;" id="handle" class="btn btn-danger">Sluiten</button></p></div>');

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
        else {
            $(".error").show().empty().text(data.message).delay(10000).fadeOut("slow");
        }
    });
}
