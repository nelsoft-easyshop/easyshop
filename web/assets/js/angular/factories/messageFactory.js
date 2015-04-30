app.factory('MessageFactory', function($http, $q) {

    var MessageFactory = {
        partner: null,
        conversation: [],
        conversationList: [],
    };
    var $csrftoken = $("meta[name='csrf-token']").attr('content');

    MessageFactory.getPartner = function($parterId) { 
        var $partner = null;
        angular.forEach(MessageFactory.conversationList, function(value, key) {
            if (value.partner_member_id == $parterId) {
                $partner = value;
            }
        });

        return $partner;
    }

    MessageFactory.setPartner = function($partnerObject) {
        MessageFactory.partner = $partnerObject;
    }

    MessageFactory.setConversationList = function($conversationList) {
        MessageFactory.conversationList = $conversationList;
    }

    MessageFactory.setConversation = function($conversationObject) {
        MessageFactory.conversation = $conversationObject;
    }

    MessageFactory.getMessages = function($userId, $page) {
        var $conversation = [];
        var $deferred = $q.defer();

        $http.get('/MessageController/getConversationMessages?partnerId='+$userId+'&page='+$page)
            .success(function(data, status, headers, config) {
                if (data.length > 0) {
                    angular.forEach(data, function(value, key) {
                        this.push(constructMessage(value)); 
                    }, $conversation);
                    MessageFactory.setConversation(MessageFactory.conversation.concat($conversation));
                }
                $deferred.resolve($conversation);
            })
            .error(function() {
                $deferred.reject("Error occured while getting messages");
            });

        return $deferred.promise;
    };

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
                MessageFactory.setConversationList(MessageFactory.conversationList.concat($conversationList));
                $deferred.resolve(data.conversationHeaders);
            })
            .error(function() {
                $deferred.reject("Error occured while getting messages");
            });

        return $deferred.promise;
    };

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
