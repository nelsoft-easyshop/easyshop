<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/basic.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
    <link rel="stylesheet" type="text/css" href="/assets/css/message-box.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.home-reminder.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>


<div class="message-box display-none" style="display: none;">
    <div class="row-fluid">
        <div class="col-md-7 col-sm-7 col-xs-10">
            <div class="message-container">
                <p class="message-title">
                    We've just updated our <a href="https://www.easyshop.ph/terms">Terms &amp; Conditions </a>
                </p>
                <span class="divider"></span>
                <p class="message-text">
                    To better protect our Users and Sellers alike, we've just updated our Payment conditions. Starting today, PesoPay will only accept Philippine-issued credit cards.
                    <br/><br/>
                    Continuing to use EasyShop means an explicit agreement to the new terms that we've updated.
                </p>
                <span class="link-text simplemodal-close">
                    Continue Shopping <i class="fa fa-angle-right fa-lg"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src="/assets/js/src/vendor/jquery.simplemodal.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
    <script src="/assets/js/src/message-box.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.home-reminder.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

