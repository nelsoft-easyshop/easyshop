
<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/productUpload_step3.js"></script>

<div class="wrapper">


  <div class="clear"></div>

  <div class="tab_list">
    <!-- <p><a href="">Iam a Buyer</a></p> -->
    <p class="active"><a href="">Iam a Seller</a></p>
  </div>
  <div class="clear"></div>
  <div class="seller_product_content">
    <div class="top_nav">
      <ul>
        <li>
            <!--
          <a href="">
            <img src="<?=base_url()?>assets/images/img_signup.png" alt="signup"><br />
            <span>Account Sign-in</span>
          </a>
          -->
        </li>
        <li>
            <!--
          <a href="">
            <img src="<?=base_url()?>assets/images/img_shop.png" alt="shop"><br />
            <span>Whant to Shop</span>
          </a>
          -->
        </li>
        <li>
          <!--
          <a href="">
            <img src="<?=base_url()?>assets/images/img_setup.png" alt="setup"><br />
            <span>Shop exam and set up shop</span>
          </a>
          -->
        </li>
        <li>
          <!--
          <a href="">
            <img src="<?=base_url()?>assets/images/img_publish.png" alt="publish"><br />
            <span>Published Baby</span>
          </a>
          -->
        </li>
        <li>
          <!--
          <a href="">
            <img src="<?=base_url()?>assets/images/img_delivery.png" alt="delivery"><br />
            <span>Delivery Operation</span>
          </a>
          -->
        </li>
        <li>
          <!--
          <a href="">
            <img src="<?=base_url()?>assets/images/img_ratings.png" alt="ratings"><br />
            <span>Ratings &amp; Withdrawals</span>
          </a>
          -->
        </li>
      </ul>
    </div>
    <div class="inner_seller_product_content">
      <div class="steps">
        <ul>
         <li class="steps_item"><a href="#">1</a></li>
         <li><img src="<?=base_url()?>assets/images/img_dotted.png" alt="trail"></li>
         <li class="steps_item"><a href="#">2</a></li>
         <li><img src="<?=base_url()?>assets/images/img_dotted.png" alt="trail"></li>
         <li class="steps_item steps_acitve"><a href="#" class=""><span>step</span><br />3</a></li>
         <li><img src="<?=base_url()?>assets/images/img_dotted.png" alt="trail"></li>
       </ul>
     </div>

    <!-- Content -->

    <div>
      
      <table>
        <tr>
          <td>
            Shipping Service
          </td>
          <td>
            <select name="shipService1" id="shipService1"><option selected="" value="0">-</option>
              <optgroup label="Economy services">
              <option value="14">    Economy Shipping</option>
              <option value="8">    USPS Parcel Select</option>
              <option value="32">    USPS Standard Post</option>
              <option value="9">    USPS Media Mail</option>
              <option value="63">    FedEx SmartPost</option>
              </optgroup> 
              <optgroup label="Standard services">
              <option value="1">    Standard Shipping</option>
              <option value="3">    UPS Ground</option>
              <option value="10">    USPS First Class Package</option>
              <option value="62">    FedEx Ground or FedEx Home Delivery</option>
              </optgroup>
              <optgroup label="Expedited services">
              <option value="2">    Expedited Shipping</option>
              <option value="7">    USPS Priority Mail</option>
              <option value="19">    USPS Priority Mail Flat Rate Envelope</option>
              <option value="23">    USPS Priority Mail Small Flat Rate Box</option>
              <option value="20">    USPS Priority Mail Medium Flat Rate Box</option>
              <option value="22">    USPS Priority Mail Large Flat Rate Box</option>
              <option value="24">    USPS Priority Mail Padded Flat Rate Envelope</option>
              <option value="25">    USPS Priority Mail Legal Flat Rate Envelope</option>
              <option value="4">    UPS 3 Day Select</option>
              <option value="5">    UPS 2nd Day Air</option>
              <option value="64">    FedEx Express Saver</option>
              <option value="65">    FedEx 2Day</option>
              </optgroup>
              <optgroup label="One-day services">
              <option value="18">    One-day Shipping</option>
              <option value="11">    USPS Priority Mail Express</option>
              <option value="21">    USPS Priority Mail Express Flat Rate Envelope</option>
              <option value="26">    USPS Priority Mail Express Legal Flat Rate Envelope</option>
              <option value="6">    UPS Next Day Air Saver</option>
              <option value="12">    UPS Next Day Air</option>
              <option value="66">    FedEx Priority Overnight</option>
              <option value="67">    FedEx Standard Overnight</option>
              </optgroup>
              <optgroup label="Other services">
              <option value="161">    Economy Shipping from outside US</option>
              <option value="162">    Standard Shipping from outside US</option>
              <option value="163">    Expedited Shipping from outside US</option>
              <option value="167">    FedEx International Economy</option>
              <option value="150">    Local Pickup</option>
              </optgroup>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            Estimated Weight (g)
          </td>
          <td>
            <input type="text" size="28" name="weight">
          </td>
        </tr>
<!--
        <tr>
          <td>
            Dimension
          </td> 
          <td>
            <input type="text" placeholder="Length" size="5">  x
             <input type="text" placeholder="Width" size="5"> x
           <input type="text" placeholder="Height" size="5"> 
          </td>
          <tr>
             <td>
            Total
          </td>
          <td>
            <input type="text" size="28">
          </td>
          </tr> 
        </tr>
-->
      </table>

      <table>
      <tr>
        <td>Product Attribute Combinations</td>
        <td>
          <ul id="product_combination_list">
            <?php foreach($attr as $attrkey=>$temp):?>
              <li class="product_combination" value="<?php echo $attrkey;?>">
              <?php foreach($temp as $pattr):?>
                <?php echo $pattr;?> &nbsp
              <?php endforeach;?>
              </li>
            <?php endforeach;?>
          </ul>
        </td>
      </tr>
      </table>

      <table id="shiploc_selectiontbl">
        <tr>
          <td>Location</td>
          <td>Price</td>
        </tr>
        <input type="hidden" value="1" id="shiploc_count">
        <tr>
          <td>
            <select name="shiploc1" class="shiploc">
              <option selected="" value="0">Select Location</option>
              <?php foreach($shiploc['area'] as $island=>$loc):?>
                <option value="<?php echo $shiploc['islandkey'][$island];?>"><?php echo $island;?></option>
                <?php foreach($loc as $region=>$subloc):?>
                  <option value="<?php echo $shiploc['regionkey'][$region];?>"><?php echo $region;?></option>
                  <?php foreach($subloc as $id_cityprov=>$cityprov):?>
                    <option value="<?php echo $id_cityprov;?>"><?php echo $cityprov;?></option>
                  <?php endforeach;?>
                <?php endforeach;?>
              <?php endforeach;?>
            </select>
          </td>
          <td>
            ₱<input type="text" name="shipprice1" class="shipprice">
          </td>
        </tr>
        <tr>
          <td>
            <a href="javascript:void(0)" id="add_location">+ Add Location</a>
          </td>
        </tr>
      </table>
      <input type="button" id="add_shipping_details" value="Add to Shipping List">
    </div>   

    <div class="clear"><br></div>

    <h2>Shipping Summary</h2>
	<input type="hidden" id="shippingsummary_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
    <table id="shipping_summary" class="tablehide">
      <input type="hidden" id="summaryrowcount" value="0">
	  
      <tr class="cloningfield">
        <td>
        </td>
        <td>
          <table class="shiplocprice_summary">
            <tbody>
              <tr class="cloningfield" data-idlocation="" data-groupkey="">
                <td></td>
                <td data-value=""></td>
                <td class="tablehide">
                  <span class="button delete_priceloc">
                    <img src="<?php echo base_url();?>assets/images/icon_delete.png"> Delete
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
        <td style="border:none;">
          <span class="edit_summaryrow button edit_del">
            <img src="<?php echo base_url();?>assets/images/icon_edit.png"> Edit
          </span>
          <span class="delete_summaryrow button edit_del">
            <img src="<?php echo base_url();?>assets/images/icon_delete.png"> Delete
          </span>
          <span class="accept_summaryrow buttonhide button accept_cancel">
            <img src="<?php echo base_url();?>assets/images/check_icon.png"> Accept
          </span>
          <span class="cancel_summaryrow buttonhide button accept_cancel">
            <img src="<?php echo base_url();?>assets/images/x_icon.png"> Cancel
          </span>
        </td>
      </tr>
    </table>
    <span id="btnShippingDetailsSubmit" class="tablehide">Submit</span>

  </div>
</div>

<div class="clear"></div>  

