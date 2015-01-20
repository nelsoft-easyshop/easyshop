var socket = io.connect( 'https://local.easyshop:8000' );
$( "#messageForm" ).submit( function() {
    var nameVal = 'kurt';
    var msg = $( "#message" ).val();
    socket.emit( 'message', { name: nameVal, message: msg } );
    $( "#message" ).val("")
    return false;
});
socket.on( 'message', function( data ) {
    var newMsgContent = '<div><strong>' + data.name + '</strong> : ' + data.message + '</div>';
    $( "#chat" ).append( newMsgContent );
});