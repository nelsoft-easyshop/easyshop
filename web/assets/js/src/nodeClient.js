(function ($) {
    $(document).ready(function () {
        var $chatClient = $('#chatClientInfo');
        var $isLoggedIn = $('[name="is-logged-in"]');
        if ($isLoggedIn.val() === "true") {
            var socket = io.connect( 'https://' + $chatClient.data('host') + ':' + $chatClient.data('port'));
            var setAccountOnline = function(storeName) {
                socket.emit('set account online', storeName);
            };
            setAccountOnline($chatClient.data('store-name'));

            var setAccountOffline = function(storeName) {
                socket.emit('set account offline', storeName, function(data) {
                    window.location.replace('/login/logout');
                });
            }
        }

        $('.logoutClient').on('click', function(e) {
            e.preventDefault();
            setAccountOffline($chatClient.data('store-name'));
        });
    });
})(jQuery);
