<link type="text/css" href='/assets/css/contact.css' rel="stylesheet" media='screen'/>
<div class="clear"></div>
<section class="bg-product-section color-default">
    <div class="container-non-responsive bg-product-section">
        <div class="row row-contact">
            
            <?php echo $userDetails; ?>

            <div class="col-xs-9 col-feedback-container">
                
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

                        <p class="p-about">
                            <?php echo html_escape($member->getStoreDesc()); ?>
                        </p>
                       
                        <div class="div-about-edit-area">
                            <?php echo form_open('home/doUpdateDescription') ?>
                                <textarea class="input-lg input-message textarea-about" name='description' rows="12" placeholder='Say something about your shop...'"><?php echo html_escape($member->getStoreDesc()); ?></textarea>
                                <center>
                                    <input type="submit"  id="save-about" class="btn btn-send" value="SAVE CHANGES" />
                                </center>
                                <input type='hidden' name='userId' value="<?php echo $member->getIdMember(); ?>" />
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            
            
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

