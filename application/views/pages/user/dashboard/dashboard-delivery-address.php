<div class="div-tab ">
    <div class="div-delivery-setup delivery-setup-loading" style="text-align:center">
        <br/><br/>
        <img src="<?php echo getAssetsDomain(); ?>assets/images/es-loader-3-md.gif"/>
        <br/><br/>
        <p class="loading-text">Loading your delivery address info...</p>
    </div>
    <div id="deliverAddressDiv" style="display:none;">
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
                <input type="hidden" name="c_country" value="">
                <?php echo form_open('',array('id'=>'deliverAddressForm','class' => 'form-horizontal', 'role' => 'form'));?>
                    <div class="row">
                        <div class="col-md-11 col-alert">
                            <div class="alert alert-es-danger alert-dismissible" style="display:none" role="alert" id="delivery-address-error">
                                Please fix the errors in the delivery address you have provided.
                            </div>
                            
                            <div class="alert alert-es-success" style="display:none" role="alert" id="delivery-address-success">
                                Delivery address updated successfully.
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Consignee Name : </label>
                        <div class="col-sm-8">
                            <input type="text" id="consigneeName" name="consignee" value=""  class="text-info" placeholder="Consignee name">
                            <div id="errorsDivConsignee" style="display:none;">
                                <span class="val-error-icon"><i class="fa fa-times"></i></span>
                                <span class="val-error" id="errorTextConsignee"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Mobile Number : </label>
                         <div class="col-sm-8">
                            <input type="text" class="text-info text-required" value="" id="consigneeMobile" maxlength="11" name="c_mobile" placeholder="Enter your 11 digit mobile number here">
                            <div id="errorsDivMobile" style="display:none;">
                                <span class="val-error-icon"><i class="fa fa-times"></i></span>
                                <span class="val-error" id="errorTextMobile"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Telephone Number : </label>
                         <div class="col-sm-8">
                            <input type="text" class="text-info text-required" value="" id="consigneeLandLine" name="c_telephone" placeholder="Enter your telephone number here">
                            <div id="errorsDivTelephone" style="display:none;">
                                <span class="val-error-icon"><i class="fa fa-times"></i></span>
                                <span class="val-error" id="errorTextTelephone"></span>
                            </div>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-sm-3 control-label">Address : </label>
                        <div class="col-sm-4">
                            <select class="text-info text-address address_dropdown stateregionselect" id="deliver_stateregion" name="c_stateregion">
                                <option value="">--Select State/Region--</option>
   
                            </select>
                            <span class="span-label-address">State/Region</span>
                            <div id="errorsRegionDiv" style="display:none;">
                                <span class="val-error-icon"><i class="fa fa-times"></i></span>
                                <span class="val-error" id="errorTextRegion"></span>
                            </div>
                            <br/>
                        </div>
                        <div class="col-sm-4">
                            <select class="text-info text-address address_dropdown cityselect" id="delivery_city" name="c_city">
                                <option value="">--- Select City ---</option>
                                <option class="optionclone" value="" style="display:none;" disabled></option>
        
                            </select>
                            <span class="span-label-address">City</span>
                            <div id="errorsCityDiv" style="display:none;">
                                <span class="val-error-icon"><i class="fa fa-times"></i></span>
                                <span class="val-error" id="errorTextCity"></span>
                            </div>
                            <br/>
                        </div>
                
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-8">
                            <input type="text" class="text-info text-address" id="deliveryAddress" value="" name="c_address" placeholder="Enter your street address here">
                            <span class="span-label-address">Street Address</span>
                            <div id="errorsDivStreetAddress" style="display:none;">
                                <span class="val-error-icon"><i class="fa fa-times"></i></span>
                                <span class="val-error" id="errorTextStreetAddress"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Map Location : </label>
                        <div class="col-sm-8 col-map">
                            <span id="locationMarkedText"></span>
                            <span class="map-trigger">Mark on map <i class="fa fa-caret-down"></i></span>
                            <input type="hidden" name="map_lat" id="map_clat" value="">
                            <input type="hidden" name="map_lng" id="map_clng" value="">
                            <input type="hidden" name="temp_lat" id="temp_clat" value="">
                            <input type="hidden" name="temp_lng" id="temp_clng" value="">
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
</div>

