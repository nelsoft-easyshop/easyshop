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
            <input type="hidden" name="c_country" value="<?php echo html_escape($countryId) ?>">
            <?php echo form_open('',array('id'=>'deliverAddressForm','class' => 'form-horizontal', 'role' => 'form'));?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Consignee Name : </label>
                    <div class="col-sm-8">
                        <input type="text" id="consigneeName" name="consignee" value="<?php echo html_escape(($address) ? $address->getConsignee() : '')?>"  class="text-info" placeholder="Consignee name">
                        <div id="errorsDivConsignee" style="display:none;">
                            <span class="val-error-icon"><i class="fa fa-times"></i></span>
                            <span class="val-error" id="errorTextConsignee"></span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Mobile Number : </label>
                     <div class="col-sm-8">
                        <input type="text" class="text-info text-required" value="0<?php echo html_escape(($address) ? $address->getMobile() : '')?>" id="consigneeMobile" name="c_mobile" placeholder="Enter your 11 digit mobile number here">
                        <div id="errorsDivMobile" style="display:none;">
                            <span class="val-error-icon"><i class="fa fa-times"></i></span>
                            <span class="val-error" id="errorTextMobile"></span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Telephone Number : </label>
                     <div class="col-sm-8">
                        <input type="text" class="text-info text-required" value="<?php echo html_escape(($address) ? $address->getTelephone() : '')?>" id="consigneeLandLine" name="c_telephone" placeholder="Enter your telephone number here">
                        <!--DISPLAY WHEN OK-->
                        <!-- <span class="val-success"><i class="fa fa-check"></i></span> -->
                    </div>
                </div>
                
                 <div class="form-group">
                    <label class="col-sm-3 control-label">Address : </label>
                    <div class="col-sm-4">
                        <select class="text-info text-address address_dropdown stateregionselect" id="deliver_stateregion" name="c_stateregion" data-status="<?php echo $consigneeStateRegionId?>">
                            <option value="0">--Select State/Region--</option>
                            <?php foreach($stateRegionLists as $srkey=>$stateregion):?>
                                <option class="echo" value="<?php echo $srkey?>" <?php echo $consigneeStateRegionId == $srkey ? "selected":"" ?>><?php echo $stateregion?></option>
                            <?php endforeach;?>
                        </select>
                        <input type="hidden" name="cstateregion_orig" value="<?php echo $consigneeStateRegionId?>">
                        <span class="span-label-address">State/Region</span>
                    </div>
                    <div class="col-sm-4 col-city">
                        <select class="text-info text-address address_dropdown cityselect stateregionselect" id="delivery_city" name="c_city" data-status="<?php echo $consigneeCityId?>">
                            <option value="0">--- Select City ---</option>
                            <option class="optionclone" value="" style="display:none;" disabled></option>
                            <?php if($consigneeCityId != '' && $consigneeStateRegionId != ''):?>
                                <?php foreach($cityLookup[$consigneeStateRegionId] as $lockey=>$city):?>
                                    <option class="echo" value="<?php echo $lockey?>" <?php echo $consigneeCityId == $lockey ? "selected":"" ?> ><?php echo $city?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                        <input type="hidden" name="ccity_orig" value="<?php echo $consigneeCityId?>">                        
                        <span class="span-label-address">City</span>
                    </div>
                    <div class="col-sm-4 col-city">
                        <input type="hidden" name="c_country" value="<?php echo $countryId?>">                        
                    </div>                    
                </div>
                
                <div class="form-group">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-8">
                        <input type="text" class="text-info text-address" id="deliveryAddress" value="Brgy. San Roque, Evergreen Village" name="c_address" placeholder="Enter your street address here">
                        <span class="span-label-address">Street Address</span>
                        <input type="hidden" name="caddress_orig" value="<?php echo html_escape(($address) ? $address->getAddress() : '')?>">
                        <div id="errorsDivStreetAddress" style="display:none;">
                            <span class="val-error-icon"><i class="fa fa-times"></i></span>
                            <span class="val-error" id="errorTextStreetAddress"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Map Location : </label>
                    <div class="col-sm-8 col-map">
                        <span>Location not marked</span> <span class="map-trigger">Mark on map <i class="fa fa-caret-down"></i></span>
                        <input type="hidden" name="map_lat" id="map_clat" value="<?php echo ($address) ? $address->getLat() : '' ?>">
                        <input type="hidden" name="map_lng" id="map_clng" value="<?php echo ($address) ? $address->getLng() : ''?>">
                        <input type="hidden" name="temp_lat" id="temp_clat" value="<?php echo ($address) ? $address->getLat() : '' ?>">
                        <input type="hidden" name="temp_lng" id="temp_clng" value="<?php echo ($address) ? $address->getLng() : ''?>">
                        <input type="hidden" name="current_lat" id="current_lat" value="">
                        <input type="hidden" name="current_lang" id="current_lang" value="">
                        <div class="map-container">
                            <span class='refresh_map'><a href="javascript:void(0);"><i class="fa fa-search"></i> Search Address</a></span>
                            <span><a href="javascript:void(0)" class="span-current-location"><i class="fa fa-map-marker span-current-location"></i> Current Location</a><span>
                            <div id="GoogleMapContainer" title="Google Map Container"></div>

                            <div id="delivery_mapcanvas" class="map-canvas"  class="map-canvas" frameborder="0" style="max-width:500px;max-height:450px;display:block;"></div>                           
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