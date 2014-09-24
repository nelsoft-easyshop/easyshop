<link type="text/css" href='/assets/css/contact.css' rel="stylesheet" media='screen'/>
<div class="clear"></div>
<section class="bg-product-section color-default">
    <div class="container-non-responsive bg-product-section">
        <div class="row row-contact">
            <div class="col-xs-3 no-padding col-left-wing">
                <div class="left-wing">
                    <div class="panel-contact-details">
                        <p class="panel-title-contact">
                            Details
                            
                        </p>
                       <i class="fa fa-edit fa-edit-icon pull-right" rel="tooltip" data-toggle="tooltip" data-placement="left"  title="Edit vendor details"></i>
                       <script>
                            $('[rel=tooltip]').tooltip() 
                       </script>
                       <i class="fa fa-ban fa-cancel-edit pull-right"  rel="tooltip" data-toggle="tooltip" data-placement="left"  title="Cancel"></i>
                       <script>
                            $('[rel=tooltip]').tooltip() 
                       </script>
                        <table width="100%" class="table-contact-details">

                            <?php echo form_open('/' . html_escape($member->getUsername()) . '/about'); ?>
                            <tr>
                                <td class="td-contact-icon"><i class="fa fa-user fa-2x"></i></td>
                                <td class="td-contact-detail">
                                    <text class="text-contact"><?php echo html_escape($member->getStoreName()); ?></text>
                                    <input type="text" class="input-detail" placeholder="Seller Name..." name="storeName" value="<?php echo html_escape($member->getStoreName()); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="td-contact-icon"><i class="fa fa-phone fa-2x"></i></td>
                                <td class="td-contact-detail">
                                    <text class="text-contact"><?php echo html_escape($member->getContactno()); ?></text>
                                    <input type="text" class="input-detail" placeholder="Contact Number..." name="contactNumber" value="<?php echo html_escape($member->getContactno()); ?>">
                                </td>
                            </tr>
                            <!-- <tr>
                                <td class="td-contact-icon"><i class="fa fa-print fa-2x"></i></td>
                                <td class="td-contact-detail">
                                    <text class="text-contact">+61 3 8376 6284</text>
                                    <input type="text" class="input-detail" placeholder="Fax Number..." value=" +61 3 8376 6284">
                                </td>
                            </tr> -->
                            <tr>
                                <td class="td-contact-icon"><i class="fa fa-map-marker fa-2x"></i></td>
                                <td class="td-contact-detail">
                                    <text class="text-contact"><?php echo html_escape($addr->getAddress()); ?></text>
                                    <input type="text" class="input-detail" placeholder="Address Line..." name="streetAddress" value="<?php echo html_escape($addr->getAddress()); ?>">
                                    <select class="input-detail input-detail-select" name="citySelect" id="citySelect">
                                        <?php foreach($cities as $key => $value): ?>
                                            <?php echo "<option value='" . html_escape($value['location']) . "' " . ($value['location'] === $addr->getCity()->getLocation()? "selected>" : ">") . html_escape($value['location']) . "</option>"; ?> 
                                        <?php endforeach; ?>
                                    </select>
                                    <select class="input-detail input-detail-select" name="regionSelect" id="regionSelect">
                                        <?php foreach($regions as $key => $value): ?>
                                            <?php echo "<option value='" . html_escape($value['location']) . "' " . ($value['location'] === $addr->getStateregion()->getLocation()? "selected>" : ">") . html_escape($value['location']) . "</option>"; ?> 
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-contact-icon"><i class="fa fa-envelope fa-2x"></i></td>
                                <td class="td-contact-detail">
                                    <text class="text-contact"><?php echo html_escape($member->getSupportEmail()); ?></text>
                                    <input type="email" class="input-detail" placeholder="Email Address..." name="supportEmail" value="<?php echo html_escape($member->getSupportEmail()); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="td-contact-icon"><i class="fa fa-globe fa-2x"></i></td>
                                <td class="td-contact-detail">
                                    <text class="text-contact"><a href="#"><?php echo html_escape($member->getWebsite()); ?></a></text>
                                    <input type="text" class="input-detail" placeholder="Website..." name="website" value="<?php echo html_escape($member->getWebsite()); ?>">
                                </td>
                            </tr>
                            <tr >
                                <td colspan="2">
                                    <center>
                                        <input type="submit"  id="save-edit" class="btn btn-default-3" value="Save Changes" />
                                    </center>
                                </td>
                            </tr>
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
            <div class="col-xs-9 col-feedback-container">
                <div class="panel-feedback-ratings">
                    <p class="panel-title-feedback">
                        Feedback Ratings
                    </p>
                    <div class="row">
                        <div class="col-xs-12">
                            <table width="100%">
                                <tr>
                                
                                    <?php $ratingCounter = 1; ?>
                                    <?php foreach($ratingHeaders as $ratingHeader): ?>
                                        <td width="33%">
                                            <?php echo html_escape($ratingHeader)?>: 
                                            <span class="span-rate">
                                                
                                                <?php for($i = 0; $i < round($feedbackSummary['rating'.$ratingCounter]); $i++): ?>
                                                    <i class="fa fa-star star-feed star-active"></i>
                                                <?php endfor; ?>
                                                
                                                <?php for($i = 0; $i < 5 - round($feedbackSummary['rating'.$ratingCounter]); $i++): ?>
                                                    <i class="fa fa-star star-feed"></i>
                                                <?php endfor; ?>
                                                
                                            </span>
                                        </td>
                                    <?php $ratingCounter++; ?>
                                    <?php endforeach; ?>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr/>

                    <table width="100%" class="table-tab">
                        <tr>
                            <td class="td-title-1">
                                <p class="tab-title-1"><a id="ab" class="a-tab tab-active-link" href="#as-buyer" role="tab" data-toggle="tab">Feedback as a Buyer</a></p>
                            </td>
                            <td class="td-title-2">
                                <p class="tab-title-2"><a id="as" class="a-tab" href="#as-seller" role="tab" data-toggle="tab">Feedback as a Seller</a></p>
                            </td>
                            <td class="td-title-3">
                                <p class="tab-title-3"><a id="fob" class="a-tab" href="#for-other-buyer" role="tab" data-toggle="tab">Feedback for other - Buyer</a></p>
                            </td>
                            <td class="td-title-4">
                                <p class="tab-title-4"><a id="fos" class="a-tab" href="#for-other-seller" role="tab" data-toggle="tab">Feedback for other - Seller</a></p>
                            </td>
                        </tr>
                    </table>
                    <div class="div-feedback-list">
                        <div class="tab-content">
                            <?php foreach($feedbackTabs as $feedbackTab): ?>
                                <?php echo $feedbackTab; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                
                <div class="panel-feedback-message">
                    <p class="panel-title-feedback">
                        Leave A Feedback
                    </p>
                    <div class="div-message-form">
                        <div class="row">
                            <div class="col-xs-12">
                                <table width="100%" class="table-rate">
                                    <tr>
                                        <td width="33%">
                                             Item Quality: 
                                            <span class="span-rate">
                                                <i class="fa fa-star fa-star-rate i1"></i>
                                                <i class="fa fa-star fa-star-rate i2"></i>
                                                <i class="fa fa-star fa-star-rate i3"></i>
                                                <i class="fa fa-star fa-star-rate i4"></i>
                                                <i class="fa fa-star fa-star-rate i5"></i>
                                            </span>
                                        </td>
                                        <td width="33%">
                                            Communication: 
                                            <span class="span-rate">
                                                <i class="fa fa-star fa-star-rate c-1"></i>
                                                <i class="fa fa-star fa-star-rate c-2"></i>
                                                <i class="fa fa-star fa-star-rate c-3"></i>
                                                <i class="fa fa-star fa-star-rate c-4"></i>
                                                <i class="fa fa-star fa-star-rate c-5"></i>
                                            </span>
                                        </td>
                                        <td width="34%" align="right">
                                           Shipment Time: 
                                            <span class="span-rate">
                                                <i class="fa fa-star fa-star-rate s-1"></i>
                                                <i class="fa fa-star fa-star-rate s-2"></i>
                                                <i class="fa fa-star fa-star-rate s-3"></i>
                                                <i class="fa fa-star fa-star-rate s-4"></i>
                                                <i class="fa fa-star fa-star-rate s-5"></i>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-message-1">
                                <input type="text" class="input-lg input-message" placeholder="NAME..."/>
                            </div>
                            <div class="col-xs-6 col-message-2">
                                <input type="text" class="input-lg input-message" placeholder="PHONE NUMBER..."/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-message-1">
                                <input type="text" class="input-lg input-message" placeholder="EMAIL ADDRESS..."/>
                            </div>
                            <div class="col-xs-6 col-message-2">
                                <input type="text" class="input-lg input-message" placeholder="WEBSITE..."/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <textarea class="input-lg input-message" rows="7" placeholder="MESSAGE..."></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <center>
                                <input type="submit" class="btn btn-send" value="SEND FEEDBACK">
                            </center>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    
    <input type = 'hidden' id='memberid' value='<?php echo html_escape($member->getIdMember()); ?>'/> 
</section>

<script type="text/javascript" src="/assets/js/src/vendor/jquery.easing.min.js"></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.scrollUp.min.js"></script>
<script type="text/javascript" src="/assets/js/src/userabout.js?ver="<?=ES_FILE_VERSION?>></script>

