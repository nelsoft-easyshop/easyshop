<div class="sales-title-total">
    <span class="trans-title">CURRENT SALES</span> 
    <span class="total-sale-amount">&#8369; <?=number_format($currentTotalSales,2,'.',',');?></span> 
</div>

<div id="current-sales-container" class="sales-breakdown-container">
    <input id="request-type-container" type="hidden" value="<?=EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER; ?>" /> 
    <span class="p-label-stat">
        Total Amount : 
    </span>

    <span class="p-stat-total">
        &#8369; <?=number_format($currentTotalSales,2,'.',',');?>
    </span>

    <div class="row">
        <div class="col-sm-7 col-xs-12">
            <div class="row">
                <div class="col-xs-6" style="padding-right:0px;">
                    <div class="form-search-item">
                    <input type="text" class="input-date-sales date-picker-sales date-from" placeholder="Start Date" />
                    <span class="fa fa-calendar"></span>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-search-item">
                    <input type="text" class="input-date-sales date-picker-sales date-to" placeholder="End Date" />
                    <span class="fa fa-calendar"></span>
                    </div>
                </div>
                <div class="col-xs-6">
                    <input type="button" data-request="<?=EasyShop\Entities\EsOrderProductStatus::FORWARD_SELLER; ?>" data-container="current-sales-container" class="filter-sales" value="Search" />
                </div>
            </div>
        </div>
        <div class="col-sm-2">
        </div>
        <div class="col-sm-3 col-xs-12" >
            <div style="position: relative; width: 100%;">
                <div class="hide-selection-box">
                    Hide a column <i class="icon-dropdown pull-right"></i>
                </div>
                <div class="hide-selection-cont">
                    <div>
                        <input type="checkbox" id="hide-date"/><label class="control-label">Hide date</label>
                    </div>
                    <div>
                        <input type="checkbox" id="hide-trans"/><label class="control-label">Hide transaction ID</label>
                    </div>
                    <div>
                        <input type="checkbox" id="hide-trans"/><label class="control-label">Base Price</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <div class="sales-container ">
        <?=$currentSales; ?>
    </div>
</div>
<br/>
<div class="payout-title-total">
    <span class="trans-title">HISTORY OF SALES</span> 
    <span class="payout-sale-amount">&#8369; <?=number_format($historyTotalSales,2,'.',',');?></span> 
</div>

<div id="history-sales-container" class="payout-breakdown-container">
    <input id="request-type-container" type="hidden" value="<?=EasyShop\Entities\EsOrderProductStatus::PAID_FORWARDED; ?>" /> 
    <span class="p-label-stat">
        Payout Amount : 
    </span>

    <span class="p-stat-total">
        &#8369; <?=number_format($historyTotalSales,2,'.',',');?>
    </span>

    <div class="row">
        <div class="col-sm-7 col-xs-12">
            <div class="row">
                <div class="col-xs-6" style="padding-right:0px;">
                    <div class="form-search-item">
                    <input type="text" class="input-date-sales date-picker-sales date-from" placeholder="Start Date" />
                    <span class="fa fa-calendar"></span>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-search-item">
                    <input type="text" class="input-date-sales date-picker-sales date-to" placeholder="End Date" />
                    <span class="fa fa-calendar"></span>
                    </div>
                </div>
                <div class="col-xs-6">
                    <input type="button" data-request="<?=EasyShop\Entities\EsOrderProductStatus::PAID_FORWARDED; ?>" data-container="history-sales-container" class="filter-sales" value="Search" />
                </div>
            </div>
        </div>
        <div class="col-sm-2">
        </div>
        <div class="col-sm-3 col-xs-12" >
            <div style="position: relative; width: 100%;">
                <div class="hide-selection-box">
                    Hide a column <i class="icon-dropdown pull-right"></i>
                </div>
                <div class="hide-selection-cont">
                    <div>
                        <input type="checkbox" id="hide-date"/><label class="control-label">Hide date</label>
                    </div>
                    <div>
                        <input type="checkbox" id="hide-trans"/><label class="control-label">Hide transaction ID</label>
                    </div>
                    <div>
                        <input type="checkbox" id="hide-trans"/><label class="control-label">Base Price</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <div class="sales-container ">
        <?=$historySales; ?>
    </div>
</div>