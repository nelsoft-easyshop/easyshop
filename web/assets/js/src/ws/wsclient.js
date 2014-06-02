/**
 * Web socket client
 */
var esClient = (function () {
    
    // Load Autobahn.js, if not already loaded
    window.ab || document.write('<script src="https://autobahn.s3.amazonaws.com/js/autobahn.min.js">\x3C/script>');
    
    var client = {};
    var abConnection;
    
    /**
     * Connect to websocket server using specified identification
     * 
     * @param string sid Session id
     * @param fn onPushAction Receives 2 params topic and data respectively
     * 
     * @returns {_L4.client} Self
     */
    client.listen = function (sid, onPushAction) {

        /*
         * Warning: ab is a legacy verison of AutobahnJS. We'll settle on it for now.
         */
        abConnection = new ab.Session(
            'wss://' + window.location.hostname + '/ws/?id=' + sid,
            function() {
                
                // Once the connection has been established
                abConnection.subscribe(sid, onPushAction);
            },
            function() {
                console.warn('WebSocket connection closed');
            },
            {
                'skipSubprotocolCheck': true
            }
        );

        return client;
    };
    
    return client;
})();