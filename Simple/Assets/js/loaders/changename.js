
$('.col-sm-8').on('keyup', '.password', function (event) {
    if (event.keyCode == 13) {
        $(".col-sm-8 #changename").click();
    }
});

$('.col-sm-8').on('click', '#changename', function () {
    var data = {
        username: $("#username").val(),
        password: $("#password").val(),
        action: "changeName",
        controller: "Shop"
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            diamonds = parseInt($(".col-sm-4 .diamonds").text()) - parseInt($(".message b").text());
            $(".col-sm-4 .diamonds").empty().append(diamonds);
            $(".success").show().empty().append(data.message).delay(6000).fadeOut("slow");
        }
        else {
            $(".error").show().empty().append(data.message).delay(3000).fadeOut("slow");
        }
    });
});