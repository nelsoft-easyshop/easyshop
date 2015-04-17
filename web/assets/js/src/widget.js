(function ($) {

    $(window).on("load resize",function(){ 
        var widgetWidth = $(".es-widget-1").outerWidth();
        var colLeftWingWidth = $(".es-widget-1 .col-left-wing").outerWidth();
        var colRightWingWidth = widgetWidth - colLeftWingWidth;

        var widgetWidth2 = $(".es-widget-2").outerWidth();

        $(".es-widget-1 .col-right-wing").css("width", colRightWingWidth+"px");

        if(widgetWidth <= 249){
            $(".es-widget-1 .item:nth-child(2)").css("display", "none");
            $(".es-widget-1 .item:nth-child(3)").css("display", "none");
            $(".es-widget-1 .right-wing").css("padding-left", "12px").css("text-align", "center");
            $(".es-widget-1 .search-form").css("margin-right", "0px");
            $(".es-widget-1 .see-all-container").css("margin-top", "16px");
            $(".es-widget-1 .container-title").css("font-size", "10px");
        }
        else if(widgetWidth >= 250){
            $(".es-widget-1 .item:nth-child(2)").removeAttr("style");
            $(".es-widget-1 .item:nth-child(3)").removeAttr("style");
            $(".es-widget-1 .right-wing").css("padding-left", "32px").removeAttr("style");
            $(".es-widget-1 .search-form").css("margin-right", "10px").removeAttr("style");
            $(".es-widget-1 .see-all-container").css("margin-top", "0px").removeAttr("style");
            $(".es-widget-1 .container-title").css("font-size", "10px").removeAttr("style");
        }

        if(widgetWidth2 <= 205){
            $(".es-widget-2 .item:nth-child(4)").css("display", "none");
            $(".es-widget-2 .item:nth-child(5)").css("display", "none");
        }
        else if(widgetWidth2 <= 125){
            $(".es-widget-2 .item:nth-child(2)").css("display", "none");
            $(".es-widget-2 .item:nth-child(3)").css("display", "none");
        }
        else{
            $(".es-widget-2 .item:nth-child(2)").removeAttr("style");
            $(".es-widget-2 .item:nth-child(3)").removeAttr("style");
            $(".es-widget-2 .item:nth-child(4)").removeAttr("style");
            $(".es-widget-2 .item:nth-child(5)").removeAttr("style");
        }
    });

    $(document).on('click',".widget-link-textbox",function () {
        $(this).select();
        alert("Right click and copy or press ctrl + c");
    });
})(jQuery);
