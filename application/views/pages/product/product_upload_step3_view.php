
<link type="text/css" href="<?=base_url()?>assets/css/sell_item.css" rel="stylesheet" />

<div class="wrapper">
  <div class="clear"></div>

  <div class="seller_product_content">
 
    <div class="inner_seller_product_content">
      <h2 class="f24">Sell an Item</h2>
      <input type="hidden" id="uploadstep1_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
            <div class="sell_steps sell_steps3">
                <ul>
                    <li><a href="javascript:void(0)" id="step1_link">Step 1 : Select Category</a></li>
                    <li><a href="javascript:void(0)" id="step2_link">Step 2 : Upload Item</a></li>                   
                    <li>Step 3 : Select Shipping Locations</li>
                    <li>Step 4 : Success</li>
                </ul>
            </div>
    <div class="clear"></div>
    
    <?php echo form_open('sell/edit/step2', array('id'=>'edit_step2'));?>
        <input type="hidden" name="p_id" id="p_id" value="<?php echo $product_id;?>">
    <?php echo form_close();?>

    <?php echo form_open('sell/edit/step1', array('id'=>'edit_step1'));?>
        <input type="hidden" name="p_id" id="p_id" value="<?php echo $product_id;?>">
    <?php echo form_close();?>
    
    <!-- Start of top Shipping Content -->   
    <div class="shipping_wrapper">
    <!-- Start of Shipping Container --> 
    <div class="shipping_container">
	  <?php if($attr['has_attr'] == 1):?>
		  <table>
		  <tr>
			<td><strong class="f14">Select Product Attribute Combination, Location, and Price</strong></td>
		  </tr>
		  <tr>
			<td>
			  <ul id="product_combination_list">				
				<?php foreach($attr['attributes'] as $attrkey=>$temp):?>
				  <li class="product_combination" value="<?php echo $attrkey;?>">
					
					  <?php foreach($temp as $pattr):?>
						<p>&bull; <?php echo $pattr;?> </p>
					  <?php endforeach;?>
				   
				  </li>
				<?php endforeach;?>
			  </ul>
			</td>
		  </tr>
		  </table>
	  <?php else:?>
		<span><strong class="f14">Select Delivery Location and Price</strong></span>
		<input type="hidden" id="product_item_id" value="<?php echo $attr['product_item_id'];?>">
	  <?php endif;?>
	  <input type="hidden" id="has_attr" value="<?php echo $attr['has_attr'];?>">
	  
      <div class="shipping_border"></div>
	  
      <table id="shiploc_selectiontbl" class="shipping_table2" width="526px" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <td width="170px">Location</td>
            <td width="230px">Price</td>
            <td width="136px">&nbsp;</td>
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
            <a href="javascript:void(0)" id="add_location" class="blue">+ Add Location</a>
          </td>
        </tr>
      </table>
      <input type="button" id="add_shipping_details" value="Add to Shipping List" class="orange_btn3">
    </div>   
    <!-- End of Shipping Container --> 

    <!-- Start of Shipping Courier -->
    <div class="shipping_courier_container">

      <a target="_blank" href="http://www.air21.com.ph/main/rate_calculator.php"><img src="<?=base_url()?>assets/images/img_logo_air21.jpg"> Air21</a>
      <a target="_blank" href="http://www.lbcexpress.com/"><img src="<?=base_url()?>assets/images/img_logo_lbc.jpg"> LBC</a>
      <a target="_blank" href="http://www.jrs-express.com/Ratecalc.aspx"><img src="<?=base_url()?>assets/images/img_logo_jrs.jpg"> JRS</a>
    </div>
    <!-- End of Shipping Courier -->

    </div>
    <!-- End of top Shipping Content -->
    
	<div class="clear"></div>

    <!-- start of shipping summary -->
    <div class="shipping_summary_container">
    <h2 class="f20">Shipping Summary</h2>
	  <input type="hidden" id="shippingsummary_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
    <table id="shipping_summary" class="<?php echo $shipping_summary['has_shippingsummary'] ? "" : "tablehide"?> shipping_table3" width="980px" cellspacing="0" cellpadding="0">
		
	  <thead>
        <tr>
          <td width="415px" class="f14">Product Attribute Combinations</td>
          <td><span class="f14">Location</span><span class="f14">Price</span></td>
          <td></td>
        </tr>
      </thead>

	<?php $datagroupcounter = 0; ?>
	<?php if($shipping_summary['has_shippingsummary']):?>
		<?php foreach($attr["attributes"] as $attk=>$temp): ?>
		  <tr class="tr_shipping_summary" data-group="<?php echo $datagroupcounter;?>">
			<td class="prod_att_pad">
				<?php if($attr['has_attr'] == 1):?>
					<?php foreach($temp as $att):?>
						<p><?php echo $att;?></p>
					<?php endforeach;?>
				<?php else:?>
						<p>All Attribute Combinations</p>
				<?php endif;?>
			</td>
			<td width="350px">
			  <table class="shiplocprice_summary">
				<tbody>
				  <?php foreach($shipping_summary[$attk] as $lockey=>$price):?>
				  <tr data-idlocation="<?php echo $lockey?>" data-groupkey="<?php echo $datagroupcounter?>">
					<td width="100px"><?php echo $shipping_summary['location'][$lockey]?></td>
					<td width="170px" data-value="<?php echo number_format((float)$price,2,'.',',');?>"><?php echo number_format((float)$price,2,'.',',');?></td>
					<td class="tablehide">
					  <span class="button delete_priceloc">
						<img src="<?php echo base_url();?>assets/images/icon_delete.png"> Delete
					  </span>
					</td>
				  </tr>
				  <?php endforeach;?>
				  <!-- Cloning Field-->
				  <tr class="cloningfield" data-idlocation="" data-groupkey="">
					<td width="100px"></td>
					<td width="170px" data-value=""></td>
					<td class="tablehide">
					  <span class="button delete_priceloc">
						<img src="<?php echo base_url();?>assets/images/icon_delete.png"> Delete
					  </span>
					</td>
				  </tr>
				  <!-- END OF Cloning Field-->
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
		<?php $datagroupcounter++; ?>
		<?php endforeach;?>
	<?php endif;?>
	  
	<!-- Original Cloning Field -->
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
	<!-- CLOSE Original Cloning Field -->
	
		<input type="hidden" id="json_displaygroup" value='<?php echo $json_displaygroup;?>'>
		<input type="hidden" id="json_locationgroup" value='<?php echo $json_locationgroup;?>'>
		<input type="hidden" id="json_islandlookup" value='<?php echo $json_islandlookup;?>'>
		<input type="hidden" id="json_fdata" value='<?php echo $json_fdata;?>'>
		<input type="hidden" id="json_id_product_item" value='<?php echo $json_id_product_item;?>'>
		<input type="hidden" id="summaryrowcount" value="<?php echo $datagroupcounter?>">
	</table>
    </div>
	
	<div style="color:red;<?php echo $inc_location ? '' : 'display:none;'?>" id="div_locationwarning">
		Warning: Your shipping location selection does not cover the whole
		<span id="location_warning">
			<?php echo $inc_location ? $inc_locationmsg : '';?>
		</span>
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
