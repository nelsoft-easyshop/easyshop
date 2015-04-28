<section class="bg-cl-fff" data-ng-app="messageApp">
    <div data-ng-controller="MessageController">
        <div class="container inbox-view-content">
            <div id="head_container" class="row" ui-view="conversationHead">
                <div class="col-xs-12 col-sm-4">
                    <input type="button" id="modal-launcher" class="btn btn-default-4" value="Compose">
                </div>
                <div class="col-xs-12 col-sm-8 msg-chsn-username">
                    <h3 id="chsn_username">{{storeName}}</h3>
                    <span>
                        <button class="btn btn-default-1"> Delete selected </button>
                        <button data-ng-show="userId != null" data-ng-click="deleteConversation(userId)" class="btn btn-default-1"> Delete this conversation </button>
                    </span>
                </div>
            </div>
            <div class="row mrgn-bttm-45">
                <div id="panel_container" class="col-xs-12 col-sm-4 mrgn-bttm-25">
                    <table id="table_id">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($conversationHeaders as $conversation): ?>
                            <tr class="<?php echo $conversation['unread_message_count'] ? 'NS' : '' ?>">
                                <td width="10%">
                                    <div class="img-wrapper-div">
                                        <span class="img-wrapper-span">    
                                        <img src="<?php echo getAssetsDomain() . '.' . $conversation['partner_image']; ?>"/>
                                        <?php $span = $conversation['unread_message_count'] > 0 ? '('.$conversation['unread_message_count'].')' : ''; ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="msg-sender-details">
                                    <a class="btn_each_msg" href="#/read/<?php echo $conversation['partner_member_id'] ?>/<?php echo html_escape($conversation['partner_storename']); ?>">
                                        <span class="conversation-partner-name" data-userid="<?php echo $conversation['partner_member_id'] ?>">
                                            <?php echo html_escape($conversation['partner_storename']); ?>
                                        </span>
                                        <span class='unread-conversation'> 
                                            <?php echo $span; ?> 
                                        </span>
                                        <span class="msg_message">
                                            <?php echo html_escape($conversation['last_message']); ?>
                                        </span>
                                        <span class="msg_date"><?PHP echo $conversation['last_date']; ?></span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Load angular view -->
                <div ui-view="conversationDetail">Select message</div>
            </div>
        </div>
        <div id="modal-background">
            <img src="<?php echo getAssetsDomain(); ?>assets/images/horizontal_loading.gif">
        </div>
        <div id="modal-container">
            <div id="modal-div-header">
                <button id="modal-close">X</button>
            </div>
            <div id="modal-inside-container">
                <div>
                    <label>To : </label>
                    <input type="text" value="" id="msg_name" name="msg_name" placeholder="Store name" class="ui-form-control">
                </div>
                <div class="mrgn-top-20">
                    <label>Message : </label><br>
                    <textarea cols="40" rows="5" name="msg-message" id="msg-message" class="ui-form-control" placeholder="Your message here.."></textarea>		
                </div>
            </div>
            <button id="modal_send_btn" class="btn btn-default-3">Send</button>
            <input type="hidden" id="userInfo" data-store-name="<?=html_escape($userEntity->getStoreName())?>">
        </div>
        <textarea id="userDataContainer" style="display:none"></textarea>
    </div>
</section>

<script src="/assets/js/src/vendor/bower_components/angular.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/src/vendor/bower_components/angular-ui-router.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/src/vendor/bower_components/ng-infinite-scroll.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/angular/app.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/angular/controllers/messageController.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/angular/factories/messageFactory.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>


<!-- <script src="/assets/js/src/vendor/bower_components/jquery.dataTables.js"></script>
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src="/assets/js/src/messaging.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.inbox_view.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?> -->

