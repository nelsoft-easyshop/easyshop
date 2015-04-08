
<style>
    .activated-container{
        height: 220px !Important;
    }

    @media only screen and (max-width: 991px){
        .activated-container{
            height: 288px !important;
        }
    }
</style>


<div class="div-tab">
    <div class="div-tab-inner">
        <div class="div-account-setting">
            <p class="panel-setting-title">Email Address</p>
            <div class="div-setting-content">
                <div class="current-email">
                <span class="setting-current-email" id="currentEmail"><?php echo html_escape($member->getEmail()); ?></span> 
                <button class="btn btn-setting-edit-btn" id="btn-edit-email">
                   <i class="icon-edit"></i> Edit
                </button>
                <img src="<?php echo getAssetsDomain(); ?>assets/images/es-loader-3-sm.gif" class="verify_img" style="display:none"/>
                <div id="verifyEmail" style="<?php echo $member->getIsEmailVerify() == 0 && trim($member->getEmail()) !== ''?'':'display:none;'?>"  <?php echo (trim($member->getEmail())==''?'':'disabled');?>>
                    <span class="val-error" style="color:blue !important; cursor:pointer;" id="verifyEmailAction">Resend Email Verification</span>
                </div>
                <div id="verifiedEmail" style="<?php echo $member->getIsEmailVerify() == 0?'display:none;':''?>">
                    <span class="val-error" style="color:green !important" id="verifiedEmailText"><strong>Verified</strong></span>
                </div>                                   
                <div id="errorIndicatoreVerify" style="display:none;">
                    <span class="val-error" id="errorTextVerify"></span>
                 </div>
                </div>
                <div class="edit-email" id="editEmailPanel">
                    <div class="row">
                        <div class="col-md-5 col-inline-textbtn">
                            <input type="text" class="text-info text-required" id="emailAddressEdit" value="<?php echo html_escape($member->getEmail());?>"/>
                            <div id="errorIndicatoreEmailAddress" style="display:none;">
                                <span class="val-error" id="errorTextEmail"></span>
                            </div>
                            <!--DISPLAY WHEN OK-->
                            <span class="val-success"><i class="fa fa-check"></i></span>
                        </div>
                        <div class="col-md-5">
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/es-loader-3-sm.gif" class="changeEmailLoader" style="display:none"/>
                             <div id="changeEmailBtnAction">   
                                <button class="btn btn-setting-save-btn" id="changeEmailBtn">
                                    Save changes
                                </button>
                                <button class="btn btn-setting-cancel-btn" id="cancel-edit-email">
                                    Cancel
                                </button>
                        </div>                                
                        </div>
                    </div>
                </div>
                 <p class="p-note-setting">
                    You will receive all notifications from EasyShop.ph through your email. Make sure that your e-mail address is active and that you have verified your account.
                </p>
            </div>
        </div>
        <div class="div-account-setting">
            <p class="panel-setting-title">Change Password</p>
            <div class="div-setting-content">
                <div class="current-password">
                    <span class="setting-current-email">
                        &bull;
                        &bull;
                        &bull;
                        &bull;
                        &bull;
                        &bull;
                        &bull;
                        &bull;
                        &bull;
                        &bull;
                    </span> 
                    <button class="btn btn-setting-edit-btn" id="btn-edit-password">
                       <i class="icon-edit"></i> Change password
                    </button>
                </div>
                <div class="edit-password">
                     <form class="form-horizontal" id="changePassForm">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-es-danger" style="display:none" role="alert" id="password-change-error">
                                    Error
                                </div>
                                
                                <div class="alert alert-es-success" style="display:none" role="alert" id="password-change-success">
                                    Password updated successfully
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Current Password : </label>
                            <div class="col-sm-5">
                                <input type = "password" id="currentPassword" name="currentPassword" class="text-info text-required" placeholder="Type your current password here">
                                
                                <!--DISPLAY WHEN OK
                                <span class="val-success-pass"><i class="fa fa-check"></i></span>-->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">New Password : </label>
                            <div class="col-sm-5">
                                <input type="password" id="password" name="password" class="text-info text-required" placeholder="Type your new password here">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-required">Confirm New Password : </label>
                            <div class="col-sm-5">
                                <input type="password" id="confirmPassword" name="confirmPassword" class="text-info" placeholder="Confirm your new password here" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4" style='text-align:center;'>
                                
                            </div>
                            <div class="col-sm-5" >
                                <center>
                                    <img src="<?php echo getAssetsDomain(); ?>assets/images/es-loader-3-sm.gif" class="changePasswordLoader" style="display:none"/>
                                </center>
                                <div id="actionGroupChangePass">
                                
                                <input type="submit" class="btn btn-setting-save-btn" id="changePassBtn" name="changePassBtn"  value="Save Changes">
                                <span class="btn btn-setting-cancel-btn" id="cancel-edit-password">
                                    Cancel
                                </span>
                            </div>
                            </div>
                        </div>
                    </form>
                </div>
                <p class="p-note-setting">
                    Having a strong password makes your account more secure. We recommend that you change your password regularly. For the best security, use a combination of numbers, letters and special characters.
                </p>
            </div>
        </div>
        <div class="div-account-setting">
            <p class="panel-setting-title">Deactivate your account</p>
            <div class="div-setting-content">
                <p class="p-deact-note-setting">
                    Deactivating your account will make all of your products unavailable for purchase. However, other people with whom you have transacted with will keep a record of the details of your transactions.
                    You will receive an email that contains a reactivation link should you wish to reactivate your account at a future time.
                </p>
                <div class="current-status">
                    <button class="btn btn-default-1 btn-deactivate" id="btn-edit-status">
                       I want to deactivate my account
                    </button>
                </div>
                <div class="edit-status">
                    <p class="p-orange"> Are you sure you want to deactivate your account? </p>
                    <form class="form-horizontal" method="post" id="deactivateAccountForm">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Your Username : </label>
                            <div class="col-sm-5">
                                <input type="text" name="deactivateUserName" id="deactivateUsername" class="text-info text-required" placeholder="Type your username here">
                            </div>
                        </div>                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Your Password : </label>
                            <div class="col-sm-5">
                                <input type="password" name="deactivatePassword" id="deactivatePassword" class="text-info text-required" placeholder="Type your current password here">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-5">
                                <div id="deactivateActionPanel">
                                    <input type="hidden" id="idMember" value="<?php echo $member->getidMember() ?>">
                                    <input class="btn btn-setting-save-btn" type='submit' value='Save Changes' id="deactivateAccountButton"/>
                                    <span class="btn btn-setting-cancel-btn" id="cancel-deact-status">
                                        Cancel
                                    </span>
                                </div>
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/es-loader-3-sm.gif" id="deactivateAccountLoader" style="display:none"/>                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="email-cooldown-mins" value="<?php echo \EasyShop\Account\AccountManager::EMAIL_COOLDOWN_DURATION_IN_MINUTES ?>"/>


<div id="activated-modal" style="display:none; height: 100px;">
    <h2 class="deactivate-modal-title">Deactivated Account</h2>
    <div id="activated-message">
            You have successfully deactivated your account.<br/><br/> If you have been unsatisfied with <a href="#">EasyShop</a> as an e-Commerce platform, please send us an email at <a href="#">info@easyshop.ph</a> to let us know how we can improve our website further.<br/><br/> Have a nice day!
    </div>
</div>
