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
            onExpiry: reload
        });
    });

    function serverTime() {
        var time = null;
        $.ajax({
            url: '/home/getServerTime',
            dataType: 'text',
            success: function(text) {
                time = new Date(text);
            },
            error: function(http, message, exc) {
                time = new Date();
            }
        });
        return time;
    }

    function reload(){
        window.location.reload();
    }
})(jQuery);
