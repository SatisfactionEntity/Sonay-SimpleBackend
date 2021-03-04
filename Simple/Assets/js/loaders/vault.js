$(".col-sm-6 .btn.btn-success").click(function () {
    {
        var data = {
            code: $('.num1').val() + $('.num2').val() + $('.num3').val() + $('.num4').val(),
            action: "tryKey",
            controller: "Vault"
        };
        DataCall(data, function (data) {
            data = JSON.parse(data);
            if (data.amount) {
                $("#trys").empty().text(data.amount);
            }
            if (data.cracked) {
                $('#' + data.id + ' #cracked').empty().text(data.yes);
                $('#' + data.id + ' #username').empty().text(data.cracker);
                $(".success").show().empty().text(data.message).delay(25000).fadeOut("slow");
                for (i = 0; i < 10; i++) {
                    $('#' + data.id).animate({
                        backgroundColor: '#4cc94c!important',
                    });
                    $('#' + data.id).animate({
                        backgroundColor: (data.id % 2 == 0 ? '#f2f2f2' : '#ffffff') + '!important',
                    });
                }
            }
            else {
                $(".error").show().empty().text(data.message).delay(10000).fadeOut("slow");
            }
        });
    }
});