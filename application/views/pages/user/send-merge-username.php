<link type="text/css" href='/assets/css/main-style.css?ver=<?php echo ES_FILE_VERSION ?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/new-login.css?ver=<?php echo ES_FILE_VERSION ?>' rel="stylesheet" media='screen'/>
<br/>
<br/>
<section class="section-login">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                Update your account
            </div>
            <div class="panel-body div-merge-container">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="merge-message" align="center">
                            <i class="glyphicon glyphicon-exclamation-sign"></i> <strong>Ooooops!</strong> Your username <a href="#"><strong>kurt</strong></a> has already been taken. <br/>Please choose from the following options below.
                        </h5>
                    </div>
                </div>
               <div class="row">
                    <div class="col-md-5">
                        <div class="form-group form-merge-2">
                            <label class="control-label">Type in your new username</label>
                            <input type="text" class="form-control input-merge" placeholder="New username"/>
                            <div class="div-validation-container">
                                <p class="span-validation-ok">
                                    <i class="glyphicon glyphicon-ok-sign"></i>
                                    Username is available
                                </p>
                               <!-- WHEN USERNAME IS NOT AVAILABLE
                                <p class="span-validation-error">
                                    <i class="glyphicon glyphicon-remove-sign"></i>
                                    Username is not available
                                </p>-->
                            </div>
                            <button class="btn btn-block btn-orange-lg proceed">
                                PROCEED
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2 col-divider">
                        <div class="border-vr">
                        </div>
                        <div class="border-hr">
                        </div>
                        <div class="border-or">
                            OR
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group form-merge">
                            <p class="label-merge">
                                If you believe you own this account, type in your email address to send a verification request to the registered email of username to update your account.
                            </p>
                            <input type="text" class="form-control input-merge input-merge-username" placeholder="Type in your email account"/>
                            <div class="div-validation-container">
                                <p class="span-validation-ok">
                                    <i class="glyphicon glyphicon-ok-sign"></i>
                                    Username is available
                                </p>
                               <!-- WHEN USERNAME IS NOT AVAILABLE
                                <p class="span-validation-error">
                                    <i class="glyphicon glyphicon-remove-sign"></i>
                                    Username is not available
                                </p>-->
                            </div>
                            <div class="div-search-merge-account div-result">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <center>
                                            <a href="#">
                                                <div class="div-rec-product-image">
                                                    <center>
                                                        <span class="span-me">
                                                            <img src="/assets/images/img_main_product.png" class="img-rec-product">
                                                        </span>
                                                    </center>
                                                </div>
                                            </a>
                                        </center>
                                    </div>
                                    <div class="col-xs-9">
                                        <table width="100%" class="tbl-merge">
                                            <tr>
                                                <td class="td-merge-label">
                                                    Username:
                                                </td>
                                                <td class="td-merge-detail">
                                                    sampleUser
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="td-merge-label">
                                                    Email:
                                                </td>
                                                <td class="td-merge-detail">
                                                    sampleUser@yahoo.com
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="td-merge-label">
                                                    Location:
                                                </td>
                                                <td class="td-merge-detail">
                                                    Makina City
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-block btn-orange-lg proceed">
                                SEND REQUEST
                            </button>
                        </div>
                    </div>
               </div>
            </div>
        </div>
    </div>
    <!-- modal content -->
    <div id="basic-modal-content" class="modal-message">
        <div class="modal-text-content">
            We've just sent a verification message to kurtwilkinson024@yahoo.com account's inbox.<br/> Please login to your email account and follow the instructions provided to complete this process.
        </div>
        <center>
            <span class="modalCloseImg simplemodal-close btn btn-default-1">Close</span>
            <span class="modalCloseImg simplemodal-close btn btn-default-2">Go to homepage</span>
        </center>
    </div>
    <div style='display:none;'>
        <a class="modalCloseImg simplemodal-close" title="Close">
            <i class="glyphicon glyphicon-remove"></i>
        </a>
    </div>
</section>
<br/>
<br/>
<script>
    jQuery(function ($) {
        $('.proceed').click(function (e) {
            $('.modal-message').modal();
            return false;
        });

        $( ".input-merge-username" ).keyup(function() {
            $(".div-search-merge-account").css("display", "block");
        });

         $( ".div-search-merge-account" ).click(function() {
            $(".div-search-merge-account").css("display", "none");
         });
    });
</script>

<script src='/assets/js/src/vendor/jquery.simplemodal.js' type='text/javascript'></script>
