<div class="div-tab">
    <div class="div-personal-info">
        <p class="panel-dashboard-title">Delivery Address</p>
        <form class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-sm-3 control-label">Consignee Name : </label>
                <div class="col-sm-4">
                    <input type="text" class="text-info" placeholder="First Name">
                </div>
                <div class="col-sm-4">
                    <input type="text" class="text-info" placeholder="Last Name">
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
                <label class="col-sm-3 control-label">Telephone Number : </label>
                 <div class="col-sm-8">
                    <input type="text" class="text-info text-required" placeholder="Enter your telephone number here">
                    <!-- DISPLAY WHEN ERROR 
                    <span class="val-error-icon"><i class="fa fa-times"></i></span>
                    <span class="val-error">Please enter at least 11 characters</span>
                    -->
                    <!--DISPLAY WHEN OK-->
                    <span class="val-success"><i class="fa fa-check"></i></span>
                </div>
            </div>
            
             <div class="form-group">
                <label class="col-sm-3 control-label">Address : </label>
                <div class="col-sm-4">
                    <select class="text-info">
                        <option>--Select State/Region--</option>
                        <option>Bataan</option>
                    </select>
                    <span class="span-label-address">State/Region</span>
                </div><div class="col-sm-4">
                    <select class="text-info">
                        <option>--City--</option>
                        <option>Mariveles</option>
                    </select>
                    <span class="span-label-address">City</span>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-3"></div>
                <div class="col-sm-8">
                    <input type="text" class="text-info" placeholder="Enter your street address here">
                    <span class="span-label-address">Street Address</span>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-3"></div>
                <div class="col-sm-8">
                    <input type="checkbox" class="input-checkbox-dash" id="check-default"/>
                    <label id="set-default" class="lbl-checkbox" for="check-default">Set as default address <i class="fa fa-question-circle"></i></label>
                    <div class="default-ad-explain">
                        Setting as default updates address in Personal Information
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label">Map Location : </label>
                <div class="col-sm-8">
                    <p>Location not marked</p> <span class="map-trigger">Map</span>
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3861.5040209573253!2d120.9912379!3d14.570331999999999!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c981a81d19cb%3A0x908919dcb6a3f9f1!2sMarc+2000+Tower%2C+%231973+San+Andres+St%2C+Malate%2C+Manila%2C+Metro+Manila!5e0!3m2!1sen!2sph!4v1415776376560" width="600" height="450" frameborder="0" style="border:0"></iframe>
                    </div>
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