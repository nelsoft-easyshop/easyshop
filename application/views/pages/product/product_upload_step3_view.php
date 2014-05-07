
<link type="text/css" href="<?=base_url()?>assets/css/sell_item.css?ver=1.0" rel="stylesheet" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/product_preview.css?ver=1.0" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.bxslider.css?ver=1.0" type="text/css" media="screen"/>
<link  type="text/css"  href='<?=base_url()?>assets/css/product_upload_tutorial.css?ver=1.0' rel="stylesheet" media='screen'/>

<div class="wrapper">
  <div class="seller_product_content">
 
    <div class="inner_seller_product_content">
      <h2 class="f24">Sell an Item</h2>
        
        <a id="tutShippingLoc" class="tooltips" href="javascript:void(0)" style='text-decoration:underline'>
             <img src="<?= base_url() ?>assets/images/icon_qmark.png" alt="">  
             What's this?
             <span>Click here to read more. Your progress will not be lost. </span>
        </a>
        
            <div class="sell_steps sell_steps3">
                <ul>
                    <li><a href="javascript:void(0)" id="step1_link">Step 1 : Select Category</a></li>
                    <li><a href="javascript:void(0)" id="step2_link">Step 2 : Upload Item</a></li>                   
                    <li><span>Step 3</span> : Select Shipping Locations</li>
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
    
    <!-- Start of Shipping Content -->   
    <div class="wrapper shipping_wrapper">
      <div class="shipping_title">
        <strong class="f14">Shipment Options</strong>
      </div>
      <!-- Start of Shipping Container --> 
      <div class="shipping_container">
      	
      		  <table class="shipping_prod_attr_comb" cellspacing="0" cellpadding="0" width="465px">
              <thead>
                <tr>
                  <td class="step3_title"><h4>Select from available combinations</h4></td>
                </tr>
              </thead>
      		  <tr>
      			<td class="border-left border-right border-bottom">
              <div class="prod_comb_list_con">
                      <?php if($attr['has_attr'] == 1):?>
                          <ul id="product_combination_list">				
                            <?php foreach($attr['attributes'] as $attrkey=>$temp):?>
                              <li class="product_combination" value="<?php echo $attrkey;?>">
                                <div>
                                  <?php foreach($temp as $pattr):?>
                                    <p>&bull; <?php echo $pattr;?> </p>
                                  <?php endforeach;?>
                                </div> 
                              </li>
                            <?php endforeach;?>
                          </ul>
                      <?php else:?>
                         <ul id="product_combination_list">
                            <li class="product_combination_locked">&bull; All Combinations</li>
                         </ul>
                         <input type="hidden" id="product_item_id" value="<?php echo $attr['product_item_id'];?>">

                      <?php endif;?>
              </div>
      			</td>
      		  </tr>
      		  </table>
  	      
        
          
            <input type="hidden" id="has_attr" value="<?php echo $attr['has_attr'];?>">
      
            <!-- <div class="shipping_border"></div> -->
          <!-- Start of Shipping Courier -->
          <div class="shipping_courier_container">
            <table cellspacing="0" cellpadding="0">
                <thead>
                  <tr>
                     <td colspan="3" class="step3_title">
                         <h4>Set shipment fee</h4>
                    </td>
                  </tr>
                </thead>
                <td class="border-left">

                    <table id="shiploc_selectiontbl" class="shipping_table2" width="463px" cellspacing="0" cellpadding="0">
                      <input type="hidden" value="1" id="shiploc_count">
                    <tbody>
                        <tr>
                            <td width="170px">Location</td>
                            <td width="175px">Price</td>
                            <td width="108px">&nbsp;</td>
                        </tr>
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
                            <td colspan="2" class="add_loc_con">
                                <a href="javascript:void(0)" id="add_location" class="grey_btn">+ Add another location</a>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <table cellspacing="0" cellpadding="0" width="463px" class="price_table_bottom">
                      <tbody>
                        <tr>
                          <td class="border-right set_price_error1" height="45px">
                            <p style="display:none; color:red;" id="spanerror">
                               Location already used for selected attribute.
                            </p>
                            
                          </td>
                        </tr>
                        <tr>
                          <td class="border-right">
                              <input type="button" id="add_shipping_details" value="Add to Shipping List" class="orange_btn3">
                          </td>
                        </tr>
                        <tr>
                          <td class="border-right border-bottom set_price_error2" height="50px">
                            <div style="font-weight:bold; color:green;<?php echo $inc_location ? '' : 'display:none;'?>" id="div_locationwarning">
                              Note: Your shipment location does not cover the entire
                              <span id="location_warning">
                                <?php echo $inc_location ? $inc_locationmsg : '';?>
                              </span>
                              area
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </td>
            </table>
            
            <table cellspacing="0" cellpadding="0" width="465px" style="margin-top:15px;">
                <thead>
                  <tr>
                     <td colspan="3" class="step3_title">
                         <h3>Rate Calculator</h3>
                    </td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border-left border-right border-bottom">
                      <div class="rate_calculator_con">
                          <a target="_blank" href="http://www.air21.com.ph/main/rate_calculator.php">
                            <img src="<?=base_url()?>assets/images/img_logo_air21.jpg" alt="Air21">
                          </a>
                          <a target="_blank" href="http://www.lbcexpress.com/">
                            <img src="<?=base_url()?>assets/images/img_logo_lbc.jpg" alt="LBC">
                          </a>
                          <a target="_blank" href="http://www.jrs-express.com/Ratecalc.aspx">
                            <img src="<?=base_url()?>assets/images/img_logo_jrs.jpg" alt="JRS">
                          </a>
                      </div> 
                    </td>
                  </tr>
                </tbody>        
            </table>  
          </div>
          <!-- End of Shipping Courier -->

      </div>   
      <!-- End of Shipping Container --> 

      <!-- start of shipping summary -->
      <div class="shipping_summary_container">
          <h2 class='f20'>Shipment Summary</h2>
          <table class="" cellspacing="0" cellpadding="0">
               <thead>
                    <tr>
                      <td width="128px" class="f12">Combination</td>
                      <td width="80px"><span class="f12">Location</span></td>
                      <td width="257px"><span class="f12">Price</span></td>     
                    </tr>
                </thead>
            </table>
            <div class="shipping_list_items_con">
            
            <table id="shipping_summary" class="<?php echo $shipping_summary['has_shippingsummary'] ? "" : "tablehide"?> shipping_table3" width="auto" cellspacing="0" cellpadding="0"> 
                <?php $datagroupcounter = 0; ?>
                   <?php if($shipping_summary['has_shippingsummary']):?>
                      <?php foreach($attr["attributes"] as $attk=>$temp): ?>
                      <tr class="tr_shipping_summary" data-group="<?php echo $datagroupcounter;?>">
                          <td class="prod_att_pad" valign="top">
                            <?php if($attr['has_attr'] == 1):?>
                              <?php foreach($temp as $att):?>
                                <p><?php echo $att;?></p>
                              <?php endforeach;?>
                            <?php else:?>
                                <p>All Combinations</p>
                            <?php endif;?>
                          </td>
                          <td width="230px" valign="top">
                              <table class="shiplocprice_summary">
                                  <tbody>
                                    <?php foreach($shipping_summary[$attk] as $lockey=>$price):?>
                                        <tr data-idlocation="<?php echo $lockey?>" data-groupkey="<?php echo $datagroupcounter?>">
                                            <td width="85px"><?php echo $shipping_summary['location'][$lockey]?></td>
                                            <td width="55px" data-value="<?php echo number_format((float)$price,2,'.',',');?>"><?php echo number_format((float)$price,2,'.',',');?></td>
                                            <td width="100px" class="tablehide">
                                              <span class="button delete_priceloc">
                                              <img src="<?php echo base_url();?>assets/images/icon_delete.png"> Delete
                                              </span>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                    <!-- Cloning Field-->
                                        <tr class="cloningfield" data-idlocation="" data-groupkey="">
                                            <td width="85px"></td>
                                            <td width="55px" data-value=""></td>
                                            <td width="100px" class="tablehide">
                                                <span class="button delete_priceloc">
                                                <img src="<?php echo base_url();?>assets/images/icon_delete.png"> Delete
                                                </span>
                                            </td>
                                        </tr>
                                    <!-- END OF Cloning Field-->
                                  </tbody>
                              </table>
                          </td>
                          <td width="110px" valign="top">
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
                    <td class="prod_att_pad"></td>
                    <td width="230px" valign="top">
                        <table class="shiplocprice_summary">
                          <tbody>
                            <tr class="cloningfield" data-idlocation="" data-groupkey="">
                              <td width="90px"></td>
                              <td width="75px" data-value=""></td>
                              <td class="tablehide">
                                <span class="button delete_priceloc">
                                  <img src="<?php echo base_url();?>assets/images/icon_delete.png"> Delete
                                </span>
                              </td>
                            </tr>

                          </tbody>
                        </table>
                    </td>
                    <td width="110px" valign="top" class="shipping_list_btns">
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
         
      </div>
      <!-- end of shipping summary -->

      <div class="clear"></div>
      <span id="btnShippingDetailsSubmit" class="tablehide orange_btn3">Submit</span>
      <img src="<?=base_url()?>/assets/images/orange_loader.gif" class="loading_img_step3" style="display:none; margin-left:470px;"/>

    </div>
    <!-- End of Shipping Content -->
    	 
	 <?php echo form_open('sell/step4', array('id'=>'step4_form'));?>
		  <input type="hidden" name="prod_h_id" id="prod_h_id" value="<?php echo $product_id;?>">
          <input type="hidden" name="prod_billing_id" id="prod_billing_id" value="0">
          <input type="checkbox" name="allow_cod" id="allow_cod" style="display:none">
	 <?php echo form_close();?>
     
     <?php echo form_open('sell/preview', array('id'=>'nonmodal_preview'));?>
          <input type="hidden" name="p_id" id="p_id" value="<?php echo $product_id;?>">
          <input type="hidden" name="modal" id="modal" value="false">
     <?php echo form_close();?>
     
   </div> 
  </div>
</div>

<div class="clear"></div> 

<div id="previewProduct" style="display:none"></div>

<div id="div_tutShippingLoc" class="tutorial_modal" style="display:none; width: 100%;">
	<div class="paging">
		<div class="p_title">
		  <h2>Set Shipment Options</h2>
		</div>
		<div class="p_content">
		  <p class='h_strong'>You can set here the location(s) where you are willing to ship your item and the corresponding shipment fee. You may set the shipment details for each of the combinations you have provided in the previous step.</p>
            
        </div>
        <div class='shipment_container'>
            <div class='shipment_divider'>
                <span style="display:block;"><p style='font-weight:bold'>No item detail combinations were provided during the previous step.</p></span>
                <img src="<?=base_url()?>assets/images/tutorial/prd_upload_step3/attr_combination_def.png" alt="No Attribute Combinations.png">
            </div>
            <div class='shipment_divider'>
                <span style="display:block;"><p style='font-weight:bold'>Item detail combinations were provided during the previous step.</p></span>
                <img src="<?=base_url()?>assets/images/tutorial/prd_upload_step3/attr_combination_opt.png" alt="With Attribute Combinations.png">
            </div>
        </div>
	</div>
	<div class="paging">
		<div class="p_title">
		  <h2>Set Shipment Options</h2>
		</div>
		<div class="p_content">
		  <p class='h_strong'>
            You can now assign different locations and shipment fee for the combination(s) that you have selected.
            You may click on <strong>'Add another location'</strong> to add as many location and shipment fee fields as you need.
            Once ready, click on <strong>'Add to shipping list'</strong> to add the shipping details to the summary section.
          </p>
		  <div class='shipment_container'><img src="<?=base_url()?>assets/images/tutorial/prd_upload_step3/loc_price.png" style='margin: 5px;'></div>
		</div>
	</div>
	<div class="paging">
		<div class="p_title">
		  <h2>Set Shipment Options</h2>
		</div>
		<div class="p_content">
		  <p class='h_strong'>All of your added shipment information is listed in the shipment summary. You may click on <strong>'Edit'</strong> to change the shipping price, or <strong>'Delete'</strong> to delete the entry.  </p>
		  <div class='shipment_container'><img src="<?=base_url()?>assets/images/tutorial/prd_upload_step3/shipping_summary.png" alt="With Attribute Combinations.png" style='margin: 10px;'></div>
		  <p style='font-weight:bold'>Shipment option set for a certain region applies to all the locations within that region. However, if a more specific location is included, the shipment fee for the more specific location will be used. A link to the rate calculators of different courier services are provided at the bottom of the page.</p>
		
        </div>
	</div>
	
	<div class="pagination" id="paging_tutShippingLoc">
		<a href="#" class="first" data-action="first">&laquo;</a>
		<a href="#" class="previous" data-action="previous">&lsaquo;</a>
		<input type="text" readonly="readonly" data-max-page="3" />
		<a href="#" class="next" data-action="next">&rsaquo;</a>
		<a href="#" class="last" data-action="last">&raquo;</a>
	</div>

    
</div>


<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/jquery.numeric.js"></script>
<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery-ui.js"></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.jqpagination.min.js'></script>
<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery.simplemodal.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/productUpload_step3.js?ver=1.0"></script>
<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/productUpload_preview.js?ver=1.0"></script>
<script src="<?=base_url()?>assets/JavaScript/js/jquery.idTabs.min.js" type="text/javascript"></script>
<script type="text/javascript">


   $(".product_combination").each(function() {
    if ($(this).find("p").length > 6) {
      $(this).css('overflow-y','scroll');
    }
  });
</script>
