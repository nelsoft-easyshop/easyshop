<?php if(strtolower(ENVIRONMENT) === 'development'): ?> 
    <link type="text/css" href='/assets/css/contact.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    <link type="text/css" href="/assets/css/vendor/bower_components/chosen.min.css" rel="stylesheet"  media="screen"/> 
    <link type="text/css" href='/assets/css/new-dashboard.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php else: ?>
    <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.dashboard-primary.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
<?php endif; ?>

<link type="text/css" href='/assets/css/jstree/style.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<section class="section-dashboard ">
    <div class="container container-dashboard">
        <div class="row-fluid">
            <div class="idTabs">
                <div class="col-sm-3 col-sidebar">

                    <!--Start of OLD sidebar tab for desktop-->
                    <!--
                    <ul class="sidebar-dashboard" style="display: none;" >
                        <a href="#dashboard" class="dash-me selected">
                            <li id="dash" class="mf-li">Dashboard</li>
                        </a>

                        <a>
                            <li id="my-store-menu-trigger" class=" dashboard-menu-trigger" data-section="my-store">My Store <i class="icon-control-down toggle-down pull-right"></i></li>
                        </a>
                        <div id="my-store-menu" class="dashboard-menu">
                            <ul class="sidebar-submenu">
                                <a href="#setup" id="store-setup-tab"><li class="f-li ms-f">Store Setup</li></a>
                                <a href="#customize-category" id="customize-category-tab"><li class="f-li ms-f">Customize Category</li></a>
                                <a href="#product-management" id="product-management-tab"><li class="f-li">Product Management</li></a>
                            </ul>
                        </div>
    
                        <a href="#transactions">
                            <li id="transaction-menu-trigger" class=" dashboard-menu-trigger" data-section="my-transaction">Transactions<i class="m icon-control-down toggle-down pull-right"></i></li>
                        </a>
                        <div id="transaction-menu" class="dashboard-menu">
                            <ul class="sidebar-submenu">
                                <a href="javascript:void(0);" class="transaction-trigger" data-type="on-going"><li class="f-li">On-going Transaction</li></a>
                                <a href="javascript:void(0);" class="transaction-trigger" data-type="completed"><li class="m-li">Completed Transaction</li></a>
                            </ul>
                        </div>
                        
                        <a>
                            <li id="my-account-menu-trigger" class="ml-li dashboard-menu-trigger" data-section="my-account">My Account <i class="icon-control-down toggle-down pull-right"></i></li>
                        </a>
                        <div id="my-account-menu" class="dashboard-menu">
                            <ul class="sidebar-submenu submenu-my-account">
                                <a href="#personal-information" class="personal-info-trigger"><li class="f-li">Personal Information</li></a>
                                <a href="#delivery-address" class="delivery-address-trigger"><li class="m-li">Delivery Address</li></a>
                                <a href="#payment-account" class="payment-account-trigger" id="payment-account-tab"><li class="m-li m-li2">Payment Account</li></a>
                                <a href="#activity-logs" class="activity-logs-trigger" ><li class="m-li m-li2">Activity Logs</li></a>
                                <a href="#account-settings" class="settings-trigger"><li class="f-li f-a">Account Settings</li></a>
                            </ul>
                        </div>
                    
                    </ul
                    -->
                    <!--End of OLD sidebar tab for desktop-->


                    <!--Start of new sidebar tab for desktop-->
                    <ul class="dashboard-sidebar-container">
                        <li>
                            <a href="#dashboard" class="selected">Dashboard</a>
                        </li>

                        <li src="mystore">
                            <a>
                                My Store
                                <i class="pull-right icon-control-down fa-lg sidebar-menu-icon"></i>
                            </a>
                            <div class="dashboard-sidebar-submenu-wrapper">
                                <ul class="dashboard-sidebar-submenu-container">
                                    <li>
                                        <a href="#setup">Store Setup</a>
                                    </li> 
                                    <li>
                                        <a href="#customize-category">Customize Category</a>
                                    </li> 
                                    <li>
                                        <a href="#product-management">Product Management</a>
                                    </li> 
                                </ul>
                            </div>
                        </li>
                        <li src="transactions">
                            <a >
                                Transactions
                                <i class="pull-right icon-control-down fa-lg sidebar-menu-icon"></i>
                            </a>
                            <div class="dashboard-sidebar-submenu-wrapper">
                                <ul class="dashboard-sidebar-submenu-container">
                                    <li>
                                        <a href="#on-going-transaction">On-Going Transaction</a>
                                    </li> 
                                    <li>
                                        <a href="#completed-transaction">Completed Transaction</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li src="myaccount">
                            <a>
                                My Account
                                <i class="pull-right icon-control-down fa-lg sidebar-menu-icon"></i>
                            </a>
                            <div class="dashboard-sidebar-submenu-wrapper">
                                <ul class="dashboard-sidebar-submenu-container">
                                    <li>
                                        <a href="#personal-information">Personal Information</a>
                                    </li> 
                                    <li>
                                        <a href="#delivery-address">Delivery Address</a>
                                    </li> 
                                    <li>
                                        <a href="#payment-account">Payment Account</a>
                                    </li>
                                    <li>
                                        <a href="#activity-logs">Activity Log</a>
                                    </li>
                                    <li>
                                        <a href="#account-settings">Account Settings</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <!--End of new sidebar tab for desktop-->

                    <!--Start of new sidebar tab for mobile-->
                    <ul class="mobile-dashboard-sidebar-container">
                        <li>
                            <a href="#dashboard">Dashboard</a>
                        </li>

                        <li class="col-xs-4">
                            <a src="mystore">
                                My Store
                                <i class="fa fa-angle-down sidebar-menu-icon"></i>
                            </a>
                            <div class="mobile-dashboard-sidebar-submenu-wrapper">
                                <ul class="mobile-dashboard-sidebar-submenu-container">
                                    <li>
                                        <a src="setup" href="#setup">Store Setup</a>
                                    </li> 
                                    <li>
                                        <a href="#customize-category">Customize Category</a>
                                    </li> 
                                    <li>
                                        <a href="#product-management">Product Management</a>
                                    </li> 
                                </ul>
                            </div>
                        </li>
                        <li class="col-xs-4">
                            <a src="transaction">
                                Transactions
                                <i class="fa fa-angle-down sidebar-menu-icon"></i>
                            </a>
                            <div class="mobile-dashboard-sidebar-submenu-wrapper">
                                <ul class="mobile-dashboard-sidebar-submenu-container">
                                    <li>
                                        <a href="#on-going-transaction">On-Going Transaction</a>
                                    </li> 
                                    <li>
                                        <a href="#completed-transaction">Completed Transaction</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="col-xs-4">
                            <a src="myaccount">
                                My Account
                                <i class="fa fa-angle-down sidebar-menu-icon"></i>
                            </a>
                            <div class="mobile-dashboard-sidebar-submenu-wrapper">
                                <ul class="mobile-dashboard-sidebar-submenu-container">
                                    <li>
                                        <a href="#personal-information">Personal Information</a>
                                    </li> 
                                    <li>
                                        <a href="#delivery-address">Delivery Address</a>
                                    </li> 
                                    <li>
                                        <a href="#payment-account">Payment Account</a>
                                    </li>
                                    <li>
                                        <a href="#activity-logs">Activity Log</a>
                                    </li>
                                    <li>
                                        <a href="#account-settings">Account Settings</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <!--End of new sidebar tab for mobile-->
                    <?php if(\EasyShop\PaymentGateways\PointGateway::POINT_ENABLED): ?>
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
                            <img src="<?php echo getAssetsDomain(); ?>assets/images/es-loader-3-md.gif">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!--Start of OLD sidebar tab for mobile-->
                <!--
                <div class="mobile-dashboard-menu" style="display: none !important;">        
                    <div class="row-fluid row-menu-mobile-res" >
                        <a class="dash-mobile-trigger my-transactions-mobile">
                            <div class="col-xs-4 col-dash-mobile" data-section="my-transactions">
                                Transactions
                            </div>
                        </a>
                        <div class="col-xs-4 col-dash-mobile my-store-menu-mobile" data-section="my-store">
                            My Store <i class="ms fa fa-angle-down"></i>
                        </div>
                        <div class="col-xs-4 col-dash-mobile my-account-menu-mobile" data-section="my-account">
                            My Account <i class="ma fa fa-angle-down"></i>
                        </div>
                    </div> 
                    
                    <div class="my-store-menu-mobile-cont mobile-menu-container" data-section="my-store">
                        <ul class="my-store-menu-mobile-ul">
                            <a class="ms-setup dash-mobile-trigger" class="dash-mobile-trigger"><li class="m-menu-setup">Store Setup</li></a>
                            <a class="ms-customize dash-mobile-trigger" class="dash-mobile-trigger"><li class="m-menu-customize">Customize Category</li></a>
                            <a class="ms-prod dash-mobile-trigger" class="dash-mobile-trigger"><li class="m-menu-prod">Product Management</li></a>
                        </ul>
                    </div>
                    <div class="my-account-menu-mobile-cont mobile-menu-container" data-section="my-account">
                        <ul class="my-account-menu-mobile-ul">
                            <a class="ma-info dash-mobile-trigger"><li class="m-menu-personal">Personal Information</li></a>
                            <a class="ma-delivery dash-mobile-trigger"><li class="m-menu-delivery">Delivery Address</li></a>
                            <a class="ma-payment dash-mobile-trigger"><li class="m-menu-payment">Payment Account</li></a>
                            <a class="ma-activity dash-mobile-trigger"><li class="m-menu-activity">Activity Logs</li></a>
                            <a class="ma-settings dash-mobile-trigger"><li class="m-menu-setting">Account Settings</li></a>
                        </ul>
                    </div>
                </div>
                -->
                <!--END of OLD sidebar tab for mobile-->
            </div>
            <div class="col-md-9 col-content">
                <div class="div-dashboard-content">
                    <div class="" id="dashboard">
                        <?php echo $dashboardHomeView; ?>
                    </div>
                    <?php include("dashboard-transactions.php");?>
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
    <script type='text/javascript' src="/assets/js/src/vendor/bower_components/jquery.ui.touch-punch.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src='/assets/js/src/vendor/bower_components/jquery.validate.js?ver=<?=ES_FILE_VERSION?>'></script>
    <script type="text/javascript" src="/assets/js/src/vendor/bower_components/jquery.raty.js"></script>
    <script type='text/javascript' src='/assets/js/src/vendor/image.js?ver=<?=ES_FILE_VERSION?>'></script>
    <script type='text/javascript' src="/assets/js/src/vendor/jquery.idTabs.min.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src="/assets/js/src/vendor/jquery.idTabs.dashboard.home.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type="text/javascript" src='/assets/js/src/vendor/bower_components/chosen.jquery.min.js' ></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js?ver=<?=ES_FILE_VERSION?>'></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
    <script type='text/javascript' src='/assets/js/src/vendor/bower_components/jstree.js'></script>
    <script type='text/javascript' src="/assets/js/src/vendor/bower_components/pwstrength.bootstrap.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src="/assets/js/src/vendor/bower_components/jquery.nicescroll.js?ver=<?=ES_FILE_VERSION?>"></script> 
    <script type='text/javascript' src="/assets/js/src/dashboard.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type='text/javascript' src="/assets/js/src/dashboard-myaccount.js?ver=<?=ES_FILE_VERSION?>"></script> 
    <script type='text/javascript' src="/assets/js/src/dashboard-express-edit.js?ver=<?=ES_FILE_VERSION?>"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.dashboard-primary.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>


