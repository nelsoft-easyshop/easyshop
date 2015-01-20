var fs = require('fs');
var express = require('express');
var https = require('https');
var key = fs.readFileSync('key/easyshop.key');
var cert = fs.readFileSync('key/easyshop.crt');
var https_options = {
    key: key,
    cert: cert
};
var PORT = 8000;
var HOST = 'local.easyshop';
app = express();

server = https.createServer(https_options, app).listen(PORT, HOST);
console.log('HTTPS Server listening on %s:%s', HOST, PORT);
io = require('socket.io').listen(server);

io.sockets.on( 'connection', function( client ) {
    console.log( "New client !" );
    client.on( 'message', function( data ) {
        console.log( 'Message received ' + data.name + ":" + data.message );
        io.sockets.emit( 'message', { name: data.name, message: data.message } );
    });
});