<?php 
    error_reporting(0);
	switch ($toggle_view){
		case "1":
			$msg = "Password reset successfully sent!";
			break;
		case "2":
			$msg = "Sorry, the email you provided is unregistered. ";
			break;
		case "3":
			$msg = "Unable to send, please try again later. ";
			break;							
		default:
			$msg = form_error('email');
	}
?> 

<div class="clear"></div>
<div class="wrapper login_content">
  <form id="identify_form" method="post">
    <div class="login_box">
      <div>
        <label for="email">Email Address: </label>
        <input type="text" id="email" name="email">
      </div>
      <div>
        <input id="identify_btn" type="submit" name="identify_btn" value="Reset Password"/>
      </div>
      <span class="red" id="login_error"><center><?php echo $msg; ?></center></span>
    </div>
  </form>
</div>
</section>
<div class="clear"></div>

<script src="<?=base_url().$page_javascript?>" type="text/javascript"></script>
