
<div class="row">
    <div class="col-xs-12">
        <div class="form-filter">
            <label class="label-sort">Filter by:</label>
            <select id="select-feedback-filter" class="select-filter-item filter-feedbacks">
                <option value="<?=$allFeedBackConstant;?>" >All</option>
                <option value="<?=$asBuyerConstant;?>" >Feedback as buyer</option>
                <option value="<?=$asSellerConstant;?>" >Feedback as seller</option>
                <option value="<?=$asOtherSellerConstant;?>" >Feedback for seller</option>
                <option value="<?=$asOtherBuyerConstant;?>" >Feedback for buyer</option>
            </select>
        </div>
    </div>
</div>
<br/> 
<div id="feedback-view-container">
    <?=$feedBackView; ?>
</div>

