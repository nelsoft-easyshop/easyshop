
<link type="text/css" href='/assets/css/easy-icons/easy-icons.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/contact.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/main-style.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-homepage.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-dashboard.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-dashboard-transaction.css' rel="stylesheet" media='screen'/>
<section class="section-dashboard ">
    <div class="container container-dashboard">
        <div class="row-fluid">
            <div class=" idTabs">
            <div class="col-sm-3 col-sidebar">
                <ul class="sidebar-dashboard" >
                    <a href="#dashboard" class="selected"><li id="dash"  class="mf-li">Dashboard</li></a>
                    <a><li id="my-store-menu-trigger">My Store <i class="m icon-control-down toggle-down pull-right" id="control-menu-1"></i></li></a>
                    <div id="my-store-menu">
                        <ul class="sidebar-submenu">
                            <a href="#transactions"  class="aaa id-transactions-trigger"><li id="transactions-trigger-li"class="f-li ms-f">Transactions</li></a>
                            <a href="#setup"><li class="f-li">Store Setup</li></a>
                        </ul>
                    </div>
                    <a><li id="my-account-menu-trigger" class="ml-li">My Account <i class="a icon-control-down toggle-down pull-right"></i></li></a>
                    <div id="my-account-menu">
                        <ul class="sidebar-submenu submenu-my-account">
                            <a href="#personal-information"><li class="f-li">Personal Information</li></a>
                            <a href="#delivery-address"><li class="m-li">Delivery Address</li></a>
                            <a href="#account-settings"><li class="f-li f-a">Account Settings</li></a>
                            <a href="#payment-account"><li class="f-li f-a">Payment Account</li></a>
                        </ul>
                    </div>
                </ul>
            </div>
            <div class="mobile-dashboard-menu">
                <div class="row-fluid row-menu-mobile-res">
                    <a href="#dashboard" class="dash-mobile-trigger">
                        <div class="col-xs-4 col-dash-mobile ">
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
                        <a href="#transactions" class="dash-mobile-trigger"><li class="m-menu-transactions">Transactions</li></a>
                        <a href="#setup" class="dash-mobile-trigger"><li class="m-menu-setup">Store Setup</li></a>
                    </ul>
                </div>
                <div class="my-account-menu-mobile-cont">
                    <ul class="my-account-menu-mobile-ul">
                        <a href="#personal-information" class="dash-mobile-trigger"><li class="m-menu-personal">Personal Information</li></a>
                        <a href="#delivery-address" class="dash-mobile-trigger"><li class="m-menu-delivery">Delivery Address</li></a>
                        <a href="#account-settings" class="dash-mobile-trigger"><li class="m-menu-setting">Account Settings</li></a>
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
                    <div id="personal-information">
                        <?php include("dashboard-personal-info.php");?>
                    </div>
                    <div id="delivery-address">
                        <?php include("dashboard-delivery-address.php");?>
                    </div>
                    <div id="account-settings">
                        <?php include("dashboard-account-settings.php");?>
                    </div>
                    <div id="payment-account">
                        <?php include("dashboard-payment-account.php");?>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</section>
<br/>

<div>
    <input type="hidden" id="request-url" value="/me/product/next" />
    <input type="hidden" id="request-url-soft-delete" value="/me/product/delete-soft" />
    <input type="hidden" id="request-url-hard-delete" value="/me/product/delete-hard" />
    <input type="hidden" id="request-url-resotre" value="/me/product/restore" />
    <input type="hidden" id="feedback-request-url" value="/me/feedback/next" />
    <input type="hidden" id="sales-request-url" value="/me/sales/next" />
    <?=form_open('/sell/edit/step2', ['id' => 'formEdit']); ?>
        <input type="hidden" id="editTextProductId" name="p_id" value="" />
        <input type="hidden" id="editTextCategoryId" name="hidden_attribute" value="" />
        <input type="hidden" id="editTextCategoryName" name="othernamecategory" value="" />"
    <?=form_close();?> 
</div>
<script type='text/javascript' src='/assets/js/src/vendor/image.js?ver=<?=ES_FILE_VERSION?>'></script>
<script src="/assets/js/src/vendor/jquery.idTabs.min.js?ver=<?=ES_FILE_VERSION?>"></script>
<script src="/assets/js/src/dashboard.js?ver=<?=ES_FILE_VERSION?>"></script>
<script src="/assets/js/src/vendor/jquery.sortable.js?ver=<?=ES_FILE_VERSION?>"></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js?ver=<?=ES_FILE_VERSION?>'></script>


