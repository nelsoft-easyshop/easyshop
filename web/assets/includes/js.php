<?php
/*
 * This file is only for scripts that have dependent scripts
 */
?>


<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type='text/javascript' src="/assets/js/src/vendor/jquery-1.9.1.js" ></script>
    <script type='text/javascript' src="/assets/js/src/landingpage-responsive-nav.js" ></script>
    <script type='text/javascript' src="/assets/js/src/vendor/jquery-ui.js"></script>
    <script type='text/javascript' src="/assets/js/src/vendor/bower_components/jquery.cookie.js"></script>
    <script type='text/javascript' src="/assets/js/src/vendor/bower_components/socket.io.js"></script>
    <script type='text/javascript' src="/assets/js/src/nodeClient.js"></script>
    <script type='text/javascript' src="/assets/js/src/universal.js"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.includes.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

<script type='text/javascript'>
    <?php if(preg_match('/(?i)msie [4-9]/',$_SERVER['HTTP_USER_AGENT'])): ?>
        var badIE = true;
    <?php else: ?>
        if(window.FileReader){
            var badIE = false;
        }
        else{
            var badIE = true;
        }
    <?php endif; ?>

    var config = {
        base_url: "<?php echo base_url(); ?>",
        badIE : badIE,
        assetsDomain: "<?php echo getAssetsDomain(); ?>",
        isSocketioEnabled: <?php echo json_encode(ES_ENABLE_SOCKETIO); ?>
    };
</script>