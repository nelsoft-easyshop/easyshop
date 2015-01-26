

/**
 * Please observe cleanliness
 * 
 * @param {type} $
 * @returns {undefined}
 */
//(function ($) {
//    $(document).ready(function () {
//        var unreadMessagesLabel = $("#unread-messages-count");
//
//        /* Register events */
//        easyshop.eventDispatcher.register('messageCount', function (messageCount) {
//            unreadMessagesLabel.html(messageCount);
//        });
//
//        /* Begin listening for events */
//        var host = window.location.hostname;
//        var domainNamePos = host.indexOf('easyshop');
//        var protocol = window.location.protocol === 'https:' ? 'wss' : 'ws';
//        var url = protocol + '://ws.' + (0 === domainNamePos ? host : host.substr(domainNamePos));
//
//        easyshop.websocket.client.listen(url, $('#user-session').val(), function (topic, data) {
//            easyshop.eventDispatcher.dispatch(data);
//        });
//    });
//})(window.jQuery);
