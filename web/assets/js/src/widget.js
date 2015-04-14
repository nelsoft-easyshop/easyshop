(function ($) {

    $(window).on("load resize",function(){ 
        var widgetWidth = $(".es-widget-1").outerWidth();
        if(widgetWidth == 150){
            $(".es-widget-1 .item:nth-child(odd)").css("display", "none");
            $(".es-widget-1 .right-wing").css("padding-left", "32px").css("text-align", "center");
            $(".es-widget-1 .container-title").css("font-size", "10px");
        }
        else if(widgetWidth == 250){
            $(".es-widget-1 .item:nth-child(odd)").removeAttr("style");
            $(".es-widget-1 .right-wing").css("padding-left", "32px").removeAttr("style");
            $(".es-widget-1 .container-title").css("font-size", "10px").removeAttr("style");
        }
    });

})(jQuery);
