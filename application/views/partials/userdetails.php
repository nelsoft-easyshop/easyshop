<div class="col-md-3 no-padding col-left-wing">
    <div class="left-wing-contact" id="user-detail-partial">
        <div class="panel-contact-details">
            <p class="panel-title-contact">
                Details <i class="fa fa-caret-down drop-user-details"></i>
           </p>
           <input type="hidden" id="isEditable" value="<?php echo html_escape($isEditable)?>"/>
           <input type="hidden" id="errorCount" value="<?php echo html_escape(count($errors))?>"/>
           <?php if($isEditable): ?>

           <i class="fa fa-edit fa-edit-icon pull-right" id="editIconOpen" rel="tooltip" data-toggle="tooltip" data-placement="left"  title="Edit vendor details"></i>
           
           <i class="fa fa-ban fa-cancel-edit pull-right" id="editIconClose"  rel="tooltip" data-toggle="tooltip" data-placement="left"  title="Cancel"></i>
          
           <?php endif; ?>
            <div class="user-details-container">
            <table width="100%" class="table-contact-details">
                <?php if(count($errors) > 0 || $isValid): ?>
                <tr>
                    <td colspan="2"> 
                        <?php if(count($errors) > 0): ?>
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                &times;
                            </button>
                            <?php foreach($errors as $key => $value): ?>
                                <?php echo "<p><strong>".html_escape(ucwords(str_replace('_', ' ', $key))) . '</strong>: ' . html_escape($value[0]) . "</p>" ?>
                            <?php endforeach; ?>
                        </div>

                        <?php else: ?>
                        <div class="alert alert-success alert-dismissable">
                           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                &times;
                           </button>
                           <strong>Success!</strong> Details updated.
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>

                <?php echo form_open('/' . html_escape($member->getSlug()) . '/' . $targetPage); ?>
                <tr id="storeNameRow">
                    <td class="td-contact-icon"><i><img src="<?php echo getAssetsDomain() ?>assets/images/vendor-icons/profile.png" width="32px" height="32px" alt="Store Name:"/></i</td>
                    <td class="td-contact-detail">
                        <p id="validatedStoreName" class="text-contact"><?php echo html_escape($validatedStoreName); ?></p>
                        <input type="hidden" id="postStoreName" value="<?php echo html_escape($storeName)?>"/>
                        <input type="text" class="input-detail" placeholder="Store Name..." name="storeName" id="storeName" value="<?php echo html_escape($storeName); ?>">
                    </td>
                </tr>
                <tr id="contactNoRow">
                    <td class="td-contact-icon"><i><img src="<?php echo getAssetsDomain() ?>assets/images/vendor-icons/phone.png" width="32px" height="32px" alt="Contact Number:"/></i></td>
                    <td class="td-contact-detail">
                        <p id="validatedContactNo" class="text-contact"><?php echo html_escape($validatedContactNo); ?></p>
                        <input type="text" class="input-detail" maxlength="11" placeholder="Contact Number..." name="contactNumber" id="contactNo" value="<?php echo html_escape($contactNo); ?>">
                        <input type="hidden" id="postContactNo" value="<?php echo html_escape($contactNo)?>"/>
                    </td>
                </tr>
                
                <tr id="addressRow">
                    <td class="td-contact-icon"><i><img src="<?php echo getAssetsDomain() ?>assets/images/vendor-icons/location.png" width="32px" height="32px" alt="Address:"/></i></td>
                    <td class="td-contact-detail">
                        <input type="hidden" id="cityList" value="<?php echo html_escape(json_encode($cityList));?>"/>

                        <p class="text-contact" id="full-address-display"><?php echo html_escape($validatedStreetAddr . $validatedCity . $validatedRegion); ?></p>
                        <input type="hidden" id="validatedStreetAddr" value="<?php echo html_escape($validatedStreetAddr)?>"/>
                        <input type="hidden" id="validatedCity" value="<?php echo html_escape($validatedCity)?>"/>
                        <input type="hidden" id="validatedRegion" value="<?php echo html_escape($validatedRegion)?>"/>

                        <input type="hidden" id="postStreetAddr" value="<?php echo html_escape($streetAddr)?>"/>
                        <input type="hidden" id="postCity" value="<?php echo html_escape($city)?>"/>
                        <input type="hidden" id="postRegion" value="<?php echo html_escape($region)?>"/>

                        <input type="text" class="input-detail" placeholder="Address Line..." name="streetAddress" id="streetAddr" value="<?php echo html_escape(substr($streetAddr, 0, -2)); ?>">
                        <select class="input-detail input-detail-select" name="regionSelect" id="regionSelect">
                            <option value='' selected>Select State/Region</option>
                            <?php foreach($regions as $key => $value): ?>
                                <?php echo "<option value='" . html_escape($key) . "' " . ($value === $region ? "selected>" : ">") . html_escape($value) . "</option>"; ?> 
                            <?php endforeach; ?>
                        </select>
                        <select class="input-detail input-detail-select" name="citySelect" id="citySelect">
                        </select>
                    </td>
                </tr>

                <tr id="websiteRow">
                    <td class="td-contact-icon"><i><img src="<?php echo getAssetsDomain() ?>assets/images/vendor-icons/website.png" width="32px" height="32px" alt="Website:" /></i></td>
                    <td class="td-contact-detail">
                        <p class="text-contact">
                            <div class="external-links-container">
                                <a href=" <?php echo preg_match("~^(?:f|ht)tps?://~i", $validatedWebsite) ? html_escape($validatedWebsite) : 'http://' . html_escape($validatedWebsite)?>/" id="validatedWebsite" rel="nofollow">
                                    <?php echo html_escape($validatedWebsite); ?>
                                </a>
                            </div>
                        </p>
                        <input type="hidden" id="postWebsite" value="<?php echo html_escape($website)?>"/>
                        <input type="text" class="input-detail" placeholder="Website..." name="website" id="website" value="<?php echo html_escape($website); ?>">
                    </td>
                </tr>
                <tr >
                    <td colspan="2">
                        <center>
                            <input type="submit"  id="save-edit" class="btn btn-default-3" value="Save Changes" />
                        </center>
                    </td>
                </tr>
                <?php echo form_close(); ?>
            </table>
            </div>
            <br/>

        </div>
    </div>
</div>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type="text/javascript" src="/assets/js/src/usercontact.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type="text/javascript" src="/assets/js/src/vendor/jquery.numeric.js"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.partial_userdetails.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

