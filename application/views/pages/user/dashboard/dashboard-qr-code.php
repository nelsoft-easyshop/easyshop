<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->

<!--[if (gt IE 9)|!(IE)]><!--><html class="no-js"><!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Easyshop QR Code</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon"/>

    <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
        <link type="text/css" href='/assets/css/vendor/bower_components/bootstrap.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='all'/>
        <link rel="stylesheet" href="/assets/css/qr-code-css.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="all">
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.dashboard-qr-code.css?ver=<?=ES_FILE_VERSION?>' media='all'/>
    <?php endif; ?>
    
    <link rel="stylesheet" href="/assets/css/qr-code-print.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="print">
</head>

<body >
<section class="qr-code-wrapper">
    <div class="container text-center">
        <div class="row">
            <div class="col-xs-12 qr-code-img-con">
                <img src="<?php echo getAssetsDomain(); ?>assets/images/qrcode-images/qr-code_03.jpg">
                <img src="<?php echo getAssetsDomain(); ?>assets/images/qrcode-images/qr-code_04.jpg">
                <img src="<?php echo getAssetsDomain(); ?>assets/images/qrcode-images/qr-code_05.jpg">
                <img src="<?php echo getAssetsDomain(); ?>assets/images/qrcode-images/qr-code_06.jpg">
                <img src="<?php echo getAssetsDomain(); ?>assets/images/qrcode-images/qr-code_07.jpg">
                <img src="<?php echo getAssetsDomain(); ?>assets/images/qrcode-images/qr-code_08.jpg">
                <img src="<?php echo getAssetsDomain(); ?>assets/images/qrcode-images/qr-code_09.jpg">
                <img src="<?php echo getAssetsDomain(); ?>assets/images/qrcode-images/qr-code_10.jpg">
                <img src="<?php echo getAssetsDomain(); ?>assets/images/qrcode-images/qr-code_11.jpg">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h1 class="qr-sellername"><?=html_escape($slug)?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 qr-code-main">
                <img src="/<?php echo html_escape($qrCodeImageName); ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 qr-code-p">
                <p>
                    Scan the QR Code
                </p>
                <p>
                    or enter the following URL into your browser
                </p>
                <?=html_escape($storeLink)?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 mrgn-tb-100 qr-es-logo">
                <img src="<?php echo getAssetsDomain(); ?>assets/images/qrcode-images/easyshop-logo.jpg" alt="Easyshop.ph">
            </div>
        </div>
    </div>
    <div class="hide-border-bottom">
        <div class="border-1"></div>
        <div class="border-2"></div>
    </div>
</section> 
<script type="text/javascript">
    window.onload = function () {
        window.print();
        setTimeout(function(){window.close();}, 1);
    }
</script>
</body>
</html>
