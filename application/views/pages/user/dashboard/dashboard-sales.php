<div class="sales-title-total">
    <span class="trans-title">CURRENT SALES</span> 
    <span class="total-sale-amount">&#8369; <?=number_format($currentTotalSales,2,'.',',');?></span> 
</div>

<div class="sales-breakdown-container">
    <span class="p-label-stat">
        Total Amount : 
    </span>
    <?=$currentSales; ?>
</div>
<br/>
<div class="payout-title-total">
    <span class="trans-title">HISTORY OF SALES</span> 
    <span class="payout-sale-amount">&#8369; <?=number_format($historyTotalSales,2,'.',',');?></span> 
</div>

<div class="payout-breakdown-container">
    <span class="p-label-stat">
        Payout Amount : 
    </span>
    <?=$historySales; ?>
</div>