<link type="text/css" href="<?=base_url()?>assets/css/sell_item.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<link type="text/css" href="<?=base_url()?>assets/css/responsive_css.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet"  media="screen"/>
<link rel="stylesheet" href="<?=base_url()?>assets/css/product_preview.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link type="text/css" href="<?=base_url()?>assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<div class="container">
	<div class="seller_product_content row">
		<div class="inner_seller_product_content pd-bottom-20">
			<h2 class="f24">Sell an Item</h2>
				<div class="sell_steps sell_steps4">
					<ul> 
                    <li class="steps_txt_hide">
                        <span class="span_bg left-arrow-shape2"></span>
                        <span class="steps_txt">
                           Step 1: Select Category
                        </span>
                        <span class="span_bg right-arrow-shape"></span>
                    </li>
                    <li class="steps_txt_hide">
                        <span class="span_bg left-arrow-shape2"></span>
                        <span class="steps_txt">
                          Step 2: Upload Item
                        </span>
                         <span class="span_bg right-arrow-shape"></span>
                    </li>                   
                    <li>
                        <span class="span_bg left-arrow-shape ar_active"></span>
                        <span class="steps_txt_active">
                          <span class="f18">Step 3:</span> Success
                        </span>
                         <span class="span_bg right-arrow-shape ar_r_active"></span>
                    </li>
                  </ul>
				</div>
			<div class="clear"></div>
		</div>
		
		<div class="step4_section">
			<div class="step4_header">
				<h5>How you will be paid</h5>
			</div>
			<div class="step4_content step4_paysel">
				<?php if( count($product_billingdetails) !== 0 ):?>
				<div class="step4_bankdetails col-sx-12 col-sm-9 col-md-9">
					<h5 class="text-uppercase"><strong>Bank Deposit</strong></h5>
					<div>
						<div class="col-xs-3 col-sm-4 col-md-3"><strong>Bank account name:</strong></div>	
						<div class="col-xs-9 col-sm-8 col-md-9"><?php echo $product_billingdetails['bank_account_name']?></div>
					</div>
					<div class="clear"></div>
					<div>
						<div class="col-xs-3 col-sm-4 col-md-3"><strong>Bank account number:</strong></div>
						<div class="col-xs-9 col-sm-8 col-md-9"><?php echo $product_billingdetails['bank_account_number']?></div>
					</div>
					<div class="clear"></div>
					<div>
						<div class="col-xs-3 col-sm-4 col-md-3"><strong>Bank name:</strong></div>
						<div class="col-xs-9 col-sm-8 col-md-9"><?php echo $product_billingdetails['bank_name']?></div>
					</div>
					<div class="clear"></div>
				</div>
				<?php endif;?>
				<?php if( (int)$product['is_cod'] === 1 ):?>
				<div class="step4-cod col-sx-12 col-sm-3 col-md-3">
					<span class="cod-images"></span>
					<div class="cod-button">
						<span>Cash on Delivery</span>
					</div>
				</div>
				<?php endif;?>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="step4_section">
			<div class="step4_header">
				<h5>Product Delivery</h5>
			</div>
			<div class="step4_content step4_delivery">
				<?php if( (int)$product['is_meetup'] === 1 ):?>
					<div class="mrgin-bttm-10">
						<div class="">
							<div class="ok-btn glyphicon glyphicon-ok pd-8-12"></div> 
							<span class="pd-lr-10">For meetup</span>
						</div>
					</div>
				<?php endif;?>
				<?php if( $shipping_summary['is_delivery'] ):?>
					<div>
						<div class="">
							<div class="ok-btn glyphicon glyphicon-ok pd-8-12"></div> 
							<span class="pd-lr-10">For delivery</span>
						</div>
						<div class="step4_delivery_sub">
							
							<div class="clear"></div>
						<?php if( $shipping_summary['is_freeshipping'] ):?>
							<p>Free shipping</p>
						<?php elseif( $shipping_summary['has_shippingsummary'] ):?>
							<p><strong>Shipping Details:</strong></p>
							<?php foreach( $shipping_summary['shipping_display'] as $garr ):?>
							<div>
								<div class="clear"></div>
								<div class="pd-8-12">
									<?php foreach( $garr['location'] as $price=>$locarr ):?>
									<div class="row col-sx-mrgn">
										<div class="col-sx-12 col-sm-4 col-md-4">
											Php
											<div class="delivery-sub-box width-70p pd-8-12"><?php echo html_escape($price)?></div>
										</div>									
									<!-- </div>
									<div class="row col-sx-mrgn"> -->
										<div class="col-sx-12 col-sm-8 col-md-8">
											<span class="display-ib line-height">Locations:</span>
											<div class="delivery-sub-box width-75p vrtcl-top pd-lr-10 pd-top-4">
												<?php foreach($locarr as $locID):?>
												<span class="delivery-sub-box-item"><?php echo $shipping_summary['location_lookup'][$locID]?></span>
												<?php endforeach;?>
											</div>
										</div>
									</div>
									<?php endforeach;?>
									<div class="clear"></div>
									<div class="step4_attr">
										
										<div class="col-sx-12 col-sm-12 col-md-12">
											<?php if( (int)$attr['has_attr']===0 ):?>
												<p>&bull; All Combinations</p>
											<?php else:?>
												<?php foreach($garr['attr'] as $attrID): ?>
													<p><span class="glyphicon glyphicon-chevron-right"></span>
														<?php foreach( $attr['attributes'][$attrID] as $pattr ):?>
															<?php echo $pattr['name'] . ' : ' . $pattr['value'] . ' '?>
														<?php endforeach;?>
													</p>
												<?php endforeach; ?>
											<?php endif;?>
										</div>
									</div>
									<div class="clear"></div>
								</div>
							</div>
							<?php endforeach;?>
						<?php endif;?>
						</div>
					</div>
				<?php endif;?>
			</div>
		</div>
		
		<div style="margin-top:3em;">
			<div>
				<h4><strong>Product Preview</strong></h4>
				<div style="max-height:100%; border:1px solid #CECECE;" class="pd-8-12">
					<div class="clear"></div>
					<section class="top_margin">
					  <!-- <div class="prod_preview_bread_crumbs">
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
					  </div> -->
					</section>
					<section class="step4_prod_preview">
						<div class="row">
							<div class="col-md-4">
								<div id="product_content_gallery" class="step4_prod_cont_gal">
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
					 
						
						<div class="col-md-8">
						<h4 class="id-class" id="<?php echo $product['id_product'];?>"> 
						  <span id="pname"> <?php echo html_escape($product['product_name'])?> </span> 
						  <!-- <span class="seller-name"> 
							  <img src="<?php echo base_url() . $product['userpic']?>/60x60.png"><br />
							  <span><?php echo html_escape($product['sellerusername']);?></span> 
							  <p style='font-size:8px;'>No ratings received.</p>
						  </span>  -->
						</h4>
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
							<input type="text" value ="1" class="product_quantity" disabled>
						  </div>
						</div>
						<div class="price_details">
						
						
			 
						<div class="price_box col-sx-4 col-sm-4 col-md-4">
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
						  
						  
						  <div class="availability col-sx-4 col-sm-4 col-md-4">
							<p> Availability <br />
								<span class="quantity" id="p_availability"><?php echo $availability;?></span>
							</p>
						  </div>
						  <div class="buy_box col-sx-4 col-sm-4 col-md-4"> 
							<!-- <a href="JavaScript:void(0)" id="send" class="fm1 preview_buy_btn disabled">Buy Now</a> <br/>
							<span>Delivers in 5-8 business days*</span>  -->
						  </div>
						</div>
						<div class="clear"></div>
						<p class="product_content_payment"> <strong>Payment:</strong><br />
						  <span class="mastercard"></span>
						  <span class="visa"></span>
						  <span class="paypal"></span>
						</p>
					  </div>
					  <div class="clear"></div>
					  
					  <div id="tabs" class="prod_previews_tabs">
						<ul>
						  <li><a href="#tabs-1">Product Details</a></li>
						  <li><a href="#tabs-2">Specification</a></li>						  
						</ul>
						
						<div class="clear"></div>
					   
						<div id="tabs-1" class="col-sx-12 col-sm-12 col-md-12">
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
					  </div>
					  
					 					  
					  </div>
					  <div class="clear"></div>
					</section>

				</div>
			</div>
		</div>
	</div>

	<div class="clear"></div>  
	<div class="row">
		<div class="col-sx-12 col-sm-12 col-md-12 text-center">
			<div class="pd-tb-20">
				<a href="/sell/step1" target="_blank" class="orange_btn3 vrtcl-mid">Sell another Item</a>
				<a href="/item/<?php echo $product['slug']?>" target="_blank" class="btn btn-default">View Product</a>
			</div>
		</div>
	</div>
	<div class="clear"></div>
<script src="<?=base_url()?>assets/js/src/vendor/jquery.idTabs.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$('#tabs').tabs();
</script>
