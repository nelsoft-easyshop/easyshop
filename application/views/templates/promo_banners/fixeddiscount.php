<div class="right_header_discount">
      <div id="dsc_cont">
          <span class="dsc_header_txt">
            <?php echo number_format( $percentage,0,'.',','); ?> % DISCOUNT
          </span>
          <?php if($start_promo): ?>
              <span class="dsc_prp_txt">
                <span class="dsc_prp_txt2" style='margin-top:5px;'>
                    PROMO LASTS UNTIL
                </span>
              </span>
          <?php else: ?>  
            <span class="dsc_prp_txt3">PROMO STARTS ON</span>
          <?php endif; ?>
      </div>
      <div class="dsc_tmr">
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
      <?php if($start_promo): ?>
      <div class="discount_perc">
          <p id="percentage"><?php echo number_format( $percentage,0,'.',',');?>%<br/>OFF</p>
      </div>
      <?php endif;?>
  </div>
  
  <script type='text/javascript'>
        $(document).ready(function(){
            var endDate = new Date(<?php echo date('M d,Y H:i:s',strtotime(($start_promo == "1" ? $enddate: $startdate))); ?>);
            $('.cd_timer_container').countdown({
                until : endDate,
                serverSync: serverTime,
                layout: ' <div class="cd_timer_days"><span id="countdown_days">{dnn}</span> <span class="cnt_lgnd">DAYS</span> </div>'+
                    ' <div class="cd_timer_hours"><span id="countdown_hours">{hnn}</span> <span class="cnt_lgnd">HOURS</span> </div>'+
                    ' <div class="cd_timer_minutes"><span id="countdown_minutes">{mnn}</span> <span class="cnt_lgnd">MINUTES</span> </div>' +
                    ' <div class="cd_timer_seconds"><span id="countdown_second">{snn}</span> <span class="cnt_lgnd">SECONDS</span> </div>',
                 onExpiry: reload,
        });
  </script>