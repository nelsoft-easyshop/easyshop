<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--Modifed by Janz - to include "status" under uploadFinish function-->
<!--[if IE]> <html class="ie"> <![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JustBoil's Result Page</title>
<script language="javascript" type="text/javascript">
    var htmlclass = document.documentElement.className;
    var isNewIE = false;
    var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;

    if( (navigator.appName.indexOf('Netscape') != -1 || navigator.appName.indexOf('Explorer') != -1) && navigator.userAgent.indexOf('Trident') != -1 )
        isNewIE = true;
    
    if( htmlclass == 'ie' || isNewIE || isSafari) {
        window.parent.window.jbImagesDialog.uploadFinish({
            filename:'<?php echo $file_name; ?>',
            result: '<?php echo $result; ?>',
            resultCode: '<?php echo $resultcode; ?>',
            status: '<?php echo $status?>',
            base_url: '<?php echo $base_url?>'
        });
    } 
    else{
        window.jbImagesDialog.uploadFinish({
            filename:'<?php echo $file_name; ?>',
            result: '<?php echo $result; ?>',
            resultCode: '<?php echo $resultcode; ?>',
            status: '<?php echo $status?>',
            base_url: '<?php echo $base_url?>'
        });
    }
</script>
</head>

<body>

Result: <?php echo $result; ?>

</body>
</html>
