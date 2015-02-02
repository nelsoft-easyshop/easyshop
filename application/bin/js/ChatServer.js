var express = require('express');
var https = require('https');
var app = express();
var socketioJwt = require('socketio-jwt');

require('./config').configureExpress(app);

var PORT = app.get('PORT');
var HOST = app.get('HOST');
var JWT_SECRET = app.get('JWT_SECRET');
var https_options = {
    key: app.get('KEY'),
    cert: app.get('CERT')
};

var server = https.createServer(https_options, app).listen(PORT, HOST);
console.log('HTTPS Server listening on %s:%s', HOST, PORT);
io = require('socket.io').listen(server);

io.set('authorization',socketioJwt.authorize({
    secret: JWT_SECRET,
    handshake: true
}));

io.sockets.on( 'connection', function(socket) {
    
    socket.on('set account online', function() {
        var storename = socket.client.request.decoded_token.storename; 
        socket.join(storename);
    });

    /**
     * This function need not be called as socketio's rooms always tear down 
     * any disconnected socket by itself
     */
    socket.on('set account offline', function() {
        var storename = socket.client.request.decoded_token.storename; 
        socket.leave(storename);
    });

    socket.on('send message', function(data) {
        /**
         *  TODO: VALIDATE MESSAGE BEFORE EMITTING 
         */
        io.to(data.recipient).emit('send message', {
            recipient: data.recipient,
            message: data.message
        });
    });
    
    socket.on('message opened', function() {
        var storename = socket.client.request.decoded_token.storename; 
        io.to(storename).emit('message opened');
    });

});
