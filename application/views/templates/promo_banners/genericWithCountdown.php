<div class="panel panel-default panel-countdown">
    <div class="panel-heading">
        <center>
            <span class="span_bg icon_info"></span> <strong>This product is for promo use only. For more information, visit our
            <a href="<?php echo $facebook; ?>/photos/a.214678272075103.1073741828.211771799032417/277834815759448/?type=1">Facebook page</a></strong>
        </center>
    </div>
    <?php if($start_promo): ?>
    <div class="panel-body no-padding">
        <div class="row">
            <div class="col-md-5 no-padding">
            <center>
                <div id="dsc_cont">
                    <span class="genericWithCountdown_dsc_prp_txt3">TIME REMAINING</span>
                </div>
                </center>
            </div>
            
            <div class="col-md-7 no-padding">
                    <div class="cd_timer_container product_view <?php echo ($start_promo)?'':'float';?>">
                        <div class="cd_timer_days">
                            <span id='countdown_days'>00</span>
                            <span class="cnt_lgnd">DAYS</span>
                        </div>
                        <div class="cd_timer_hours">
                            <span id='countdown_hours'>00</span>
                            <span class="cnt_lgnd">HOURS</span>
                        </div>
                        <div class="cd_timer_minutes">
                            <span id='countdown_minutes'>00</span>
                            <span class="cnt_lgnd">MINUTES</span>
                        </div>
                        <div class="cd_timer_seconds">
                            <span id="countdown_second">00</span>
                            <span class="cnt_lgnd">SECONDS</span>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <?PHP endif; ?>
</div>



<script type='text/javascript'>
    $(document).ready(function(){
        var endDate = new Date(<?php echo json_encode(date('M d,Y H:i:s',strtotime(($start_promo == "1" ? $enddate: $startdate)))); ?>);
        $('.cd_timer_container').countdown({
            until : endDate,
            serverSync: serverTime,
            layout: ' <div class="cd_timer_days"><span id="countdown_days">{dnn}</span> <span class="cnt_lgnd">DAYS</span> </div>'+
                ' <div class="cd_timer_hours"><span id="countdown_hours">{hnn}</span> <span class="cnt_lgnd">HOURS</span> </div>'+
                ' <div class="cd_timer_minutes"><span id="countdown_minutes">{mnn}</span> <span class="cnt_lgnd">MINUTES</span> </div>' +
                ' <div class="cd_timer_seconds"><span id="countdown_second">{snn}</span> <span class="cnt_lgnd">SECONDS</span> </div>',
            onExpiry: reload,
        });
    });

</script>


