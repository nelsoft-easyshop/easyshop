(function ($){
    $( ".fa-edit-icon" ).click(function() {
        $(".input-detail").css("display","inline");
        $(".fa-cancel-edit").css("display","inline");
        $("#save-edit").css("display","inline");
        $(".fa-edit").css("display","none");
        $(".text-contact").css("display","none");
    });

    $( ".fa-cancel-edit" ).click(function() {
        $(".input-detail").css("display","none");
        $(".fa-cancel-edit").css("display","none");
        $("#save-edit").css("display","none");
        $(".fa-edit").css("display","inline");
        $(".text-contact").css("display","inline");
    });

    $(document).on('click', '#btn-send-msg', function(){
        var message = $('#message').val().trim();
        if (message === '') {
            alert('Invalid Message');
            return false;
        }
    });

    $(function()
    {
        initSectorUI();
        $("#navigator a").click(function()
        {
            showSectorMini($(this).attr('href'));
        });
    });

    var initSectorUI = function(){
        if (location.hash) {
            showSectorMini(location.hash);
        }
    };

    var showSectorMini = function(sector){
        if (sector === "#SendMessage") {
            alert('Message sent.');
        }
        else {
            alert('Invalid Recipient, Try refreshing the page.');
        }
    };
})(jQuery);
