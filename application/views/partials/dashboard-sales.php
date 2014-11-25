
<?php if(isset($dateFrom) && $dateFrom !== null): ?>
<span class="credit-date-label">From: </span> <span class="credit-date"><?=$dateFrom; ?></span>
<span class="credit-date-label">To: </span> <span class="credit-date"><?=$dateTo; ?></span>
<?php endif; ?>
<div style="overflow-y:no-scroll;overflow-x:scroll; height:auto; height: auto;">
    <table class="table table-total-sales table-striped"> 
        <thead>
            <tr class="tr-orange">
                <th width="250px">Product Name</th>
                <th width="100px" align="center" id="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'th-date' : 'th-p-date'?>">Date Bought</th>
                <th width="250px" align="center" id="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'th-trans' : 'th-p-trans'?>">Transaction ID</th>
                <th width="250px" align="center" id="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'th-base-price' : 'th-p-base-price'?>">Base Price</th>
                <th width="100px" align="center" id="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'th-quantity' : 'th-p-quantity'?>">Quantity</th>
                <th width="250px" align="center" id="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'th-handling' : 'th-p-handling'?>">Handling Fee</th>
                <th width="250px" align="center" id="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'th-total' : 'th-p-total'?>">Total</th>
                <th width="250px" align="center" id="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'th-es-charge' : 'th-p-es-charge'?>">Easyshop Charge</th>
                <th width="27px" align="center" id="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'th-payment' : 'th-p-payment'?>">Payment Method Charge</th>
                <th width="250px" align="right" style="text-align: right;" id="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'th-net' : 'th-p-net'?>">Net Amount</th>
            </tr>
        </thead>

        <tbody>
            <?php if(count($sales) > 0): ?>
                <?php foreach ($sales as $transaction): ?>
                <tr>
                    <th width="250px"><?=htmlspecialchars(utf8_encode($transaction->getProduct()->getName()),ENT_QUOTES,'ISO-8859-1'); ?></td>
                    <td width="250px" align="center" class="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'td-date' : 'td-p-date'?>"><?=$transaction->getOrder()->getDateadded()->format('M/d/Y');?></td>
                    <td width="250px" align="center" class="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'td-trans' : 'td-p-trans'?>"><?=$transaction->getOrder()->getTransactionId();?></td>
                    <td width="250px" align="center" class="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'td-base-price' : 'td-p-base-price'?>">&#8369; <?=number_format($transaction->getPrice(),2,'.',',');?></td>
                    <td width="250px" align="center" class="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'td-quantity' : 'td-p-quantity'?>"><?=$transaction->getOrderQuantity(); ?></td>
                    <td width="250px" align="center" class="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'td-handling' : 'td-p-handling'?>">&#8369; <?=number_format($transaction->getHandlingFee(),2,'.',','); ?></td>
                    <td width="250px" align="center" class="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'td-total' : 'td-p-total'?>">&#8369; <?=number_format($transaction->getTotal(),2,'.',','); ?></td>
                    <td width="250px" align="center" class="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'td-es-charge' : 'td-p-es-charge'?>">&#8369; <?=number_format($transaction->getEasyshopCharge(),2,'.',','); ?></td>
                    <td width="250px" align="center" class="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'td-payment' : 'td-p-payment'?>">&#8369; <?=number_format($transaction->getPaymentMethodCharge(),2,'.',','); ?></td>
                    <td width="250px" align="right" class="<?=$type === EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER ? 'td-net' : 'td-p-net'?>">&#8369; <?=number_format($transaction->getNet(),2,'.',','); ?></td>
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


