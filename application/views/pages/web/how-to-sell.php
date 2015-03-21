
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link type="text/css" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<?php else: ?>
    <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.how-to.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
<?php endif; ?>

<div class="container info_wrapper pd-tb-45">
    <img src="<?php echo getAssetsDomain()?>assets/images/img_how-to-sell.png?ver=<?=ES_FILE_VERSION?>" alt="How to Sell" class="img-info-main">
    <div class="img-social-media-con">
        <a href="mailto:info@easyshop.ph">
            <img src="<?php echo getAssetsDomain()?>assets/images/img_email_lnk.png?ver=<?=ES_FILE_VERSION?>" alt="eMail" class="img-sc-email">
        </a>
        <a href="<?php echo $facebook; ?>">
            <img src="<?php echo getAssetsDomain()?>assets/images/img_fb_lnk.png?ver=<?=ES_FILE_VERSION?>" alt="Facebook - easyshopphilippines" class="img-sc-fb">
        </a> 
        <a href="<?php echo $twitter; ?>">
            <img src="<?php echo getAssetsDomain()?>assets/images/img_twitter_lnk.png?ver=<?=ES_FILE_VERSION?>" alt="Twitter - EasyShopPH" class="img-sc-tw">
        </a>
    </div>
</div>

