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
    
    $( "#btn-edit-store-name" ).click(function() {
      $( ".current-store-name" ).slideToggle( "slow" );
      $( ".edit-store-name" ).slideToggle( "slow" );
    });

    $( "#cancel-edit-store-name" ).click(function() {
        $( "#btn-edit-store-name" ).trigger( "click" );
    });

    $( "#btn-edit-store-url" ).click(function() {
      $( ".current-store-url" ).slideToggle( "slow" );
      $( ".edit-store-url" ).slideToggle( "slow" );
    });

    $( "#cancel-edit-store-url" ).click(function() {
        $( "#btn-edit-store-url" ).trigger( "click" );
    });

    $( "#btn-edit-store-theme" ).click(function() {
      $( ".current-store-theme" ).slideToggle( "slow" );
      $( ".edit-store-theme" ).slideToggle( "slow" );
    });

    $( "#cancel-edit-store-theme" ).click(function() {
        $( "#btn-edit-store-theme" ).trigger( "click" );
    });

    $( ".current-color-drop" ).click(function() {
        $( ".color-dropdown" ).slideToggle( "slow" );
        var attr8 = $("i.cd").attr("class");
        if(attr8 == "cd icon-dropdown pull-right"){
            $('i.cd').removeClass("cd icon-dropdown pull-right").addClass("cd icon-dropup pull-right");

        }
        else if(attr8 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-1" ).click(function() {
      $( ".color-name-drop" ).text("Easyshop");
      $( ".current-color-drop" ).css("background", "#ff893a");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor1 = $("i.cd").attr("class");
        if(attrColor1 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });
    $( "#color-item-2" ).click(function() {
      $( ".color-name-drop" ).text("Chestnut Rose");
      $( ".current-color-drop" ).css("background", "#D24D57");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor2 = $("i.cd").attr("class");
        if(attrColor2 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-3" ).click(function() {
      $( ".color-name-drop" ).text("POMEGRANATE");
      $( ".current-color-drop" ).css("background", "#F22613");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor3 = $("i.cd").attr("class");
        if(attrColor3 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-4" ).click(function() {
      $( ".color-name-drop" ).text("RED");
      $( ".current-color-drop" ).css("background", "#FF0000");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor4 = $("i.cd").attr("class");
        if(attrColor4 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-5" ).click(function() {
      $( ".color-name-drop" ).text("THUNDERBIRD");
      $( ".current-color-drop" ).css("background", "#D91E18");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor5 = $("i.cd").attr("class");
        if(attrColor5 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-6" ).click(function() {
      $( ".color-name-drop" ).text("FLAMINGO");
      $( ".current-color-drop" ).css("background", "#EF4836");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor6 = $("i.cd").attr("class");
        if(attrColor6 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-7" ).click(function() {
      $( ".color-name-drop" ).text("TALL POPPY");
      $( ".current-color-drop" ).css("background", "#C0392B");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor7 = $("i.cd").attr("class");
        if(attrColor7 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-8" ).click(function() {
      $( ".color-name-drop" ).text("MONZA");
      $( ".current-color-drop" ).css("background", "#CF000F");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor8 = $("i.cd").attr("class");
        if(attrColor8 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#color-item-9" ).click(function() {
      $( ".color-name-drop" ).text("CINNABAR");
      $( ".current-color-drop" ).css("background", "#E74C3C");
      $( ".current-color-drop" ).trigger( "click" );
      var attrColor9 = $("i.cd").attr("class");
        if(attrColor9 == "cd icon-dropup pull-right"){
            $('i.cd').removeClass("cd icon-dropup pull-right").addClass("cd icon-dropdown pull-right");
        }
    });

    $( "#btn-edit-store-cat" ).click(function() {
      $( ".current-store-cat" ).slideToggle( "slow" );
      $( ".edit-store-cat" ).slideToggle( "slow" );
    });

    $( "#cancel-edit-store-cat" ).click(function() {
        $( "#btn-edit-store-cat" ).trigger( "click" );
    });

    $( "#btn-edit-store-cat-new" ).click(function() {
      $( ".current-store-cat" ).slideToggle( "slow" );
      $( ".edit-store-cat-new" ).slideToggle( "slow" );
    });

    $( "#cancel-store-cat-new" ).click(function() {
        $( "#btn-edit-store-cat-new" ).trigger( "click" );
    });

    $(function() {
        $('.category_sort').sortable();
    });
    
    $('.feedback-from-seller').click(function() {
        $(this).toggleClass("active-bar",0);
        $('.feedback-from-seller-container').slideToggle();
        $('.feedback-from-buyer-container').slideUp();
        $('.feedback-for-buyer-container').slideUp();
        $('.feedback-for-seller-container').slideUp();
        $('.feedback-from-buyer').removeClass("active-bar");
        $('.feedback-for-seller').removeClass("active-bar");
        $('.feedback-for-buyer').removeClass("active-bar");
    });
    
    $('.feedback-from-buyer').click(function() {
        $(this).toggleClass("active-bar",0);
        $('.feedback-from-buyer-container').slideToggle();
        $('.feedback-from-seller-container').slideUp();
        $('.feedback-for-buyer-container').slideUp();
        $('.feedback-for-seller-container').slideUp();
        $('.feedback-from-seller').removeClass("active-bar");
        $('.feedback-for-seller').removeClass("active-bar");
        $('.feedback-for-buyer').removeClass("active-bar");
    });
    
    $('.feedback-for-seller').click(function() {
        $(this).toggleClass("active-bar",0);
        $('.feedback-for-seller-container').slideToggle();
        $('.feedback-from-seller-container').slideUp();
        $('.feedback-from-buyer-container').slideUp();
        $('.feedback-for-buyer-container').slideUp();
        $('.feedback-from-seller').removeClass("active-bar");
        $('.feedback-from-buyer').removeClass("active-bar");
        $('.feedback-for-buyer').removeClass("active-bar");
    });
    
    $('.feedback-for-buyer').click(function() {
        $(this).toggleClass("active-bar",0);
        $('.feedback-for-buyer-container').slideToggle();
        $('.feedback-from-seller-container').slideUp();
        $('.feedback-from-buyer-container').slideUp();
        $('.feedback-for-seller-container').slideUp();
        $('.feedback-from-seller').removeClass("active-bar");
        $('.feedback-from-buyer').removeClass("active-bar");
        $('.feedback-for-seller').removeClass("active-bar");
    });
    //FOR MOBILE
    $('.my-store-menu-mobile').click(function() {
        $('.my-account-menu-mobile-cont').slideUp();
        $('.my-store-menu-mobile-cont').slideToggle("fast", function(){
            var attr_ms = $("i.ms").attr("class");
            if(attr_ms == "ms fa fa-angle-down"){
                $('i.ms').removeClass("ms fa fa-angle-down").addClass("ms fa fa-angle-up");
            }
            else if(attr_ms == "ms fa fa-angle-up"){
                $('i.ms').removeClass("ms fa fa-angle-up").addClass("ms fa fa-angle-down");
            }
            
            var attr_ma2 = $("i.ma").attr("class");
            if(attr_ma2 == "ma fa fa-angle-up"){
                $('i.ma').removeClass("ma fa fa-angle-up").addClass("ma fa fa-angle-down");
            }
        });
    });
    
    $('.my-account-menu-mobile').click(function() {
        $('.my-store-menu-mobile-cont').slideUp();
        $('.my-account-menu-mobile-cont').slideToggle("fast", function(){
            var attr_ma = $("i.ma").attr("class");
            if(attr_ma == "ma fa fa-angle-down"){
                $('i.ma').removeClass("ma fa fa-angle-down").addClass("ma fa fa-angle-up");
            }
            else if(attr_ma == "ma fa fa-angle-up"){
                $('i.ma').removeClass("ma fa fa-angle-up").addClass("ma fa fa-angle-down");
            }
            
            var attr_ms2 = $("i.ms").attr("class");
            if(attr_ms2 == "ms fa fa-angle-up"){
                $('i.ms').removeClass("ms fa fa-angle-up").addClass("ms fa fa-angle-down");
            }
        });
    });
    
    $('.dash-mobile-trigger').click(function() {
        $('.my-store-menu-mobile-cont').slideUp("fast");
        $('.my-account-menu-mobile-cont').slideUp("fast");
        
        var attr_ms3 = $("i.ms").attr("class");
        if(attr_ms3 == "ms fa fa-angle-up"){
            $('i.ms').removeClass("ms fa fa-angle-up").addClass("ms fa fa-angle-down");
        }
        
        
        var attr_ma3 = $("i.ma").attr("class");
        if(attr_ma3 == "ma fa fa-angle-up"){
            $('i.ma').removeClass("ma fa fa-angle-up").addClass("ma fa fa-angle-down");
        }
    });
    
   
    $('.dash-mobile-trigger').click(function() {
        $('.my-store-menu-mobile-cont').slideUp("fast");
        $('.my-account-menu-mobile-cont').slideUp("fast");
    });
    
    $('#sc-selection-trigger').click(function() {
        $('.hide-selection-cont').slideToggle("fast");
        
    });

    
   
    
    $('#hide-date').click(function() {
        $('#th-date').toggle();
        $('.td-date').toggle();
    });
    
    $('#hide-trans').click(function() {
        $('#th-trans').toggle();
        $('.td-trans').toggle();
    });
    
    $('#hide-base-price').click(function() {
        $('#th-base-price').toggle();
        $('.td-base-price').toggle();
    });
    
    $('#hide-quantity').click(function() {
        $('#th-quantity').toggle();
        $('.td-quantity').toggle();
    });
    
    $('#hide-handling').click(function() {
        $('#th-handling').toggle();
        $('.td-handling').toggle();
    });
    
    $('#hide-total').click(function() {
        $('#th-total').toggle();
        $('.td-total').toggle();
    });
    
    $('#hide-es-charge').click(function() {
        $('#th-es-charge').toggle();
        $('.td-es-charge').toggle();
    });
    
    $('#hide-payment').click(function() {
        $('#th-payment').toggle();
        $('.td-payment').toggle();
    });
    
    $('#hide-net').click(function() {
        $('#th-net').toggle();
        $('.td-net').toggle();
    });
    
    $('#sc-p-selection-trigger').click(function() {
        $('.hide-p-selection-cont').slideToggle("fast");
        
    });
    
    $('#hide-p-date').click(function() {
        $('#th-p-date').toggle();
        $('.td-p-date').toggle();
    });
    
    $('#hide-p-trans').click(function() {
        $('#th-p-trans').toggle();
        $('.td-p-trans').toggle();
    });
    
    $('#hide-p-base-price').click(function() {
        $('#th-p-base-price').toggle();
        $('.td-p-base-price').toggle();
    });
    
    $('#hide-p-quantity').click(function() {
        $('#th-p-quantity').toggle();
        $('.td-p-quantity').toggle();
    });
    
    $('#hide-p-handling').click(function() {
        $('#th-p-handling').toggle();
        $('.td-p-handling').toggle();
    });
    
    $('#hide-p-total').click(function() {
        $('#th-p-total').toggle();
        $('.td-p-total').toggle();
    });
    
    $('#hide-p-es-charge').click(function() {
        $('#th-p-es-charge').toggle();
        $('.td-p-es-charge').toggle();
    });
    
    $('#hide-p-payment').click(function() {
        $('#th-p-payment').toggle();
        $('.td-p-payment').toggle();
    });
    
    $('#hide-p-net').click(function() {
        $('#th-p-net').toggle();
        $('.td-p-net').toggle();
    });
    
    
    $(function() {
        $( "#birthday-picker" ).datepicker({
            changeMonth: true,
			changeYear: true
        });
        
        $( "#sales-end-date" ).datepicker({
            changeMonth: true,
			changeYear: true
        });
        
        $( "#sales-start-date" ).datepicker({
            changeMonth: true,
			changeYear: true
        });
        
        $( "#payout-end-date" ).datepicker({
            changeMonth: true,
			changeYear: true
        });
        
        $( "#payout-start-date" ).datepicker({
            changeMonth: true,
			changeYear: true
        });
    });
    
    $('#give-feedback').click(function (e) {
        $('#feedback-modal').modal();
        $('#feedback-modal').parents("#simplemodal-container").addClass("feedback-container");
        return false;
    });

    $('#ship-item').click(function (e) {
        $('#shipping-details').modal();
        $('#shipping-details').parents("#simplemodal-container").addClass("shipping-details-container");
        return false;
    });

}(jQuery));


