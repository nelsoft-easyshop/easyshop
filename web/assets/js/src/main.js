


(function ($) {
    $(document).ready(function () {
        var unreadMessagesLabel = $("#unread-messages-count");
        
        /* Register events */
        easyshop.eventDispatcher.register('messageCount', function (data) {
            unreadMessagesLabel.html(data.unreadMessageCount);
        });
        
        /* Begin listening for events */
        easyshop.websocket.client.listen($('#user-session').val(), function (topic, data) {
            easyshop.eventDispatcher.dispatch(data);
        });
    });
})(window.jQuery);
