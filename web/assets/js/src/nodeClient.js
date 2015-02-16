(function ($) {
    /*
    $(document).ready(function () {
        
        var $chatClient = $('#chatClientInfo');
        var $isLoggedIn = $('[name="is-logged-in"]');

        if ($.parseJSON($isLoggedIn.val())) {

            var socket = io.connect( 'https://' + $chatClient.data('host') + ':' + $chatClient.data('port'));
     
            var setAccountOnline = function(storeName) {
                socket.emit('set account online', storeName);
            };

            var setAccountOffline = function(storeName) {
                socket.emit('set account offline', storeName, function(data) {});
            };

            socket.on('send message', function( data ) {
                updateMessageCountIcons();
            });

            socket.on('message opened', function( data ) {
                updateMessageCountIcons();
            });

            setAccountOnline($chatClient.data('store-name'));

            $('.logoutClient').on('click', function(e) {
                e.preventDefault();
                setAccountOffline($chatClient.data('store-name'));
                window.location.replace('/login/logout');
            });
        }

    });
    */
  
})(jQuery);
