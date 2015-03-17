
jQuery(function ($) {
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var provider = $("#data-container").attr('data-provider');
    var id = $("#data-container").attr('data-id');
    var fname = $("#data-container").attr('data-fname');
    var gender = $("#data-container").attr('data-gender');
    var email = $("#data-container").attr('data-email');
    $('.proceed').on('click', function (e) {
        var username = $("#txt-username").val().trim();
        if (username === "") {
            $(".username-denied").show();
            $(".username-accepted").hide();
            return false;
        }
        $.ajax({
            dataType : "json",
            type: "post",
            url : "/SocialMediaController/registerSocialMediaAccount",
            data : {csrfname : csrftoken, username:username, provider:provider, id:id, fname:fname, gender:gender, email:email},
            beforeSend : function () {
                $(".form-merge-2 img").show();
                $(".proceed").hide();
            },
            success : function (data) {
                $(".form-merge-2 img").hide();
                $(".proceed").show();
                if (data == false) {
                    $(".username-denied").show();
                    $(".username-accepted").hide();
                    $(".username-restrictions").hide();
                }
                else if (data == 'Invalid Username') {
                    $(".username-restrictions").show();
                    $(".username-denied").hide();
                    $(".username-accepted").hide();
                } else {
                    $(".username-accepted").show();
                    $(".username-denied").hide();
                    $(".username-restrictions").hide();
                    $('.modal-message').modal({
                        onShow : function () {
                            $('.simplemodal-close').on('click', function() {
                                window.location.replace("/");
                            });
                            $("#modal-login").on('click',function () {
                                window.location.replace("/");
                            });
                        }
                    });
                }
                return false;
            }
        });
    });

    $('.send-request').on('click', function (e) {
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var txtEmail = $("#txt-email").val().trim();
        if (txtEmail == "") {
            $(".email-denied").show();
            $(".email-accepted").hide();
            return false;
        }
        $.ajax({
            dataType : "json",
            type: "post",
            url : "/SocialMediaController/sendMergeNotification",
            data : {csrfname : csrftoken, oauthId:id, oauthProvider:provider, email:txtEmail, error:'username'},
            beforeSend : function () {
                $("#img-send-request").show();
                $(".send-request").hide();
            },
            success : function (data) {
                $("#img-send-request").hide();
                $(".send-request").show();
                if (data == false) {
                    $(".email-denied").show();
                    $(".email-accepted").hide();
                }
                else {
                    $(".email-sent").html("We've just sent a verification message to " + txtEmail + " account's inbox.<br/> Please login to your email account and follow the instructions provided to complete this process.");
                    $('.modal-message-send-request').modal({
                        onShow : function () {
                            $('#close-modal').on('click', function() {
                                return false;
                            });
                            $('#homepage-modal').on('click', function() {
                                window.location.replace("/");
                            });
                        }
                    });
                }
            }
        });
    });

    $('#check-availability').on('click', function() {
        var txtEmail = $('#txt-email').val().trim();
        if (txtEmail == "") {
            $(".email-denied").show();
            $(".email-accepted").hide();
            return false;
        }
        $.ajax({
            dataType : "json",
            type: "post",
            url : "/SocialMediaController/checkEmailAvailability",
            data : {csrfname : csrftoken, email:txtEmail},
            beforeSend : function () {
                $("#img-check-availability").show();
                $("#check-availability").hide();
            },
            success : function (data) {
                $("#available-result").hide();
                $("#img-check-availability").hide();
                $("#check-availability").show();
                if (data == false) {
                    $(".email-denied").show();
                    $(".email-accepted").hide();
                }
                else {
                    $(".email-denied").hide();
                    $(".email-accepted").show();
                    $("#available-result").show();
                    $("#available-image").attr('src',data.image);
                    $("#available-email").html(data.email);
                    $("#available-location").html(data.location);
                }
            }
        });
    });
});