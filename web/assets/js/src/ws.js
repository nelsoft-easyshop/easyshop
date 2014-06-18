

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
        
        /* Begin listening for events */
        var url = 'ws://ws.' + window.location.hostname;
        easyshop.websocket.client.listen(url, $('#user-session').val(), function (topic, data) {
            easyshop.eventDispatcher.dispatch(data);
        });
    });
})(window.jQuery);
