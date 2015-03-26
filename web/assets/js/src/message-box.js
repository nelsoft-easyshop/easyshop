(function ($) {
    $(window).on("load", function(){
        
        var isRememberMeCookieSet = $.cookie("reminder-cookie");
        if(!isRememberMeCookieSet){
            
            $.cookie("reminder-cookie", '1', {
                expires : 31
            });
     
            $(".message-box").fadeIn().modal({
                onOpen: function(dialog) {
                    $(".message-box").removeClass("display-none");
                    dialog.overlay.fadeIn('fast', function () {
                        dialog.container.fadeIn('fast', function () {
                            dialog.data.fadeIn('fast');
                        });
                    });
                },
                onClose: function (dialog) {
                    dialog.data.fadeOut('fast', function () {
                        dialog.container.fadeOut('fast', function () {
                            dialog.overlay.fadeOut('fast', function () {
                                $.modal.close();
                            });
                        });
                    });
                    $(".message-box").addClass("display-none");

                }
            });
            $(".message-box").parents(".simplemodal-container").removeAttr("id").addClass("my-modal");
            $(".my-modal").css("height", "403px");
            var containerHeight = $(".my-modal").outerHeight();
            $(".message-container").css("height", containerHeight);
        }
    });
}(jQuery));


