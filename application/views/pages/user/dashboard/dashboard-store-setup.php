<div class="div-tab">
    <div class="dashboard-breadcrumb">
        <ul>
            <li>Dashboard</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>My Store</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>Store Setup</li>
        </ul>
    </div>
    <div class="div-tab-inner">
        <div class="div-store-setup">
            <p class="panel-setup-title">Store Name</p>
            <div class="div-setup-content">
                <div class="current-store-name">
                    <span class="setting-current-email" id="store-name-display"><?php echo html_escape( $member->validatedStoreName ) ?></span> 
                    <button class="btn btn-setting-edit-btn" id="btn-edit-store-name">
                       <i class="icon-edit"></i> Edit
                    </button>
                </div>
                <div class="edit-store-name">
                    <div class="row">
                        <div class="col-md-5 col-inline-textbtn">
                            <input id="input-store-name" type="text" class="text-info text-required" value="<?php echo html_escape($member->validatedStoreName); ?>"/>

                            <span class="val-error-icon-setup" id="fail-icon-store-name" style="display:none">
                                <i class="fa fa-times"></i>
                            </span>
                            <span class="val-error" id="fail-message-store-name" style="display:none">
                                
                            </span>
                        </div>
                        <div class="col-md-5 col-action-buttons">
                            <button class="btn btn-setting-save-btn save-store-setting" data-variable="store-name">
                                Save changes
                            </button>
                            <button class="btn btn-setting-cancel-btn" id="cancel-edit-store-name">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
                <p class="p-note-setting">
                    You can give your store a different name other than you're registered username. Improve you're store's visibility and branding by giving you're store a name that other people can easily recognize and remember.
                </p>
            </div>
        </div>
        <div class="div-store-setup">
            <p class="panel-setup-title">Store Link</p>
            <div class="div-setup-content">
                <div class="current-store-url current-store-slug">
                    <span class="setting-current-email"  id="store-slug-display" data-slug="<?php echo html_escape($member->getSlug()); ?>">
                        <a href="<?php echo base_url().html_escape($member->getSlug()); ?>"> 
                            <?php echo base_url().html_escape($member->getSlug()); ?>
                        </a>
                    </span> 

                    <?php if(!$member->getIsSlugChanged()): ?>
                        <button class="btn btn-setting-edit-btn" id="btn-edit-store-url">
                        <i class="icon-edit"></i> Edit
                        </button>
                    <?php endif; ?>
                    
                </div>
                <div class="edit-store-url edit-store-slug">
                    <div class="row">
                        <div class="col-md-7 col-inline-textbtn">
                            <div class="div-url-container">
                                <table>
                                    <tr>
                                        <td>
                                             <span class="setting-edit-url"><?php echo base_url(); ?></span>
                                        </td>
                                        <td width="100%">
                                            <input type="text" class="text-info-url text-required" id="input-store-slug" value="<?php echo html_escape($member->getSlug()); ?>"/>
                                            <span class="val-error-icon-url" id="fail-icon-store-slug" style="display:none;">
                                                <i class="fa fa-times"></i>
                                            </span>
                                            
                                        </td>
                                    </tr>
                                </table>
                                <span class="val-error" id="fail-message-store-slug" style="display:none">
                                </span>
                            </div>
                        </div>
                        <div class="col-md-5 col-inline-btn-url">
                            <button class="btn btn-setting-save-btn save-store-setting" data-variable="store-slug">
                                Save changes
                            </button>
                            <button class="btn btn-setting-cancel-btn" id="cancel-edit-store-url">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
                <p class="p-note-setting">
                    Your store link can only be changed once. Once a change has been made to the store link, you will also have to regenerate your store QR code. 
                </p>
            </div>
        </div>
        <div class="div-store-setup">
            <p class="panel-setup-title">STORE QUICK RESPONSE CODE</p>
            <div class="div-setup-content">
                <button class="btn btn-default-1 btn-deactivate printQrCode" data-url="/memberpage/generateQrCode">
                   GENERATE MY STORE QR CODE
                </button>
                <p class="p-note-setting">
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation
                </p>
            </div>
        </div>
        <div class="div-store-setup">
            <p class="panel-setup-title">My Theme</p>
            <div class="div-setup-content">
                <div class="current-store-theme">
                    <form class="form-horizontal">
                       <div class="form-group">
                            <?php $storeColor = $member->getStoreColor(); ?>
                            <label class="col-sm-2 control-label">Color : </label>
                            <div class="col-sm-5 col-xs-12 col-with-radio">
                                <div class="current-color-choice" style="background: #<?php echo html_escape($storeColor->getHexadecimal()) ?>;">
                                    <?php echo html_escape($storeColor->getName()); ?>
                                </div>
                                 
                            </div>
                           
                            <div class="col-sm-2 col-xs-12 col-with-radio">
                                <span class="btn btn-setting-edit-btn" id="btn-edit-store-theme">
                                   <i class="icon-edit"></i> Edit
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="edit-store-theme form-horizontal">
                   <div class="form-group">
                        <label class="col-sm-3 control-label">Pick Your Color : </label>
                        <div class="col-sm-5 col-xs-12 col-with-radio">
                            <div class="color-theming">
                                <div class="current-color-drop" style="background: #<?php echo html_escape($storeColor->getHexadecimal()); ?>;">
                                    <input type="hidden" value="<?php echo $storeColor->getIdStoreColor(); ?>" id="current-store-color-id"/>
                                    <span class="color-name-drop"><?php echo html_escape($storeColor->getName()); ?></span>
                                    <i class="cd icon-dropdown pull-right"></i>
                                </div>
                                
                                <div class="color-dropdown" >
                                    <ul class="color-list" id="store-color-dropdown">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-5">
                            <button id="store-color-save" class="btn btn-setting-save-btn btn-color-save">
                                Save changes
                            </button>
                            <span class="btn btn-setting-cancel-btn btn-color-cancel" id="cancel-edit-store-theme">
                                Cancel
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-5">
                            <span class="val-error" id="store-color-error" style=""></span>
                        </div>
                    </div>
                </div>
                    
                <div class="clear"></div>
                <p class="p-note-setting">
                    Customize the color theme of your store page. Choose from the different available colors and make your store page more personalized.
                </p>
            </div>
        </div>
        <div class="div-store-setup">
            <p class="panel-setup-title">CATEGORIES</p>
            <div class="div-store-content">
                <div class="current-store-cat">
                    <div class="form-horizontal">
                       <div class="form-group">
                            <label class="col-sm-3 control-label">Current Order : </label>
                            <div class="col-sm-9 col-with-radio">

                                <div class="store-category-view">
                                </div>
                                
                                <div>
                                    <br/>
                                    <button class="btn btn-setting-edit-btn" id="btn-edit-store-cat-new">
                                    <i class="fa fa-plus"></i> Add Category
                                    </button>
                                    <button class="btn btn-setting-edit-btn" id="btn-edit-store-cat">
                                    <i class="icon-edit"></i> Edit
                                    </button>
                                </div>
                                
                                
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="edit-store-cat form-horizontal">
                   <div class="form-group">
                        <label class="col-sm-3 control-label">Reorder List : </label>
                        <div class="col-sm-5 col-with-radio">
                            <ul class="category_sort list store-category-draggable">
                            </ul> 
                            <p class="note-category">Drag the category items to reorder</p>
                            <button class="btn btn-setting-save-btn">
                               Save Changes
                            </button>
                            <button class="btn btn-setting-cancel-btn" id="cancel-edit-store-cat">
                               Cancel
                            </button>
                        </div>
                    </div>
                </div>
                <div class="edit-store-cat-new">
                    <div class="row">
                        <div class="col-md-5 col-inline-textbtn">
                            <input type="text" class="text-info text-required" value="Professional Services"/>
                             <!--DISPLAY WHEN ERROR -->
                            <span class="val-error-icon-setup"><i class="fa fa-times"></i></span>
                            <span class="val-error">Please enter at least 6 characters.</span>

                            <!--DISPLAY WHEN OK
                            <span class="val-success-url"><i class="fa fa-check"></i></span>-->
                        </div>
                        <div class="col-md-5">
                            <button class="btn btn-setting-save-btn">
                                Submit
                            </button>
                            <button class="btn btn-setting-cancel-btn" id="cancel-store-cat-new">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
                <p class="p-note-setting">
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation
                </p>
            </div>
        </div>
    </div>
</div>