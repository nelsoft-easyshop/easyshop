
<div class="div-tab">
    <div class="dashboard-breadcrumb">
        <ul>
            <li>Dashboard</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>My Store</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>Transactions</li>
        </ul>
    </div>
    <div class="div-tab-inner">
        <div class="transaction-tabs">
            <ul class="idTabs">
                <li><a href="#on-going-transaction">On going</a></li>
                <li><a href="#completed-transaction">Completed</a></li>
            </ul>
        </div>
    <!---------------------------------------------------------------ongoing bought starts here---------------------------------------------------------------->
        <div class="col-md-12" id="on-going-transaction">
            <div class="row">
                <div class="transaction-title-bought">
                    <span class="trans-title">Bought</span>
                    <span class="count"><?=$ongoingBoughtTransactionsCount?></span>
                </div>
                <div class="on-going-transaction-list-bought">
                    <?PHP if ( (int) $ongoingBoughtTransactionsCount >= 1) : ?>
                        <div class="mrgn-top-20 mrgn-bttm-15 row">
                            <div class="col-md-9 transaction-top-btns">
                                <input type="text" class="ui-form-control transaction-search search-transaction-num" data="ongoing-bought" placeholder="Enter transaction no.">
                                <button class="btn btn-default-3 printTransactions" data-url="/memberpage/printBuyTransactions">
                                    <i class="icon-fax"></i> <span>Print</span>
                                </button>
                                <button class="btn btn-default-3 exportTransactions" data-url="/memberpage/exportBuyTransactions">
                                    <i class="icon-file"></i> <span>Export CSV</span>
                                </button>
                            </div>
                            <div class="col-md-3 text-right">
                                <span>Payment Filter:</span>
                                <select class="select-filter-item payment-filter" data="ongoing-bought">
                                    <option value="all" selected=selected>Show all</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_PAYPAL?>">PayPal</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_DRAGONPAY?>">DragonPay</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY?>">Cash On Delivery</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_DIRECTBANKDEPOSIT?>">Direct Bank Deposit</option>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div id="ongoing-bought">
                            <?=$transactionInfo['ongoing']['bought']?>
                        </div>
                    <?PHP else : ?>
                        <br/>
                        <div class="jumbotron no-items">
                            <i class="icon-category"></i> You have not bought any items yet.
                        </div>
                    <?PHP endif; ?>
                </div>
            <!---------------------------------------------------------------ongoing sold starts here---------------------------------------------------------------->
                <div class="transaction-title-sold mrgn-top-12">
                    <span class="trans-title">Sold</span>
                    <span class="count"><?=$ongoingSoldTransactionsCount?></span>
                </div>
                <div class="on-going-transaction-list-sold">
                    <?PHP if ( (int) $ongoingSoldTransactionsCount >= 1) : ?>
                        <div class="mrgn-top-20 mrgn-bttm-15 row">
                            <div class="col-md-9 transaction-top-btns">
                                <input type="text" class="ui-form-control transaction-search search-transaction-num" data="ongoing-sold" placeholder="Enter transaction no.">
                                <button class="btn btn-default-3 printTransactions" data-url="/memberpage/printSellTransactions">
                                    <i class="icon-fax"></i> <span>Print</span>
                                </button>
                                <button class="btn btn-default-3 exportTransactions" data-url="/memberpage/exportSellTransactions">
                                    <i class="icon-file"></i> <span>Export CSV</span>
                                </button>
                            </div>
                            <div class="col-md-3 text-right">
                                <span>Payment Filter:</span>
                                <select class="select-filter-item payment-filter" data="ongoing-sold">
                                    <option value="all" selected=selected>Show all</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_PAYPAL?>">PayPal</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_DRAGONPAY?>">DragonPay</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY?>">Cash On Delivery</option>
                                    <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_DIRECTBANKDEPOSIT?>">Direct Bank Deposit</option>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                    <div id="ongoing-sold">
                        <?=$transactionInfo['ongoing']['sold']?>
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
                <div class="transaction-title-bought-completed">
                    <span class="trans-title">Bought</span>
                    <span class="count"><?=$completeBoughtTransactionsCount?></span>
                </div>
                <div class="on-going-transaction-list-bought-completed">
                    <?PHP if ( (int) $completeBoughtTransactionsCount >= 1) : ?>
                    <div class="mrgn-top-20 mrgn-bttm-15 row">
                        <div class="col-md-9 transaction-top-btns">
                            <input type="text" class="ui-form-control transaction-search search-transaction-num" data="complete-bought" placeholder="Enter transaction no.">
                        </div>
                        <div class="col-md-3 text-right">
                            <span>Payment Filter:</span>
                            <select class="select-filter-item payment-filter" data="complete-bought">
                                <option value="all" selected=selected>Show all</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_PAYPAL?>">PayPal</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_DRAGONPAY?>">DragonPay</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY?>">Cash On Delivery</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_DIRECTBANKDEPOSIT?>">Direct Bank Deposit</option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="complete-bought">
                        <?=$transactionInfo['complete']['bought']?>
                    </div>
                    <?PHP else : ?>
                        <br/>
                        <div class="jumbotron no-items">
                            <i class="icon-category"></i> There are no transactions for this category.
                        </div>
                    <?PHP endif; ?>
                </div>
            <!---------------------------------------------------------------completed sold starts here---------------------------------------------------------------->
                <div class="transaction-title-sold-completed mrgn-top-12">
                    <span class="trans-title">Sold</span>
                    <span class="count"><?=$completeSoldTransactionsCount?></span>
                </div>
                <div class="on-going-transaction-list-sold-completed">
                    <?PHP if ( (int) $completeSoldTransactionsCount >= 1) : ?>
                    <div class="mrgn-top-20 mrgn-bttm-15 row">
                        <div class="col-md-9">
                            <input type="text" class="ui-form-control transaction-search search-transaction-num" data="complete-sold" placeholder="Enter transaction no.">
                        </div>
                        <div class="col-md-3 text-right">
                            <span>Payment Filter:</span>
                            <select class="select-filter-item payment-filter" data="complete-sold">
                                <option value="all" selected=selected>Show all</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_PAYPAL?>">PayPal</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_DRAGONPAY?>">DragonPay</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_CASHONDELIVERY?>">Cash On Delivery</option>
                                <option value="<?=\EasyShop\Entities\EsPaymentMethod::PAYMENT_DIRECTBANKDEPOSIT?>">Direct Bank Deposit</option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="complete-sold">
                        <?=$transactionInfo['complete']['sold']?>
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