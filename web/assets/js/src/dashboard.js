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
<<<<<<< HEAD

function triggerTab(x){
    $('.idTabs a[href="#'+x+'"]').trigger('click');
};

=======
    
     $( "#dash" ).click(function() {
        alert("asdasd");
     });
     
     $("#homeTabs ul").idTabs("one"); 
>>>>>>> b95190e5fa6b5b841db98e5d184cb086dd80d8ff
}(jQuery));