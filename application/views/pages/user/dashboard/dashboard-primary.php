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
                            <a href="#transactions" class="id-transactions-trigger"><li class="f-li ms-f">Transactions</li></a>
                            <a href="#setup"><li class="f-li">Store Setup</li></a>
                        </ul>
                    </div>
                    <a><li id="my-account-menu-trigger" class="ml-li">My Account <i class="a icon-control-down toggle-down pull-right"></i></li></a>
                    <div id="my-account-menu">
                        <ul class="sidebar-submenu submenu-my-account">
                            <a href="#personal-information"><li class="f-li">Personal Information</li></a>
                            <a href="#delivery-address"><li class="m-li">Delivery Address</li></a>
                            <a href="#account-settings"><li class="f-li f-a">Account Settings</li></a>
                        </ul>
                    </div>
                </ul>
                
            </div>
            <div class="mobile-dashboard-menu">
                <div class="row-fluid">
                    <a href="#dashboard">
                        <div class="col-xs-4 col-dash-mobile">
                            Dashboard
                        </div>
                    </a>
                    <div class="col-xs-4 col-dash-mobile my-store-menu-mobile">
                        My Store
                    </div>
                    <div class="col-xs-4 col-dash-mobile my-account-menu-mobile">
                        My Account
                    </div>
                </div>
                <div class="my-store-menu-mobile-cont">
                    <ul class="my-store-menu-mobile-ul">
                        <a href="#transactions" class=""><li class="m-menu-transactions">Transactions</li></a>
                        <a href="#setup"><li class="m-menu-setup">Store Setup</li></a>
                    </ul>
                </div>
                <div class="my-account-menu-mobile-cont">
                    <ul class="my-account-menu-mobile-ul">
                        <a href="#personal-information"><li class="m-menu-personal">Personal Information</li></a>
                        <a href="#delivery-address"><li class="m-menu-delivery">Delivery Address</li></a>
                        <a href="#account-settings"><li class="m-menu-setting">Account Settings</li></a>
                    </ul>
                </div>
            </div>
            </div>
            <div class="col-md-9 col-content">
                <div class="div-dashboard-content">
                    <div id="dashboard">
                        <?php include("dashboard-home.php");?>
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
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</section>
<br/>
<script src="/assets/js/src/jquery-1.8.2.js?ver=<?=ES_FILE_VERSION?>"></script>

<script src="/assets/js/src/vendor/jquery.idTabs.min.js?ver=<?=ES_FILE_VERSION?>"></script>
<script src="/assets/js/src/dashboard.js?ver=<?=ES_FILE_VERSION?>"></script>
<script src="/assets/js/src/vendor/jquery.sortable.js?ver=<?=ES_FILE_VERSION?>"></script>
