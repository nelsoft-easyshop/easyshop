
<?php if(isset($dateFrom) && $dateFrom !== null): ?>
<span class="credit-date-label">From: </span> <span class="credit-date"><?=$dateFrom; ?></span>
<span class="credit-date-label">To: </span> <span class="credit-date"><?=$dateTo; ?></span>
<?php endif; ?>
<div style="overflow-y:no-scroll;overflow-x:scroll; height:auto; height: auto;">
    <table class="table table-total-sales table-striped"> 
        <thead>
            <tr class="tr-orange">
                <th width="250px">Product Name</th>
                <th width="100px" align="center" id="th-date">Date Bought</th>
                <th width="250px" align="center" id="th-trans">Transaction ID</th>
                <th width="250px" align="center" id="th-base-price">Base Price</th>
                <th width="100px" align="center" id="th-quantity">Quantity</th>
                <th width="250px" align="center" id="th-handling">Handling Fee</th>
                <th width="250px" align="center" id="th-total">Total</th>
                <th width="250px" align="center" id="th-es-charge">Easyshop Charge</th>
                <th width="27px" align="center" id="th-payment">Payment Method Charge</th>
                <th width="250px" align="right" style="text-align: right;" id="th-net">Net Amount</th>
            </tr>
        </thead>

        <tbody>
            <?php if(count($sales) > 0): ?>
                <?php foreach ($sales as $transaction): ?>
                <tr>
                    <th width="250px"><?=htmlspecialchars(utf8_encode($transaction->getProduct()->getName()),ENT_QUOTES,'ISO-8859-1'); ?></td>
                    <td width="250px" align="center" class="td-p-date"><?=$transaction->getOrder()->getDateadded()->format('M/d/Y');?></td>
                    <td width="250px" align="center" class="td-p-trans"><?=$transaction->getOrder()->getTransactionId();?></td>
                    <td width="250px" align="center" class="td-p-base-price">&#8369; <?=number_format($transaction->getPrice(),2,'.',',');?></td>
                    <td width="250px" align="center" class="td-p-quantity"><?=$transaction->getOrderQuantity(); ?></td>
                    <td width="250px" align="center" class="td-p-handling">&#8369; <?=number_format($transaction->getHandlingFee(),2,'.',','); ?></td>
                    <td width="250px" align="center" class="td-p-total">&#8369; <?=number_format($transaction->getTotal(),2,'.',','); ?></td>
                    <td width="250px" align="center" class="td-p-es-charge">&#8369; <?=number_format($transaction->getEasyshopCharge(),2,'.',','); ?></td>
                    <td width="250px" align="center" class="td-p-payment">&#8369; <?=number_format($transaction->getPaymentMethodCharge(),2,'.',','); ?></td>
                    <td width="250px" align="right" class="td-p-net">&#8369; <?=number_format($transaction->getNet(),2,'.',','); ?></td>
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
<center>
<?=$pagination; ?>
</center>


