 
<link rel="stylesheet" href="<?=base_url()?>assets/css/my_cart_css.css" type="text/css" media="screen"/>
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
  <div class="wrapper">
    <h2 class="checkout_title">Payment</h2>

    <div>
      <div><b><?php echo ucwords(strtolower($consignee));?></b></div>
      <div> <?php echo ucwords(strtolower($c_address));?></div>
      <div><?php echo ucwords(strtolower($c_city));?></div>
      <div> <?php echo ucwords(strtolower($country_name));?></div>
      <div> <?php echo ucwords(strtolower($c_mobile));?></div>
      <div> <?php echo ucwords(strtolower($c_telephone));?></div>
    </div>
[ <a style="color:#0654BA;"  href="javascript:void(0);"  class="link_address">Change Shipping Address</a> ]
<hr>
    <div class="payment_wrapper">

  <?php
  if($success){
    ?>

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

 
    <?php
  }else{
    ?>
      <div>
      <?php 
  $total = 0; 
  foreach ($cat_item as $key => $value) {
    $total += $value['subtotal'];
    ?>
    <div class="order_sum_content">
      <div><?php echo $value['seller_username'] ?></div>
      <div><?php echo $value['name'] ?></div>
      <div><?php echo $value['qty'] ?></div>
      <div><?php echo number_format($value['price'], 2, '.',',') ?></div>
     
     <?php if(!$value['availability']){ ?>
     <div style="color:red">
       Please <a style="color:#0654BA" href="javascript:{}" class="link_address">change your shipping address</a> or go to remove this from your <a style="color:#0654BA">Cart</a>.
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
    provinceFilter('');

    $('.cityselect').on('change', function(){
        $(this).parent('div').siblings('div').find('select.provinceselect').val(0);
        provinceFilter( $(this) );
    });

/************************** PROVINCE FILTER SELECT  **************************************/
/*
 *  Function to filter provinces in dropdown.
 */
    function provinceFilter(cityselect){

        if ( cityselect == '' ){
          cityselect = $('select.cityselect');
        }

        cityselect.each(function(k,v){
            var selectvalue = $(v).find(':selected').attr('value');
            var provinceoption = $(v).parent('div').siblings('div').find('select.provinceselect option.echo');
            provinceoption.each(function(){
                if($(this).attr('data-parent') != selectvalue){
                    $(this).hide();
                    $(this).attr('disabled',true);
                }
                else{
                    $(this).show();
                    $(this).attr('disabled',false);
                }
            });
        });
    }

});
</script>

<!-- PAYPAL FUNCTION -->
 <script type="text/javascript">
 
      $(document).on('click','.paypal',function () {
        var action = "payment/paypal_setexpresscheckout";
        var csrftoken = $('input[name="es_csrf_token"]').val();
        var csrftoken = "<?php echo $my_csrf['csrf_hash'];?>";

        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>' + action, 
            dataType: "json",
            data:   "es_csrf_token="+csrftoken, 
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
        var action = "memberpage/edit_consignee_address";
        var csrftoken = "<?php echo $my_csrf['csrf_hash'];?>";
 
        var formD = $('#delAddressFrm').serializeArray();
        formD.push({name:'map_lat', value:0});
        formD.push({name:'map_lng', value:0});
        formD.push({name:'c_deliver_address_btn', value:"save"});
    
        $.ajax({
            type: "POST", 
            url: '<?php echo base_url();?>' + action,
            data:formD,
            dataType: "json", 
            success: function(d) {
              if(d == ""){
                alert('Please Double Check your Details');
              }else{
                alert('Shipping address changed!');
                location.reload();
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
          <label >Consignee Name:</label>
          <div >    
            <input type="text" name="consignee" id="consignee" value="<?php echo $consignee?>">
          </div>
        </div>
        <div>
          <label >Mobile No:</label>
          <div >
            <input maxlength="10" placeholder="eg. 9051235678" type="text" name="c_mobile" id="c_mobile" value="<?php echo $c_mobile?>">
          </div>
        </div>
        <div>
          <label  >Telephone No:</label>
          <div >
            <input type="text" name="c_telephone" id="c_telephone" value="<?php echo $c_telephone?>">
          </div>
        </div> 
        <div>
            <label>Address:</label>
            <div >
                <div>
                    City:  
                    <select name="c_city" class="address_dropdown cityselect" data-status="<?php echo $c_cityID?>">
                        <option value="0">--- Select City ---</option>
                        <?php foreach($city_lookup as $ckey=>$city):?>
                        <option class="echo" value="<?php echo $ckey?>" <?php echo $c_cityID == $ckey ? "selected":"" ?>><?php echo $city?></option>
                        <?php endforeach;?>
                    </select>
                </div>  
                <div>
                    Province:   
                    <select name="c_province" class="address_dropdown provinceselect" data-status="<?php echo $c_provinceID?>">
                        <option value="0">--- Select Province ---</option>
                        <?php foreach($province_lookup as $parentkey=>$arr):?>
                        <?php foreach($arr as $lockey=>$province):?>
                        <option class="echo" value="<?php echo $lockey?>" data-parent="<?php echo $parentkey?>" <?php echo $c_provinceID == $lockey ? "selected":"" ?> ><?php echo $province?></option>
                        <?php endforeach;?>
                        <?php endforeach;?>
                    </select>
                </div> 
                <div>
                    Country:
                    <select disabled>
                        <option selected=""><?php echo $country_name?></option>
                    </select>
                    <input type="hidden" name="c_country" value="<?php echo $country_id?>">
                </div> 
            </div>
        </div>
        <div>
            <label  >Full Address:</label>
            <div >
              <input type="text" name="c_address" value="<?php echo $c_address?>">
            </div>
        </div> 
        <div>
             <input type="button" value="Change Shipping Address" class="changeAddressBtn">
        </div> 
      </div>
       <?php echo form_close();?>
</div>


