 
<link rel="stylesheet" href="<?=base_url()?>assets/css/my_cart_css.css" type="text/css" media="screen"/>
<script src="<?= base_url() ?>assets/JavaScript/js/jquery.idTabs.min.js"></script>
<div class="clear"></div>

<section>
  <div class="wrapper">
    <h2 class="checkout_title">Payment</h2>
    <div class="shipping_info_content">
      <div>
        <!--<form id="shipping-form" method="POST">-->
		<?php 
			$attr = array('id'=>'shipping-form');
            
            
			echo form_open('',$attr);
		?>
          <h2><span>Ship to:</span> <!-- <a href=""><img src="images/img_edit2.jpg"> Edit</a> --></h2>
          <div class="shipping_info_title"><h4>Consignee Name:</h4></div>
          <div class="shipping_info_inner_content">
            <div><span>First Name:</span> <input type="text" id="u_consignee" name="u_consignee" value="<?php echo $address['consignee']; ?>"></div>

          </div>
          <div class="clear shipping_info_inner_border"></div>
          <div class="shipping_info_title"><h4>Delivery Address:</h4></div>
          <div class="shipping_info_inner_content">
            <div><span>St. No/Bldg. No:</span> <input type="text" id="u_streetno" name="u_streetno" value="<?php echo $address['streetno']; ?>"></div>
            <div><span>Street Name:</span> <input type="text" id="u_streetname" name="u_streetname" value="<?php echo $address['streetname']; ?>"></div>
            <div><span>Barangay:</span>  <input type="text" id="u_barangay" name="u_barangay" value="<?php echo $address['barangay']; ?>"></div><br />
            <div><span>City/Town:</span>  <input type="text" id="u_city" name="u_city" value="<?php echo $address['citytown']; ?>"></div>
            <div><span>Country:</span> <input type="text" id="u_country" name="u_country" value="<?php echo $address['country']; ?>"></div>
            <div><span>Postal Code:</span> <input type="text" id="u_postal" name="u_postal" value="<?php echo $address['postalcode']; ?>"></div>
          </div>
          <div class="clear shipping_info_inner_border"></div>
          <div class="shipping_info_title"><h4>Contact:</h4></div>
          <div class="shipping_info_inner_content">
            <div><span>Mobile No:</span> <input type="text" id="u_mobile" name="u_mobile" value="<?php echo $address['mobile']; ?>"></div>
            <div><span>Telephone No:</span>  <input type="text" id="u_telephone" name="u_telephone" value="<?php echo $address['telephone']; ?>"></div>
          </div>
          <div class="clear"></div>
        <?php echo form_close();?>
      </div>
    </div>
    <div class="payment_wrapper">

      <p class="fl_pay"><strong>How would you like to pay?</strong></p>
      <ul class="idTabs payment_options_tabs">
        <li><a href="#cod">Cash on Delivery</a></li>
        <li><a href="#cdb">Credit or Debit Card</a></li>
        <li><a href="#paypal">Paypal</a></li>
        <li><a href="#megalink">Megalink</a></li>
      </ul>
      <div id="cod" class="payment_inner_content">
        <p class="cod_desc"><strong>You can pay in cash to our courier when you receive the goods at your doorstep.</strong></p> 
        <p class="chck_billing_add"><input type="checkbox"> Billing address is the same as shipping  address</p>       
        <a href="JavaScript:void(0)" class="payment">Proceed to Payment <span> </span></a> 
        <p class="notify">You will be notified regarding your order status via email or sms.</p>
        <p class="subscribe"><input type="checkbox" checked> <img src="<?= base_url() ?>assets/images/icon_email.png" alt="email"> Subscribe to Easyshop Newsletter for great deals and amazing discounts</p>
        <p class="chck_privacy"><input type="checkbox"> I have read and understand Easyshop <a href="">Privacy Policy</a>.</p>
      </div>

      <div id="cdb" class="payment_inner_content">
        <p class="cod_desc"><strong>Pay using  Credit or Debit Card:</strong></p>
        <p class="img_payment_cards"><img src="<?= base_url() ?>assets/images/img_visa.png"> <img src="<?= base_url() ?>assets/images/img_mastercard.png"> <img src="<?= base_url() ?>assets/images/img_jcb.png"></p>
        <div class="cdb_info">
          <label>Cardholder's Name:</label> <input type="text"><br />
          <label>Card Number:</label> <input type="text"><br />
          <label>Credit Card Validity:</label>
          <select>
            <option>Month</option>
          </select>
          <select>
            <option>Year</option>
          </select><br />
          <label>Credit Card check</label><input type="text" class="cc_check">

          <span>?</span>
          <img src="<?= base_url() ?>assets/images/img_ccc.jpg" class="cc_check_img">
          
          <br />
          <label></label><input type="checkbox" checked>Save my Card information
        </div>
        <p class="chck_billing_add"><input type="checkbox"> Billing address is the same as shipping  address</p>    
        <a href="JavaScript:void(0)" class="payment">Proceed to Payment <span> </span></a> 
        <p class="notify">You will be notified regarding your order status via email or sms.</p>
        <p class="subscribe"><input type="checkbox" checked> <img src="<?= base_url() ?>assets/images/icon_email.png" alt="email"> Subscribe to Easyshop Newsletter for great deals and amazing discounts</p>
        <p class="chck_privacy"><input type="checkbox"> I have read and understand Easyshop <a href="">Privacy Policy</a>.</p>
      </div>

      <div id="paypal" class="payment_inner_content">
        <p class="cod_desc"><strong>Pay using your PayPal account. You will be redirected to the PayPal system to complete the payment.</strong></p>  <br />
        <!-- PayPal Logo -->
        <a href="https://www.paypal.com/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open('https://www.paypal.com/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=700, height=350'); return false;"><img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" border="0" alt="PayPal Logo" style="vertical-align:middle;text-decoration: underline;"> What is PayPal?</a>
        <br />
        <!-- PayPal Logo --> 
        <?php
        if(count($cat_item) <= 0)
        { ?> <br /> <br />
       No Items in Cart Can't Proceed.
       <?php 
        }else{ ?> <br /> <br />
      <!-- PAYPAL BUTTON -->
      <div class="paypal_button">
      <a style="cursor:pointer" data-type="paypal" class="paypal">
        <img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" alt="Paypap Express Checkout" align="left" style="margin-right:7px;">
        <span></span>
      </a>
      </div>


      <div class="paypal_loader"><img src="/assets/images/paypal_load.gif"></div>
      <!-- END OF PAYPAL BUTTON -->
      <?php
        } ?>

    <br> 
    <br>
    <br>
 





    <p class="notify">You will be notified regarding your order status via email or sms.</p>
    <p class="subscribe"><input type="checkbox" checked> <img src="<?= base_url() ?>assets/images/icon_email.png" alt="email"> Subscribe to Easyshop Newsletter for great deals and amazing discounts</p>
    <p class="chck_privacy"><input type="checkbox"> I have read and understand Easyshop <a href="">Privacy Policy</a>.</p>
  </div>

  <div id="megalink" class="payment_inner_content">
    <p class="cod_desc"><strong>Pay online using your megalink debit/atm card from the following banks</strong></p> 
    <ul>
      <li>Country Rural Bank of Bulacan</li>
      <li>MASS-SPECC Cooperative Development Center</li>
      <li>Pacific Ace Savings Bank</li>
      <li>Unionbank of the Philippines</li>
      <li>United Coconut Planters Bank</li>
    </ul>
    <p class="chck_billing_add"><input type="checkbox"> Billing address is the same as shipping  address</p>       
    <a href="JavaScript:void(0)" class="payment">Proceed to Payment <span> </span></a> 
    <p class="notify">You will be notified regarding your order status via email or sms.</p>
    <p class="subscribe"><input type="checkbox" checked> <img src="<?php echo base_url(); ?>assets/images/icon_email.png" alt="email"> Subscribe to Easyshop Newsletter for great deals and amazing discounts</p>
    <p class="chck_privacy"><input type="checkbox"> I have read and understand Easyshop <a href="">Privacy Policy</a>.</p>
  </div>
</div>

<!-- Order Summary Start -->

<div class="order_summary">
  <h2>Order Summary</h2>
  <p>You have <?php echo count($cat_item);?> item in your cart</p>
  <div class="order_sum_title">
    <div>Product</div>
    <div>Quantity</div>
    <div>Price</div>
  </div>
  

  <?php 
  $total = 0;
  foreach ($cat_item as $key => $value) {
    $total += $value['subtotal'];
    ?>
    <div class="order_sum_content">
      <div><?php echo $value['name'] ?></div>
      <div><?php echo $value['qty'] ?></div>
      <div><?php echo number_format($value['price'], 2, '.',',') ?></div>
    </div>
    <?php } ?>


    <div class="order_sum_sub_total">
      <p>Subtotal: <span><?php echo number_format($total, 2, '.',','); ?></span></p>
      <p>Shipping fee: <span>Free</span></p>
      <p class="order_sum_total">Total: <span>Php <?php echo number_format($total, 2, '.',','); ?></span></p>
    </div>

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
  $('.paypal_loader').hide();
  $('.paypal_button').show();

  $('.proceed').unbind("click").click(function(e){

    var type =   $(this).data( "type" );
    e.preventDefault();
    $('#shipping-form').attr('action', "/payment/"+type).submit();

  });
});
</script>

<!-- PAYPAL FUNCTION -->
 <script type="text/javascript">

   $('.paypal').unbind("click").click(function(e){
    var action = "payment/paypal_setexpresscheckout";
    var csrftoken = $('input[name="es_csrf_token"]').val();
     $.ajax({
          async: false,
          type: "POST",
          url: '<?php echo base_url();?>' + action,
          data: "es_csrf_token=" + csrftoken,
          dataType: "json",
          beforeSend: function(jqxhr, settings) { 
            $('.paypal_loader').show();
            $('.paypal_button').hide();
          },
          success: function(d) {
            if (d.e == 1) {
               $('#shipping-form').attr('action', d.d).submit();
               $('.paypal_loader').hide();
               $('.paypal_button').show();
            } else {
              alert(d.d);
              $('.paypal_loader').hide();
              $('.paypal_button').show();
            }
          }
        });
  });

 </script> 
<!-- END OF PAYPAL FUNCTION -->