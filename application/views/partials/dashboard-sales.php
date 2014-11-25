
<?php if(isset($dateFrom) && $dateFrom !== null): ?>
<span class="credit-date-label">From: </span> <span class="credit-date"><?=$dateFrom; ?></span>
<span class="credit-date-label">To: </span> <span class="credit-date"><?=$dateTo; ?></span>
<?php endif; ?>
<div style="overflow-y:no-scroll;overflow-x:scroll; height:auto; height: auto;">
    <table class="table table-striped table-total-sales table-bordered"> 
        <thead>
            <tr class="tr-orange">
                <th width="15%">Product Name</th>
                <th align="right">Base Price</th>
                <th align="right">Quantity</th>
                <th align="right">Handling Fee</th>
                <th align="right">Total</th>
                <th align="right">Easyshop Charge</th>
                <th align="right">Payment Method Charge</th>
                <th align="right">Net Amount</th>
            </tr>
        </thead>

        <tbody>
            <?php if(count($sales) > 0): ?>
                <?php foreach ($sales as $transaction): ?>
                <tr>
                    <td><?=htmlspecialchars(utf8_encode($transaction->getProduct()->getName()),ENT_QUOTES,'ISO-8859-1'); ?></td>
                    <td align="right">&#8369; <?=number_format($transaction->getPrice(),2,'.',',');?></span></td>
                    <td align="right"><?=$transaction->getOrderQuantity(); ?></td>
                    <td align="right">&#8369; <?=number_format($transaction->getHandlingFee(),2,'.',','); ?></td>
                    <td align="right">&#8369; <?=number_format($transaction->getTotal(),2,'.',','); ?></td>
                    <td align="right">&#8369; <?=number_format($transaction->getEasyshopCharge(),2,'.',','); ?></td>
                    <td align="right">&#8369; <?=number_format($transaction->getPaymentMethodCharge(),2,'.',','); ?></td>
                    <td align="right">&#8369; <?=number_format($transaction->getNet(),2,'.',','); ?></td>
                </tr> 
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" align="center">No data available</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?=$pagination; ?>


