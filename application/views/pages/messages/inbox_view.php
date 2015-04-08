<script type="text/javascript" src="/assets/js/src/vendor/jquery.dataTables.min.js"></script>

<section class="bg-cl-fff">
    <div class="container inbox-view-content">
        <div id="head_container" class="row">
            <div class="col-xs-12 col-sm-4">
                <input type="button" id="modal-launcher" class="btn btn-default-4" value="Compose">
            </div>
            <div class="col-xs-12 col-sm-8 msg-chsn-username">
                <h3 id="chsn_username"></h3>
                <span>
                    <button id="chsn_delete_btn" class="btn btn-default-1"> Delete selected </button>
                    <button id="delete_all_btn" class="btn btn-default-1"> Delete this conversation </button>
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
                    <?PHP foreach($result['messages'] as $key => $row) { ?>
                        <tr class="<?=(reset($row)['opened'] == 0 && reset($row)['status'] == "receiver" ? "NS" : "")?>">
                            <td width="80">
                                <div class="img-wrapper-div">
                                    <span class="img-wrapper-span">
                                    <?php if(reset($row)['status'] == "sender"): ?>
                                        <img data="<?php echo getAssetsDomain().'.'.reset($row)['sender_img']?>" src="/<?php echo reset($row)['recipient_img']?>/60x60.png">
                                    <?php else: ?>
                                        <img data="<?php echo getAssetsDomain().'.'.reset($row)['recipient_img']?>" src="/<?php echo reset($row)['sender_img']?>/60x60.png">
                                    <?php endif; ?>
                                    <?php $span = (reset($row)['unreadConversationCount'] != 0 ? '('.reset($row)['unreadConversationCount'].')' : ""); ?>
                                    </span>
                                </div>
                                
                            </td>
                            <td class="msg-sender-details">
                                <a class="btn_each_msg" id="ID_<?=html_escape(reset($row)['name'])?>" href="javascript:void(0)" data='<?=html_escape(json_encode($row))?>'>
                                <span class="msg_sender">
                                    <?=html_escape(reset($row)['name'])."</span><span class=\"unreadConve\">".$span."</span>"; ?>
                                    <?php
                                    $keys = array_keys($row);
                                    $row[reset($keys)]['message'] = html_escape(reset($row)['message']);
                                    ?>
                                    <span class="msg_message"><?PHP echo reset($row)['message']; ?></span>
                                <span class="msg_date"><?PHP echo reset($row)['time_sent']; ?></span>
                                </a>
                            </td>
                        </tr>
                    <?PHP
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div id="msg_inbox_container" class = "msg_container mrgn-bttm-45 col-xs-12 col-sm-8">
                <div id="msg_field">

                </div>
                <div id="msg_textarea">
                    <textarea id="out_txtarea" placeholder="Write a message" class="ui-form-control"></textarea>
                    <button id="send_btn" class="btn btn-default-3" data="">Reply</button><img src="<?php echo getAssetsDomain(); ?>assets/images/horizontal_bar_loader.gif">
                </div>
            </div>
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
</section>
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src="/assets/js/src/messaging.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.inbox_view.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>


