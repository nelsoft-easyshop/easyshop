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
    
     $( "#dash" ).click(function() {
        alert("asdasd");
     });
     
     $("#homeTabs ul").idTabs("one"); 
}(jQuery));