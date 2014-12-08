<!--
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
-->

<div class="div-tab">
    <div class="dashboard-breadcrumb">
        <ul>
            <li>Dashboard</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>My Account</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>Account Settings</li>
        </ul>
    </div>
    <div class="div-tab-inner">
        <div class="div-account-setting">
            <p class="panel-setting-title">Email Address</p>
            <div class="div-setting-content">
                <div class="current-email">
                <span class="setting-current-email" id="currentEmail"><?php echo html_escape($member->getEmail()); ?></span> 
                <button class="btn btn-setting-edit-btn" id="btn-edit-email">
                   <i class="icon-edit"></i> Edit
                </button>
                <img src="/assets/images/orange_loader_small.gif" class="verify_img" style="display:none"/>
                <div id="verifyEmail" style="<?php echo $member->getIsEmailVerify() == 0 && trim($member->getEmail()) !== ''?'':'display:none;'?>"  <?php echo (trim($member->getEmail())==''?'':'disabled');?>>
                    <span class="val-error" style="color:blue !important; cursor:pointer;" id="verifyEmailAction">Verify Email</span>
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
                            <img src="/assets/images/orange_loader_small.gif" class="changeEmailLoader" style="display:none"/>
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
                                <input type="password" id="confirmPassword" name="confirmPassword" class="text-info" placeholder="Confirm your new password here">
                            </div>
                        </div>
                        <input id="username" name="wsx" type="hidden" value="<?php echo $member->getUserName(); ?>"/>
                        <div class="form-group">
                    
                            <div class="col-sm-4" style='text-align:center;'>
                                <img src="/assets/images/orange_loader_small.gif" class="changePasswordLoader" style="display:none"/>
                            </div>
                            <div class="col-sm-5" id="actionGroupChangePass">
                                <input type="submit" class="btn btn-setting-save-btn" id="changePassBtn" name="changePassBtn"  value="Save Changes">
                                <span class="btn btn-setting-cancel-btn" id="cancel-edit-password">
                                    Cancel
                                </span>
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
                    Deactivating your account will disable your profile and remove your name and picture from most things you've shared on Easyshop. Some information may still be visible to others, such as your name in their friends list and messages you sent.
                </p>
                <div class="current-status">
                    <button class="btn btn-default-1 btn-deactivate" id="btn-edit-status">
                       I want to deactivate my account
                    </button>
                </div>
                <div class="edit-status">
                    <p class="p-orange"> Are you sure you want to deactivate your account? </p>
                    <form class="form-horizontal" id="deactivateAccountForm">
                       <!--<div class="form-group">
                            <label class="col-sm-3 control-label">Reason for leaving : </label>
                            <div class="col-sm-9 col-with-radio">
                                <div class="div-reason-choice"><input type="radio" id="1" name="reason"/> <label class="lbl-radio" for="1">I don't find Easyshop useful.</label></div>
                                <div class="div-reason-choice"><input type="radio" id="2" name="reason"/> <label class="lbl-radio" for="2">I get too many emails, invitations, and requests from Easyshop.</label></div>
                                <div class="div-reason-choice"><input type="radio" id="3" name="reason"/> <label class="lbl-radio" for="3">I have a privacy concern.</label></div>
                                <div class="div-reason-choice"><input type="radio" id="4" name="reason"/> <label class="lbl-radio" for="4">I spend too much time using Easyshop.</label></div>
                                <div class="div-reason-choice"><input type="radio" id="5" name="reason"/> <label class="lbl-radio" for="5">I don't understand how to use Easyshop.</label></div>
                                <div class="div-reason-choice"><input type="radio" id="6" name="reason"/> <label class="lbl-radio" for="6">I don't feel safe on Easyshop.</label></div>
                                <div class="div-reason-choice"><input type="radio" id="7" name="reason"/> <label class="lbl-radio" for="7">My account was hacked.</label></div>
                                <div class="div-reason-choice"><input type="radio" id="8" name="reason"/> <label class="lbl-radio" for="8">I have another Easyshop account.</label></div>
                                <div class="div-reason-choice"><input type="radio" id="9" name="reason"/> <label class="lbl-radio" for="9">This is temporary. I'll be back.</label></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Newsletter opt out : </label>
                            <div class="col-sm-9 col-with-radio">
                                <div class="div-reason-choice"><input type="checkbox" id="opt-out" name="opt-out"/> <label class="lbl-radio" for="opt-out">Opt out of receiving newsletters from Easyshop</label></div>
                            </div>
                        </div>
                        -->
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
                                <img src="/assets/images/orange_loader_small.gif" id="deactivateAccountLoader" style="display:none"/>                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--
        <div class="div-account-setting">
            <p class="panel-setting-title">Connect with easydeal.ph</p>
            <div class="div-setting-content">
                <button class="btn btn-easydeal-btn">
                   Connect to <img src="/assets/images/easydeal-logo.png" class="easydeal-btn-img"/>
                </button>
                <p class="p-note-setting">
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation
                </p>
            </div>
        </div>
        -->
    </div>
</div>


<div id="activated-modal" style="display:none; height: 100px;">
    <h2>Deactivated Account</h2>
    <div id="activated-message">
            You have successfully deactivated your account.<br/><br/> If you have been unsatisfied with <a href="#">EasyShop</a> as an e-Commerce platform, please send us an email at <a href="#">info@easyshop.ph</a> to let us know how we can improve our website further.<br/><br/> Have a nice day!
    </div>
</div>
