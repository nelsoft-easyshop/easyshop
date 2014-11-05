
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
                                <p class="p-time-left">For more information, visit our <a href="https://www.facebook.com/EasyShopPhilippines/photos/a.214678272075103.1073741828.211771799032417/277834815759448/?type=1">Facebook page</a></p>
                                <?php if($product->getStartPromo()): ?>
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
                                <?php endif; ?>
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


