<link type="text/css" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<link rel="stylesheet" href="/assets/css/bootstrap-mods.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>

<div class="clear mrgn-top-35 display-ib"></div>


<div class="res_wrapper login_content">
    <input type='hidden' value='<?php echo isset($loginFail)? $loginFail : ""; ?>' id='loginFail'/>
    <input type='hidden' value='<?php echo isset($timeoutLeft)? $timeoutLeft : ""; ?>' id='timeoutLeft'/>

    <div class="login-tli-con" id="failed-login" style="display: none">
        <div class="border-all border-rad-3">
            <div class="col-md-12 border-bottom bg-cl-f7f7f7">
                <div class="pd-tb-8">
                    <h5><strong>Trouble Logging In?</strong></h5>
                </div>
            </div>               
            <div class="col-md-12 text-center pd-top-30 pd-bttm-30">
                Too many failed login attempts.
                <strong><p id="lockoutDuration"></p></strong>
            </div>
            <div class="pd-tb-20 pd-lr-20 text-right login-btn-con">
                <a href="login" class="btn btn-primarybtn">Try again</a>
                <a href="login/identify" class="btn btn-default">Help me locate my account</a>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    
    <div class="border-all border-rad-3" id="login-form">
        <div class="col-md-12 border-bottom bg-cl-f7f7f7">
            <div class="pd-tb-8">
                 <h5><strong>Log In</strong></h5>

            </div>
        </div>
        
        <div class="clear"></div>
        <div class="pd-tb-45">
            <?php if($logged_in): ?>
            
                <div class="col-sm-12 col-md-12">
                    <div class="login_box ">
                        <div style="text-align:center;">
                            <p>You are currently signed-in as <b><?php echo html_escape($user->getUsername()); ?></b>. </p>
                            <p>If you wish to sign-in as another user, <a href='/login/logout' class="orange2">click here.</a> </p>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            <?php else: ?>
            
                <?php $attr = array('id'=>'login_form'); ?>
                <?php echo form_open('', $attr); ?>
                    <div class="col-sm-5 col-md-5 pd-tb-20">
                        <div class="login_box">
                            <div class="row mrgn-bttm-25">
                                <div class="col-sm-11 col-sm-offset-2 col-md-11 col-md-offset-2">
                                    For Registered Users
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="row mrgn-bttm-10">
                                <div class="col-xs-12 col-sm-3 col-sm-offset-2 col-md-3 col-md-offset-2">
                                    <label for="login_username">Username:</label>
                                </div>
                                <div class="col-xs-12 col-sm-7 col-md-7">
                                    <input type="text" id="login_username" name="login_username" class="ui-form-control">
                                    <span id="username_error" class="red error_cont" style="font-weight:bold;display:block;padding:4px 0;"></span>
                                </div>
                            </div>
                            <div class="row mrgn-bttm-10">
                                <div class="col-xs-12 col-sm-3 col-sm-offset-2 col-md-3 col-md-offset-2">
                                    <label for="login_password">Password:</label>
                                </div>
                                <div class="col-xs-12 col-sm-7 col-md-7">
                                <input type="password" id="login_password" name="login_password" class="ui-form-control"> 
      
                                    <span id="passw_error" class="red error_cont" style="font-weight:bold;display:block;padding:4px 0;"> </span>
                                    <?php $formError = isset($errors) ? reset($errors)['login'] : ''; ?>
                                  
                                    <span id="login_error" class="red" style="font-weight: bold; display:block">
                                        <?php if($formError !== 'Account Deactivated' && $formError !== 'Account Banned'):  ?>
                                        <?php echo html_escape($formError); ?>
                                        <?php endif; ?>
                                    </span>
                                    
                                    <span id="deactivatedAccountPrompt" class="red" style="font-weight:bold; display: <?php echo $formError === 'Account Deactivated' ? 'block' : 'none'  ?>">
                                    Oooops! This account is currently deactivated. If you want to reactivate your account click <a id='sendReactivationLink' data-id="" style='color:blue;cursor:pointer;'>here</a> to send a reactivation link to your email.
                                    </span>
                                    
                                    <?php if($formError === 'Account Banned'): ?>
                                        <input type="hidden" id="account-banned-error" value="true" data-message="<?php echo reset($errors)['message']; ?>">
                                    <?php endif; ?>
                                  

                                    <img src="/assets/images/orange_loader_small.gif" id="loading_img_activate" class="login_loading_img" style="display:none"/>                                    
                                </div>
                            </div>
                            <div class="row mrgn-bttm-25 mrgn-top-20">
                                <div class="col-xs-12 col-sm-5 col-sm-offset-2 col-md-5 col-md-offset-2">
                                    <div class="checkbox">
                                        <label for="keepmeloggedin">
                                            <input type="checkbox" name="keepmeloggedin" id="keepmeloggedin"> Remember me
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-5 col-md-5">
                                    <p class="text-right"><a href="/login/identify"><small>Forgot your password?</small></a></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2 text-center">
                                    <input id="login" type="submit" name="login_form" value="Log In"/>
                                    <img src="<?php echo getAssetsDomain()?>assets/images/orange_loader_small.gif" id="loading_img" class="login_loading_img" style="display:none"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2 text-center log-in-border">
                        <div class="width-50p border-or-tb">&nbsp;</div>
                        <div class="border-or">or</div>
                    </div>
                    <div class="col-sm-5 col-md-5 pd-tb-20">
                        <div class="row mrgn-bttm-25">
                            <div class="col-sm-10 col-sm-pull-1 col-md-10 col-md-pull-1 col-sm-offset-1 col-md-offset-1">
                                Use your Facebook or Google accounts to log in:
                            </div>
                        </div>
                        <div class="row mrgn-bttm-10">
                            <div class="col-sm-10 col-sm-pull-1 col-md-10 col-md-pull-1 col-sm-offset-1 col-md-offset-1">
                                <div class="log-in-btn log-in-facebook">
                                    <div>
                                        <a href="<?=$facebook_login_url?>">
                                            <span class="log-in-img"><img src="<?php echo getAssetsDomain()?>assets/images/img-log-in-fb.png"></span>
                                            <span class="text-center">Log In with Facebook</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mrgn-bttm-10">
                            <div class="col-sm-10 col-sm-pull-1 col-md-10 col-md-pull-1 col-sm-offset-1 col-md-offset-1">
                                <div class="log-in-btn log-in-google">
                                    <div>
                                        <a href="<?=$google_login_url?>">
                                            <span class="log-in-img"><img src="<?php echo getAssetsDomain()?>assets/images/img-log-in-google.png"></span>
                                            <span class="text-center">Log In with Google</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="row mrgn-bttm-20 mrgn-top-40">
                            <div class="col-sm-10 col-sm-pull-1 col-md-10 col-md-pull-1 col-sm-offset-1 col-md-offset-1 text-center">
                                Don't have an account yet?
                            </div>
                        </div>
                        <div class="row mrgn-bttm-10">
                            <div class="col-sm-10 col-sm-pull-1 col-md-10 col-md-pull-1 col-sm-offset-1 col-md-offset-1">
                                <div class="log-in-btn log-in-register">
                                    <div>
                                        <a href="/register"><span class="text-center">Register Here</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                <?php echo form_close();?>

            <?php endif; ?>
        </div>
    </div>
    <div class="clear"></div>
</div>


<div class="clear"></div>

<input type='hidden' value='<?php echo $url?>' id='redirect_url'/>

   
<script type='text/javascript' src='/assets/js/src/vendor/jquery.validate.js'></script>   
<script type="text/javascript" src="/assets/js/src/login.js?ver=<?=ES_FILE_VERSION?>"></script>
