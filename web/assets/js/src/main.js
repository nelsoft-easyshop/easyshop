

/**
 * Please observe cleanliness
 * 
 * @param {type} $
 * @returns {undefined}
 */
(function ($) {
    $(document).ready(function () {
        var unreadMessagesLabel = $("#unread-messages-count");
        
        /* Register events */
        easyshop.eventDispatcher.register('messageCount', function (messageCount) {
            unreadMessagesLabel.html(messageCount);
        });
        
        easyshop.eventDispatcher.register('unreadMessages', function (unreadMessages) {
            // todo: populate messages
        });
        
        /* Begin listening for events */
        easyshop.websocket.client.listen($('#user-session').val(), function (topic, data) {
            easyshop.eventDispatcher.dispatch(data);
        });
    });
})(window.jQuery);
