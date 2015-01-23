<link type="text/css" href='/assets/css/main-style.css?ver=<?php echo ES_FILE_VERSION ?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-login.css?ver=<?php echo ES_FILE_VERSION ?>' rel="stylesheet" media='screen'/>
<br/>
<section class="section-login">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                Update your account
            </div>
            <div class="panel-body div-merge-container" align="center">
                <p>
                    The <a href="/<?=$member->getSlug()?>" target="_blank"><?=html_escape($member->getEmail())?></a> email account is already registered to an existing user. <br/>If you own this account would you like to merge this into a single account?
                </p>
                <div class="div-btn-container">
                    <button class="btn  btn-orange-lg proceed">
                        PROCEED <i class="glyphicon glyphicon-play"></i>
                    </button>
                    <img src="<?php echo getAssetsDomain()?>assets/images/orange_loader.gif" style="display: none">
                    <b>
                        <div class="div-link-login">
                            <a href="/register">
                                No, I would like to register with a different email account
                            </a>
                        </div>
                    </b>
                </div>
            </div>
        </div>
    </div>
    <!-- modal content -->
    <div id="basic-modal-content-proceed">
        <div class="modal-text-content">
            We've just sent a verification message to <?= html_escape($member->getEmail());?> account's inbox.<br/> Please login to your email account and follow the instructions provided to complete this process.
        </div>
        <center>
            <span class="modalCloseImg simplemodal-close btn btn-default-1" id="close-modal">Close</span>
            <span class="modalCloseImg simplemodal-close btn btn-default-2" id="homepage-modal">Go to homepage</span>
        </center>
    </div>
    <div style='display:none;'>
        <a class="modalCloseImg simplemodal-close" title="Close">
            <i class="glyphicon glyphicon-remove"></i>
        </a>
    </div>
    <div id="dataContainer" data-id="<?=$oauthId?>" data-provider="<?=$oauthProvider?>" data-email="<?=html_escape($member->getEmail())?>" data-mId="<?=$member->getIdMember()?>"  data-uname="<?=html_escape($member->getUsername())?>"></div>
</section>
<br/>
<br/>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src='/assets/js/src/SocialMediaMerge.js?ver=<?php echo ES_FILE_VERSION ?>' type='text/javascript'></script>
    <script src='/assets/js/src/vendor/jquery.simplemodal.js' type='text/javascript'></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.SocialMediaMerge.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

