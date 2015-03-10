<link rel="stylesheet" type="text/css" href="/assets/css/new-login.css" media='screen'/>

<div class="clear"></div>

<section class="bg-cl-fff">
    <div class="container pd-bttm-30">
        <div class="row">
            <div class="col-md-12 identify-email-container pd-bttm-30">
                <h1 class="email-h1-title border-bottom pd-bttm-10 text-left">Forgot Password</h1>
                <center>
                    <?php echo form_open(); ?>
                    <div class="input-group col-xs-12 col-sm-9 col-md-5 identify-email-top-group ">
                        <span class="input-group-addon" id="basic-addon1">E-mail: </span>
                        <input type="text" class="form-control" name="email" placeholder="Please enter your registered email address..." id="email" aria-describedby="basic-addon1">   
                    </div>
                    <div class="input-group col-xs-12 col-sm-9 col-md-5 identify-email-bottom-group ">
                        <input type="submit" class="form-control submit-email-form" name="submit" value="Send me a password reset link"/>  
                    </div>
                    <?php echo form_close(); ?>
          
                    <div class="input-group identify-email-bottom-group col-xs-12 col-sm-9 col-md-5 response-dialog">
                        <?php if(isset($isPost) && $isPost): ?>
                            <?php if(isset($isSuccessful) && $isSuccessful): ?>
                                <div class="alert alert-success" role="alert">
                                    <strong>Instructions to reset your password has been sent to your e-mail.</strong>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-danger" role="alert">
                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                    <strong>
                                        Ooops. <?php echo html_escape(reset($message)); ?>
                                    </strong>
                                </div>
                            <?php endif; ?>
                    
                        <?php endif; ?>
                    </div>

                </center>
            </div>
        </div>
    </div>
</section>

<div class="clear"></div>


<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src="/assets/js/src/forgotpassword.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.user_forgotpass.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

