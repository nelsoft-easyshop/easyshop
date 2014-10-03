
<!-- REVIEW SEO TAGS -->
<script type="application/ld+json">
    <?php echo $jsonReviewSchemaData;?>
</script>

<link rel="stylesheet" href="/assets/css/jquery.jqzoom.css?ver=<?=ES_FILE_VERSION?>" type="text/css">
<link rel="stylesheet" href="/assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/productview.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>

<link rel="canonical" href="<?php echo base_url()?>item/<?php echo $product['slug'];?>"/>

<div class="clear"></div>
<section class="top_margin product-page-section">
    <div class="wrapper">
        <div class="prod_categories">
            <div class="nav_title">Categories <img src="/assets/images/img_arrow_down.png"></div>
            <?php echo $category_navigation; ?>
        </div> 
        <div class="prob_cat_nav">
            <div class="category_nav product_content">
                <ul>
                <?php foreach($main_categories as $category): ?>
                    <li class = <?php echo ((isset($breadcrumbs[0]['id_cat']) &&  $category['id_cat'] === $breadcrumbs[0]['id_cat'])?"active":"");?>> <a href="<?=base_url()?>category/<?php echo $category['slug']?>"> <?php echo html_escape($category['name']);?> </a> </li>
                <?php endforeach;?>
                </ul>
                <span class="span_bg prod_cat_drop"></span>
            </div>
        </div>
        <div class="clear"></div>
        <div class="bread_crumbs">
            <ul>
                <li class=""><a href="<?=base_url()?>home">Home</a></li>
                <?php foreach($breadcrumbs as $crumbs): ?>
                <li> <a href="<?=base_url()?>category/<?php echo $crumbs['slug']?>"> <?php echo html_escape($crumbs['name']);?> </a> </li>
                <?php endforeach;?>
                <li class="bread_crumbs_last_child"><?php echo html_escape($product['product_name']);?></li>
            </ul>
        </div>
    </div>
</section>


<section>

    <div class="wrapper">
        <div class="">
            <div  id="product_content_gallery">
                <div class="cd_promo_badge_con">
                    <?php if($product['is_sold_out']): ?>
                        <span class="cd_soldout_product_page">
                            <img src="<?=base_url()?>assets/images/img_cd_soldout.png" alt="Sold Out">
                        </span>
                    <?php endif; ?>
            
                    <?php if(isset($product['percentage']) && $product['percentage'] > 0): ?>
                        <span class="cd_slide_discount">
                            <span><?php echo  number_format( $product['percentage'],0,'.',',');?>%<br>OFF</span>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="prod_con_gal"> 
                    <a href="<?=base_url()?><?php echo $product_images[0]['path']; ?> <?php echo $product_images[0]['file']; ?>" class="jqzoom" rel='gal1'  title="Easyshop.ph" > 
                        <img src="<?=base_url()?><?php echo $product_images[0]['path']; ?>small/<?php echo $product_images[0]['file']; ?>"  title="product">
                    </a> 
                </div>
                <br/>
                <div class="thumbnails_container">
                    <div class="jcarousel">
                        <ul id="thumblist">
                        <?php foreach($product_images as $image): ?>
                            <li> <a href="javascript:void(0);" rel="{gallery: 'gal1', smallimage: '<?=base_url()?><?php echo $image['path']; ?>small/<?php echo $image['file']; ?>',largeimage: '<?=base_url()?><?php echo $image['path']; ?><?php echo $image['file']; ?>'}"> <img src='<?=base_url()?><?php echo $image['path']; ?>thumbnail/<?php echo $image['file']; ?>'> </a> </li>
                        <?php endforeach;?>
                        </ul>
                    </div>
                    <!-- Controls -->
                    <a href="javascript:void(0)" class="jcarousel-control-prev inactive">&lsaquo;</a>
                    <a href="javascript:void(0)" class="jcarousel-control-next inactive">&rsaquo;</a>
                </div>
            </div>
      
            <?php echo $banner_view; ?>

            <div class="product_inner_content_info" >
    
                <h1 class="id-class" id="<?php echo $product['id_product'];?>"> 
                    <span id="pname"> <?php echo html_escape($product['product_name'])?> </span>
                </h1>
        
                <span class="seller-name"> 
                    <a href="/<?php echo $product['sellerslug'];?>"> 
                        <img class=" seller-img" src="/<?php echo $product['userpic']?>/60x60.png?<?php echo time();?>"><br />
                        <span class="name"><?php echo html_escape($product['sellerusername']);?></span> 
                    </a>
                    <br/>
                
                    <a class="modal_msg_launcher" href="javascript:void(0)" title="Send <?=html_escape($product['sellerusername'])?> a message">
                        <span>
                            <span class="span_bg prod_message"></span> 
                        </span>
                    
                        <br/>
                        <?php if(($vendorrating['rate_count'] <=0)):?>
                        <p><span style="font-size:11px; margin-left:8px;">No ratings received yet.</span></p>
                        <?php else:?>
                            <p><span class="rating_criteria"><?php echo $this->lang->line('rating')[0].':';?></span><span class="rating_value"><?php echo number_format($vendorrating['rating1'],2,'.',',');?></span> <img src="<?=base_url()?>assets/images/star-on.png" alt="*" title=""></p>
                            <p><span class="rating_criteria"><?php echo $this->lang->line('rating')[1].':';?></span><span class="rating_value" > <?php echo number_format($vendorrating['rating2'],2,'.',',');?> </span> <img src="<?=base_url()?>assets/images/star-on.png" alt="*" title=""></p>
                            <p><span class="rating_criteria"><?php echo $this->lang->line('rating')[2].':';?></span><span class="rating_value"> <?php echo number_format($vendorrating['rating3'],2,'.',',');?></span> <img src="<?=base_url()?>assets/images/star-on.png" alt="*" title=""></p>
                        <?php endif;?>
                    </a>            
                </span> 
            
                <div class="clear prod_inner_border"></div>
            
                <?php foreach($product_options as $key=>$product_option):?>
                    <?php if(count($product_option)>1): ?>
                        <div class="product_option"> 
                            <span><?php echo html_escape(str_replace("'", '', $key));?></span>
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
                    
                        <div class="product_option" style="display:none"> <span><?php echo html_escape(str_replace("'", '', $key));?></span>
                            <div>
                                <ul class="options" name="<?php echo str_replace("'", '', $key);?>">
                                    <li data-hidden="true" id="<?php echo html_escape($product_option[0]['value']);?>" data-price="<?php echo $product_option[0]['price'];?>" data-attrid="<?php echo $product_option[0]['value_id'];?>" data-type="<?php echo ($product_option[0]['type'] === 'specific')?0:1;?>"><?php echo html_escape($product_option[0]['value']);?></li>
                                </ul>
                            </div>
                        </div>

                    <?php endif; ?>
                    
                <?php endforeach;?>
            
                <!-- Quantity -->
                <div class="product_option"> <span>Quantity</span>
                    <div class="">
                        <input type="text" value ="0" class="product_quantity">
                    </div>
                </div>
            
                <div class="price_details">
                    <div class="price_box">
                        <div class="pbt pbt1">Price</div>
                        <div>
                      
                            <span class='currency' style ='display: <?php echo (floatval($product['price']) !== 0.01) ? 'inline':'none';  ?> '> PHP </span> 
                            
                            <span class="current_price fm1" data-baseprice="<?php echo $product['price']?>"> 
                                <?php echo (floatval($product['price']) !== 0.01) ? number_format($product['price'],2,'.',',') : 'FREE';?> 
                            </span> 
                        </div>
                        <?PHP if( ((intval($product['is_promote']) === 1) && $product['start_promo'] && !$product['end_promo'] && $product['percentage'] > 0)
                                || ((intval($product['is_promote']) === 0) && $product['discount'] > 0)): ?>   
                            <div><span class="recent_price"> PHP <?php echo number_format($product['original_price'],2,'.',','); ?></span> | <strong> <?php echo number_format( $product['percentage'],0,'.',',');?> % OFF  </strong></div>          
                        <?PHP endif;?>
                    </div>
                        
                        
                    <div class="availability">
                        <p> 
                            Availability <br />
                            <span class="quantity" data-qty="" data-default="false" id="p_availability"></span>
                        </p>
                    </div>
                    
                    <div class="buy_box">
                        
                        <?php if($logged_in && intval($userdetails['is_email_verify']) !== 1): ?>
                            <p class="buy_btn_sub"> Verify your email </p>
                        <?php elseif($logged_in && $uid == $product['sellerid']): ?>
                            <p class="buy_btn_sub"> This is your own listing </p>
                        <?php else: ?>
                            <?php if(count($shipment_information) === 0 && intval($product['is_meetup']) === 1): ?>
                                 <a href="javascript:void(0)" class="btn-meet-up modal_msg_launcher" title="Send <?=html_escape($product['sellerusername'])?> a message">Contact Seller</a> <br/>
                                <span>Item is listed as an ad only. *</span>
                            <?php elseif($product['promo_type'] == 6 && $product['start_promo'] == 1): ?>
                                <a href="javascript:void(0)" id='<?php echo $product['can_purchase']?'send':'' ?>_registration' class="fm1 orange_btn3 disabled">Buy Now</a> <br/>
                                <span>Click buy to qualify for the promo*</span>
                            <?php elseif(!$is_buy_button_viewable && intval($product['start_promo']) === 1) : ?>
                                <p class="buy_btn_sub"> This product is for promo use only. </p>
                            <?php else: ?>
                                 <a href="javascript:void(0)" id='<?php echo $product['can_purchase']?'send':'' ?>' class="fm1 orange_btn3 disabled">Buy Now</a> <br/>
                                <span>Delivers upon seller confirmation*</span>
                            <?php endif; ?>
                        
                           
                        <?php endif;?>
                        
                     
                    </div>

                </div>
            
                <div class="prod_loc_areas">
                    <p>
                        <strong class="location_message">Shipment Fee:</strong>
                        <select class="shiploc" id="shipment_locations">
                            <option class="default" selected="" value="0">Select Location</option>
                            <?php foreach($shiploc['area'] as $island=>$loc):?>
                                <option data-price="0" data-type="1" id="<?php echo 'locationID_'.$shiploc['islandkey'][$island];?>" value="<?php echo $shiploc['islandkey'][$island];?>" disabled><?php echo $island;?></option>
                                <?php foreach($loc as $region=>$subloc):?>
                                    <option data-price="0" data-type="2" id="<?php echo 'locationID_'.$shiploc['regionkey'][$region];?>" value="<?php echo $shiploc['regionkey'][$region];?>" style="margin-left:15px;" disabled>&nbsp;&nbsp;&nbsp;<?php echo $region;?></option>
                                    <?php foreach($subloc as $id_cityprov=>$cityprov):?>
                                        <option data-price="0" data-type="3" id="<?php echo 'locationID_'.$id_cityprov;?>" value="<?php echo $id_cityprov;?>" style="margin-left:30px;" disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cityprov;?></option>
                                    <?php endforeach;?>
                                <?php endforeach;?>
                            <?php endforeach;?>
                        </select>
                
                        <?php if($product['is_meetup'] && count($shipment_information) === 0):  ?>
                            <span class="shipping_fee"> <span class="loc_invalid"> Contact the seller * </span></span
                        <?php else:?>
                            <?PHP if($product['is_free_shipping']):  ?>
                                <span style="margin-left: 15px;"><span class="span_bg img_free_shipping"></span></span>
                            <?PHP else: ?>
                                <span class="shipping_fee"> <span class="loc_invalid"> Select location* </span></span>
                            <?PHP endif; ?>
                        <?php endif; ?>
                        
                        
                      
                    </p>
                </div>
            
                <p class="product_content_payment"> 
                
                    <strong>Payment:</strong><br />
                    <?php if(isset($payment_method['cdb'])): ?>
                        <span class="mastercard"></span>
                        <span class="visa"></span>
                    <?php endif; ?>
                
                    <?php if(isset($payment_method['dragonpay'])) : ?>
                        <span class="dragonpay"></span>
                    <?php endif; ?>
                
                    <?php if(isset($payment_method['paypal'])) : ?>
                        <span class="paypal"></span>
                    <?php endif; ?>
                    <?php if( isset($payment_method['cod']) && intval($product['is_cod'],10) === 1): ?>
                            <span class="cod"></span>
                    <?php endif; ?>
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
                    <p> 
                        <strong>Description: </strong>
                        <div class='html_description'>
                            <?php  
                                $clean_desc = html_purify($product['description']);
                                $us_ascii = mb_convert_encoding($clean_desc, 'HTML-ENTITIES', 'UTF-8');
                                $doc = new DOMDocument();
                                //@ = error message suppressor, just to be safe
                                @$doc->loadHTML($us_ascii);
                                $tags = $doc->getElementsByTagName('a');
                                foreach($tags as $a){
                                    $a->setAttribute('rel', 'nofollow');
                                }
                                echo @$doc->saveHTML($doc); 
                            ?>
                        </div>
                    </p>

                    <ul class="prod_details_list">
                        <li><strong>Brand: </strong><?php echo html_escape(ucfirst(strtolower($product['brand_name'])));?></li>
                        <li><strong>Additional description: </strong><?php echo html_escape($product['brief']);?></li>
                        <li><strong>Condition: </strong><?php echo html_escape($product['condition']);?></li>
                    </ul>
                </div>
                <div id="tabs-2">
                    <h3>Specifications of <?php echo html_escape($product['product_name']);?></h3>
                    <div> 
                        <span>SKU</span> <span><?php echo (strlen(trim($product['sku'])) > 0)?html_escape($product['sku']):'not specified';?></span>
                    </div>
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
                        <?php if($logged_in && $uid != $product['sellerid'] && in_array($uid,$allowed_reviewers) ):?>
                        <p class="write_review"> <img src="<?=base_url()?>assets/images/img_edit.png">Write a review </p>
                        <?php elseif($uid == $product['sellerid']): ?>
                        <p class=""><!-- Unable to review own product --></p>
                        <?php else: ?>
                        <p class="" style="color:#f18200;"><strong>Sign-in & purchase item to write a review</strong></p>
                        <?php endif; ?>
                    </div>
                    <div id="write_review_content">
                        <h3>Write a Review</h3>
                        <div id="review_container">
                            <?php $attr = array('id'=>'review_form'); ?>
                            <?php echo form_open('',$attr); ?>
                                <div>
                                    <label>Subject *</label>
                                    <input type="text" name="subject" maxlength="150" class="ui-form-control">
                                </div>
                                <div>
                                    <label>Rating</label>
                                    <div id="star"></div>
                                </div>
                                <div class="review-con-comment">
                                    <label>Comment *</label>
                                    <textarea name="comment" class="ui-form-control"></textarea>
                                </div>
                                <input type="submit" value="Submit" class="orange_btn" name="review_form">
                                <img src="<?=base_url()?>assets/images/bx_loader.gif" id="load_submitreview" style="position: relative; top:18px; left:30px; display:none"/>
                            <?php echo form_close(); ?>
                        </div>
                        <div class="clear"></div>
                        <div id="review_success_container" style="display:none">
                            <p> <img src="<?=base_url()?>assets/images/img_success.png"> </p>
                            <div style="">
                                <p><strong>Your review has been submitted. Reload the page to view your review. </strong></p>
                                <p><strong><a href="<?php echo base_url()?>item/<?php echo $product['slug'];?>">Click here to return to the product page.</a></strong></p>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="reviews_content">
                        <?php if(count($reviews) === 0): ?>
                            <div> 
                                <strong>This product has 0 reviews so far. Be the first to review it.</strong> 
                            </div>
                        <?php else: ?>
                            <input type="hidden" value="<?PHP echo end($reviews)['id_review']; ?>" id="lastreview"/>
                            <?php foreach($reviews as $review):?>
                                <div class="review_left_content">
                                    <h3><?php echo html_escape($review['title'])?></h3>
                                    <p><?php echo $review['reviewer']?> | <?php echo $review['datesubmitted']?></p>
                                    <p>Rating:
                                        <?php for($i = $review['rating'];$i>0;$i--):?>
                                            <img src="<?=base_url()?>assets/images/star-on.png" alt="*" title="">
                                        <?php endfor;?>
                                        <?php for($i = 5-$review['rating'];$i>0;$i--):?>
                                            <img src="<?=base_url()?>assets/images/star-off.png" alt="" title="">
                                        <?php endfor;?>
                                    </p>
                                </div>
                                
                                <div class="right_left_content">
                                    <p class="review_comment_content"><?php echo html_escape($review['review'])?></p>
                        
                                    <?php if($review['reply_count'] > 0):?>
                                        <?php $reply_counter = 0;?>
                                        <div class="reply_content_shown">
                                            <?php foreach($review['replies'] as $reply):?>
                                                <p> <strong><?php echo $reply['reviewer'];?></strong> on: <?php echo $reply['datesubmitted'];?> <br>
                                                "<?php echo html_escape($reply['review']);?>" </p>
                                                <?php $reply_counter++;?>
                                                <?php if($reply_counter == 3 && $review['reply_count'] > 3):?>
                                                    </div><div class="reply_content">
                                                <?php endif;?>
                                            <?php endforeach;?>
                                        </div>
                                    <?php endif;?>
                        
                                    <?php if($review['reply_count'] > 3):?>
                                        <p class="show_replies">Show more replies</p>
                                        <p class="hide_replies">Hide replies</p>
                                    <?php endif;?>
                        
                        
                        
                                    <?php if( ($uid == $product['sellerid'] || $review['is_reviewer'] == 1) && $logged_in ) : ?>
                                        <span class="reply_btn">Reply</span>
                                        <div class="reply_area">
                                            <!--<form method="post">-->
                                            <?php echo form_open(); ?>
                                            <input type="hidden" name="p_reviewid" value="<?php echo $review['id_review']?>">
                                            <input type="hidden" name="id_product" value="<?php echo $product['id_product']?>">
                                            <textarea class="reply_field" name="reply_field" cols=50 rows=4></textarea>
                                            <br>
                                            <span class="reply_save orange_btn3">Save</span> 
                                            <img src="<?=base_url()?>assets/images/orange_loader_small.gif" id="savereply_loadingimg" style="position: relative; top:12px; left:15px; display:none"/>
                                            <span class="reply_cancel">Cancel</span>
                                            <?php echo form_close();?>
                                        </div>
                                    <?php endif;?>
                                </div>
                    
    
                            <?php endforeach;?>
                            <div class="clear"></div>
                            <div class="review_last"> 
                                <span id="see_more_reviews" style="font-weight:bold;"><a href="">See more reviews.</a></span> 
                                <img src="<?=base_url()?>assets/images/orange_loader_small.gif" id="more_review_loading_img" style="position: relative; top:12px; left:15px; display:none; "/>
                                <br/><br/>
                            </diV>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
                
                
            <div class="recommendation_list">
                <h3>Recommended</h3>
                <ul>
                    <?PHP foreach ($recommended_items as $row): ?>                
                    <li>
                        <span class="rec_item_container">
                        <a href="<?=base_url()."item/".$row['slug'];?>" class="lnk_rec_item">
                            <img class="rec_item_img" src="<?=base_url().$row['path'].'categoryview/'.$row['file']?>">
                        </a>
                        </span>
                        <p>
                            <a href="<?=base_url()."item/".$row['slug'];?>">
                                <span class="prod_rec_item"><?php echo html_escape($row['product']);?></span>
                            </a><br />
                            <span class="price"> PHP <?=number_format($row['price'],2,'.',',');?></span>
                        </p>
                    </li>
                    <?PHP endforeach; ?>
                </ul>
            </div>
            
        </div>
    </div>



    <div class="clear"></div>
    
    <div id="modal-background"> </div>
    
    <div id="modal-container">
        <div id="modal-div-header">
            <button id="modal-close">X</button>        
        </div>
        <div id="modal-inside-container">
            <div>
                <label>To : </label>
                <input type="text" value="<?=$product['sellerusername'];?>" disabled id="msg_name" name="msg_name" class="ui-form-control" >
            </div>
            <div>
                <label>Message : </label>
                <textarea cols="40" rows="5" name="msg-message" id="msg-message" class="ui-form-control" placeholder="Say something.."></textarea>		
            </div>	   
        </div>
        <button id="modal_send_btn">Send</button>
    </div>
    
    <input id='p_qty' type='hidden' value=' <?php echo json_encode($product_quantity);?>'>
    <input id='p_shipment' type='hidden' value='<?php echo json_encode($shipment_information);?>'>
    <input id='p_itemid' type='hidden' value='0'/>
    
    <input id='seller-id' type='hidden' value='<?php echo $product['sellerid']; ?>'/>
    <input id='seller-username' type='hidden' value='<?php echo $product['sellerusername']; ?>'/>
    <input id='user-id' type='hidden' value='<?php echo empty($uid) ? 0 : $uid;?>' />
  
</section>

<script src="/assets/js/src/vendor/jquery.jqzoom-core.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.idTabs.min.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.raty.min.js" type="text/javascript"></script>
<script src="/assets/js/src/categorynavigation.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script src='/assets/js/src/vendor/jquery.numeric.js' type='text/javascript' ></script>
<script src='/assets/js/src/vendor/jquery.simplemodal.js'  type='text/javascript'></script>
<script src='/assets/js/src/vendor/jquery.validate.js'  type='text/javascript'></script>
<script src="/assets/js/src/productpage.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script>
