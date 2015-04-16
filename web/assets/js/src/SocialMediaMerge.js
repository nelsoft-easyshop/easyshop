jQuery(function ($) {
    $('.proceed').click(function (e) {
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var oauthId = $("#dataContainer").attr('data-id');
        var oauthProvider = $("#dataContainer").attr('data-provider');
        var email = $("#dataContainer").attr('data-email');
        $.ajax({
            type : "POST",
            dataType : "json",
            url : "/SocialMediaController/SendMergeNotification",
            data : {csrfname : csrftoken, oauthId:oauthId, oauthProvider:oauthProvider, email:email, error:'email'},
            beforeSend : function () {
                $(".div-btn-container img").show();
                $(".div-btn-container button").hide();
            },
            success : function (data) {
                $(".div-btn-container button").show();
                $(".div-btn-container img").hide();
                $('#basic-modal-content-proceed').modal({
                    onShow : function() {
                        $('#close-modal').on('click', function() {
                            return false;
                        });
                        $('#homepage-modal').on('click', function() {
                            window.location.replace("/");
                        });
                    }
                });
            }
        });
    });
});
