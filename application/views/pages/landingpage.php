<!DOCTYPE html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="<?=base_url()?>assets/css/landingpage/bootstrap.css" rel="stylesheet">
        <link href="<?=base_url()?>assets/css/landingpage/bootstrap-responsive.css" rel="stylesheet">
        <link href="<?=base_url()?>assets/css/landingpage/mystyle.css" rel="stylesheet">
		<link href="<?=base_url()?>assets/css/jquery-ui.css" rel="stylesheet">
		<title><?php echo $title;?></title>
    </head>
    <body>
        <div class = "container header_bg">
            <div class="row-fluid">
                <div id="himg" class="text-center"> <img src="<?=base_url()?>assets/images/landingpage/img_logo.png"> </div>
               
                    <p class="text-center">ONLINE SHOPPING</p>                 
                    <p class="text-center">MADE <span>EASY</span></p>
               
                   <p class="text-center header_sub">
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut
                    </p>
               
            </div>
        </div>
        <div class = "container content1">
            <div class="row-fluid">
                <div class="row-fluid">
                        <span class="spn48">REGISTER</span>
                        <div class="row-fluid">
								<?php echo form_open('', array('id'=>'register_form1'));?>
									<fieldset>

											<h4>Username</h4>
											<input type="text" placeholder="" id="username" name="username" class="reqfield">
											<input type="hidden" id="usernamecheck" value="" name="usernamecheck">
											<span class="red ci_form_validation_error"><?php echo form_error('username'); ?></span>
											<img id="username_loader" class="img_loader_small" src="<?=base_url()?>/assets/images/orange_loader_small.gif" style="display:none;">
											<div id="username_status">
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/check_icon.png" id="username_check" style="position: relative;display:none;vertical-align:middle"/>
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/x_icon.png" id="username_x" style="position: relative;display:none;vertical-align:middle"/>
												<span class="username_availability"></span>
											</div>
											<span class="help-block spnmsg">The username must be 5-25 characters long.</span>
									
										
										
											<h4>Password</h4>
											<input type="password" placeholder="" id="password" name="password" class="reqfield">
											<span class="red ci_form_validation_error"><?php echo form_error('password'); ?></span>
											<span class="help-block spnmsg">Password must be alphanumeric with a minimum of 6 characters.</span>
										
										
										
											<h4>Confirm Password</h4>
											<input type="password" placeholder="" id="cpassword" name="cpassword" class="reqfield" disabled>
											<img class="fieldstatus" src="<?=base_url()?>/assets/images/check_icon.png" id="cpassword_check" style="position: relative;display:none;"/>
											<img class="fieldstatus" src="<?=base_url()?>/assets/images/x_icon.png" id="cpassword_x" style="position: relative;display:none; "/>
											<span class="red ci_form_validation_error"><?php echo form_error('cpassword'); ?></span>
											<span class="help-block spnmsg" style="text-align:left"></span>
										
										
											<h4>Email Address</h4>
											<input type="text" placeholder="" id="email" name="email" class="reqfield">
											<input type="hidden" id="emailcheck" value="">
											<img id="email_loader" class="img_loader_small" src="<?=base_url()?>/assets/images/orange_loader_small.gif" style="display:none;">
											<div id="email_status">
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/check_icon.png" id="email_check" style="position: relative;display:none;vertical-align:middle"/>
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/x_icon.png" id="email_x" style="position: relative;display:none;vertical-align:middle"/>
												<span class="email_availability"></span>
											</div>
											<span class="red ci_form_validation_error"><?php echo form_error('email'); ?></span>
											<span class="help-block spnmsg"></span>
										
											<h4>Mobile Number<h4>
											<input type="text" placeholder="e.g. 9051234567" name="mobile" class="reqfield" id="mobile" maxlength="10">
											<span class="red ci_form_validation_error"><?php echo form_error('mobile'); ?></span>
											<span class="help-block spnmsg"></span>
											
											<span>By registering to Easyshop.ph, you agree to comply with our Terms and Conditions</span>
											
										<!--<button type="button" class="btn btn-warning btn-large">SEND</button>-->
										<input type="submit" class="btn btn-warning btn-large" value="SEND" name="register_form1" id="register_form1_btn">
										<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" class="img_loader_small2" id="register_form1_loadingimg" style="display:none"/>
									</fieldset>

									<p class="span6 border1"></p>

								<?php echo form_close();?>

								<div class="input-append">
								<?php echo form_open('', array('id'=>'subscription_form'));?>
									<h4>Stay in touch to get the latest updates</h4>
									<input class="subscribe_input" id="appendedInputButton" type="text" name="subscribe_email">
									<!--<button class="btn btn-warning btn-large" type="button">Subscribe</button>-->
									<input type="submit" class="btn btn-warning btn-large" value="Subscribe" name="subscribe_btn" id="subscribe_btn">
									<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" id="subscribe_loadingimg" style="position: relative; top:10px; left:20px; display:none"/>
								<?php echo form_close();?>
								</div>
                        </div>                
                                
                </div>            
            </div>
        </div>
		
		<div id="register_result" style="display:none;">
			<span id="result_desc"></span>
		</div>
		
        <div class = "footer">
            <div class="container content2">
                <div class="row-fluid">
                    <div class="row-fluid">
                        <div class="span12" style="text-align:center">
                            <span class="spn48">WE MAKE SHOPPING EASY</span>
                            <div class="row-fluid">
                                <div class="span4">
                                    <img src="<?=base_url()?>assets/images/landingpage/img_online_shopping.png"><br>
                                    <span class="spn18"><b>Online Shopping</b></span><br>
                                    <span class="help-block spnmsg">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, <br>
                                    sed diam nonummy nibh euismod tincidunt ut laoreet dolore<br>
                                    magna aliquam erat volutpat. Ut wisi enim ad minim veniam</span>
                                </div>
                                <div class="span4">
                                    <img src="<?=base_url()?>assets/images/landingpage/img_paperless_payment.png"><br>
                                    <span class="spn18"><b>Paperless Payment</b></span>
                                    <span class="help-block spnmsg">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, <br>
                                    sed diam nonummy nibh euismod tincidunt ut laoreet dolore<br>
                                    magna aliquam erat volutpat. Ut wisi enim ad minim veniam</span>
                                </div>
                                <div class="span4">
                                    <img src="<?=base_url()?>assets/images/landingpage/img_shipping_delivery.png"><br>
                                    <span class="spn18"><b>Item Delivery</b></span>
                                    <span class="help-block spnmsg">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, <br>
                                    sed diam nonummy nibh euismod tincidunt ut laoreet dolore<br>
                                    magna aliquam erat volutpat. Ut wisi enim ad minim veniam</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="footer2">
            <div class="container">
                <span class="help-block spnmsg"><br>Copryright &#169; 2014 easyshop.ph. All rights reserved<br></span>
            </div>
        </div>
    </body>

	<script>
		var config = {
			 base_url: "<?php echo base_url(); ?>",
		};
	</script>
	
	<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery-1.9.1.js" ></script>
	<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery-ui.js"></script>
	<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/landingpage/bootstrap.min.js'></script>
	
	<!-- Form Plugins -->
	<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.numeric.js'></script>
	<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.validate.js'></script>
	
	<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/landingpage/landingpage.js'></script>
	
	<!-- Password Strength -->
	<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/mootools-core-1.4.5-full-compat.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/password_meter.js"></script>
	
	
	