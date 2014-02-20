<!--[if lt IE 10]>
<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/excanvas.js"></script>
<style>
.item_attr_content ul li {
    width: 25%;
    float: left
}
</style>
<![endif]-->

<!-- ProgressBar / Widget CSS file TEMPORARY-->
<!-- <link type="text/css" href="<?=base_url()?>assets/css/jquery-ui.css" rel="stylesheet" /> -->

<!--Memberpage Modal custom CSS-->
<link type="text/css" href="<?=base_url()?>assets/css/memberpage.css" rel="stylesheet" media='screen'/>
<!--Pagination CSS-->
<link  type="text/css"  href='<?=base_url()?>assets/css/jqpagination.css' rel="stylesheet" media='screen'/>
<!--Jcrop CSS-->
<link type="text/css" href="<?=base_url()?>assets/css/jquery.Jcrop.min.css" rel="stylesheet" media='screen'/>


<div id = "member_page_body">
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
						<li>
							<span style="color: #FFFFFF;padding: 9px;">Setup</span>
							<ul>
								<li><a href="javascript:void(0)" onclick="triggerTab('dashboard');">Dashboard</a></li>
                                <!-- <li><a href="javascript:void(0)" onclick="triggerTab('wishlist');">Wishlist</a></li> -->
								<li><a href="javascript:void(0)" onclick="triggerTab('personal_information');">Personal Information</a></li>
								<li><a href="javascript:void(0)" onclick="triggerTab('delivery_address');">Delivery Address</a></li>
								<li><a href="javascript:void(0)" onclick="triggerTab('transactions');">Transactions</a></li>
                                <!-- <li><a href="javascript:void(0)" onclick="triggerTab('privacy_settings');">Privacy Settings</a></li> -->
                                <li><a href="javascript:void(0)" onclick="triggerTab('security_settings');">Security Setting</a></li>

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
					<div class="avatar">
						<div class="avatar_edit" style="width:45px; position: relative; float:right"><img name='avatar_edit_icon' src="<?=base_url()?>assets/images/img_edit.png">Edit</div>
						<?php echo $image_profile?>	
						<?php echo form_open_multipart('memberpage/upload_img', 'id="form_image"');?>
							<input type="file" style="display:none" id="imgupload" name="userfile"/>
							<input type='hidden' name='x' value='0' id='image_x'>
							<input type='hidden' name='y' value='0' id='image_y'>
							<input type='hidden' name='w' value='0' id='image_w'>
							<input type='hidden' name='h' value='0' id='image_h'>
						</form>
					</div>
					<div id="div_user_image_prev">
						<span> Crop your Photo! </span>
						<img src="" id="user_image_prev">
						<button>OK</button>
					</div>
					<div class="profile_completeness">
						<span>Profile Completeness</span>
						<span id="profprog_percentage" value=""></span>
						<div id="progressbar" class="profile_progress"></div>
					</div>
				</div>
				<div>
						<ul class="idTabs member_side_nav"> 
							<li><a href="#dashboard">Dashboard</a></li>
							<!-- <li><a href="#wishlist">Wishlist</a></li> -->
							<li><a href="#personal_information">Personal Information</a></li>
							<li><a href="#delivery_address">Delivery Address</a></li>
							<li><a href="#transactions">Transactions</a></li>
							<!-- <li><a href="#privacy_settings">Privacy Settings</a></li> -->
							<li><a href="#security_settings">Security Settings</a></li>
						</ul> 
					</div>	
			</div>

			<div class="profile_main_content" id="dashboard">
				<h2>Dashboard</h2>
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
						You have not received ratings yet.
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
						You have not received ratings yet.
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
						You have not received ratings yet.
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
						<li><a href="#deleted_items">Deleted Items<span><?php echo count($deleted_products);?></span></a></li>
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
								  		
										<div class="post_item_button">
								  		    <?php echo form_open('sell/edit'); ?>
												<input type="hidden" name="p_id" value ="<?php echo $active_product['id_product'];?>" /> 
												<input class="manage_lnk" type = "submit" value="Edit Item"> </input>
											<?php echo form_close(); ?> 
                                            <span class="border_white">|</span>
                                       
                                            <?php echo form_open('product/changeDelete'); ?>
                                                <input type="hidden" name="p_id" value ="<?php echo $active_product['id_product'];?>" /> 
                                                <input type="hidden" name="action" value ="delete" /> 
                                                <input class="manage_lnk" type = "submit" value="Delete Item"> </input>
                                            <?php echo form_close(); ?>
                                            
                                        </div>
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

				
			
				<div class="dashboard_table" id="deleted_items">
					<h2>Deleted Items</h2>
					<?php if(count($deleted_products) == 0):?>
						<p><strong>No deleted items.</strong></p>
					<?php else:?>

					<div class="pagination" id="pagination_deleted">
						<a href="#" class="first" data-action="first">&laquo;</a>
						<a href="#" class="previous" data-action="previous">&lsaquo;</a>
						<input type="text" readonly="readonly" data-max-page="<?php echo (count($deleted_products)===0)?1:(ceil(count($deleted_products)/$items_per_page));?>" />
						<a href="#" class="next" data-action="next">&rsaquo;</a>
						<a href="#" class="last" data-action="last">&raquo;</a>
					</div>
					
					<div class="paging">
					<?php $product_counter =0; ?>
					<?php foreach($deleted_products as $deleted_product):?>
							<div class="post_items_content">
								<div class="post_item_content_left">
								  <img src="<?php echo base_url().$deleted_product['path'].'categoryview/'.$deleted_product['file']; ?>" class="product_img">
								  <p>
                                    <?php $rounded_score = round($deleted_product['average_rating']); ?>
									<?php for($i = 0; $i < $rounded_score ;$i++): ?>
										<img src="<?=base_url()?>assets/images/star-on.png">
									<?php endfor; ?>
									<?php for($i = 0; $i < 5-$rounded_score;$i++): ?>
										<img src="<?=base_url()?>assets/images/star-off.png">
									<?php endfor; ?>
									<br />
									<img src="<?=base_url()?>assets/images/img_icon_review.png" class="reviews"><?php echo $deleted_product['review_count']; ?>
								  </p>
								</div>
								<div class="post_item_content_right">
									<div class="product_title_container">
										<p class="post_item_product_title">
											<a href="<?=base_url();?>item/<?php echo $deleted_product['id_product']."/".es_url_clean($deleted_product['name']);?>"><?php echo html_escape($deleted_product['name']);?></a>
										</p>
								  		<div class="post_item_button">
                                            <?php echo form_open('product/changeDelete'); ?>
                                                <input type="hidden" name="p_id" value ="<?php echo $deleted_product['id_product'];?>" /> 
                                                <input type="hidden" name="action" value ="restore" /> 
                                                <input class="manage_lnk" type = "submit" value="Restore Item"> </input>
                                            <?php echo form_close(); ?>
										</div>
									</div>
								  	<div class="price_container"> 
										<p>
											<span class="fm1 f24 orange"><?php echo number_format($deleted_product['price'],2,'.',',');?></span>
											<br />Price
										</p>
										<p>
											<span class="fm1 f24">xx</span><br />
											Available Stock
										</p>
									</div>
								  <p><strong>Description:</strong><br />
									<span class="item_prod_desc_content">
										<?php echo html_escape($deleted_product['brief']); ?>
									</span>
									<span class="show_prod_desc blue f11">Read more</span>
								  </p>
								  <div class="clear"></div>
								  <p class="post_item_category">
									<strong>Category:</strong><br />
									<?php foreach($deleted_product['parents'] as $parent):?>
										<?php echo $parent;?><?php echo (end($deleted_product['parents'])===$parent)?'':'<img src="assets/images/img_arrow_right.png">'; ?>
									<?php endforeach; ?>
								  </p>
								  <div class="show_more_options blue"><p>View Features and Specifications</p></div>
									<div class="attr_hide">
								
								    <?php $i = 0; 
									      foreach($deleted_product['data_attr'] as $key=>$data_attr): ?>								
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
							
							
							
						<?php $product_counter++;?>
						<?php if($product_counter === $items_per_page): $product_counter = 0; ?>
							</div><div class="paging">
						<?php endif;  ?>
					<?php endforeach; ?>
					</div>
					<?php endif;?>
				</div>
				
				<div class="dashboard_table" id="dashboard-feedbacks">
					<h2>Feedbacks</h2>
						<ul class="idTabs feedbacks_tabs">
							<li><a href="#op_buyer">Others posted (You are Buyer)</a></li>
							<li><a href="#op_seller">Others posted (You are Seller)</a></li>
							<li><a href="#yp_buyer">You posted (You are Buyer)</a></li>
							<li><a href="#yp_seller">You posted (You are Seller)</a></li>
						</ul>

						<div class="clear"></div>
						<div id="others_post">

							<div id="op_buyer">
								<h4>Feedbacks others posted where you are buyer</h4>
								<?php if(count($allfeedbacks['otherspost_buyer'])==0):?>
									<p><strong>You have not yet received any feedbacks for this category.</strong></p>
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
								<h4>Feedbacks others posted where you are seller</h4>
								<?php if(count($allfeedbacks['otherspost_seller'])==0):?>
									<p><strong>You have not yet received any feedbacks for this category.</strong></p>
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
							<h4>Feedbacks you posted where you are buyer</h4>
							<?php if(count($allfeedbacks['youpost_buyer'])==0):?>
								<p><strong>You have not yet received any feedbacks for this category.</strong></p>
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
							<h4>Feedbacks you posted where you are seller</h4>
							<?php if(count($allfeedbacks['youpost_seller'])==0):?>
								<p><strong>You have not yet received any feedbacks for this category.</strong></p>
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
            
            
            <!--
			<div class="profile_main_content" id="wishlist">
				<h2>Wish List</h2>
				<p class="wishlist_create">
					<a href="#create_wishlist" class="">
						<img src="<?=base_url()?>assets/images/icon_wishlist.png" alt="wishlist">Create Wish List
					</a>
				</p>
				<p class="wishlist_create">
					<a href="#" class="">
						<img src="<?=base_url()?>assets/images/icon_print.png" alt="print wishlist">Print All Wish List
					</a>
				</p>


				<div id="create_wishlist">
					<h3>Create Wishlist</h3>
					<span>Wishlist Name:</span> <input type="text"> <br />
					<span>Comment:</span> <textarea></textarea><br />
					<input type="submit" class="orange_btn3" value="Submit"> | <a href="" class="simplemodal-close">Cancel</a>
				</div>
                    
					<div class="wishlist_container">
							<div class="wishlist_title">
								<h3>My Wish List <span class="f11">3</span></h3>
								<a href="" class="orange_btn3"><strong>Buy Wishlist</strong></a>
							</div>
							<div class="wishlist_content">
								<div>
									<a href="">
										<img src="<?=base_url()?>assets/images/img_electronics_product2.jpg" alt="">
										<p>
											Ematic 7" Android 4.2 Capacitive Multi-Touch 4GB Wifi Tablet Kindle Books EGM003
										</p>
									</a>
								</div>
								<div>
									<p class="f11">
										Available Stock<br />
										<span class="fm1 f18 grn">12</span>
									</p>
								</div>
								<div>
									<p class="f11">
										Price<br />
										<span class="fm1 f18 orange">Php 1,999.00</span>
									</p>
								</div>
								<div>
									<a href="" class="orange2"><strong>Buy It Now</strong></a>
								</div>
							</div>
							<div class="wishlist_content">
								<div>
									<a href="">
										<img src="<?=base_url()?>assets/images/img_electronics_product2.jpg" alt="">
										<p>
											Ematic 7" Android 4.2 Capacitive Multi-Touch 4GB Wifi Tablet Kindle Books EGM003
										</p>
									</a>
								</div>
								<div>
									<p class="f11">
										Available Stock<br />
										<span class="fm1 f18 grn">12</span>
									</p>
								</div>
								<div>
									<p class="f11">
										Price<br />
										<span class="fm1 f18 orange">Php 1,999.00</span>
									</p>
								</div>
								<div>
									<a href="" class="orange2"><strong>Buy It Now</strong></a>
								</div>
							</div>
							<div class="wishlist_content">
								<div>
									<a href="">
										<img src="<?=base_url()?>assets/images/img_electronics_product2.jpg" alt="">
										<p>
											Ematic 7" Android 4.2 Capacitive Multi-Touch 4GB Wifi Tablet Kindle Books EGM003
										</p>
									</a>
								</div>
								<div>
									<p class="f11">
										Available Stock<br />
										<span class="fm1 f18 grn">12</span>
									</p>
								</div>
								<div>
									<p class="f11">
										Price<br />
										<span class="fm1 f18 orange">Php 1,999.00</span>
									</p>
								</div>
								<div>
									<a href="" class="orange2"><strong>Buy It Now</strong></a>
								</div>
							</div>
					</div>

					<div class="wishlist_container">
							<div class="wishlist_title">
								<h3>My Special Wish List for January <span class="f11">3</span></h3>
								<a href="" class="orange_btn3"><strong>Buy Wishlist</strong></a>
							</div>
							<div class="wishlist_content">
								<div>
									<a href="">
										<img src="<?=base_url()?>assets/images/img_electronics_product2.jpg" alt="">
										<p>
											Ematic 7" Android 4.2 Capacitive Multi-Touch 4GB Wifi Tablet Kindle Books EGM003
										</p>
									</a>
								</div>
								<div>
									<p class="f11">
										Available Stock<br />
										<span class="fm1 f18 grn">12</span>
									</p>
								</div>
								<div>
									<p class="f11">
										Price<br />
										<span class="fm1 f18 orange">Php 1,999.00</span>
									</p>
								</div>
								<div>
									<a href="" class="orange2"><strong>Buy It Now</strong></a>
								</div>
							</div>
							<div class="wishlist_content">
								<div>
									<a href="">
										<img src="<?=base_url()?>assets/images/img_electronics_product2.jpg" alt="">
										<p>
											Ematic 7" Android 4.2 Capacitive Multi-Touch 4GB Wifi Tablet Kindle Books EGM003
										</p>
									</a>
								</div>
								<div>
									<p class="f11">
										Available Stock<br />
										<span class="fm1 f18 grn">12</span>
									</p>
								</div>
								<div>
									<p class="f11">
										Price<br />
										<span class="fm1 f18 orange">Php 1,999.00</span>
									</p>
								</div>
								<div>
									<a href="" class="orange2"><strong>Buy It Now</strong></a>
								</div>
							</div>
							<div class="wishlist_content">
								<div>
									<a href="">
										<img src="<?=base_url()?>assets/images/img_electronics_product2.jpg" alt="">
										<p>
											Ematic 7" Android 4.2 Capacitive Multi-Touch 4GB Wifi Tablet Kindle Books EGM003
										</p>
									</a>
								</div>
								<div>
									<p class="f11">
										Available Stock<br />
										<span class="fm1 f18 grn">12</span>
									</p>
								</div>
								<div>
									<p class="f11">
										Price<br />
										<span class="fm1 f18 orange">Php 1,999.00</span>
									</p>
								</div>
								<div>
									<a href="" class="orange2"><strong>Buy It Now</strong></a>
								</div>
							</div>
					</div>
			</div>
            -->
			
			<div class="profile_main_content" id="personal_information">
				<form method="post" id="personal_profile_main" name="personal_profile_main">
					<h2>Personal Information</h2>
					<div class="clear"></div>
					<div class="profile_fields progress_update update_all" >
						<div>
							<label for="nickname">Nickname:</label>
							<input name="nickname" type="text" value="<?php echo $nickname?>">
						</div>
						<div>
							<label for="fullname">Real name:</label>
							<input name="fullname" type="text" value="<?php echo $fullname?>">
						</div>
						<div>
							<label for="gender">Gender:</label>							
							<input type="radio" name="gender" value="M" <?php echo ($gender=='M'?'checked="true"':'') ?>/> Male
							<input type="radio" name="gender" value="F" <?php echo ($gender=='F'?'checked="true"':'') ?>/> Female
						</div>
						<div>
							<label for="dateofbirth">Birthday:</label>
							<input type="text" name="dateofbirth" id="datepicker" value="<?php echo ($birthday == '0000-00-00' || $birthday == '0001-01-01'?'':$birthday)?>">
							<span class="red ci_form_validation_error"><?php echo form_error('dateofbirth'); ?></span>
						</div>	

						<div id="mobilediv">
							<label for="mobile">Mobile:</label>
							<input type="text" name="mobile" id="mobile" maxlength="11" value="<?php echo $contactno;?>" <?php echo (trim($contactno)==''?'':'disabled');?>>
							<span class="red ci_form_validation_error"><?php echo form_error('mobile'); ?></span>
							<input type="hidden" name="mobile_orig" value="<?php echo $contactno;?>">
							<input type="hidden" name="is_contactno_verify" value="<?php echo $is_contactno_verify;?>">
						
							<span class="verify toverify" style="<?php echo $is_contactno_verify == 0 && trim($contactno) !== ''?'':'display:none;'?>">
								<span class="verify_now">Verify</span>
								<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" class="verify_img" style="display:none;"/>
							</span>
							<span class="verify doneverify" style="<?php echo $is_contactno_verify == 0?'display:none;':''?>">
								<img src="<?=base_url()?>/assets/images/check_icon.png"><span><strong>Verified</strong></span>
							</span>
						
							<span class="personal_contact_cont" style="<?php echo trim($contactno)!==''?'':'display:none;' ?>">
								<span class="edit_personal_contact">
									<img src="<?=base_url()?>/assets/images/icon_edit.png"><span>Edit</span>
								</span>
								<span  class="cancel_personal_contact">
									<img src="<?=base_url()?>/assets/images/x_icon.png"><span>Cancel</span>
								</span>
							</span>
							
							<span class="red ci_form_validation_error"><?php echo form_error('mobile'); ?></span>
						</div>
						
						<div id="cont_mobilediv" class="errordiv" style="display:none;">
							<span></span>
						</div>
					
						<div id="verifcode_div" style="display:none;">
							<p>Verification code sent. Please enter the verification code below:</p>
							<input type="text" name="verifcode" id="verifcode">
							<p class="verifcode_error error red" style="display:none;">Incorrect verification code.</p>
						</div>
					
						<div id="emaildiv">
							<label for="email">Email:</label>
							<input type="text" name="email" id="email" value="<?php echo $email;?>" <?php echo (trim($email)==''?'':'disabled');?>>
							<span class="red ci_form_validation_error"><?php echo form_error('email'); ?></span>
							<input type="hidden" name="email_orig" value="<?php echo $email;?>">
							<input type="hidden" name="is_email_verify" value="<?php echo $is_email_verify;?>">
							
							<span class="verify toverify" style="<?php echo $is_email_verify == 0 && trim($email) !== ''?'':'display:none;'?>">
								<span class="verify_now">Verify</span>
								<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" class="verify_img" style="display:none"/>
							</span>	
							<span class="verify doneverify" style="<?php echo $is_email_verify == 0?'display:none;':''?>">
								<img src="<?=base_url()?>/assets/images/check_icon.png"><span><strong>Verified</strong></span>
							</span>
							
							<span class="personal_contact_cont" style="<?php echo trim($email)!==''?'':'display:none;' ?>">
								<span class="edit_personal_contact">
									<img src="<?=base_url()?>/assets/images/icon_edit.png"><span>Edit</span>
								</span>
								<span  class="cancel_personal_contact">
									<img src="<?=base_url()?>/assets/images/x_icon.png"><span>Cancel</span>
								</span>
							</span>
							
							<span class="red ci_form_validation_error"><?php echo form_error('email'); ?></span>
						</div>	
						
						<div id="cont_emaildiv" class="errordiv" style="display:none;">
							<span></span>
						</div>
						
						<div class="save_con">
							<input type="submit" name="personal_profile_main" value="Save" id="ppm_btn"/>
							<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" id="load_personalinfo" style="display:none"/>
						</div>
						<input type="hidden" class="progress_update_hidden" value="">
					</div>
				</form>
				<div>
				<form method="post" id="personal_profile_address" name="personal_profile_address" class="dropdownform">
					<div class="personal_info_title">
						<img src="<?=base_url()?>assets/images/icon_add.png" alt="add"> Address
					</div>
					<div class="edit_profile">
						<h2>+ Add an Address</h2>
					</div>
					<div class="address_information gen_information">
						<div class="add_info echoed_info">
								<?php echo $streetno?> <?php echo $streetname?> <?php echo $barangay?> 
								<?php echo $citytown?> <?php echo $country?> <?php echo $postalcode?>
						</div>
						<div class="edit_address edit_info_btn">
							<span><img src="<?=base_url()?>assets/images/icon_edit.png"> Edit</span>
						</div>
						<div class="delete_information" name="del_address">
							<span><img src="<?=base_url()?>assets/images/icon_delete.png">Delete</span>
						</div>
						<div class="clear"></div>
					</div>
					<div class="edit_fields profile_fields">
						<div class="inner_profile_fields">
							<div class="address_fields progress_update update_once">
								<div class="address_fields_layer1">
									<div>
										<input type="text" name="streetno" id="streetno" value="<?php echo $streetno?>">
										<p>Street No./Bldg. No.</p>
									</div>
									<div>
										<input type="text" name="streetname" id="streetname" value="<?php echo $streetname?>">
										<p>Street Name</p>
									</div>
									<div>
										<input type="text" name="barangay" id="barangay" value="<?php echo $barangay?>">
										<p>Barangay</p>
									</div>
								</div>
								<div class="address_fields_layer2">
									<div>
										<input type="text" name="citytown" id="citytown" value="<?php echo $citytown?>">
										<p>City/Town</p>
									</div>
									<div>
										<input type="text" name="country" id="country" value="<?php echo $country?>">
										<p>Country</p>
									</div>
									<div>
										<input type="text" name="postalcode" id="postalcode" value="<?php echo $postalcode?>">
										<p>Postal Code</p>
									</div>
								</div>
								<input type="hidden" name="addresstype" value="0"/>
								<div class="clear"></div>
								<input type="hidden" class="progress_update_hidden" value="">
							</div>
							<div>
								<label></label>
								<span class="red ci_form_validation_error"><?php echo form_error('streetno'); ?></span>
								<span class="red ci_form_validation_error"><?php echo form_error('streetname'); ?></span>
								<span class="red ci_form_validation_error"><?php echo form_error('citytown'); ?></span>
								<span class="red ci_form_validation_error"><?php echo form_error('country'); ?></span>
							</div>
                            <!--
							 <div class="view_map_btn">
								 <input type="button" id="view_map" value="View on map">                            
							</div>
                            -->
														
							<div id="map"  style="display: none">
								<a id="close" href="javascript:void(0)">Close</a>
								<div id="GoogleMapContainer" title="Google Map Container"></div>
							</div>
							<div id="map-canvas"></div>
							<div class="clear"></div>
							
							<div class="clear"></div>
							<input type="submit" name="personal_profile_address_btn" class="save_address" value="save">
							<span class="cancel" name="cancel_address">Cancel</span>
							<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" id="load_address" style="position: relative; left:440px; display:none"/>
						</div>
						<div class="clear"></div>
						<div class="error_container" style="padding-left:100px"></div>
					</div>
				</form>
					
				<div class="clear"></div>
				<div>
					<form method="post" id="personal_profile_school" name="personal_profile_school" class="dropdownform">
						<div class="personal_info_title">
							<img src="<?=base_url()?>assets/images/icon_school.png" alt="add"> School
						</div>
						<div class="edit_profile">
							<h2>+ Add a School</h2>
						</div>
						<div class="school_information gen_information">
							<div class="school_info echoed_info">
								<?php 	if(count($school)>0){
											foreach ($school as $i){
												echo '<p>'.$i['schoolname'].' '.$i['schoolyear'].' ';
												switch ($i['schoollevel']){
													case '1': echo $this->lang->line('schoollevel_option')[1]; break;
													case '2': echo $this->lang->line('schoollevel_option')[2]; break;
													case '3': echo $this->lang->line('schoollevel_option')[3]; break;
													case '4': echo $this->lang->line('schoollevel_option')[4]; break;
													case '5': echo $this->lang->line('schoollevel_option')[5]; break;
													default: echo $this->lang->line('schoollevel_option')[0];
												}
												echo '</p>';
											}
										}
								?> 
							</div>
							<div class="edit_school edit_info_btn">
								<span><img src="<?=base_url()?>assets/images/icon_edit.png"> Edit</span>
							</div>
							<div class="delete_information" name="del_school">
								<span><img src="<?=base_url()?>assets/images/icon_delete.png">Delete</span>
							</div>
							<div class="clear"></div>
						</div>
						<div class="edit_fields profile_fields">
							<div class="inner_profile_fields school_fields progress_update update_once">
								<div id="add_school">
									<div>
										<input type="text" name="schoolname1" value="<?php echo isset($school[0]['schoolname'])?$school[0]['schoolname']:"";?> ">
										<p>School Name</p>
									</div>
									<div>
										<input type="text" name="schoolyear1" class="year" maxlength="4" value="<?php echo isset($school[0]['schoolyear'])?$school[0]['schoolyear']:"";?>">
										<p>Year</p>
									</div>
									<div>
										<select name="schoollevel1" data-status="<?php echo isset($school[0]['schoollevel'])?$school[0]['schoollevel']:"";?>">
											<option value="0" <?php echo isset($school[0]['schoollevel'])?"":"selected";?> ><?php echo $this->lang->line('schoollevel_option')[0]?></option>
											<option value="1" <?php echo isset($school[0]['schoollevel']) && $school[0]['schoollevel'] == 1 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[1]?></option>
											<option value="2" <?php echo isset($school[0]['schoollevel']) && $school[0]['schoollevel'] == 2 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[2]?></option>
											<option value="3" <?php echo isset($school[0]['schoollevel']) && $school[0]['schoollevel'] == 3 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[3]?></option>
											<option value="4" <?php echo isset($school[0]['schoollevel']) && $school[0]['schoollevel'] == 4 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[4]?></option>
											<option value="5" <?php echo isset($school[0]['schoollevel']) && $school[0]['schoollevel'] == 5 ? "selected":"";?>><?php echo $this->lang->line('schoollevel_option')[5]?></option>
										</select>
										<p>Education Attainment</p>
										<input type="hidden" name="schoolcount1" value="1"/>
									</div>
								</div>
								<div id="container_school">
									<?php if(count($school)>1):?>
										<?php for($schcount = 1; $schcount < count($school); $schcount++ ):?>
											<div class="add_another_school dynamic_dd" style="display:block;">
												<div>
													<input type="text" name="schoolname<?php echo $school[$schcount]['schoolcount']?>" value="<?php echo $school[$schcount]['schoolname']?>">
													<p>School Name</p>
												</div>
												<div>
													<input type="text" name="schoolyear<?php echo $school[$schcount]['schoolcount']?>" value="<?php echo $school[$schcount]['schoolyear']?>">
													<p>Year</p>
												</div>
												<div>
													<select name="schoollevel<?php echo $school[$schcount]['schoolcount']?>" data-status="<?php echo $school[$schcount]['schoollevel']?>" >
														<option value="0" <?php echo $school[$schcount]['schoollevel'] == 0 ? "selected":""; ?> ><?php echo $this->lang->line('schoollevel_option')[0]?></option>
														<option value="1" <?php echo $school[$schcount]['schoollevel'] == 1 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[1]?></option>
														<option value="2" <?php echo $school[$schcount]['schoollevel'] == 2 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[2]?></option>
														<option value="3" <?php echo $school[$schcount]['schoollevel'] == 3 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[3]?></option>
														<option value="4" <?php echo $school[$schcount]['schoollevel'] == 4 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[4]?></option>
														<option value="5" <?php echo $school[$schcount]['schoollevel'] == 5 ? "selected":"";?>><?php echo $this->lang->line('schoollevel_option')[5]?></option>
													</select>
													<p>Education Attainment</p>
												</div>
												<input type="hidden" name="schoolcount<?php echo $school[$schcount]['schoolcount']?>" value="<?php echo $school[$schcount]['schoolcount']?>"/>
											</div>
										<?php endfor;?>
									<?php endif;?>
								</div>
								<div class="clear"></div>
								<p href="#" id="addRow_school"> + Add another school</p>		
								<div>
								    <span class="red ci_form_validation_error"><?php echo form_error('schoolname'); ?></span>
									<span class="red ci_form_validation_error"><?php echo form_error('schoolyear'); ?></span>
									<span class="red ci_form_validation_error"><?php echo form_error('schoollevel'); ?></span>
								</div>
								<input type="hidden" class="progress_update_hidden" value="">
							</div>
							<div class="clear"></div>							
							<input type="submit" class="save_school" name="personal_profile_school" value="Save"/>
							<span class="cancel" name="cancel_school">Cancel</span>
							<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" id="load_school" style="position: relative; left:440px; display:none"/>
						</div>
						<div class="clear"></div>
					</form>
				</div>
				<div class="clear"></div>
				
				<form method="post" id="personal_profile_work" name="personal_profile_work" class="dropdownform">
					<div>
						<div class="personal_info_title">
							<img src="<?=base_url()?>assets/images/icon_work.png" alt="add"> Work
						</div>
						<div class="edit_profile">
							<h2>+ Add Work</h2>
						</div>
						<div class="work_information gen_information">	
							<div class="work_info echoed_info">
								<?php 	if(count($work)>0){
											foreach ($work as $i){
												echo "<p>" . $i['companyname'] . " " . $i['designation'] . " " . $i['year'] . "</p>";
											}
										}
								?>
							</div>
							<div class="edit_work edit_info_btn">
								<span><img src="<?=base_url()?>assets/images/icon_edit.png"> Edit</span>
							</div>
							<div class="delete_information" name="del_work">
								<span><img src="<?=base_url()?>assets/images/icon_delete.png">Delete</span>
							</div>
							<div class="clear"></div>
						</div>
						<div class="edit_fields profile_fields">
							<div class="inner_profile_fields work_fields progress_update update_once">
								<div id="add_work">
										<div>
											<input type="text" name="companyname1" value="<?php echo isset($work[0]['companyname'])?$work[0]['companyname']:"";?>">
											<p>Company Name</p>
										</div>
										<div>
											<input type="text" name="designation1" value="<?php echo isset($work[0]['designation'])?$work[0]['designation']:"";?>">
											<p>Designation</p>
										</div>
										<div>
											<input type="text" name="year1" value="<?php echo isset($work[0]['year'])?$work[0]['year']:"";?>" maxlength="4">
											<p>Year</p>
										</div>
										<input type="hidden" name="workcount1" value="1"/>
								</div>
								<div id="container_work">
									<?php if(count($work) > 1):?>
										<?php for($workcount = 1; $workcount < count($work); $workcount++):?>
											<div class="add_another_work dynamic_dd" style="display: block;">
												<div>
													<input type="text" name="companyname<?php echo $work[$workcount]['count']?>" value="<?php echo $work[$workcount]['companyname']?>">
													<p>Company Name</p>
												</div>
												<div>
													<input type="text" name="designation<?php echo $work[$workcount]['count']?>" value="<?php echo $work[$workcount]['designation']?>">
													<p>Designation</p>
												</div>
												<div>
													<input type="text" name="year<?php echo $work[$workcount]['count']?>" value="<?php echo $work[$workcount]['year']?>">
													<p>Year</p>
												</div>
												<input type="hidden" name="workcount<?php echo $work[$workcount]['count']?>" value="<?php echo $work[$workcount]['count']?>"/>
											</div>
										<?php endfor;?>
									<?php endif;?>
								</div>
								<div class="clear"></div>
								<p href="#" id="addRow_work"> + Add another work</p>
								<div>
									<label></label>
									<span class="red ci_form_validation_error"><?php echo form_error('companyname1');?></span>
									<span class="red ci_form_validation_error"><?php echo form_error('designation1');?></span>
									<span class="red ci_form_validation_error"><?php echo form_error('year1');?></span>
								</div>
								<input type="hidden" class="progress_update_hidden" value="">
							</div>
							<div class="clear"></div>
							<input type="submit" name="personal_profile_work_btn" value="Save" class="save_work">
							<span class="cancel" name="cancel_work">Cancel</span>
							<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" id="load_work" style="position: relative; left:440px; display:none"/>
						</div>
					</div>
				</form>
				
			</div>
			</div>
		
				<div class="profile_main_content" id="delivery_address">
					<form method="post" id="c_deliver_address" name="c_deliver_address">	
							<h2>Delivery Address</h2>
							<div class="profile_fields">
								<div class="inner_profile_fields progress_update update_once">
									<div>
										<label for="consignee_name">Consignee name:</label>
										<input type="text" name="consignee" id="consignee" value="<?php echo $consignee?>">
										<span class="red ci_form_validation_error"><?php echo form_error('consignee');?></span>
									</div>
									<div>
										<label for="mobile_num">Mobile No:</label>
										<input type="text" name="c_mobile" id="c_mobile" value="<?php echo $c_mobile?>">
										<span class="red ci_form_validation_error"><?php echo form_error('c_mobile');?></span>
									</div>
									<div>
										<label for="telephone_num">Telephone No:</label>
										<input type="text" name="c_telephone" id="c_telephone" value="<?php echo $c_telephone?>">
										<span class="red ci_form_validation_error"><?php echo form_error('c_telephone');?></span>
									</div>
									<div class="address_label">
										<label>Address:</label>
									</div>	
									<div class="delivery_address_content">
										<div class="delivery_address_content_layer1">
											<div>
												<input type="text" name="c_streetno" id="c_streetno" value="<?php echo $c_streetno?>">
												<p>Street No/Bldg. No</p>
											</div>
											<div>
												<input type="text" name="c_streetname" value="<?php echo $c_streetname?>">
												<p>Street Name</p>
											</div>
											<div>
												<input type="text" name="c_barangay" value="<?php echo $c_barangay?>">
												<p>Barangay</p>
											</div>
										</div>
										<div class="delivery_address_content_layer2">
											<div>
												<input type="text" name="c_citytown" value="<?php echo $c_citytown?>">
												<p>City/Town</p>
											</div>
											<div>
												<input type="text" name="c_country" value="<?php echo $c_country?>">
												<p>Country</p>
											</div>
											<div>
												<input type="text" name="c_postalcode" id="c_postalcode" value="<?php echo $c_postalcode?>">
												<p>Postal Code</p>
											</div>
										</div>
									</div>
									<div>
										<label></label>
										<input type="checkbox" name="c_def_address" id="c_def_address"> <span>Set as Default Address</span>
										<img src="<?=base_url()?>/assets/images/icon_qmark.gif" title="Setting as default updates address in Personal Information">
									</div>
									<br>
									<div style="padding-left:100px">
										<label></label>
											<br><span class="red ci_form_validation_error"><?php echo form_error('c_streeno');?></span>
											<br><span class="red ci_form_validation_error"><?php echo form_error('c_streetname');?></span>
											<br><span class="red ci_form_validation_error"><?php echo form_error('c_citytown');?></span>
											<br><span class="red ci_form_validation_error"><?php echo form_error('c_country');?></span>
									</div>
									<div id="progressbar" class="profile_progress"></div>
									<input type="hidden" class="progress_update_hidden" value="">
								</div>
							</div>
							<div class="clear"></div>
							<div class="bottom_save">
								<input type="submit" name="c_deliver_address_btn" value="Save">
									<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" id="load_deliver_address" style="position: relative; top:12px; left:15px;  display:none"/>
							</div>	
					</form>
				</div>	

			<div class="profile_main_content" id="transactions">
				<h2>Transactions</h2>
				
				<div>
					<ul class="idTabs post_items">
						<li><a href="#bought">Bought <span><?php echo count($transaction['buy']);?></span></a> </li>
						<li><a href="#sold">Sold 	<span><?php echo count($transaction['sell']);?></span></a> </li>
					</ul>
				</div>
				
				<div id="bought" class="transactions-buy dashboard_table">
					<?php if(count($transaction['buy'])===0):?>
						<br/>
                        <div><strong>You have not bought any items yet.</strong></div>
					<?php else: ?>
						<?php $transac_counter = 0;?>
						<div class="paging">
						<?php foreach($transaction['buy'] as $tk=>$transact):?>
							<div class="transac-container">
								<div class="transac_title">
									<h4><span><strong>Transaction #:</strong>  <?php echo $tk;?></span>
									<span class="transac_title_date"><?php echo $transact['dateadded']?></span>
								</div>
								<div class="transac_prod_wrapper">
								
									<div class="transac-product-container">
										<?php foreach($transact['products'] as $product):?>
										<div class="transac_prod_first">
											<img src="<?=base_url()?><?php echo $product['product_image_path'];?>">
											<div>
												<p class="transac_prod_name">
													<a href="<?php echo base_url();?>item/<?php echo $product['product_id'];?>/<?php echo es_url_clean($product['name']);?>"><?php echo $product['name'];?></a><br />
													<span><?php echo $product['attr'];?></span>
												</p>
												<p>Bought from: <a href="<?php echo base_url();?>vendor/<?php echo $product['seller'];?>"><?php echo $product['seller'];?></a></p>
												<p>Quantity:<span class="fm1 f16"><?php echo $product['order_quantity']?></span></p>
												<p>Total:<span class="fm1 f16">Php<?php echo number_format($product['price'],2,'.',',');?></span></p>
											</div>
											<div class="clear"></div>
										</div>
										<?php endforeach;?>
										
									</div>
									<div class="feedback_wrapper">
									<?php foreach($transact['users'] as $uk=>$user):?>
										<div class="feedback_container">
											<?php if(trim($user['feedb_msg']) !== '' && $user['rating1'] != 0 && $user['rating2'] != 0 && $user['rating3'] != 0):?>												
												<p>For:<a href="<?php echo base_url();?>vendor/<?php echo $user['name'];?>"><?php echo $user['name'];?></a> | on:<?php echo $user['fbdateadded'];?></p>
												<p>"<?php echo $user['feedb_msg'];?>"</p>
												<p> Rating 1: 
													<?php for($x=0;$x<5;$x++):?>
														<?php if($x<$user['rating1']):?>
															<img src="<?=base_url()?>assets/images/star-on.png" alt="*" title="">
														<?php else:?>
															<img src="<?=base_url()?>assets/images/star-off.png" alt="*" title="">
														<?php endif;?>
													<?php endfor;?>
												</p>
												<p> Rating 2: 
													<?php for($x=0;$x<5;$x++):?>
														<?php if($x<$user['rating2']):?>
															<img src="<?=base_url()?>assets/images/star-on.png" alt="*" title="">
														<?php else:?>
															<img src="<?=base_url()?>assets/images/star-off.png" alt="*" title="">
														<?php endif;?>
													<?php endfor;?>
												</p>
												<p> Rating 3: 
													<?php for($x=0;$x<5;$x++):?>
														<?php if($x<$user['rating3']):?>
															<img src="<?=base_url()?>assets/images/star-on.png" alt="*" title="">
														<?php else:?>
															<img src="<?=base_url()?>assets/images/star-off.png" alt="*" title="">
														<?php endif;?>
													<?php endfor;?>
												</p>
											<?php else: ?>
												<p class="transac-feedback-btn"> + Feedback for <?php echo $user['name'];?></p>
												<div class="transac-feedback-container">
													<form class="transac-feedback-form">
														<input type="hidden" name="feedb_kind" value="0">
														<input type="hidden" name="order_id" value="<?php echo $tk;?>">
														<input type="hidden" name="for_memberid" value="<?php echo $uk;?>">
														<textarea rows="4" cols="50" name="feedback-field"></textarea><br>
														<span class="red ci_form_validation_error"><?php echo form_error('feedback-field'); ?></span>
														<span>Rating 1: </span><div class="feedb-star rating1"></div><br>
														<span class="red ci_form_validation_error"><?php echo form_error('rating1'); ?></span>
														<span>Rating 2: </span><div class="feedb-star rating2"></div><br>
														<span class="red ci_form_validation_error"><?php echo form_error('rating2'); ?></span>
														<span>Rating 3: </span><div class="feedb-star rating3"></div><br>
														<span class="red ci_form_validation_error"><?php echo form_error('rating3'); ?></span>
														<span class="raty-error error red"></span>
														<span class="feedback-submit">Submit</span><span class="feedback-cancel">Cancel</span>
													</form>
												</div>
											<?php endif;?>
										</div>
									<?php endforeach;?>
									</div>
								</div>
							</div>
							<div class="clear"></div>
							<?php $transac_counter++;?>
							<?php if($transac_counter === $items_per_page): $transac_counter = 0;?>
								</div><div class="paging">
							<?php endif;?>
						<?php endforeach;?>
						</div>
						<div class="pagination" id="pagination-bought">
							<a href="#" class="first" data-action="first">&laquo;</a>
							<a href="#" class="previous" data-action="previous">&lsaquo;</a>
							<input type="text" readonly="readonly" data-max-page="<?php echo (count($transaction['buy'])===0)?1:(ceil(count($transaction['buy'])/$items_per_page));?>" />
							<a href="#" class="next" data-action="next">&rsaquo;</a>
							<a href="#" class="last" data-action="last">&raquo;</a>
						</div>
					<?php endif; ?>
				</div>
				
				<div id="sold" class="transactions-sell dashboard_table">
					<?php if(count($transaction['sell'])===0):?>
                        <br/>
						<div><strong>You have not sold any items yet.</strong></div>
					<?php else: ?>
						<?php $transac_counter = 0;?>
						<div class="paging">
						<?php foreach($transaction['sell'] as $tk=>$transact):?>
							<div class="transac-container">
								<div class="transac_title">
									<h4>
										<span>
											<strong>Transaction #: </strong> <?php echo $tk;?><br />
											<strong>Sold to: </strong> <a href="<?php echo base_url();?>vendor/<?php echo $transact['buyer']?>"><?php echo $transact['buyer']?></a>
										</span>
										<span class="transac_title_date"><?php echo $transact['dateadded']?></span>
									</h4>
								</div>
								<div class="transac_prod_wrapper">
									
										<?php foreach($transact['products'] as $product):?>
											<div class="sold_prod_container transac-product-container">
												<img src="<?=base_url()?><?php echo $product['product_image_path'];?>">
												<div>
													<p class="transac_prod_name">
													<a href="<?php echo base_url();?>item/<?php echo $product['product_id'];?>/<?php echo es_url_clean($product['name']);?>"><?php echo $product['name'];?></a>
													<span><?php echo $product['attr'];?></span>
													</p>
													<p>Quantity:<span class="fm1 f18"><?php echo $product['order_quantity']?></span></p>
													<p>Total:<span class="fm1 f18">Php<?php echo number_format($product['price'],2,'.',',');?></span></p>
												</div>							
											</div>
										<?php endforeach;?>
									
								</div>

								<div class="feedback_wrapper">
								<?php foreach($transact['users'] as $uk=>$user):?>
									<div class="feedback_container">
										<?php if(trim($user['feedb_msg']) !== ''):?>												
											<p>For:<a href="<?php echo base_url();?>vendor/<?php echo $user['name'];?>"><?php echo $user['name'];?></a> | on:<?php echo $user['fbdateadded'];?></p>
											<p>"<?php echo $user['feedb_msg'];?>"</p>
											<p> Rating 1: 
												<?php for($x=0;$x<5;$x++):?>
													<?php if($x<$user['rating1']):?>
														<img src="<?=base_url()?>assets/images/star-on.png" alt="*" title="">
													<?php else:?>
														<img src="<?=base_url()?>assets/images/star-off.png" alt="*" title="">
													<?php endif;?>
												<?php endfor;?>
											</p>
											<p> Rating 2: 
												<?php for($x=0;$x<5;$x++):?>
													<?php if($x<$user['rating2']):?>
														<img src="<?=base_url()?>assets/images/star-on.png" alt="*" title="">
													<?php else:?>
														<img src="<?=base_url()?>assets/images/star-off.png" alt="*" title="">
													<?php endif;?>
												<?php endfor;?>
											</p>
											<p> Rating 3: 
												<?php for($x=0;$x<5;$x++):?>
													<?php if($x<$user['rating3']):?>
														<img src="<?=base_url()?>assets/images/star-on.png" alt="*" title="">
													<?php else:?>
														<img src="<?=base_url()?>assets/images/star-off.png" alt="*" title="">
													<?php endif;?>
												<?php endfor;?>
											</p>
										<?php else: ?>
											<p class="transac-feedback-btn"> + Feedback for <?php echo $user['name'];?></p>
											<div class="transac-feedback-container">
												<form class="transac-feedback-form">	
													<input type="hidden" name="feedb_kind" value="1">
													<input type="hidden" name="order_id" value="<?php echo $tk;?>">
													<input type="hidden" name="for_memberid" value="<?php echo $uk;?>">
													<textarea rows="4" cols="50" name="feedback-field"></textarea><br>
													<span class="red ci_form_validation_error"><?php echo form_error('feedback-field'); ?></span>
													<span>Rating 1: </span><div class="feedb-star rating1"></div><br>
													<span class="red ci_form_validation_error"><?php echo form_error('rating1'); ?></span>
													<span>Rating 2: </span><div class="feedb-star rating2"></div><br>
													<span class="red ci_form_validation_error"><?php echo form_error('rating2'); ?></span>
													<span>Rating 3: </span><div class="feedb-star rating3"></div><br>
													<span class="red ci_form_validation_error"><?php echo form_error('rating3'); ?></span>
													<span class="raty-error error red"></span>
													<span class="feedback-submit">Submit</span> <span class="feedback-cancel">Cancel</span>
												</form>
											</div>
										<?php endif;?>
									</div>
								<?php endforeach;?>
								</div>
							</div>
							<div class="clear"></div>
							<?php $transac_counter++;?>
							<?php if($transac_counter === $items_per_page): $transac_counter = 0;?>
								</div><div class="paging">
							<?php endif;?>
						<?php endforeach;?>
						</div>
						<div class="pagination" id="pagination-sold">
							<a href="#" class="first" data-action="first">&laquo;</a>
							<a href="#" class="previous" data-action="previous">&lsaquo;</a>
							<input type="text" readonly="readonly" data-max-page="<?php echo (count($transaction['sell'])===0)?1:(ceil(count($transaction['sell'])/$items_per_page));?>" />
							<a href="#" class="next" data-action="next">&rsaquo;</a>
							<a href="#" class="last" data-action="last">&raquo;</a>
						</div>
					<?php endif; ?>					
				</div>
				
			</div>
		    <!--
			<div class="profile_main_content" id="privacy_settings">
				<h2>Privacy Settings</h2>
					<div class="profile_fields">
							<p>Who can access my dynamic</p>
								<div>
									<input type="radio" name="access">Everyone
									<input type="radio" name="access">Friends
									<input type="radio" name="access">Only Me
								</div>
							<p>Allow the search to find me</p>
								<div>
									<input type="radio" name="search">Yes
									<input type="radio" name="search">No
								</div>
							<p>Who can focus on my</p>
								<div>
									<input type="radio" name="focus">Everyone<br />
									<input type="radio" name="focus">My only concern people<br />
									<input type="radio" name="focus">The answers to questions in order to focus on my
								</div>
					</div>
					<div class="bottom_save">
						<input type="submit" name="privacy_settings_btn" value="Save"/>
					</div>	
			</div>	
            -->
			
			
			<div class="profile_main_content" id="security_settings">
				<h2>Security Settings</h2>
					<div class="profile_fields">
                            <!--
							<p>Username:</p>
							<div>
								<p>dem0123 <a href="" class="change_password">change username</a></p>
							</div>
                            -->
                            
							<p>Login password</p>
							<div>
								<p>****************** <a href="<?=base_url()?>register/changepass" class="change_password">change password</a></p>
								<p>
									Having a strong password makes your account more secure. We recommend that you change your password regularly. For the best security, use a combination of numbers, letters and special characters.
								</p>
							</div>
                            <!--
							<p>Security question</p>
							<div>
								<p>Not set <a href="">Set up</a></p>
								<p>Strength: <span class="strength_green">Strong</span></p>
								<p>
									Forget your login password is one way. We recommend that you set up an easy to remember, and most questions and answers are not easy to get the others to more effectively protect your passwords secure.
								</p>
							</div>
                            -->
					</div>
			</div>			
		
	</section>
	<div class="clear"></div>
</div>



<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.simplemodal.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.numeric.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.validate.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.Jcrop.min.js'></script>
<script src="<?=base_url()?>assets/JavaScript/js/jquery.raty.min.js" type="text/javascript"></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.jqpagination.min.js'></script>
<script src="<?=base_url()?>assets/JavaScript/js/jquery.idTabs.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/JavaScript/js/jquery.knob.js" type="text/javascript"></script>
<!-- MEMBERPAGE JS-->
<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/memberpage.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=&sensor=false"></script>
