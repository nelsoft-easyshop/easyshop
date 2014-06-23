
<?php 

        date_default_timezone_set('Asia/Manila');
        $timeStart = date('H:i') ;
        $timeEnd = date('H', time()+60*60).':00';
        $secondsLapse = date('s');
 
        $start = strtotime($timeStart);
        $stop = strtotime($timeEnd);
        $diff = ($stop - $start)/60;
        $intoSeconds = $diff * 60;
        $secondsRemaining = $intoSeconds - $secondsLapse;
        // echo 'current time: ' .$timeStart . '<br>';
        // echo 'refresh time: ' . $timeEnd . '<br>';
        // echo 'minutes remaining: ' . $diff . '<br>';
        // echo 'seconds remaining: ' . $secondsRemaining;
?>
<meta http-equiv="refresh" content="<?=$secondsRemaining; ?>" />

<!-- REVIEW SEO TAGS -->
<script type="application/ld+json">
	<?php echo $jsonReviewSchemaData;?>
</script>
<!-- <div id="fb-root"></div> -->
<!-- <script src="https://connect.facebook.net/en_US/all.js"></script> -->
<script>
  // FB.init({
  //   appId  : '1395192884090886',
  //   status : true, 
  //   cookie : true, 
  //   xfbml  : true  
  // });
</script>
<script type="text/javascript">
// $(document).ready(function() {
//     FB.getLoginStatus(function(response) {
//           if (response.status == 'connected') {
//                 var user_id = response.authResponse.userID;
//                 var page_id = "211771799032417"; //  
//                 var fql_query = "SELECT uid FROM page_fan WHERE page_id=" + page_id + " and uid=" + user_id;
                
//                 FB.api('/me/likes/'+page_id, function(response) {
//                       console.log(response);
//                       if (response.data[0]) {
//                          console.log('like');
//                       } else {
//                          console.log('not like');
//                       }
//                 });
//           }else{

//             console.log('please login')
          
//           } 
//     });
// });
</script>
<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.jqzoom.css?ver=<?=ES_FILE_VERSION?>" type="text/css">
<link rel="stylesheet" href="<?=base_url()?>assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?=base_url()?>assets/css/productview.css" type="text/css" media="screen"/>

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
          <li class = <?php echo (($category['id_cat'] === $breadcrumbs[0]['id_cat'])?"active":"");?>> <a href="<?=base_url()?>category/<?php echo $category['slug']?>"> <?php echo html_escape($category['name']);?> </a> </li>
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
            <?php if($product['start_promo']): ?>
                <?php if($product['is_soldout']): ?>
                    <span class="cd_soldout">
                        <img src="<?=base_url()?>assets/images/img_cd_soldout.png" alt="Sold Out">
                    </span>
                <?php else: ?>
                    <span class="cd_slide_discount">
                        <span><?php echo  number_format( $product['percentage'],0,'.',',');?>%<br>OFF</span>
                    </span>
                <?php endif; ?>
            <?php endif; ?>
    
        </div>
        <div class="prod_con_gal"> <a href="<?=base_url()?><?php echo $product_images[0]['path']; ?><?php echo $product_images[0]['file']; ?>" class="jqzoom" rel='gal1'  title="Easyshop.ph" > <img src="<?=base_url()?><?php echo $product_images[0]['path']; ?>small/<?php echo $product_images[0]['file']; ?>"  title="product"> </a> </div>
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
            <a href="<?php echo base_url() . 'vendor/' . $product['sellerusername'];?>"> 
              <img class=" seller-img" src="<?php echo base_url() . $product['userpic']?>/60x60.png?<?php echo time();?>"><br />
              <span class="name"><?php echo html_escape($product['sellerusername']);?></span> 
            </a><br/>
            
            
            <a id="modal-launcher2" href="javascript:void(0)" title="Send a message">
            
            <span><span class="span_bg prod_message"></span> </span>
            
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
                <span class="quantity" data-qty="" data-default="false" id="p_availability"></span>
            </p>
          </div>
          <div class="buy_box"> 
            <?PHP if($logged_in && $userdetails['is_email_verify'] == 1 ): ?>
                <?php if($uid == $product['sellerid']): ?>
                    <p class="buy_btn_sub"> This is your own listing </p>
                <?php elseif($product['can_purchase'] === false): ?>
                    <p class="buy_btn_sub"> Purchase limit exceeded </p>
                <?php else: ?>
                    <a href="JavaScript:void(0)" id="send" class="fm1 orange_btn3 disabled">Buy Now</a> <br/>
                <?php endif;?>
            <?php else: ?>
            
            <a href="<?PHP echo base_url();echo $logged_in?'me?me=myinfo':'login';?>" id="unablesend" class="add_to_cart">
            <span></span>  <?PHP echo $logged_in?'Verify now':'Login now';?></a> <br />
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
     
            <span class="shipping_fee"> <span class="loc_invalid"> Select location* </span></span>
         
          </p>
        </div>
        <p class="product_content_payment"> <strong>Payment:</strong><br />

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
          <p> <strong>Description: </strong>
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
                </div></p>

            <ul>
            <li><strong>Brand: </strong><?php echo html_escape(ucfirst(strtolower($product['brand_name'])));?></li>
            <li><strong>Additional description: </strong><?php echo html_escape($product['brief']);?></li>
            <li><strong>Condition: </strong><?php echo html_escape($product['condition']);?></li>

         </ul>
        </div>
        <div id="tabs-2">
          <h3>Specifications of <?php echo html_escape($product['product_name']);?></h3>
          <div> <span>SKU</span> <span><?php echo (strlen(trim($product['sku'])) > 0)?html_escape($product['sku']):'not specified';?></span></div>
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
                  <input type="text" name="subject" maxlength="150">
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
                <img src="<?=base_url()?>assets/images/bx_loader.gif" id="load_submitreview" style="position: relative; top:18px; left:30px; display:none"/>
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
    
    <div id="modal-background">
    </div>
    <div id="modal-container">
        <div id="modal-div-header">
            <button id="modal-close">X</button>        
        </div>
        <div id="modal-inside-container">
            <div>
                <label>To : </label>
                <input type="text" value="<?=$product['sellerusername'];?>" disabled id="msg_name" name="msg_name" >
            </div>
            <div>
                <label>Message : </label>
                <textarea cols="40" rows="5" name="msg-message" id="msg-message" placeholder="Say something.."></textarea>		
            </div>	   
        </div>
        <button id="modal_send_btn">Send</button>
    </div>
  </div>


  <input type = 'hidden' id='cd_enddate' value="<?php echo date('M d,Y H:i:s',strtotime(($product['start_promo'] == "1" ? $product['enddate'] : $product['startdate']))); ?>"/>
  <input id='p_qty' type='hidden' value=' <?php echo json_encode($product_quantity);?>'>
  <input id='p_shipment' type='hidden' value='<?php echo json_encode($shipment_information);?>'>
  <input id='p_itemid' type='hidden' value='0'/>
  
</section>

<script src="<?=base_url()?>assets/js/src/vendor/jquery.jqzoom-core.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/vendor/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/vendor/jquery.idTabs.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/vendor/jquery.raty.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/categorynavigation.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script type='text/javascript' src='<?=base_url()?>assets/js/src/vendor/jquery.numeric.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/js/src/vendor/jquery.simplemodal.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/js/src/vendor/jquery.validate.js'></script>
<script src="<?=base_url()?>assets/js/src/productpage.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>

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

    $(function(){    
        var uid = <?php echo json_encode(!empty($uid)?$uid:0);?>;
        var seller_id = <?php echo $product['sellerid']; ?>;
        if (uid ==  seller_id || uid == 0  ) {
            $(".vendor-msg-modal").remove();
            $("#modal-background").remove();
            $("#modal-container").remove();
        }
        $("#modal-background, #modal-close").click(function() {
            $("#modal-container, #modal-background").toggleClass("active");
            $("#modal-container").hide();
            $("#msg-message").val("");
        });
        $("#modal-launcher2").click(function() {
            $("#modal-container, #modal-background").toggleClass("active");
            $("#modal-container").show();
        });
        
        $("#modal_send_btn").on("click",function(){
            var recipient =<?php echo $product['sellerid']; ?>;
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
            var msg = $("#msg-message").val();
            if (msg == "") {
                alert("Say something..");
                return false;
            }
            var msg = $("#msg-message").val();
                $.ajax({
                    async : true,
                    type : "POST",
                    dataType : "json",
                    url : "<?=base_url()?>messages/send_msg",
                    data : {recipient:recipient,msg:msg,csrfname:csrftoken},
                    success : function(data) {
                $("#modal-container, #modal-background").toggleClass("active");
                $("#modal-container").hide();
                $("#msg-message").val("");
                alert("Your message has been sent.");
                    }
                });
        });
        
    });
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

<script src="<?=base_url()?>assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script>
<script>
    
    $(document).ready(function(){

        $('.mid_slide1').bxSlider({
            mode: 'horizontal',
            auto:true,
            autoControls: true,
            pause: 3500,
            controls:false,
            slideWidth: 510
        });

        $('.mid_slide2').bxSlider({
            slideWidth: 160,
            minSlides: 2,
            maxSlides: 3,
            moveSlides: 1,
            slideMargin: 0,
            infiniteLoop:true,
            autoControls: false,
            pager:false
        });

        $('.countdown_slides').bxSlider({
            slideWidth: 220,
            minSlides: 3,
            maxSlides: 4,
            moveSlides: 2,
            slideMargin: 0,
            infiniteLoop:true,
            autoControls: false,
            pager:false
        });

        $('.slider3').bxSlider({
            slideWidth: 452,
            minSlides: 1,
            maxSlides: 1,
            moveSlides: 1,
            slideMargin: 0,
            infiniteLoop:true,
            autoControls: false,
            pager:false
        });

        $('.bx-wrapper').addClass('slide_arrows');

        //middle content top slides
        $('.mid_slide1').parent('.bx-viewport').addClass('mid_top_slides');

        //middle content countdown slides
        $('.countdown_slides').parent('.bx-viewport').parent('.bx-wrapper').addClass('countdown_slides_wrapper');

        //middle content bottom slides
        $('.mid_slide2').parent('.bx-viewport').parent('.bx-wrapper').addClass('mid_bottom_slides');
        $('.mid_slide2').parent('.bx-viewport').addClass('inner_mid_bottom_slides');

        //electronics slides
        $('.slider3').parent('.bx-viewport').addClass('electronic_slides');

        //side navigation menu slides
        $('.slides_prod').parent('.bx-viewport').addClass('side_menu_slides');
        $('.side_menu_slides').parent('.bx-wrapper').addClass('side_menu_nav_slides');
        $('.side_menu_nav_slides').children('.bx-controls').addClass('side_menu_nav_arrow');


    });


</script>