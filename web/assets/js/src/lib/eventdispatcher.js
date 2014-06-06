
var easyshop = (function (es) {
    es.eventDispatcher = (function (eventDispatcher) {
        var eventHandlers = {};
        
        eventDispatcher.register = function (key, handler) {
            eventHandlers[key] = handler;
        };
        
        eventDispatcher.dispatch = function (data) {
            for (var eventKey in eventHandlers) {
                if (eventKey in data) {
                    eventHandlers[eventKey](data[eventKey]);
                }
            }
        };
        
        return eventDispatcher;
    })(es.eventDispatcher || {});
    return es;
})(easyshop || {});