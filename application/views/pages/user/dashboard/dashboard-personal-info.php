
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link type="text/css" href='/assets/css/vendor/bower_components/jquery-ui.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <link type="text/css" href='/assets/css/vendor/bower_components/jquery.ui.theme.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php else: ?>
    <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.dashboard-personal-info.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
<?php endif; ?>

<div class="div-tab">
    <div class="div-tab-inner">
        <div class="div-personal-info">
            <p class="panel-dashboard-title">Personal Information</p>
            <form class="form-horizontal" id="formPersonalInfo" role="form">
                <input type="hidden"/>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Real Name : </label>
                    <div class="col-sm-8">
                        <input type="text" class="text-info" value="<?php echo html_escape($member->getFullname())?>" name ="fname" id="fullname" placeholder="First Name">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Gender : </label>
                    <div class="col-sm-8 col-with-radio">
                        <input type="radio" id="male" name="gender" value="M" <?php echo ($member->getGender()=='M'?'checked="true"':'') ?>/> <label class="lbl-radio" for="male">Male</label>
                        <input type="radio" id="female" name="gender" value="F" <?php echo ($member->getGender()=='F'?'checked="true"':'') ?>/> <label class="lbl-radio" for="female">Female</label>
                        <div id="errorIndicatorGender" style="display:none;">
                            <span class="val-error-icon" id="errorIndicator"><i class="fa fa-times"></i></span>
                            <span class="val-error" id="errorTextGender"></span>
                        </div>                          
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Birthday : </label>
                     <div class="col-sm-8">
                        <input type="text" value="<?= ($member->getBirthday()->format('Y-m-d') == EasyShop\Entities\EsMember::DEFAULT_DATE )? '' : $member->getBirthday()->format('Y-m-d')?>" name="birthday" class="text-info" id="birthday-picker" placeholder="Pick the date of your birthday here">
                        <div id="errorIndicatorBirthday" style="display:none;">
                            <span class="val-error-icon" id="errorIndicator"><i class="fa fa-times"></i></span>
                            <span class="val-error" id="errorTextBirthday"></span>
                        </div>                    
                    </div>

                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Mobile Number : </label>
                     <div class="col-sm-8">
                        <input type="text" id="mobileNumber" value="<?= $member->getContactno() !== '' ? '0'.html_escape($member->getContactno()) : '';?>" name="mobile" class="text-info text-required" maxlength="11" placeholder="Enter your 11 digit mobile number here">
                        <!-- DISPLAY WHEN ERROR -->
                        <div id="errorIndicatorMobileNumber" style="display:none;">
                            <span class="val-error-icon" id="errorIndicator"><i class="fa fa-times"></i></span>
                            <span class="val-error" id="errorTextMobile"></span>
                        </div>
                        
                        <!--DISPLAY WHEN OK
                        <span class="val-success"><i class="fa fa-check"></i></span>-->
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-3"></div>
                     <div class="col-sm-8">
                        <button id="savePersonalInfo" class="btn btn-lg btn-save-dashboard">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
