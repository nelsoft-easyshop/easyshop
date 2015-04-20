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
                                        <p class="p-time-left">TIME REMAINING <span class="span-per-hour-rate">DISCOUNT PER HOUR</span></p>
                                    <?php else: ?>
                                        <p class="p-time-left">PROMO STARTS IN </p>
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
        

        <?php $targetDate = $product->getStartPromo() ? $product->getEnddate() : $product->getStartdate(); ?>
        <?php $remainingTime = $targetDate->getTimestamp() - time(); ?>
        
        <input id="remainingTime" type="hidden" value='<?php echo $remainingTime?>'/>s

    </div>

    <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
        <script src="/assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
        <script src="/assets/js/src/vendor/jquery.hilios.countdown.js" type="text/javascript"></script> 
        <script src="/assets/js/src/promo/countdown-sale.js" type="text/javascript"></script>
    <?php else:?>
        <script src="/assets/js/min/easyshop.countdown.js" type="text/javascript"></script>
    <?php endif;?>
<?php endif; ?>
