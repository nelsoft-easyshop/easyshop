<div class="col-xs-3 no-padding col-left-wing">
    <div class="left-wing">
        <div class="panel-contact-details">
            <p class="panel-title-contact">
                Details
           </p>
           <input type="hidden" id="isEditable" value="<?php echo html_escape($isEditable)?>"/>
           <input type="hidden" id="errorCount" value="<?php echo html_escape(count($errors))?>"/>
           <?php if($isEditable): ?>

           <i class="fa fa-edit fa-edit-icon pull-right" id="editIconOpen" rel="tooltip" data-toggle="tooltip" data-placement="left"  title="Edit vendor details"></i>
           
           <i class="fa fa-ban fa-cancel-edit pull-right" id="editIconClose"  rel="tooltip" data-toggle="tooltip" data-placement="left"  title="Cancel"></i>
          
           <?php endif; ?>

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
                                <?php echo ucwords(str_replace('_', ' ', $key)) . ': ' . $value[0] . "<br/> <br/>" ?>
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

                <?php echo form_open('/' . html_escape($member->getUsername()) . '/' . $targetPage); ?>
                <tr id="storeNameRow">
                    <td class="td-contact-icon"><i><img src="/assets/images/vendor-icons/profile.png" width="32px" height="32px" alt="Seller Name:"/></i</td>
                    <td class="td-contact-detail">
                        <p class="text-contact"><?php echo html_escape($storeName); ?></p>
                        <input type="hidden" id="displayStoreName" value="<?php echo html_escape($storeName)?>"/>
                        <input type="text" class="input-detail" placeholder="Seller Name..." name="storeName" id="storeName" value="<?php echo html_escape($storeName); ?>">
                    </td>
                </tr>
                <tr id="contactNoRow">
                    <td class="td-contact-icon"><i><img src="/assets/images/vendor-icons/phone.png" width="32px" height="32px" alt="Contact Number:"/></i></td>
                    <td class="td-contact-detail">
                        <p class="text-contact"><?php echo html_escape($contactNo); ?></p>
                        <input type="text" class="input-detail" maxlength="11" placeholder="Contact Number..." name="contactNumber" id="contactNo" value="<?php echo html_escape($contactNo); ?>">
                        <input type="hidden" id="displayContactNo" value="<?php echo html_escape($contactNo)?>"/>
                    </td>
                </tr>
                <!-- <tr>
                    <td class="td-contact-icon"><i><img src="/assets/images/vendor-icons/fax.png" width="32px" height="32px" /></i></td>
                    <td class="td-contact-detail">
                        <p class="text-contact">+61 3 8376 6284</p>
                        <input type="text" class="input-detail" placeholder="Fax Number..." value=" +61 3 8376 6284">
                    </td>
                </tr> -->
                <tr id="addressRow">
                    <td class="td-contact-icon"><i><img src="/assets/images/vendor-icons/location.png" width="32px" height="32px" alt="Address:"/></i></td>
                    <td class="td-contact-detail">
                        <input type="hidden" id="cityList" value="<?php echo html_escape(json_encode($cityList));?>"/>

                        <p class="text-contact"><?php echo html_escape($streetAddr . $city . ', ' . $region); ?></p>
                        <input type="hidden" id="displayStreetAddr" value="<?php echo html_escape($streetAddr)?>"/>
                        <input type="hidden" id="displayCity" value="<?php echo html_escape($city)?>"/>
                        <input type="hidden" id="displayRegion" value="<?php echo html_escape($region)?>"/>

                        <input type="text" class="input-detail" placeholder="Address Line..." name="streetAddress" id="streetAddr" value="<?php echo html_escape(substr($streetAddr, 0, -2)); ?>">
                        <select class="input-detail input-detail-select" name="regionSelect" id="regionSelect">
                            <?php foreach($regions as $key => $value): ?>
                                <?php echo "<option value='" . html_escape($value['location']) . "' " . ($value['location'] === $region? "selected>" : ">") . html_escape($value['location']) . "</option>"; ?> 
                            <?php endforeach; ?>
                        </select>
                        <select class="input-detail input-detail-select" name="citySelect" id="citySelect">
                            <?php foreach($cities as $key => $value): ?>
                                <?php echo "<option value='" . html_escape($value['location']) . "' " . ($value['location'] === $city? "selected>" : ">") . html_escape($value['location']) . "</option>"; ?> 
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <!-- <tr>
                    <td class="td-contact-icon"><i><img src="/assets/images/vendor-icons/mail.png" width="32px" height="32px" /></i></td>
                    <td class="td-contact-detail">
                        <p class="text-contact"><?php echo html_escape($member->getSupportEmail()); ?></p>
                        <input type="email" class="input-detail" placeholder="Email Address..." name="supportEmail" value="<?php echo html_escape($member->getSupportEmail()); ?>">
                    </td>
                </tr> -->
                <tr id="websiteRow">
                    <td class="td-contact-icon"><i><img src="/assets/images/vendor-icons/website.png" width="32px" height="32px" alt="Website:" /></i></td>
                    <td class="td-contact-detail">
                        <p class="text-contact"><a href="#"><?php echo html_escape($website); ?></a></p>
                        <input type="hidden" id="displayWebsite" value="<?php echo html_escape($website)?>"/>
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
            <br/>
            <!--
            <center>
                <span class="span-social-media">
                    <a href="#">
                        <img src="/assets/images/fb64.png" width="32" height="32" rel="tooltip" data-toggle="tooltip" title="Facebook" data-placement="bottom">
                    </a>
                    <script>
                        $('[rel=tooltip]').tooltip() 
                   </script>
                </span>
                <span class="span-social-media">
                    <a href="#">
                        <img src="/assets/images/twitter64.png" width="32" height="32"  rel="tooltip" data-toggle="tooltip" title="Twitter" data-placement="bottom">
                    </a>
                    <script>
                        $('[rel=tooltip]').tooltip() 
                   </script>
                </span>
                <span class="span-social-media">
                    <a href="#">
                        <img src="/assets/images/googleplus64.png" width="32" height="32"  rel="tooltip" data-toggle="tooltip" title="Google+" data-placement="bottom">
                    </a>
                    <script>
                        $('[rel=tooltip]').tooltip() 
                   </script>
                </span>
                <span class="span-social-media">
                    <a href="#">
                        <img src="/assets/images/linkedin64.png" width="32" height="32"  rel="tooltip" data-toggle="tooltip" title="LinkedIn" data-placement="bottom">
                    </a>
                    <script>
                        $('[rel=tooltip]').tooltip() 
                   </script>
                </span>
                <span class="span-social-media">
                    <a href="#">
                        <img src="/assets/images/skype64.png" width="32" height="32"  rel="tooltip" data-toggle="tooltip" title="Skype" data-placement="bottom">
                    </a>
                    <script>
                        $('[rel=tooltip]').tooltip() 
                   </script>
                </span>
                <span class="span-social-media">
                    <a href="#">
                        <img src="/assets/images/youtube64.png" width="32" height="32"  rel="tooltip" data-toggle="tooltip" title="YouTube" data-placement="bottom">
                    </a>
                    <script>
                        $('[rel=tooltip]').tooltip() 
                   </script>
                </span>
            </center>
            -->
        </div>
    </div>
</div>

<script type="text/javascript" src="/assets/js/src/usercontact.js?ver="<?=ES_FILE_VERSION?>></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.numeric.js"></script>

