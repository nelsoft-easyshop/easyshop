(function ($){
    $( ".p-reply-text" ).click(function() {
        $( ".div-reply-container" ).toggle("slow");
        $( ".text-cancel" ).toggle("fade");
    });
})(jQuery);