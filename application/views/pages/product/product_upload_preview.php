<link rel="stylesheet" href="<?=base_url()?>assets/css/product_preview.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.bxslider.css" type="text/css" media="screen"/>

<div  style="position:relative; height:400px">

    <div>
        <strong> How am I going to be paid </strong> <br/>
        <label for="deposit_info">Deposit to: </label>
        <select id="deposit_info">
            <?php foreach($billing_info as $x): ?>
                <option data-bankname="<?php echo $x['bank_name']?>" data-bankid="<?php echo $x['bank_id'];?>" data-acctname="<?php echo $x['bank_account_name']; ?>" data-acctno="<?php echo $x['bank_account_number']; ?>"    value="<?php echo $x['id_billing_info'];?>"><?php echo $x['payment_type'].': '.$x['bank_name'].' - '.$x['bank_account_name'];?></option>
            <?php endforeach; ?>
            <option value="0">ADD NEW PAYMENT ACCOUNT</option>
        </select><br/><br/>
        <label>Account name: </label><input name="deposit_acct_name" id="deposit_acct_name" type ="text" value="<?php echo isset($billing_info[0]['bank_account_name'])?$billing_info[0]['bank_account_name']:''; ?>"  <?php echo isset($billing_info[0]['bank_account_name'])?'readonly':''; ?>/>
        <label>Account number:</label><input name="deposit_acct_no" id="deposit_acct_no" type ="text" value="<?php echo isset($billing_info[0]['bank_account_number'])?$billing_info[0]['bank_account_number']:''; ?>" <?php echo isset($billing_info[0]['bank_account_number'])?'readonly':''; ?>/>
        <label>Bank:</label>
        <select id="bank_list" <?php echo (isset($billing_info[0]['bank_id']))?'disabled':'';?>>
            <option value="0">Please select a bank</option>
            <?php foreach($bank_list as $x): ?>
                <?php if(isset($billing_info[0]['bank_id'])): ?>
                    <option value="<?php echo $x['id_bank'];?>" <?php echo (intval($x['id_bank'],10) === intval($billing_info[0]['bank_id'],10))?'selected':'';?>><?php echo $x['bank_name']; ?></option>
                <?php else: ?>
                    <option value="<?php echo $x['id_bank'];?>" ><?php echo $x['bank_name']; ?></option>
                <?php endif; ?>
               
            <?php endforeach; ?>
        </select>
        <input type="hidden" id="bank_name" value="<?php echo isset($billing_info[0]['bank_name'])?$billing_info[0]['bank_name']:''; ?>"/>
        <input type="hidden" id="billing_info_id" value="<?php echo isset($billing_info[0]['id_billing_info'])?$billing_info[0]['id_billing_info']:'0'; ?>"/>
        
         <?php if(count($billing_info) > 0): ?>
            <span class="deposit_edit">Edit</span>
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
        <input type="hidden" id="preview_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
    </div>
    <strong>Product Preview</strong>
    <div style="max-height:100%;overflow-y:auto;border:1px solid #f48000;">
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
                        <div class="prod_con_gal"> <a class="jqzoom"  href="javascript:void"> <img src="<?=base_url()?><?php echo $product_images[0]['path']; ?>small/<?php echo $product_images[0]['file']; ?>"  title="product"> </a> </div>
                        <br/>
                        
                        
                        <div class="thumbnails_container">
                          <div class="jcarousel">
                            <ul id="thumblist">
                              <?php foreach($product_images as $image): ?>
                              <li> <a href="javascript:void(0);"> <img src='<?=base_url()?><?php echo $image['path']; ?>thumbnail/<?php echo $image['file']; ?>'> </a> </li>
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
                  <img src="<?php echo base_url() . $product['userpic']?>/60x60.png"><br />
                  <span><?php echo html_escape($product['sellerusername']);?></span> 
                  <p>No ratings received.</p>
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
                      <a href="#" rel="{gallery: 'gal1', smallimage: '<?=base_url()?><?php echo $i['img_path'].'small/'.$i['img_file']; ?>',largeimage: '<?=base_url()?><?php echo $i['img_path'].$i['img_file']; ?>'}">
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
                <input type="text" value ="1" class="product_quantity">
              </div>
            </div>
            <div class="price_details">
              <div class="price_box">
                <div class="pbt pbt1">Price</div>
                <div>PHP <span class="current_price fm1" data-baseprice="<?php echo $product['price']?>"> <?php echo number_format($product['price'],2,'.',',');?> </span> </div>
              </div>
              <div class="availability">
                <p> Availability <br />
                    <span class="quantity" id="p_availability"><?php echo $availability;?></span>
                </p>
              </div>
              <div class="buy_box"> 
                <input type="hidden" id="buynow_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
                <a href="JavaScript:void(0)" id="send" class="fm1 orange_btn_preview disabled">Buy Now</a> <br/>
                <span>Delivers in 5-8 business days*</span> </div>
            </div>
            <p class="product_content_payment"> <strong>Payment:</strong><br />
              <span class="mastercard"></span>
              <span class="visa"></span>
              <span class="jcb"></span>
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
                  <p class="write_review"> <img src="<?=base_url()?>assets/images/img_edit.png">Write a review </p>
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

<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery-ui.js"></script>
<script src="<?=base_url()?>assets/JavaScript/js/jquery.idTabs.min.js" type="text/javascript"></script>

