/**
 * Web socket client
 */
var esClient = (function () {
    var client = {};
    var webSocket;
    
    /**
     * Connect to websocket server using specified identification
     * 
     * @param string id
     * 
     * @returns {_L4.client} Self
     */
    client.connect = function (id) {
        webSocket = new WebSocket('wss://' + window.location.hostname + '/ws/?id=' + id);
        return client;
    };
    
    /**
     * Set the push event handler function. This function will
     * recieve a MessageEvent object.
     * 
     * @param fn fn Event handler
     * 
     * @returns {_L4.client} Self
     */
    client.setPushEventListener = function (fn) {
        webSocket.onmessage = fn;
        return client;
    };
    
    return client;
})();
