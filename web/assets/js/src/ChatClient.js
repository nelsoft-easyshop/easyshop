(function($){
    var socket = io.connect( 'https://local.easyshop:8000' );
    var $userInfo = $('#userInfo');

    $(document).ready(function() {
        $( "#messageForm" ).submit( function() {
            var nameVal = 'kurt';
            var recipientId = "1";
            if (recipientId == $userInfo.data('member-id')) {
                recipientId = "4";
            }
            var msg = $( "#message" ).val();
            socket.emit('send message', {recipientId: recipientId, name: nameVal, message: msg });
            $( "#message" ).val("")
            return false;
        });

        socket.on('send message', function( data ) {
            //Put my onReload function here
            var newMsgContent = '<div><strong>' + data.recipientId + '</strong> : ' + data.message + '</div>';
            $( "#chat" ).append( newMsgContent );
        });

        setAccountOnline($userInfo.data('member-id'));

    });

    var setAccountOnline = function(memberId) {
        socket.emit('set account online', memberId);
    };

})(jQuery)