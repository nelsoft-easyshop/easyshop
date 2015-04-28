<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link type="text/css" href='/assets/css/main-style.css?ver=<?php echo ES_FILE_VERSION ?>' rel="stylesheet" media='screen'/>
    <link type="text/css" href='/assets/css/new-login.css?ver=<?php echo ES_FILE_VERSION ?>' rel="stylesheet" media='screen'/>
<?php else: ?>
    <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.social-media-merge.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
<?php endif; ?>

<br/>
<br/>
<section class="section-login">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-body div-merge-container">
                <p class="panel-title-merge">Update your account</p>
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="merge-message">
                            Hi <a href="javascript:void(0);"><?= html_escape($username)?></a>, welcome to EasyShop.ph! Choose a username to begin using your social media account now.  If you already have an EasyShop account, you can also choose to merge these two for your convenience.
                        </h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group form-merge-2">
                            <label class="control-label">Type in your username</label>
                            <input type="text" class="form-control input-merge" placeholder="New username" id="txt-username"/>
                            <div class="div-validation-container auto-height">
                                <p class="span-validation-ok username-accepted" style="display: none">
                                    Username is available
                                </p>
                                 <p class="span-validation-error username-denied" style="display: none">
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
                            
                            <?php if(trim($email) === ''): ?>
                            <br/>
                            <div class="form-group">
                                <label class="control-label">Type in your email address</label>
                                <input type="text" name="email" class="form-control register-new-email" placeholder="email@sample.com" aria-describedby="basic-addon2">
                                <span class="merge-email-note">Your social media account does not have an associated email address</span>
                                <div class="div-validation-container auto-height">
                                    <p class="span-validation-ok register-new-email-accepted" style="display:none;">
                                        Email is available
                                    </p>
                                    <p class="span-validation-error register-new-email-denied" style="display:none;">
                                        Email is not available
                                    </p>
                                </div>
                             </div>
                            <?php endif; ?>
                            <br/>
                            <button class="btn btn-block btn-orange-lg proceed">
                                PROCEED
                            </button>
                            <center>
                                <img src="<?php echo getAssetsDomain()?>assets/images/es-loader-3-sm.gif" style="display: none;">
                            </center>
                        </div>
                    </div>
                    <div class="col-md-2 col-divider">
                        <div class="border-vr">
                        </div>
                        <div class="border-hr">
                        </div>
                        <div class="border-or">
                            <span>OR</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group form-merge">
                            <p class="label-merge">
                                If you wish to merge an existing account with this one, type in the email address of the other account so we can send a merge verfification request to that email.
                              
                            </p>
                            <div class="row">
                                <div class="col-md-7 col-check-1">
                                    <input type="text" class="form-control input-merge input-merge-username" id="txt-email" placeholder="Type in your email account"/>
                                </div>
                                <div class="col-md-5 col-check-2">
                                    <button class="btn btn-default-3 btn-block check-availability" id="check-availability">Check Availability</button>
                                    <center>
                                        <img src="<?php echo getAssetsDomain()?>assets/images/es-loader-3-sm.gif" id="img-check-availability" style="display: none;  margin-top: 7px;">
                                    </center>
                                </div>
                            </div>
                            <div class="div-validation-container">
                                <p class="span-validation-ok email-accepted" style="display: none">
                                    E-mail address found
                                </p>
                                <p class="span-validation-error email-denied" style="display: none">
                                     E-mail address not found
                                </p>
                                <p class="span-validation-error email-already-merged" style="display: none">
                                    Account has already been merged
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
                                                    Email:
                                                </td>
                                                <td class="td-merge-detail" id="available-email">
                                                    
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-block btn-orange-lg send-request disabled">
                                SEND REQUEST
                            </button>
                            <center>
                                <img src="<?php echo getAssetsDomain()?>assets/images/es-loader-3-sm.gif" id="img-send-request" style="display: none;">
                            </center>
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
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src='/assets/js/src/SocialMediaRegistration.js?ver=<?php echo ES_FILE_VERSION ?>' type='text/javascript'></script>
    <script src='/assets/js/src/vendor/jquery.simplemodal.js' type='text/javascript'></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.SocialMediaRegistration.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

