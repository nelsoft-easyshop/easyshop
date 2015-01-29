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
    
    function updateMessageCountIcons()
    {
        $.ajax({
            type:"get",
            dataType : "json",
            url : "/MessageController/getNumberOfUnreadMessages",
            success : function(count)
            {   
                var numberOfUnreadMessages = $.parseJSON(count);
                var title = '';
                
                if($('#original-title').length === 0){
                    var originalTitleTag = document.createElement('meta');
                    originalTitleTag.id = "original-title";
                    originalTitleTag.name = "original-title";
                    title = $(document).prop('title');
                    originalTitleTag.content = title;
                    document.getElementsByTagName('head')[0].appendChild(originalTitleTag);
                }
                else{
                    title = $('#original-title').attr('content')
                }
           
                $(document).prop('title', '(' + numberOfUnreadMessages + ') ' + title);
                $('#unread-messages-count').html(numberOfUnreadMessages);
                if(parseInt(numberOfUnreadMessages) > 0){
                    $('#unread-messages-count').css('display','inline-block');
                }
                else{
                    $('#unread-messages-count').css('display','none'); 
                }
            }
        }); 
    }
    
})(jQuery);
