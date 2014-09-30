<link rel="canonical" href="<?php echo base_url()?>login"/>

<div class="clear"></div>
<div class="clear mrgn-top-35"></div>
    <div class="wrapper login_content">
        <div class="login-tli-con" style="display: none">
            <div class="border-all border-rad-3">
                <div class="border-bottom bg-cl-f7f7f7">
                    <div class="pd-tb-20 pd-lr-20">
                        <h3><strong>Trouble Logging In?</strong></h3>
                    </div>
                </div>
                <div class="text-center pd-top-30 pd-bttm-30">
                    <p><strong>"loremipsum.dolor@yahoo.com"</strong> is not associated with any easyshop.ph account.</p>
                </div>
                <div class="pd-tb-20 pd-lr-20 text-right login-btn-con">
                    <a href="" class="btn btn-primarybtn">Try again</a>
                    <a href="" class="btn btn-default">Help me locate my account</a>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="login_left_content"><img src="/assets/images/img_login_banner.jpg" alt="banner"></div>
        <?php if($logged_in): ?>
        
            <div class="login_box">
                <div style="text-align:center;">
                    <p>You are currently signed-in as <b><?php echo $user["username"]; ?></b>. </p>
                    <p>If you wish to sign-in as another user, <a href='/login/logout' class="orange2">click here.</a> </p>
                </div>
            </div>
            
        <?php else: ?>
        
            <?php $attr = array('id'=>'login_form'); ?>
            <?php echo form_open('', $attr); ?>
                <div class="login_box">
                    <div>
                        <label for="login_username">Username:</label>
                        <input type="text" id="login_username" name="login_username">
                        <span id="username_error" class="red error_cont" style="font-weight:bold;display:block;margin:5px 0px 0px 93px;"></span>
                    </div>
                    <div>
                        <label for="login_password">Password:</label>
                        <input type="password" id="login_password" name="login_password"> 
                        <span id="passw_error" class="red error_cont" style="font-weight:bold;display:block;margin:5px 0px 0px 93px;"> </span>
                        <span id="login_error" class="red" style="font-weight:bold;display:block;margin:5px 0px 0px 93px;"><?php echo (isset($form_error)?$form_error:'');?>  </span>
                        <p><a href="/login/identify">Forgot your password?</a></p>
                    </div>
                    
                    <div>
                        <input type="checkbox" name="keepmeloggedin" id="keepmeloggedin">
                        <p><label for="keepmeloggedin">
                        Remember me
                        </label></p>
                    </div>
                    <div>
                        <input id="login" type="submit" name="login_form" value="Login"/>
                        <img src="/assets/images/orange_loader_small.gif" id="loading_img" class="login_loading_img" style="display:none"/>
                        <br>
                        <p>
                        <a href="/register">Don't have an account? Register now</a>
                        </p>
                    </div>
                </div>
            <?php echo form_close();?>

        <?php endif; ?>
    </div>

</section>
<div class="clear"></div>

<input type='hidden' value='<?php echo $url?>' id='redirect_url'/>

   
<script type='text/javascript' src='/assets/js/src/vendor/jquery.validate.js'></script>   
<script type="text/javascript" src="/assets/js/src/login.js?ver=<?=ES_FILE_VERSION?>"></script>
