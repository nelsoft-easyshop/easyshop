<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="div-banner-container">
                <div class="row">
                    <div class="col-xs-12" align="center">
                        <div class="div-inline">
                            <div class="circle-promo">
                                SALE!
                            </div>
                        </div>
                        <div class="div-inline">
                            <div class="time-container">
                              <?php if($product->getStartPromo()): ?> 
                                    <p class="p-time-left">TIME REMAINING</p>
                                <?php else: ?>  
                                    <p class="p-time-left">PROMO STARTS IN</p> 
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
                            <div class="circle-promo">
                                <span class="main-promo-title"><?=number_format( $product->getDiscountPercentage(),0,'.',',');?>%</span>
                                <span class="sub-promo-title">OFF</span> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script>
<script type='text/javascript'>
    $(document).ready(function(){
        var endDate = new Date(<?php echo json_encode(date('M d,Y H:i:s',strtotime(($product->getStartPromo() == "1" ? $product->getEnddate()->format("Y-m-d h:i:s"): $product->getStartdate()->format("Y-m-d h:i:s"))))); ?>);
        $('#table-countdown').countdown({
            until : endDate,
            serverSync: serverTime,
            layout: ' <td class="td-time-num"><span class="span-time-num">{dnn}</span><span class="span-time-label">DAYS</td></td>'+
                ' <td class="td-time-num"><span class="span-time-num">{hnn}</span><span class="span-time-label">HOURS</td></td>'+
                ' <td class="td-time-num"><span class="span-time-num">{mnn}</span><span class="span-time-label">MINUTES</td></td>' +
                ' <td class="td-time-num"><span class="span-time-num">{snn}</span><span class="span-time-label">SECONDS</td></td>',
            onExpiry: reload,
        });
    });
</script>