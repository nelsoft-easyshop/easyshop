(function($) {
    $(document).ready(function(){
        
        var remainingTimeMillisecond = $('#remainingTime').val() * 1000;
        var currentTime = new Date();
        var endDate = new Date(currentTime.getTime() + remainingTimeMillisecond);
        
        $('#table-countdown').countdown(endDate).on('update.countdown', function(event) {
            var $this = $(this).html(event.strftime(''
                + '<td class="td-time-num"><span class="span-time-num">%D</span><span class="span-time-label">DAYS</td></td>'
                + '<td class="td-time-num"><span class="span-time-num">%H</span><span class="span-time-label">HOURS</td></td>'
                + '<td class="td-time-num"><span class="span-time-num">%M</span><span class="span-time-label">MINUTES</td></td>'
                + '<td class="td-time-num"><span class="span-time-num">%S</span><span class="span-time-label">SECONDS</td></td>'));
        }).on('finish.countdown', function(event) {
            window.location.reload();
        });
        
        var currenTime = new Date();
        var nearestHour = new Date();
        nearestHour.setHours(nearestHour.getHours() + 1);
        nearestHour.setMinutes(0);
        nearestHour.setSeconds(0);
        var msToNextHour = nearestHour.getTime() - currenTime.getTime();
        
        setTimeout(function(){ 
           window.location.reload();
        }, msToNextHour);
        
    });

})(jQuery);
