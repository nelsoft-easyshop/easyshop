<link rel="stylesheet" href="<?=base_url()?>assets/css/my_cart_css.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/> 

<script src="<?= base_url() ?>assets/js/src/vendor/jquery.idTabs.min.js"></script>
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
        <div><span>Date Added:</span>  <?php echo date("d/m/Y H:i", strtotime($dateadded)); ?></div>
      </div>
      <?php }else{
        ?>
        <div class="payment_items_con">
        <div>Transaction unsuccessful.</div>
      </div>
        <?php
      } ?>
      <h2>List of Products</h2>
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

      <?php  if(isset($completepayment)): ?>
        <div style='font-size: 17px;'>
            <p>
            You have made a successful purchase on Easyshop.ph. An e-mail has been sent to you and the people from whom you purchased
            regarding the status of your transaction. Please complete any necessary steps that are stated and thank you for using Easyshop.ph.
            You may view your pending transactions by clicking <a style='text-decoration:underline; color:#f48000' href='<?=base_url()?>me?me=pending'>here </a>
            </p>
        </div>
      <?php else: ?>
        <div style='font-size: 17px;'>
            <p>
            We are sorry, your purchase cannot be completed at this time. Should you need any further assistance you may reach us through our customer hotline. Thank you for using Easyshop.ph. 
            </p>
        </div>
      <?php endif ?>
      
      <div style='font-size: 14px; font-weight:bold;'>
        <br/><br/>
        <p><a href="<?=base_url()?>home">Continue Shopping</a></p>
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