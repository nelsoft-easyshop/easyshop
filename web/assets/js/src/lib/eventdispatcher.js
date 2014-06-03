
var easyshop = (function (es) {
    es.eventDispatcher = (function (eventDispatcher) {
        var eventHandlers = {};
        
        eventDispatcher.register = function (key, handler) {
            eventHandlers[key] = handler;
        };
        
        eventDispatcher.dispatch = function (data) {
            for (var key in data) {
                eventHandlers[key](data[key]);
            }
        };
        
        return eventDispatcher;
    })(es.eventDispatcher || {});
    return es;
})(easyshop || {});