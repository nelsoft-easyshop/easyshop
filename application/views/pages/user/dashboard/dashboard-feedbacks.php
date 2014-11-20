
<div class="feedback-from-seller">
    <span class="trans-title">Feedback from Seller</span> 
    <span class="count"><?=$asBuyerFeedbackCount;?></span> 
</div>
<br/>
<div class="feedback-from-seller-container">
    <?php if($asBuyerFeedbackCount <= 0): ?>
    <div class="jumbotron no-items">
        <i class="icon-category"></i>No items for this category
    </div>
    <?php else: ?>
    <?=$asBuyerView; ?>
    <?php endif; ?>
</div>


<div class="feedback-from-buyer">
    <span class="trans-title">Feedback from Buyer</span> 
    <span class="count"><?=$asSellerFeedbackCount;?></span> 
</div>
<br/>
<div class="feedback-from-buyer-container">
    <?php if($asSellerFeedbackCount <= 0): ?>
    <div class="jumbotron no-items">
        <i class="icon-category"></i>No items for this category
    </div>
    <?php else: ?>
    <?=$asSellerView; ?>
    <?php endif; ?>
</div>

<div class="feedback-for-seller">
    <span class="trans-title">Feedback for Seller</span> 
    <span class="count"><?=$asOtherSellerFeedbackCount;?></span> 
</div>
<br/>
<div class="feedback-for-seller-container">
    <?php if($asOtherSellerFeedbackCount <= 0): ?>
    <div class="jumbotron no-items">
        <i class="icon-category"></i>No items for this category
    </div>
    <?php else: ?>
    <?=$asOtherSellerView; ?>
    <?php endif; ?>
    <br/>
</div>

<div class="feedback-for-buyer">
    <span class="trans-title">Feedback for Buyer</span> 
    <span class="count"><?=$asOtherBuyerFeedbackCount;?></span> 
</div>
<br/>
<div class="feedback-for-buyer-container">
    <?php if($asOtherBuyerFeedbackCount <= 0): ?>
    <div class="jumbotron no-items">
        <i class="icon-category"></i>No items for this category
    </div>
    <?php else: ?>
    <?=$asOtherBuyerView; ?>
    <?php endif; ?>
    <br/>
</div> 
 