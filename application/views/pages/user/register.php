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

    <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
        <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
        <link type='text/css' href='/assets/css/new-login-register.css?ver=<?=ES_FILE_VERSION?>' rel='stylesheet' media='screen' />
        <link type='text/css' href='/assets/css/basic.css?ver=<?=ES_FILE_VERSION?>' rel='stylesheet' media='screen' />
        <link type='text/css' href='/assets/css/responsive_css.css?ver=<?=ES_FILE_VERSION?>' rel='stylesheet' media='screen' />
        <link type='text/css' href='/assets/css/main-style.css?ver=<?=ES_FILE_VERSION?>' rel='stylesheet' media='screen' />
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.register.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
    <?php endif; ?>
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


     <section class="new-login-register">
        <div class="new-login-register-content">
            <div class="login-logo">
                <a href="/">
                    <img src="<?php echo getAssetsDomain(); ?>assets/images/es-logo-login.png" alt="Easyshop">
                </a>
            </div>
            <div class="login-loading-content text-center">
                <img src="<?php echo getAssetsDomain(); ?>assets/images/es-loader-3-md.gif" alt="loading...">
            </div>
            <div class="login-hide-content" style="display:none;">
                <div id="adv2" class="login-tabs">
                    <div id="alter-tab" class="idTabs">
                        <div class="idtabs-tab" >
                            <a href="#login" id="tab-login">
                                login
                                <span class="tab-down-arrow"></span>
                            </a>
                        </div>
                        <div class="idtabs-tab" id="anchor-create" >
                            <a href="#create-account" id="tab-create" >
                                create an account
                                <span class="tab-down-arrow"></span>
                            </a>
                        </div>
                        <span class="clear"></span>
                    </div>

                </div>

                <div id="login">

                    <div class="login-left-content">
                        <div class="login-left-border">
                            <h1>login to your account</h1>
                            <div>
                                <?php $attr = array('id'=>'login_form'); ?>
                                <?php echo form_open('', $attr); ?>
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-4">Username:</label>
                                        <span class="col-xs-12 col-sm-8 padding-reset">
                                            <input class="ui-form-control" type="text" id="login_username" name='login_username'>
                                            <span id="username_error" style="color:#f42800">
                                        </span>
                                    </div>
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-4">Password:</label>
                                        <span class="col-xs-12 col-sm-8 padding-reset">
                                            <input class="ui-form-control" type="password" id='login_password' name='login_password'>
                                            <span id="passw_error" style="color:#f42800">
                                            <span id="login_error">
                                            <?php $formError = isset($errors) ? reset($errors)['login'] : ''; ?>
                                            <?php if($formError !== 'Account Deactivated' && $formError !== 'Account Banned'):  ?>
                                                <?php echo html_escape($formError); ?>
                                            <?php endif; ?>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="row" >
                                        <span class="col-xs-12  padding-right-reset">
                                            <span id="deactivatedAccountPrompt" class="input-error error-deactivated" style="display: <?=$formError === 'Account Deactivated' ? 'block' : 'none'  ?>">
                                                Oooops! This account is currently deactivated.
                                                If you want to reactivate your account click <a id='sendReactivationLink' data-id="" >here</a> to send
                                                a reactivation link to your email.
                                            </span>
                                        </span>
                                    </div>
                                    <div class="row keepmeloggedin-cotent">
                                        <span class="col-xs-12 col-sm-6">
                                            <label for="keepmeloggedin">
                                                <input type="checkbox" name="keepmeloggedin" id="keepmeloggedin"> Remember me
                                            </label>
                                        </span>
                                        <span class="col-xs-12 col-sm-6 text-right padding-reset">
                                            <a href="/login/identifyEmail" class="login-lnk2 font-bold">Forgot your password?</a>
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span class="col-xs-12 padding-right-reset">
                                            <input type="submit" class="btn btn-default-3 login-btn" value='Login' name='login_form'/>
                                        </span>
                                    </div>
                                    <?php if($formError === 'Account Banned'): ?>
                                        <input type="hidden" id="account-banned-error" value="true" data-message="<?php echo reset($errors)['message']; ?>">
                                    <?php endif; ?>
                                <?php echo form_close();?>
                            </div>
                        </div>
                    </div>
                    <div class="login-right-content">
                        <h1>use your social network account</h1>
                        <div>
                             <a href="<?=$facebook_login_url?>" class="btn facebook-btn">
                                <span class="log-in-img"><img src="/assets/images/img-log-in-fb.png"></span>
                                <span class="text-center">Log In with Facebook</span>
                            </a>
                        </div>
                        <div>
                            <a href="<?=$google_login_url?>" class="btn google-btn">
                                <span class="log-in-img"><img src="/assets/images/img-log-in-google.png"></span>
                                <span class="text-center">Log In with Google</span>
                            </a>
                        </div>
                        <div class="text-center font-bold">
                            <span>
                                Don't have an account? 
                                    <a href="#anchor-create" class="login-lnk open-create-account">Create an account</a>
                            </span>
                        </div>
                    </div>
                    <input type='hidden' value='<?php echo $dayRange.' '.$hourRange; ?>' id='office_hours'/>
                    <input type='hidden' value='<?php echo $officeContactNo ?>' id='office_contactno'/>
                    <div class="clear"></div>
                </div>
                <!-- create account section -->
                <div id="create-account">
                    <div class="login-left-content md-hide-content">
                        <h1>why create an account?</h1>
                        <div class="why-create-content">
                            <img src="/assets/images/img-why-create1.png" alt="Buy the product you love">
                            <span><strong>Buy</strong> the product you love</span>
                        </div>
                        <div class="why-create-content">
                            <img src="/assets/images/img-why-create2.png" alt="Upload and Sell your items">
                            <span><strong>Upload and Sell</strong> your items</span>
                        </div>
                        <div class="why-create-content">
                            <img src="/assets/images/img-why-create3.png" alt="Unlimited upload of your items">
                            <span><strong>Unlimited upload</strong> of your items</span>
                        </div>
                    </div>
                    <div class="login-right-content">
                         <h1>create your easyshop account</h1>

                         <?php echo form_open('', array('id'=>'register_form1'));?>
                        <fieldset>
                            <div class="<?= $is_promo ? 'reg2_password' : 'reg2_username'?>">
                                <div class="row">
                                    <label class="col-xs-12 col-sm-5">Username</label>
                                    <span class="col-xs-12 col-sm-7">
                                        <input maxlength='25' type="text" placeholder="" id="username" name="username" class="reqfield ui-form-control" autocomplete="off"/>
                                        <input  type="hidden" id="usernamecheck" value="" name="usernamecheck">
                                        <span class="red ci_form_validation_error"><?php echo form_error('username'); ?></span>
                                        <div id="username_status">
                                            <span class="username_availability"></span>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <?php if($is_promo):?>
                                <div class="reg2_fullname" style="display: inline-block>">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-5">Fullname</label>
                                        <span class="col-xs-12 col-sm-7">
                                            <input type="text" placeholder="" id="fullname" name="fullname" class="reqfield ui-form-control" autocomplete="off" value="">
                                            <span class="red ci_form_validation_error"><?php echo form_error('fullname'); ?></span>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="reg2_password">
                                <div class="row">
                                    <label class="col-xs-12 col-sm-5">Password</label>
                                    <span class="col-xs-12 col-sm-7">
                                        <input class="ui-form-control" type="password" placeholder="" id="password" name="password" class="reqfield">
                                        <span class="red ci_form_validation_error"><?php echo form_error('password'); ?></span>
                                    </span>
                                </div>
                            </div>
                            <div class="reg2_confirmpassword">
                                <div class="row">
                                    <label class="col-xs-12 col-sm-5 padding-right-reset">Confirm Password</label>
                                    <span class="col-xs-12 col-sm-7">
                                        <input type="password" class="ui-form-control" placeholder="" id="cpassword" name="cpassword" class="reqfield" disabled>
                                        <span class="field_pword_status">
                                            <span class="fieldstatus input-success" id="cpassword_check" style="display:none;"/>Password match</span>
                                            <span class="fieldstatus input-error" id="cpassword_x" style="display:none; "/>password don't match</span>
                                        </span>
                                        <span class="red ci_form_validation_error"><?php echo form_error('cpassword'); ?></span>
                                        
                                    </span>
                                </div>
                            </div>
                            <div class="reg2_email">
                                <div class="row">
                                    <label class="col-xs-12 col-sm-5">Email Address</label>
                                    <span class="col-xs-12 col-sm-7">
                                        <input type="text" placeholder="" id="email" name="email" class="reqfield ui-form-control" autocomplete="off">
                                        <input type="hidden" id="emailcheck" value="">
                                        <div id="email_status">
                                            <span class="fieldstatus input-success"  id="email_check" style="display:none;"/>Email is valid</span>
                                        </div>
                                        <span class="red email_availability"></span>
                                        <span class="red ci_form_validation_error"><?php echo form_error('email'); ?></span>
                                    </span>
                                </div>
                            </div>
                            <div class="mobile">
                                <div class="row">
                                <label class="col-xs-12 col-sm-5">Mobile Number</label>
                                <span class="col-xs-12 col-sm-7">
                                    <input type="text" placeholder="e.g. 09051234567" name="mobile" class="reqfield ui-form-control" id="mobile" maxlength="11">
                                    <input type="hidden" id="mobilecheck" value="">
                                    <div id="mobile_status">
                                        <span class="fieldstatus input-success" id="mobile_check" style="display:none;"/>Mobile is valid</span>
                                        <span class=" red  mobile_availability"></span>
                                    </div>
                                    <span class="red ci_form_validation_error"><?php echo form_error('mobile'); ?></span>
                                    <span class="help-block spnmsg padding1"></span>
                                </span>
                            </div>
                            <div class="row">
                                <span class="col-xs-12">
                                    <input type="submit" class="btn btn-default-3 create-account-btn" value="SUBMIT" name="register_form1" id="register_form1_btn" >
                                </span>
                            </div>
                            <div class="reg2_tc">
                                <p class="terms_con padding1 padding-t1">
                                    By registering to Easyshop.ph, you agree to comply with our
                                    <span class="terms_and_conditions">Terms and Conditions</span>
                                </p>
                            </div>
                        </fieldset>
                        <?php echo form_close();?>

                        <div class="why-create-mobile">
                            <span class="show-why-create-mobileview">why create an account?</span>
                            <div class="why-create-mobileview">
                                <div class="why-create-content">
                                    <img src="/assets/images/img-why-create1.png" alt="Buy the product you love">
                                    <span><strong>Buy</strong> the product you love</span>
                                </div>
                                <div class="why-create-content">
                                    <img src="/assets/images/img-why-create2.png" alt="Upload and Sell your items">
                                    <span><strong>Upload and Sell</strong> your items</span>
                                </div>
                                <div class="why-create-content">
                                    <img src="/assets/images/img-why-create3.png" alt="Unlimited upload of your items">
                                    <span><strong>Unlimited upload</strong> of your items</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
   
            <div class="login-throttle" style="display:none;">
                <div class="login-throttle-border">
                    <div>
                        <h1>TROUBLE LOGGING-IN?</h1>
                    </div>
                    <div >
                        <p class="login-throttle-message">Too many failed login-attempts.</p>
                        <p>Timeout Remaining: <span class="login-throttle-message" id="login-timeout">60</span> sec </p>
                    </div>
                    <div class="row throttle-btn-container" >
                        <input type="submit" class="btn btn-default-3" id="login-try-again" value="Try again" name="retry">
                        <a href="/login/identifyEmail">
                        <input type="submit" class="btn help-me-locate-account-btn" value="Help me locate my account" name="forgot-password">
                        </a>
                   </div>
                </div>
            </div>
        </div>
    
     
    
    </section>
    <div id="terms-section">
        <div class="terms-overlay-bg">&nbsp;
        </div>
        <div class="terms-and-condition">
            <div class="pos-rel terms-header">
                <h1>TERMS &amp; CONDITIONS</h1>
                <div class="close-term"><img src="/assets/images/img-close.png" alt="close"></div>
            </div>
            <div class="terms-content">
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
        </div>
    </div>
</body>

<input type="hidden" name="login_referrer" class="login-referrer" value="<?php echo $redirect_url; ?>"/>

<?php echo form_open('registration/success', [' id' => 'success_register' ]);?>
    <input type="hidden" name="registration_referrer" class="registration-referrer" value="<?php echo $is_promo ? $redirect_url : 'registration'; ?>"/>
<?php echo form_close();?>

<script type="text/javascript">
    var config = {
         base_url: "<?php echo base_url(); ?>"
    };
</script>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type='text/javascript' src="/assets/js/src/vendor/bower_components/jquery.js"></script>
    <script type='text/javascript' src="/assets/js/src/vendor/bower_components/jquery.cookie.js"></script>
    <script type='text/javascript' src="/assets/js/src/vendor/jquery.idTabs.min.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src="/assets/js/src/vendor/bower_components/jquery-ui.js"></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
    <script type='text/javascript' src='/assets/js/src/vendor/bower_components/jquery.validate.js'></script>
    <script type='text/javascript' src="/assets/js/src/vendor/bower_components/pwstrength.bootstrap.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src="/assets/js/src/universal.js"></script>
    <script type='text/javascript' src='/assets/js/src/register.js?ver=<?=ES_FILE_VERSION?>'></script>
    <script type="text/javascript" src="/assets/js/src/login.js?ver=<?=ES_FILE_VERSION?>"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.user_register.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

<!--[if IE 9]>
    <script type="text/javascript" src="/assets/js/src/register-ie-override.js"></script>
<![endif]-->

<script>
    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
</script>

</html>