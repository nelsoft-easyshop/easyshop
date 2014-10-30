<link type="text/css" href='/assets/css/main-style.css?ver=<?php echo ES_FILE_VERSION ?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-login.css?ver=<?php echo ES_FILE_VERSION ?>' rel="stylesheet" media='screen'/>
<br/>
<br/>
<section class="section-login">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                Update your account
            </div>
            <div class="panel-body div-merge-container">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="merge-message" align="center">
                            <i class="glyphicon glyphicon-exclamation-sign"></i> <strong>Ooooops!</strong> Your username <a href="#"><strong><?=$username?></strong></a> has already been taken. <br/>Please choose from the following options below.
                        </h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group form-merge-2">
                            <label class="control-label">Type in your new username</label>
                            <input type="text" class="form-control input-merge" placeholder="New username" id="txt-username"/>
                            <div class="div-validation-container">
                                <p class="span-validation-ok username-accepted" style="display: none">
                                    <i class="glyphicon glyphicon-ok-sign"></i>
                                    Username is available
                                </p>
                                 <p class="span-validation-error username-denied" style="display: none">
                                     <i class="glyphicon glyphicon-remove-sign"></i>
                                     Username is not available
                                 </p>
                            </div>
                            <button class="btn btn-block btn-orange-lg proceed">
                                PROCEED
                            </button>
                            <img src="/assets/images/orange_loader.gif" style="display: none">
                        </div>
                    </div>
                    <div class="col-md-2 col-divider">
                        <div class="border-vr">
                        </div>
                        <div class="border-hr">
                        </div>
                        <div class="border-or">
                            OR
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group form-merge">
                            <p class="label-merge">
                                If you believe you own this account, type in your email address to send a verification request to the registered email of username to update your account.
                            </p>
                            <div class="row">
                                <div class="col-md-7 col-check-1">
                                    <input type="text" class="form-control input-merge input-merge-username" id="txt-email" placeholder="Type in your email account"/>
                                </div>
                                <div class="col-md-5 col-check-2">
                                    <button class="btn btn-default-3 btn-block check-availability" id="check-availability">Check Availability</button>
                                    <img src="/assets/images/orange_loader.gif" id="img-check-availability" style="display: none">
                                </div>
                            </div>
                            <div class="div-validation-container">
                                <p class="span-validation-ok email-accepted" style="display: none">
                                    <i class="glyphicon glyphicon-ok-sign"></i>
                                    Email Address is available
                                </p>
                                 <p class="span-validation-error email-denied" style="display: none">
                                     <i class="glyphicon glyphicon-remove-sign"></i>
                                     Email Address is not available
                                 </p>
                            </div>
                            <div class="div-search-merge-account div-result" id="available-result" style="display: none">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <center>
                                            <a href="#">
                                                <div class="div-rec-product-image">
                                                    <center>
                                                        <span class="span-me">
                                                            <img src="/assets/images/img_main_product.png" id="available-image" class="img-rec-product">
                                                        </span>
                                                    </center>
                                                </div>
                                            </a>
                                        </center>
                                    </div>
                                    <div class="col-xs-9">
                                        <table width="100%" class="tbl-merge">
                                            <tr>
                                                <td class="td-merge-label">
                                                    Username:
                                                </td>
                                                <td class="td-merge-detail" id="available-username">
                                                    sampleUser
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="td-merge-label">
                                                    Email:
                                                </td>
                                                <td class="td-merge-detail" id="available-email">
                                                    sampleUser@yahoo.com
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="td-merge-label">
                                                    Location:
                                                </td>
                                                <td class="td-merge-detail" id="available-location">
                                                    Makina City
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-block btn-orange-lg send-request">
                                SEND REQUEST
                            </button>
                            <img src="/assets/images/orange_loader.gif" id="img-send-request" style="display: none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal content -->
    <div id="basic-modal-content" class="modal-message">
        <div class="modal-text-content">
            Username updated! You can now login to EasyShop.ph
        </div>
        <center>
            <span class="modalCloseImg simplemodal-close btn btn-default-2" id="modal-login">Login</span>
        </center>
    </div>
    <div id="basic-modal-content" class="modal-message-send-request">
        <div class="modal-text-content email-sent">

        </div>
        <center>
            <span class="modalCloseImg simplemodal-close btn btn-default-1" id="close-modal">Close</span>
            <span class="modalCloseImg simplemodal-close btn btn-default-2" id="homepage-modal">Go to homepage</span>
        </center>
    </div>
    <div style='display:none;'>
<!--        <a class="modalCloseImg simplemodal-close" title="Close">-->
<!--            <i class="glyphicon glyphicon-remove"></i>-->
<!--        </a>-->
    </div>
    <div id="data-container" data-provider="<?=$social_media_type?>" data-id="<?=$social_media_id?>" data-fname="<?=$fullname?>" data-gender="<?=$gender?>" data-email="<?=$email?>"></div>
</section>
<br/>
<br/>
<script>
    jQuery(function ($) {
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var provider = $("#data-container").attr('data-provider');
        var id = $("#data-container").attr('data-id');
        var fname = $("#data-container").attr('data-fname');
        var gender = $("#data-container").attr('data-gender');
        var email = $("#data-container").attr('data-email');
        $('.proceed').on('click', function (e) {
            var username = $("#txt-username").val();
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
                        return false;
                    }
                    else {
                        $(".username-accepted").show();
                        $(".username-denied").hide();
                        $('.modal-message').modal({
                            onShow : function () {
                                $("#modal-login").on('click',function () {
                                    window.location.replace("/");
                                });
                            }
                        });
                        return false;
                    }
                }
            });
        });

        $('.send-request').on('click', function (e) {
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var txtEmail = $("#txt-email").val();
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
            var txtEmail = $('#txt-email').val();
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
                        $("#available-username").html(data.username);
                        $("#available-email").html(data.email);
                        $("#available-location").html(data.location);
                    }
                }
            });
        });
    });
</script>

<script src='/assets/js/src/vendor/jquery.simplemodal.js' type='text/javascript'></script>
