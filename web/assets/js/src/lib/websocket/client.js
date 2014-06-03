
var easyshop = (function (es) {
    es.websocket = (function (ws) {
        ws.client = (function (wsClient) {
            var abConnection;

            /**
             * Connect to websocket server using specified identification
             * 
             * @param string sid Session id
             * @param fn onPushAction Receives 2 params `topic` and `data` respectively
             * 
             * @returns {wsClientient} Self
             */
            wsClient.listen = function (sid, onPushAction) {

                /* Warning: ab is a legacy verison of AutobahnJS. We'll settle on it for now */
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

                return wsClient;
            };

            return wsClient;
        })(ws.client || {});
        
        return ws;
    })(es.websocket || {});
    
    return es;
})(easyshop || {});
