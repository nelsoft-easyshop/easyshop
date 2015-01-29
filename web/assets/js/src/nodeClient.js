(function ($) {
    
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
            }
       
            /* Register events */
            socket.on('send message', function( data ) {
                $.ajax({
                    type:"get",
                    dataType : "json",
                    url : "/MessageController/getNumberOfUnreadMessages",
                    success : function(count)
                    {   
                       var numberOfUnreadMessages = $.parseJSON(count);
                       $(document).prop('title', '(' + numberOfUnreadMessages + ') ' + $(document).prop('title'));
                       $('#unread-messages-count').html(numberOfUnreadMessages);
                       $('#unread-messages-count').css('display','inline');
                    }
                }); 
            });

            setAccountOnline($chatClient.data('store-name'));

            $('.logoutClient').on('click', function(e) {
                e.preventDefault();
                setAccountOffline($chatClient.data('store-name'));
                window.location.replace('/login/logout');
            });
        }

    });
})(jQuery);
