<link type="text/css" href='/assets/css/easy-icons/easy-icons.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/main-style.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/contact.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-homepage.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-dashboard.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<section class="section-dashboard">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sidebar">
                <ul class="sidebar-dashboard" role="tablist">
                    <li class="active"><a href="#dashboard" role="tab" data-toggle="tab">Dashboard</a></li>
                    <li id="my-store-menu-trigger">My Store <i class="m icon-control-down toggle-down pull-right" id="control-menu-1"></i></li>
                    <div id="my-store-menu">
                        <ul class="sidebar-submenu">
                            <li><a href="#transactions" role="tab" data-toggle="tab">Transactions</a></li>
                            <li><a href="#promo" role="tab" data-toggle="tab">Promo</a></li>
                            <li><a href="#setup" role="tab" data-toggle="tab">Store Setup</a></li>
                        </ul>
                    </div>
                    <li>My Account <i class="icon-control-down toggle-down pull-right"></i></li>
                </ul>
            </div>
            <div class="col-md-9 col-content">
                <div class="div-dashboard-content tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="dashboard">
                        <?php include("dashboard-home.php");?>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="transactions">transactions</div>
                    <div role="tabpanel" class="tab-pane fade" id="promo">promo</div>
                    <div role="tabpanel" class="tab-pane fade" id="setup">setup</div>
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
<script src="/assets/js/src/bootstrap.js?ver=<?=ES_FILE_VERSION?>"></script>
<script src="/assets/js/src/dashboard.js?ver=<?=ES_FILE_VERSION?>"></script>
