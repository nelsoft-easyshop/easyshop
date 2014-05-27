<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Easyshop.com - Homepage</title>
        <meta name="description" content="" />
        <meta name="keywords" content=""/>
        <meta name="viewport" content="width=device-width">
		<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon"/>

		<!-- CSS -->
        <link rel="stylesheet" href="<?=base_url()?>assets/css/landingpage/style_v2.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="<?=base_url()?>assets/css/landingpage/rwdgrid.css" type="text/css" media="screen"/>

        <!-- start responsive menu -->
        <link rel="stylesheet" href="<?=base_url()?>assets/css/landingpage/responsive-nav.css">

		<!-- Contact Form CSS files -->
		<link type='text/css' href='<?=base_url()?>assets/css/basic.css' rel='stylesheet' media='screen' />
		<link href="<?=base_url()?>assets/css/jquery-ui.css?ver=1.0" rel="stylesheet">
        <!-- JS -->
		<!-- html5.js for IE less than 9 -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- css3-mediaqueries.js for IE less than 9 -->
		<!--[if lt IE 9]>
			<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
		<![endif]-->

		<!-- fonts -->
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
</head>
<body>
	<header>
		<div class="container-16">
				<div class="grid-4">
					<a href="<?=base_url()?>home"><span class="span_bg logo"></span></a>
				</div>
				<div class="nav_container">
					<nav class="nav-collapse">
					  <ul>
					    <li class="grid-1"><a href="#">Shop</a></li>
					    <li class="grid-1"><a href="#">Sell</a></li>
					    <li class="btn_login">
					    	<input type="text">
					    	<input type="text">
					    	<a href="#" class="btn">Login</a>
					    </li>
					    <li class="btn_register">
					    	<span class="btn reg_btn" id="reg_btn">Register</span>
					    </li>
					  </ul>
					</nav>
				</div>		
				
		</div>
		<div class="register_container container-16" id="register_container">
			<div class="grid-6">
					    		<?php echo form_open('', array('id'=>'register_form1'));?>
									<fieldset>

											<h4>Username</h4>
											<input type="text" placeholder="" id="username" name="username" class="reqfield" autocomplete="off"/>
											<input type="hidden" id="usernamecheck" value="" name="usernamecheck">
											<span class="red ci_form_validation_error"><?php echo form_error('username'); ?></span>
											<img id="username_loader" class="img_loader_small" src="<?=base_url()?>/assets/images/orange_loader_small.gif" style="display:none;">
											<div id="username_status">
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/check_icon.png" id="username_check" style="position: relative;display:none;vertical-align:middle"/>
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/x_icon.png" id="username_x" style="position: relative;display:none;vertical-align:middle"/>
												<span class="username_availability"></span>
											</div>
											<span class="help-block spnmsg text-center padding1">The username must be 5-25 characters long.</span>
									
										
										
											<h4>Password</h4>
											<input type="password" placeholder="" id="password" name="password" class="reqfield">
											<span class="red ci_form_validation_error"><?php echo form_error('password'); ?></span>
											<span class="help-block spnmsg text-center padding1">Password must be alphanumeric with a minimum of 6 characters.</span>
										
										
										
											<h4>Confirm Password</h4>
											<input type="password" placeholder="" id="cpassword" name="cpassword" class="reqfield" disabled>
											<img class="fieldstatus" src="<?=base_url()?>/assets/images/check_icon.png" id="cpassword_check" style="position: relative;display:none;"/>
											<img class="fieldstatus" src="<?=base_url()?>/assets/images/x_icon.png" id="cpassword_x" style="position: relative;display:none; "/>
											<span class="red ci_form_validation_error"><?php echo form_error('cpassword'); ?></span>
											<span class="help-block spnmsg padding1" style="text-align:left"></span>
										
										
											<h4>Email Address</h4>
											<input type="text" placeholder="" id="email" name="email" class="reqfield" autocomplete="off">
											<input type="hidden" id="emailcheck" value="">
											<img id="email_loader" class="img_loader_small" src="<?=base_url()?>/assets/images/orange_loader_small.gif" style="display:none;">
											<div id="email_status">
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/check_icon.png" id="email_check" style="position: relative;display:none;vertical-align:middle"/>
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/x_icon.png" id="email_x" style="position: relative;display:none;vertical-align:middle"/>
												<span class="email_availability"></span>
											</div>
											<span class="red ci_form_validation_error"><?php echo form_error('email'); ?></span>
											<span class="help-block spnmsg padding1"></span>
										
											<h4>Mobile Number</h4>
											<input type="text" placeholder="e.g. 9051234567" name="mobile" class="reqfield" id="mobile" maxlength="10">
											<input type="hidden" id="mobilecheck" value="">
											<img id="mobile_loader" class="img_loader_small" src="<?=base_url()?>/assets/images/orange_loader_small.gif" style="display:none;">
											<div id="mobile_status">
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/check_icon.png" id="mobile_check" style="position: relative;display:none;vertical-align:middle"/>
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/x_icon.png" id="mobile_x" style="position: relative;display:none;vertical-align:middle"/>
												<span class="mobile_availability"></span>
											</div>
											<span class="red ci_form_validation_error"><?php echo form_error('mobile'); ?></span>
											<span class="help-block spnmsg padding1"></span>
											<h4></h4>
											
											
										<!--<button type="button" class="btn btn-warning btn-large">SEND</button>-->
										<input type="submit" class="btn btn_send" value="SEND" name="register_form1" id="register_form1_btn" >
										<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" class="img_loader_small2" id="register_form1_loadingimg" style="display:none"/>
									
										<p class="terms_con padding1 padding-t1">By registering to Easyshop.ph, you agree to comply with our <span class="terms_and_conditions">Terms and Conditions</span></p>
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
									</fieldset>

									<p class="span6 border1"></p>

								<?php echo form_close();?>
			</div>					
		</div>
	</header>

	<div class="clear"></div>
	<section class="bg_mid">
		<div class="container-16">
			<div class="grid-11 mgrn_top3">
						<h1><span>YOUR</span> BUSINESS</h1>
						<h1><span>HAS A</span>  NEW HOME</h1>
						<p class="fs1 mgrn_top1">
						EasyShop.ph provides fast and easy online shopping experience.
						</p>
						<p class="fs2 mgrn_top2">Start buying on June 15, 2014 </p>
						
						<div class="mgrn_top2">
							<div class="vid_container"><span class="fs1">watch this video </span> 
							<span>
								<img src="<?=base_url()?>assets/images/landingpage/img_video.jpg" class="vidplay" alt="Easyshop.ph Video">
								<span id="videoplayer">									
									<embed src="https://www.youtube.com/v/bA4vWKZSE00" type="application/x-shockwave-flash"></embed>
								</span>
							</span>
							</div>	
						</div>	
	
			</div>
		</div>
	</section>
	
	<div class="clear"></div>
	<footer>
		<div class="container-16">
			<div class="footer">
				<ul>
					<li>
						<a href="">Shop</a>
					</li>
					<li>
						<a href="">Sell</a>
					</li>
					<li>
						<div class="footer_payment">
							Payment Methods:
							<span class="span_bg mastercard"></span>
							<span class="span_bg visa"></span>
							<span class="span_bg paypal"></span>
							<span class="span_bg dragonpay"></span>
							<span class="span_bg cod"></span>
						</div>
					</li>
					<li>
						<div class="social_media">
							Social Media:
							<a href="https://www.facebook.com/EasyShopPhilippines"><span class="span_bg facebook"></span></a>
							<a href="https://twitter.com/EasyShopPH"><span class="span_bg twitter"></span></a>
						</div>
					</li>
				</ul>
				
			</div>
			<div class="clear"></div>
		</div>
		<div class="copyright_content">
				<p>Copyright © 2014 easyshop.ph. All rights reserved </p>
			</div>
	</footer>
</body>
<?php echo form_open('registration/success', array('id'=>'success_register'));?>
		  <input type="hidden" name="referrer" class="referrer" value="landingpage"/>
	 <?php echo form_close();?>




<!-- script for responsive navigation
<script src="<?=base_url()?>assets/JavaScript/js/landingpage/responsive-nav.js"></script> -->

<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery-1.9.1.js" ></script>
<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery-ui.js"></script>


<script type="text/javascript">

$(function() {
	$( "#videoplayer" ).dialog({
		width:"68%",
		autoOpen: false,
		modal: true,
		closeOnEscape: true,
		draggable:false,
	});

	$( ".vidplay" ).click(function() {
	$( "#videoplayer" ).dialog( "open" );
	});
});
/****** Terms and Conditions Dialog box ********/
$(function() {
	$( ".dialog" ).dialog({
		width:"65%",
		autoOpen: false,
		modal: true,
		closeOnEscape: true,
		draggable:false,
	});

	$( ".terms_and_conditions" ).click(function() {
	$( ".dialog" ).dialog( "open" );
	$(".dialog").siblings().parent('.ui-dialog').addClass('terms_container');
	});
});

</script> 
<script type="text/javascript">
$(document).ready(function() {
    $("#reg_btn").click(function() {
            $('#register_container').toggle();
    });
});

$(document).mouseup(function (e)
{
    var container = $("#register_container");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.hide();
    }
});
</script>


<!-- Form Plugins -->
	<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.numeric.js'></script>
	<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.validate.js'></script>
	
	<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/landingpage/landingpage.js?ver=1.0'></script>
	
	<!-- Password Strength -->
	<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/mootools-core-1.4.5-full-compat.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/password_meter.js"></script>