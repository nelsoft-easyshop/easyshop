<?php if(!$product->getEndPromo()): ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="div-banner-container">
                <div class="row">
                    <div class="col-xs-12" align="center">
                        <div class="div-inline">
                            <div class="circle-promo">
                                <span class="small-countdown">COUNTDOWN</span>
                                SALE!
                            </div>
                        </div>
                        <div class="div-inline">
                            <div class="time-container">
                                <?php if($product->getStartPromo()): ?>
                                    <p class="p-time-left">TIME REMAINING <span class="span-per-hour-rate">2% PER HOUR</span></p>
                                <?php else: ?>  
                                    <p class="p-time-left">2% OFF STARTS IN </p> 
                                <?php endif; ?>
                                <table id="table-countdown" align="center">
                                    <tr>
                                        <td class="td-time-num">
                                            <span class="span-time-num">00</span>
                                            <span class="span-time-label">DAYS</td>
                                        </td>
                                        <td class="td-time-num">
                                            <span class="span-time-num">00</span>
                                            <span class="span-time-label">HOURS</td>
                                        </td>
                                        <td class="td-time-num">
                                            <span class="span-time-num">00</span>
                                            <span class="span-time-label">MINUTES</td>
                                        </td>
                                        <td class="td-time-num">
                                            <span class="span-time-num">00</span>
                                            <span class="span-time-label">SECONDS</td>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="div-inline">
                            <?php if($product->getStartPromo()): ?> 
                            <div class="circle-promo">
                                <span class="main-promo-title"><?php echo number_format( $product->getDiscountPercentage(),0,'.',',');?>%</span>
                                <span class="sub-promo-title">OFF</span>
                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <input id="endDate" type="hidden" value='<?php echo date('M d,Y H:i:s',strtotime(($product->getStartPromo() == "1" ? $product->getEnddate()->format("Y-m-d h:i:s"): $product->getStartdate()->format("Y-m-d h:i:s")))); ?>' >
</div>

<script src="/assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script> 
<script src="/assets/js/src/promo/countdown-sale.js" type="text/javascript"></script>

<?php endif; ?>