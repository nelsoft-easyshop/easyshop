<div id="login_register">
<div class="clear"></div>

      <div class="wrapper login_content">
	
          <div class="login_left_content"><img src="<?=base_url()?>assets/images/img_login_banner.jpg" alt="banner"></div>
			<?php if($logged_in): ?>
				<div class="login_box">
					<div style="text-align:center;">
						<p>You are currently signed-in as <b><?php echo $uname; ?></b>. </p>
						<p>If you wish to sign-in as another user, <a href='<?=base_url()?>login/logout' class="orange2">click here.</a> </p>
					</div>
				</div>
			<?php else: ?>
				<!--<form id="login_form">-->
				<?php
					$attr = array('id'=>'login_form');
					echo form_open('', $attr);
				?>
					<div id="login_div" class="login_box fillup">
						<div>
                            <label for="login_username">Username:</label>
                            <input type="text" id="login_username" name="login_username">
							<span id="username_error" class="red error_cont" style="font-weight:bold;margin-left:8em;"></span>
						</div>
						<div>
						  <label for="login_password">Password:</label>
						  <input type="password" id="login_password" name="login_password"> 
						  <span id="passw_error" class="red error_cont" style="font-weight:bold;margin-left:8em;"></span>
						  <p><a href="<?=base_url()?>login/identify">Forgot your password?</a></p>
						</div>
						<div>
						  <input type="checkbox" name="keepmeloggedin">
						  <p>
							Keep me signed in
						  </p>
						</div>

						<div>
						  <input id="login" type="submit" name="login_form" value="Login"/>
						  <img src="<?=base_url()?>/assets/images/orange_loader_small.gif" id="loading_img" class="login_loading_img" style="display:none"/>
						  <br>
						  <p>
							<!--<a href="<?=base_url()?>register">Don't have an account? Register now</a>-->
							<a class="signup_register">Don't have an account? Register now</a>
						  </p>
						</div>
					</div>
					<div>
						<?php
							/*
							echo 'SERVER: '.$_SERVER['HTTP_REFERER'];
							echo '<br/>';
							$redirect = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url().'home';
							echo 'redirect: '.$redirect.' ';echo '<br/>';
							echo 'currenturl: '.current_url().'<br/>';
							echo 'uri: '.uri_string(); echo '<br/>';
							$redirect = ($redirect!==$_SERVER['PHP_SELF'])?$redirect:base_url().'home'; 
							<input id="login_redirect" type = "hidden" value="<?php echo $redirect;?>"/>
							*/
						?>
					</div>
				<?php echo form_close();?>
				
				
				<!-- Registration Section -->
					<div id="signup_div" class="login_box fillup" style="display:none;">
					<?php
						$attr2 = array('id'=>'register_form1');
						echo form_open('', $attr2);
					?>
						<div class="field">
							<label for="username">Username:</label>
							<input id="username" name="username" type="text" maxlength="25" autocomplete="off" />
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
							<input type="submit" id="register_page1" name="register_page1" value="Agree and Continue"/>
							<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" id="register_form1_loadingimg" style="position: relative; top:10px; left:20px; display:none"/>
							<a class="signup_register">Already have an account? Sign In</a>
						</div>
						
						<?php echo form_close();?>
					</div>
					<!-- END OF Registration Section -->

			<?php endif; ?>
      </div>

    </section>
   <div class="clear"></div>
</div>
   
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.validate.js'></script>   
<script src="<?=base_url().$page_javascript.'?ver=1.0';?>" type="text/javascript"></script>
