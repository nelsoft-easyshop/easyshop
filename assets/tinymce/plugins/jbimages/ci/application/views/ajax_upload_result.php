<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--Modifed by Janz - to include "status" under uploadFinish function-->
<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><!--<![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JustBoil's Result Page</title>
<script language="javascript" type="text/javascript">
	var htmlclass = document.documentElement.className;
	if( htmlclass == 'ie6' || htmlclass == 'ie7' || htmlclass == 'ie8' || htmlclass == 'ie9' ) {
		window.parent.window.jbImagesDialog.uploadFinish({
			filename:'<?php echo $file_name; ?>',
			result: '<?php echo $result; ?>',
			resultCode: '<?php echo $resultcode; ?>',
			<!--Added code-->
			status: '<?php echo $status?>'
		});
	} else {
		window.jbImagesDialog.uploadFinish({
			filename:'<?php echo $file_name; ?>',
			result: '<?php echo $result; ?>',
			resultCode: '<?php echo $resultcode; ?>',
			<!--Added code-->
			status: '<?php echo $status?>'
		});
	}
	
	
</script>
</head>

<body>

Result: <?php echo $result; ?>

</body>
</html>
