<div class="container">
    <div class="prod-detail-main">
        <div class="div-prod-lower">
            <div class="div-detail-nav">
                <ul class="ul-detail-nav">
                    <li class="active"><a href="#details" role="tab" data-toggle="tab">Product Detail</a></li>
                    <li><a href="#reviews" role="tab" data-toggle="tab">Reviews (<span class="span-review-count"><?=count($productReview);?></span>)</a></li>
                </ul>
            </div>
            <div class="div-detail-nav-mobile">
                <table width="100%" class="table-nav-prod">
                    <tbody>
                        <tr>
                            <td class="td-detail active" width="50%" id="tdDetails">
                                <a href="#details" role="tab" data-toggle="tab" id="prodDetails">
                                    <p class="p-detail-a">Product Detail</p>
                                </a>
                            </td>
                            <td class="td-review" width="50%" id="tdReviews">
                                <a href="#reviews" role="tab" data-toggle="tab" id="prodReviews">
                                     <p class="p-detail-a">Reviews (<span class="span-review-count"><?=count($productReview);?></span>)</p>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="details">
                    <div class="div-detail-container ">
                        <p class="p-detail-title">Product Detail</p>
                        <div class="p-html-description">
                            <div class="external-links-container">
                                <?php echo nl2br($productDetails); ?>
                                <?php if($product->getCondition() !== ""): ?>
                                    Condition: <?=html_escape($product->getCondition()); ?>
                                    <br>
                                <?php endif; ?>
                                <?php if($product->getBrief() !== ""): ?>
                                    Brief Description: <?=html_escape($product->getBrief()); ?>
                                    <br>
                                <?php endif; ?>
                                <?php if($product->getBrand()->getName() !== "" && $product->getBrand()->getIdBrand() !== \EasyShop\Entities\EsBrand::CUSTOM_CATEGORY_ID): ?>
                                    Brand: <?=html_escape($product->getBrand()->getName()); ?>
                                    <br>
                                <?php elseif ($product->getBrandOtherName() !== ""):?>
                                    Brand: <?=html_escape($product->getBrandOtherName()); ?>
                                    <br>
                                <?php endif; ?>
                                <?php if($product->getSku() !== ""): ?>
                                    SKU: <?=html_escape($product->getSku()); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="reviews">
                    <div class="div-detail-container ">
                        <p class="p-detail-title">Product Review</p>
                        <div class="clear"></div>
                        <div class="div-review-content">
                            <div class="row">
                                <div class="col-md-10">
                                    <div id="main-review-container" class="div-review-item-container">
                                        <?php if(count($productReview) <= 0): ?> 
                                            <div id="no-review-div" class="row">
                                                <div class="col-xs-12">
                                                    <p class="p-no-review-note">This product has 0 reviews so far. Be the first to review it.</p>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <?php foreach ($productReview as $key => $value): ?> 
                                            <div class="row">
                                                <div class="col-sm-2 col-xs-12 col-user-image" align="center">
                                                    <a href="/<?=$value['reviewer_slug'];?>">
                                                        <div class="div-user-image">
                                                            <img src="<?php echo getAssetsDomain().'.'.$value['reviewer_avatar']; ?>" class="img-user"/>
                                                        </div>
                                                    </a>
                                                    <div class="clear"></div>
                                                    <a href="/<?=$value['reviewer_slug'];?>"><p class="p-username"><?=html_escape($value['reviewer']);?></p></a>
                                                    <p class="p-date-review"><?=$value['datesubmitted']; ?></p>
                                                </div>
                                                <div class="col-sm-10 col-xs-12">
                                                    <div class="div-review-content-container">
                                                        <div class="row div-row-user">
                                                            <div class="me">
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            <a href="/<?=$value['reviewer_slug'];?>">
                                                                                <div class="div-user-image">
                                                                                    <img src="<?php echo getAssetsDomain().'.'.$value['reviewer_avatar']; ?>" class="img-user"/>
                                                                                </div>
                                                                            </a>
                                                                        </td>
                                                                        <td class="td-user-info">
                                                                            <a href="/<?=$value['reviewer_slug'];?>"><p class="p-username"><?=html_escape($value['reviewer']);?></p></a>
                                                                            <p class="p-date-review"><?=$value['datesubmitted']; ?></p> 
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <p class="p-review-title"><?=html_escape($value['title']);?>
                                                            <span class="span-review-item-rate">
                                                                <?php for ($i=0; $i < 5; $i++): ?>
                                                                    <span class="fa fa-star fa-review-rate <?=($value['rating'] > 0) ? 'fa-star-active' : '' ?> "></span>
                                                                    <?php $value['rating']--; ?>
                                                                <?php endfor; ?> 
                                                            </span>
                                                        </p>
                                                        <div class="clear"></div>
                                                        <p class="p-review-content">
                                                            <?=nl2br(html_escape($value['review']));?>
                                                        </p>
                                                        <?php $canReplyToReview = in_array($viewerId, $value['participants']); ?>
                                                        <?php if($canReplyToReview): ?>
                                                        <a href="javascript:void(0)" class="p-reply-text" >
                                                            <p class="pull-right">
                                                                <span class="text-cancel">Cancel </span>Reply
                                                            </p> 
                                                        </a> 
                                                        <?php endif; ?>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <?php if($canReplyToReview): ?>
                                                    <div class="div-reply-container">
                                                        <p class="p-reply-title">Write Reply</p> 
                                                        <div class="clear"></div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <form class="form-horizontal" role="form">
                                                                    <div class="form-group">
                                                                        <label for="subject" class="col-sm-2 control-label label-comment" style="text-align: left !important; margin-left: 10px;">Comment: </label>
                                                                        <div class="col-sm-10 col-text-area" style="margin-left: -10px;">
                                                                            <textarea id="textareaReview<?=$value['id_review']; ?>" class="input-textarea " rows="7"></textarea>
                                                                            <span class="error-label error-label-textarea error-<?=$value['id_review']; ?>" >Please provide a review message</span>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12" align="center">
                                                                <button class="btn-reply btn-js-reply" data-parent="<?=$value['id_review']; ?>">
                                                                    Submit
                                                                </button>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="clear"></div>
                                                    <div class="div-review-item-container review-container-<?=$value['id_review']; ?>">
                                                        <?php foreach ($value['replies'] as $keyReply => $valueReply): ?>
                                                        <div class="row">
                                                            <div class="col-xs-2 col-user-image no-padding" align="center">
                                                                <a href="/<?=$valueReply['reviewer_slug'];?>">
                                                                    <div class="div-user-image">
                                                                        <img src="<?php echo getAssetsDomain().'.'.$valueReply['reviewer_avatar']; ?>" class="img-user"/>
                                                                    </div>
                                                                </a>
                                                                <div class="clear"></div>
                                                                <a href="/<?=$valueReply['reviewer_slug'];?>"><p class="p-username"><?=html_escape($valueReply['reviewer']);?></p></a>
                                                                <p class="p-date-review-replied-item"><?=$valueReply['datesubmitted'];?></p>
                                                            </div>
                                                            <div class="col-sm-10 col-xs-12">
                                                                <div class="div-review-content-container div-reply-content-container">
                                                                    <div class="row div-row-user">
                                                                        <div class="me">
                                                                            <table>
                                                                                <tr>
                                                                                    <td>
                                                                                        <a href="/<?=$valueReply['reviewer_slug'];?>">
                                                                                            <div class="div-user-image">
                                                                                                <img src="<?php echo getAssetsDomain().'.'.$valueReply['reviewer_avatar']; ?>" class="img-user"/>
                                                                                            </div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="td-user-info">
                                                                                        <a href="/<?=$valueReply['reviewer_slug'];?>"><p class="p-username"><?=html_escape($valueReply['reviewer']);?></p></a>
                                                                                        <p class="p-date-review"><?=$valueReply['datesubmitted'];?></p>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </div> 
                                                                    <div class="clear"></div>
                                                                    <p class="p-review-content">
                                                                        <?=nl2br(html_escape($valueReply['review']));?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <?php endforeach; ?>
                                        <?php endif; ?> 
                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                            <?php if($canReview): ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="p-reply-title">Write a Review</p>
                                    <div class="div-write-review-container">
                                            <div class="col-md-6">
                                                <form class="form-horizontal" role="form">
                                                    <div class="form-group">
                                                        <label for="subject" class="col-xs-2 control-label label-subject">Subject: </label>
                                                        <div class="col-xs-10">
                                                            <input type="text" id="review-title" class="input-reply" id="subject" autocomplete="off">
                                                            <span id="error-review-title" class="error-label">Please provide a review subject</span>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-md-6">
                                                <form class="form-horizontal" role="form">
                                                    <div class="form-group">
                                                        <label for="subject" class="col-xs-2 control-label" class="label-rate">Rating: </label>
                                                        <div class="col-xs-10" style="vertical-align: middle;">
                                                            <div class="span-star-container">
                                                                <i data-count="1" class="fa fa-star fa-star-rate js-rate"></i>
                                                                <i data-count="2"  class="fa fa-star fa-star-rate js-rate"></i>
                                                                <i data-count="3"  class="fa fa-star fa-star-rate js-rate"></i>
                                                                <i data-count="4"  class="fa fa-star fa-star-rate js-rate"></i>
                                                                <i data-count="5"  class="fa fa-star fa-star-rate js-rate"></i>
                                                                <input type="hidden" readonly value="0" id="star-rate" />
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        <div class="clear"></div>  
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="subject" class="col-md-2 control-label label-comment" style="padding-right: 15px;">Comment: </label>
                                                <div class="col-md-10 col-comment-review col-xs-12">
                                                    <textarea id="review-comment" class="input-textarea-write " rows="7"></textarea>
                                                    <span id="error-review-nessage" class="error-label error-label-textarea" >Please provide a review message</span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12" align="center">
                                                <button id="submitReview" class="btn-reply">
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</div>

