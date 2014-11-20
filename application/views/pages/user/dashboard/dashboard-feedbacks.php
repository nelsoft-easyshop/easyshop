
<div class="feedback-from-seller">
    <span class="trans-title">Feedback from Seller</span> 
    <span class="count"><?=$asBuyerFeedbackCount;?></span> 
</div>
<br/>
<div class="feedback-from-seller-container">
    <input type="hidden" class="feedback-type" value=<?=$asBuyerConstant;?> />
    <input type="hidden" class="feedback-hidden-container" value="as-buyer" />
    <?php if($asBuyerFeedbackCount <= 0): ?>
    <div class="jumbotron no-items">
        <i class="icon-category"></i>No items for this category
    </div>
    <?php else: ?>
    <div class="feedbacks-container">
        <?=$asBuyerView; ?>
    </div>
    <?php endif; ?>
</div>


<div class="feedback-from-buyer">
    <span class="trans-title">Feedback from Buyer</span> 
    <span class="count"><?=$asSellerFeedbackCount;?></span> 
</div>
<br/>
<div class="feedback-from-buyer-container">
    <input type="hidden" class="feedback-type" value=<?=$asSellerConstant;?> />
    <input type="hidden" class="feedback-hidden-container" value="as-seller" />
    <?php if($asSellerFeedbackCount <= 0): ?>
    <div class="jumbotron no-items">
        <i class="icon-category"></i>No items for this category
    </div>
    <?php else: ?>
    <div class="feedbacks-container">
        <?=$asSellerView; ?>
    </div>
    <?php endif; ?>
</div>

<div class="feedback-for-seller">
    <span class="trans-title">Feedback for Seller</span> 
    <span class="count"><?=$asOtherSellerFeedbackCount;?></span> 
</div>
<br/>
<div class="feedback-for-seller-container">
    <input type="hidden" class="feedback-type" value=<?=$asOtherSellerConstant;?> />
    <input type="hidden" class="feedback-hidden-container" value="as-other-seller" />
    <?php if($asOtherSellerFeedbackCount <= 0): ?>
    <div class="jumbotron no-items">
        <i class="icon-category"></i>No items for this category
    </div>
    <?php else: ?>
    <div class="feedbacks-container">
        <?=$asOtherSellerView; ?>
    </div>
    <?php endif; ?>
    <br/>
</div>

<div class="feedback-for-buyer">
    <span class="trans-title">Feedback for Buyer</span> 
    <span class="count"><?=$asOtherBuyerFeedbackCount;?></span> 
</div>
<br/>
<div class="feedback-for-buyer-container">
    <input type="hidden" class="feedback-type" value=<?=$asOtherBuyerConstant;?> />
    <input type="hidden" class="feedback-hidden-container" value="as-other-buyer" />
    <?php if($asOtherBuyerFeedbackCount <= 0): ?>
    <div class="jumbotron no-items">
        <i class="icon-category"></i>No items for this category
    </div>
    <?php else: ?>
    <div class="feedbacks-container">
        <?=$asOtherBuyerView; ?>
    </div>
    <?php endif; ?>
    <br/>
</div> 
 