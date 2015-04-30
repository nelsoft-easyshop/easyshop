app.factory('MessageFactory', function($http, $q) {

    var MessageFactory = {
        data: {
            currentSelectedPartner: null,
            conversation: [],
            conversationList: [],
        }
    };
    var $csrftoken = $http.defaults.headers.common['X-CSRF-TOKEN'];

    /**
     * check if partner is already in your conversation
     * if exist return partner object
     * @param  {integer} $partnerId
     * @return {object}
     */
    MessageFactory.getPartner = function($partnerId) { 
        var $partner = null;
        angular.forEach(MessageFactory.data.conversationList, function(value, key) {
            if (value.partner_member_id == $partnerId) {
                $partner = value;
            }
        });

        return $partner;
    }

    /**
     * Set partner currentSelectedPartner object
     * @param {object} $partnerObject
     */
    MessageFactory.setPartner = function($partnerObject) {
        MessageFactory.data.currentSelectedPartner = $partnerObject;
    }

    /**
     * Set conversationList object
     * @param {object} $conversationList
     */
    MessageFactory.setConversationList = function($conversationList) {
        MessageFactory.data.conversationList = $conversationList;
    }

    /**
     * Set conversation object
     * @param {object} $conversationObject [description]
     */
    MessageFactory.setConversation = function($conversationObject) {
        MessageFactory.data.conversation = $conversationObject;
    }

    /**
     * request messages in server
     * @param  {integer} $userId
     * @param  {integer} $page
     * @return {object}
     */
    MessageFactory.getMessages = function($userId, $page) {
        var $conversation = [];
        var $deferred = $q.defer();

        $http.get('/MessageController/getConversationMessages?partnerId='+$userId+'&page='+$page)
            .success(function(data, status, headers, config) {
                if (data.length > 0) {
                    angular.forEach(data, function(value, key) {
                        this.push(constructMessage(value)); 
                    }, $conversation);
                    MessageFactory.setConversation(MessageFactory.data.conversation.concat($conversation));
                }
                $deferred.resolve($conversation);
            })
            .error(function() {
                $deferred.reject("Error occured while getting messages");
            });

        return $deferred.promise;
    };

    /**
     * Request to server to send message in a specific user
     * @param  {string} $storeName
     * @param  {string} $messageInput
     * @return {object}
     */
    MessageFactory.sendMessage = function($storeName, $messageInput) {
        var $deferred = $q.defer();
        var $sendData = {
            recipient: $storeName,
            message: $messageInput,
            csrfname: $csrftoken
        };

        $http({
            method: 'POST',
            url: "/MessageController/send",
            data: $.param($sendData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .success(function(data, status) {
            if (data.success) {
                data.messageDetails.isSender = true;
                var $message = [constructMessage(data.messageDetails)];

                $deferred.resolve($message);
            }
            else {
                $deferred.reject(data.errorMessage);
            }
        })
        .error(function() {
            $deferred.reject("Error occured while getting messages");
        });

        return $deferred.promise;
    };

    /**
     * Mark as read/opened selected message
     * @param  {integer} $userId
     * @return {integer}
     */
    MessageFactory.markAsRead = function($userId) {
        var $deferred = $q.defer();
        var $sendData = {
            partnerId: $userId,
            csrfname: $csrftoken
        };
        $http({
            method: 'POST',
            url: "/MessageController/markMessageAsRead",
            data: $.param($sendData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .success(function(data, status) {
            $deferred.resolve(data.numberOfUpdatedMessages);
        })
        .error(function() {
            $deferred.reject("Error occured while getting messages");
        });

        return $deferred.promise;
    };

    /**
     * Request to server to delete specific conversation to user
     * @param  {integer} $userId
     * @return {integer} 
     */
    MessageFactory.deleteConversation = function($userId) {
        var $deferred = $q.defer();
        var $sendData = {
            partnerId: $userId,
            csrfname: $csrftoken
        };
        $http({
            method: 'POST',
            url: "/MessageController/deleteConversation",
            data: $.param($sendData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .success(function(data, status) {
            $deferred.resolve(data.numberOfDeletedMessages);
        })
        .error(function() {
            $deferred.reject("Error occured while getting messages");
        });

        return $deferred.promise;
    };

    /**
     * Delete selected messages by giving message id
     * @param  {array} $messageIds
     * @return {integer}
     */
    MessageFactory.deleteMessage = function($messageIds) {
        var $deferred = $q.defer();
        var $sendData = {
            message_ids: angular.toJson($messageIds),
            csrfname: $csrftoken
        };
        $http({
            method: 'POST',
            url: "/MessageController/deleteMessage",
            data: $.param($sendData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .success(function(data, status) {
            $deferred.resolve(data.numberOfDeletedMessages);
        })
        .error(function() {
            $deferred.reject("Error occured while getting messages");
        });

        return $deferred.promise;
    };

    /**
     * Request to server to get conversation list in your inbox
     * @param  {integer} $page
     * @param  {string}  $searchString
     * @return {object}
     */
    MessageFactory.getConversationList = function($page, $searchString) {
        var $conversationList = [];
        var $deferred = $q.defer();

        $http.get('/MessageController/getConversationHeaders?searchString='+$searchString+'&page='+$page)
            .success(function(data, status, headers, config) {
                if (data.conversationHeaders.length > 0) { 
                    angular.forEach(data.conversationHeaders, function(value, key) {
                        if (MessageFactory.getPartner(value.partner_member_id) == null) {
                            this.push(value); 
                        }
                    }, $conversationList);
                }
                MessageFactory.setConversationList(MessageFactory.data.conversationList.concat($conversationList));
                $deferred.resolve(data.conversationHeaders);
            })
            .error(function() {
                $deferred.reject("Error occured while getting messages");
            });

        return $deferred.promise;
    };

    /**
     * Construct message object to display in view and controller
     * @param  {object} $object
     * @return {object}
     */
    var constructMessage = function ($object) {
        return {
            'messageId': $object.id_msg,
            'message': $object.message,
            'senderImage': $object.senderImage,
            'isSender': $object.isSender,
            'timeSent': $object.time_sent,
            'senderStorename': $object.senderStorename,
            'recipientId': $object.recipientMemberId,
            'recipientImage': $object.recipientImage
        }
    } 

    return MessageFactory;
});
