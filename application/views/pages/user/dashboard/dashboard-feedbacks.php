
<div class="row">
    <div class="col-xs-12">
        <div class="form-filter">
            <label class="label-sort">Sort by:</label>
            <select class="select-filter-item filter-feedbacks">
                <option value="0" >All</option>
                <option value="<?=$asBuyerConstant;?>" >Feedback as seller</option>
                <option value="<?=$asSellerConstant;?>" >Feedback as buyer</option>
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

