
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/register.js'></script>

<div class="clear"></div>

<section>
  <div class="wrapper">
	<span class="reg_title">Account Registration</span>
  </div>
</section>
<section>
  <div class="wrapper">
	<div class="reg_steps reg_step1">
	  <ul>
		<li>Step 1. Fill in the account information</li>
		<li>Step 2. Verify account information</li>
		<li>Step 3. Successful registration</li>
	  </ul>
	</div>
  </div>
</section>
<div class="clear"></div>
<section>
  <div class="wrapper">
   <?php if($logged_in):?>
			<div class="register_prompt_box">
				<div>
					<strong>
						<p>You are currently signed-in. </p>
						<p>If you wish to register another user, <a href='<?=base_url()?>login/logout?referrer=register'>click here.</a> </p>
					</strong>
				</div>
			</div>
			<div class="clear"></div>
   <?php else: ?>
		<form method="post" id="register_form1" autocomplete="off">
	  <div class="fill_out_form">
		  <div class="field">
            <label for="username">Username:</label>
            <input id="username" name="username" type="text" maxlength="25" autocomplete="off" value="<?php echo $reg_username?>"/>
			<div id="username_status" style="display:inline;">
				<img src="<?=base_url()?>/assets/images/check_icon.png" id="username_check" style="position: relative;display:none;vertical-align:middle"/>
				<img src="<?=base_url()?>/assets/images/x_icon.png" id="username_x" style="position: relative;display:none;vertical-align:middle"/>
				<span class="username_availability"></span>
			</div>
			<span class="red ci_form_validation_error"><?php echo form_error('username'); ?></span>
          </div>
          <div class="username_info">
              The username must be 5-25 characters long.
          </div>

          <div class="field">
            <div class="password_label">
              <label for="password">Password:</label>
              <input id="password" name="password" type="password" maxlength="25" />
            </div>
			<span class="red ci_form_validation_error"><?php echo form_error('password'); ?></span>
          </div>
          <div class="clear"></div>
          <div class="password_info">
               Passwords must be within 6-25 characters, and include numbers and upper-case and lower-case letters.
          </div>
          <div class="field">
            <label>Confirm Password:</label>
            <input id="cpassword" name="cpassword" type="password" maxlength="100" disabled="true">
			<img src="<?=base_url()?>/assets/images/check_icon.png" id="cpassword_check" style="position: relative;display:none;"/>
			<img src="<?=base_url()?>/assets/images/x_icon.png" id="cpassword_x" style="position: relative;display:none; "/>
			<span class="red ci_form_validation_error"><?php echo form_error('cpassword'); ?></span>
          </div>
          <div class="field">
            <label><span>Captcha: </span></label>
            <input id="captcha_word" type="text" size="6" maxlength="6" name="captcha_word"/>
			<span id = "captcha_img" style="position: relative; top:10px"><?php echo $image?></span>
			<img src="<?=base_url()?>/assets/images/imgrefresh.png" id="captcha_refresh" style="position: relative; top:5px">
			<img src="<?=base_url()?>/assets/images/bx_loader.gif" id="captcha_loading" style="position: relative; top:10px; left:20px; display:none"/>
			<span class="red ci_form_validation_error"><?php echo form_error('captcha_word'); ?></span>
          </div>
		  <div class="field" style="padding-top:25px">
			<input type="submit" id="register_page1" name="register_page1" value="Agree and Continue"/>
			<img src="<?=base_url()?>/assets/images/bx_loader.gif" id="register_form1_loadingimg" style="position: relative; top:10px; left:20px; display:none"/>
		  </div>

	  </div>
	</form>
   <?php endif; ?>
   
   
  </div>
</section>

<!-- password strength checker -->
<?php if(!$logged_in): ?>
	<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/mootools-core-1.4.5-full-compat.js"></script> 
	<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/password_meter.js"></script>
<?php endif; ?>