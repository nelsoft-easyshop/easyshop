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
            var clients = container[data.recipient];
            for(var i = 0; i < clients.length;i++){
                   clients[i].emit('send message', {recipient: data.recipient, message: data.message });
            }
        }
    });

    client.on('set account online', function(storeName) {
        //TODO : add feature to see if user is online
        
        if (!(storeName in container)) {
            console.log(storeName + " is now Online" );
            container[storeName] = [];
        }
        client.storeName = storeName;
        container[storeName].push(client);
    });

    client.on('set account offline', function(storeName, callback) {
        if (storeName in container) {
            callback(true);
            delete container[storeName];
            console.log(storeName + " is now Offline" );
        }
        else {
            callback(false);
        }
    });
    
    client.on('message opened', function(storename) {
        if (storename in container) {
            var currentClientId = client.id;
            var clients = container[storename];
            for(var i = 0; i < clients.length;i++){
                if(currentClientId !== clients[i].id){
                    clients[i].emit('message opened')
                }
            }
        }
    });

});
