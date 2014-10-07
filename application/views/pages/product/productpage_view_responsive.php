<!-- REVIEW SEO TAGS -->
<script type="application/ld+json">
    <?php echo $jsonReviewSchemaData;?>
</script>
<link rel="stylesheet" href="/assets/css/jquery.jqzoom.css?ver=<?=ES_FILE_VERSION?>" type="text/css">
<link rel="stylesheet" href="/assets/css/productview.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<script>
    
</script>
<section style="color-gray">
    <div class="container font-roboto" style="max-width:980px;">
        <div class="row">
            <div class="col-md-12">
                <section class="top_margin product-page-section display-when-desktop">
                    <div class="wrapper">
                        <?php echo $category_navigation_desktop ?>
                        <div class="prod_cat_nav" id="prod_drop_nav">
                            <div class="category_nav product_content">
                                <ul>
                                <?php foreach($main_categories as $category): ?>
                                    <li class = <?php echo ((isset($breadcrumbs[0]['id_cat']) &&  $category['id_cat'] === $breadcrumbs[0]['id_cat'])?"active":"");?>> <a href="/category/<?php echo $category['slug']?>"> <?php echo html_escape($category['name']);?> </a> </li>
                                <?php endforeach;?>
                                </ul>
                                <span id="prod" class="span_drop span_bg prod_cat_drop"></span>
                            </div>
                        </div>
                       
                        <div class="clear"></div>
                        <div class="bread_crumbs">
                            <ul>
                                <li class=""><a href="/">Home</a></li>
                                <?php foreach($breadcrumbs as $crumbs): ?>
                                    <li> <a href="/category/<?php echo $crumbs['slug']?>"> <?php echo html_escape($crumbs['name']);?> </a> </li>
                                <?php endforeach;?>
                                <li class="bread_crumbs_last_child"><?php echo html_escape($product['product_name']);?></li>
                            </ul>
                        </div>
                    </div>
                    <br/>
                </section>
                <div class="display-when-mobile-833">
                    <?php echo $category_navigation_mobile; ?>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-5 col-zoom">
                <?php include("product_image_gallery.php") ?>
            </div>
            
            
            
            <div class="col-md-7" style="position: relative; z-index: 1;">
                <?php echo $banner_view; ?>
                <div class="display-when-mobile-833">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table width="100%">
                            <tr>
                                <td width="100%" class="v-align-top" colspan="2">
                                    <h1 class="id-class product-name" id="<?php echo $product['id_product'];?>"> 
                                        <span id="pname"> <?php echo html_escape($product['product_name'])?> </span>
                                    </h1>
                                </td>
                            </tr>
                            <tr>
                                <td width="60%" class="v-align-top td-price">
                                    <div>PHP 
                                        <span class="current_price fm1" data-baseprice="<?php echo $product['price']?>"> 
                                            <?php echo number_format($product['price'],2,'.',',');?> 
                                        </span> 
                                    </div>
                                    <?PHP if( ((intval($product['is_promote']) === 1) && $product['start_promo'] && !$product['end_promo'] && $product['percentage'] > 0)
                                            || ((intval($product['is_promote']) === 0) && $product['discount'] > 0)): ?>   
                                        <div class="div-discount"><span class="recent_price"> PHP <?php echo number_format($product['original_price'],2,'.',','); ?></span> | <strong> <?php echo number_format( $product['percentage'],0,'.',',');?> % OFF  </strong></div>          
                                    <?PHP endif;?>
                                </td>
                                <td width="40%" class="v-align-top td-price">
                                    Availability: <span class="quantity quantity_m" data-qty="" data-default="false"></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="panel-group panel-seller" id="seller-accordion">
                    <div class="panel panel-default no-border">
                        <div class="panel-heading panel-seller-header">
                            <a data-toggle="collapse" data-parent="#seller-accordion" href="#seller" class="a-accordion-header">
                                Seller: <?php echo html_escape($product['storename']);?>
                                <i class="sell glyphicon glyphicon-chevron-down pull-right"></i>
                            </a>
                            <script>
                                    $("#seller-accordion").on('click','.a-accordion-header',function() {
                                        
                                        var attr = $("i.sell").attr("class");

                                        if(attr == "sell glyphicon glyphicon-chevron-down pull-right")
                                        {
                                            $('i.sell').removeClass("sell glyphicon glyphicon-chevron-down pull-right").addClass("sell glyphicon glyphicon-chevron-up pull-right");
                                        }else{
                                            $('i.sell').removeClass("sell glyphicon glyphicon-chevron-up pull-right").addClass("sell glyphicon glyphicon-chevron-down pull-right");
                                        
                                        }
                                    });
                            </script>
                        </div>
                        <div id="seller" class="panel-collapse collapse">
                            <div class="panel-body panel-seller-body">
                                <table width="100%" class="font-12">
                                    <tr>
                                        <td class="v-align-top" width="10%">
                                            <a href="/<?php echo $product['sellerslug'];?>"> 
                                                <img class="seller-img seller-img-m" src="<?=$avatarImage?>"><br />
                                            </a>
                                        </td>
                                        <td class="v-align-top td-seller-info">
                                            <a href="/<?php echo $product['sellerslug'];?>"> 
                                                <span class="name"><?php echo html_escape($product['storename']);?></span> 
                                            </a>
                                            <a class="modal_msg_launcher" href="javascript:void(0)" title="Send <?=html_escape($product['sellerusername'])?> a message">
                                                <span>
                                                    <span class="span_bg prod_message" width="100px" height="100px"></span> 
                                                </span>
                                                
                                                <br/>
                                                <div  width="100%" style="border-top: solid #fff 1px; height: 1px;">
                                                </div>
                                                <?php if(($vendorrating['rate_count'] <=0)):?>
                                                <p><span style="font-size:11px; margin-left:0px;">No ratings received yet.</span></p>
                                                <?php else:?>
                                                    
                                                    <table width="70%">
                                                        <tr>
                                                            <td>
                                                                <span class="rating_criteria"><?php echo $this->lang->line('rating')[0].':';?></span>
                                                            </td>
                                                            <td style="td-rating">
                                                                <span class="rating_value rating_value_m"><?php echo number_format($vendorrating['rating1'],2,'.',',');?></span> <img src="/assets/images/star-on.png" alt="*" title="">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span class="rating_criteria"><?php echo $this->lang->line('rating')[1].':';?></span>
                                                            </td>
                                                            <td style="td-rating">
                                                                <span class="rating_value rating_value_m"> <?php echo number_format($vendorrating['rating2'],2,'.',',');?> </span> <img src="/assets/images/star-on.png" alt="*" title="">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span class="rating_criteria"><?php echo $this->lang->line('rating')[2].':';?></span>
                                                            </td>
                                                            <td style="td-rating">
                                                                <span class="rating_value rating_value_m"> <?php echo number_format($vendorrating['rating3'],2,'.',',');?></span> <img src="/assets/images/star-on.png" alt="*" title="">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php endif;?>
                                            </a> 
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="panel panel-product-info no-border " width="100%" >
                    <div class="panel-body">
                        <div class="row display-when-desktop ">
                            <table width="100%">
                                <tr>
                                    <td width="60%" style="vertical-align:top; padding-left: 10px;">
                                        <h1 class="id-class product-name" id="<?php echo $product['id_product'];?>"> 
                                            <span id="pname"> <?php echo html_escape($product['product_name'])?> </span>
                                        </h1>
                                    </td>
                                    <td  width="40%" style="vertical-align:top; padding-right: 3px;">
                                        <span class="seller-name"> 
                                            <a href="/<?php echo $product['sellerslug'];?>"> 
                                                <img class=" seller-img" src="<?=$avatarImage?>"/><br />
                                                <span class="name"><?php echo html_escape( $product['storename']);?></span> 
                                            </a>
                                            <br/>
                                            <a class="modal_msg_launcher" href="javascript:void(0)" title="Send <?=html_escape($product['sellerusername'])?> a message">
                                                <span>
                                                    <span class="span_bg prod_message"></span> 
                                                </span>
                                                <br/>
                                                <?php if(($vendorrating['rate_count'] <=0)):?>
                                                    <center><p style="margin-left: 13px;">No ratings received yet.</p></center>
                                                <?php else:?>
                                                    <p class="p-rating-seller"><span class="rating_criteria"><?php echo $this->lang->line('rating')[0].':';?></span><span class="pull-right"><span class="rating_value"><?php echo number_format($vendorrating['rating1'],2,'.',',');?></span> <img src="/assets/images/star-on.png" alt="*" title=""></span></p>
                                                    <p class="p-rating-seller"><span class="rating_criteria"><?php echo $this->lang->line('rating')[1].':';?></span><span class="pull-right"><span class="rating_value" > <?php echo number_format($vendorrating['rating2'],2,'.',',');?> </span> <img src="/assets/images/star-on.png" alt="*" title=""></span></p>
                                                    <p class="p-rating-seller"><span class="rating_criteria"><?php echo $this->lang->line('rating')[2].':';?></span><span class="pull-right"><span class="rating_value"> <?php echo number_format($vendorrating['rating3'],2,'.',',');?></span> <img src="/assets/images/star-on.png" alt="*" title=""></span></p>
                                                <?php endif;?>
                                            </a>            
                                        </span> 
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="clear prod_inner_border display-when-desktop "></div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php foreach($product_options as $key=>$product_option):?>
                                    <?php if(count($product_option)>1): ?>
                                        <div class="product_option"> 
                                            <span class="label-option"><?php echo html_escape(str_replace("'", '', $key));?></span>
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
                                <div class="product_option"> <span class="label-option">Quantity</span>
                                    <div class="div-quantity-m">
                                        <input type="text" value ="0" class="product_quantity no-border" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear prod_inner_border display-when-desktop "></div>
                        <div class="row display-when-desktop ">
                            <div class="col-md-4 col-price">
                                <div class="div-box-price">
                                    <center>
                                        <div class="pbt pbt1">Price</div>
                                        <div>PHP 
                                            <span class="current_price fm1" data-baseprice="<?php echo $product['price']?>"> 
                                                <?php echo number_format($product['price'],2,'.',',');?> 
                                            </span> 
                                        </div>
                                        <?PHP if( ((intval($product['is_promote']) === 1) && $product['start_promo'] && !$product['end_promo'] && $product['percentage'] > 0)
                                                || ((intval($product['is_promote']) === 0) && $product['discount'] > 0)): ?>   
                                            <div ><span class="recent_price font-10"> PHP <?php echo number_format($product['original_price'],2,'.',','); ?></span> | <strong class="font-10"> <?php echo number_format( $product['percentage'],0,'.',',');?> % OFF  </strong></div>          
                                        <?PHP endif;?>
                                    </center>
                                </div>
                            </div>
                            <div class="col-md-3 col-availability">
                                <div class="div-box-price">
                                    <center>
                                        <p class="p-prod-label-availability">Availability</p>
                                        <p>
                                            <p class="quantity" data-qty="" data-default="false" id="p_availability"></p>
                                        </p>
                                    </center>
                                </div>
                            </div>
                            <div class="col-md-5 col-buy">
                                <div width="100%" class="div-buy-now">
                                    <center>
                                        <?php if($logged_in && intval($userdetails['is_email_verify']) !== 1): ?>
                                            <p class="buy_btn_sub"> Verify your email </p>
                                        <?php elseif($logged_in && $uid == $product['sellerid']): ?>
                                            <p class="buy_btn_sub"> This is your own listing </p>
                                        <?php else: ?>
                                            <?php if(count($shipment_information) === 0 && intval($product['is_meetup']) === 1): ?>
                                                    <a href="javascript:void(0)" class="btn-meet-up modal_msg_launcher font-14" title="Send <?=html_escape($product['sellerusername'])?> a message" >Contact Seller</a> <br/>
                                                <span class="font-10" width="100%">Item is listed as an ad only. *</span>
                                            <?php else: ?>
                                                    <a href="javascript:void(0)" id='<?php echo $product['can_purchase']?'send':'' ?>' class="fm1 orange_btn3 disabled">Buy Now</a> <br/>
                                                <span class="font-10" width="100%">Delivers upon seller confirmation*</span>
                                            <?php endif; ?>
                                        
                                            
                                        <?php endif;?>
                                    </center>
                                </div>
                            </div>
                        </div>
                        <div class="clear prod_inner_border display-when-desktop "></div>
                        <div class="row row-prod-info">
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
                                    <span class="span-ship">
                                    <?php if($product['is_meetup'] && count($shipment_information) === 0):  ?>
                                        <span class="shipping_fee"> <span class="loc_invalid"> Contact the seller * </span></span>
                                    <?php else:?>
                                        <?PHP if($product['is_free_shipping']):  ?>
                                            <span class="span-free"style="margin-left: 15px;"><span class="span_bg img_free_shipping"></span></span>
                                        <?PHP else: ?>
                                            <span class="shipping_fee"> <span class="loc_invalid"> Select location* </span></span>
                                        <?PHP endif; ?>
                                    <?php endif; ?>
                                    </span>
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
                    </div>
                </div>
                <!--FOR MOBILE-->
                <div class="display-when-mobile-833">
                    <div width="100%">
                        <center>
                            <?php if($logged_in && intval($userdetails['is_email_verify']) !== 1): ?>
                                <div class="alert alert-danger no-border"> Verify your email </div>
                            <?php elseif($logged_in && $uid == $product['sellerid']): ?>
                                <div class="alert alert-danger no-border">  This is your own listing </div>
                            <?php else: ?>
                                <?php if(count($shipment_information) === 0 && intval($product['is_meetup']) === 1): ?>
                                        <br/><a href="javascript:void(0)" class="btn btn-block btn-lg btn-meet-up modal_msg_launcher" title="Send <?=html_escape($product['sellerusername'])?> a message">Contact Seller</a> <br/>
                                    <div class="alert alert-danger no-border"> <i class="glyphicon glyphicon-warning-sign"></i>Item is listed as an ad only. *</div>
                                <?php else: ?>
                                        <a href="javascript:void(0)" id='<?php echo $product['can_purchase']?'send':'' ?>' class="fm1 orange_btn3 disabled btn-lg btn-block" >Buy Now</a> <br/>
                                    <p class="font-10 span-deliver-confirm" width="100%">Delivers upon seller confirmation*</p>
                                <?php endif; ?>
                            <?php endif;?>
                        </center>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-10">
            <script>
                
            </script>
                <div id="tabs" class="display-when-desktop">
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
                            <p>
                                <span class="p-desc-label">Brand: </span><?php echo html_escape(ucfirst(strtolower($product['brand_name'])));?>
                            </p>
                            <p>
                                <span class="p-desc-label">Condition: </span><?php echo html_escape($product['condition']);?>
                            </p>
                            <p>
                                <span class="p-desc-label">Additional description: </span><?php echo html_escape($product['brief']);?>
                            </p>
                            
                    </div>
                    <div id="tabs-2">
                        
                        <p class="p-panel-head">Specifications of <?php echo html_escape($product['product_name']);?></p>
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
                            <p class="write_review"> <img src="/assets/images/img_edit.png">Write a review </p>
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
                                    <table width="100%" style="margin-left:-10px;" class="font-roboto table-write-review">
                                        <tr>
                                            <td style="padding: 15px 0px 5px 0px;" width="10%" ]>
                                                <label>Subject:*</label>
                                            </td>
                                            <td style="padding: 15px 0px 5px 0px;" width="70%">
                                                <input type="text" class="form-control no-border" style="width: 100% !important; height: 40px; !important;" name="subject" maxlength="150">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 10px 0px 5px 0px;">
                                                <label>Rating</label>
                                            </td>
                                            <td style="padding: 10px 0px 5px 0px;">
                                                <div id="star"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0px 5px 0px;" colspan="2">
                                                <label>Comment *</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 0px 0px 0px 27px;" colspan="2">
                                                <textarea class="form-control no-border" style="width: 100% !important;" name="comment"></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                    <center>
                                        <input type="submit" value="Submit" class="btn-orange" name="review_form">
                                    </center>
                                    
                                    
                                    <img src="/assets/images/bx_loader.gif" id="load_submitreview" style="position: relative; top:-200px; left:50%; display:none"/>
                                <?php echo form_close(); ?>
                            </div>
                            <div class="clear"></div>
                            <div id="review_success_container" style="display:none">
                                
                                
                                <table width="100%">
                                    <tr>
                                        <td style="vertical-align:top;">
                                            <p> <img src="/assets/images/img_success.png"> </p>
                                        </td>
                                        <td width="98%">
                                            <p><strong>Your review has been submitted. Reload the page to view your review. </strong></p>
                                            <p><strong><a href="/item/<?php echo $product['slug'];?>">Click here to return to the product page.</a></strong></p>
                                        </td>
                                    </tr>
                                </table>
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
                                                <img src="/assets/images/star-on.png" alt="*" title="">
                                            <?php endfor;?>
                                            <?php for($i = 5-$review['rating'];$i>0;$i--):?>
                                                <img src="/assets/images/star-off.png" alt="" title="">
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
                                                
                                                <span class="reply_cancel">Cancel</span>
                                                <img src="/assets/images/orange_loader_small.gif" id="savereply_loadingimg" style="position: relative; top:12px; left:45%; margin-bottom: 15px; display:none"/>
                                                <div class="clear"></div>
                                                <?php echo form_close();?>
                                            </div>
                                        <?php endif;?>
                                    </div>
                        
        
                                <?php endforeach;?>
                                <div class="clear"></div>
                                <div class="review_last"> 
                                    <span id="see_more_reviews" style="font-weight:bold;"><a href="javascript:void(0)">See more reviews.</a></span> 
                                    <img src="/assets/images/orange_loader_small.gif" id="more_review_loading_img" style="position: relative; top:12px; left:15px; display:none; "/>
                                    <br/><br/>
                                </diV>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                <div class="col-md-12">
                <div class="panel-group display-when-mobile-833" id="accordion">
                    <div class="panel panel-default no-border"  id="details-accordion">
                        <div class="panel-heading  no-border">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#productDescription" class="accordion-details-toggle">
                                    Product Details
                                    <i class="indicator glyphicon glyphicon-chevron-up pull-right"></i>
                                </a>
                               
                            </h4>
                        </div>
                        
                        <div id="productDescription" class="panel-collapse collapse in">
                            <div class="panel-body">
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
                                <p>
                                    <strong>Brand: </strong><?php echo html_escape(ucfirst(strtolower($product['brand_name'])));?>
                                </p>
                                <p>
                                    <strong>Condition: </strong><?php echo html_escape($product['condition']);?>
                                </p>
                                <p>
                                    <strong>Additional description: </strong><?php echo html_escape($product['brief']);?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel panel-default no-border" id="specs-accordion">
                        <div class="panel-heading no-border">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#specs" class="accordion-specs-toggle">
                                    Specifications
                                    <i class="indicator glyphicon glyphicon-chevron-down pull-right"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="specs" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p class="p-panel-head">Specifications of <?php echo html_escape($product['product_name']);?></p>
                                    <table width="100%">
                                        <tr>
                                            <td width="40%">
                                                <strong>SKU:</strong>
                                            </td>
                                            <td  width="60%">
                                                <?php echo (strlen(trim($product['sku'])) > 0)?html_escape($product['sku']):'not specified';?>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php foreach($product_options as $key=>$product_option):?>
                                        <?php if(count($product_option)===1): ?>
                                            <?php if(intval($product_option[0]['datatype'],10) === 2): ?>
                                                    <div class="tab2_html_con">
                                                        <strong style="text-transform: capitalize;"><?php echo html_escape(str_replace("'", '', $key));?>: </strong>
                                                        <?php echo html_purify($product_option[0]['value']);?>
                                                    </div>
                                                    <table width="100%">
                                                        <tr>
                                                            <td width="40%">
                                                                <strong style="text-transform: capitalize;"><?php echo html_escape(str_replace("'", '', $key));?>: </strong>
                                                            </td>
                                                            <td  width="60%">
                                                                <?php echo html_purify($product_option[0]['value']);?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                            <?php else: ?>   
                                                    
                                                    <table width="100%">
                                                        <tr>
                                                            <td width="40%">
                                                                <strong style="text-transform: capitalize;"><?php echo html_escape(str_replace("'", '', $key));?>:</strong>
                                                            </td>
                                                            <td  width="60%">
                                                                <?php echo html_escape($product_option[0]['value']);?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default no-border" id="review-accordion">
                        <div class="panel-heading no-border">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#review" class="accordion-review-toggle">
                                    Reviews
                                <i class="indicator glyphicon glyphicon-chevron-down pull-right"></i>
                                </a>
                                <script>
                                function toggleChevron(e) {
                                    $(e.target)
                                        .prev('.panel-heading')
                                        .find("i.indicator")
                                        .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
                                }
                                $('#accordion').on('hidden.bs.collapse', toggleChevron);
                                $('#accordion').on('shown.bs.collapse', toggleChevron);
                                </script>
                            </h4>
                        </div>
                        <div id="review" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="reviews_title">
                                    
                                    <p class="p-panel-head pull-left">Product Review</p>
                                    <?php if($logged_in && $uid != $product['sellerid'] && in_array($uid,$allowed_reviewers) ):?>
                                    <p class="write_review pull-right"> <img src="/assets/images/img_edit.png">Write a review </p>
                                    <?php elseif($uid == $product['sellerid']): ?>
                                    <p class=""><!-- Unable to review own product --></p>
                                    <?php else: ?>
                                
                                    <p class="pull-left font-10 p-sign-in" >Sign-in & purchase item to write a review</p>
                                    <?php endif; ?>
                                </div>
                                
                                    <?php if(count($reviews) === 0): ?>
                                    <div> 
                                        <strong><br/><br/>This product has 0 reviews so far. Be the first to review it.<br/><br/></strong> 
                                    </div>
                                <?php else: ?>
                                    <input type="hidden" value="<?PHP echo end($reviews)['id_review']; ?>" id="lastreview"/>
                                    <?php foreach($reviews as $review):?>
                                        
                                        <div class="media-body" style="">
                                            <h5 class="media-heading"><b><?php echo html_escape($review['title'])?></b></h5>
                                            <p class="p-date-review"><?php echo $review['reviewer']?> | <?php echo $review['datesubmitted']?></p>
                                            <p class="p-rating">
                                                <?php for($i = $review['rating'];$i>0;$i--):?>
                                                    <img src="/assets/images/star-on.png" alt="*" title="">
                                                <?php endfor;?>
                                                <?php for($i = 5-$review['rating'];$i>0;$i--):?>
                                                    <img src="/assets/images/star-off.png" alt="" title="">
                                                <?php endfor;?>
                                            </p>
                                            <p class="p-review-content">
                                                <?php echo html_escape($review['review'])?>
                                            </p>
                                                <?php if($review['reply_count'] > 0):?>
                                                <?php $reply_counter = 0;?>
                                                <div class="">
                                                    <?php foreach($review['replies'] as $reply):?>
                                                        
                                                        <blockquote> <p class="p-reply-sender"><strong><?php echo $reply['reviewer'];?></strong> replied on <?php echo $reply['datesubmitted'];?></p>
                                                        <p class="p-reply-content">"<?php echo html_escape($reply['review']);?>"</p>
                                                        </blockquote>
                                                        <?php $reply_counter++;?>
                                                        <?php if($reply_counter == 3 && $review['reply_count'] > 3):?>
                                                            </div><div class="reply_content_m">
                                                        <?php endif;?>
                                                    <?php endforeach;?>
                                                </div>
                                            <?php endif;?>
                                            
                                            <?php if($review['reply_count'] > 3):?>
                                                <p class="show_replies_m">Show more replies</p>
                                                <p class="hide_replies_m">Hide replies</p>
                                            <?php endif;?>
                                            
                                            <?php if( ($uid == $product['sellerid'] || $review['is_reviewer'] == 1) && $logged_in ) : ?>
                                                <span class="reply_btn_m">Reply</span>
                                                <div class="reply_area_m">
                                                    <!--<form method="post">-->
                                                    <?php echo form_open(); ?>
                                                    <input type="hidden" name="p_reviewid" value="<?php echo $review['id_review']?>">
                                                    <input type="hidden" name="id_product" value="<?php echo $product['id_product']?>">
                                                    <textarea class="reply_field_m form-control no-border" name="reply_field" cols=50 rows=4></textarea>
                                                    
                                                    <span class="reply_save_m orange_btn3">Save</span> 
                                                    <img src="/assets/images/orange_loader_small.gif" id="savereply_loadingimg_m" style="position: relative; top:12px; left:15px; display:none"/>
                                                    <span class="reply_cancel_m orange_btn3">Cancel</span>
                                                    <?php echo form_close();?>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                        
                                    <?php endforeach; ?>
                                    <div> 
                                        <center>
                                        <span id="see_more_reviews_m" style="font-weight:bold;"><a href="">See more reviews.</a></span> 
                                        <img src="/assets/images/orange_loader_small.gif" id="more_review_loading_img_m" style="position: relative; top:12px; left:15px; display:none; "/>
                                        <br/><br/>
                                        </center>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                </div>
            </div>
            <div class="col-md-2">
                
                
                <div class="recommendation_list display-when-desktop">
                    
                    <h5>Recommended</h5>
                    <ul>
                        <?PHP foreach ($recommended_items as $row): ?>                
                        <li style="display: inline-block;">
                            <span class="rec_item_container">
                            <a href="<?= "/item/".$row['slug'];?>" class="lnk_rec_item">
                                <img class="rec_item_img" src="/<?= $row['path'].'categoryview/'.$row['file']?>">
                            </a>
                            </span>
                            <p style="vertical-align: middle; margin-top: 5px;">
                                <a href="<?="/item/".$row['slug'];?>">
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
    </div>

    <div class="clear"></div>

    <div id="modal-background"></div>

    <div id="modal-container" class="font-roboto">
        <div id="modal-div-header">
            <h3 class="modal-header-h3">Send Message</h3>
            <button id="modal-close">&times;</button>        
        </div>
        <div id="modal-inside-container">
            
            <table style="margin-top: 10px;" width="100%">
                <tr>
                    <td width="15%" ><label>To : </label></td>
                    <td width="90%"><input class="input-name-rate" width="100%" type="text" value="<?=$product['sellerusername'];?>" disabled id="msg_name" name="msg_name" ></td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top: 10px;"><label>Message : </label></td>
                </tr>
                <tr>
                    <td colspan="2"> <textarea width="100%" rows="5" class="no-border textarea-message" name="msg-message" id="msg-message" placeholder="Say something.."></textarea></td>
                </tr>
            </table>
        </div>
        
        <center>
                <button class="btn btn-lg btn-block" id="modal_send_btn">Send</button>
        </center>
        
    </div>


    <input id='p_qty' type='hidden' value=' <?php echo json_encode($product_quantity);?>'>
    <input id='p_shipment' type='hidden' value='<?php echo json_encode($shipment_information);?>'>
    <input id='p_itemid' type='hidden' value='0'/>

    <input id='seller-id' type='hidden' value='<?php echo $product['sellerid']; ?>'/>
    <input id='seller-username' type='hidden' value='<?php echo $product['sellerusername']; ?>'/>
    <input id='user-id' type='hidden' value='<?php echo empty($uid) ? 0 : $uid;?>' />

</section>

<script type='text/javascript' src='/assets/js/src/bootstrap.js?ver=<?=ES_FILE_VERSION?>'></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.jqzoom-core.js"></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.idTabs.min.js"></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.raty.min.js"></script>
<script type="text/javascript" src="/assets/js/src/categorynavigation.js?ver=<?=ES_FILE_VERSION?>" ></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js'></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.validate.js'></script>
<script type="text/javascript" src="/assets/js/src/productpage.js?ver=<?=ES_FILE_VERSION?>" ></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.plugin.min.js"></script>
<script type="text/javascript" src="/assets/js/src/vendor/jquery.countdown.min.js"></script>
