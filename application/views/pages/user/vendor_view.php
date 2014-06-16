<!--[if lt IE 9]>
        <script src="<?=base_url()?>assets/js/src/vendor/excanvas.js"></script>
<![endif]-->

<!--Pagination CSS-->
<link  type="text/css"  href='<?=base_url()?>assets/css/jqpagination.css' rel="stylesheet" media='screen'/>

<div id = "member_page_body">
	<div class="clear"></div>
	<section>
		<div class="wrapper profile_content">
			<div class="logo"> <a href="<?=base_url()?>home"><span class="span_bg"></span></a> </div>
			<div class="profile_top_nav">
				<div>
					<ul>
						<li><a href="<?=base_url()?>home">Home</a></li>
						<li>
							<span>Setup</span>
							<ul>
								<li><a href="javascript:void(0)" onclick="triggerTab('dashboard');">Dashboard</a></li>
							</ul>
						</li>
						<li><a href="<?=base_url()?>home/under_construction">News</a></li>
					</ul>
				</div>
				<div class="member_srch_wrapper">
                    <form method="get" action="<?=base_url()?>search/search.html">
                        <input type="text" name="q_str" id="member_sch" onblur="this.placeholder = 'Search'" onfocus="this.placeholder = ''" placeholder="Search">
                        <input type="submit" value="" class="span_bg">
                    </form>
				</div>   
                <div id="search_content" class="member_srch_container"></div>  
			</div>
		</div>
	</section>
	<div class="clear"></div>
	<section>
		<div class="wrapper profile_wrapper">
			<div class="profile_left_nav">
				<div>
					<div class="vendor-avatar">
						<span>
							<?php echo $image_profile?>	
						</span>
					</div>
                    <br/>
					<div class="vendor-profile">
						<p>Joined: <?php echo $vendordetails['datecreated'] != '' ? $vendordetails['datecreated'] : 'N/A';?></p>
						<p>Contact #: <?php echo $vendordetails['contactno'] != '' ? $vendordetails['contactno'] : 'N/A'?></p>
						<p>
						<span class="span_bg vendor_map"></span> 
							<?php echo $vendordetails['stateregionname'] != '' && $vendordetails['cityname'] != '' ? $vendordetails['stateregionname'] . ", " . $vendordetails['cityname'] : "Location not set."?>
						</p>
					</div>
					<div class="vendor-msg-modal">
					    <p><a id="modal-launcher" href="javascript:void(0)" class="orange_btn3 modal-launcher2">Send a message</a></p> 
					</div>
				</div>
			</div>

			<div class="profile_main_content" id="dashboard">
				<h2><?php echo $vendordetails['username'];?></h2>
				<div class="progress_bar_panel">
					<div>
						<h3>Total Posted Items</h3>
						<input class="items db_total_items" data-width="150" data-fgColor="#FF8400" data-max="1000" data-thickness=".1" data-angleOffset="180" data-readOnly=true data-displayPrevious=true data-value="<?php echo $active_count + $deleted_count;?>" value="<?php echo $active_count + $deleted_count;?>">
					</div>
					<div>
						<h3>Active Items</h3>
						<input class="items db_active_items" data-width="150" data-fgColor="#ff4400" data-max="1000" data-thickness=".1" data-angleOffset="180" data-readOnly=true data-displayPrevious=true data-value="<?php echo $active_count;?>" value="<?php echo $active_count;?>">
					</div>
					<div>
						<h3>Sold Items</h3>
						<input class="items db_sold_items" data-width="150" data-fgColor="#7ad014" data-max="1000" data-thickness=".1" data-angleOffset="180" data-readOnly=true data-displayPrevious=true data-value="<?php echo $sold_count;?>" value="<?php echo $sold_count;?>">
					</div>
				</div>
				<div class="posted_feedbacks_top">
					<h3 class="fm1 f18">Feedback Score:</h3>
					<span>(<?php echo $allfeedbacks['rcount'];?> Feedback/s received)</span>
					<p><?php echo $this->lang->line('rating')[0].':'; ?>
					<span>
					<?php if($allfeedbacks['rating1'] === 0 ):?>
						<?php echo $vendordetails['username'];?> has not received any ratings yet.
					<?php else:?>
						<?php for($i = 0; $i < $allfeedbacks['rating1']; $i++):?>
							<span class="span_bg star_on"></span>
						<?php endfor;?>
						<?php for($i = 0; $i < 5-$allfeedbacks['rating1']; $i++):?>
							<span class="span_bg star_off"></span>
						<?php endfor;?>
					<?php endif;?>
					</span>
					</p>
					<p><?php echo $this->lang->line('rating')[1].':'; ?>
					<span><?php if($allfeedbacks['rating2'] === 0 ):?>
						<?php echo $vendordetails['username'];?> has not received any ratings yet.
					<?php else:?>
						<?php for($i = 0; $i < $allfeedbacks['rating2']; $i++):?>
							<span class="span_bg star_on"></span>
						<?php endfor;?>
						<?php for($i = 0; $i < 5-$allfeedbacks['rating2']; $i++):?>
							<span class="span_bg star_off"></span>
						<?php endfor;?>
					<?php endif;?>
					</span>
					</p>
					<p><?php echo $this->lang->line('rating')[2].':'; ?>
					<span>
					<?php if($allfeedbacks['rating3'] === 0 ):?>
						<?php echo $vendordetails['username'];?> has not received any ratings yet.
					<?php else:?>
						<?php for($i = 0; $i < $allfeedbacks['rating3']; $i++):?>
							<span class="span_bg star_on"></span>
						<?php endfor;?>
						<?php for($i = 0; $i < 5-$allfeedbacks['rating3']; $i++):?>
							<span class="span_bg star_off"></span>
						<?php endfor;?>
					<?php endif;?>
					</span>
					</p>
				</div>
				<div class="clear"></div>
				<div>
					<ul class="idTabs post_items">
						<li><a href="#active_items">Active Items <span class="db_active_items"><?php echo $active_count;?></span></a></li>
						<li><a href="#dashboard-feedbacks">Feedbacks <span><?php echo $allfeedbacks['afbcount'];?></span></a></li>
					</ul>
				</div>
				<div class="clear"></div>
				
				<?php $items_per_page = 10; ?>

				<input id="mid" type="hidden" value="<?php echo $vendordetails['id_member'];?>">
				
				<div class="dashboard_table" id="active_items" data-key="active">
					<h2>Active Items</h2>
					<?php if($active_count === 0):?>
						<p><strong>No items on sale.</strong></p>
					<?php else:?>
					<div class="pagination" id="pagination_active">
						<a href="#" class="first" data-action="first">&laquo;</a>
						<a href="#" class="previous" data-action="previous">&lsaquo;</a>
						<input type="text" readonly="readonly" data-max-page="<?php echo ($active_count===0)?1:(ceil($active_count/$items_per_page));?>" data-origmaxpage = "<?php echo ($active_count===0)?1:(ceil($active_count/$items_per_page));?>"/>
						<a href="#" class="next" data-action="next">&rsaquo;</a>
						<a href="#" class="last" data-action="last">&raquo;</a>
					</div>
					
					<div class="post_item_srch_container">
						<input type="text" class="box sch_box" placeholder="Search" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search'" />
						<span class="span_bg sch_btn"></span>
						<label for="active_sort">Sort By</label>
						<select name="active_sort" class="post_active_sort sort_select">
							<option value="1">Date of Entry</option>
							<option value="2">Name</option>
							<option value="3">Price</option>
							<option value="4">Availability</option>
							<option value="5"># of Sold Items</option>
						</select>
						<span class="span_bg arrow_sort"></span>
						<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" class="loading_img" style="display:none;"/>
					</div>
					
					<div class="page_load" style="display:none;text-align:center;">
						<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" class="loading_img"/>
					</div>
                    
					<?php $pageNum = 0;?>
					<div class="paging" data-page="<?php echo $pageNum;?>">					
					<?php $product_counter = $mycounter = 0; 
					     foreach($active_products as $active_product): ?>
							<div class="post_items_content" data-order = "<?php echo $mycounter;?>">
								
								<div class="post_item_content_left">
									<div class="post_item_img_table">
										<span class="post_item_img_con">
											<img src="<?php echo base_url().$active_product['path'].'categoryview/'.$active_product['file']; ?>" class="product_img">
										</span>
									</div>
									<p>
                                        <?php $rounded_score = round($active_product['average_rating']); ?>
										<?php for($i = 0; $i < $rounded_score;$i++): ?>
											<span class="span_bg star_on"></span>
										<?php endfor; ?>
										<?php for($i = 0; $i < 5-$rounded_score;$i++): ?>
											<span class="span_bg star_off"></span>
										<?php endfor; ?>
										<br />
										<span class="span_bg reviews"></span><?php echo $active_product['review_count']; ?> Reviews
									</p>
								</div>
								<div class="post_item_content_right">
									<div class="product_title_container">
										<p class="post_item_product_title fm1"><a href="<?=base_url();?>item/<?php echo $active_product['slug']?>"><?php echo html_escape($active_product['name']);?></a></p>
								  	</div>
									<div class="price_container" data-prodprice="<?php echo $active_product['price'];?>">
										<p><span class="fm1 f24 orange">Php <?php echo number_format($active_product['price'],2,'.',',');?></span><br />Price</p>
										<p><span class="fm1 f24 grn"><?php echo $active_product['sold']; ?></span><br />Sold Items</p>
										<p><span class="fm1 f24"><?php echo $active_product['availability']; ?></span><br />Available Stock</p>
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
										<?php echo $parent;?><?php echo (end($active_product['parents'])===$parent)?'':'<span class="span_bg img_arrow_right"></span>'; ?>
									<?php endforeach; ?>
								  </p>
									
									<div class="show_more_options blue"><span class="span_bg"></span><p>View Features and Specifications</p></div>
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
							
							<?php $product_counter++;$mycounter++; ?>
							<?php if($product_counter === $items_per_page): 
								$product_counter = 0;
								$pageNum++;
							?>
								</div><div class="paging" data-page="<?php echo $pageNum;?>">
							<?php endif;  ?>
							
					<?php endforeach; ?>
					</div> 	
					<?php endif;?>
				</div>

				<div class="dashboard_table" id="dashboard-feedbacks">
					<h2>Feedbacks</h2>
						<ul class="idTabs feedbacks_tabs">
							<li><a href="#op_buyer">Feedbacks as a Buyer</a></li>
							<li><a href="#op_seller">Feedbacks as a Seller</a></li>
							<li><a href="#yp_buyer">Feedbacks for others - Buyer</a></li>
							<li><a href="#yp_seller">Feedbacks for others - Seller</a></li>
						</ul>

						<div class="clear"></div>
						<div id="others_post">

							<div id="op_buyer">
								<h4>Feedbacks others posted for <?php echo $vendordetails['username'];?> as a buyer</h4>
								<?php if(count($allfeedbacks['otherspost_buyer'])==0):?>
									<p><strong><?php echo $vendordetails['username'];?> have not yet received any feedbacks for this category.</strong></p>
								<?php else:?>
									<?php $afb_counter = 0;?>
									<div class="paging posted_feedbacks">
									<?php foreach($allfeedbacks['otherspost_buyer'] as $k=>$tempafb):?>
											<div>
												<!--<h3>Feedback from Transaction#: <?php echo $k;?></h3>-->
												<?php foreach($tempafb as $key=>$afb):?>
												<p>From: <a href="<?php echo base_url();?>vendor/<?php echo $afb['member_name'];?>"><?php echo $afb['member_name'];?></a> | on: <?php echo $afb['dateadded'];?></p>
												<p>"<?php echo html_escape($afb['feedb_msg'])?>"</p>
												<p><?php echo $this->lang->line('rating')[0].':'; ?>
													<?php for($i = 0; $i < $afb['rating1']; $i++):?>
														<span class="span_bg star_on"></span>
													<?php endfor;?>
													<?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
														<span class="span_bg star_off"></span>
													<?php endfor;?>
												</p>
												<p><?php echo $this->lang->line('rating')[1].':'; ?>
													<?php for($i = 0; $i < $afb['rating2']; $i++):?>
														<span class="span_bg star_on"></span>
													<?php endfor;?>
													<?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
														<span class="span_bg star_off"></span>
													<?php endfor;?>
												</p>
												<p><?php echo $this->lang->line('rating')[2].':'; ?>
													<?php for($i = 0; $i < $afb['rating3']; $i++):?>
														<span class="span_bg star_on"></span>
													<?php endfor;?>
													<?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
														<span class="span_bg star_off"></span>
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
								<h4>Feedbacks others posted for <?php echo $vendordetails['username'];?> as a seller</h4>
								<?php if(count($allfeedbacks['otherspost_seller'])==0):?>
									<p><strong><?php echo $vendordetails['username'];?> have not yet received any feedbacks for this category.</strong></p>
								<?php else:?>
									<?php $afb_counter = 0;?>
									<div class="paging posted_feedbacks">
									<?php foreach($allfeedbacks['otherspost_seller'] as $k=>$tempafb):?>
										
											<div>
												<!--<h3>Feedback from Transaction#: <?php echo $k;?></h3>-->
												<?php foreach($tempafb as $afb):?>
												<p>From: <a href="<?php echo base_url();?>vendor/<?php echo $afb['member_name'];?>"><?php echo $afb['member_name'];?></a> | on: <?php echo $afb['dateadded'];?></p>
												<p>"<?php echo html_escape($afb['feedb_msg'])?>"</p>
												<p><?php echo $this->lang->line('rating')[0].':'; ?> 
													<?php for($i = 0; $i < $afb['rating1']; $i++):?>
														<span class="span_bg star_on"></span>
													<?php endfor;?>
													<?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
														<span class="span_bg star_off"></span>
													<?php endfor;?>
												</p>
												<p><?php echo $this->lang->line('rating')[1].':'; ?>
													<?php for($i = 0; $i < $afb['rating2']; $i++):?>
														<span class="span_bg star_on"></span>
													<?php endfor;?>
													<?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
														<span class="span_bg star_off"></span>
													<?php endfor;?>
												</p>
												<p><?php echo $this->lang->line('rating')[2].':'; ?> 
													<?php for($i = 0; $i < $afb['rating3']; $i++):?>
														<span class="span_bg star_on"></span>
													<?php endfor;?>
													<?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
														<span class="span_bg star_off"></span>
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
							<h4>Feedbacks <?php echo $vendordetails['username'];?> posted as a buyer</h4>
							<?php if(count($allfeedbacks['youpost_buyer'])==0):?>
								<p><strong><?php echo $vendordetails['username'];?> have not yet posted any feedbacks for this category.</strong></p>
							<?php else:?>
							<?php $afb_counter = 0;?>
								<div class="paging posted_feedbacks">
								<?php foreach($allfeedbacks['youpost_buyer'] as $k=>$tempafb):?>
									<div>
										<!--<h3>Feedback from Transaction#: <?php echo $k;?></h3>-->
										<?php foreach($tempafb as $afb):?>
										<p>For: <a href="<?php echo base_url();?>vendor/<?php echo $afb['for_membername'];?>"><?php echo $afb['for_membername'];?></a> | on: <?php echo $afb['dateadded'];?></p>
										<p>"<?php echo html_escape($afb['feedb_msg'])?>"</p>
										<p><?php echo $this->lang->line('rating')[0].':'; ?>
											<?php for($i = 0; $i < $afb['rating1']; $i++):?>
												<span class="span_bg star_on"></span>
											<?php endfor;?>
											<?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
												<span class="span_bg star_off"></span>
											<?php endfor;?>
										</p>
										<p><?php echo $this->lang->line('rating')[1].':'; ?>
											<?php for($i = 0; $i < $afb['rating2']; $i++):?>
												<span class="span_bg star_on"></span>
											<?php endfor;?>
											<?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
												<span class="span_bg star_off"></span>
											<?php endfor;?>
										</p>
										<p><?php echo $this->lang->line('rating')[2].':'; ?>
											<?php for($i = 0; $i < $afb['rating3']; $i++):?>
												<span class="span_bg star_on"></span>
											<?php endfor;?>
											<?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
												<span class="span_bg star_off"></span>
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
							<h4>Feedbacks <?php echo $vendordetails['username'];?> posted as a seller</h4>
							<?php if(count($allfeedbacks['youpost_seller'])==0):?>
								<p><strong><?php echo $vendordetails['username'];?> have not yet posted any feedbacks for this category.</strong></p>
							<?php else:?>
							<?php $afb_counter = 0;?>
								<div class="paging posted_feedbacks">
								<?php foreach($allfeedbacks['youpost_seller'] as $k=>$tempafb):?>
										<div>
											<!--<h3>Feedback from Transaction#: <?php echo $k;?></h3>-->
											<?php foreach($tempafb as $afb):?>
											<p>For: <a href="<?php echo base_url();?>vendor/<?php echo $afb['for_membername'];?>"><?php echo $afb['for_membername'];?></a> | on: <?php echo $afb['dateadded'];?></p>
											<p>"<?php echo html_escape($afb['feedb_msg'])?>"</p>
											<p><?php echo $this->lang->line('rating')[0].':'; ?>
												<?php for($i = 0; $i < $afb['rating1']; $i++):?>
													<span class="span_bg star_on"></span>
												<?php endfor;?>
												<?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
													<span class="span_bg star_off"></span>
												<?php endfor;?>
											</p>
											<p><?php echo $this->lang->line('rating')[1].':'; ?>
												<?php for($i = 0; $i < $afb['rating2']; $i++):?>
													<span class="span_bg star_on"></span>
												<?php endfor;?>
												<?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
													<span class="span_bg star_off"></span>
												<?php endfor;?>
											</p>
											<p><?php echo $this->lang->line('rating')[2].':'; ?>
												<?php for($i = 0; $i < $afb['rating3']; $i++):?>
													<span class="span_bg star_on"></span>
												<?php endfor;?>
												<?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
													<span class="span_bg star_off"></span>
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
		</div>
		<div id="modal-background">
		</div>
		<div id="modal-container">
			<div id="modal-div-header">
				<button id="modal-close">X</button>        
			</div>
			<div id="modal-inside-container">
				<div>
					<label>To : </label>
					<input type="text" value="<?=$vendordetails['username'];?>" disabled id="msg_name" name="msg_name" >
				</div>
				<div>
					<label>Message : </label>
					<textarea cols="40" rows="5" name="msg-message" id="msg-message" placeholder="Say something.."></textarea>		
				</div>	   
			</div>
			<button id="modal_send_btn">Send</button>
		</div>
		
<script src="<?=base_url()?>assets/js/src/vendor/jquery.raty.min.js" type="text/javascript"></script>
<script type='text/javascript' src='<?=base_url()?>assets/js/src/vendor/jquery.jqpagination.min.js'></script>
<script src="<?=base_url()?>assets/js/src/vendor/jquery.idTabs.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/vendor/jquery.knob.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/src/vendorpage.js?ver=<?=ES_FILE_VERSION?>"></script>

<script>
    $(function(){
	if (<?=$my_id?> == <?php echo $vendordetails['id_member']; ?> || <?=$my_id?> == 0  ) {
	    $(".vendor-msg-modal").remove();
	    $("#modal-background").remove();
	    $("#modal-container").remove();
	}
		
	$("#modal-background, #modal-close").click(function() {
	    $("#modal-container, #modal-background").toggleClass("active");
	    $("#modal-container").hide();
	    $("#msg-message").val("");
	});
	$("#modal-launcher").click(function() {
	    $("#modal-container, #modal-background").toggleClass("active");
	    $("#modal-container").show();
	});
	
	$("#modal_send_btn").on("click",function(){
	    var recipient = <?php echo $vendordetails['id_member']; ?>;
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
		    alert("Message Sent");
                }
            });
	});
	
    });
    
    
    
    $(document).ready(function() {
        var currentRequest = null;
     
        $('#member_sch').on('input propertychange', function() {
            var searchQuery = $.trim( $(this).val());
            searchQuery = searchQuery.replace(/ +(?= )/g,'');
            var fulltext = searchQuery; 
                if(searchQuery != ""){
                    currentRequest = $.ajax({
                        type: "GET",
                         url: '<?php echo base_url();?>search/suggest', 
                        cache: false,
                        data: "q="+fulltext, 
                        beforeSend: function(jqxhr, settings) { 
                            $("#search_content").empty();
                            if(currentRequest != null) {
                                currentRequest.abort();
                            }
                        },
                        success: function(html) {
                            $("#search_content").empty();


                            if(html==0){
                                $("#search_content").append('No result found');
                            }
                            else{
                                $("#search_content").append(html);
                                $("#search_content").show();
                            }
                        }
                    });
                }else{
                    $("#search_content").hide();
                }
        });
    });

    $(document).ready(function() { 

        $('#member_sch').focus(function() {
        $('#search_content').show();
        $(document).bind('focusin.member_srch_container click.member_srch_container',function(e) {
            if ($(e.target).closest('#search_content, #member_sch').length) return;
            $('#search_content').hide();
            });
         });

        $('#search_content').hide();
    });
    
</script>


























