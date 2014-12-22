<link type="text/css" href='/assets/css/contact.css?ver=<?php echo ES_FILE_VERSION ?>' rel="stylesheet" media='screen'/>
<div class="clear"></div>
<section class="bg-product-section color-default">
    <div class="container bg-product-section">
        <div class="row row-contact">
            
            <?php echo $userDetails; ?>

            <div class="col-md-9 col-feedback-container">
                
               
               
                <?php if($isEditable || strlen(trim($member->getStoreDesc())) > 0 ): ?>
                
                    <div class="panel-about-seller">
                        <?php if($isEditable): ?>
                            <i class="fa fa-edit fa-edit-about pull-right" rel="tooltip" data-toggle="tooltip" data-placement="left"  title="Edit vendor about content"></i>
                            <i class="fa fa-ban fa-cancel-about pull-right"  rel="tooltip" data-toggle="tooltip" data-placement="left"  title="Cancel"></i>
                        <?php endif; ?>
                        <p class="panel-title-feedback">
                            About <?php echo html_escape( strlen($member->getStoreName()) > 0 ? $member->getStoreName() : $member->getUsername() ); ?>
                        </p>
                        <div class="clear"></div>
                        <div class="div-about-content">

                            <p>
                                <pre class="p-about"><?php echo html_escape($member->getStoreDesc()); ?></pre>
                            </p>
                            <div class="div-about-edit-area">
                                <?php echo form_open('store/doUpdateDescription') ?>
                                    <textarea class="input-lg input-message textarea-about" name='description' maxlength="1024" rows="12" id="description" placeholder="'Say something about your shop...'" data-value="<?php echo html_escape($member->getStoreDesc()); ?>"><?php echo html_escape($member->getStoreDesc()); ?></textarea>
                                    <center>
                                        <input type="submit"  id="save-about" class="btn btn-send" value="SAVE CHANGES" />
                                    </center>
                                    <input type='hidden' name='userId' value="<?php echo $member->getIdMember(); ?>" />
                                <?php echo form_close(); ?>
                            </div>
                            <input type='hidden' id='open-description' value='<?php echo $isEditable && (!$member->getStoreDesc() || strlen($member->getStoreDesc() === 0))  ? 'true' : 'false' ?>'/>
                        </div>
                        
                    </div>
                
                <?php endif; ?>
                <div class="panel-feedback-ratings">
                    <p class="panel-title-feedback">
                        Feedback Ratings
                    </p>
                    <div class="row row-ratings">
                        <div class="col-xs-12">
                            <?php $ratingCounter = 1; ?>
                            <?php foreach($ratingHeaders as $ratingHeader): ?>
                                <div class=" col-sm-4 col-xs-12 col-rate-mobile">
                                    <p class="span-rate-c"  style="display: inline;"><?php echo html_escape($ratingHeader)?>: </p>
                                    <span class="span-rate" style="display: inline;">
                                        <?php for($i = 0; $i < round($feedbackSummary['rating'.$ratingCounter]); $i++): ?>
                                            <i class="fa fa-star star-feed star-active"></i>
                                        <?php endfor; ?>
                                        <?php for($i = 0; $i < 5 - round($feedbackSummary['rating'.$ratingCounter]); $i++): ?>
                                            <i class="fa fa-star star-feed"></i>
                                        <?php endfor; ?>
                                    </span>
                                 </div>
                            <?php $ratingCounter++; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <hr/>

                    <table width="100%" class="table-tab">
                        <tr>
                            <td class="td-title-1">
                                <p class="tab-title-1"><a id="ab" class="a-tab tab-active-link" href="#as-buyer" role="tab" data-toggle="tab">Feedback from Seller</a></p>
                            </td>
                            <td class="td-title-2">
                                <p class="tab-title-2"><a id="as" class="a-tab" href="#as-seller" role="tab" data-toggle="tab">Feedback from Buyer</a></p>
                            </td>
                            <td class="td-title-3">
                                <p class="tab-title-3"><a id="fob" class="a-tab" href="#for-other-buyer" role="tab" data-toggle="tab">Feedback for Seller</a></p>
                            </td>
                            <td class="td-title-4">
                                <p class="tab-title-4"><a id="fos" class="a-tab" href="#for-other-seller" role="tab" data-toggle="tab">Feedback for Buyer</a></p>
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
                    <div class="feedback-mobile">
                        <div class="feedback-cat-mobile feedback-from-seller">
                            Feedback from Seller
                        </div>
                        <div class="feedback-mobile-cont feedback-mobile-1">
                            <div class="feedback-mobile-item">
                                <table class="table-feed-mobile">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="div-user-image">
                                                    <a href="/justineduazo">
                                                        <img src="/assets/images/img_profile_pic.jpg" class="img-user-image">
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="td-info-mobile">
                                                <p class="p-user-name">
                                                    <a href="/justineduazo">
                                                        justineduazo
                                                    </a>
                                                </p>
                                                <p class="p-date-feedback">
                                                    18 th December , 2014 
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="feedback-item-mobile-content">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="td-feedback-criteria">Item quality</td>
                                                        <td class="td-feedback-star">
                                                            <span>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-4">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="td-feedback-criteria">Communication</td>
                                                        <td class="td-feedback-star">
                                                            <span>
                                                                    <i class="fa fa-star star-feed star-active"></i>
                                                                    <i class="fa fa-star star-feed star-active"></i>
                                                                    <i class="fa fa-star star-feed"></i>
                                                                    <i class="fa fa-star star-feed"></i>
                                                                    <i class="fa fa-star star-feed"></i>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-4">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="td-feedback-criteria">Item shipment</td>
                                                        <td class="td-feedback-star">
                                                            <span>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed"></i>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-item-message">
                                            "Lorem ipsum dolor sit amet, "
                                         </div>
                                    </div>
                                </div>
                            </div>
                            <div class="feedback-mobile-item">
                                <table class="table-feed-mobile">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="div-user-image">
                                                    <a href="/justineduazo">
                                                        <img src="/assets/images/img_profile_pic.jpg" class="img-user-image">
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="td-info-mobile">
                                                <p class="p-user-name">
                                                    <a href="/justineduazo">
                                                        justineduazo
                                                    </a>
                                                </p>
                                                <p class="p-date-feedback">
                                                    18 th December , 2014 
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="feedback-item-mobile-content">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="td-feedback-criteria">Item quality</td>
                                                        <td class="td-feedback-star">
                                                            <span>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-4">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="td-feedback-criteria">Communication</td>
                                                        <td class="td-feedback-star">
                                                            <span>
                                                                    <i class="fa fa-star star-feed star-active"></i>
                                                                    <i class="fa fa-star star-feed star-active"></i>
                                                                    <i class="fa fa-star star-feed"></i>
                                                                    <i class="fa fa-star star-feed"></i>
                                                                    <i class="fa fa-star star-feed"></i>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-4">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="td-feedback-criteria">Item shipment</td>
                                                        <td class="td-feedback-star">
                                                            <span>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed star-active"></i>
                                                                <i class="fa fa-star star-feed"></i>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-item-message">
                                            "Lorem ipsum dolor sit amet, "
                                         </div>
                                    </div>
                                </div>
                            </div>
                            <div class="jumbotron no-feedback-list">
                                <center>
                                    <span class="fa fa-clipboard fa-2x"></span>
                                    <strong>NO FEEDBACK FOR THIS CATEGORY</strong>
                                </center>
                            </div>
                            <br/>
                        </div>
                        <div class="feedback-cat-mobile feedback-from-buyer">
                            Feedback from Buyer
                        </div>
                        <div class="feedback-mobile-cont feedback-mobile-2">
                            <div class="jumbotron no-feedback-list">
                                <center>
                                    <span class="fa fa-clipboard fa-2x"></span>
                                    <strong>NO FEEDBACK FOR THIS CATEGORY</strong>
                                </center>
                            </div>
                        </div>
                        <div class="feedback-cat-mobile feedback-for-seller">
                            Feedback for Seller
                        </div>
                        <div class="feedback-mobile-cont feedback-mobile-3">
                            <div class="jumbotron no-feedback-list">
                                <center>
                                    <span class="fa fa-clipboard fa-2x"></span>
                                    <strong>NO FEEDBACK FOR THIS CATEGORY</strong>
                                </center>
                            </div>
                        </div>
                        <div class="feedback-cat-mobile feedback-for-buyer">
                            Feedback from Seller
                        </div>
                        <div class="feedback-mobile-cont feedback-mobile-4">
                            <div class="jumbotron no-feedback-list">
                                <center>
                                    <span class="fa fa-clipboard fa-2x"></span>
                                    <strong>NO FEEDBACK FOR THIS CATEGORY</strong>
                                </center>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <?php if(count($orderRelations) > 0): ?>
                    <div class="panel-feedback-message">
                        <p class="panel-title-feedback">
                            Leave A Feedback
                        </p>
                        <div class="div-message-form">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php $count = 1; ?>
                                    <?php foreach($ratingHeaders as $ratingHeader): ?>
                                        <div class="col-sm-4 col-xs-12 rating-header  col-rate-mobile" style="margin-bottom: 20px;"  id='rating-header<?php echo $count?>'>
                                            <?php echo html_escape($ratingHeader); ?>
                                            <span class="span-rate feedback-ratings">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fa fa-star fa-star-rate" data-number="<?php echo $i ?>"></i>
                                                <?php endfor; ?>
                                            </span>
                                        </div>
                                    <?php $count++; ?>
                                    <?php endforeach;?>
                                </div>
                            </div>
                                                            
                            <?php echo form_open('/store/doCreateFeedback', ['id' => 'feedback-form']); ?>
                            <div class="row">
                                <div class="col-xs-12 col-message-2">
                                    <input name="username" type="text" class="input-lg input-message" placeholder="<?php echo html_escape($viewer->getUsername()); ?>" readonly/>
                                </div>
                            </div>                            
                            <div class="row">
                                <div class="col-xs-12 col-message-2">
                                    <select id="feedback-select" class="input-lg input-message" name="feeback-order">
                                        <option value="0">Select the transaction you want to review</option>
                                        <?php foreach($orderRelations as $order): ?>
                                              <option value="<?php echo $order['idOrder'] ?>">
                                                    <?php echo html_escape($order['productname']); ?>
                                                    <?php echo '(Invoice: '.$order['invoiceNo'].')';  ?>
                                                    on <?php echo $order['dateadded']->format('F j, Y');  ?>
                                              </option>
                                        <?php endforeach; ?>
                                    </select>    
                                    <span id="feedback-order-error-icon" class="glyphicon glyphicon-remove form-control-feedback error-color" style="top: 0px !important; right: 17px;"></span>
                                    <br/>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-message-2">
                                    <textarea class="input-lg input-message" rows="7" placeholder="WRITE YOUR MESSAGE..." name="feedback-message" id="feedback-message"></textarea>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-xs-12 col-message-2">
                                    <div class="alert alert-danger hide" role="alert" id="feedback-star-error">
                                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                        Please rate this user in all available criteria.
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <center>
                                        <input type="submit" class="btn btn-send" value="SEND FEEDBACK">
                                    </center>
                                </div>
                            </div>
                            <input type='hidden' name='userId' value='<?php echo $member->getIdMember() ?>' />
                            <input type='hidden' name='rating1' value='0' id='input-rating-header1'/>
                            <input type='hidden' name='rating2' value='0' id='input-rating-header2'/>
                            <input type='hidden' name='rating3' value='0' id='input-rating-header3'/>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
               
  
                <?php endif; ?>
               
            </div>
            </div>
        </div>
    </div>
    
    <input type = 'hidden' id='memberid' value='<?php echo html_escape($member->getIdMember()); ?>'/> 
    

    
</section>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.easing.min.js"></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.scrollUp.min.js"></script>
<script type="text/javascript" src="/assets/js/src/userabout.js?ver=<?php echo ES_FILE_VERSION?>"></script>

