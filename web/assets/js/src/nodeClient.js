(function ($) {

    $(document).ready(function () {
 
            var $chatConfig = $('#chatServerConfig');
            var $isLoggedIn = $('[name="is-logged-in"]');
            var isChatAllowed = $.parseJSON($('#listOfFeatureWithRestriction').data('real-time-chat'));

            if ($.parseJSON($isLoggedIn.val())) {

                if(config.isSocketioEnabled && isChatAllowed){

                    var socket = io.connect( 'https://' + $chatConfig.data('host') + ':' + $chatConfig.data('port'), {query: 'token=' + $chatConfig.data('jwttoken') });
                
                    var setAccountOnline = function() {
                        socket.emit('set account online');
                    };

                    var setAccountOffline = function() {
                        socket.emit('set account offline');
                    };

                    socket.on('send message', function( data ) {
                        updateMessageCountIcons();
                    });

                    socket.on('message opened', function( data ) {
                        updateMessageCountIcons();
                    });

                    setAccountOnline();
            
                }
            }

    });
  
  
})(jQuery);
