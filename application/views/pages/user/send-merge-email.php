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
                    Your username <a href="#">kurt</a> has the same registered email address of <a href="#">kurt-wilkinson</a>
                    <br/>
                    Would you like to merge both accounts?
                </p>
                <div class="div-btn-container">
                    <button class="btn  btn-orange-lg proceed">
                        PROCEED <i class="glyphicon glyphicon-play"></i>
                    </button>
                    <b>
                        <div class="div-link-login">
                            <a href="#">
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
            $('#basic-modal-content-proceed').modal();
            return false;
        });

    });
</script>

<script src='/assets/js/src/vendor/jquery.simplemodal.js' type='text/javascript'></script>
