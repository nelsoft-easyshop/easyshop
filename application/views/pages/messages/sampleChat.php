<div id="chat" style="height:200px;width: 400px;border: 1px #000000 solid">

</div>
<form id="messageForm">
    <input type="text" size="35" id="message">
    <input type="submit" value="send">
</form>
<input type="hidden" id="userInfo" data-member-id="<?=$memberId?>" >

<script src="/assets/js/src/node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script>
<!--<script src="/assets/js/src/ChatClient.js"></script>-->
<script>
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
                socket.emit('send message', {recipientId: recipientId, name: nameVal, message: msg }, function(error){
                    if (error) {
                        alert('Message sent!, user is online');
                    }
                    else {
                        alert('User is not online!');
                    }
                });
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
</script>
