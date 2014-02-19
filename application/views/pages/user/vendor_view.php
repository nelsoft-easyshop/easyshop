<!--[if lt IE 10]>
<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/excanvas.js"></script>
<style>
.item_attr_content ul li {
    width: 25%;
    float: left
}
</style>
<![endif]-->
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.Jcrop.min.js'></script>
<link type="text/css" href="<?=base_url()?>assets/css/jquery.Jcrop.min.css" rel="stylesheet" media='screen'/>
<!-- ProgressBar / Widget CSS file TEMPORARY-->
<link type="text/css" href="<?=base_url()?>assets/css/jquery-ui.css" rel="stylesheet" />
<!--Memberpage Modal custom CSS-->
<link type="text/css" href="<?=base_url()?>assets/css/memberpage.css" rel="stylesheet" media='screen'/>

<script src="<?=base_url()?>assets/JavaScript/js/jquery.raty.min.js" type="text/javascript"></script>

<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.jqpagination.min.js'></script>
<link  type="text/css"  href='<?=base_url()?>assets/css/jqpagination.css' rel="stylesheet" media='screen'/>


<!-- MEMBERPAGE JS-->
<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/memberpage.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=&sensor=false"></script>


<div id = "member_page_body">
	<script src="<?=base_url()?>assets/JavaScript/js/jquery.idTabs.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets/JavaScript/js/jquery.knob.js" type="text/javascript"></script>
	<div class="clear"></div>
	<section>
		<div class="wrapper profile_content">
			<div class="logo">
			    <a href="<?=base_url()?>home"><img src="<?=base_url()?>assets/images/img_logo.png" alt="Logo"></a>
			</div>
			<div class="profile_top_nav">
				<div>
					<ul>
						<li><a href="<?=base_url()?>home">Home</a></li>
						<li><a href="<?=base_url()?>home">Homepage</a></li>
						<li>
							<span style="color: #FFFFFF;padding: 9px;">Setup</span>
							<ul>
								<li><a href="javascript:void(0)" onclick="triggerTab('dashboard');">Dashboard</a></li>
							</ul>
						</li>
						<li><a href="<?=base_url()?>home/under_construction">News</a></li>
					</ul>
				</div>
				<div>
					<input type="text">
					<button class="search_btn2"> </button>
				</div>         	
			</div>
		</div>
	</section>
	<div class="clear"></div>
	<section>
		<div class="wrapper profile_wrapper">
			<div class="profile_left_nav">
				<div>
					<div class="vendor-avatar">
						<?php echo $image_profile?>	
					</div>
					<div class="vendor-profile">
						<p>Joined: <?php echo $vendordetails['datecreated'];?></p>
						<p><img src="<?=base_url()?>assets/images/icon_map.png"> From: <?php echo $vendordetails['city'] . " " . $vendordetails['region'];?></p>
					</div>
				</div>
			</div>

			<div class="profile_main_content" id="dashboard">
				<h2><?php echo $vendordetails['username'];?></h2>
				<div class="progress_bar_panel">
					<div>
						<h3>Total Posted Items</h3>
						<input class="items" data-width="150" data-fgColor="#FF8400" data-max="1000" data-thickness=".1" data-angleOffset="180" data-readOnly=true data-displayPrevious=true value="<?php echo count($active_products)+count($deleted_products);?>">
					</div>
					<div>
						<h3>Active Items</h3>
						<input class="items" data-width="150" data-fgColor="#ff4400" data-max="1000" data-thickness=".1" data-angleOffset="180" data-readOnly=true data-displayPrevious=true value="<?php echo count($active_products);?>">
					</div>
					<div>
						<h3>Sold Items</h3>
						<input class="items" data-width="150" data-fgColor="#7ad014" data-max="1000" data-thickness=".1" data-angleOffset="180" data-readOnly=true data-displayPrevious=true value="0">
					</div>
				</div>
				<div class="posted_feedbacks_top">
					<h3 class="fm1 f18">Feedback Score:</h3>
					<span>(<?php echo $allfeedbacks['rcount'];?> Feedback/s received)</span>
					<p>Rating 1: 
					<span>
					<?php if($allfeedbacks['rating1'] === 0 ):?>
						<?php echo $vendordetails['username'];?> have not received ratings yet.
					<?php else:?>
						<?php for($i = 0; $i < $allfeedbacks['rating1']; $i++):?>
							<img src="<?=base_url()?>assets/images/star-on.png">
						<?php endfor;?>
						<?php for($i = 0; $i < 5-$allfeedbacks['rating1']; $i++):?>
							<img src="<?=base_url()?>assets/images/star-off.png">
						<?php endfor;?>
					<?php endif;?>
					</span>
					</p>
					<p>Rating 2: 
					<span><?php if($allfeedbacks['rating2'] === 0 ):?>
						<?php echo $vendordetails['username'];?> have not received ratings yet.
					<?php else:?>
						<?php for($i = 0; $i < $allfeedbacks['rating2']; $i++):?>
							<img src="<?=base_url()?>assets/images/star-on.png">
						<?php endfor;?>
						<?php for($i = 0; $i < 5-$allfeedbacks['rating2']; $i++):?>
							<img src="<?=base_url()?>assets/images/star-off.png">
						<?php endfor;?>
					<?php endif;?>
					</span>
					</p>
					<p>Rating 3: 
					<span>
					<?php if($allfeedbacks['rating3'] === 0 ):?>
						<?php echo $vendordetails['username'];?> have not received ratings yet.
					<?php else:?>
						<?php for($i = 0; $i < $allfeedbacks['rating3']; $i++):?>
							<img src="<?=base_url()?>assets/images/star-on.png">
						<?php endfor;?>
						<?php for($i = 0; $i < 5-$allfeedbacks['rating3']; $i++):?>
							<img src="<?=base_url()?>assets/images/star-off.png">
						<?php endfor;?>
					<?php endif;?>
					</span>
					</p>
				</div>
				<div class="clear"></div>
				<div>
					<ul class="idTabs post_items">
						<li><a href="#active_items">Active Items <span><?php echo count($active_products);?></span></a></li>
						<!-- <li><a href="#latest_post_item">Latest Post Items <span>0</span></a></li> -->
						<li><a href="#sold_items">Sold Items <span>0</span></a></li>
						<li><a href="#sold_items">Sold Out Items <span>0</span></a></li>
						<li><a href="#dashboard-feedbacks">Feedbacks <span><?php echo $allfeedbacks['afbcount'];?></span></a></li>
						<!-- <li><input type="text" value="search"><input type="submit" value="Submit"></li> -->
					</ul>
				</div>
				<div class="clear"></div>
				
				<?php $items_per_page = 10; ?>

				<div class="dashboard_table" id="active_items">
					<h2>Active Items</h2>
					<?php if(count($active_products) == 0):?>
						<p><strong>No items on sale.</strong></p>
					<?php else:?>
					<div class="pagination" id="pagination_active">
						<a href="#" class="first" data-action="first">&laquo;</a>
						<a href="#" class="previous" data-action="previous">&lsaquo;</a>
						<input type="text" readonly="readonly" data-max-page="<?php echo (count($active_products)===0)?1:(ceil(count($active_products)/$items_per_page));?>" />
						<a href="#" class="next" data-action="next">&rsaquo;</a>
						<a href="#" class="last" data-action="last">&raquo;</a>
					</div>
					<div class="paging">					
					<?php $product_counter = 0; 
					     foreach($active_products as $active_product): ?>
							<div class="post_items_content">
								
								<div class="post_item_content_left">
									<img src="<?php echo base_url().$active_product['path'].'categoryview/'.$active_product['file']; ?>" class="product_img">
									<p>
                                        <?php $rounded_score = round($active_product['average_rating']); ?>
										<?php for($i = 0; $i < $rounded_score;$i++): ?>
											<img src="<?=base_url()?>assets/images/star-on.png">
										<?php endfor; ?>
										<?php for($i = 0; $i < 5-$rounded_score;$i++): ?>
											<img src="<?=base_url()?>assets/images/star-off.png">
										<?php endfor; ?>
										<br />
										<img src="<?=base_url()?>assets/images/img_icon_review.png" class="reviews"><?php echo $active_product['review_count']; ?> Reviews
									</p>
								</div>
								<div class="post_item_content_right">
									<div class="product_title_container">
										<p class="post_item_product_title fm1"><a href="<?=base_url();?>item/<?php echo $active_product['id_product']."/".es_url_clean($active_product['name']);?>"><?php echo html_escape($active_product['name']);?></a></p>
								  	</div>
									<div class="price_container">
										<p><span class="fm1 f24 orange">Php <?php echo number_format($active_product['price'],2,'.',',');?></span><br />Price</p>
										<p><span class="fm1 f24 grn">xx</span><br />Sold Items</p>
										<p><span class="fm1 f24">xx</span><br />Available Stock</p>
									</div>
									<p><strong>Description:</strong><br />
										<span class="item_prod_desc_content">
											<?php echo html_escape($active_product['brief']); ?>
										</span>
										<span class="show_prod_desc blue f11">Read more</span>
									</p>
								  	<div class="clear"></div>
								  <p class="post_item_category">
									<strong>Category:</strong><br />
									<?php foreach($active_product['parents'] as $parent):?>
										<?php echo $parent;?><?php echo (end($active_product['parents'])===$parent)?'':'<img src="'.base_url().'assets/images/img_arrow_right.png">'; ?>
									<?php endforeach; ?>
								  </p>
									
									<div class="show_more_options blue"><p>View Features and Specifications</p></div>
									<div class="attr_hide">
								    	<?php $i = 0; 
									      foreach($active_product['data_attr'] as $key=>$data_attr): ?>								
													<div class="item_attr_container">
													<div class="item_attr"><?php echo html_escape($key); ?>:</div>
													<div class="item_attr_content">
													<ul>
													<?php foreach($data_attr as $foo): ?>
													
														<li><span><?php echo html_escape($foo['value']);?></span></li>
													
													<?php endforeach; $i++;?>
													</ul>
													</div>
													</div>
										<?php endforeach; ?>
									</div>	
							
 
								</div>
							</div>
							
							<?php $product_counter++; ?>
							<?php if($product_counter === $items_per_page): $product_counter = 0; ?>
								</div><div class="paging">
							<?php endif;  ?>
							
					<?php endforeach; ?>
					</div> 	
					<?php endif;?>
				</div>

				<div id="dashboard-feedbacks">
					<h2>Feedbacks</h2>
						<ul class="idTabs feedbacks_tabs">
							<li><a href="#op_buyer">Others posted (<?php echo $vendordetails['username'];?> as Buyer)</a></li>
							<li><a href="#op_seller">Others posted (<?php echo $vendordetails['username'];?> as Seller)</a></li>
							<li><a href="#yp_buyer"><?php echo $vendordetails['username'];?> as Buyer</a></li>
							<li><a href="#yp_seller"><?php echo $vendordetails['username'];?> as Seller</a></li>
						</ul>

						<div class="clear"></div>
						<div id="others_post">

							<div id="op_buyer">
								<h4>Feedbacks others posted where <?php echo $vendordetails['username'];?> is buyer</h4>
								<?php if(count($allfeedbacks['otherspost_buyer'])==0):?>
									<p><strong><?php echo $vendordetails['username'];?> have not yet received any feedbacks for this category.</strong></p>
								<?php else:?>
									<?php $afb_counter = 0;?>
									<div class="paging posted_feedbacks">
									<?php foreach($allfeedbacks['otherspost_buyer'] as $k=>$tempafb):?>
											<div>
												<h3>Feedback from Transaction#: <?php echo $k;?></h3>
												<?php foreach($tempafb as $key=>$afb):?>
												<p>From: <a href="<?php echo base_url();?>vendor/<?php echo $afb['member_name'];?>"><?php echo $afb['member_name'];?></a> | on: <?php echo $afb['dateadded'];?></p>
												<p>"<?php echo $afb['feedb_msg']?>"</p>
												<p>Rating 1: 
													<?php for($i = 0; $i < $afb['rating1']; $i++):?>
														<img src="<?=base_url()?>assets/images/star-on.png">
													<?php endfor;?>
													<?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
														<img src="<?=base_url()?>assets/images/star-off.png">
													<?php endfor;?>
												</p>
												<p>Rating 2: 
													<?php for($i = 0; $i < $afb['rating2']; $i++):?>
														<img src="<?=base_url()?>assets/images/star-on.png">
													<?php endfor;?>
													<?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
														<img src="<?=base_url()?>assets/images/star-off.png">
													<?php endfor;?>
												</p>
												<p>Rating 3: 
													<?php for($i = 0; $i < $afb['rating3']; $i++):?>
														<img src="<?=base_url()?>assets/images/star-on.png">
													<?php endfor;?>
													<?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
														<img src="<?=base_url()?>assets/images/star-off.png">
													<?php endfor;?>
												</p>
												<?php endforeach;?>
											</div>
											<?php $afb_counter++;?>
											<?php if($afb_counter === $items_per_page): $afb_counter = 0;?>
												</div><div class="paging">
											<?php endif;?>
										
									<?php endforeach;?>
									</div>
									<div class="pagination" id="pagination-opbuyer">
										<a href="#" class="first" data-action="first">&laquo;</a>
										<a href="#" class="previous" data-action="previous">&lsaquo;</a>
										<input type="text" readonly="readonly" data-max-page="<?php echo (count($allfeedbacks['otherspost_buyer'])===0)?1:(ceil(count($allfeedbacks['otherspost_buyer'])/$items_per_page));?>" />
										<a href="#" class="next" data-action="next">&rsaquo;</a>
										<a href="#" class="last" data-action="last">&raquo;</a>
									</div>
								<?php endif;?>						
							</div>
						
						
							<div id="op_seller">
								<h4>Feedbacks others posted where <?php echo $vendordetails['username'];?> is seller</h4>
								<?php if(count($allfeedbacks['otherspost_seller'])==0):?>
									<p><strong><?php echo $vendordetails['username'];?> have not yet received any feedbacks for this category.</strong></p>
								<?php else:?>
									<?php $afb_counter = 0;?>
									<div class="paging posted_feedbacks">
									<?php foreach($allfeedbacks['otherspost_seller'] as $k=>$tempafb):?>
										
											<div>
												<h3>Feedback from Transaction#: <?php echo $k;?></h3>
												<?php foreach($tempafb as $afb):?>
												<p>From: <a href="<?php echo base_url();?>vendor/<?php echo $afb['member_name'];?>"><?php echo $afb['member_name'];?></a> | on: <?php echo $afb['dateadded'];?></p>
												<p>"<?php echo $afb['feedb_msg']?>"</p>
												<p>Rating 1: 
													<?php for($i = 0; $i < $afb['rating1']; $i++):?>
														<img src="<?=base_url()?>assets/images/star-on.png">
													<?php endfor;?>
													<?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
														<img src="<?=base_url()?>assets/images/star-off.png">
													<?php endfor;?>
												</p>
												<p>Rating 2: 
													<?php for($i = 0; $i < $afb['rating2']; $i++):?>
														<img src="<?=base_url()?>assets/images/star-on.png">
													<?php endfor;?>
													<?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
														<img src="<?=base_url()?>assets/images/star-off.png">
													<?php endfor;?>
												</p>
												<p>Rating 3: 
													<?php for($i = 0; $i < $afb['rating3']; $i++):?>
														<img src="<?=base_url()?>assets/images/star-on.png">
													<?php endfor;?>
													<?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
														<img src="<?=base_url()?>assets/images/star-off.png">
													<?php endfor;?>
												</p>
												<?php endforeach;?>
											</div>
											<?php $afb_counter++;?>
											<?php if($afb_counter === $items_per_page): $afb_counter = 0;?>
												</div><div class="paging">
											<?php endif;?>
									<?php endforeach;?>
									</div>
									<div class="pagination" id="pagination-opseller">
										<a href="#" class="first" data-action="first">&laquo;</a>
										<a href="#" class="previous" data-action="previous">&lsaquo;</a>
										<input type="text" readonly="readonly" data-max-page="<?php echo (count($allfeedbacks['otherspost_seller'])===0)?1:(ceil(count($allfeedbacks['otherspost_seller'])/$items_per_page));?>" />
										<a href="#" class="next" data-action="next">&rsaquo;</a>
										<a href="#" class="last" data-action="last">&raquo;</a>
									</div>
								<?php endif;?>
							</div>
						<div id="yp_buyer">
							<h4>Feedbacks <?php echo $vendordetails['username'];?> posted as buyer</h4>
							<?php if(count($allfeedbacks['youpost_buyer'])==0):?>
								<p><strong><?php echo $vendordetails['username'];?> have not yet posted any feedbacks for this category.</strong></p>
							<?php else:?>
							<?php $afb_counter = 0;?>
								<div class="paging posted_feedbacks">
								<?php foreach($allfeedbacks['youpost_buyer'] as $k=>$tempafb):?>
									<div>
										<h3>Feedback from Transaction#: <?php echo $k;?></h3>
										<?php foreach($tempafb as $afb):?>
										<p>For: <a href="<?php echo base_url();?>vendor/<?php echo $afb['for_membername'];?>"><?php echo $afb['for_membername'];?></a> | on: <?php echo $afb['dateadded'];?></p>
										<p>"<?php echo $afb['feedb_msg']?>"</p>
										<p>Rating 1: 
											<?php for($i = 0; $i < $afb['rating1']; $i++):?>
												<img src="<?=base_url()?>assets/images/star-on.png">
											<?php endfor;?>
											<?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
												<img src="<?=base_url()?>assets/images/star-off.png">
											<?php endfor;?>
										</p>
										<p>Rating 2: 
											<?php for($i = 0; $i < $afb['rating2']; $i++):?>
												<img src="<?=base_url()?>assets/images/star-on.png">
											<?php endfor;?>
											<?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
												<img src="<?=base_url()?>assets/images/star-off.png">
											<?php endfor;?>
										</p>
										<p>Rating 3: 
											<?php for($i = 0; $i < $afb['rating3']; $i++):?>
												<img src="<?=base_url()?>assets/images/star-on.png">
											<?php endfor;?>
											<?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
												<img src="<?=base_url()?>assets/images/star-off.png">
											<?php endfor;?>
										</p>
										<?php endforeach;?>
									</div>
									<?php $afb_counter++;?>
									<?php if($afb_counter === $items_per_page): $afb_counter = 0;?>
										</div><div class="paging">
							<?php endif;?>	
								<?php endforeach;?>
								</div>
								<div class="pagination" id="pagination-ypbuyer">
									<a href="#" class="first" data-action="first">&laquo;</a>
									<a href="#" class="previous" data-action="previous">&lsaquo;</a>
									<input type="text" readonly="readonly" data-max-page="<?php echo (count($allfeedbacks['youpost_buyer'])===0)?1:(ceil(count($allfeedbacks['youpost_buyer'])/$items_per_page));?>" />
									<a href="#" class="next" data-action="next">&rsaquo;</a>
									<a href="#" class="last" data-action="last">&raquo;</a>
								</div>
							<?php endif;?>
						</div>
						<div id="yp_seller">
							<h4>Feedbacks <?php echo $vendordetails['username'];?> posted as seller</h4>
							<?php if(count($allfeedbacks['youpost_seller'])==0):?>
								<p><strong><?php echo $vendordetails['username'];?> have not yet posted any feedbacks for this category.</strong></p>
							<?php else:?>
							<?php $afb_counter = 0;?>
								<div class="paging posted_feedbacks">
								<?php foreach($allfeedbacks['youpost_seller'] as $k=>$tempafb):?>
										<div>
											<h3>Feedback from Transaction#: <?php echo $k;?></h3>
											<?php foreach($tempafb as $afb):?>
											<p>For: <a href="<?php echo base_url();?>vendor/<?php echo $afb['for_membername'];?>"><?php echo $afb['for_membername'];?></a> | on: <?php echo $afb['dateadded'];?></p>
											<p>"<?php echo $afb['feedb_msg']?>"</p>
											<p>Rating 1: 
												<?php for($i = 0; $i < $afb['rating1']; $i++):?>
													<img src="<?=base_url()?>assets/images/star-on.png">
												<?php endfor;?>
												<?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
													<img src="<?=base_url()?>assets/images/star-off.png">
												<?php endfor;?>
											</p>
											<p>Rating 2: 
												<?php for($i = 0; $i < $afb['rating2']; $i++):?>
													<img src="<?=base_url()?>assets/images/star-on.png">
												<?php endfor;?>
												<?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
													<img src="<?=base_url()?>assets/images/star-off.png">
												<?php endfor;?>
											</p>
											<p>Rating 3: 
												<?php for($i = 0; $i < $afb['rating3']; $i++):?>
													<img src="<?=base_url()?>assets/images/star-on.png">
												<?php endfor;?>
												<?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
													<img src="<?=base_url()?>assets/images/star-off.png">
												<?php endfor;?>
											</p>
											<?php endforeach;?>
										</div>
										<?php $afb_counter++;?>
										<?php if($afb_counter === $items_per_page): $afb_counter = 0;?>
											</div><div class="paging">
										<?php endif;?>
								<?php endforeach;?>
								</div>
								<div class="pagination" id="pagination-ypseller">
									<a href="#" class="first" data-action="first">&laquo;</a>
									<a href="#" class="previous" data-action="previous">&lsaquo;</a>
									<input type="text" readonly="readonly" data-max-page="<?php echo (count($allfeedbacks['youpost_seller'])===0)?1:(ceil(count($allfeedbacks['youpost_seller'])/$items_per_page));?>" />
									<a href="#" class="next" data-action="next">&rsaquo;</a>
									<a href="#" class="last" data-action="last">&raquo;</a>
								</div>
							<?php endif;?>
						</div>
							</div>
				
				</div>

			</div>