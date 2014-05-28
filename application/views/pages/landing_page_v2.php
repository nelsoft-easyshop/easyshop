<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Easyshop.com - Homepage</title>
        <link rel="shortcut icon" href="<?php echo base_url()?>assets/images/favicon.ico" type="image/x-icon"/>
        <meta property="og:title" content="EasyShop.ph" />
		<meta property="og:description" content="Our vision is to be the leading Online Shopping website in South East Asia. 
           The mission that EasyShop has is to provide its customer with a Fast and Easy 
           online shopping experience. Our goal is to be the first website you think about
           when buying online." />
		<meta property="og:image" content="https://easyshop.ph/assets/images/img_logo.png" />
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->

		<!-- CSS -->
        <link rel="stylesheet" href="<?=base_url()?>assets/css/landingpage/style_v2.css" type="text/css" media="screen"/>
<!--         <link rel="stylesheet" href="<?=base_url()?>assets/css/landingpage/rwdgrid.css" type="text/css" media="screen"/> -->


		<!-- Contact Form CSS files -->
		<link type='text/css' href='<?=base_url()?>assets/css/basic.css' rel='stylesheet' media='screen' />
		<link href="<?=base_url()?>assets/css/jquery-ui.css?ver=4.10" rel="stylesheet">
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

	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=154815247949100&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


	<header id="header" class="">
		<div class="container-16">
				<div class="grid-4">
					<a href="<?=base_url()?>home"><span class="span_bg logo"></span></a>
				</div>
				<div class="nav_container">
					<nav class="nav-collapse">
					  <ul>
                        <li class="grid-1"><a href="<?=base_url()?>home">Shop</a></li>
					    <li class="grid-1"><a href="<?=base_url()?>sell/step1">Sell</a></li>
                        <?php if(!$logged_in): ?>
                            <li class="btn_login">
                                <?php echo form_open('login');?>
                                    <input type="text" placeholder='Username' name='login_username'>
                                    <input type="password" placeholder='Password' name='login_password'>
                                    <input type="submit" class='btn' value='Login' name='login_form'/>
                                <?php echo form_close();?>
                            </li>
                            <li class="btn_register">
                                <span class="btn reg_btn" id="reg_btn">Register</span>
                            </li>
                        <?php else: ?>
                            <li class='btn_login' style=''>   
                                <a href='<?=base_url()?>me'><input style='overflow: hidden; text-overflow: ellipsis; max-width: 120px;' type="submit" class='btn' id='userpage' value = "<?php echo html_escape($uname);?>"/></a>
                            </li>
                            <li class='btn_register'>   
                                <a href='<?=base_url()?>login/logout'><span class="btn" id="sign_out">Sign-out</span></a>
                            </li>
                        <?php endif; ?>    
                        
                        
					  </ul>
					</nav>
				</div>		
				
		</div>
		<div class="register_container container-16" id="register_container">
			<div class="grid-6">
					    		<?php echo form_open('', array('id'=>'register_form1'));?>
									<fieldset>

											<h4>Username</h4>
											<input maxlength='25' type="text" placeholder="" id="username" name="username" class="reqfield" autocomplete="off"/>
											<input  type="hidden" id="usernamecheck" value="" name="usernamecheck">
											<span class="red ci_form_validation_error"><?php echo form_error('username'); ?></span>
											<div id="username_status">
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/check_icon.png" id="username_check" style="position: relative;display:none;vertical-align:middle"/>
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/x_icon.png" id="username_x" style="position: relative;display:none;vertical-align:middle"/>
												<span class="username_availability"></span>
											</div>
											
											<h4>Password</h4>
											<input type="password" placeholder="" id="password" name="password" class="reqfield">
											<span class="red ci_form_validation_error"><?php echo form_error('password'); ?></span>

											<h4 class="txt_cp">Confirm Password</h4>
											<input type="password" placeholder="" id="cpassword" name="cpassword" class="reqfield" disabled>
											<span class="field_pword_status">
											<img class="fieldstatus" src="<?=base_url()?>/assets/images/check_icon.png" id="cpassword_check" style="position: relative;display:none;"/>
											<img class="fieldstatus" src="<?=base_url()?>/assets/images/x_icon.png" id="cpassword_x" style="position: relative;display:none; "/>
											</span>
											<span class="red ci_form_validation_error"><?php echo form_error('cpassword'); ?></span>
											<span class="help-block spnmsg padding1" style="text-align:left"></span>
										
										
											<h4>Email Address</h4>
											<input type="text" placeholder="" id="email" name="email" class="reqfield" autocomplete="off">
											<input type="hidden" id="emailcheck" value="">
											<div id="email_status">
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/check_icon.png" id="email_check" style="position: relative;display:none;vertical-align:middle"/>
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/x_icon.png" id="email_x" style="position: relative;display:none;vertical-align:middle"/>
											</div>
                                            <br/>
                                            <span class="red email_availability"></span>
											<span class="red ci_form_validation_error"><?php echo form_error('email'); ?></span>
											<span class="help-block spnmsg padding1"></span>
										
											<h4>Mobile Number</h4>
											<input type="text" placeholder="e.g. 9051234567" name="mobile" class="reqfield" id="mobile" maxlength="10">
											<input type="hidden" id="mobilecheck" value="">
											<div id="mobile_status">
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/check_icon.png" id="mobile_check" style="position: relative;display:none;vertical-align:middle"/>
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/x_icon.png" id="mobile_x" style="position: relative;display:none;vertical-align:middle"/>
												<span class=" red  mobile_availability"></span>
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
						<h1><span>Buy an</span> iPhone 5s</h1>
						<h1><span>for as low as</span></h1>
						<h1><span>Php 48,990</span></h1>
						<h1><span>Php 489.90*</span></h1>
						<p class="mgrn_top4">
							The offer starts on June 15 at 12 noon<br />and expires on June 17, 2014.
						</p>
						<p class="mgrn_top4">To qualify, like us on Facebook and  </p>
						<p class="fs2 txt_reg">register now!</p>
						<p>
							*The price will drop by 2% every hour until it<br />reaches 99% off for 48 hours. <br />
							One stock is available per item.
						</p>
						
						<div class="mgrn_top2">
							<div class="vid_container"><span class="fs1">watch this video </span> 
							<span>
								<img src="<?=base_url()?>assets/images/landingpage/btn_play.png" class="vidplay" alt="Easyshop.ph Video">
								<span id="videoplayer">									
									<embed src="https://www.youtube.com/v/-iLuVgicBjE&rel=0" type="application/x-shockwave-flash"></embed>
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
						<a href="<?=base_url()?>home">Shop</a>
					</li>
					<li>
						<a href="<?=base_url()?>sell/step1">Sell</a>
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
					<li class="social_media_container">
						<div class="social_media">
							<span class="txt_sm">Social Media:</span>
							<ul>
								<li>
									<div class="fb-like" data-href="https://www.facebook.com/EasyShopPhilippines" data-width="200" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>
								</li>
								<li>
									<a href="https://twitter.com/EasyShopPH" class="twitter-follow-button" data-show-count="false" data-size="large" data-show-screen-name="false">Follow @EasyShopPH</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
								</li>
							</ul>
							
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


<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery-1.9.1.js" ></script>
<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery-ui.js"></script>

<script type="text/javascript">

var config = {
     base_url: "<?php echo base_url(); ?>",
};

$(document).ready(function() {  
    $("#reg_btn").click(function() {
           $('#register_container').fadeIn(300);
    });    

    var pathname = $(location).attr('href');
    var idx = pathname.indexOf('#');
    if(idx > -1){
        if(pathname.substring(idx+1) == 'register'){
             $("#reg_btn").click();
        }
    }
});

$(window).load(function () {
    $(window).scroll(function () {
        var e;
        return e = $(window).scrollTop(), e < 50 ? $("#header").removeClass("fixed_header") : $("#header").addClass("fixed_header").fadeIn(300);
    })
}) 

/**** video player dialog box ****/
$(function() {
	$( "#videoplayer" ).dialog({
		width:"68%",
		autoOpen: false,
		modal: true,
		closeOnEscape: true,
		draggable:false,
        show: {
            effect: "fade",
            duration: 600
        },
        hide: {
            effect: "fade",
            duration: 400
        }
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
        show: {
            effect: "fade",
            duration: 1000
        },
        hide: {
            effect: "fade",
            duration: 400
        }
	});

	$( ".terms_and_conditions" ).click(function() {
	$( ".dialog" ).dialog( "open" );
	$(".dialog").siblings().parent('.ui-dialog').addClass('terms_container');
	});
});

</script> 
<script type="text/javascript">


$(document).click(function (e)
{
    var container = $("#register_container");
    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0  // ... nor a descendant of the container
        && !e.target.hasClass('reg_btn')
        )
    {
        container.fadeOut(300);
    }
});

</script>

    <!-- Form Plugins -->
	<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.numeric.js'></script>
	<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.validate.js'></script>
	
	<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/landingpage/landingpage.js?ver=4.10'></script>
	
	<!-- Password Strength -->
	<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/mootools-core-1.4.5-full-compat.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/password_meter.js"></script>
