(function ($) {
    
    $(document).ready(function () {
        
        var $chatConfig = $('#chatServerConfig');
        var $isLoggedIn = $('[name="is-logged-in"]');

        if ($.parseJSON($isLoggedIn.val())) {

            var socket = io.connect( 'https://' + $chatConfig.data('host') + ':' + $chatConfig.data('port'), {query: 'token=' + $chatConfig.data('jwttoken') });
        
            var setAccountOnline = function() {
                socket.emit('set account online');
            };

            var setAccountOffline = function() {
                socket.emit('set account offline');
            };
       
            /* Register events */
            socket.on('send message', function( data ) {
                updateMessageCountIcons();
            });

            /* Register events */
            socket.on('message opened', function( data ) {
                updateMessageCountIcons();
            });
            
            setAccountOnline();
                        
        }

    });
  
})(jQuery);
