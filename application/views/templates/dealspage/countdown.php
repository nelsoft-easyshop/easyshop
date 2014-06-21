   <!-- start of countdown sales -->
    <div class="countdown_category_banner">
        <div class="cd_timer_container">
            <div class="cd_timer_days">
                <span id='countdown_days'>00</span>
                <span>DAYS</span>
            </div>
            <div class="cd_timer_hours">
                <span id='countdown_hours'>00</span>
                <span>HOURS</span>
            </div>
            <div class="cd_timer_minutes">
                <span id='countdown_minutes'>00</span>
                <span>MINUTES</span>
            </div>
            <div class="cd_timer_seconds">
                <span id="countdown_second">00</span>
                <span>SECONDS</span> 
            </div>
        </div>
        <div class='cd_timerlabel_container'><span class='cd_timerlabel'><?php echo (strtotime(date('M d,Y H:i:s')) < strtotime($startdate))?'STARTS IN':'TIME LEFT';?></span></div>
    </div>
    <div class="cd_announcement">
        Due to our users' popular demand, we are now opening our promo items for our regular payment methods 
        like <br /><strong>DragonPay</strong> and <strong>COD (Cash-on-Delivery)</strong>.
    </div>
    
    
    <script type='text/javascript'>
        $(document).ready(function(){
            var timer_date = new Date($('#timer_date').val());
            $('.cd_timer_container').countdown({
                until : timer_date,
                serverSync: serverTime,
                layout: ' <div class="cd_timer_days"><span id="countdown_days">{dnn}</span> <span>DAYS</span> </div>'+
                        ' <div class="cd_timer_hours"><span id="countdown_hours">{hnn}</span> <span>HOURS</span> </div>'+
                        ' <div class="cd_timer_minutes"><span id="countdown_minutes">{mnn}</span> <span>MINUTES</span> </div>' +
                        ' <div class="cd_timer_seconds"><span id="countdown_second">{snn}</span> <span>SECONDS</span> </div>',
                onExpiry: reload,
            });
        });
    </script>