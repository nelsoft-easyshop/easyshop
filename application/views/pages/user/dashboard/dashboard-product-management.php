
<style>
        .activated-container{
            height: 220px !Important;
        }

    @media only screen and (max-width: 991px){
        .activated-container{
            height: 288px !important;
        }
    }
</style>


<div class="div-tab">
    <div class="dashboard-breadcrumb">
        <ul>
            <li>Dashboard</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>My Store</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>Product Management</li>
        </ul>
    </div>
    <div class="div-tab-inner">
        <div class="div-account-setting">
            <p class="panel-setting-title">Restore Inactive Items</p>
            <div class="div-setting-content">
                <p class="p-deact-note-setting">
                    Restores all inactive products to an Active status, making them available to all users again.
                </p>
                <div class="current-activate-prod">
                    <button class="btn btn-default-3 btn-prod-manage" id="activate-products">
                       Restore all inactive items
                    </button>
                </div>
                <div class="edit-activate-prod">
                    <form class="form-horizontal" id="deactivateAccountForm">
                        <p class="p-orange"> Are you sure you want to activate your products? </p>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Your Username : </label>
                            <div class="col-sm-5">
                                <input type="text" class="text-info text-required" placeholder="Type your username here">
                            </div>
                        </div>                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Your Password : </label>
                            <div class="col-sm-5">
                                <input type="password" class="text-info text-required" placeholder="Type your current password here">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-5">
                                <input class="btn btn-setting-save-btn" type='submit' value='Activate'/>
                                <span class="btn btn-setting-cancel-btn" id="cancel-activate-products">
                                    Cancel
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="div-tab-inner">
        <div class="div-account-setting">
            <p class="panel-setting-title">Disable Active Items</p>
            <div class="div-setting-content">
                <p class="p-deact-note-setting">
                    Sets all your active products to an Inactive status. This will remove your product from our search results and will no longer be visible to our users.
                </p>
                <div class="current-deactivate-prod">
                    <button class="btn btn-default-3 btn-prod-manage" id="deactivate-products">
                       Disable all active items
                    </button>
                </div>
                <div class="edit-deactivate-prod">
                    <form class="form-horizontal" id="deactivateAccountForm">
                        <p class="p-orange"> Are you sure you want to activate your products? </p>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Your Username : </label>
                            <div class="col-sm-5">
                                <input type="text" class="text-info text-required" placeholder="Type your username here">
                            </div>
                        </div>                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Your Password : </label>
                            <div class="col-sm-5">
                                <input type="password" class="text-info text-required" placeholder="Type your current password here">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-5">
                                <input class="btn btn-setting-save-btn" type='submit' value='Deactivate'/>
                                <span class="btn btn-setting-cancel-btn" id="cancel-deactivate-products">
                                    Cancel
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="div-tab-inner">
        <div class="div-account-setting">
            <p class="panel-setting-title">Delete Inactive Items</p>
            <div class="div-setting-content">
                <p class="p-deact-note-setting">
                    Permanently deletes all your inactive products. This is irreversible, please use this button with caution.
                </p>
                <div class="current-delete-prod">
                    <button class="btn btn-default-1 btn-prod-manage"  id="delete-products">
                       Delete all inactive items
                    </button>
                </div>
                <div class="edit-delete-prod">
                    <form class="form-horizontal" id="deactivateAccountForm">
                        <p class="p-orange"> Are you sure you want to activate your products? </p>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Your Username : </label>
                            <div class="col-sm-5">
                                <input type="text" class="text-info text-required" placeholder="Type your username here">
                            </div>
                        </div>                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Your Password : </label>
                            <div class="col-sm-5">
                                <input type="password" class="text-info text-required" placeholder="Type your current password here">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-5">
                                <input class="btn btn-setting-save-btn" type='submit' value='Delete'/>
                                <span class="btn btn-setting-cancel-btn" id="cancel-delete-products">
                                    Cancel
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
