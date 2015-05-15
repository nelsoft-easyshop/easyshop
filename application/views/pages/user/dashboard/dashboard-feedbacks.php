
<div class="row">
    <div class="col-xs-12">
        <div class="form-filter">
            <label class="label-sort">Filter by:</label>
            <select id="select-feedback-filter" class="select-filter-item filter-feedbacks">
                <option value="<?=EasyShop\Entities\EsMemberFeedback::TYPE_ALL;?>" >All</option>
                <option value="<?=EasyShop\Entities\EsMemberFeedback::TYPE_AS_BUYER;?>" >Feedback from seller</option>
                <option value="<?=EasyShop\Entities\EsMemberFeedback::TYPE_AS_SELLER;?>" >Feedback from buyer</option>
                <option value="<?=EasyShop\Entities\EsMemberFeedback::TYPE_FOR_OTHERS_AS_SELLER;?>" >Feedback for buyer</option>
                <option value="<?=EasyShop\Entities\EsMemberFeedback::TYPE_FOR_OTHERS_AS_BUYER;?>" >Feedback for seller</option>
            </select>
        </div>
        <br/>
    </div>
</div>
<br/> 
<div id="feedback-view-container"></div>

