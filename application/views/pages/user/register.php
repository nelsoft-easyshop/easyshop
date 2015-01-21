<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js"> <!--<![endif]-->
<head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Easyshop.ph - Welcome</title>
        <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon"/>
        <meta property="og:title" content="EasyShop.ph" />
        <meta property="og:description" content="Our vision is to be the leading Online Shopping website in South East Asia.
           The mission that EasyShop has is to provide its customer with a Fast and Easy
           online shopping experience. Our goal is to be the first website you think about
           when buying online." />
        <meta property="og:image" content="https://easyshop.ph/assets/images/img_logo.png?ver=<?=ES_FILE_VERSION?>" />
        <meta name="viewport" content="width=device-width">

        <!-- CSS -->
        <link rel="stylesheet" href="/assets/css/landingpage/style_v2.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
        <link rel="stylesheet" href="/assets/css/landingpage/jquery.bxslider.css" type="text/css" media="screen"/> <!-- Slider CSS -->
        <link rel="stylesheet" href="/assets/css/landingpage/responsive-nav.css?ver=<?=ES_FILE_VERSION?>"><!-- responsive menu -->

        <!-- Contact Form CSS files -->
        <link type='text/css' href='/assets/css/basic.css?ver=<?=ES_FILE_VERSION?>' rel='stylesheet' media='screen' />
        <link type='text/css' href="/assets/css/jquery-ui.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet">




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

        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=154815247949100&version=v2.0";
          fjs.parentNode.insertBefore(js, fjs);
          }(document, 'script', 'facebook-jssdk'));
        </script>
    <header id="header" class="">
        <div class="header">
                <div class="logo_con">
                    <a href='/'><span class="span_bg logo"></span></a>
                </div>
                <div class="nav_container">
                    <nav class="nav-collapse">
                      <ul>
                        <li class="grid-1"><a href="/">Shop</a></li>
                        <li class="grid-1"><a href="/sell/step1">Sell</a></li>
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
                            <li class='btn_login'>
                                <a href='/me'><input type="submit" class='btn' id='userpage' value='<?php echo html_escape($user["username"]);?>'/></a>
                            </li>


                             <li class="btn_register">
                                <a href='/login/logout'><span class='btn' id='signout'>Sign-out</span></a>
                            </li>
                        <?php endif; ?>
                        <li class="shop_con">
                            <a href="/">Shop</a>
                            <a href="/sell/step1">Sell</a>
                        </li>
                      </ul>
                    </nav>
                </div>

        </div>

        <div class="register_container container-16" id="register_container">
            <div class="grid-6">

            <?php echo form_close();?>
                <div class="dialog t_and_c">
                    <h3>TERMS &amp; CONDITIONS</h3>
                    <p>
                        Welcome to EasyShop.ph, a company under the group of Nelsoft Technologies Inc. Before using the website, you must agree to the terms and conditions listed below.
                    </p>
                    <h3>Creating Your Account</h3>
                    <p>
                        As an EasyShop.ph member, it is your responsibility to be vigilant in keeping your account secure to prevent any undesirable activity. By creating an EasyShop.ph account, you are responsible for your account password.
                        EasyShop.ph reserves the right to do background checks, obtain or delete your information.
                        Our age policy only requires that a minor under the age of 18 should only use the website under the authorization of a parent or a legal guardian.
                    </p>
                    <h3>Intellectual Property Rights</h3>
                    <p>
                        The seller is fully responsible for the Listing. By Listing on the website
                        of Easyshop.ph, the seller confirms warrants that you the valid owner of
                        Products and has the right to sell the Products online in the Philippines.
                    </p>
                    <h3>Information Submitted By User</h3>
                    <p> 
                        EasyShop.ph is the registered owner of and holds the title to and all interest in the trademark "EasyShop.ph" and the trade name EasyShop.ph and owns all intellectual property rights to and all interest in the trade name EasyShop.ph as well as the EasyShop.ph logo.
                        All intellectual property rights, whether registered or unregistered (including website design, including, text, graphics, software, photos, video, music, sound, and their selection and arrangement, and all software compilations, underlying source code and software) are EasyShop.ph property.
                        All contents are protected by copyright under Philippine Copyright Laws and International Conventions.
                        All rights are reserved.
                    </p>
                    <h3>Information Submitted By User</h3>
                    <p>
                        Submitting a false e-mail address, or pretending to be another person is prohibited in EasyShop.ph. All information submitted will be EasyShop.ph property.
                    </p>
                    <h3>Registration as a Seller</h3>
                    <p>
                        As a seller, you are fully responsible for your listing. By listing on the website of EasyShop.ph, you confirm that you are the valid owner of the items you are selling.
                        Below are the guidelines a seller must conform to:
                        <ul style=''>
                            <li>You have the right to sell your items online in Philippines.</li>
                            <li>The products you are selling conform with Philippine laws.</li>
                            <li>The product must adhere to the description you have posted on the website.</li>
                            <li>The products are free from defects.</li>
                            <li>Misleading information are not allowed in the website.</li>
                            <li>Product pricing must be competitive.</li>
                            <li>Illegal and offensive content are prohibited.</li>
                        </ul>
                    </p>
                    <h3 class="htitle2">Indemnification</h3>
                    <p>
                        As a seller, you must agree to hold EasyShop.ph not responsible from issues which may arise from the following:
                        <ul style="list-style:none;">
                            <li>a) Product defects;</li>
                            <li>b) Negligence or faults to whatever nature of the Seller;</li>
                            <li>c) Breaches in warranty</li>
                        </ul>
                    </p>
                    <h3 class="htitle2">Website Utility</h3>
                    <p>
                    </p>
                    <h3 class="htitle2">Prohibited Actions</h3>
                    <p>
                        Unlawful, offensive, threatening, libelous, defamatory, pornographic, obscene or otherwise objectionable materials, and posts which violates any party’s intellectual property or this Agreement shall be removed.
                    </p>
                    <p>
                        Other prohibited actions include:
                    
                        <ul>
                            <li>Violating Philippines/International Laws as well as any third party rights.</li>
                            <li>Violating EasyShop.ph policy</li>
                            <li>Spamming</li>
                            <li>Misleading content</li>
                            <li>Uploading content (viruses, malware etc.) which might cause harm to the interests of EasyShop.ph users.</li>
                            <li>Using any information from the users without consent.</li>
                        </ul>
                    </p>
                    <h3 class="htitle2">Consequences:</h3>
                    <ul>
                        <li>Status Downgrade</li>
                        <li>Cancellation of lists.</li>
                        <li>Suspension or Termination of your account.</li>
                        <li>Legal and Criminal charges</li>
                    </ul>
                    <p></p>
                    <h3 class="htitle2">Indemnification</h3>
                    <p>
                        You agree to indemnify, defend and hold harmless EasyShop.ph, its officers, directors, employees, agents, licensors and suppliers against all losses, expenses, damages and costs, including reasonable attorneys’ fees, resulting from any violation of this Agreement or any activity related to your account (including negligent or wrongful conduct) by you or any other person.
                    </p>
                    <p>
                        As a seller, you must agree to hold EasyShop.ph not responsible from issues which may arise from the following:
                        <ul style="list-style:none;">
                            <li>a) Product defects;</li>
                            <li>b) Negligence or faults to whatever nature of the Seller;</li>
                            <li>c) Warranty breaches</li>
                        </ul>
                    </p>
                    <h3 class="htitle">CONTENT</h3>
                    <h3 class="htitle2">Prohibited Items:</h3>
                    <ul>
                        <li>Cosmetics without Bureau of Food and Drug Authorization</li>
                        <li>Pornography.</li>
                        <li>Firearms or any deadly, or hazardous weapons.</li>
                        <li>Black-market Items.</li>
                        <li>Stolen goods.</li>
                        <li>Smuggled items</li>
                        <li>Illegal items.</li>
                    </ul>
                    <p>
                        Vulgar language, sexual slangs, violence (threats, etc), and other looked down behavior are not allowed in EasyShop.ph.
                    </p>
                    <h3 class="htitle2">Billing Invoice</h3>
                    <p>
                        Read the details (listing fees, transaction fees, marketing cost, etc.) indicated in the invoice <b>CAREFULLY</b>.
                    </p>
                    <p>
                        Be aware of the terms and conditions prior to purchasing any item from EasyShop.ph.
                    </p>
                    <p>
                        Easyshop.ph will not take liability for any loss nor damage upon a product received through third party logistics or delivery services.
                    </p>
                    <p>
                        EasyShop.ph will not be responsible should the buyer/seller provides incorrect information that will lead to problems in the deal. EasyShop.ph reserves the right to withhold payments until a buyer has received the item as well as the right to verify if a buyer is authorized to use certain payment methods.
                    </p>
                    <p>
                        EasyShop.ph reserves the right to cancel transactions when the buyer/seller fails to confirm.
                    </p>
                    <h3 class="htitle2">Delivery/Payment Methods</h3>
                    <p>
                        You have an option to pick up purchases from the seller or through our partners.
                    </p>
                    <ul>
                        <li>
                            We will not be responsible for any issues involving lost and damaged goods or delays in delivery. Such disputes shall be resolved between the buyer seller as well as the logistics provider.
                        </li>
                        <li>Prices are subject to change as per seller’s price inputs.</li>
                    </ul>
                    <p>
                        Sellers must take full responsibility for the buyers to receive their purchased items.
                    </p>
                    <h3 class="htitle2">Returns Refunds</h3>
                    <p>
                        The seller must ensure that the items that he sells are damage free and should conform with the warranties.
                    </p>
                    <p>
                        Returns and refunds must be settled between the buyer and the seller should the seller has already received payment.
                    </p>
                    <p>
                        A processing fee will be deducted by EasyShop.ph in case of refunds and returns.
                    </p>
                    <h3 class="htitle2">Conflicts</h3>
                    <p>
                        For cases of conflict, the seller and buyer is expected to resolve the problem themselves. Under circumstances where conflicts are unresolvable, EasyShop.ph will not be involved in any legal proceedings.
                    </p>
                    <h3 class="htitle2">Termination</h3>
                    <p>
                        EasyShop.ph may, in its sole discretion, without prior notice and without incurring any liability to you, terminate or limit your access to this website for any justifiable cause, including but without limitation to (1) your breach or violation of these Terms of Use, or other incorporated agreements or guidelines, (2) upon the request of any law enforcement or government agency, (3) discontinuance or any material modification to this website or any part thereof, (4) extended periods of inactivity, and (5) your engagement in fraudulent, illegal or prohibited activities.
                    </p>
                </div>
    </header>
    <div class="clear"></div>

    <div class="clear"></div>
    <section class="mid_reg2">
        <div class="wrapper register_wrapper" id="register">
            <h1>REGISTER</h1>
            <div class="register_container2" id="register_container2">
                <?php echo form_open('', array('id'=>'register_form1'));?>
                    <fieldset>
                        <div class="<?= $is_promo ? 'reg2_password' : 'reg2_username'?>">
                            <h4>Username</h4>
                            <input maxlength='25' type="text" placeholder="" id="username" name="username" class="reqfield" autocomplete="off"/>
                            <input  type="hidden" id="usernamecheck" value="" name="usernamecheck">
                            <span class="red ci_form_validation_error"><?php echo form_error('username'); ?></span>
                            <div id="username_status">
                                <img class="fieldstatus" src="/assets/images/check_icon.png" id="username_check" style="position: relative;display:none;vertical-align:middle"/>
                                <img class="fieldstatus" src="/assets/images/x_icon.png" id="username_x" style="position: relative;display:none;vertical-align:middle"/>
                                <span class="username_availability"></span>
                            </div>
                        </div>
                        <?php if($is_promo):?>
                            <div class="reg2_fullname" style="display: inline-block>">
                                <h4>Fullname</h4>
                                <input type="text" placeholder="" id="fullname" name="fullname" class="reqfield" autocomplete="off" value="">
                                <span class="red ci_form_validation_error"><?php echo form_error('fullname'); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="reg2_password">
                            <h4 class="txt_cp">Password</h4>
                            <input type="password" placeholder="" id="password" name="password" class="reqfield">
                            <span class="red ci_form_validation_error"><?php echo form_error('password'); ?></span>
                        </div>
                        <div class="reg2_confirmpassword">
                            <h4 class="txt_cp">Confirm Password</h4>
                            <input type="password" placeholder="" id="cpassword" name="cpassword" class="reqfield" disabled>
                            <span class="field_pword_status">
                                <img class="fieldstatus" src="/assets/images/check_icon.png" id="cpassword_check" style="position: relative;display:none;"/>
                                <img class="fieldstatus" src="/assets/images/x_icon.png" id="cpassword_x" style="position: relative;display:none; "/>
                            </span>
                            <span class="red ci_form_validation_error"><?php echo form_error('cpassword'); ?></span>
                            <span class="help-block spnmsg padding1" style="text-align:left"></span>
                        </div>
                        <div class="reg2_email">
                            <h4>Email Address</h4>
                            <input type="text" placeholder="" id="email" name="email" class="reqfield" autocomplete="off">
                            <input type="hidden" id="emailcheck" value="">
                            <div id="email_status">
                                <img class="fieldstatus" src="/assets/images/check_icon.png" id="email_check" style="position: relative;display:none;vertical-align:middle"/>
                                <img class="fieldstatus" src="/assets/images/x_icon.png" id="email_x" style="position: relative;display:none;vertical-align:middle"/>
                            </div>
                            <br/>
                            <span class="red email_availability"></span>
                            <span class="red ci_form_validation_error"><?php echo form_error('email'); ?></span>
                            <span class="help-block spnmsg padding1"></span>
                        </div>
                        <div class="mobile">
                            <h4>Mobile Number</h4>
                            <input type="text" placeholder="e.g. 09051234567" name="mobile" class="reqfield" id="mobile" maxlength="11">
                            <input type="hidden" id="mobilecheck" value="">
                            <div id="mobile_status">
                                <img class="fieldstatus" src="/assets/images/check_icon.png" id="mobile_check" style="position: relative;display:none;vertical-align:middle"/>
                                <img class="fieldstatus" src="/assets/images/x_icon.png" id="mobile_x" style="position: relative;display:none;vertical-align:middle"/>
                                <span class=" red  mobile_availability"></span>
                            </div>
                            <span class="red ci_form_validation_error"><?php echo form_error('mobile'); ?></span>
                            <span class="help-block spnmsg padding1"></span>
                        </div>
                        <div class="reg2_tc">
                            <p class="terms_con padding1 padding-t1">
                                By registering to Easyshop.ph, you agree to comply with our
                                <span class="terms_and_conditions">Terms and Conditions</span>
                            </p>
                        </div>
                        <div class="reg2_btn_submit">
                            <!--<button type="button" class="btn btn-warning btn-large">SEND</button>-->
                            <input type="submit" class="btn btn_send" value="SEND" name="register_form1" id="register_form1_btn" >
                            <div style='display:inline-block; position:absolute; width:50px; overflow:hidden;'>
                            <img style='display:none;margin-top:5px;margin-left:5px;' src="/assets/images/orange_loader_small.gif" class="img_loader_small2" id="register_form1_loadingimg"/>
                            </div>
                        </div>
                    </fieldset>
                <?php echo form_close();?>
            </div>
        </div>
    </section>
    <div class="clear"></div>
    <footer>
        <div class="wrapper">
            <div class="footer">
                <ul>
                    <li>
                        <a href="/">Shop</a>
                    </li>
                    <li>
                        <a href="/sell/step1">Sell</a>
                    </li>
                    <li>
                        <div class="footer_payment">
                            <p><strong>Payment Methods:</strong></p>
                            <span class="span_bg mastercard"></span>
                            <span class="span_bg visa"></span>
                            <span class="span_bg paypal"></span>
                            <span class="span_bg dragonpay"></span>
                            <span class="span_bg cod"></span>
                        </div>
                    </li>
                    <li class="social_media_container">
                        <div class="social_media">
                            <p><strong>Social Media:</strong></p>
                            <ul>
                                <li>
                                    <div class="fb-like" data-href="<?php echo $facebook; ?>" data-width="200" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>
                                </li>
                                <li>
                                    <a href="<?php echo $twitter; ?>" class="twitter-follow-button" data-show-count="false" data-size="large" data-show-screen-name="false">Follow @EasyShopPH</a>

                                </li>
                            </ul>

                        </div>
                    </li>
                    <li>

                    </li>
                </ul>

            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
        <div class="copyright_content">
                <p>Copyright © 2014 easyshop.ph. All rights reserved </p>
            </div>
    </footer>

</body>
<?php echo form_open('registration/success', array('id'=>'success_register'));?>
<input type="hidden" name="referrer" class="referrer" value="<?=$redirect_url ?>"/>
<?php echo form_close();?>


<script src="/assets/js/src/vendor/jquery-1.9.1.js"></script>
<script src="/assets/js/src/landingpage-responsive-nav.js"></script>
<script type='text/javascript' src="/assets/js/src/vendor/jquery-ui.js"></script>

<script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.validate.js'></script>
<script type='text/javascript' src='/assets/js/src/landingpage.js?ver=<?=ES_FILE_VERSION?>'></script>
<script src="/assets/js/src/vendor/jquery.bxslider.min.js"></script>

<script type="text/javascript">
    var config = {
         base_url: "<?php echo base_url(); ?>",
    };
    

    var navigation = responsiveNav(".nav-collapse");


    (function( $ ) {


        jQuery('.bxslider_slides').bxSlider({
            infiniteLoop: true,
            auto: true
        });
    })(jQuery);  
    jQuery('#reg_btn,#reg_txt').click(function(event) {
        event.preventDefault();
        var n = jQuery(document).height();
        jQuery('html, body').animate({ scrollTop: 200 }, 300);

        navigation.close();
    });
</script>
<!-- password meter: uses mootool, consider replacing -->
<script type="text/javascript" src="/assets/js/src/vendor/mootools-core-1.4.5-full-compat.js"></script>
<script type="text/javascript" src="/assets/js/src/vendor/password_meter.js"></script>
<!-- end mootool -->
<script>
    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
</script>
