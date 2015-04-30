app.controller('MessageController', ['$scope','$stateParams', '$state', 'ModalService', 'MessageFactory',
    function($scope, $stateParams, $state, ModalService, MessageFactory) {

        MessageFactory.setConversation([]);
        MessageFactory.setPartner(null);

        $scope.userId = $stateParams.userId;
        $scope.storeName = $stateParams.storeName;
        $scope.messageCurrentPage = 1;
        $scope.messageBusy = false;
        $scope.selectedMessage = [];
        $scope.conversation = MessageFactory.conversation;
        $scope.conversationList = MessageFactory.conversationList;
        $scope.conversationListCurrentPage = 2;
        $scope.listBusy = false;

        var updatePartnerHeader = function($partnerId) {
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
            $scope.conversationList = MessageFactory.conversationList;
        };

        $scope.setConversationList = function($conversationList) {
            MessageFactory.setConversationList($conversationList);
            $scope.conversationList = MessageFactory.conversationList;
        }

        $scope.setConversation = function($conversation) {
            MessageFactory.setConversation($conversation);
            $scope.conversation = MessageFactory.conversation;
        }

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

            updatePartnerHeader($scope.userId);
            $scope.conversation = MessageFactory.conversation;
        };

        $scope.sendMessage = function($storeName, $messageInput) { 
            if ($messageInput && $storeName) {
                MessageFactory.sendMessage($storeName, $messageInput)
                    .then(function(messageData) {
                        var $partnerId = messageData[0].recipientId;
                        var $partner = MessageFactory.getPartner($partnerId);
                        if ($partner) {
                            if (MessageFactory.partner == null || $partnerId != MessageFactory.partner.partner_member_id) {
                                MessageFactory.setPartner($partner);
                                $state.go("readMessage", {userId: $partnerId, storeName: $storeName});
                            }
                            else {
                                $scope.setConversation(messageData.concat(MessageFactory.conversation));
                            }
                            MessageFactory.partner.last_message = $messageInput;
                            MessageFactory.partner.last_date = messageData[0].timeSent;
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
                            $scope.setConversationList($newConversation.concat(MessageFactory.conversationList));
                            $scope.setConversation(messageData.concat(MessageFactory.conversation));
                            $state.go("readMessage", {userId: $partnerId, storeName: $storeName});
                        }
                    }, function(errorMessage) {
                        alert(errorMessage);
                    });
            }
        };

        $scope.deleteConversation = function($userId) {
            if ($userId && MessageFactory.partner) {
                var $indexToRemove = MessageFactory.conversationList.indexOf(MessageFactory.partner);
                MessageFactory.conversationList.splice($indexToRemove, 1);
                MessageFactory.deleteConversation($userId)
                    .then(function(count) {},
                    function(errorMessage) {
                        alert(errorMessage);
                    });
                $scope.conversationList = MessageFactory.conversationList;
            }
        };

        $scope.deleteMessage = function() {
            var $messageIds = [];
            angular.forEach($scope.selectedMessage, function(selectedValue, key) {
                this.splice(this.indexOf(selectedValue), 1);
                $messageIds.push(selectedValue.messageId);
            }, MessageFactory.conversation);

            if (MessageFactory.conversation.length > 0) {
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
            $scope.conversation = MessageFactory.conversation;
        };

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
            $scope.conversationList = MessageFactory.conversationList;
        }
    }
]); 
