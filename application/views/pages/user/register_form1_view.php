
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
		<!--<form method="post" id="register_form1" autocomplete="off">-->
		<?php 
			$attr = array('id'=>'register_form1','autocomplete'=>'off');
			echo form_open('',$attr);
		?>
	  <div class="fill_out_form">
		  <div class="field">
            <label for="username">Username:</label>
            <input id="username" name="username" type="text" maxlength="25" autocomplete="off" value="<?php echo $reg_username?>"/>
			<input type="hidden" id="usernamecheck" value="">
			<img class="check_loader" src="<?=base_url()?>/assets/images/orange_loader_small.gif" style="display:none;">
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
               Password must be alphanumeric with a minimum of 6 characters.
          </div>
		  
          <div class="field">
            <label>Confirm Password:</label>
            <input id="cpassword" name="cpassword" type="password" maxlength="100" disabled="true">
			<img src="<?=base_url()?>/assets/images/check_icon.png" id="cpassword_check" style="position: relative;display:none;"/>
			<img src="<?=base_url()?>/assets/images/x_icon.png" id="cpassword_x" style="position: relative;display:none; "/>
			<span class="red ci_form_validation_error"><?php echo form_error('cpassword'); ?></span>
          </div>
		  
		  <div class="field">			
				<label for="email">Email Address:</label>
				<input type="text" name="email" class="contactinfo" id="email" maxlength="40">
				<input type="hidden" id="emailcheck" value="">
				<img class="check_loader" src="<?=base_url()?>/assets/images/orange_loader_small.gif" style="display:none;">
				<div id="email_status" style="display:inline;">
					<img src="<?=base_url()?>/assets/images/check_icon.png" id="email_check" style="position: relative;display:none;vertical-align:middle"/>
					<img src="<?=base_url()?>/assets/images/x_icon.png" id="email_x" style="position: relative;display:none;vertical-align:middle"/>
					<span class="email_availability"></span>
				</div>
				<span class="red ci_form_validation_error"><?php echo form_error('email'); ?></span>
		  </div>

		<div class="field" id="tc">
			<input type="checkbox" name="terms_checkbox" id="terms_checkbox">
			<label for="terms_checkbox" id="terms">I agree to Easyshop.ph's Terms and Conditions.</label>
			<span class="red ci_form_validation_error"><?php echo form_error('terms_checkbox'); ?></span>
		</div>
		  
		  <div class="field" style="padding-top:25px">
			<input type="submit" id="register_page1_btn" value="Agree and Continue"/>
            <input type="hidden"  name="register_page1" value="register_page1"/>
            <img src="<?=base_url()?>/assets/images/orange_loader_small.gif" id="register_form1_loadingimg" style="position: relative; top:10px; left:20px; display:none"/>
		  </div>
		  
	  </div>
	<?php echo form_close();?>
   <?php endif; ?>
  </div>
</section>

<!-- password strength checker -->
<?php if(!$logged_in): ?>
	<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/mootools-core-1.4.5-full-compat.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/password_meter.js"></script>
<?php endif; ?>

<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.numeric.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.validate.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/register.js'></script>
