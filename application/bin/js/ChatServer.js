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

    client.on('send message', function(data) {
        if (data.recipient in container) {
            container[data.recipient].emit('send message', {recipient: data.recipient, message: data.message });
        }
    });

    client.on('set account online', function(storeName) {
        //TODO : add feature to see if user is online
        if (!(storeName in container)) {
            client.storeName = storeName;
            container[client.storeName] = client;
            console.log(storeName + " is now Online" );
        }
    });

    client.on('disconnect', function(data){
        if (!client.storeName) {
            return;
        }
        delete container[client.storeName];
    });

});
