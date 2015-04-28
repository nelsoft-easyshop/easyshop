<div class="div-tab">
    <div class="div-tab-inner payment-account-container">
        <div class="div-account-setting">
            <p class="panel-setting-title" id="panel-setting-title">Payment Account</p>
            <p class="p-note-setting">
                Your payment account will be used whenever a purchased is made for your item. Refunds may also be transferred to any of these
                accounts if a paypal account is not available.
            </p>
            <div class="add-bank-btn-con">
                <button class="btn btn-default-3 add-bank-account"><i class="fa fa-plus"></i>Add a Bank Account</button>
            </div>
            <div class="select-bank">
                <div class="row">
                    <?php echo form_open('/memberpage/createPaymentAccount', [ 'id' => 'newPaymentForm' ]); ?>
                        <div class="col-xs-12 col-sm-12"><strong>Bank Name:</strong></div>
                        <div class="col-xs-12 col-sm-12">
                            <select class="text-info mrgn-top-10 bank-dropdown" name='account-bank-id'>
                                <option value="0">Please select a bank</option>
                            </select>
                        </div>
                        <div class="clear pd-top-10">
                            <div class="col-xs-12 col-sm-6">
                                <strong>Account Name:</strong>
                                <input type="text" class="text-info mrgn-top-10 account-name-input" name='account-name' >
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <strong>Account Number:</strong>
                                <input type="text" class="text-info mrgn-top-10 account-number-input" name='account-number' >
                            </div>
                        </div>
                        <div class="col-xs-12 text-center bank-btns-con pd-top-30">
                            <span class="btn btn-default-1 cancel-add-bank">Cancel</span>
                            <input type="submit" class="btn btn-default-3" value="Save">
                        </div> 
                    <?php echo form_close(); ?>

                </div>
                <br/>
                <div class="alert alert-danger" id="payment-create-error" role="alert" style="display:none;">
                    
                </div>
                
            </div>
        </div>
        <div class="clear"></div>
        
        <div class="div-store-setup payment-account-loading" style="text-align:center">
            <img src="<?php echo getAssetsDomain(); ?>assets/images/es-loader-3-md.gif"/>
            <br/><br/>
            <p class="loading-text">Loading your payment accounts...</p>
        </div>

        <div id="payment-account-template" class="bank-account-item mrgn-bttm-15" style="display:none">
            <div class="col-xs-8">
                <div class="pd-top-10">
                    <span class="col-xs-5 col-sm-4 bank-label">Bank Name:</span>
                    <span class="col-xs-7 col-sm-8 bank-name-container"></span>
                    <span class="col-xs-7 col-sm-8 edit-bank" style="display:none">
                        <select class="text-info"></select>
                    </span>
                    <input type="hidden" class="bank-id" value="0"/>
                    
                </div>
                <div class="clear pd-top-10">
                    <span class="col-xs-5 col-sm-4 bank-label">Account Name:</span>
                    <span class="col-xs-7 col-sm-8 account-name-container">
                    </span>
                    <span class="col-xs-7 col-sm-8 edit-account-name" style="display:none">
                        <input type="text" class="text-info" placeholder="Add an account name"/>
                    </span>
                </div>
                <div class="clear pd-top-10">
                    <span class="col-xs-5 col-sm-4 bank-label">Account Number:</span>
                    <span class="col-xs-7 col-sm-8 account-number-container"></span>
                    
                    <span class="col-xs-7 col-sm-8 edit-account-number" style="display:none">
                        <input type="text" class="text-info" placeholder="Add an account name"/>
                    </span>
                </div>
                <div class="clear pd-top-5">
                    <br/>
                    <div class="alert alert-danger update-payment-account-error" role="alert" style="display:none">
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="col-xs-4 bank-account-item-btns">
                <span class="btn btn-set-default">Default Account</span>
                <span class="btn btn-default-1 mrgn-2 mrgn-top-10 edit-account-btn"><i class="icon-edit"></i> <span>Edit</span></span>
                <span class="btn btn-default-1 mrgn-top-10 delete-account-btn"><i class="icon-delete"></i> <span>Delete</span></span>
                
                <span class="btn btn-default-3 mrgn-2 mrgn-top-10 save-edit-btn" style="display:none">Save</span>
                <span class="btn btn-default-1 mrgn-top-10 cancel-edit-btn" style="display:none">Cancel</span>
                
            </div>
            <div class="clear"></div>
            <input type="hidden" class="payment-account-id" value="0"/>
        </div>
        <div class="clear"></div>  
</div>
</div>
