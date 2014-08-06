<!DOCTYPE html>
<html lang="en">

	<link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?=base_url()?>assets/css/style.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?=base_url()?>assets/css/responsive_css.css" type="text/css" media="screen"/>

<div class="clear"></div>

<div class="container feeds-cont">
	<!-- LEFT PANEL -->
	<div class="col-md-3 ">
		<div class="row mrgin-bttm-8">
			<div class="col-md-12 ">
				<div class="pd-8-12 feed-cat">Categories</div>
			</div>
		</div>
		<div class="row mrgin-bttm-8">
			<div class="col-md-12 ">
				<div class="table-bordered">
					<div class="row ">
						<div class="col-md-12 ">
							<div class="border-bottom pd-8-12 title">Followed Sellers</div>
						</div>
					</div>
					<div class="row ">
						<div class="col-md-12 ">
							<div class="row">
								<div class="col-md-12">
									<?php if( count($followed_users) === 0 ):?>
										<p class="pd-8-12">No subscriptions yet</p>
									<?php else:?>
										<?php foreach($followed_users as $fu):?>
										<div class="media pd-8-12 ">
											<a class="pull-left" target="_blank" href="<?php echo base_url() . html_escape($fu['vendor_name'])?>">
												<img class="media-object" src="<?php echo base_url() . $fu['imgurl']?>">
											</a>
											<div class="media-body">
												<h5 class="title"><a target="_blank" href="<?php echo base_url() . html_escape($fu['vendor_name'])?>"><?php echo html_escape($fu['vendor_name'])?></a></h5>
												<?php echo $fu['datecreated']?>
											</div>
										</div>
										<?php endforeach;?>
									<?php endif;?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mrgin-bttm-8">
			<div class="col-md-12 ">
				<img class="img-responsive" src="<?=base_url()?>assets/user/default/banner.png">
			</div>
		</div>
	</div>
	
	<!-- MID PANEL -->
	<div class="col-md-6 ">
		<div class="row mrgin-bttm-8">
			<div class="col-md-12 ">
				<img class="img-responsive" src="<?=base_url()?>assets/user/default/banner.png">
			</div>
		</div>
		<div class="row mrgin-bttm-8">
			<div class="col-md-12">
				<div class="row mrgin-bttm-8">
					<div class="col-md-12 ">
						<div class="table-bordered pd-8-12 ">
							<div class="row">
								<div class="col-md-4 col-xs-4 feed-menu active"><a href="#featured_prod">Featured Products</a></div>
								<div class="col-md-4 col-xs-4 feed-menu"><a href="#new_prod">New Products</a></div>
								<div class="col-md-4 col-xs-4 feed-menu"><a href="#easy_treats">Easy Treats</a></div>
							</div>
						</div>
					</div>
				</div>
				<div class="media table-bordered mrgin-bttm-8 product feature">
					<div class="col-md-10 col-sm-10 media-sub media-content">
						<a class="pull-left" href="#">
							<img class="media-object" src="<?=base_url()?>assets/product/default/categoryview/default_product_img.jpg">
						</a>
						<div class="media-body">
							<div class="content">
								<h5 class="title">SALKSJDLKASD</h5>
								The following two divs contains a long text that will not fit in the box. As you can see, the text is clipped
							</div>
							<div class="condition">Condition: NEW</div>
						</div>
					</div>
					<div class="col-md-2 col-sm-2 media-sub media-btn-panel">
						<p>Php</p>
						<p class="feed-price">531,000.00</p>
						<div class="orange-btn">Buy Now</div>
					</div>
				</div>
				<div id="featured_prod" class="row feed-prod-cont" style="display:block;">
					<div class="col-md-12 ">
						<?php foreach( $featured_prod as $prod ):?>
						<div class="media table-bordered mrgin-bttm-8 product">
							<div class="col-md-10 col-sm-10 media-sub media-content">
								<a class="pull-left" target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>">
									<img class="media-object" src="<?php echo base_url() . $prod['path'] . "categoryview/" . $prod['file']?>">
								</a>
								<div class="media-body">
									<div class="content">
										<h5 class="title"><a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>"><?php echo $prod['name']?></a></h5>
										<?php echo $prod['brief']?>
									</div>
									<div class="condition">Condition: <?php echo $prod['condition']?></div>
								</div>
							</div>
							<div class="col-md-2 col-sm-2 media-sub media-btn-panel">
								<p>Php</p>
								<p class="feed-price"><?php echo $prod['price']?></p>
								<div class="orange-btn"><a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>">Buy Now</a></div>
							</div>
						</div>
						<?php endforeach;?>
						
						<div class="row mrgin-bttm-8 row-loadmore load_more_div">
							<div class="col-md-12">
								<?php echo form_open("",array("class"=>"load_more_form"));?>
								<input type="hidden" name="feed_page" value="1">
								<input type="hidden" name="feed_set" value="1">
								<input type="hidden" name="ids" value='<?php echo $fpID?>'>
								<?php echo form_close();?>
								<input type="button" class="orange-btn load-more feed_load_more" value="Load More">
							</div>
						</div>
					</div>
				</div>
				<div id="new_prod" class="row feed-prod-cont">
					<div class="col-md-12 ">
						<?php foreach( $new_prod as $prod ):?>
						<div class="media table-bordered mrgin-bttm-8 product">
							<div class="col-md-10 col-sm-10 media-sub media-content">
								<a class="pull-left" target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>">
									<img class="media-object" src="<?php echo base_url() . $prod['path'] . "categoryview/" . $prod['file']?>">
								</a>
								<div class="media-body">
									<div class="content">
										<h5 class="title"><a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>"><?php echo $prod['name']?></a></h5>
										<?php echo $prod['brief']?>
									</div>
									<div class="condition">Condition: <?php echo $prod['condition']?></div>
								</div>
							</div>
							<div class="col-md-2 col-sm-2 media-sub media-btn-panel">
								<p>Php</p>
								<p class="feed-price"><?php echo $prod['price']?></p>
								<div class="orange-btn"><a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>">Buy Now</a></div>
							</div>
						</div>
						<?php endforeach;?>
						
						<div class="row mrgin-bttm-8 row-loadmore load_more_div">
							<div class="col-md-12">
								<?php echo form_open("",array("class"=>"load_more_form"));?>
								<input type="hidden" name="feed_page" value="1">
								<input type="hidden" name="feed_set" value="2">
								<?php echo form_close();?>
								<input type="button" class="orange-btn load-more feed_load_more" value="Load More">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- RIGHT PANEL -->
	<div class="col-md-3 ">
		<div class="row mrgin-bttm-8">
			<div class="col-md-12 ">
				<div class="table-bordered">
					<div class="row ">
						<div class="col-md-12 ">
							<div class="border-bottom pd-8-12 title">Popular Items</div>
						</div>
					</div>
					<div class="row ">
						<div class="col-md-12 ">
							<div class="row ">
								<div class="col-md-12 ">
									<div class="media pd-8-12 ">
										<a class="pull-left" href="#">
											<img class="media-object" src="<?=base_url()?>assets/user/default/60x60.png">
										</a>
										<div class="media-body">
											<h5 class="title">Walking Shoes</h5>
											P 450.00
										</div>
									</div>
									<div class="media pd-8-12 ">
										<a class="pull-left" href="#">
											<img class="media-object" src="<?=base_url()?>assets/user/default/60x60.png">
										</a>
										<div class="media-body">
											<h5 class="title">Sexy Grinder</h5>
											P 250.00
										</div>
									</div>
									<div class="media pd-8-12 ">
										<a class="pull-left" href="#">
											<img class="media-object" src="<?=base_url()?>assets/user/default/60x60.png">
										</a>
										<div class="media-body">
											<h5 class="title">Transparent Undies</h5>
											P 150.00
										</div>
									</div>
									<div class="media pd-8-12 ">
										<a class="pull-left" href="#">
											<img class="media-object" src="<?=base_url()?>assets/user/default/60x60.png">
										</a>
										<div class="media-body">
											<h5 class="title">Expired Milk</h5>
											P 2,550.00
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mrgin-bttm-8">
			<div class="col-md-12 ">
				<img class="img-responsive" src="<?=base_url()?>assets/user/default/banner.png">
			</div>
		</div>
		<div class="row mrgin-bttm-8">
			<div class="col-md-12 ">
				<div class="table-bordered">
					<div class="row ">
						<div class="col-md-12 ">
							<div class="border-bottom pd-8-12 title">Promo Items</div>
						</div>
					</div>
					<div class="row ">
						<div class="col-md-12 ">
							<div class="row ">
								<div class="col-md-12 ">
									<div class="media pd-8-12 ">
										<a class="pull-left" href="#">
											<img class="media-object" src="<?=base_url()?>assets/user/default/60x60.png">
										</a>
										<div class="media-body">
											<h5 class="title">Walking Shoes</h5>
											P 450.00
										</div>
									</div>
									<div class="media pd-8-12 ">
										<a class="pull-left" href="#">
											<img class="media-object" src="<?=base_url()?>assets/user/default/60x60.png">
										</a>
										<div class="media-body">
											<h5 class="title">Sexy Grinder</h5>
											P 250.00
										</div>
									</div>
									<div class="media pd-8-12 ">
										<a class="pull-left" href="#">
											<img class="media-object" src="<?=base_url()?>assets/user/default/60x60.png">
										</a>
										<div class="media-body">
											<h5 class="title">Transparent Undies</h5>
											P 150.00
										</div>
									</div>
									<div class="media pd-8-12 ">
										<a class="pull-left" href="#">
											<img class="media-object" src="<?=base_url()?>assets/user/default/60x60.png">
										</a>
										<div class="media-body">
											<h5 class="title">Expired Milk</h5>
											P 2,550.00
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>

<script type="text/javascript" src="<?=base_url()?>assets/js/src/feed.js"></script>

</html>