<link type="text/css" href='/assets/css/easy-icons/easy-icons.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/main-style.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/contact.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-homepage.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-dashboard.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-dashboard-transaction.css' rel="stylesheet" media='screen'/>
<section class="section-dashboard ">
    <div class="container">
        <div class="row ">
            <div class="col-md-3 col-sidebar">
                <ul class="sidebar-dashboard idTabs" >
                    <a href="#dashboard" class="selected"><li id="dash"  class="mf-li">Dashboard</li></a>
                    <a><li id="my-store-menu-trigger">My Store <i class="m icon-control-down toggle-down pull-right" id="control-menu-1"></i></li></a>
                    <div id="my-store-menu">
                        <ul class="sidebar-submenu">
                            <a href="#transactions"><li class="f-li">Transactions</li></a>
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
            <div class="col-md-9 col-content">
                <div class="div-dashboard-content">
                    <div class="" id="dashboard">
                        <?=$dashboardHomeView; ?>
                    </div>
                    <div id="transactions">
                        <?php include("dashboard-transactions.php");?>
                    </div>
                    <div id="setup">setup</div>
                    <div id="personal-information">
                        <?php include("dashboard-personal-info.php");?>
                    </div>
                    <div id="delivery-address">
                        <?php include("dashboard-delivery-address.php");?>
                    </div>
                    <div id="account-settings">account settings</div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</section>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

<div>
    <input type="hidden" id="request-url" value="/me/product/next" />
    <input type="hidden" id="request-url-soft-delete" value="/me/product/delete-soft" />
    <input type="hidden" id="request-url-hard-delete" value="/me/product/delete-hard" />
    <?=form_open('/sell/edit/step2', ['id' => 'formEdit']); ?>
        <input type="hidden" name="p_id" value="" />
        <input type="hidden" name="hidden_attribute" value="" />
        <input type="hidden" name="othernamecategory" value="" />"
    <?=form_close();?> 
</div>

<script src="/assets/js/src/jquery-1.8.2.js?ver=<?=ES_FILE_VERSION?>"></script>
<script type='text/javascript' src="/assets/js/src/vendor/jquery-ui.js"></script>
<script src="/assets/js/src/vendor/jquery.idTabs.min.js?ver=<?=ES_FILE_VERSION?>"></script>
<script src="/assets/js/src/dashboard.js?ver=<?=ES_FILE_VERSION?>"></script>
