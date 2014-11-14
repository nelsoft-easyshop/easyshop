(function ($) {

    $( "#dash" ).click(function() {
        $( "#my-account-menu" ).slideUp();
        var attr = $("i.m").attr("class");
        if(attr == "m icon-control-up toggle-down pull-right"){
            $('i.m').removeClass("m icon-control-up toggle-down pull-right").addClass("m icon-control-down toggle-down pull-right");
        }
        
        $( "#my-store-menu" ).slideUp();
        var attr2 = $("i.a").attr("class");
        if(attr2 == "a icon-control-up toggle-down pull-right"){
            $('i.a').removeClass("a icon-control-up toggle-down pull-right").addClass("a icon-control-down toggle-down pull-right");
            $( ".ml-li" ).css("border-radius", "0px 0px 7px 7px");
            $( ".submenu-my-account" ).css("border-radius", "0px");
            $( ".f-a" ).css("border-radius", "0px");
        }
    });
    
    $( "#my-store-menu-trigger" ).click(function() {
    $( "#my-account-menu" ).slideUp();
     var attr4 = $("i.a").attr("class");
    if(attr4 == "a icon-control-up toggle-down pull-right"){
            $('i.a').removeClass("a icon-control-up toggle-down pull-right").addClass("a icon-control-down toggle-down pull-right");
            $( ".ml-li" ).css("border-radius", "0px 0px 7px 7px");
            $( ".submenu-my-account" ).css("border-radius", "0px");
            $( ".f-a" ).css("border-radius", "0px");
        }
        $( "#my-store-menu" ).slideToggle( "slow", function() {
            var attr = $("i.m").attr("class");
        if(attr == "m icon-control-down toggle-down pull-right"){
            $('i.m').removeClass("m icon-control-down toggle-down pull-right").addClass("m icon-control-up toggle-down pull-right");
        }
        else if(attr == "m icon-control-up toggle-down pull-right"){
            $('i.m').removeClass("m icon-control-up toggle-down pull-right").addClass("m icon-control-down toggle-down pull-right");
        }
        });
        
    });
    
    $( "#my-account-menu-trigger" ).click(function() {
        $( "#my-store-menu" ).slideUp();
        var attr3 = $("i.m").attr("class");
        if(attr3 == "m icon-control-up toggle-down pull-right"){
            $('i.m').removeClass("m icon-control-up toggle-down pull-right").addClass("m icon-control-down toggle-down pull-right");
        }
        
        $( "#my-account-menu" ).slideToggle( "slow", function() {
            var attr = $("i.a").attr("class");
        if(attr == "a icon-control-down toggle-down pull-right"){
            $('i.a').removeClass("a icon-control-down toggle-down pull-right").addClass("a icon-control-up toggle-down pull-right");
            $( ".ml-li" ).css("border-radius", "0px");
            $( ".submenu-my-account" ).css("border-radius", "0px 0px 7px 7px");
            $( ".f-a" ).css("border-radius", "0px 0px 7px 7px");
        }
        else if(attr == "a icon-control-up toggle-down pull-right"){
            $('i.a').removeClass("a icon-control-up toggle-down pull-right").addClass("a icon-control-down toggle-down pull-right");
            $( ".ml-li" ).css("border-radius", "0px 0px 7px 7px");
            $( ".submenu-my-account" ).css("border-radius", "0px");
            $( ".f-a" ).css("border-radius", "0px");
        }
        });
    });
    
    $( "#info-item-1" ).click(function() {
        $( "#info-attributes-1" ).slideToggle( "slow", function() {
        var i_icon = $("i.info-item-icon-1").attr("class");
         if(i_icon == "info-item-icon-1 fa fa-plus-circle"){
            $('i.info-item-icon-1').removeClass("info-item-icon-1 fa fa-plus-circle").addClass("info-item-icon-1 fa fa-minus-circle");
            $(".text-info-icon-1").text("less info");
        }
        else if(i_icon == "info-item-icon-1 fa fa-minus-circle"){
            $('i.info-item-icon-1').removeClass("info-item-icon-1 fa fa-minus-circle").addClass("info-item-icon-1 fa fa-plus-circle");
            $(".text-info-icon-1").text("more info");
        }
        });
    });
    
    $( "#info-item-2" ).click(function() {
        $( "#info-attributes-2" ).slideToggle( "slow", function() {
        var i_icon = $("i.info-item-icon-2").attr("class");
         if(i_icon == "info-item-icon-2 fa fa-plus-circle"){
            $('i.info-item-icon-2').removeClass("info-item-icon-2 fa fa-plus-circle").addClass("info-item-icon-2 fa fa-minus-circle");
            $(".text-info-icon-2").text("less info");
        }
        else if(i_icon == "info-item-icon-2 fa fa-minus-circle"){
            $('i.info-item-icon-2').removeClass("info-item-icon-2 fa fa-minus-circle").addClass("info-item-icon-2 fa fa-plus-circle");
            $(".text-info-icon-2").text("more info");
        }
        });
    });  
    
    $( "#info-item-3" ).click(function() {
        $( "#info-attributes-3" ).slideToggle( "slow", function() {
        var i_icon = $("i.info-item-icon-3").attr("class");
         if(i_icon == "info-item-icon-3 fa fa-plus-circle"){
            $('i.info-item-icon-3').removeClass("info-item-icon-3 fa fa-plus-circle").addClass("info-item-icon-3 fa fa-minus-circle");
            $(".text-info-icon-2").text("less info");
        }
        else if(i_icon == "info-item-icon-3 fa fa-minus-circle"){
            $('i.info-item-icon-3').removeClass("info-item-icon-3 fa fa-minus-circle").addClass("info-item-icon-3 fa fa-plus-circle");
            $(".text-info-icon-3").text("more info");
        }
        });
    });
    
      $( "#info-item-4" ).click(function() {
        $( "#info-attributes-4" ).slideToggle( "slow", function() {
        var i_icon = $("i.info-item-icon-4").attr("class");
         if(i_icon == "info-item-icon-4 fa fa-plus-circle"){
            $('i.info-item-icon-4').removeClass("info-item-icon-4 fa fa-plus-circle").addClass("info-item-icon-4 fa fa-minus-circle");
            $(".text-info-icon-2").text("less info");
        }
        else if(i_icon == "info-item-icon-4 fa fa-minus-circle"){
            $('i.info-item-icon-4').removeClass("info-item-icon-4 fa fa-minus-circle").addClass("info-item-icon-4 fa fa-plus-circle");
            $(".text-info-icon-4").text("more info");
        }
        });
    }); 
    
      $( "#info-item-5" ).click(function() {
        $( "#info-attributes-5" ).slideToggle( "slow", function() {
        var i_icon = $("i.info-item-icon-5").attr("class");
         if(i_icon == "info-item-icon-5 fa fa-plus-circle"){
            $('i.info-item-icon-5').removeClass("info-item-icon-5 fa fa-plus-circle").addClass("info-item-icon-5 fa fa-minus-circle");
            $(".text-info-icon-2").text("less info");
        }
        else if(i_icon == "info-item-icon-5 fa fa-minus-circle"){
            $('i.info-item-icon-5').removeClass("info-item-icon-5 fa fa-minus-circle").addClass("info-item-icon-5 fa fa-plus-circle");
            $(".text-info-icon-5").text("more info");
        }
        });
    }); 
    
      $( "#info-item-6" ).click(function() {
        $( "#info-attributes-6" ).slideToggle( "slow", function() {
        var i_icon = $("i.info-item-icon-6").attr("class");
         if(i_icon == "info-item-icon-6 fa fa-plus-circle"){
            $('i.info-item-icon-6').removeClass("info-item-icon-6 fa fa-plus-circle").addClass("info-item-icon-6 fa fa-minus-circle");
            $(".text-info-icon-2").text("less info");
        }
        else if(i_icon == "info-item-icon-6 fa fa-minus-circle"){
            $('i.info-item-icon-6').removeClass("info-item-icon-6 fa fa-minus-circle").addClass("info-item-icon-6 fa fa-plus-circle");
            $(".text-info-icon-6").text("more info");
        }
        });
    }); 
    
    $( "#dash" ).click(function() {
        $("#aaa").addClass("selected");
        $("#active-items").css("display", "block");
    });

    $('.transaction-title-bought').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-bought').slideToggle();
        $('.on-going-transaction-list-sold').slideUp();
        $('.transaction-title-sold').removeClass("active-bar");
    });

    $('.transaction-title-sold').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-sold').slideToggle();
        $('.on-going-transaction-list-bought').slideUp();
        $('.transaction-title-bought').removeClass("active-bar");
    });

        $('.transaction-title-bought-completed').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-bought-completed').slideToggle();
        $('.on-going-transaction-list-sold-completed').slideUp();
        $('.transaction-title-sold-completed').removeClass("active-bar");
    });

    $('.transaction-title-sold-completed').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-sold-completed').slideToggle();
        $('.on-going-transaction-list-bought-completed').slideUp();
        $('.transaction-title-bought-completed').removeClass("active-bar");
    });
    
    $('.sales-title-total').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.sales-breakdown-container').slideToggle();
        $('.payout-breakdown-container').slideUp();
        $('.payout-title-total').removeClass("active-bar");
    });
    
    $('.payout-title-total').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.payout-breakdown-container').slideToggle();
        $('.sales-breakdown-container').slideUp();
       $('.sales-title-total').removeClass("active-bar");
    });
    
    $(".trans-item-info").click(function() {
        $(this).children("i").toggleClass("fa-minus-circle");
        $(this).next(".info-attributes").slideToggle();
    });

    $(".view-delivery-lnk").click(function() {
        $(this).next(".view-delivery-details").slideToggle();
    });
    
     //$(paginationContainer).children('center').html(obj.paginationData);
    
    $( "#set-default" ).hover(function() {
        $( ".default-ad-explain" ).slideToggle( "slow" );
    });
    
    $( ".map-trigger" ).click(function() {
        $( ".map-container" ).slideToggle( "slow" );
    });
   
   $( "#btn-edit-email" ).click(function() {
      $( ".current-email" ).slideToggle( "slow" );
      $( ".edit-email" ).slideToggle( "slow" );
    });
    
    $( "#cancel-edit-email" ).click(function() {
        $( "#btn-edit-email" ).trigger( "click" );
    });
    
    $( "#btn-edit-password" ).click(function() {
      $( ".current-password" ).slideToggle( "slow" );
      $( ".edit-password" ).slideToggle( "slow" );
    });
    
    $( "#cancel-edit-password" ).click(function() {
        $( "#btn-edit-password" ).trigger( "click" );
    });
    
    $( "#btn-edit-status" ).click(function() {
      $( ".current-status" ).slideToggle( "slow" );
      $( ".edit-status" ).slideToggle( "slow" );
    });
    
    $( "#cancel-deact-status" ).click(function() {
        $( "#btn-edit-status" ).trigger( "click" );
    });
    
}(jQuery));

