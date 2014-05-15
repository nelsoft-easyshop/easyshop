 <?php
 
 ?>
<link rel="stylesheet" href="<?=base_url()?>assets/css/my_cart_css.css?ver=1.0" type="text/css" media="screen"/>
<style type="text/css">
/* Overlay */
#simplemodal-overlay {
  background-color:#bcbcbc;
}

/* Container */
#simplemodal-container {
  height: auto !important;
  width: auto !important; 
  background-color:#0000;
  padding: 5px;
}
</style>
<script src="<?= base_url() ?>assets/JavaScript/js/jquery.idTabs.min.js"></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.simplemodal.js'></script>

<div class="clear"></div>
<section>
  <div class="wrapper payment_content_wrapper">
    <h2 class="checkout_title">Payment</h2>



<?php
    if($shippingDetails == true){
?>
<div class="payment_wrapper<?php if(!$success){ ECHO '3';}?>">
        <div class="member_shipping_info_container">
          <h2>Ship to:</h2>
          <div><span>Name:</span><strong><?php echo ucwords(strtolower($consignee));?></strong></div>
          <div><span>Full Address:</span><?php echo ucwords(strtolower(html_escape($c_address)));?></div>
          <div><span>City:</span><?php echo ucwords(strtolower($c_stateregion));?></div>
          <div><span>Country:</span><?php echo ucwords(strtolower($country_name));?></div>
          <div><span>Mobile:</span><?php echo ucwords(strtolower($c_mobile));?></div>
          <div><span>Telephone:</span><?php echo ucwords(strtolower($c_telephone));?></div>
        </div>
<?php
    }else{
?>
<div class="payment_wrapper2">
    <div class="change_shipping_add_con">
        <div class="txt_change_wrapper">
          <div class="txt_change_shipping_address">
            <span style="color:red">PLEASE CHANGE YOUR SHIPPING ADDRESS!</span>
          </div>
        </div>
        <?php
            }
        ?>
        <div class="change_shipping_btn_con">
          <a href="javascript:void(0);"  class="link_address orange_btn3">Change Shipping Address</a> 
        </div>

    


  <?php
  if($success){
    ?>
  <div class="">
      <p class="fl_pay"><strong>How would you like to pay?</strong></p>
      <ul class="idTabs payment_options_tabs">
        <li><a href="#cod">Cash on Delivery</a></li>
        <li><a href="#cdb">Credit or Debit Card</a></li>
        <li><a href="#paypal">Paypal</a></li>
        <li><a href="#dragonpay">Dragon Pay</a></li>
      </ul>

<!-- #### CASH ON DELIVERY #### -->

      <div id="cod" class="payment_inner_content">
        <?php
          if($codsuccess){
        ?>
            <p class="cod_desc"><strong>You can pay in cash to our courier when you receive the goods at your doorstep.</strong></p> 
            <br>
                    <?php 
        $attr = array(
          'class' => 'codFrm',
          'id' => 'codFrm',
          'name' => 'codFrm'
          );
        echo form_open('pay/cashondelivery/', $attr);
        ?>
            <input type="submit" class="payment" value="Proceed to Payment"> 
            <input type="hidden" value="<?php echo md5(uniqid(mt_rand(), true));?>" name="paymentToken">   
            <?php echo form_close();?>
            <p class="notify">You will be notified regarding your order status via email or sms.</p>
            <!-- <p class="subscribe"><input type="checkbox" checked> <img src="<?= base_url() ?>assets/images/icon_email.png" alt="email"> Subscribe to Easyshop Newsletter for great deals and amazing discounts</p> -->
    <p class="chck_privacy"><input type="checkbox"> I acknowledge I have read and understood Easyshop.ph's <a href="">Privacy Policy</a>.</p>
    <?php }else{ ?>

      <span style="color:red"><strong>NOTE: one or more of your chosen items are not available for cash on delivery.</strong></span>
      <div class="pay_sum_head">
                <div class="pay_sum_c1">Seller</div>
                <div class="pay_sum_c2">Product</div>
                <div class="pay_sum_c3">Quantity</div>
                <div class="pay_sum_c4">Price</div>
              </div>
    <?php 
      $total = 0; 
      foreach ($cat_item as $key => $value) {
      $total += $value['subtotal'];
    ?>
            <div class="payment_sum_con">
              
              <div class="pay_sum_c1"><?php echo $value['seller_username'] ?></div>
              <div class="pay_sum_c2"><?php echo $value['name'] ?></div>
              <div class="pay_sum_c3"><?php echo $value['qty'] ?></div>
              <div class="pay_sum_c4"><?php echo number_format($value['price'], 2, '.',',') ?></div>
              <div class="cod_status_con"><?php echo ($value['cash_delivery'] ? "<span style='color:green'>Available for Cash on Delivery</span>" : "<span style='color:red'>Not available for Cash on Delivery</span> (Go to your <a href='".base_url()."cart' style='color:#0654BA'>Cart</a> and Remove this Item)") ;?></div>
                 <?php if(!$value['availability']){ ?>
                 <div style="color:red">
                   Please <a style="color:#0654BA" href="javascript:{}" class="link_address">change your shipping address</a> or go to remove this from your <a href="<?=base_url()?>cart" style="color:#0654BA">Cart</a>.
                 </div>
                 <?php } ?>
            </div>
           <?php } ?>
        <?php } ?>  
      </div>

<!-- #### CREDIT CARD / DEBIT CARD #### -->

      <div id="cdb" class="payment_inner_content">
 
        <p class="cod_desc"><strong>Pay using  Credit or Debit Card. You will be redirected to the PayPal system to complete the payment.</strong></p>  <br />
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
      <a style="cursor:pointer" data-type="2"  class="paypal">
        <img src="<?php echo base_url()?>assets/images/paypal_checkout_button.png" alt="Paypal Credit/Debit Card Checkout" align="left" style="margin-right:7px;">
        <span></span>
      </a>
      </div>


      <div class="paypal_loader"><img src="/assets/images/paypal_load.gif"></div> 
      <!-- END OF PAYPAL BUTTON -->
      <?php
        } ?>
    <div style="clear:both"></div>
    <p class="notify">You will be notified regarding your order status via email or sms.</p>
    <!-- <p class="subscribe"><input type="checkbox" checked> <img src="<?= base_url() ?>assets/images/icon_email.png" alt="email"> Subscribe to Easyshop Newsletter for great deals and amazing discounts</p> -->
    <p class="chck_privacy"><input type="checkbox"> I acknowledge I have read and understood Easyshop.ph's <a href="">Privacy Policy</a>.</p>
  </div>

<!-- #### PAYPAL #### -->

      <div id="paypal" class="payment_inner_content">
        <p class="cod_desc"><strong>Pay using your PayPal account. You will be redirected to the PayPal system to complete the payment.</strong></p>  <br />
        <!-- PayPal Logo -->
        <a href="https://www.paypal.com/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open('https://www.paypal.com/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=700, height=350'); return false;"><img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" border="0" alt="PayPal Logo" style="vertical-align:middle;text-decoration: underline;"> What is PayPal?</a>
        <br />
        <!-- PayPal Logo --> 
        <?php
        if(count($cat_item) <= 0)
        { ?> <br /> <br />
       There are no items in the cart.
       <?php 
        }else{ ?> <br /> <br />
      <!-- PAYPAL BUTTON -->
      <div class="paypal_button">
      <a style="cursor:pointer" data-type="1"  class="paypal">
        <img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" alt="Paypal Checkout" align="left" style="margin-right:7px;">
        <span></span>
      </a>
      </div>


      <div class="paypal_loader"><img src="/assets/images/paypal_load.gif"></div> 
      <!-- END OF PAYPAL BUTTON -->
      <?php
        } ?>
    <div style="clear:both"></div>
    <p class="notify">You will be notified regarding your order status via email or sms.</p>
    <!-- <p class="subscribe"><input type="checkbox" checked> <img src="<?= base_url() ?>assets/images/icon_email.png" alt="email"> Subscribe to Easyshop Newsletter for great deals and amazing discounts</p> -->
    <p class="chck_privacy"><input type="checkbox"> I acknowledge I have read and understood Easyshop.ph's <a href="">Privacy Policy</a>.</p>
  </div>

<!-- #### DRAGON PAY #### -->

    <div id="dragonpay" class="payment_inner_content">
 <input type="button" class="btnDp" value="Pay via DRAGON PAY">
    </div>


<!-- #### MORE PAYMENT HERE! #### -->

</div>

    <?php
  }else{
    ?>
        <br/>
        <span style='padding:8px; background-color: #FFEFEF; border: 1px solid #FF0000; font-size: 12px; font-weight:bold;'>One or more of your item(s) is unavailable in your location. </span>
    </div>
    
      <div class="order_sum_table">
        <h2>Order Summary</h2>
        <div class="order_sum_header">
          <div>Seller</div>
          <div>Items</div>
          <div>Quantity</div>
          <div>Price</div>
          <div>&nbsp;</div>
        </div>
        <div class="clear"></div>



      <?php 
  $total = 0; 
  foreach ($cat_item as $key => $value) {
    $total += $value['subtotal'];
    ?>
    <div class="order_sum_content order_sum_content2">
      <div><?php echo $value['seller_username'] ?></div>
      <div><?php echo $value['name'] ?></div>
      <div><?php echo $value['qty'] ?></div>
      <div><?php echo number_format($value['price'], 2, '.',',') ?></div>
     
     <?php if(!$value['availability']){ ?>
     <div class="error_shipping_address">
        <span>
          Please <a style="color:#0654BA" href="javascript:{}" class="link_address">change your shipping address</a>
          or remove this from your <a href="<?=base_url()?>cart" style="color:#0654BA">Cart</a>.
        </span>
     </div>
     <?php } ?>
    </div>

    <?php } ?>
      </div>
    <?php
  }
    ?>
    </div>

<!-- Order Summary Start -->
<?php
   if($success){
 ?>

<div class="order_summary">
  <h2>Order Summary</h2>
  <p>You have <?php echo count($cat_item);?> item in your cart</p>
  <div class="order_sum_title">
    <div>Product</div>
    <div>Quantity</div>
    <div>Shipping Fee</div>
    <div>Price</div> 
  </div>
  

  <?php 
  $total = 0;
  $shipping_fee = 0;

  foreach ($cat_item as $key => $value) {
    $total += $value['subtotal'] ;
    $shipping_fee += $value['shipping_fee'];
    ?>
    <div class="order_sum_content">
      <div class="sum_con_name"><?php echo $value['name'] ?></div>
      <div class="sum_con_qty"><?php echo $value['qty'] ?></div>
      <div class="sum_con_ship_fee"><?php echo number_format($value['shipping_fee'], 2, '.',',') ?></div>
      <div class="sum_con_price"><?php echo number_format($value['price'], 2, '.',',') ?></div> 
    </div>
    <?php } ?>


    <div class="order_sum_sub_total">
      <p>Subtotal: <span><?php echo number_format($total, 2, '.',','); ?></span></p>
      <p>Shipping fee: <span>Php <?php echo number_format($shipping_fee, 2, '.',','); ?></span></p>
      <p class="order_sum_total">Total: <span>Php <?php echo number_format($total + $shipping_fee, 2, '.',','); ?></span></p>
    </div>

  </div>
</div> 
  <?php 
}
   ?>
</div>
</section>

<div class="clear"></div>


<script type="text/javascript">
$(document).ready(function(){

    $('.paypal_loader').hide();
    $('.div_change_addree').hide();
    $('.paypal_button').show(); 

    var jsonCity = <?php echo $json_city;?>;
  

/************************** PROVINCE FILTER SELECT  **************************************/
/*
 *  Function to filter provinces in dropdown.
 */
  function cityFilter(stateregionselect,cityselect){
  var stateregionID = stateregionselect.find('option:selected').attr('value');
  var optionclone = cityselect.find('option.optionclone').clone();
  optionclone.removeClass('optionclone').addClass('echo').attr('disabled', false);
  cityselect.find('option.echo').remove();
  if(stateregionID in jsonCity){
    jQuery.each(jsonCity[stateregionID], function(k,v){
      //optionclone.attr('value', k).html(v).show();
      optionclone.attr('value', k).html(v).css('display', 'block');
      cityselect.append(optionclone.clone());
    });
  } 
  cityselect.trigger('chosen:updated');
}

 $('.stateregionselect').on('change', function(){
    var cityselect = $(this).parent('div').siblings('div').find('select.cityselect');
    cityselect.val(0);
    cityFilter( $(this), cityselect );
  });
  
});
</script>

<!-- PAYPAL FUNCTION -->
 <script type="text/javascript">
 
      $(document).on('click','.paypal',function () {
        var action = "pay/setting/paypal"; 
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var type = $(this).data('type');
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>' + action, 
            dataType: "json",
            data:   csrfname+"="+csrftoken+"&paypal="+type, 
            beforeSend: function(jqxhr, settings) { 
              $('.paypal_loader').show();
              $('.paypal_button').hide();
            },
            success: function(d) {
              if (d.e == 1) { 
                window.location.replace(d.d);
              } else {
                alert(d.d);
              }
              $('.paypal_loader').hide();
              $('.paypal_button').show();
            }
        });
    });

      $(document).on('click','.btnDp',function () {
        var action = "payment/payDragonPay"; 
             var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var type = $(this).data('type');
        $.ajax({
          type: "POST",
          url: '<?php echo base_url();?>' + action, 
          dataType: "json",
          data:   csrfname+"="+csrftoken, 
          beforeSend: function(jqxhr, settings) { 
           
          },
          success: function(d) {
            if (d.e == 1) { 
              window.location.replace(d.u);
            } else {
              alert(d.m);
            }
         
          }
        });
      });
 </script> 
<!-- END OF PAYPAL FUNCTION -->

<script type="text/javascript">
    $(document).on('click','.link_address',function () {
        $('.div_change_addree').modal({
              escClose: false,
              containerCss:{
                maxWidth: 900,
                minWidth: 605,
                maxHeight: 600,
              },
              persist: true
        });
        $('#simplemodal-container').addClass('div_change_addree');
    });

    $(document).on('click','.changeAddressBtn',function () {
        var action = "payment/changeAddress";
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        

        if($('.stateregionselect').val() == '0' || $('.cityselect').val() == '0' || $('.c_address').val().length == 0 || $('#c_mobile').val().length < 10){
          alert('Fill up Required Fields!');
          return false;
        }
    
        var formD = $('#delAddressFrm').serializeArray();
        formD.push({name:'temp_lat', value:0});
        formD.push({name:'temp_lng', value:0});
        formD.push({name:'map_lat', value:0});
        formD.push({name:'map_lng', value:0});
        formD.push({name:'c_deliver_address_btn', value:"Save"});
    
        

        $.ajax({
            type: "POST", 
            url: '<?php echo base_url();?>' + action,
            data:formD,
            dataType: "json", 
            success: function(d) {
              
              if(d == "success"){
                   location.reload();
              }else{
                //alert('Shipping address changed!');
             
                alert(d);
              }
            }
        });
    });
</script>

<div class="div_change_addree simplemodal-container">
  <h3>Change your Shipping Address</h3>
  
         <?php 
        $attr = array(
          'class' => 'delAddressFrm',
          'id' => 'delAddressFrm',
          'name' => 'delAddressFrm',
          'enctype' => 'multipart/form-data'
          );
        echo form_open('', $attr);
        ?>

      <div class="del_address">
        <div>
          <label >Consignee Name:<font color="red">*</font></label>           
          <input type="text" name="consignee" id="consignee" value="<?php echo $consignee?>">
        </div>
        <div>
          <label >Mobile No:<font color="red">*</font></label> 
          <input maxlength="10" placeholder="eg. 9051235678" type="text" name="c_mobile" id="c_mobile" value="<?php echo $c_mobile?>"> 
        </div>
        <div>
          <label  >Telephone No:</label>
          <input type="text" name="c_telephone" id="c_telephone" value="<?php echo $c_telephone?>">
        </div> 
        <div>
            <label>Address:</label>
        </div>
        <div>
            <label><span>State/Region:<font color="red">*</font></span></label>
            <select name="c_stateregion" class="address_dropdown stateregionselect" data-status="<?php echo $c_stateregionID?>">
              <option value="0">--- Select State/Region ---</option>
              <?php foreach($stateregion_lookup as $srkey=>$stateregion):?>
                <option class="echo" value="<?php echo $srkey?>" <?php echo $c_stateregionID == $srkey ? "selected":"" ?>><?php echo $stateregion?></option>
              <?php endforeach;?>
            </select>
        </div>
        <div>
            <label><span>City:<font color="red">*</font></span></label>               
              <select name="c_city" class="address_dropdown cityselect" data-status="<?php echo $c_cityID?>">
              <option value="0">--- Select City ---</option>
              <option class="optionclone" value="" style="display:none;" disabled></option>
              <?php foreach($city_lookup as $parentkey=>$arr):?>
                <?php foreach($arr as $lockey=>$city):?>
                  <option class="echo" value="<?php echo $lockey?>" data-parent="<?php echo $parentkey?>" <?php echo $c_cityID == $lockey ? "selected":"" ?> ><?php echo $city?></option>
                <?php endforeach;?>
              <?php endforeach;?>
            </select>
        </div>
        <div>
            <label><span>Country:<font color="red">*</font></span></label>            
            <select disabled>
                <option selected=""><?php echo $country_name?></option>
            </select>
            <input type="hidden" name="c_country" value="<?php echo $country_id?>">
        </div>
        <div>
            <label  >Full Address:<font color="red">*</font></label>
            <input type="text" name="c_address" class="c_address" value="<?php echo html_escape($c_address);?>">
        </div> 
        <div class="change_shipping_add_btn_con">
             <input type="button" value="Change Shipping Address" class="changeAddressBtn orange_btn3">
        </div> 
      </div>
       <?php echo form_close();?>
</div>


