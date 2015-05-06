app.controller('MessageController', ['$scope', '$stateParams', '$state', 'ModalService', 'MessageFactory', 'socketFactory', 
    function($scope, $stateParams, $state, ModalService, MessageFactory, socketFactory) {

        MessageFactory.setConversation([]);
        MessageFactory.setPartner(null);

        $scope.userId = $stateParams.userId;
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
                                        }
                                  );
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
                        var $recipientId = messageData[0].recipientId;
                        var $recipient = MessageFactory.getPartner($recipientId);
                        /**
                         * If recipient already exists in the conversation header list
                         */
                        if ($recipient) {
                            if (MessageFactory.data.currentSelectedPartner == null || $recipientId != MessageFactory.data.currentSelectedPartner.partner_member_id) {
                                MessageFactory.setPartner($recipient);
                                $state.go("readMessage", {userId: $recipientId});
                            }
                            else {
                                MessageFactory.setConversation(messageData.concat(MessageFactory.data.conversation));
                            }
                            MessageFactory.data.currentSelectedPartner.last_message = $messageInput;
                            MessageFactory.data.currentSelectedPartner.last_date = messageData[0].timeSent;
                        }
                        else{
                            var $newConversation = [{
                                'from_id': messageData[0].recipientId,
                                'id_msg': messageData[0].messageId,
                                'is_sender': true,
                                'last_date': messageData[0].timeSent,
                                'last_message': $messageInput,
                                'partner_image': messageData[0].recipientImage,
                                'partner_member_id': $recipientId,
                                'partner_storename': $storeName,
                                'to_id': $recipientId,
                                'unread_message_count': 0,
                            }];
                            MessageFactory.setConversationList($newConversation.concat(MessageFactory.data.conversationList));
                            $state.go("readMessage", {userId: $recipientId});
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
            if ($userId && MessageFactory.data.currentSelectedPartner) {
                var $indexToRemove = MessageFactory.data.conversationList.indexOf(MessageFactory.data.currentSelectedPartner);
                MessageFactory.data.conversationList.splice($indexToRemove, 1);
                MessageFactory.deleteConversation($userId)
                              .then(function(count) {},
                                    function(errorMessage) {
                                        alert(errorMessage);
                                    }
                              );
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
                                    }
                              );
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

            ModalService.showModal(modalDefaults, modalOptions)
                        .then(function ($parameters) {
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

        /**
         * Set real time chat configuration
         *
         * @param {mixed} $chatConfig
         */
        $scope.setRealTimeChatSettings = function($chatConfig) {
            var iosocketConnection = io.connect(
                'https://' + $chatConfig.chatServerHost + ':' + $chatConfig.chatServerPort, 
                { query: 'token=' + $chatConfig.jwtToken }
            );
            
            var socket = socketFactory({
                ioSocket: iosocketConnection 
            });

            /**
             * Handler if a send message action is broadcasted to current user's room
             * i.e. a message was sent to the current user
             */
            socket.on('send message', function( data ) {
                var newMessage = data.message.message;
                var newMessageSenderId = parseInt(newMessage.senderMemberId, 10);
                var sender = MessageFactory.getPartner(newMessageSenderId);
                if(sender){
                    var currentPartnerId = 0;
                    if(MessageFactory.data.currentSelectedPartner !== null){
                        currentPartnerId = parseInt(MessageFactory.data.currentSelectedPartner.from_id, 10);
                    }
                    sender.unread_message_count = parseInt(sender.unread_message_count,10) + 1; 
                    if(currentPartnerId === newMessageSenderId){
                        MessageFactory.setConversation([MessageFactory.constructMessage(newMessage)].concat(MessageFactory.data.conversation));
                        updateConversationList(newMessageSenderId);
                    }
                    sender.last_message = newMessage.message;
                    sender.last_date = newMessage.time_sent;
                }
                else{
                    var newConversationHeader = [{
                        'from_id': newMessageSenderId,
                        'id_msg': newMessage.id_msg,
                        'is_sender': false,
                        'last_date': newMessage.time_sent,
                        'last_message': newMessage.message,
                        'partner_image': newMessage.senderImage,
                        'partner_member_id': newMessage.senderMemberId,
                        'partner_storename': newMessage.senderStorename,
                        'to_id': newMessage.recipientMemberId,
                        'unread_message_count': 1,
                    }];
                    MessageFactory.setConversationList(newConversationHeader.concat(MessageFactory.data.conversationList));        
                }
            });
        }
    }
]); 
