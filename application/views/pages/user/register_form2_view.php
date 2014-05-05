
<div id="register_form2_view">

<div class="clear"></div>

<section>
  <div class="wrapper">
	<span class="reg_title">Verify account information</span>
   </div>
</section>
<section>
  <div class="wrapper">
	<div class="reg_steps reg_step2">
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
	<div><strong>You can complete the registration by verifying either your mobile number or your e-mail address.</strong></div>
	<div class="fill_out_form">	

		<!--<form method="post" id="register_form2_a" name="register_form2_a">-->
		<?php 
			$attr = array('id'=>'register_form2_a','name'=>'register_form2_a');
			echo form_open('',$attr);
		?>
			<div class="field">
					<label for="register_region">Region:</label>
					<select id="register_region" name="register_region">
					  <option>NCR</option>
					  <option>Ilocos Region</option>
					  <option>Cagayan Valley</option>
					  <option>Central Luzon</option>
					  <option>Bicol Region</option>
					  <option>Western Visayas</option>
					  <option>Central Visayas</option>
					  <option>Eastern Visayas</option>
					  <option>Zambaonga Peninsula</option>
					  <option>Davao Region</option>
					  <option>Caraga</option>
					  <option>CALABARZON</option>
					  <option>MIMAROPA</option>
					  <option>SOCCSKSARGEN</option>
					  <option>CAR</option>
					  <option>ARMM</option>
					  <option>Others</option>
					</select>
			</div>
			<div class="field">
					<label for="register_mobile">Mobile Number:</label>
					<input type="text" name="register_mobile" class="contactinfo" id="register_mobile" maxlength="11">
					<span class="red ci_form_validation_error"><?php echo form_error('register_mobile'); ?></span>
					<label for="cregister_mobile">Confirm Mobile Numer:</label>
					<input type="text" name="cregister_mobile" id="cregister_mobile" maxlength="11" disabled="true" autocomplete="off">
					<img src="<?=base_url()?>/assets/images/check_icon.png" id="cregister_mobile_check" style="position: relative; display:none;"/>
					<img src="<?=base_url()?>/assets/images/x_icon.png" id="cregister_mobile_x" style="position: relative; display:none;"/>
					<span class="red ci_form_validation_error"><?php echo form_error('cregister_mobile'); ?></span>
			</div>
			
			<div class="filler"><span class="error red" id="err_mobilespan">Mobile No. already used.</span></div>
		
			<div class="filler"><label><strong>OR</strong></label></div>
		
			<div class="field">			
					<label for="register_email">Email Address:</label>
					<input type="text" name="register_email" class="contactinfo" id="register_email" maxlength="40">
					<span class="red ci_form_validation_error"><?php echo form_error('register_email'); ?></span>
					<label for="cregister_email">Confirm Email Address:</label>
					<input type="text" name="cregister_email" id="cregister_email" disabled="true" maxlength="40" autocomplete="off">
					<img src="<?=base_url()?>/assets/images/check_icon.png" id="cregister_email_check" style="position: relative; display:none;"/>
					<img src="<?=base_url()?>/assets/images/x_icon.png" id="cregister_email_x" style="position: relative; display:none;"/>
					<span class="red ci_form_validation_error"><?php echo form_error('cregister_email'); ?></span>
			</div>

			<div class="filler"><span class="error red" id="err_emailspan">Email already used.</span></div>

			<div class="field" id="tc">
				<input type="checkbox" name="terms_checkbox" id="terms_checkbox">
				<label for="terms_checkbox" id="terms">I agree to Easyshop.ph's Terms and Conditions.</label>
				<span class="red ci_form_validation_error"><?php echo form_error('terms_checkbox'); ?></span>
			</div>
			<input type="submit" class="verify" name="register_form2_a_btn" value="submit" id="register_form2_a_btn">
			<strong><span class="countdown_submit"></span></strong>
			<img src="<?=base_url()?>/assets/images/bx_loader.gif" id="register_loading" style="position: relative; top:12px; left:15px; display:none"/>
		<!--</form>	  -->
		<?php echo form_close();?>
		
		<!-- GENERIC success modal content -->
		<div id="verification-content">
			<h3>EASYSHOP.PH - Registration Complete</h3>
			  <div class="verification-inner-content">
			  	<span><strong>Thank you for registering. You may now <a href="<?=base_url()?>login" style="color:blue;">login</a></strong></span>
			  	<br>
				<span class="verification-span">Verification code sent to:</span>
				<span class="verification-span-error">Failed to send verification code.</span>
				<div class="verification-msg"></div>

				
					<div class="verification_field_mobile">
						<!--<form method="post" id="register_form2_b" name="register_form2_b">-->
						<?php
							$attr = array(
										'id' => 'register_form2_b',
										'name' => 'register_form2_b'
									);
							echo form_open('',$attr);
						?>
							<div class="verification_field_mobile_set">
								<label for="verification_code">Mobile Verification Code:</label>
								<input id="verification_code" name="verification_code" type="text" maxlength="6" />
								<span class="red ci_form_validation_error"><?php echo form_error('verification_code'); ?></span>
								<div class="verification_info">
									 Please enter your 6 digit mobile verification code
								</div>
								<div id="verification_code_error">Invalid confirmation code</div>
								<input type="submit" class="submit_verification" value="Verify" name="register_form2_b_btn" id="register_form2_b_btn"/>
							</div>
							<p class="mobilestat"></p>
							<p class="mobilestat-success statresult">
							  If you do not receive an SMS verification code within 60 seconds, please recheck your mobile number
							</p>
							<p class="mobilestat-error statresult">
							  <span class="staterror">Sorry, we failed to send your mobile verification code to: %%</span>
							</p>
							<p class="mobilestat-exceed statresult">
							  <span class="staterror">You have exceeded the number of times to verify your mobile. Try again after 30 mins.</span>
							</p>
						<?php echo form_close(); ?>
					</div>
				<!--
					<div class="or_separator" style="display:none">
						<p>
							<strong>OR</strong>
						</p>
					</div>
				-->

					<div class="verification_field_email">
						<p class="emailstat"></p>
						<p class="emailstat-success statresult">
							Email confirmation sent. Please check your email and click the link provided to verify your mail.<br>
							<a href="http://%%" style="color:blue;" target="_blank">Click here to go to %%</a>
						</p>
						<p class="emailstat-error statresult">
							<span class="staterror">Sorry, we failed to send your email verification code to: %%</span>
						</p>
						<p class="emailstat-exceed statresult">
							<span class="staterror">You have exceeded the number of times to verify your email. Try again after 30 mins.</span>
						</p>
					</div>
			  </div>
		</div>  
			
		<input type="hidden"  name="register_page2" value="you can't see me" />
	</div>
  </div>
</section>

</div>


<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.simplemodal.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.numeric.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.validate.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/register.js?ver=1.0'></script>
