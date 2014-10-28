<link type="text/css" href='/assets/css/new-login.css?ver=<?php echo ES_FILE_VERSION ?>' rel="stylesheet" media='screen'/>
<br/>
<br/>
<br/>
<br/>
<section class="section-login">
    <div class="container">
        <div class="div-title-page-container">
            Social Media Login
        </div>
        <div class="div-merge-container" align="center">
            <p>
                Your username <a href="#">kurt</a> has the same registered email address of <a href="#">kurt-wilkinson</a>
                <br/>
                Would you like to merge both accounts
            </p>
            <div class="div-btn-container">
                <button class="btn btn-lg btn-orange-lg proceed">
                    PROCEED <i class="glyphicon glyphicon-play"></i>
                </button>
                <div class="div-link-login">
                    <a>Go back to login</a>
                </div>
            </div>
        </div>
    </div>
    <!-- modal content -->
		<div id="basic-modal-content">
			sdfsfsd
		</div>
<div style='display:none'>
			<img src='img/basic/x.png' alt='' />
		</div>
</section>
<br/>
<br/>
<br/>
<br/>
<script>
    jQuery(function ($) {
	// Load dialog on page load
	//$('#basic-modal-content').modal();

	// Load dialog on click
	$('.proceed').click(function (e) {
		$('#basic-modal-content').modal();
           
		return false;
	});
});
</script>
<script src='/assets/js/src/vendor/jquery.simplemodal.js' type='text/javascript'></script>