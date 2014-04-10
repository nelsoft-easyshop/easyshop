<?php 
    error_reporting(0);
	switch ($toggle_view){
		case "1":
			$msg = $this->lang->line('msg1');
			$class = "error1";
			$imgclass = "img_error1";
			break;
		case "2":
			$msg = $this->lang->line('msg2');
			$class = "error2";
			$imgclass = "img_error2";
			break;
		case "3":
			$msg = $this->lang->line('msg3');
			$class = "error3";
			$imgclass = "img_error3";
			break;							
		default:
			$msg = form_error('email');
	}
?> 

<div class="clear"></div>
<div class="wrapper login_content forgot_pword_con">
	<div class="forgot_pword_inner_con">
		<span class="reg_title">Forgot Password</span>
	  <!--<form id="identify_form" method="post">-->
	  <?php
		$attr = array('id'=>'identify_form');
		echo form_open('',$attr);
	  ?>
	    <div class="reset_pword">
	      <div>
	        <label for="email" class="f14">Email Address: </label>
	        <input type="text" id="email" name="email">
	      </div>
	      <div class="">
	        <input id="identify_btn" class="orange_btn3" type="submit" name="identify_btn" value="Reset Password"/>
	      </div>
		  <?php if(!empty($toggle_view)){ ?>
			  <div class="reset_pword_error_con <?php echo $class; ?>">
			  <span class="span_bg img_error_con <?php echo $imgclass; ?>"></span>
			  <span id="login_error"><?php echo $msg; ?></span>
	      </div>
		  <?php } ?>
	    </div>
	  <?php echo form_close();?>
	</div>
</div>
</section>
<div class="clear"></div>

<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.numeric.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.validate.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/register.js'></script>
