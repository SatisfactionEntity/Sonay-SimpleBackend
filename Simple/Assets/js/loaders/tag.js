$('.row').on('click', '.tag-remove-link', function () {
    taghandler('deleteTag', $(this).data('name'));
});

$("#addtag").click(function () {
    taghandler('addTag', $(".tag-name").val())
});

$("#tagadd button").click(function(){
    taghandler('addTag', $(this).text())
});

$(".tag-name").keyup(function (event) {
    if (event.keyCode == 13) {
        $("#addtag").click();
    }
});

$("#search").keyup(function (event) {
    if (event.keyCode == 13) {
        $(".input-group-btn .searchTag").click();
    }
});

$(".input-group-btn .searchTag").click(function(){
    search = $("#search").val();
    if(search != "") {
        window.location = "/tag/" + search;
    }
});

function taghandler(type, tag) {
    var data = {
        action: type,
        controller: "Tags",
        tag: tag
    };
    DataCall(data, function (data) {
        data = JSON.parse(data);
        if (data.valid) {
            if ($("#search").data('current') == tag) {
                location.reload();
            }
            else if (type == 'addTag') {
                $(".tag-list.make-clickable").append('<li class="tag" data-identifier="' + tag + '"><a class="tag" style="font-size: 10px;">' + tag.toLowerCase() + '</a>' + '<a class="tag-remove-link" data-name="' + tag + '"></a></li>');
                $(".tag-name").val('');
                $(".taghelp").empty().append('<center><em>' + data.message + '</em></center>');
                if ($("li.tag").length >= SimpleCMS.maxtags) {
                    $(".panel-body.panel-mytags div").eq(1).hide();
                    $(".panel-body.panel-mytags div").eq(2).show();
                    $("#addtagpart").hide();
                }
            }
            else {
                $('.tag-list.make-clickable [data-identifier="' + tag + '"]').remove();
                if ($("li.tag").length < SimpleCMS.maxtags && $("#addtagpart").css('display') == 'none') {
                    $(".panel-body.panel-mytags div").eq(1).show();
                    $(".panel-body.panel-mytags div").eq(2).hide();
                    $("#addtagpart").show();
                }
            }
        }
        else {
            $('.TagError').show().text(data.error).delay(5000).fadeOut("slow");
        }
    });
}