(function ($) {
    $( "#my-store-menu-trigger" ).click(function() {
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
}(jQuery));