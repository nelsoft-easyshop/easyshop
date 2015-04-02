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
                    A Friendly Reminder
                </p>
                <span class="divider"></span>
                <p class="message-text">
                    We encourage everyone to be vigilant in transacting online. For your protection, <b>use only the payment method offered by EasyShop.ph (PesoPay, DragonPay, and Cash on Delivery)</b>. We do not encourage bank to bank transfers or deposits as payment for items bought on the EasyShop.ph website.
                    <br/><br/>
                    Should you decide to still go with the payment method not specified in the EasyShop.ph website, you can do so at your own risk. Just remember that EasyShop.ph will not be liable for damages incurred by transactions made outside of our system.
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

