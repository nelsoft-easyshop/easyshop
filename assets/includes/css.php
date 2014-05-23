<link type="text/css" href="<?=base_url()?>assets/css/style.css?ver=1.0" rel="stylesheet"  media="screen"/>
<link type='text/css' href='<?=base_url()?>assets/css/basic.css?ver=1.0' rel='stylesheet' media='screen' />
<link type="text/css" href="<?=base_url()?>assets/css/jcarousel.css" rel="stylesheet" />
<script>

  <?php
  if(preg_match('/(?i)msie [4-9]/',$_SERVER['HTTP_USER_AGENT']))
  {
    // if IE<=9
    ?>
    var badIE = true;
    <?php
  }
else
{
    // if IE>9
      ?>
    var badIE = false;
    <?php
}
  ?> 


    var config = {
         base_url: "<?php echo base_url(); ?>",
         badIE : badIE
    };




</script>
