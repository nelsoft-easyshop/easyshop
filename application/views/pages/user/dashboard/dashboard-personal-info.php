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
            <form class="form-horizontal" role="form">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Real Name : </label>
                    <div class="col-sm-4">
                        <input type="text" class="text-info" placeholder="First Name">
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="text-info" placeholder="Last Name">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Gender : </label>
                    <div class="col-sm-5 col-with-radio">
                        <input type="radio" id="male" name="gender"/> <label class="lbl-radio" for="male">Male</label>
                        <input type="radio" id="female" name="gender"/> <label class="lbl-radio" for="female">Female</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Birthday : </label>
                     <div class="col-sm-8">
                        <input type="text" class="text-info" id="datepicker" placeholder="Pick the date of your birthday here">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Mobile Number : </label>
                     <div class="col-sm-8">
                        <input type="text" class="text-info text-required" placeholder="Enter your 11 digit mobile number here">
                        <!-- DISPLAY WHEN ERROR -->
                        <span class="val-error-icon"><i class="fa fa-times"></i></span>
                        <span class="val-error">Please enter at least 11 characters</span>
                        
                        <!--DISPLAY WHEN OK
                        <span class="val-success"><i class="fa fa-check"></i></span>-->
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Email Address : </label>
                     <div class="col-sm-8">
                        <input type="text" class="text-info text-required" placeholder="Enter your email address here">
                         <!-- DISPLAY WHEN SUCCESS -->
                        <span class="val-success"><i class="fa fa-check"></i></span>
                        <!-- DISPLAY WHEN ERROR
                        <span class="val-error-icon"><i class="fa fa-times"></i></span>
                        <span class="val-error">Invalid email format</span> -->
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-3"></div>
                     <div class="col-sm-8">
                        <button class="btn btn-lg btn-save-dashboard">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>