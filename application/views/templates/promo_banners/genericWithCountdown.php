<?php if($product->getStartPromo()): ?>
    
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="div-banner-container">
                    <div class="row">
                        <div class="col-xs-12" align="center">
                            <div class="div-inline">
                                <div class="circle-promo">
                                    PROMO!
                                </div>
                            </div>
                            <div class="div-inline">
                                <div class="time-container">
                                    <p class="p-time-left">For more information, visit our <a href="<?=isset($externalLink[\EasyShop\Entities\EsSocialMediaProvider::FACEBOOK]) ? $externalLink[\EasyShop\Entities\EsSocialMediaProvider::FACEBOOK]->getLink(): \EasyShop\Entities\EsProductExternalLink::DEFAULT_LINK?>" target="_blank">Facebook page</a></p>
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
                                <div class="circle-promo">
                                    <a href="#"><i class="fa fa-facebook fa-2x"></i></a>
                                </div>
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
        <input id="remainingTime" type="hidden" value='<?php echo $remainingTime?>'/>
        <input type="hidden" id="dateOfAnnouncement" data-date="<?php echo isset($externalLink[\EasyShop\Entities\EsSocialMediaProvider::FACEBOOK]) ? $externalLink[\EasyShop\Entities\EsSocialMediaProvider::FACEBOOK]->getDateOfAnnouncement()->format("F d, Y") : ''?>">
    </div>
    <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
        <script src="/assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
        <script src="/assets/js/src/vendor/bower_components/jquery.countdown.js" type="text/javascript"></script> 
        <script src="/assets/js/src/promo/generic-with-countdown.js" type="text/javascript"></script> 
    <?php else:?>
        <script src="/assets/js/min/easyshop.genericWithCountdown.js" type="text/javascript"></script>
    <?php endif;?>
    
<?php endif; ?>
