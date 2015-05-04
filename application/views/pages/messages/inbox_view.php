<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link type="text/css" href='/assets/css/new-inbox.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <link type="text/css" href='/assets/css/vendor/bower_components/bootstrap.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php else: ?>
    <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.inbox-view.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>

<section class="bg-cl-fff">
    <?php echo form_open('/');?>
    <?php echo form_close();?> 
    <div data-ng-controller="MessageController">
        <div on-init-directive callback-fn="setConversationList(<?=html_escape($conversationHeaders);?>)"></div>
        <div on-init-directive callback-fn="setUnreadMessageCount(<?=html_escape($unreadConversationCount);?>)"></div>
    </div>
    
    <div class="container inbox-view-content">
        <div id="head_container" class="row">
            <div class="row">
                <!--LEFT SIDE-->
                <div class="col-md-4">
                    <div ui-view="conversationHead"></div>
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
</section>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src="/assets/js/src/vendor/bower_components/angular.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
    <script src="/assets/js/src/vendor/bower_components/angular-ui-router.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
    <script src="/assets/js/src/vendor/bower_components/ng-infinite-scroll.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
    <script src="/assets/js/src/vendor/bower_components/checklist-model.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
    <script src="/assets/js/src/vendor/bower_components/angular-ui-bootstrap-tpls.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.inbox-view.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>
<script src="/assets/js/angular/app.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/angular/services/modalService.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/angular/controllers/messageController.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/angular/controllers/headerController.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/angular/factories/messageFactory.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/angular/factories/headerFactory.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/angular/directives/csrfDirective.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<script src="/assets/js/angular/directives/onInitDirective.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>

