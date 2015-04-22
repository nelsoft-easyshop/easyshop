
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link type="text/css" href='/assets/css/new-dashboard.css' rel="stylesheet" media='screen'/>
<?php else: ?>
    <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.member-account-activate.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
<?php endif; ?>

<style>
#simplemodal-container{
        height: 150px !important;
    }
#simplemodal-container h2{
    padding: 14px 20px;
    font-size: 18px;
    font-weight: 900;
    color: #565656;
    }
.simplemodal-wrap {
    margin: 36px 0;
}
#activated-message {
    padding: 0;
}

.feedback-content {
    border: none;
}

@media only screen and (max-width: 991px){
        #simplemodal-container{
            height: 200px !important;
        }
    }
</style>
<div class="activateAccount">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
                <div class="panel panel-default panel-activate">
                    <div class="panel-heading">Activate your account</div>
                    <div class="panel-body">
                        <p class="p-panel-body">
                        Hello there!<br>

                        We're really excited that you've decided to reactivate your account with us.<br>
                        Just fill in the required information below and and you'll be done in a jiffy.<br>
                        </p>
                        <br>
                            <?php 
                                $attr = array('class' => 'form-horizontal','id' => 'activateAccountForm');
                                echo form_open('/memberpage/doReactivateAccount', $attr);
                             ?>                             
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Type your username: </label>
                                <div class="col-sm-7">
                                    <input type = "username"  id="username" name="username" class="text-info text-required" placeholder="Type your username here">
                                </div> 
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Type your password : </label>
                                <div class="col-sm-7">
                                    <input type = "hidden" name="h" value="<?php echo $hash; ?>">
                                    <input type = "hidden" name="userId" value="<?php echo $idMember; ?>">
                                    <input type = "password"  id="password" name="password" class="text-info text-required" placeholder="Type your password here">
                                </div> 
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Confirm your password : </label>
                                <div class="col-sm-7">
                                    <input type = "password"  id="confirmPassword" name="confirmPassword" class="text-info text-required" placeholder="Confirm your password here">
                                </div>
                            </div>
                            <div class="activateActions">
                                <center>
                                    <input type="submit" class="btn btn-setting-save-btn" id="activateAccountButton" name="activateAccountButton"  value="Submit">
                                    <a href="/" class="btn btn-setting-cancel-btn" id="cancel-edit-password">
                                        Cancel
                                    </a>
                                </center>
                            </div>
                            <div style='text-align:center !important;'>
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/orange_loader_small.gif" id="deactivateAccountLoader" style="display:none;"/>                                
                            </div>
                            <div class="alert alert-danger" style="display:none;" role="alert" id="password-change-error">
                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                Sorry, but you have entered invalid credentials
                            </div>                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="activated-modal" style="display:none; height: 100px;">
    <div class="feedback-content">
        <div id="activated-message">
             Congratulations! You've just reactivated your account with EasyShop!

            <a href="/home">Click here</a> to go back to our Homepage and start browsing for your favourite items.
        </div>
    </div>
</div>
<div id="invalidCredentials" style="display:none; height: 100px;">
    <div class="feedback-content">
        <div id="activated-message" class="invalidCredentialsMessage">
             Sorry, but you have entered invalid credentials

        </div>
    </div>
</div>
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type='text/javascript' src="/assets/js/src/vendor/bower_components/jquery.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src='/assets/js/src/vendor/bower_components/jquery.validate.js'></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js?ver=<?=ES_FILE_VERSION?>'></script>
    <script type='text/javascript' src="/assets/js/src/accountactivation.js?ver=<?=ES_FILE_VERSION?>"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.MemberPageAccountActivate.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

