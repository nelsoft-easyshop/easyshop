var express = require('express');
var https = require('https');
var redis = require("redis");
var app = express();
var socketioJwt = require('socketio-jwt');

require('./config').configureExpress(app);

var NODE_PORT = app.get('NODE_PORT');
var NODE_HOST = app.get('NODE_HOST');
var JWT_SECRET = app.get('JWT_SECRET');
var CHAT_CHANNEL_NAME = app.get('REDIS_CHANNEL_NAME');
var REDIS_PORT = app.get('REDIS_PORT');
var REDIS_HOST = app.get('REDIS_HOST');
var https_options = {
    key: app.get('KEY'),
    cert: app.get('CERT')
};

var server = https.createServer(https_options, app).listen(NODE_PORT, NODE_HOST);

console.log('HTTPS Server listening on %s:%s', NODE_HOST, NODE_PORT);
io = require('socket.io').listen(server);

io.set('authorization',socketioJwt.authorize({
    secret: JWT_SECRET,
    handshake: true
}));

io.sockets.on( 'connection', function(socket) {
 
    var clientSubscribe = redis.createClient(REDIS_PORT, REDIS_HOST, {});
    clientSubscribe.subscribe(CHAT_CHANNEL_NAME);
    
    socket.on('set account online', function() {
       var storename = socket.client.request.decoded_token.storename; 
       socket.join(storename);
    });
    
    clientSubscribe.on("message", function(channel, jsonString){
        var data = JSON.parse(jsonString);
        if(data.event === 'message-opened' && data.reader){
            io.to(data.reader).emit('message opened');
        }
        else if(data.event === 'message-sent' && data.recipient && data.message){
            io.to(data.recipient).emit('send message', {
                recipient: data.recipient,
                message: data.message
            });
        }
    });

    /**
     * This function need not be called as socketio's rooms always tear down 
     * any disconnected socket by itself
     */
    socket.on('set account offline', function() {
        var storename = socket.client.request.decoded_token.storename; 
        socket.leave(storename);
    });


});
