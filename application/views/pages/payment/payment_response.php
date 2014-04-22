<link rel="stylesheet" href="<?=base_url()?>assets/css/my_cart_css.css" type="text/css" media="screen"/> 

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
      <div class="payment_items_con">
        <div><span>Invoice Number:</span>  <?php echo $invoice_no; ?></div>
        <div><span>Total Amount:</span>  P <?php echo $total; ?></div>
        <div><span>Date Added:</span>  <?php echo date("D-m-Y h:m", strtotime($dateadded)); ?></div>
      </div>
      <h2>Purchased Product</h2>
      <div class="payment_items_con">
         <?php 
         foreach ($itemList as $key => $value) {
            ?>
            <div><span>Product Name:</span> <?php echo $value['name']; ?></div>
            <div><span>Name of Seller:</span> <?php echo $value['seller_username']; ?></div>
            <div><span>Product Price:</span> <?php echo $value['price']; ?></div>
            <div><span>Product Quantity:</span> <?php echo $value['qty']; ?></div>
            <div><span>Product Subtotal:</span> <?php echo $value['subtotal']; ?></div>
            <div><span>Product Other Price:</span> <?php echo $value['otherFee']; ?></div>
            <div><span>Product Total Fee:</span> <?php echo $value['subtotal'] + $value['otherFee']; ?></div>
         <?php
         }
         ?>
      </div> 
      
    </div>
 
  </div>
</section>

<div class="clear"></div>
 
 