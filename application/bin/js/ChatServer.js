var express = require('express');
var https = require('https');
var app = express();

require('./config').configureExpress(app);

var PORT = app.get('PORT');
var HOST = app.get('HOST');
var https_options = {
    key: app.get('KEY'),
    cert: app.get('CERT')
};

var server = https.createServer(https_options, app).listen(PORT, HOST);
console.log('HTTPS Server listening on %s:%s', HOST, PORT);
io = require('socket.io').listen(server);

var container = {};
io.sockets.on( 'connection', function(client) {
    
    client.on('set account online', function(storename) {
        client.join(storename);
    });

    client.on('set account offline', function(storename) {
        client.leave(storename);
    });

    client.on('send message', function(data) {
        io.to(data.recipient).emit('send message', {
            recipient: data.recipient,
            message: data.message
        });
    });
    
    client.on('message opened', function(storename) {
        io.to(storename).emit('message opened');
    });
    

});
