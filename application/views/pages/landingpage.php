<!DOCTYPE html>
    <head>
    <link rel="shortcut icon" href="<?php echo base_url()?>assets/images/favicon.ico" type="image/x-icon"/>
<meta property="og:title" content="EasyShop.ph" />
<meta property="og:description" content="Our vision is to be the leading Online Shopping website in South East Asia. 
           The mission that EasyShop has is to provide its customer with a Fast and Easy 
           online shopping experience. Our goal is to be the first website you think about
           when buying online." />
<meta property="og:image" content="https://easyshop.ph/assets/images/img_logo.png" />

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="<?=base_url()?>assets/css/landingpage/bootstrap.css" rel="stylesheet">
        <link href="<?=base_url()?>assets/css/landingpage/bootstrap-responsive.css" rel="stylesheet">
        <link href="<?=base_url()?>assets/css/landingpage/mystyle.css" rel="stylesheet">
		<link href="<?=base_url()?>assets/css/jquery-ui.css" rel="stylesheet">
		<title><?php echo $title;?></title>
        
         <!-- Google Analytics -->
      <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-48811886-1', 'easyshop.ph');
          ga('send', 'pageview');
        </script>
        <!-- End of Google Analytics -->
    </head>
    <body>
         <!-- Google Tag Manager -->
         <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-KP5F8R"
         height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
         <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
         new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
         j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
         '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
         })(window,document,'script','dataLayer','GTM-KP5F8R');</script>
         <!-- End Google Tag Manager -->
    
    
        <div class = "container header_bg">
            <div class="row-fluid">
				<div class="login_btn">
                	<a href="<?=base_url();?>login" class="">Log In</a>
                </div>
                <div id="himg" class="text-center"> 
                	<a href="<?=base_url()?>home"><img src="<?=base_url()?>/assets/images/landingpage/img_logo.png"></a>
                	
                </div>
                
                    <h2 class="text-center txt_header">SHOPPING MADE <span class="c_ylw">EASY</span></h2>
                 <div class="himg2">                     
                    <h1 class="txt_header2 margin_top1">Your <span class="c_ylw">business</span></h1>
                    <h1 class="txt_header2 margin_top2 padding_left1">has a <span>new home!</span></h1>
               
                   <p class="header_sub hs_top">
                      <span>Sign up and enjoy selling online for free! </span>
                      Registered sellers will be invited to the EasyShop.ph Pre-Launch 
                      Event on 17 May 2014, wherein freebies and prizes await you.
                   </p>
                   <span class="img_scroll"><img src="<?=base_url()?>/assets/images/landingpage/img_scroll.png"></span>
                </div>
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
											<input type="text" placeholder="" id="username" name="username" class="reqfield" autocomplete="off"/>
											<input type="hidden" id="usernamecheck" value="" name="usernamecheck">
											<span class="red ci_form_validation_error"><?php echo form_error('username'); ?></span>
											<img id="username_loader" class="img_loader_small" src="<?=base_url()?>/assets/images/orange_loader_small.gif" style="display:none;">
											<div id="username_status">
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/check_icon.png" id="username_check" style="position: relative;display:none;vertical-align:middle"/>
												<img class="fieldstatus" src="<?=base_url()?>/assets/images/x_icon.png" id="username_x" style="position: relative;display:none;vertical-align:middle"/>
												<span class="username_availability"></span>
											</div>
											<span class="help-block spnmsg text-center">The username must be 5-25 characters long.</span>
									
										
										
											<h4>Password</h4>
											<input type="password" placeholder="" id="password" name="password" class="reqfield">
											<span class="red ci_form_validation_error"><?php echo form_error('password'); ?></span>
											<span class="help-block spnmsg text-center">Password must be alphanumeric with a minimum of 6 characters.</span>
										
										
										
											<h4>Confirm Password</h4>
											<input type="password" placeholder="" id="cpassword" name="cpassword" class="reqfield" disabled>
											<img class="fieldstatus" src="<?=base_url()?>/assets/images/check_icon.png" id="cpassword_check" style="position: relative;display:none;"/>
											<img class="fieldstatus" src="<?=base_url()?>/assets/images/x_icon.png" id="cpassword_x" style="position: relative;display:none; "/>
											<span class="red ci_form_validation_error"><?php echo form_error('cpassword'); ?></span>
											<span class="help-block spnmsg" style="text-align:left"></span>
										
										
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
											<span class="help-block spnmsg"></span>
										
											<h4>Mobile Number<h4>
											<input type="text" placeholder="e.g. 9051234567" name="mobile" class="reqfield" id="mobile" maxlength="10">
											<span class="red ci_form_validation_error"><?php echo form_error('mobile'); ?></span>
											<span class="help-block spnmsg"></span>
											
											
											
										<!--<button type="button" class="btn btn-warning btn-large">SEND</button>-->
										<input type="submit" class="btn btn-warning btn-large" value="SEND" name="register_form1" id="register_form1_btn" >
										<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" class="img_loader_small2" id="register_form1_loadingimg" style="display:none"/>
									
										<p class="terms_con">By registering to Easyshop.ph, you agree to comply with our <span class="terms_and_conditions">Terms and Conditions</span></p>
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

								<div class="input-append">
								<?php echo form_open('', array('id'=>'subscription_form'));?>
									<h4>Stay in touch to get the latest updates</h4>
									<input placeholder="e-mail address" class="subscribe_input" id="appendedInputButton" type="text" name="subscribe_email">
									<!--<button class="btn btn-warning btn-large" type="button">Subscribe</button>-->
									<input type="submit" class="btn btn-warning btn-large subscribe_btn" value="Subscribe" name="subscribe_btn" id="subscribe_btn" >
									<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" class="img_loader_small2 sub_loading_img" id="subscribe_loadingimg" style="display:none"/>
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
                        <div class="span12 text-center">
                            <span class="spn48 content_header">WE MAKE SHOPPING EASY</span>
                            <div class="row-fluid">
                                <div class="span4 text-center">
                                    <img src="<?=base_url()?>assets/images/landingpage/img_online_shopping.png"><br>
                                    <span class="spn18 cotent2_title"><b>Online Shopping</b></span>
                                    <span class="help-block spnmsg content2_sub">   
                                    	We provide our users with a fast and easy online shopping experience, 
                                    	letting them enjoy the benefits of one-stop shopping at the comforts of their own home.
                                     
                                    </span>
                                </div>
                                <div class="span4 text-center">
                                    <img src="<?=base_url()?>assets/images/landingpage/img_paperless_payment.png"><br>
                                    <span class="spn18 cotent2_title"><b>Paperless Payment</b></span>
                                    <span class="help-block spnmsg content2_sub">
                                         Pay through trusted payment channels and carry out your transactions easily.
                             			 Easyshop aims to provide you with flexible payment options in order to simplify 
                             			 your shopping and selling experience. 
                                    </span>
                                </div>
                                <div class="span4 text-center">
                                    <img src="<?=base_url()?>assets/images/landingpage/img_secured.png"><br>
                                    <span class="spn18 cotent2_title"><b>Secured Transaction</b></span>
                                    <span class="help-block spnmsg content2_sub">
                                    	Easyshop provides a secure platform by which users can carry out their transactions. 
                             			Our business model ensures that deals between buyers and sellers are done risk-free.
                                    </span>
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
    
   	 <?php echo form_open('registration/success', array('id'=>'success_register'));?>
		  <input type="hidden" name="referrer" class="referrer" value="landingpage"/>
	 <?php echo form_close();?>
     
     <?php echo form_open('subscription/success', array('id'=>'success_subscribe'));?>
		  <input type="hidden" name="referrer" class="referrer" value="landingpage"/>
	 <?php echo form_close();?>

	
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
	
	
	
	<script>
		var config = {
			 base_url: "<?php echo base_url(); ?>",
		};
		// $(document).ready(function(){
		//   $(document).scroll(function () {
		// 	  var y = $(this).scrollTop();
		// 	  if (y > 20) {
		// 		  $('.login_btn').show();
		// 	  } else {
		// 		  $('.login_btn').hide();
		// 	  }
		//   });
		// });
	$(window).load(function () {
	    	$(window).scroll(function () {
	        var e; 
	        return e = $(window).scrollTop(), e < 50 ? $(".login_btn").removeClass("show") : $(".login_btn").addClass("show");


	    })
	})
		
	</script>