(function ($) {
    $(document).ready(function () {
        var $chatClient = $('#chatClientInfo');
        var $isLoggedIn = $('[name="is-logged-in"]');
        if ($isLoggedIn.val() === "true") {
            var socket = io.connect( 'https://' + $chatClient.data('host') + ':' + $chatClient.data('port'));
            var setAccountOnline = function(memberId) {
                socket.emit('set account online', memberId);
            };
            setAccountOnline($chatClient.data('store-name'));

        }
    });
})(jQuery);
