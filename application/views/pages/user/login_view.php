
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
					<div class="login_box">
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
						  <input type="checkbox" name="keepmeloggedin" id="keepmeloggedin">
						  <p><label for="keepmeloggedin">
							Remember me
						  </label></p>
						</div>
						<div>
						  <input id="login" type="submit" name="login_form" value="Login"/>
						  <img src="<?=base_url()?>/assets/images/orange_loader_small.gif" id="loading_img" class="login_loading_img" style="display:none"/>
						  <br>
						  <p>
							<a href="<?=base_url()?>register">Don't have an account? Register now</a>
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
                    <input id="redirect" type="hidden" value="<?php echo $user_cur_loc;?>"/> 
                    
				<?php echo form_close();?>
				
			<?php endif; ?>
      </div>

    </section>
   <div class="clear"></div>

   
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.validate.js'></script>   
<script src="<?=base_url().$page_javascript?>" type="text/javascript"></script>
