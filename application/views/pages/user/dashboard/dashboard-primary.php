<?php if(strtolower(ENVIRONMENT) === 'development'): ?> 
    <link type="text/css" href='/assets/css/vendor/bower_components/jstree.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <link type="text/css" href='/assets/css/contact.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <link type="text/css" href="/assets/css/vendor/bower_components/chosen.min.css" rel="stylesheet"  media="screen"/> 
    <link type="text/css" href='/assets/css/new-dashboard.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php else: ?>
    <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.dashboard-primary.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
<?php endif; ?>


<section class="section-dashboard ">
    <div class="container container-dashboard">
        <div class="row-fluid">
            <div class=" idTabs">
            <div class="col-sm-3 col-sidebar">
                <ul class="sidebar-dashboard" >
                    <a href="#dashboard" class="dash-me selected"><li id="dash"  class="mf-li">Dashboard</li></a>
                    <a><li id="my-store-menu-trigger">My Store <i class="m icon-control-down toggle-down pull-right" id="control-menu-1"></i></li></a>
                    <div id="my-store-menu">
                        <ul class="sidebar-submenu">
                            <a href="#transactions"  class="aaa id-transactions-trigger"><li id="transactions-trigger-li"class="f-li ms-f">Transactions</li></a>
                            <a href="#setup" id="store-setup-tab"><li class="f-li ms-f">Store Setup</li></a>
                            <a href="#customize-category" id="customize-category-tab"><li class="f-li ms-f">Customize Category</li></a>
                            <a href="#product-management" id="product-management-tab"><li class="f-li">Product Management</li></a>
                        </ul>
                    </div>
                    <a><li id="my-account-menu-trigger" class="ml-li">My Account <i class="a icon-control-down toggle-down pull-right"></i></li></a>
                    <div id="my-account-menu">
                        <ul class="sidebar-submenu submenu-my-account">
                            <a href="#personal-information" class="personal-info-trigger"><li class="f-li">Personal Information</li></a>
                            <a href="#delivery-address" class="delivery-address-trigger"><li class="m-li">Delivery Address</li></a>
                            <a href="#payment-account" class="payment-account-trigger" id="payment-account-tab"><li class="m-li m-li2">Payment Account</li></a>
                            <a href="#activity-logs" class="activity-logs-trigger" ><li class="m-li m-li2">Activity Logs</li></a>
                            <a href="#account-settings" class="settings-trigger"><li class="f-li f-a">Account Settings</li></a>
                        </ul>
                    </div>
                </ul>
                
                <div class="easy-point-container">
                    <div class="easy-point-title">
                        easy points
                        <a href="/easypoints" target="_blank">
                            <span class="easy-point-question">?</span>
                        </a>
                        <p class="easy-point-tooltip">
                            Whats this?
                        </p>
                    </div>
                    <div class="current-point-container">
                        <div class="border-bttm">
                            <span class="current-point-title">Current points</span>
                            <span class="current-points"><?php echo number_format($totalUserPoint, 2, '.', ',') ?></span>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <ul class="easy-point-content">
                    </ul>
                    <div class="text-center point-loader">
                        <img src="<?php getAssetsDomain(); ?>assets/images/es-loader-3-md.gif">
                    </div>
                </div>
            </div>
            <div class="mobile-dashboard-menu">
                <div class="row-fluid row-menu-mobile-res">
                    <a  class="dash-mobile-trigger dashboard-home-mobile selectedM">
                        <div class="col-xs-4 col-dash-mobile">
                            Dashboard
                        </div>
                    </a>
                    <div class="col-xs-4 col-dash-mobile my-store-menu-mobile">
                        My Store <i class="ms fa fa-angle-down"></i>
                    </div>
                    <div class="col-xs-4 col-dash-mobile my-account-menu-mobile">
                        My Account <i class="ma fa fa-angle-down"></i>
                    </div>
                </div>
                <div class="my-store-menu-mobile-cont">
                    <ul class="my-store-menu-mobile-ul">
                        <a class="ms-trans dash-mobile-trigger"><li class="m-menu-transactions">Transactions</li></a>
                        <a class="ms-setup dash-mobile-trigger" class="dash-mobile-trigger"><li class="m-menu-setup">Store Setup</li></a>
                        <a class="ms-customize dash-mobile-trigger" class="dash-mobile-trigger"><li class="m-menu-customize">Customize Category</li></a>
                        <a class="ms-prod dash-mobile-trigger" class="dash-mobile-trigger"><li class="m-menu-prod">Product Management</li></a>
                    </ul>
                </div>
                <div class="my-account-menu-mobile-cont">
                    <ul class="my-account-menu-mobile-ul">
                        <a class="ma-info dash-mobile-trigger"><li class="m-menu-personal">Personal Information</li></a>
                        <a class="ma-delivery dash-mobile-trigger"><li class="m-menu-delivery">Delivery Address</li></a>
                        <a class="ma-payment dash-mobile-trigger"><li class="m-menu-payment">Payment Account</li></a>
                        <a class="ma-activity dash-mobile-trigger"><li class="m-menu-activity">Activity Logs</li></a>
                        <a class="ma-settings dash-mobile-trigger"><li class="m-menu-setting">Account Settings</li></a>
                    </ul>
                </div>
            </div>
            
            </div>
            <div class="col-md-9 col-content">
                <div class="div-dashboard-content">
                    <div class="" id="dashboard">
                        <?=$dashboardHomeView; ?>
                    </div>
                    <div id="transactions">
                        <?php include("dashboard-transactions.php");?>
                    </div>
                    <div id="setup">
                        <?php include("dashboard-store-setup.php");?>
                    </div>
                    <div id="customize-category">
                        <?php include("dashboard-customize-category.php");?>
                    </div>
                    <div id="product-management">
                        <?php include("dashboard-product-management.php");?>
                    </div>
                    <div id="personal-information">
                        <?php include("dashboard-personal-info.php");?>
                    </div>
                    <div id="delivery-address" style="display:none;">
                        
                        <?php include("dashboard-delivery-address.php");?>
                    </div>
                    <div id="payment-account">
                        <?php include("dashboard-payment-account.php");?>
                    </div>
                    <div id="activity-logs">
                        <?php include("dashboard-activity-logs.php");?>
                    </div>
                    <div id="account-settings">
                        <?php include("dashboard-account-settings.php");?>
                    </div>
                    
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <input type="hidden" id="page-tab" value="<?php echo html_escape($tab); ?>"/>
</section>
<br/>

<div>
    <input type="hidden" id="request-url" value="/me/product/next" />
    <input type="hidden" id="request-url-soft-delete" value="/me/product/delete-soft" />
    <input type="hidden" id="request-url-hard-delete" value="/me/product/delete-hard" />
    <input type="hidden" id="request-url-resotre" value="/me/product/restore" />
    <input type="hidden" id="feedback-request-url" value="/me/feedback/next" />
    <input type="hidden" id="sales-request-url" value="/me/sales/next" />
    <input type="hidden" id="first-sales-request-url" value="/me/sales" />
    <?=form_open('/sell/edit/step2', ['id' => 'formEdit']); ?>
        <input type="hidden" id="editTextProductId" name="p_id" value="" />
        <input type="hidden" id="editTextCategoryId" name="hidden_attribute" value="" />
        <input type="hidden" id="editTextCategoryName" name="othernamecategory" value="" />
    <?=form_close();?> 
</div>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=&sensor=false"></script>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type='text/javascript' src="/assets/js/src/vendor/jquery-1.9.1.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src="/assets/js/src/vendor/jquery-ui.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src="/assets/js/src/vendor/jquery.ui.touch-punch.min.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.validate.js?ver=<?=ES_FILE_VERSION?>'></script>
    <script type="text/javascript" src="/assets/js/src/vendor/jquery.raty.min.js"></script>
    <script type='text/javascript' src='/assets/js/src/vendor/image.js?ver=<?=ES_FILE_VERSION?>'></script>
    <script type='text/javascript' src="/assets/js/src/vendor/jquery.idTabs.min.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src="/assets/js/src/vendor/jquery.idTabs.dashboard.home.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type="text/javascript" src='/assets/js/src/vendor/bower_components/chosen.jquery.min.js' ></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js?ver=<?=ES_FILE_VERSION?>'></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
    <script type='text/javascript' src='/assets/js/src/vendor/bower_components/jstree.js'></script>
    <script type='text/javascript' src="/assets/js/src/vendor/pwstrength.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src="/assets/js/src/vendor/bower_components/jquery.nicescroll.js?ver=<?=ES_FILE_VERSION?>"></script> 
    <script type='text/javascript' src="/assets/js/src/dashboard.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src="/assets/js/src/dashboard-myaccount.js?ver=<?=ES_FILE_VERSION?>"></script> 
    <script type='text/javascript' src="/assets/js/src/dashboard-express-edit.js?ver=<?=ES_FILE_VERSION?>"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.dashboard-primary.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>


