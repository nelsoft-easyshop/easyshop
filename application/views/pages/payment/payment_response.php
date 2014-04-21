<link rel="stylesheet" href="<?=base_url()?>assets/css/my_cart_css.css" type="text/css" media="screen"/> 

<script src="<?= base_url() ?>assets/JavaScript/js/jquery.idTabs.min.js"></script>
    <div class="clear"></div>

<section>
  <div class="wrapper">
    <h2 class="checkout_title">Payment Result</h2>
 
    <div class="payment_wrapper">
      <br><br>
      <?php echo (isset($message_status) ? $message_status : "" ); ?>
      <br>
      <?php echo $message; ?>
      <h2>Transaction Details</h2>
      <div>Invoice Number: <?php echo $invoice_no; ?></div> 
      <div>Total Amount: P <?php echo $total; ?></div> 
      <div>Date Added: <?php echo date("D-m-Y h:m", strtotime($dateadded)); ?></div> 
      <h2>Purchased Product</h2> 
         <?php 
         foreach ($itemList as $key => $value) {
            ?>
            Product Name: <?php echo $value['name']; ?>
            <br>
            Name of Seller: <?php echo $value['seller_username']; ?>
            <br>
            Product Price: <?php echo $value['price']; ?>
            <br>
            Product Quantity: <?php echo $value['qty']; ?>
            <br>
            Product Subtotal: <?php echo $value['subtotal']; ?>
            <br>
            Product Other Price: <?php echo $value['otherFee']; ?>
            <br>
            Product Total Fee: <?php echo $value['subtotal'] + $value['otherFee']; ?>
            <hr>
         <?php
         }
         ?>
    </div>
 
  </div>
</section>

<div class="clear"></div>
 
 