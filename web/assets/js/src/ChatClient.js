var socket = io.connect( 'https://local.easyshop:8000' );
var $user = $('#userInfo');

$( "#messageForm" ).submit( function() {
    var nameVal = 'kurt';
    var msg = $( "#message" ).val();
    socket.emit( 'message', { name: nameVal, message: msg } );
    socket.emit('setAccountOnline', '12');
    $( "#message" ).val("")
    return false;
});

socket.on( 'message', function( data ) {
    var newMsgContent = '<div><strong>' + data.name + '</strong> : ' + data.message + '</div>';
    $( "#chat" ).append( newMsgContent );
});

socket.on( 'online', function( data ) {

});
