<link rel="stylesheet" href="<?=base_url()?>assets/css/my_cart_css.css?ver=1.0" type="text/css" media="screen"/> 

<script src="<?= base_url() ?>assets/JavaScript/js/jquery.idTabs.min.js"></script>
    <div class="clear"></div>

<section>
  <div class="wrapper">
    <h2 class="checkout_title">Payment Result</h2>
 
    <div class="payment_result_con">
      <div class="payment_status_con">
      <?php echo (isset($message_status) ? $message_status : "" ); ?>
      <br>
      <?php echo $message; ?>
      </div>
      <h2>Transaction Details</h2>
      <?php
      if(isset($completepayment)){
      ?>
      <div class="payment_items_con">
        <div><span>Invoice Number:</span>  <?php echo $invoice_no; ?></div>
        <div><span>Total Amount:</span>  P <?php echo $total; ?></div>
        <div><span>Date Added:</span>  <?php echo date("D-m-Y h:m", strtotime($dateadded)); ?></div>
      </div>
      <?php }else{
        ?>
        <div class="payment_items_con">
        <div>Transaction unsuccessful.</div>
      </div>
        <?php
      } ?>
      <h2>Purchased Product</h2>
      <div class="payment_items_con">
         <?php 
         foreach ($itemList as $key => $value) {
            ?>
            <div><span>Product:</span> <?php echo $value['name']; ?></div>
            <div><span>Name of Seller:</span> <?php echo $value['seller_username']; ?></div>
            <div><span>Price:</span> <?php echo $value['price']; ?></div>
            <div><span>Quantity:</span> <?php echo $value['qty']; ?></div>
            <div><span>Subtotal:</span> <?php echo $value['subtotal']; ?></div>
            <div><span>Other Fee:</span> <?php echo $value['otherFee']; ?></div>
            <div><span>Total Fee:</span> <?php echo $value['subtotal'] + $value['otherFee']; ?></div>


            <hr>
         <?php
         }
         ?>
      </div> 

      <div>
        <a href="<?=base_url()?>home">Continue Shopping</a>
      </div>
      
    </div>
 
  </div>
</section>

<div class="clear"></div>

<?php 
  if(ENVIRONMENT == 'production'){
?>
<script>
  ga('require', 'ecommerce', 'ecommerce.js');

  <?php
    foreach ($analytics as $key => $value) {
  ?>

  ga('ecommerce:addTransaction', {
      'id': '<?php echo $value["id"]; ?>',
      'affiliation': '<?php echo $value["affiliation"]; ?>',
      'revenue': '<?php echo $value["revenue"]; ?>',
      'shipping': '<?php echo $value["shipping"]; ?>',
      'tax': '<?php echo $value["tax"]; ?>',
      'currency': '<?php echo $value["currency"]; ?>'
  });

  ga('ecommerce:addItem', {
      id: '<?php echo $value["data"]["id"]; ?>',  
      sku: '<?php echo $value["data"]["sku"]; ?>',  
      name: '<?php echo $value["data"]["name"]; ?>',  
      category: '<?php echo $value["data"]["category"]; ?>',  
      price: '<?php echo $value["data"]["price"]; ?>',  
      quantity: '<?php echo $value["data"]["quantity"]; ?>'
  }); 

  ga('ecommerce:send');

  <?php
    }
  ?>

</script>

<?php } ?>