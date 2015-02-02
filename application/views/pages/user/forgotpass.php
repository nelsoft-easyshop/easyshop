<link rel="stylesheet" type="text/css" href="/assets/css/new-login.css" media='screen'/>

<div class="clear"></div>

<section>
  
    <div class="col-md-12 identify-email-container container">
        <center>
            <?php echo form_open(); ?>
            <div class="input-group col-md-4 identify-email-top-group ">
                <span class="input-group-addon" id="basic-addon1">E-mail: </span>
                <input type="text" class="form-control" name="email" placeholder="Please enter your registered email address..." id="email" aria-describedby="basic-addon1">   
            </div>
            <div class="input-group col-md-2 identify-email-bottom-group ">
                <input type="submit" class="form-control submit-email-form" name="submit" value="Send me a password reset link"/>  
            </div>
            <?php echo form_close(); ?>
  
            <div class="input-group identify-email-bottom-group col-md-4 response-dialog">
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
 
        
</section>

<div class="clear"></div>


<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src="/assets/js/src/forgotpassword.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.user_forgotpass.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

