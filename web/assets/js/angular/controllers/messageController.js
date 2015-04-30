app.controller('MessageController', ['$scope','$stateParams', '$state', 'ModalService', 'MessageFactory',
    function($scope, $stateParams, $state, ModalService, MessageFactory) {

        MessageFactory.setConversation([]);
        MessageFactory.setPartner(null);

        $scope.userId = $stateParams.userId;
        $scope.storeName = $stateParams.storeName;
        $scope.messageCurrentPage = 1;
        $scope.messageBusy = false;
        $scope.selectedMessage = [];
        $scope.conversationListCurrentPage = 2;
        $scope.listBusy = false;

        $scope.messageData = MessageFactory.data;

        /**
         * Update conversation list data 
         * eg: date, showed message
         * @param  {integer} $partnerId
         */
        var updateConversationList = function($partnerId) {
            var $partner = MessageFactory.getPartner($partnerId);
            if ($partner) {
                MessageFactory.setPartner($partner);
                if($partner.unread_message_count > 0){
                    $partner.unread_message_count = 0;
                    MessageFactory.markAsRead($partner.partner_member_id)
                        .then(function(count) {},
                        function(errorMessage) {
                            alert(errorMessage);
                        });
                }
            }
        };

        /**
         * Set conversation list data in message factory
         * @param {Object} $conversationList
         */
        $scope.setConversationList = function($conversationList) {
            MessageFactory.setConversationList($conversationList);
        }

        /**
         * Get conversation in specific user
         * @param  {integer} $userId
         * @param  {integer} $page
         */
        $scope.getConversation = function($userId, $page) {
            if ($scope.messageBusy) {
                return;
            };
            $scope.messageBusy = true;
            MessageFactory.getMessages($userId, $page)
                .then(function(messages) {
                    if (Object.keys(messages).length > 0) {
                        $scope.messageBusy = false;
                        $scope.messageCurrentPage++;
                    }
                }, function(errorMessage) {
                    alert(errorMessage);
                });

            updateConversationList($scope.userId);
        };

        /**
         * Send message to a user
         * @param  {string} $storeName
         * @param  {string} $messageInput
         */
        $scope.sendMessage = function($storeName, $messageInput) { 
            if ($messageInput && $storeName) {
                MessageFactory.sendMessage($storeName, $messageInput)
                    .then(function(messageData) {
                        var $partnerId = messageData[0].recipientId;
                        var $partner = MessageFactory.getPartner($partnerId);
                        if ($partner) {
                            if (MessageFactory.data.partner == null || $partnerId != MessageFactory.data.partner.partner_member_id) {
                                MessageFactory.setPartner($partner);
                                $state.go("readMessage", {userId: $partnerId, storeName: $storeName});
                            }
                            else {
                                MessageFactory.setConversation(messageData.concat(MessageFactory.data.conversation));
                            }
                            MessageFactory.data.partner.last_message = $messageInput;
                            MessageFactory.data.partner.last_date = messageData[0].timeSent;
                        }
                        else{
                            var $newConversation = [{
                                'from_id': messageData[0].recipientId,
                                'id_msg': messageData[0].messageId,
                                'is_sender': true,
                                'last_date': messageData[0].timeSent,
                                'last_message': $messageInput,
                                'partner_image': messageData[0].recipientImage,
                                'partner_member_id': $partnerId,
                                'partner_storename': $storeName,
                                'to_id': $partnerId,
                                'unread_message_count': 0,
                            }];
                            MessageFactory.setConversationList($newConversation.concat(MessageFactory.data.conversationList));
                            MessageFactory.setConversation(messageData.concat(MessageFactory.data.conversation));
                            $state.go("readMessage", {userId: $partnerId, storeName: $storeName});
                        }
                    }, function(errorMessage) {
                        alert(errorMessage);
                    });
            }
        };

        /**
         * Delete conversation of a user
         * @param  {integer} $userId
         */
        $scope.deleteConversation = function($userId) {
            if ($userId && MessageFactory.data.partner) {
                var $indexToRemove = MessageFactory.data.conversationList.indexOf(MessageFactory.data.partner);
                MessageFactory.data.conversationList.splice($indexToRemove, 1);
                MessageFactory.deleteConversation($userId)
                    .then(function(count) {},
                    function(errorMessage) {
                        alert(errorMessage);
                    });
            }
        };

        /**
         * Delete selected messages
         */
        $scope.deleteMessage = function() {
            var $messageIds = [];
            angular.forEach($scope.selectedMessage, function(selectedValue, key) {
                this.splice(this.indexOf(selectedValue), 1);
                $messageIds.push(selectedValue.messageId);
            }, MessageFactory.data.conversation);

            if (MessageFactory.data.conversation.length > 0) {
                MessageFactory.deleteMessage($messageIds)
                    .then(function(count) {},
                    function(errorMessage) {
                        alert(errorMessage);
                    });
            }
            else {
                $scope.deleteConversation($scope.userId);
                $state.go('index');
            }
            $scope.selectedMessage = [];
        };

        /**
         * Compose new message
         */
        $scope.composeMessage = function() {
            var modalOptions = {
                closeButtonText: 'Cancel',
                actionButtonText: 'Send Message',
                headerText: 'Compose New Message',
            };

            var modalDefaults = {
                templateUrl: '/assets/js/angular/views/modals/composeMessageModal.html'
            };

            ModalService.showModal(modalDefaults, modalOptions).then(function ($parameters) {
                $scope.sendMessage($parameters.param1, $parameters.param2);
            });
        }

        /**
         * Get all list of conversation in your inbox
         * @param  {integer} $page
         * @param  {string} $searchString
         */
        $scope.getConversationList = function($page, $searchString) {
            if ($scope.listBusy) {
                return;
            };
            $scope.listBusy = true;
            MessageFactory.getConversationList($page, $searchString)
                .then(function(conversations) {
                    if (Object.keys(conversations).length > 0) {
                        $scope.listBusy = false;
                        $scope.conversationListCurrentPage++;
                    }
                }, function(errorMessage) {
                    alert(errorMessage);
                });
        }
    }
]); 
