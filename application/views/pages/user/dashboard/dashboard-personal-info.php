<link type="text/css" href='/assets/css/jquery-ui.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/jquery-ui.theme.min.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<div class="div-tab">
    <div class="dashboard-breadcrumb">
        <ul>
            <li>Dashboard</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>My Account</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>Personal Information</li>
        </ul>
    </div>
    <div class="div-tab-inner">
        <div class="div-personal-info">
            <p class="panel-dashboard-title">Personal Information</p>
            <form class="form-horizontal" id="formPersonalInfo" role="form">
                <input type="hidden"/>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Real Name : </label>
                    <div class="col-sm-8">
                        <input type="text" class="text-info" value="<?php echo html_escape($fullname)?>" name ="fname" id="fullname" placeholder="First Name">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Gender : </label>
                    <div class="col-sm-5 col-with-radio">
                        <input type="radio" id="male" name="gender" value="M" <?php echo ($gender=='M'?'checked="true"':'') ?>/> <label class="lbl-radio" for="male">Male</label>
                        <input type="radio" id="female" name="gender" value="F" <?php echo ($gender=='F'?'checked="true"':'') ?>/> <label class="lbl-radio" for="female">Female</label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Birthday : </label>
                     <div class="col-sm-8">
                        <input type="text" value="<?php echo html_escape($birthday == '0000-00-00' || $birthday == '0001-01-01'?'':$birthday)?>" name="birthday" class="text-info" id="birthday-picker" placeholder="Pick the date of your birthday here">
                    </div>

                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Mobile Number : </label>
                     <div class="col-sm-8">
                        <input type="text" id="mobileNumber" value="<?php echo html_escape($contactno);?>" name="mobile" class="text-info text-required" placeholder="Enter your 11 digit mobile number here">
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
