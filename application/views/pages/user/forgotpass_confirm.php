<?php require_once($page_javascript); ?>
<div class="clear"></div>
<section>
	<div class="wrapper"> <span class="reg_title">Change Password</span></div>
</section>
<div class="clear"></div>
<section>
      <div class="wrapper">
        <?php if(!$logged_in):?>
        <form method="post" id="forgotpass" autocomplete="off">
          <div class="fill_out_form">
				<?php if ($toggle_view == "1"): ?>
                    <div class="field">
                          <div class="password_label">
                                <label for="password">New Password:</label>
                                <input id="password" name="password" type="password" maxlength="25" />
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
                          <img src="<?=base_url()?>/assets/images/check_icon.png" id="cpassword_check" style="position: relative;display:none;"/>
                          <img src="<?=base_url()?>/assets/images/x_icon.png" id="cpassword_x" style="position: relative;display:none; "/>
                          <span class="red ci_form_validation_error"><?php echo form_error('cpassword'); ?></span>
                    </div>
                    <div class="clear"></div>
                    <div class="field" style="padding-top:0px">
                          <input type="submit" name="forgotpass_btn" value="Save"/>
                    </div>
                <?php elseif($toggle_view == "2"): ?>
                    <p><b>Sorry, the link is invalid or is already expired.</b></p>
           	        <p><a href='<?=base_url()?>home'>Return to the Home Page.</a></p>
                <?php else: ?>
                    Password is successfully updated! <a href='<?=base_url()?>memberpage'>click HERE</a> to return.		        
                <?php endif; ?>            
          </div>
        </form>
        <?php else: ?>
			<div class="register_prompt_box">
			  <div>
				<strong>
					<p>You are currently signed-in. </p>
				</strong> 
			  </div>
			</div>
			<div class="clear"></div>
        <?php endif; ?>
      </div>
</section>
<!-- password strength checker -->
<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/mootools-core-1.4.5-full-compat.js"></script> 
<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/password_meter.js"></script>

