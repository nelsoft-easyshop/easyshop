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
                <span class="setting-current-email">mangpedring@yahoo.com</span> 
                <button class="btn btn-setting-edit-btn" id="btn-edit-email">
                   <i class="icon-edit"></i> Edit
                </button>
                </div>
                <div class="edit-email">
                    <div class="row">
                        <div class="col-md-5 col-inline-textbtn">
                            <input type="text" class="text-info text-required" value="mangpedring@yahoo.com"/>
                            <!-- DISPLAY WHEN ERROR 
                            <span class="val-error-icon-pass"><i class="fa fa-times"></i></span>
                            <span class="val-error">Please enter at least 6 characters.</span>
                            -->
                            <!--DISPLAY WHEN OK-->
                            <span class="val-success"><i class="fa fa-check"></i></span>
                        </div>
                        <div class="col-md-5">
                            <button class="btn btn-setting-save-btn">
                                Save changes
                            </button>
                            <button class="btn btn-setting-cancel-btn" id="cancel-edit-email">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
                 <p class="p-note-setting">
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation
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
                     <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Current Password : </label>
                            <div class="col-sm-5">
                                <input type="text" class="text-info text-required" placeholder="Type your current password here">
                                <!-- DISPLAY WHEN ERROR -->
                                <span class="val-error-icon-pass"><i class="fa fa-times"></i></span>
                                <span class="val-error">Please enter at least 6 characters.</span>
                                
                                <!--DISPLAY WHEN OK
                                <span class="val-success-pass"><i class="fa fa-check"></i></span>-->
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-4 control-label">New Password : </label>
                            <div class="col-sm-5">
                                <input type="text" class="text-info text-required" placeholder="Type your new password here">
                                <div class="progress" style="height: 10px; margin-top: 5px; margin-bottom: 0px;">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 60%; background: #00a388 !important;">
                                        
                                    </div>
                                </div>
                                <span class="password-label-strength">
                                    Password Strength: <b>Good</b>
                                </span>
                                <!-- DISPLAY WHEN ERROR 
                                <span class="val-error-icon-pass"><i class="fa fa-times"></i></span>
                                <span class="val-error">Please enter at least 6 characters.</span>
                                -->
                                <!--DISPLAY WHEN OK-->
                                <span class="val-success-pass"><i class="fa fa-check"></i></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-required">Confirm New Password : </label>
                            <div class="col-sm-5">
                                <input type="text" class="text-info" placeholder="Confirm your new password here">
                                <!-- DISPLAY WHEN ERROR 
                                <span class="val-error-icon-pass"><i class="fa fa-times"></i></span>
                                <span class="val-error">Please enter at least 6 characters.</span>
                                -->
                                <!--DISPLAY WHEN OK-->
                                <span class="val-success-pass"><i class="fa fa-check"></i></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-5">
                            <button class="btn btn-setting-save-btn">
                                Save changes
                            </button>
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
                    <form class="form-horizontal">
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
                            <label class="col-sm-3 control-label">Your Password : </label>
                            <div class="col-sm-5">
                                <input type="text" class="text-info text-required" placeholder="Type your current password here">
                                <!-- DISPLAY WHEN ERROR -->
                                <span class="val-error-icon-pass"><i class="fa fa-times"></i></span>
                                <span class="val-error">Please enter at least 6 characters.</span>
                                
                                <!--DISPLAY WHEN OK
                                <span class="val-success-pass"><i class="fa fa-check"></i></span>-->
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-5">
                            <button class="btn btn-setting-save-btn">
                                Save changes
                            </button>
                            <span class="btn btn-setting-cancel-btn" id="cancel-deact-status">
                                Cancel
                            </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
    </div>
</div>