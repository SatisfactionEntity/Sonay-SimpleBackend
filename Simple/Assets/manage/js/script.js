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

function inputscanner(identifier)
{
    valid = true;
    $( identifier + ' :input:visible[required="required"]').each(function() {
        if (!this.validity.valid) {
            $(this).focus();
            $(this).animate({
                backgroundColor: 'rgb(193, 43, 43);',
                color: 'white',
            });
            $(this).animate({
                backgroundColor: 'white',
                color: 'black',
            });
            valid = false;
            return false;
        }
    });
    return valid;
}

function inputtrigger(identifier, error)
{
    color = error ? 'rgb(193, 43, 43)' : 'rgb(100, 189, 99)';
    $(identifier).animate({
        backgroundColor: color,
        color: 'white',
    });
    $(identifier).animate({
        backgroundColor: 'white',
        color: 'black',
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