<link rel="stylesheet" type="text/css" href="/assets/css/new-login.css" media='screen'/>

<div class="clear"></div>
<br/>
<br/>
<section class="section-login">
    <div class="container pd-bttm-30">
        <div class="panel panel-default">
            <div class="panel-body div-merge-container" >
                    <div class="identify-email-container">
                        <p class="panel-title-merge">Forgot Password</p>
                        Please type in your email address. We will send you an email to reset your password.
                        
                        <div class="row"><div style="display: block; height: 40px;"></div>
                            <div class="col-sm-2 col-md-3"></div>
                            <div class="col-xs-12 col-sm-8 col-md-6">
                                <div class="identify-email-bottom-group response-dialog">
                                    <?php if(isset($isPost) && $isPost): ?>
                                        <?php if(isset($isSuccessful) && $isSuccessful): ?>
                                            <div class="alert alert-es-success" role="alert">
                                                Instructions to reset your password has been sent to your e-mail.
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-es-danger" role="alert">
                                                Ooops. <?php echo html_escape(reset($message)); ?>
                                            </div>
                                        <?php endif; ?>
                                
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-3"></div>
                        </div>
                        <?php echo form_open(); ?>
                        <div class="row">
                            <div class="col-sm-2 col-md-3"></div>
                            <div class="col-xs-12 col-sm-8 col-md-6  ">
                                <div class="form-horizontal">
                                    <label class="col-md-2"><span>Email : </span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="email" placeholder="Please enter your registered email address..." id="email" aria-describedby="basic-addon1">   
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-3"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 col-md-3"></div>
                            <div class="col-xs-12 col-sm-8 col-md-6">
                                <div class="col-md-2"></div>
                                <div class="col-xs-12 col-sm-9 col-md-10 identify-email-bottom-group ">
                                    <input type="submit" class="btn submit-email-form" name="submit" value="Submit"/>  
                                    <a href="/" class="btn btn-default-1">CANCEL</a>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-3"></div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
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

