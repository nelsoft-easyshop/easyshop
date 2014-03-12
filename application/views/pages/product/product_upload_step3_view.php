
<link type="text/css" href="<?=base_url()?>assets/css/sell_item.css" rel="stylesheet" />

<div class="wrapper">


  <div class="clear"></div>

  <!--<div class="tab_list">
     <p><a href="">Iam a Buyer</a></p> 
    <p class="active"><a href="">Iam a Seller</a></p>
  </div>
  <div class="clear"></div>-->
  <div class="seller_product_content">
   <!-- <div class="top_nav">
      <ul>
        <li>
           
          <a href="">
            <img src="<?=base_url()?>assets/images/img_signup.png" alt="signup"><br />
            <span>Account Sign-in</span>
          </a>
         
        </li>
        <li>
            
          <a href="">
            <img src="<?=base_url()?>assets/images/img_shop.png" alt="shop"><br />
            <span>Whant to Shop</span>
          </a>
         
        </li>
        <li>
         
          <a href="">
            <img src="<?=base_url()?>assets/images/img_setup.png" alt="setup"><br />
            <span>Shop exam and set up shop</span>
          </a>
          
        </li>
        <li>
          
          <a href="">
            <img src="<?=base_url()?>assets/images/img_publish.png" alt="publish"><br />
            <span>Published Baby</span>
          </a>
         
        </li>
        <li>
          
          <a href="">
            <img src="<?=base_url()?>assets/images/img_delivery.png" alt="delivery"><br />
            <span>Delivery Operation</span>
          </a>
         
        </li>
        <li>
          
          <a href="">
            <img src="<?=base_url()?>assets/images/img_ratings.png" alt="ratings"><br />
            <span>Ratings &amp; Withdrawals</span>
          </a>
         
        </li>
      </ul>
    </div> -->
    <div class="inner_seller_product_content">
      <h2 class="f24">Sell an Item</h2>
      <input type="hidden" id="uploadstep1_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
            <div class="sell_steps sell_steps3">
                <ul>
                    <li><a href="#">Step 1 : Select Category</a></li>
                    <li><a href="#">Step 2 : Upload Item</a></li>                   
                    <li><a href="#">Step 3 : Select Shipping Locations</a></li>
                    <li><a href="#">Step 4 : Success</a></li>
                </ul>
            </div>
    <div class="clear"></div>
    <!-- Content -->
	
    <div class="shipping_container">
      <table>
      <tr>
        <td><strong>Product Attribute Combinations</strong></td>
      </tr>
      <tr>
        <td>
          <ul id="product_combination_list">
            <?php foreach($attr as $attrkey=>$temp):?>
              <li class="product_combination" value="<?php echo $attrkey;?>">
              <?php foreach($temp as $pattr):?>
                <?php echo $pattr;?> &nbsp;
              <?php endforeach;?>
              </li>
            <?php endforeach;?>
          </ul>
        </td>
      </tr>
      </table>

      <table id="shiploc_selectiontbl" class="shipping_table2" width="790px" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <td width="170px">Location</td>
            <td width="200px">Price</td>
            <td width="242px">&nbsp;</td>
          </tr>
        </thead>
        <input type="hidden" value="1" id="shiploc_count">
        <tr>
          <td>
            <select name="shiploc1" class="shiploc">
              <option selected="" value="0">Select Location</option>
              <?php foreach($shiploc['area'] as $island=>$loc):?>
                <option value="<?php echo $shiploc['islandkey'][$island];?>"><?php echo $island;?></option>
                <?php foreach($loc as $region=>$subloc):?>
                  <option value="<?php echo $shiploc['regionkey'][$region];?>" style="margin-left:15px;">&nbsp;&nbsp;&nbsp;<?php echo $region;?></option>
                  <?php foreach($subloc as $id_cityprov=>$cityprov):?>
					<option value="<?php echo $id_cityprov;?>" style="margin-left:30px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cityprov;?></option>
				  <?php endforeach;?>
                <?php endforeach;?>
              <?php endforeach;?>
            </select>
          </td>
          <td>
            Php <input type="text" name="shipprice1" class="shipprice">
          </td>
        </tr>
        <tr>
          <td>
            <a href="javascript:void(0)" id="add_location">+ Add Location</a>
          </td>
        </tr>
      </table>
      <input type="button" id="add_shipping_details" value="Add to Shipping List" class="grey_btn">
    </div>   

    <div class="clear"></div>

    <!-- start of shipping summary -->
    <div class="shipping_summary_container">
    <h2 class="f20">Shipping Summary</h2>
	  <input type="hidden" id="shippingsummary_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
    <table id="shipping_summary" class="tablehide shipping_table3" width="980px" cellspacing="0" cellpadding="0">
      <input type="hidden" id="summaryrowcount" value="0">
      
      <thead>
        <tr>
          <td width="415px" class="f14">Product Attribute Combinations</td>
          <td><span class="f14">Location</span><span class="f14">Price</span></td>
          <td></td>
          
        </tr>
      </thead>

      <tr class="cloningfield">
        <td class="prod_att_pad">
        </td>
        <td width="350px">
          <table class="shiplocprice_summary">
            <tbody>
              <tr class="cloningfield" data-idlocation="" data-groupkey="">
                <td width="100px"></td>
                <td width="170px" data-value=""></td>
                <td class="tablehide">
                  <span class="button delete_priceloc">
                    <img src="<?php echo base_url();?>assets/images/icon_delete.png"> Delete
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
        <td width="200px">
          <span class="edit_summaryrow button edit_del">
            <img src="<?php echo base_url();?>assets/images/icon_edit.png"> Edit
          </span>
          <span class="delete_summaryrow button edit_del">
            <img src="<?php echo base_url();?>assets/images/icon_delete.png"> Delete
          </span>
          <span class="accept_summaryrow buttonhide button accept_cancel">
            <img src="<?php echo base_url();?>assets/images/check_icon.png"> Accept
          </span>
        </td>
      </tr>
    </table>
    </div>
    <!-- end of shipping summary -->
    <span id="btnShippingDetailsSubmit" class="tablehide orange_btn3">Submit</span>
	 
	<?php echo form_open('sell/step4', array('id'=>'step4_form'));?>
		<input type="hidden" name="prod_h_id" id="prod_h_id" value="<?php echo $product_id;?>">
	<?php echo form_close();?>
    
  </div>
</div>

<div class="clear"></div>  

<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/productUpload_step3.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/jquery.numeric.js"></script>
