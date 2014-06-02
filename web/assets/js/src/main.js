


(function ($) {
    $(document).ready(function () {
        var unreadMessagesLabel = $("#unread-messages-count");
        esClient.listen($('#user-session').val(), function (topic, data) {
            var purpose = data.purpose;

            if ("unread_message_count" === purpose) {
                unreadMessagesLabel.html(data.unreadMessageCount);
            }

        });
    });
})(window.jQuery);
