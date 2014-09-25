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
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var recipient = $('#msg_recipient').val().trim();
        var message = $('#message').val().trim();
        if (message === '') {
            alert('Invalid Message');
            return false;
        }
        $.ajax({
            type : 'POST',
            dataType : 'json',
            url : '/messages/send_msg',
            beforeSend : function(){
                $('#loading_img').show();
                $('#btn-send-msg').hide();
            },
            data : {csrfname : csrftoken, recipient : recipient, msg : message},
            success: function(result){
                if(result.success !== 0){
                    alert('Message has been send');
                }
                else {
                    alert(result.msg);
                }
                $('#message').val('');
                $('#loading_img').hide();
                $('#btn-send-msg').show();
            }
        });
    });

})(jQuery);
