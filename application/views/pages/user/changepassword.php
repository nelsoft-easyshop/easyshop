
<div class="clear"></div>
<section>
	<div class="wrapper"> <span class="reg_title">Change Password</span></div>
</section>
<div class="clear"></div>
<section>
      <div class="wrapper">
        <?php if($logged_in):?>
        <form method="post" id="changepass" autocomplete="off">
          <div class="fill_out_form">
				<?php if ($toggle_view == "1"): ?>
                    <div class="field">
                        <label for="cur_password">Current Password:</label>
                        <input id="cur_password" name="cur_password" type="password" maxlength="25"/>
                        <div id="username_status" style="display:inline;">
                            <img src="<?=base_url()?>/assets/images/check_icon.png" id="username_check" style="position: relative;display:none;vertical-align:middle"/>
                            <img src="<?=base_url()?>/assets/images/x_icon.png" id="username_x" style="position: relative;display:none;vertical-align:middle"/>
                            <span class="username_availability"></span>
                        </div>
                        <span class="red ci_form_validation_error"><?php echo form_error('password'); ?></span>
                    </div>
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
                          <input id="wsx" name="wsx" type="hidden" value="<?php echo $uname; ?>"/>
                    </div>
                    <div class="clear"></div>
                    <div class="field" style="padding-top:0px">
                          <input type="submit" name="changepass_btn" value="Save"/>
                    </div>
                <?php else: ?>
                    Password is successfully updated! <a href='<?=base_url()?>memberpage'>click HERE</a> to return.		        
                <?php endif; ?>            
          </div>
        </form>
        <?php else: ?>
			<div class="register_prompt_box">
			  <div>
				<strong>
					<p>You are not currently signed-in. </p>
					<p>If you wish to login, <a href='<?=base_url()?>login'>click here.</a> </p>
				</strong> 
			  </div>
			</div>
			<div class="clear"></div>
        <?php endif; ?>
      </div>
</section>

<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/register.js'></script>
<!-- password strength checker -->
<?php if($logged_in):?>
	<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/mootools-core-1.4.5-full-compat.js"></script> 
    <script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/password_meter.js"></script>
<?php endif; ?>
