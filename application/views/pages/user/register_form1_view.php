
<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery-ui.css" type="text/css" media="screen"/>


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
            <label for="username"><strong>Username:</strong></label>
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
              <label for="password"><strong>Password:</strong></label>
              <input id="password" name="password" type="password" maxlength="25" />
            </div>
			<span class="red ci_form_validation_error"><?php echo form_error('password'); ?></span>
          </div>
          <div class="clear"></div>
          <div class="password_info">
               Password must be alphanumeric with a minimum of 6 characters.
          </div>
		  
          <div class="field">
            <label><strong>Confirm Password:</strong></label>
            <input id="cpassword" name="cpassword" type="password" maxlength="100" disabled="true">
			<img src="<?=base_url()?>/assets/images/check_icon.png" id="cpassword_check" style="position: relative;display:none;"/>
			<img src="<?=base_url()?>/assets/images/x_icon.png" id="cpassword_x" style="position: relative;display:none; "/>
			<span class="red ci_form_validation_error"><?php echo form_error('cpassword'); ?></span>
          </div>
		  
		  <div class="field">			
				<label for="email"><strong>Email Address:</strong></label>
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
			<label for="terms_checkbox" id="terms"><strong>I agree to Easyshop.ph's <span class='terms_and_conditions'>Terms and Conditions.</span></strong></label>
			<span class="red ci_form_validation_error"><?php echo form_error('terms_checkbox'); ?></span>
		</div>
        
    
		  
		  <div class="field" style="padding-top:25px">
			<input type="submit" id="register_page1_btn" value="Register"/>
            <input type="hidden"  name="register_page1" value="register_page1"/>
            <img src="<?=base_url()?>/assets/images/orange_loader_small.gif" id="register_form1_loadingimg" style="position: relative; left:20px; display:none"/>
		  </div>
		  
	  </div>
	<?php echo form_close();?>
   <?php endif; ?>
  </div>
  
      <div class="dialog t_and_c">
            <h3>TERMS &amp; CONDITIONS</h3>
            <p> 
                Welcome to Easyshop.ph, a company under the group of Nelsoft Technologies Inc. 
                Before using the Easyshop.ph website, services or tools, you must agree to the 
                following terms general rules to abide as provided by this Website. Should there 
                be any enquiries or inquisitions upon the said terms conditions- kindly contact 
                us directly or refer to our customer service section. The purpose of this document 
                is to outline the acceptance of the terms and conditions laid out between 
                Easyshop.ph and merchant will to list its products on Easyshop.ph. All the terms 
                and conditions are valid and final.
            </p>
            <h3>Creating Your Account</h3>
            <p>
                Upon creating your account, you are fully responsible for your account 
                password- should there be any improper activities occurring under your account, 
                it is your direct responsibility, Easyshop.ph reserves the right to investigate, 
                remove, or withhold your information. Our age policy only requires that a minor 
                under the age of 18 should only use the Website under the authorization of a 
                parent or a legal guardian. 
            </p>
            <h3>Listing Products (Seller Registration)</h3>
            <p>
                The seller is fully responsible for the Listing. By Listing on the website 
                of Easyshop.ph, the seller confirms warrants that you the valid owner of 
                Products and has the right to sell the Products online in the Philippines.
            </p>		
            <h3>Information Submitted By User</h3>	
            <p> Any INFORMATION submitted to the Site will become our property. You shall 
                not submit false e-mail address, pretend to be someone other than yourself. 
            </p>
            <h3>Responsibility Of Buyers</h3>
            <p>
                Easyshop.ph may, remove Product and/or Account containing Product that is 
                unlawful, offensive, threatening, libelous, defamatory, pornographic, 
                obscene or otherwise objectionable or violates any party’s intellectual 
                property or this Agreement.
            </p>
            <h3>Intellectual Property Rights</h3>
            <p>
                All intellectual property rights, whether registered or unregistered, in the 
                Site, information content on the Site and all the website design, including, 
                but not limited to, text, graphics, software, photos, video, music, sound, 
                and their selection and arrangement, and all software compilations, 
                underlying source code and software shall remain our property. The entire 
                contents of the Site also are protected by copyright as a collective work 
                under Philippine copyright laws and international conventions. All rights 
                are reserved.
            </p>	
            <h3>Termination</h3>
            <p>
                The site reserves the right to terminate the Terms and Conditions which in 
                effect, requires you to stop all access to and use of this Site. Such 
                termination shall not affect though, the rights and payment obligations, of 
                the parties arising before the date of termination. 
            </p>	
        </div>
  
</section>

<!-- password strength checker -->
<?php if(!$logged_in): ?>
	<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/mootools-core-1.4.5-full-compat.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/password_meter.js"></script>
<?php endif; ?>

<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.numeric.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.validate.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/register.js?ver=1.0'></script>
