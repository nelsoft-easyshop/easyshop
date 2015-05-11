(function ($) {

    function chatcomposesearchresultwidth() {
        var ComposeSearchwidth = $(".chat-compose-search-container .ui-form-control").outerWidth();
        $(".chat-compose-search-result").css("max-width",ComposeSearchwidth);
        $(".chat-compose-search-result ul").getNiceScroll().resize();
    }

    function contactsearchresultwidth() {
        var ContactSearchWdith = $(".contact-list-container .form-control").outerWidth();
        $(".search-contact-results-container").css("width",ContactSearchWdith);
        $(".search-contact-results").getNiceScroll().resize();
    }

    $('.chat-compose').click(function() {
        $('.chat-compose-dialog').dialog('open');
    });

    $(".chat-compose-dialog").dialog({
        autoOpen: false,
        open: function(){
            $('.ui-widget-overlay').bind('click',function(){
                $('.chat-compose-dialog').dialog('close');
            })
        },
    });

    $(".chat-compose-search-result ul").niceScroll({
        cursorborder: "3px solid #b2b2b2",
        autohidemode: false,
        enablekeyboard: true,
        smoothscroll: true,
        zindex: 999999,
        railoffset: {top:0,left:9},
    });

    $(".chat-compose-search-container .ui-form-control").focusin(function() {
        if($(".chat-compose-search-container .ui-form-control").val().length > 0){
            $(".chat-compose-search-result").fadeIn(150);
            chatcomposesearchresultwidth();
        }

    });

    $( ".chat-compose-search-container .ui-form-control" ).keyup(function() {
        if($(".chat-compose-search-container .ui-form-control").val().length > 2){
            $(".chat-compose-search-result").fadeIn(150);
            chatcomposesearchresultwidth();
        }
    });

    $(".chat-compose-search-container .ui-form-control").focusout(function() {
        $(".chat-compose-search-result").fadeOut('fast');
    });


    $(".search-contact-results").niceScroll({
        cursorborder: "3px solid #b2b2b2",
        autohidemode: false,
        enablekeyboard: true,
        smoothscroll: true,
    });

    $(".contact-list").niceScroll({
        cursorborder: "3px solid #b2b2b2",
        autohidemode: false,
        enablekeyboard: true,
        smoothscroll: true,
        zindex: 8,
        railoffset: {top:0,left:-5},
    });

    $(".contact-list-container .form-control").focusin(function() {
        if($(".contact-list-container .form-control").val().length > 0){
            $(".search-contact-results-container").fadeIn(150);
            $(".search-contact-results").getNiceScroll().resize();
        }
    });

    $( ".contact-list-container .form-control" ).keyup(function() {
        if($(".contact-list-container .form-control").val().length > 2){
            $(".search-contact-results-container").fadeIn(150);
            $(".search-contact-results").getNiceScroll().resize();
        }
    });

    $(".contact-list-container .form-control").focusout(function() {
        $(".search-contact-results-container").fadeOut('fast');
    });

    $(".chat-box-messages").niceScroll({
        cursorborder: "3px solid #b2b2b2",
        autohidemode: false,
        enablekeyboard: true,
        smoothscroll: true,
        zindex: 8,
        railoffset: {top:0,left:-4},
    });

    $(window).on("resize", function () {
        setTimeout(function(){
            chatcomposesearchresultwidth();
            contactsearchresultwidth();
            var ContainerWidth = $(".contact-list-container").width();
            var SearchLocWidth = (ContainerWidth - 85);
            var ContactListWidth = (ContainerWidth - 77);
            var ContactListLocWidth = (ContainerWidth - 135);
            $(".search-contact-info-others span,.search-contact-info-name").css("max-width",SearchLocWidth);
            $(".contact-info-name").css("max-width",ContactListWidth);
            $(".contact-info-others span:nth-child(2)").css("max-width",ContactListLocWidth);
        }, 300);

        $(".contacts-container h4").unbind('click').click(function() {
            if ($(window).width() <= 991) {
                $(this).parent().toggleClass("show-contact-list");
                $(".contact-list-container").slideToggle('fast',function() {
                    $(".contact-list").getNiceScroll().resize();
                    $(".chat-box-messages").getNiceScroll().resize();
                    contactsearchresultwidth();
                });
            }
       });

       $(".contact-list li").unbind('click').click(function() {
          
           var $this = $(this);
           $this.addClass("contact-selected");
           $this.siblings().removeClass("contact-selected");
           
           if ($(window).width() <= 991) {
               $(".contact-list-container").slideUp('fast',function(){
                   $(".contact-list").getNiceScroll().resize();
                   $(".chat-box-messages").getNiceScroll().resize();
               });
               $(".contacts-container").removeClass("show-contact-list");
           }
       });
    }).resize();

})(jQuery);

