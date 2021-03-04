$(".top_item").each(function(i) {
    setTimeout(function() {
        $(".loading").eq(i).hide();
        $(".stats").eq(i).fadeIn('slow');
    }, 2000 / (Math.random()+1));
});