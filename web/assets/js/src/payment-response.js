(function ($) {

    $(window).on("load resize",function(){ 
        $(".done-icon").slideUp(250);
        $(".new-icon").slideDown(250);
        $(".active-right-wing-cart-1").delay(500).animate({width: "50%"}, 1000);
        $(".active-left-wing-cart-2").delay(1500).animate({width: "50%"}, 1000);
        $(".active-breadcrumb-icon").delay(2500).animate({backgroundColor: "#00a388"}, 1000);
        $(".active-breadcrumb-title").delay(2500).animate({color: "#00a388"}, 1000).css("font-weight", "bold");
    });

    $(".checkout-item-attribute-container-header").click(function(){
        var triggeredAttributeBody = $(this).parent().find(".checkout-item-attribute-container-body");
        var triggeredAttributeActionText = $(this).parent().find(".checkout-item-attribute-container-action");
        
        $(".checkout-item-attribute-container-header").not($(this)).removeClass("toggled");
        $(".checkout-item-attribute-container-body").not(triggeredAttributeBody).slideUp("fast");
        $(".checkout-item-attribute-container-action").not(triggeredAttributeActionText).text("show");
        
        $(this).toggleClass("toggled");
        triggeredAttributeBody.slideToggle("fast");

        if ($('.checkout-item-attribute-container-header').hasClass('toggled')) {
            triggeredAttributeActionText.text("hide");
        }else{
            triggeredAttributeActionText.text("show");  
        }
    });
})(jQuery);

