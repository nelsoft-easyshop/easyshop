
<div class="clear"></div>
<section>
	<div class="wrapper"> <span class="reg_title">Change Password</span></div>
</section>
<div class="clear"></div>
<section>
      <div class="wrapper">
        <?php if($logged_in):?>
        <!--<form method="post" id="changepass" autocomplete="off">-->
		<?php
			$attr = array('id'=>'changepass', 'autocomplete'=>'off');
			echo form_open('',$attr);
		?>
          <div class="fill_out_form">
				<?php if ($toggle_view == "1"): ?>
                    <div class="field">
                        <label for="cur_password">Current Password:</label>
                        <input id="cur_password" name="cur_password" type="password" maxlength="25"/>
                        <div id="username_status" style="display:inline;">
                            <span class="username_availability error red"></span>
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
                    	Passwords must be within 6-25 characters, and include numbers and letters. 
                    </div>
                    <div class="field">
                          <label>Confirm New Password:</label>
                          <input id="cpassword" name="cpassword" type="password" maxlength="100" disabled="true">
                          <img src="<?php echo getAssetsDomain() ?>assets/images/check_icon.png" id="cpassword_check" style="position: relative;display:none;"/>
                          <img src="<?php echo getAssetsDomain() ?>assets/images/x_icon.png" id="cpassword_x" style="position: relative;display:none; "/>
                          <span class="red ci_form_validation_error"><?php echo form_error('cpassword'); ?></span>
                          <input id="wsx" name="wsx" type="hidden" value="<?php echo $user["username"]; ?>"/>
                    </div>
                    <div class="field">
						<br />
                        <span class="red ci_form_validation_error" style="margin-left: 150px;">
                            <strong><?php echo ($err == 69)?'The account password you entered is incorrect.':''?></strong>
                        </span>
                    </div>
                    
                    
                    <div class="clear"></div>
                    <div class="field" style="padding-top:0px">
                          <input type="submit" name="changepass_btn" value="Save"/>
                    </div>        
					<input type="hidden" value="<?php echo strtolower($user["username"]);?>" id="changepass_username">
                <?php elseif($toggle_view == ""): ?>
                    <strong>
						Password successfully updated. <br/><a href='/me' style="color: #0191C8">Click here </a> to return to your page.
					</strong>
				<?php endif; ?>            
          </div>
        <?php echo form_close();?>
        <?php else: ?>
			<div class="register_prompt_box">
			  <div>
				<strong>
					<p>You are not currently signed-in. </p>
					<p><a href='/login'>Click here to sign-in.</a> </p>
				</strong> 
			  </div>
			</div>
			<div class="clear"></div>
        <?php endif; ?>
      </div>
</section>

<script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.validate.js'></script>
<script type='text/javascript' src='/assets/js/src/register.js?ver=<?=ES_FILE_VERSION?>'></script>

<!-- password strength checker -->
<?php if(($logged_in)&&($toggle_view == "1")):?>
	<script type="text/javascript" src="/assets/js/src/vendor/mootools-core-1.4.5-full-compat.js"></script> 
    <script type="text/javascript" src="/assets/js/src/vendor/password_meter.js"></script>
<?php endif; ?>

