
var easyshop = (function (es) {
    es.websocket = (function (ws) {
        ws.client = (function (wsClient) {
            var abConnection;

            /**
             * Connect to websocket server using specified identification
             * 
             * @param string url Url to listen for pushes
             * @param string id Session identifier
             * @param fn onPushAction Receives 2 params `topic` and `data` respectively
             * 
             * @returns {wsClientient} Self
             */
            wsClient.listen = function (url, id, onPushAction) {

                //url = 'wss://' + window.location.hostname + '/ws/?id=' + sid;
                
                /* Warning: ab is a legacy verison of AutobahnJS. We'll settle on it for now */
                abConnection = new ab.Session(
                    url + '?id=' + id,
                    function() {

                        // Once the connection has been established
                        abConnection.subscribe(id, onPushAction);
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
