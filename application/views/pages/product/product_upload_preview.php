<?php if(!$modal): ?>
<link type="text/css" href="/assets/css/sell_item.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<link rel="stylesheet" href="/assets/css/product_preview.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>

<div id='previewProduct' style='width:1100px; margin-left: 10%; margin-top: 7%;'>

        <h2 class="f24">Sell an Item</h2>
        <div class="sell_steps sell_steps4">
            <ul>
              <li><a href="javascript:void(0)" id="step1_link">Step 1:Select Category</a></li>
              <li><a href="javascript:void(0)" id="step2_link">Step 2: Upload Item</a></li>                   
              <li><a href="javascript:void(0)" id="step3_link">Step 3: Select Shipping Courier</a></li>
              <li><a href="javascript:void(0)" ><span>Step 4:</span> Preview your listing</a></li>
            </ul>
        </div>
        
        <?php echo form_open('sell/step3', array('id'=>'edit_step3'));?>
            <input type="hidden" name="prod_h_id" id="prod_h_id" value="<?php echo $product['id_product'];?>">
        <?php echo form_close();?>
        
        <?php echo form_open('sell/edit/step2', array('id'=>'edit_step2'));?>
            <input type="hidden" name="p_id" id="p_id" value="<?php echo $product['id_product'];?>">
        <?php echo form_close();?>

        <?php echo form_open('sell/edit/step1', array('id'=>'edit_step1'));?>
            <input type="hidden" name="p_id" id="p_id" value="<?php echo $product['id_product'];?>">
        <?php echo form_close();?>
        
    <br/>
<?php endif; ?>
    
    <?php foreach($billing_info as $idx=>$x): ?>
        <?php if(count($x['products'])): ?>
            <div style='display:none; height: 600px; overflow-y:scroll;' class='acct_prod' data-bid='<?php echo $idx; ?>'>
                This account is currently in use for <strong><?php echo count($x['products']) ?></strong> products. Are you sure about this action?
                <br/><br/>
                <span style='font-size:10px;'>
                * All purchases made for the items listed below will still be linked to the original account. We will call you to confirm if you have made any changes within the
                current pay-out period before making a deposit. Should you wish to change the deposit account for any of your items, you can do it by editing your item listing.
                </span>
                
                <br/><br/>
                <?php foreach($x['products'] as $y): ?>
                    <div style='width:auto; height:20px;'><a href='/item/<?=$y['p_slug']?>'><span style='font-weight:bold'><?php echo html_escape($y['p_name']);?> - <?php echo date('m/d/Y', strtotime($y['p_date'])); ?></span> | <?php echo es_string_limit(html_escape($y['p_briefdesc']), 60);?></a></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>       
     <?php endforeach; ?>
    
    
    <div  style="position:relative; height:400px">
        <div class="paid_section_container">
            <h3 class="orange">How am I going to be paid</h3> 
            <label for="deposit_info">Deposit to: </label>
            <select id="deposit_info">
                <?php foreach($billing_info as $x): ?>
                    <option data-bankname="<?php echo html_escape($x['bank_name']);?>" data-bankid="<?php echo $x['bank_id'];?>" data-acctname="<?php echo  html_escape($x['bank_account_name']); ?>" data-acctno="<?php echo  html_escape($x['bank_account_number']); ?>"    value="<?php echo $x['id_billing_info'];?>"><?php echo  html_escape($x['payment_type']).': '. html_escape($x['bank_name']).' - '. html_escape($x['bank_account_name']);?></option>
                <?php endforeach; ?>
                <option value="0">ADD NEW PAYMENT ACCOUNT</option>
            </select><br/><br/>
            <?php $first_accnt = reset($billing_info);?>
            <label>Account name: </label><input name="deposit_acct_name" id="deposit_acct_name" type ="text" value="<?php echo  html_escape(isset($first_accnt['bank_account_name'])?$first_accnt['bank_account_name']:''); ?>"  <?php echo isset($first_accnt['bank_account_name'])?'readonly':''; ?>/>
            <label>Account number:</label><input name="deposit_acct_no" id="deposit_acct_no" type ="text" value="<?php echo  html_escape(isset($first_accnt['bank_account_number'])?$first_accnt['bank_account_number']:''); ?>" <?php echo isset($first_accnt['bank_account_number'])?'readonly':''; ?>/>
            <label>Bank:</label>
            <select id="bank_list" <?php echo (isset($first_accnt['bank_id']))?'disabled':'';?>>
                <option value="0">Please select a bank</option>
                <?php foreach($bank_list as $x): ?>
                    <?php if(isset($first_accnt['bank_id'])): ?>
                        <option value="<?php echo $x['id_bank'];?>" <?php echo (intval($x['id_bank'],10) === intval($first_accnt['bank_id'],10))?'selected':'';?>><?php echo  html_escape($x['bank_name']); ?></option>
                    <?php else: ?>
                        <option value="<?php echo $x['id_bank'];?>" ><?php echo  html_escape($x['bank_name']); ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <input type="hidden" id="bank_name" value="<?php echo  html_escape(isset($first_accnt['bank_name'])?$first_accnt['bank_name']:''); ?>"/>
            <input type="hidden" id="billing_info_id" value="<?php echo isset($first_accnt['id_billing_info'])?$first_accnt['id_billing_info']:'0'; ?>"/>
            
             <?php if(count($billing_info) > 0): ?>
                <span class="deposit_edit"><span class="span_bg"></span>Edit</span>
                <span class="deposit_save" style="display:none">Save</span>
             <?php else: ?>
                <span class="deposit_edit" style="display:none">Edit</span>
                <span class="deposit_save">Save</span>
             <?php endif; ?>
            
            <span class="deposit_update" style="display:none">Update</span>
            <span class="deposit_cancel" style="display:none">Cancel</span>
           

            <input type="hidden" id="temp_deposit_acct_name" value=""/>
            <input type="hidden" id="temp_deposit_acct_no" value=""/>
            <input type="hidden" id="temp_bank_list" value=""/>
            <input type="hidden" id="temp_bank_name" value=""/>
            <br/>
            <div style="position:relative;">
                <input type="checkbox" id="allow_cashondelivery" name="allow_cashondelivery" style="position:absolute; top:29px">
                <label for="allow_cashondelivery" > <span class="orange" style="position:absolute; top:26px; left: 20px;"><strong>Allow </strong></span> <span class="cod" style="position:absolute; left: 50px;"></span> </label> 
                <a class="tooltips" href="javascript:void(0)" style="position:absolute; top:20px; left:150px;">
                    <img src="<?php echo getAssetsDomain(); ?>assets/images/icon_qmark.png" alt="">
                    <span class="1line_tooltip">Payment for items that are paid with the Cash on Delivery option are not covered by Easyshop.ph</span>
                </a> 
            </div>
            
            <br/> <br/> <br/>
        </div>
        <h3 class="orange" style="padding:8px 0px;">Product Preview</h3>
        <div style="max-height:100%; overflow-y:auto;border:1px solid #f48000;">
            <div class="clear"></div>
            <section class="top_margin">
              <div class="wrapper">
                <div class="prod_categories">
                  <div class="nav_title">Categories </div>
                </div>
                <div class="prob_cat_nav">
                  <div class="category_nav product_content">
                    <ul>
                      <?php foreach($main_categories as $category): ?>
                      <li class = <?php echo (($category['id_cat'] === $breadcrumbs[0]['id_cat'])?"active":"");?>> <a href="javascript:void(0);"> <?php echo $category['name'];?> </a> </li>
                      <?php endforeach;?>
                    </ul>
                    <span class="span_bg prod_cat_drop"></span>
                  </div>
                </div>
                <div class="clear"></div>
                <div class="bread_crumbs">
                  <ul>
                    <li class=""><a href="javascript:void(0);">Home</a></li>
                    <?php foreach($breadcrumbs as $crumbs): ?>
                    <li> <a href="javascript:void(0);"> <?php echo $crumbs['name']?> </a> </li>
                    <?php endforeach;?>
                    <li class="bread_crumbs_last_child"><?php echo html_escape($product['product_name']);?></li>
                  </ul>
                </div>
              </div>
            </section>
            <section>
                <div class="wrapper">
                    <div class="content_wrapper">
                        <div  id="product_content_gallery">
                            <div class="prod_con_gal"> <a class="jqzoom"  href="javascript:void"> <img src="<?php echo getAssetsDomain(); ?><?php echo $product_images[0]['path']; ?>small/<?php echo $product_images[0]['file']; ?>"  title="product"> </a> </div>
                            <br/>
                            
                            
                            <div class="thumbnails_container">
                              <div class="jcarousel">
                                <ul id="thumblist">
                                  <?php foreach($product_images as $image): ?>
                                  <li> <a href="javascript:void(0);"> <img src='<?php echo getAssetsDomain(); ?><?php echo $image['path']; ?>thumbnail/<?php echo $image['file']; ?>'> </a> </li>
                                  <?php endforeach;?>
                                </ul>

                              </div>
                                <!-- Controls -->
                                <a href="javascript:void(0)" class="jcarousel-control-prev inactive">&lsaquo;</a>
                                <a href="javascript:void(0)" class="jcarousel-control-next inactive">&rsaquo;</a>
                            </div>
                        </div>
                        
                    </div>
             
                
                <div class="product_inner_content_info">
                <h1 class="id-class" id="<?php echo $product['id_product'];?>"> 
                  <span id="pname"> <?php echo html_escape($product['product_name'])?> </span> 
                  <span class="seller-name"> 
                      <img src="<?php echo getAssetsDomain(); ?><?php echo $product['userpic']?>/60x60.png"><br />
                      <span><?php echo html_escape($product['sellerusername']);?></span> 
                      <p style='font-size:8px;'>No ratings received.</p>
                  </span> 
                </h1>
                <div class="clear prod_inner_border"></div>
                
                <?php foreach($product_options as $key=>$product_option):?>
                    <?php if(count($product_option)>1): ?>
                    <div class="product_option_preview"> <span><?php echo html_escape(str_replace("'", '', $key));?></span>
                      <div>
                        <ul class="options" name="<?php echo str_replace("'", '', $key);?>">
                          <?php foreach($product_option as $i):?>
                          <?php if((trim($i['img_path'])!=='')&&(trim($i['img_file'])!=='')): ?>       
                          <a href="#" rel="{gallery: 'gal1', smallimage: '/<?php echo $i['img_path'].'small/'.$i['img_file']; ?>',largeimage: '/<?php echo $i['img_path'].$i['img_file']; ?>'}">
                          <?php endif; ?>
                          <li class="" id="<?php echo html_escape($i['value']);?>" data-price="<?php echo $i['price'];?>" data-attrid="<?php echo $i['value_id'];?>" data-type="<?php echo ($i['type'] === 'specific')?0:1;?>"><?php echo html_escape($i['value']);?></li>
                          <?php if((trim($i['img_path'])!=='')&&(trim($i['img_file'])!=='')): ?>
                          </a>
                          <?php endif; ?>
                          <?php endforeach;?>
                        </ul>
                      </div>
                    </div>
                    <!-- Only echo a hidden attribute if the attribute datatype is a checkbox or an optional attribute -->
                    <?php elseif((count($product_option) === 1)&&(($product_option[0]['datatype'] === '5'))||($product_option[0]['type'] === 'option')):  ?>
                    
                        <div class="product_option_preview" style="display:none"> <span><?php echo html_escape(str_replace("'", '', $key));?></span>
                            <div>
                                <ul class="options" name="<?php echo str_replace("'", '', $key);?>">
                                    <li data-hidden="true" id="<?php echo html_escape($product_option[0]['value']);?>" data-price="<?php echo $product_option[0]['price'];?>" data-attrid="<?php echo $product_option[0]['value_id'];?>" data-type="<?php echo ($product_option[0]['type'] === 'specific')?0:1;?>"><?php echo html_escape($product_option[0]['value']);?></li>
                                </ul>
                            </div>
                        </div>

                    <?php endif; ?>
                <?php endforeach;?>
                
                <!-- Quantity -->
                <div class="product_option_preview"> <span>Quantity</span>
                  <div class="">
                    <input type="text" value ="1" class="product_quantity" disabled>
                  </div>
                </div>
                <div class="price_details">
                
                
     
                <div class="price_box">
                <div class="pbt pbt1">Price</div>
                    <div>PHP 
                        <span class="current_price fm1" data-baseprice="<?php echo $product['price']?>"> 
                            <?php echo number_format($product['price'],2,'.',',');?> 
                        </span> 
                    </div>
                    <?PHP if((intval($product['is_promote']) === 1) || ($product['discount'] > 0)): ?>   
                        <div><span class="recent_price"> PHP <?php echo number_format($product['original_price'],2,'.',','); ?></span> | <strong> <?php echo number_format( $product['percentage'],0,'.',',');?> % OFF  </strong></div>          
                    <?PHP endif;?>
                </div>
                  
                  
                  <div class="availability">
                    <p> Availability <br />
                        <span class="quantity" id="p_availability"><?php echo $availability;?></span>
                    </p>
                  </div>
                  <div class="buy_box"> 
                    <a href="JavaScript:void(0)" id="send" class="fm1 preview_buy_btn disabled">Buy Now</a> <br/>
                    <span>Delivers in 5-8 business days*</span> </div>
                </div>
                <p class="product_content_payment"> <strong>Payment:</strong><br />
                  <span class="mastercard"></span>
                  <span class="visa"></span>
                  <span class="paypal"></span>
                </p>
              </div>
              <div class="clear"></div>
              
              <div id="tabs">
                <ul>
                  <li><a href="#tabs-1">Product Details</a></li>
                  <li><a href="#tabs-2">Specification</a></li>
                  <li><a href="#tabs-3">Reviews</a></li>
                </ul>
                
                <div class="clear"></div>
               
                <div id="tabs-1">
                  <ul>
                    <p> <strong>Description: </strong><?php echo html_purify($product['description']);?> </p>
                    <li><strong>Brand: </strong><?php echo html_escape(ucfirst(strtolower($product['brand_name'])));?></li>
                    <li><strong>Additional description: </strong><?php echo html_escape($product['brief']);?></li>
                    <li><strong>Condition: </strong><?php echo html_escape($product['condition']);?></li
                  </ul>
                </div>
                <div id="tabs-2">
                  <h3>Specifications of <?php echo html_escape($product['product_name']);?></h3>
                  <div> <span>SKU</span> <span><?php echo html_escape($product['sku']);?></span> </div>
                  <?php foreach($product_options as $key=>$product_option):?>
                  <?php if(count($product_option)===1): ?>
                      <?php if(intval($product_option[0]['datatype'],10) === 2): ?>
                            <div class="tab2_html_con">
                                <strong><?php echo html_escape(str_replace("'", '', $key));?> </strong>
                                <?php echo html_purify($product_option[0]['value']);?>
                            </div>
                       <?php else: ?>   
                            <div> 
                                <span><?php echo html_escape(str_replace("'", '', $key));?></span> 
                                <span><?php echo html_escape($product_option[0]['value']);?></span>
                            </div>
                       <?php endif; ?>
                  <?php endif; ?>
                  <?php endforeach;?>
                </div>
                <div id="tabs-3">
                  <div class="reviews_title">
                    <h3>Product reviews</h3>
                      <p class="write_review"> <img src="<?php echo getAssetsDomain(); ?>assets/images/img_edit.png">Write a review </p>
                  </div>
                  <div class="reviews_content">
                    <div> <strong>This product has 0 reviews so far. Be the first to review it.</strong> </div>
                  </div>
                </div>
              </div>
              
              <div class="recommendation_list">
                    <h3>Recommended</h3>
               </div>
              
              </div>
              <div class="clear"></div>
            </section>

        </div>
    </div>
    <input type='hidden' name='modal' id='modal' value="<?php echo ($modal)?'1':'0'; ?>"/>

<?php if(!$modal): ?>
    <div style='height:230px'>
    </div>
    <span id="previewSubmit" class="orange_btn3">Proceed</span>
    
    <?php echo form_open('sell/step4', array('id'=>'step4_form'));?>
		<input type="hidden" name="prod_h_id" id="prod_h_id" value="<?php echo $product['id_product'];?>">
        <input type="hidden" name="prod_billing_id" id="prod_billing_id" value="0">
        <input type="checkbox" name="allow_cod" id="allow_cod" style="display:none">
	<?php echo form_close();?>
    
</div>
<script src="/assets/js/src/vendor/jquery.idTabs.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/src/productUpload_preview.js?ver=<?=ES_FILE_VERSION?>"></script>

<?php endif;?>
