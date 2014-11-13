(function($) {
    $(document).ready(function(){
        var endDate = new Date($("#endDate").val());
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
})(jQuery);