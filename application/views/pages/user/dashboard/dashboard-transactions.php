
<div class="div-tab">
    <div class="div-tab-inner">
        <div class="transaction-tabs">
            <ul class="idTabs">
                <li><a href="#on-going-transaction" class="on-going-transaction-header">On going</a></li>
                <li><a href="#completed-transaction" class="completed-transaction-header">Completed</a></li>
            </ul>
        </div>
        <!---------------------------------------------------------------ongoing bought starts here---------------------------------------------------------------->
        <div class="col-md-12" id="on-going-transaction">
            <div class="row">
                <div class="transaction-title-bought transaction-button-head" data-method="ongoing-bought">
                    <span class="trans-title">Bought</span>
                    <span class="count"><?php echo $ongoingBoughtTransactionsCount?></span>
                </div>
                <div class="on-going-transaction-list-bought list-container">
                    <?PHP if ( (int) $ongoingBoughtTransactionsCount >= 1) : ?>
                        <div class="mrgn-top-20 mrgn-bttm-15 row">
                            <div class="col-sm-8 transaction-top-btns">
                                <input type="text" class="ui-form-control transaction-search search-transaction-num" data="ongoing-bought" placeholder="Enter transaction no.">
                                <button class="btn btn-default-3 printTransactions" data-url="/memberpage/printBuyTransactions" data-isongoing = "1">
                                    <i class="icon-fax"></i> <span>Print</span>
                                </button>
                                <button class="btn btn-default-3 exportTransactions" data-url="/memberpage/exportBuyTransactions" data-isongoing = "1">
                                    <i class="icon-file"></i> <span>Export CSV</span>
                                </button>
                            </div>
                            <div class="col-sm-4 text-right">
                                <span>Payment Filter:</span>
                                <select class="select-filter-item payment-filter" data="ongoing-bought">
                                    <option value="all" selected=selected>Show all</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_PAYPAL?>">PayPal</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_DRAGONPAY?>">DragonPay</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY?>">Cash On Delivery</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_PESOPAYCC?>">PesoPay</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_POINTS?>">Easypoint</option>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div id="ongoing-bought">
                        </div>
                    <?PHP else : ?>
                        <br/>
                        <div class="jumbotron no-items">
                            <i class="icon-category"></i> You have not bought any items yet.
                        </div>
                    <?PHP endif; ?>
                </div>
            <!---------------------------------------------------------------ongoing sold starts here---------------------------------------------------------------->
                <div class="transaction-title-sold mrgn-top-12 transaction-button-head" data-method="ongoing-sold">
                    <span class="trans-title">Sold</span>
                    <span class="count"><?=$ongoingSoldTransactionsCount?></span>
                </div>
                <div class="on-going-transaction-list-sold list-container">
                    <?PHP if ( (int) $ongoingSoldTransactionsCount >= 1) : ?>
                        <div class="mrgn-top-20 mrgn-bttm-15 row">
                            <div class="col-sm-8 transaction-top-btns">
                                <input type="text" class="ui-form-control transaction-search search-transaction-num" data="ongoing-sold" placeholder="Enter transaction no.">
                                <button class="btn btn-default-3 printTransactions" data-url="/memberpage/printSellTransactions" data-isongoing = "1">
                                    <i class="icon-fax"></i> <span>Print</span>
                                </button>
                                <button class="btn btn-default-3 exportTransactions" data-url="/memberpage/exportSellTransactions" data-isongoing = "1">
                                    <i class="icon-file"></i> <span>Export CSV</span>
                                </button>
                            </div>
                            <div class="col-sm-4 text-right">
                                <span>Payment Filter:</span>
                                <select class="select-filter-item payment-filter" data="ongoing-sold">
                                    <option value="all" selected=selected>Show all</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_PAYPAL?>">PayPal</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_DRAGONPAY?>">DragonPay</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY?>">Cash On Delivery</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_PESOPAYCC?>">PesoPay</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_POINTS?>">Easypoint</option>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                    <div id="ongoing-sold">
                    </div>
                    <?PHP else : ?>
                        <br/>
                        <div class="jumbotron no-items">
                            <i class="icon-category"></i> You have not sold any items yet.
                        </div>
                    <?PHP endif; ?>
                </div>
            </div>
        </div>
    <!---------------------------------------------------------------completed bought starts here---------------------------------------------------------------->
        <div class="col-md-12" id="completed-transaction">
            <div class="row">
                <div class="transaction-title-bought-completed transaction-button-head" data-method="complete-bought">
                    <span class="trans-title">Bought</span>
                    <span class="count"><?=$completeBoughtTransactionsCount?></span>
                </div>
                <div class="on-going-transaction-list-bought-completed list-container">
                    <?PHP if ( (int) $completeBoughtTransactionsCount >= 1) : ?>
                    <div class="mrgn-top-20 mrgn-bttm-15 row">
                        <div class="col-sm-8 transaction-top-btns">
                            <input type="text" class="ui-form-control transaction-search search-transaction-num" data="complete-bought" placeholder="Enter transaction no.">
                            <button class="btn btn-default-3 printTransactions" data-url="/memberpage/printBuyTransactions" data-isongoing = "0">
                                <i class="icon-fax"></i> <span>Print</span>
                            </button>
                            <button class="btn btn-default-3 exportTransactions" data-url="/memberpage/exportBuyTransactions" data-isongoing = "0">
                                <i class="icon-file"></i> <span>Export CSV</span>
                            </button>                            
                        </div>
                        <div class="col-sm-4 text-right">
                            <span>Payment Filter:</span>
                            <select class="select-filter-item payment-filter" data="complete-bought">
                                <option value="all" selected=selected>Show all</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_PAYPAL?>">PayPal</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_DRAGONPAY?>">DragonPay</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY?>">Cash On Delivery</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_PESOPAYCC?>">PesoPay</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_POINTS?>">Easypoint</option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="complete-bought">
                    </div>
                    <?PHP else : ?>
                        <br/>
                        <div class="jumbotron no-items">
                            <i class="icon-category"></i> There are no transactions for this category.
                        </div>
                    <?PHP endif; ?>
                </div>
            <!---------------------------------------------------------------completed sold starts here---------------------------------------------------------------->
                <div class="transaction-title-sold-completed mrgn-top-12 transaction-button-head" data-method="complete-sold">
                    <span class="trans-title">Sold</span>
                    <span class="count"><?=$completeSoldTransactionsCount?></span>
                </div>
                <div class="on-going-transaction-list-sold-completed list-container">
                    <?PHP if ( (int) $completeSoldTransactionsCount >= 1) : ?>
                    <div class="mrgn-top-20 mrgn-bttm-15 row">
                        <div class="col-sm-8 transaction-top-btns">
                            <input type="text" class="ui-form-control transaction-search search-transaction-num" data="complete-sold" placeholder="Enter transaction no.">
                            <button class="btn btn-default-3 printTransactions" data-url="/memberpage/printSellTransactions" data-isongoing = "0">
                                <i class="icon-fax"></i> <span>Print</span>
                            </button>
                            <button class="btn btn-default-3 exportTransactions" data-url="/memberpage/exportSellTransactions" data-isongoing = "0">
                                <i class="icon-file"></i> <span>Export CSV</span>
                            </button>                                 
                        </div>
                        <div class="col-sm-4 text-right">
                            <span>Payment Filter:</span>
                            <select class="select-filter-item payment-filter" data="complete-sold">
                                <option value="all" selected=selected>Show all</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_PAYPAL?>">PayPal</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_DRAGONPAY?>">DragonPay</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY?>">Cash On Delivery</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_PESOPAYCC?>">PesoPay</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_POINTS?>">Easypoint</option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="complete-sold">
                    </div>
                    <?PHP else : ?>
                        <br/>
                        <div class="jumbotron no-items">
                            <i class="icon-category"></i> There are no transactions for this category.
                        </div>
                    <?PHP endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>