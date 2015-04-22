<link rel="stylesheet" type="text/css" href="/assets/css/new-login.css" media='screen'/>
<div class="clear"></div>

<section class="bg-cl-fff">
    <div class="container pd-bttm-30">
        <div class="row">
            <div class="col-sm-12 identify-email-container pd-bttm-30">
                <h1 class="email-h1-title border-bottom pd-bttm-10 text-left">Reset Password</h1>

                <?php if(isset($isLoggedin) && $isLoggedin): ?>
                    
                    <div class="identify-email-top-group">
                        <p>
                            <strong>Oops, it seems that you are currently signed-in. </strong>
                        </p>
                        <p>
                            If you wish to continue to reset an account's password,
                            <a href='/login/logout'> make sure you are not logged-in to any account. </a>
                        </p>
                    </div>
                    
                <?php else: ?>

                    <?php $attr = [ 'id'=>'update-password','autocomplete'=>'off', 'class'=>'form-horizontal']; ?>
                    <?php echo form_open('',$attr); ?>
                    
                    <div class="form-group ">
                        <center>
                            <div class="col-sm-9 col-md-5 text-left identify-email-top-group nofloat">
                                <label class="password-update-label">New Password: </label>
                                <input type="password" class="form-control password-update-input" name="password" placeholder="" id="password"  maxlength="100">   
                            </div>
                            <div class="clear"></div>
                        </center>
                    </div>
                    
                    <div class="form-group ">
                        <center>
                            <div class="col-sm-9 col-md-5 nofloat text-left">
                                <label class="password-update-label ">Confirm Password: </label>
                                <input type="password" class="form-control" name="confirmpassword" placeholder="" id="confirmpassword"   maxlength="100" disabled="true"> 
                            </div>
                            <div class="clear"></div>
                        </center>
                    </div>
                
                    <div class="form-group ">
                        <center>
                            <div class="col-sm-9 col-md-5 nofloat  ">
                                <input type="submit" class="form-control submit-password-form" name="submit-password" placeholder=""  value="Update password"> 
                            </div>
                        </center>
                    </div>
                    
                    
                    <input type="hidden" name="hash" value="<?php echo html_escape($hash); ?>"/>
                    <?php echo form_close(); ?>
                    
                    <?php if(isset($isPost) && $isPost): ?>
                        <center>
                        <div class="input-group identify-email-bottom-group col-md-4 response-dialog nofloat">
                   
                            <?php if(isset($isSuccessful) && $isSuccessful): ?>
                                <div class="alert alert-success" role="alert">
                                    <strong>Hi <?php echo html_escape($user->getUsername()); ?>, your password has been successfully reset. You may now <a href="/login"> sign-in with your new password. </a></strong>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-danger" role="alert">
                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                    <strong>
                                        Ooops. <?php echo html_escape(reset($message)); ?>
                                    </strong>
                                </div>
                            <?php endif; ?>        
                        </div>
                        </center>
                    <?php endif; ?>
         
                    
                <?php endif; ?>
            </div>
         
            <input type="hidden" id="min-length-password" value="<?php echo $minPasswordLength; ?>"/>
        </div>
    </div>
</section>

<div class="clear"></div>



<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type='text/javascript' src="/assets/js/src/vendor/pwstrength.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
    <script type='text/javascript' src='/assets/js/src/vendor/bower_components/jquery.validate.js'></script>
    <script src="/assets/js/src/forgotpassword.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.user_forgotpass_update_password.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>
