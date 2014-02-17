<?php 
    error_reporting(0);
	switch ($toggle_view){
		case "1":
			$msg = $this->lang->line('msg1');
			break;
		case "2":
			$msg = $this->lang->line('msg2');
			break;
		case "3":
			$msg = $this->lang->line('msg3');
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
