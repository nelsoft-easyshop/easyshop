<link type="text/css" href='/assets/css/base.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-cart.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>

<div class="transaction-container">
    <div class="container">
        <!--Start of transaction breadcrumb-->
        <div class="transaction-breadcrumb-container">
            <div class="row">
                <div class="col-xs-4 col-trans-breadcrumb active">
                    <div class="breadcrumb-left-wing active-wing"></div>
                    <center>
                        <div class="circle-breadcrumb">
                            <i class="fa icon-cart fa-lg"></i>
                        </div>
                        <div class="breadcrumb-title"><i class="fa fa-check"></i> Shopping Cart</div>
                    </center>
                    <div class="breadcrumb-right-wing active-wing"></div>
                </div>
                <div class="col-xs-4 col-trans-breadcrumb active">
                    <div class="breadcrumb-left-wing active-wing"></div>
                    <center>
                        <div class="circle-breadcrumb">
                            <i class="fa icon-payment fa-lg"></i>
                        </div>
                        <div class="breadcrumb-title">Checkout Details</div>
                    </center>
                    <div class="breadcrumb-right-wing"></div>
                </div>
                <div class="col-xs-4 col-trans-breadcrumb">
                    <div class="breadcrumb-left-wing"></div>
                    <center>
                        <div class="circle-breadcrumb">
                            <i class="fa fa-check fa-lg"></i>
                        </div>
                         <div class="breadcrumb-title">Order Complete</div>
                    </center>
                    <div class="breadcrumb-right-wing"></div>
                </div>
            </div>
        </div>
        <!--End of transaction breadcrumb-->

        
        <div class="row">
            <!--Start of shipping details-->
            <div class="col-md-7">
                <div class="transaction-container bg-white">
                    <p class="transaction-container-title">Shipping Details</p>
                     <p class="transaction-container-text">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Interrogari re pervenias videmus quando suspicor, ponit fugiat leguntur cupiditatibus usque intus careat disputatione, sint audivi affirmatis indoctis secutus,
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fname">First Name <abbr class="required" title="required">*</abbr></label>
                                <input type="text" id="fname" class="form-es-control form-es-control-block" />
                             </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="lname">Last Name <abbr class="required" title="required">*</abbr></label>
                                <input type="text" id="lname" class="form-es-control form-es-control-block" />
                             </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End of shipping details-->

            <!--Start of order summary-->
            <div class="col-md-5">
                <div class="transaction-container bg-gray">
                </div>
            </div>
            <!--End of order summary-->
        </div>
        
    </div>    
</div>

<script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js?ver=<?=ES_FILE_VERSION?>'></script>
<script src="/assets/js/src/cart.js?ver=<?php echo ES_FILE_VERSION ?>"></script>