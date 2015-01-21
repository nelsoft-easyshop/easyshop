<div class="clear"></div>
<section>
      <div class="wrapper forgot_pword_con">
        <span class="reg_title">Change Password</span>
        <?php if(!$logged_in):?>
            
        <?php 
            $attr = array('id'=>'forgotpass','autocomplete'=>'off');
            echo form_open('',$attr);
        ?>      
          <div class="fill_out_form">           
                <?php if ($toggle_view == ""): ?>
                    <div class="field">
                          <div class="password_label">
                                <label for="password">New Password:</label>
                                <input id="password" name="password" type="password" maxlength="25" />
                                <input id="hash" name="hash" type="hidden" value="<?php echo $hash ?>"/>
                          </div>
                          <span class="red ci_form_validation_error"><?php echo form_error('password'); ?></span>
                    </div>
                    <div class="clear"></div>
                    <div class="password_info"> 
                        Passwords must be within 6-25 characters, and include numbers and upper-case and lower-case letters. 
                    </div>
                    <div class="field">
                          <label>Confirm New Password:</label>
                          <input id="cpassword" name="cpassword" type="password" maxlength="100" disabled="true">
                          <img src="/assets/images/check_icon.png" id="cpassword_check" style="position: relative;display:none;"/>
                          <img src="/assets/images/x_icon.png" id="cpassword_x" style="position: relative;display:none; "/>
                          <span class="red ci_form_validation_error"><?php echo form_error('cpassword'); ?></span>
                    </div>
                    <div class="clear"></div>
                    <div class="reset_pword_btn_con">
                          <input class="orange_btn3" type="button" name="forgotpass_btn" id="forgotpass_btn" value="Save"/>
                        
                    </div>
               <?php elseif($toggle_view == "1"): ?>
                    <div style='margin-left: 50px;'>
                        <p><strong>Your password has been successfully updated. </strong></p>
                        <p><a href='/'>Return to the Home Page. </a>    </p>
                    <div>
               <?php else: ?>
                    <div style='margin-left: 50px;'>
                        <p><b>Sorry, the link is invalid or is already expired.</b></p>
                        <p><a href='/'>Return to the Home Page.</a></p> 
                    </div>
               <?php endif; ?>                         
          </div>
        <?php echo form_close();?>
        
        <?php else: ?>
            <div style="margin-left:50px;">
                <br/><br/>
                <p>You are currently signed-in as <b><?php echo $user["username"]; ?></b>. </p>
                <p>If you wish to continue, sign-out first by <a href='/login/logout' class="orange2">clicking here.</a> </p>
                <br/><br/><br/>
            </div>
        <?php endif; ?>
      </div>
      
      <?php echo form_open('',array('id'=>'fp_complete', 'action' => '/resetconfirm')); ?>
        <input type='hidden' value='' id='tgv' name='tgv'/>
      <?php form_close(); ?>
      
</section>
<!-- password strength checker -->


<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type="text/javascript" src="/assets/js/src/vendor/mootools-core-1.4.5-full-compat.js"></script> 
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.validate.js'></script>
    <script type='text/javascript' src='/assets/js/src/register.js?ver=<?=ES_FILE_VERSION?>'></script>
    <script type='text/javascript' src='/assets/js/src/forgotpassword.js?ver=<?=ES_FILE_VERSION?>'></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.user_forgotpass_confirm.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

<?php if($toggle_view == ''): ?>
    <script type="text/javascript" src="/assets/js/src/vendor/password_meter.js"></script>
<?php endif; ?>
 
