<link rel="stylesheet" type="text/css" href="/assets/css/new-login.css" media='screen'/>

<div class="clear"></div>

<section>
  

  
    <div class="col-sm-12 identify-email-container container" style="text-align:center">
 
        <?php if(isset($isLoggedin) && $isLoggedin): ?>
            
            <div class="identify-email-top-group" style="font-size: 16px;">
                <strong>Oops, it seems that you are currently signed-in. </strong>
                <hr/>
                If you wish to continue to reset an account's password,<a href='/login/logout'> make sure you are not logged-in to any account. </a>
            </div>
            
        <?php else: ?>

            <?php $attr = [ 'id'=>'update-password','autocomplete'=>'off', 'class'=>'form-horizontal']; ?>
            <?php echo form_open('',$attr); ?>
            
            <div class="form-group ">
                <center>
                    <div class="col-sm-4 identify-email-top-group nofloat">
                        <label class="col-md-6 password-update-label">New Password: </label>
                        <input type="password" class="form-control password-update-input" name="password" placeholder="" id="password"  maxlength="100">   
                    </div>
                </center>
            </div>
            
            <div class="form-group ">
                <center>
                    <div class="col-sm-4 nofloat ">
                        <label class="col-md-6 password-update-label ">Confirm Password: </label>
                        <input type="password" class="form-control" name="confirmpassword" placeholder="" id="confirmpassword"   maxlength="100" disabled="true"> 
                    </div>
                </center>
            </div>
        
            <div class="form-group ">
                <center>
                    <div class="col-sm-2 nofloat  ">
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
    

        
</section>

<div class="clear"></div>

<script type='text/javascript' src="/assets/js/src/vendor/pwstrength.js?ver=<?=ES_FILE_VERSION?>"></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.validate.js'></script>
<script src="/assets/js/src/forgotpassword.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>