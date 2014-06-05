<?php require_once("assets/includes/css.php"); ?>
<?php require_once("assets/includes/js.php"); ?>

<link type="text/css" rel="stylesheet" href="<?=base_url()?>/assets/css/jquery.countdown.css" />

	<div id="countdown" style="width: 500px; height: 50px;">	</div>
	
	<span id="click">Click me</span>

<script src="<?=base_url()?>/assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>/assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script>


<script type="text/javascript">
	var endDate = new Date(<?php echo $date_end;?>);
	var rawDate = '<?php echo $date_end?>';
	console.log(endDate);
	console.log(rawDate);
	$('#countdown').countdown({
		until : endDate
	});
	
	$('#click').on('click', function(){
		alert('countdown is running');
	});
	
</script>









