<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.jqzoom.css" type="text/css">
<link rel="stylesheet" href="<?=base_url()?>assets/css/style_new.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.bxslider.css" type="text/css" media="screen"/>

<div class="clear"></div>
<section class="top_margin">
  <div class="wrapper">
    <div class="prod_categories">
      <div class="nav_title">Categories <img src="<?=base_url()?>assets/images/img_arrow_down.png"></div>
      <?php echo $category_navigation; ?>
    </div>
    <div class="prob_cat_nav">
      <div class="category_nav product_content">
        <ul>
          <?php foreach($main_categories as $category): ?>
          <li class = <?php echo (($category['id_cat'] === $breadcrumbs[0]['id_cat'])?"active":"");?>> <a href="<?=base_url()?>category/<?php echo $category['id_cat'];?>/<?php echo es_url_clean($category['name']); ?>.html"> <?php echo $category['name'];?> </a> </li>
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
        <li> <a href="<?=base_url()?>category/<?php echo $crumbs['id_cat']?>/<?php echo es_url_clean($crumbs['name']);?>.html"> <?php echo $crumbs['name']?> </a> </li>
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
        <div class="prod_con_gal"> <a href="<?=base_url()?><?php echo $product_images[0]['path']; ?><?php echo $product_images[0]['file']; ?>" class="jqzoom" rel='gal1'  title="Brand: <?php echo $product['brand_name'];?>" > <img src="<?=base_url()?><?php echo $product_images[0]['path']; ?>small/<?php echo $product_images[0]['file']; ?>"  title="product"> </a> </div>
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
      <div class="product_inner_content_info">
        <h1 class="id-class" id="<?php echo $product['id_product'];?>"> 
          <span id="pname"> <?php echo html_escape($product['product_name'])?> </span> 
          <span class="seller-name"> 
            <a href="<?php echo base_url() . 'vendor/' . $product['sellerusername'];?>"> 
              <img src="<?php echo base_url() . $product['userpic']?>/60x60.png"><br />
              <span><?php echo html_escape($product['sellerusername']);?></span> 
            </a>
            <?php if($vendorrating['rate_count'] <=0):?>
              <p>No ratings received.</p>
            <?php else:?>
              <p> Rating 1: <?php echo $vendorrating['rating1'];?></p>
              <p> Rating 2: <?php echo $vendorrating['rating2'];?></p>
              <p> Rating 3: <?php echo $vendorrating['rating3'];?></p>
            <?php endif;?>
          </span> 
        </h1>
        <div class="clear prod_inner_border"></div>
        
        <?php foreach($product_options as $key=>$product_option):?>
            <?php if(count($product_option)>1): ?>
            <div class="product_option"> <span><?php echo html_escape(str_replace("'", '', $key));?></span>
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
            <div>PHP <span class="current_price fm1" data-baseprice="<?php echo $product['price']?>"> <?php echo number_format($product['price'],2,'.',',');?> </span> </div>
          </div>
          <div class="availability">
            <p> Availability <br />
                <span class="quantity" data-qty="" data-default="false" id="p_availability"></span>
            </p>
          </div>
          <div class="buy_box"> 
            <?PHP if($logged_in && $userdetails['is_email_verify'] == 1 ): ?>
                <?php if($uid == $product['sellerid']): ?>
                     <p class="buy_btn_sub"> This is your own listing </p>
                <?php else: ?>
                    <input type="hidden" id="buynow_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
                    <a href="JavaScript:void(0)" id="send" class="fm1 orange_btn3 disabled">Buy Now</a> <br/>
                <?php endif;?>
            <?php else: ?>
            <a href="<?PHP echo base_url();echo $logged_in?'me':'login';?>" id="unablesend" class="add_to_cart"><span></span> Buy Now</a> <br />
			<p class="buy_btn_sub">
				<?php if( !$logged_in && $userdetails['is_email_verify'] == 1 ){
					echo "Sign-in to purchase this product.";
				} else if( $logged_in && !($userdetails['is_email_verify'] == 1) ){
					echo "Verify your email to purchase.";
				} else if( !$logged_in && !($userdetails['is_email_verify'] == 1) ){
					echo "Sign-in to purchase this product.";
				}?>
			</p>
            <?php endif; ?>
            <span>Delivers upon seller confirmation*</span>
            </div>
        </div>
        <div class="prod_loc_areas">
          <p>
            <strong class="location_message">Shipping Location:</strong>
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
            <br/>
            <strong>Shipment fee:</strong>
            <span class="shipping_fee"> Select location to view shipping fee </span>
            
          </p>
        </div>
        <p class="product_content_payment"> <strong>Payment:</strong><br />
          <span class="mastercard"></span>
          <span class="visa"></span>
          <span class="dragonpay"></span>
          <span class="paypal"></span>
          <?php if(intval($product['is_cod'],10) === 1): ?>
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
          <p> <strong>Description: </strong><?php echo html_purify($product['description']);?> </p>
          <ul>
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
              <!--<form method="post" action="" id="review_form"> -->
			  <?php 
				$attr = array('id'=>'review_form');
				echo form_open('',$attr);
			  ?>
              <?php #echo form_open('', array('id' => "review_form")); ?>
                <div>
                  <label>Subject *</label>
                  <input type="text" name="subject">
                </div>
                <div>
                  <label>Rating *</label>
                  <div id="star"></div>
                </div>
                <div>
                  <label>Comment *</label>
                  <textarea name="comment"></textarea>
                </div>
                <input type="submit" value="Submit" class="orange_btn" name="review_form">
                <img src="<?=base_url()?>/assets/images/bx_loader.gif" id="load_submitreview" style="position: relative; top:18px; left:30px; display:none"/>
              <!-- </form> -->
              <?php echo form_close(); ?>
            </div>
            <div class="clear"></div>
            <div id="review_success_container" style="display:none">
              <p> <img src="<?=base_url()?>assets/images/img_success.png"> </p>
              <div style="">
                <p><strong>Your review has been submitted. Reload the page to view your review. </strong></p>
                <p><strong><a href="">Click here to return to the product page.</a></strong></p>
              </div>
            </div>
          </div>
          <div class="reviews_content">
			<input type="hidden" id="reviews_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
            <?php if(count($reviews) === 0): ?>
            <div> <strong>This product has 0 reviews so far. Be the first to review it.</strong> </div>
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
              <p class="show_replies">Show replies</p>
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
                  <img src="<?=base_url()?>/assets/images/bx_loader.gif" id="savereply_loadingimg" style="position: relative; top:12px; left:15px; display:none"/>
                  <span class="reply_cancel">Cancel</span>
                <?php echo form_close();?>
              </div>
              <?php endif;?>
            </div>
            <?php endforeach;?>
            <div class="clear"></div>
            <div class="review_last"> 
              <span id="see_more_reviews"><strong><a href="">See more reviews.</a></strong></span> 
              <img src="<?=base_url()?>/assets/images/bx_loader.gif" id="more_review_loading_img" style="position: relative; top:12px; left:15px; display:none"/>
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
              <a href="<?=base_url()."item/".$row['id_product'].'/'.es_url_clean($row['product']); ?>.html">
                <span class="rec_item_container">
                  <img class="rec_item_img" src="<?=base_url().$row['path'].'categoryview/'.$row['file']?>">
                </span>
                <p>
                  <?php echo html_escape($row['product']);?><br />
                  <span class="price"> PHP <?=number_format($row['price'],2,'.',',');?></span>

                </p>
              </a>
            </li>
            <?PHP endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
  <input id='p_qty' type='hidden' value=' <?php echo json_encode($product_quantity);?>'>
  <input id='p_shipment' type='hidden' value='<?php echo json_encode($shipment_information);?>'>
  <input id='p_itemid' type='hidden' value='0'/>
</section>

<script src="<?=base_url()?>assets/JavaScript/js/jquery.jqzoom-core.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/JavaScript/js/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/JavaScript/js/jquery.idTabs.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/JavaScript/js/jquery.raty.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/JavaScript/categorynavigation.js" type="text/javascript"></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.numeric.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.simplemodal.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.validate.js'></script>
<script src="<?=base_url().$page_javascript?>" type="text/javascript"></script>

<style type="text/css">
      nav {
        display: none;
        position: absolute;
        z-index: 9999;
        background-color: #fff;
      }
      .slide_arrows {
        max-width: none !important;
      }

      #simplemodal-container {
        height: 390px !important;
      }
      .bread_crumbs {
        margin-left: 195px;
        margin-top: 10px;
      }
</style>

<script type="text/javascript">
    (function($) {
        $(function() {
            $('.jcarousel').jcarousel();

            $('.jcarousel-control-prev')
                    .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
                    .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
                    .jcarouselControl({
                target: '-=1'
            });

            $('.jcarousel-control-next')
                    .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
                    .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
                    .jcarouselControl({
                target: '+=1'
            });

            $('.jcarousel-pagination')
                    .on('jcarouselpagination:active', 'a', function() {
                $(this).addClass('active');
            })
                    .on('jcarouselpagination:inactive', 'a', function() {
                $(this).removeClass('active');
            })
                    .jcarouselPagination();
        });
    })(jQuery);

</script>
<script type="text/javascript">

$(document).on('click','.prod_cat_drop',function() {
     $(".category_nav").toggleClass("category_nav_plus");
     $(".prod_cat_drop").toggleClass("active_prod_cat_drop_arrow");
                 $(document).bind('focusin.prod_cat_drop click.prod_cat_drop',function(e) {
                if ($(e.target).closest('.prod_cat_drop, .category_nav').length) return;
                $('.category_nav').removeClass('category_nav_plus');
                $('.prod_cat_drop').removeClass('active_prod_cat_drop_arrow');
                });
             });
 
              $('.category_nav').removeClass('category_nav_plus');
              $('.prod_cat_drop').removeClass('active_prod_cat_drop_arrow');
</script>
