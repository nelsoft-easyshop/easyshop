<div class="div-tab">
    <div class="dashboard-breadcrumb">
        <ul>
            <li>Dashboard</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>My Account</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>Delivery Address</li>
        </ul>
    </div>
    <div class="div-tab-inner">
        <div class="div-personal-info">
            <p class="panel-dashboard-title">Delivery Address</p>
            <input type="hidden" name="c_country" value="<?php echo $country_id?>">
            <?php echo form_open('',array('id'=>'deliverAddressForm','class' => 'form-horizontal', 'role' => 'form'));?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Consignee Name : </label>
                    <div class="col-sm-4">
                        <input type="text" id="consigneeName" name="consignee" value="<?php echo html_escape($consignee)?>"  class="text-info" placeholder="Consignee name">
                        <div id="errorsDivConsignee" style="display:none;">
                            <span class="val-error-icon"><i class="fa fa-times"></i></span>
                            <span class="val-error" id="errorTextConsignee"></span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Mobile Number : </label>
                     <div class="col-sm-8">
                        <input type="text" class="text-info text-required" value="<?php echo html_escape($c_mobile)?>" id="consigneeMobile" name="c_mobile" placeholder="Enter your 11 digit mobile number here">
                        <div id="errorsDivMobile" style="display:none;">
                            <span class="val-error-icon"><i class="fa fa-times"></i></span>
                            <span class="val-error" id="errorTextMobile"></span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Telephone Number : </label>
                     <div class="col-sm-8">
                        <input type="text" class="text-info text-required" value="<?php echo html_escape($c_telephone)?>" id="consigneeLandLine" name="c_telephone" placeholder="Enter your telephone number here">
                        <!--DISPLAY WHEN OK-->
                        <!-- <span class="val-success"><i class="fa fa-check"></i></span> -->
                    </div>
                </div>
                
                 <div class="form-group">
                    <label class="col-sm-3 control-label">Address : </label>
                    <div class="col-sm-4">
                        <select class="text-info text-address address_dropdown stateregionselect" id="deliver_stateregion" name="c_stateregion" data-status="<?php echo $c_stateregionID?>">
                            <option value="0">--Select State/Region--</option>
                            <?php foreach($stateregion_lookup as $srkey=>$stateregion):?>
                                <option class="echo" value="<?php echo $srkey?>" <?php echo $c_stateregionID == $srkey ? "selected":"" ?>><?php echo $stateregion?></option>
                            <?php endforeach;?>
                        </select>
                        <input type="hidden" name="cstateregion_orig" value="<?php echo $c_stateregionID?>">
                        <span class="span-label-address">State/Region</span>
                    </div>
                    <div class="col-sm-4 col-city">
                        <select class="text-info text-address address_dropdown cityselect stateregionselect" id="delivery_city" name="c_city" data-status="<?php echo $c_cityID?>">
                            <option value="0">--- Select City ---</option>
                            <option class="optionclone" value="" style="display:none;" disabled></option>
                            <?php if($c_cityID != '' && $c_stateregionID != ''):?>
                                <?php foreach($city_lookup[$c_stateregionID] as $lockey=>$city):?>
                                    <option class="echo" value="<?php echo $lockey?>" <?php echo $c_cityID == $lockey ? "selected":"" ?> ><?php echo $city?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                        <input type="hidden" name="ccity_orig" value="<?php echo $c_cityID?>">                        
                        <span class="span-label-address">City</span>
                    </div>
                    <div class="col-sm-4 col-city">
                        <input type="hidden" name="c_country" value="<?php echo $country_id?>">                        
                    </div>                    
                </div>
                
                <div class="form-group">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-8">
                        <input type="text" class="text-info text-address" id="deliveryAddress" value="Brgy. San Roque, Evergreen Village" name="c_address" placeholder="Enter your street address here">
                        <span class="span-label-address">Street Address</span>
                        <input type="hidden" name="caddress_orig" value="<?php echo html_escape($c_address)?>">
                        <div id="errorsDivStreetAddress" style="display:none;">
                            <span class="val-error-icon"><i class="fa fa-times"></i></span>
                            <span class="val-error" id="errorTextStreetAddress"></span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="input-checkbox-dash" id="check-default c_def_address" name="c_def_address"/>
                        <label id="set-default" class="lbl-checkbox" for="check-default">Set as default address <i class="fa fa-question-circle"></i></label>
                        <div class="default-ad-explain">
                            Setting as default updates address in Personal Information
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Map Location : </label>
                    <div class="col-sm-8 col-map">
                        <span>Location not marked</span> <span class="map-trigger">Mark on map <i class="fa fa-caret-down"></i></span>
                        <input type="hidden" name="map_lat" id="map_clat" value="<?php echo $c_lat;?>">
                        <input type="hidden" name="map_lng" id="map_clng" value="<?php echo $c_lng;?>">
                        <input type="hidden" name="temp_lat" id="temp_clat" value="<?php echo $c_lat;?>">
                        <input type="hidden" name="temp_lng" id="temp_clng" value="<?php echo $c_lng;?>">
                        <div class="map-container">
                            <span><a href="javascript:void(0);" class='refresh_map'><i class="fa fa-search"></i> Search Address</a></span>
                            <span class="span-current-location"><a href="#"><i class="fa fa-map-marker"></i> Current Location</a><span>
                            <div id="GoogleMapContainer" title="Google Map Container"></div>

                            <div id="delivery_mapcanvas" class="map-canvas"  class="map-canvas" frameborder="0" style="max-width:500px;max-height:450px;"></div>                           
                            <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3861.5040209573253!2d120.9912379!3d14.570331999999999!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c981a81d19cb%3A0x908919dcb6a3f9f1!2sMarc+2000+Tower%2C+%231973+San+Andres+St%2C+Malate%2C+Manila%2C+Metro+Manila!5e0!3m2!1sen!2sph!4v1415776376560" width="600" height="450" frameborder="0" style="border:0"></iframe> -->
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-3"></div>
                     <div class="col-sm-8">
                        <input type="submit" class="btn btn-lg btn-save-dashboard" name="c_deliver_address_btn" id="saveDeliverAddressBtn" value="Save Changes"/>
                    </div>
                </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>