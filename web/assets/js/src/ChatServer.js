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
var idContainer = {};
console.log('HTTPS Server listening on %s:%s', HOST, PORT);
io = require('socket.io').listen(server);

io.sockets.on( 'connection', function(client) {

    client.on('send message', function(data, callback) {
        if (data.recipientId in idContainer) {
            idContainer[data.recipientId].emit('send message', {recipientId: data.recipientId, message: data.message });
            callback(true);
        }
        else {
            callback(false);
        }
    });

    client.on('set account online', function(memberId) {
        //TODO : add feature to see if user is online
        if (!(memberId in idContainer)) {
            client.memberId = memberId;
            idContainer[client.memberId] = client;
            console.log( "ID : " + memberId + " is now Online" );
        }
        else {
            console.log( "ID : " + memberId + " is already Online somewhere" );
        }
    });

});