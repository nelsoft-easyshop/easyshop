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
                            <div class="div-validation-container auto-height">
                                <p class="span-validation-ok username-accepted" style="display: none">
                                    <i class="glyphicon glyphicon-ok-sign"></i>
                                    Username is available
                                </p>
                                 <p class="span-validation-error username-denied" style="display: none">
                                     <i class="glyphicon glyphicon-remove-sign"></i>
                                     Username is not available
                                 </p>
                                <div class="username-restrictions">
                                    <ul>
                                        <li>
                                            <i class="glyphicon glyphicon-remove-sign"></i> Only letters, numbers, and underscores are allowed.
                                        </li>
                                        <li>
                                            <i class="glyphicon glyphicon-remove-sign"></i> Must consist of at least 5 characters.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <button class="btn btn-block btn-orange-lg proceed">
                                PROCEED
                            </button>
                            <img src="<?php echo getAssetsDomain()?>assets/images/orange_loader.gif" style="display: none">
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
                                    <img src="<?php echo getAssetsDomain()?>assets/images/orange_loader.gif" id="img-check-availability" style="display: none">
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
                                                            <img src="<?php echo getAssetsDomain()?>assets/images/img_main_product.png" id="available-image" class="img-rec-product">
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
                            <img src="<?php echo getAssetsDomain()?>assets/images/orange_loader.gif" id="img-send-request" style="display: none">
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
    </div>
    <div id="data-container" data-provider="<?=$social_media_type?>" data-id="<?=$social_media_id?>" data-fname="<?=$fullname?>" data-gender="<?=$gender?>" data-email="<?=$email?>"></div>
</section>
<br/>
<br/>
<script src='/assets/js/src/SocialMediaRegistration.js' type='text/javascript'></script>
<script src='/assets/js/src/vendor/jquery.simplemodal.js' type='text/javascript'></script>
