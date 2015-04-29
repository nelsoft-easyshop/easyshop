<section class="bg-cl-fff" data-ng-app="messageApp">
<div ng-cloak preload-resource='<?=$conversationHeaders;?>'></div>
    <div class="container inbox-view-content">
        <div id="head_container" class="row">
            <div class="row">
            <!--LEFT SIDE-->
            <div class="col-md-4">
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <input type="button" id="modal-launcher" class="btn btn-default-4" value="Compose">
                    </div>
                </div>
                <div class="row" data-ng-controller="MessageController">
                    <div class="row mrgn-bttm-45" ui-view="conversationHead"></div>
                </div>
            </div>
            <!--END OF LEFT SIDE-->

                <!--RIGHT SIDE-->
                <div class="col-md-8" ui-view="conversationDetail"></div>
                <!--END OF RIGHT SIDE-->
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

<script src="/assets/js/src/vendor/bower_components/angular.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/src/vendor/bower_components/angular-ui-router.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/src/vendor/bower_components/ng-infinite-scroll.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/src/vendor/bower_components/checklist-model.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/angular/app.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/angular/directives/preloadResource.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>

<script src="/assets/js/angular/controllers/messageController.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/angular/factories/messageFactory.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
