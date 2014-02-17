<link rel="stylesheet" href="<?=base_url()?>assets/css/my_cart_css.css" type="text/css" media="screen"/> 

<script src="<?= base_url() ?>assets/JavaScript/js/jquery.idTabs.min.js"></script>
    <div class="clear"></div>

<section>
  <div class="wrapper">
    <h2 class="checkout_title">Payment Result</h2>
 
    <div class="payment_wrapper">
      <br><br>
      <?php echo $message_status; ?>
      <br>
      <?php echo $message; ?>
    </div>
 
  </div>
</section>

<div class="clear"></div>
<footer>
      <div class="wrapper">
        <div class="copyright">
          <p>Copyright © 2013 Easyshop.ph</p>
        </div>
      </div>
</footer>



</body>
</html>

<script type="text/javascript">
$(document).ready(function() {
      $('.proceed').unbind("click").click(function(e){
         
        var type =   $(this).data( "type" );
        e.preventDefault();
        $('#shipping-form').attr('action', "/payment/"+type).submit();

      });
    });
</script>