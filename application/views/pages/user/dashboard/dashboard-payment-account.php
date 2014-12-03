<div class="div-tab">
    
    <div class="div-tab-inner payment-account-container">
        <div class="div-account-setting">
            <p class="panel-setting-title">Payment Account</p>
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

        <div id="payment-account-template" class="bank-account-item mrgn-bttm-15" style="display:none">
            <div class="col-xs-8">
                <div class="pd-top-10">
                    <span class="col-xs-5 col-sm-4 bank-label">Bank Name:</span>
                    <span class="col-xs-7 col-sm-8 bank-name-container"></span>
                </div>
                <div class="clear pd-top-10">
                    <span class="col-xs-5 col-sm-4 bank-label">Account Name:</span>
                    <span class="col-xs-7 col-sm-8 account-name-container"></span>
                </div>
                <div class="clear pd-top-10">
                    <span class="col-xs-5 col-sm-4 bank-label">Account Number:</span>
                    <span class="col-xs-7 col-sm-8 account-number-container"></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="col-xs-4 bank-account-item-btns">
                <span class="btn btn-set-default">Set as Default Account</span>
                <span class="btn btn-default-1 mrgn-2 mrgn-top-10"><span class="icon-edit"></span> Edit</span>
                <span class="btn btn-default-1 mrgn-top-10"><span class="icon-delete"></span> Delete</span>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
  
</div>