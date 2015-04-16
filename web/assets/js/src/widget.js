(function ($) {

    $(window).on("load resize",function(){ 
        var widgetWidth = $(".es-widget-1").outerWidth();
        var colLeftWingWidth = $(".es-widget-1 .col-left-wing").outerWidth();
        var colRightWingWidth = widgetWidth - colLeftWingWidth;

        $(".es-widget-1 .col-right-wing").css("width", colRightWingWidth+"px");

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

    $(document).on('click',".widget-link-textbox",function () {
        $(this).select();
        alert("Right click and copy or press ctrl + c");
    });
})(jQuery);
