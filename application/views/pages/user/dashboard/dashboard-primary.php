<link type="text/css" href='/assets/css/easy-icons/easy-icons.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/main-style.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/contact.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-homepage.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-dashboard.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>

<link type="text/css" href='/assets/css/new-dashboard-transaction.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>

<section class="section-dashboard idTabs">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sidebar">
                <ul class="sidebar-dashboard" >
                    <li class="active"><a href="#dashboard">Dashboard</a></li>
                    <li id="my-store-menu-trigger">My Store <i class="m icon-control-down toggle-down pull-right" id="control-menu-1"></i></li>
                    <div id="my-store-menu">
                        <ul class="sidebar-submenu">
                            <a href="#transactions"><li class="f-li">Transactions</li></a>
                            <a href="#promo"><li>Promo</li></a>
                            <a href="#setup"><li class="f-li">Store Setup</li></a>
                        </ul>
                    </div>
                    <li>My Account <i class="icon-control-down toggle-down pull-right"></i></li>
                </ul>
      
            </div>
            <div class="col-md-9 col-content">
                <div class="div-dashboard-content tab-content">
                    <div class="tab-pane" id="dashboard">
                        <?php include("dashboard-home.php");?>
                    </div>
                    <div class="" id="transactions">
                        <?php include("dashboard-transactions.php");?>
                    </div>
                    <div class="" id="promo">promo</div>
                    <div class="" id="setup">setup</div>
                </div>
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

<script src="/assets/js/src/jquery-1.8.2.js?ver=<?=ES_FILE_VERSION?>"></script>
<script src="/assets/js/src/vendor/jquery.idTabs.js?ver=<?=ES_FILE_VERSION?>"></script>
<script src="/assets/js/src/dashboard.js?ver=<?=ES_FILE_VERSION?>"></script>

