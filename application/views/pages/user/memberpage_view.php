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
<link type="text/css" href="<?=base_url()?>assets/css/jquery-ui.css" rel="stylesheet" />

<!--Memberpage Modal custom CSS-->
<link type="text/css" href="<?=base_url()?>assets/css/memberpage.css" rel="stylesheet" media='screen'/>
<!--Pagination CSS-->
<link  type="text/css"  href='<?=base_url()?>assets/css/jqpagination.css' rel="stylesheet" media='screen'/>
<!--Jcrop CSS-->
<link type="text/css" href="<?=base_url()?>assets/css/jquery.Jcrop.min.css" rel="stylesheet" media='screen'/>
<!--Selectize CSS-->
<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.selectize-default.css" type="text/css" media="screen"/>
<!--Chosen CSS-->
<link rel="stylesheet" href="<?=base_url()?>assets/css/chosen.min.css" type="text/css" media="screen"/>

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
								<!-- <li><a href="javascript:void(0)" onclick="triggerTab('wishlist');">Wishlist</a></li> -->
								<li><a href="javascript:void(0)" onclick="triggerTab('personal_information');">Personal Information</a></li>
								<li><a href="javascript:void(0)" onclick="triggerTab('payment');">Payment</a></li>
								<li><a href="javascript:void(0)" onclick="triggerTab('delivery_address');">Delivery Address</a></li>
								<li><a href="javascript:void(0)" onclick="triggerTab('transactions');">Transactions</a></li>
								<!-- <li><a href="javascript:void(0)" onclick="triggerTab('privacy_settings');">Privacy Settings</a></li> -->
								<li><a href="javascript:void(0)" onclick="triggerTab('security_settings');">Security Setting</a></li>
							</ul>
						</li>
						<li><a href="<?=base_url()?>home/under_construction">News</a></li>
					</ul>
				</div>
				<div class="member_srch_wrapper">
					<span class="member_srch_img_con"></span>
					<input type="text" id="member_sch" onblur="this.placeholder = 'Search'" onfocus="this.placeholder = ''" placeholder="Search">

					<script type="text/javascript">
					
					var jsonCity = <?php echo $json_city;?>;
					
					$(document).ready(function() {
                        var currentRequest = null;
						$( "#member_sch" ).keyup(function() {

							var searchQuery = $(this).val(); 
							if(searchQuery != ""){
								currentRequest = $.ajax({
									type: "GET",
									url: '<?php echo base_url();?>product/sch_onpress', 
									onLoading:jQuery(".member_srch_img_con").html('<img src="<?= base_url() ?>assets/images/orange_loader_small.gif" />').show(),
									cache: false,
									data: "q="+searchQuery, 
									beforeSend: function(jqxhr, settings) { 
										$("#search_content").empty();
                                        if(currentRequest != null) {
                                            currentRequest.abort();
                                        }
									},
									success: function(html) {
										$("#search_content").html(html).show();
										jQuery(".member_srch_img_con").hide();
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

					<input type="submit" class="span_bg" value="">
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
					<div class="avatar">
						<div class="avatar_edit" style="width:45px; position: relative; float:right"><span class="span_bg" name='avatar_edit_icon'></span>Edit</div>
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
					<li><a href="#payment">Payment</a></li>
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
					<h3>Total Sold Items</h3>
					<input class="items" data-width="150" data-fgColor="#7ad014" data-max="1000" data-thickness=".1" data-angleOffset="180" data-readOnly=true data-displayPrevious=true value="<?php echo $sold_count;?>">
				</div>
			</div>

			<div class="posted_feedbacks_top">
				<h3 class="fm1 f18">Feedback Score:</h3>
				<span>(<?php echo $allfeedbacks['rcount'];?> Feedback/s received)</span>
				<p><?php echo $this->lang->line('rating')[0].':'; ?> 
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
<p><?php echo $this->lang->line('rating')[1].':'; ?> 
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
<p><?php echo $this->lang->line('rating')[2].':'; ?> 
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

	<div class="post_item_srch_container">
		<input type="text" class="box sch_box" placeholder="Search" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search'" />
		<span class="span_bg sch_btn"></span>
		<label for="active_sort">Sort By</label>
		<select name="active_sort" class="post_active_sort sort_select">
			<option value="date">Date of Entry</option>
			<option value="name">Name</option>
			<option value="price">Price</option>
		</select>
		<span class="span_bg arrow_sort"></span>
		<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" class="loading_img" style="display:none;"/>
	</div>
	
	<div class="paging">					
		<?php $product_counter = $mycounter = 0; 
		foreach($active_products as $active_product): ?>
		<div class="post_items_content" data-order = "<?php echo $mycounter;?>">
			
			<div class="post_item_content_left">
				<span class="post_item_img_con">
					<img src="<?php echo base_url().$active_product['path'].'categoryview/'.$active_product['file']; ?>" class="product_img">
				</span>
				<p class="star_rating_reviews">
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
			<p class="post_item_product_title fm1"><a href="<?=base_url();?>item/<?php echo $active_product['id_product']."/".es_url_clean($active_product['name']);?>"><?php echo html_escape($active_product['name']);?></a></p>
			
			<div class="post_item_button">
				<?php echo form_open('sell/edit/step2'); ?>
				<input type="hidden" name="p_id" value ="<?php echo $active_product['id_product'];?>" /> 
				<input class="manage_lnk" type = "submit" value="Edit Item"> </input>
				<?php echo form_close(); ?> 
				<span class="border_white">|</span>
				
				<?php echo form_open('product/changeDelete'); ?>
				<input type="hidden" name="p_id" value ="<?php echo $active_product['id_product'];?>" /> 
				<input type="hidden" name="action" value ="delete" /> 
				<input class="delete_lnk" type = "submit" value="Delete Item"> </input>
				<?php echo form_close(); ?>
				
			</div>
		</div>
		<div class="price_container" data-prodprice="<?php echo $active_product['price'];?>">
			<p><span class="fm1 f24 orange">Php <?php echo number_format($active_product['price'],2,'.',',');?></span><br />Price</p>
			<p><span class="fm1 f24 grn"><?php echo $active_product['sold'];?></span><br />Sold Items</p>
			<p><span class="fm1 f24"><?php echo $active_product['availability'];?></span><br />Available Stock</p>
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
	
	<div class="post_item_srch_container">
		<input type="text" class="box sch_box" placeholder="Search" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search'" />
		<span class="span_bg sch_btn"></span>
		<label for="active_sort">Sort By</label>
		<select name="active_sort" class="post_active_sort sort_select">
			<option value="date">Date of Entry</option>
			<option value="name">Name</option>
			<option value="price">Price</option>
		</select>
		<span class="span_bg arrow_sort"></span>
		<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" class="loading_img" style="display:none;"/>
	</div>
	
	<div class="paging">
		<?php $product_counter =0; $mycounter = 0;?>
		<?php foreach($deleted_products as $deleted_product):?>
		<div class="post_items_content" data-order = "<?php echo $mycounter;?>">
			<div class="post_item_content_left">
				<img src="<?php echo base_url().$deleted_product['path'].'categoryview/'.$deleted_product['file']; ?>" class="product_img">
				<p>
					<?php $rounded_score = round($deleted_product['average_rating']); ?>
					<?php for($i = 0; $i < $rounded_score ;$i++): ?>
					<span class="span_bg star_on"></span>
				<?php endfor; ?>
				<?php for($i = 0; $i < 5-$rounded_score;$i++): ?>
				<span class="span_bg star_off"></span>
			<?php endfor; ?>
			<br />
			<span class="span_bg reviews"></span><?php echo $deleted_product['review_count']; ?> Reviews
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
                <p><span class="fm1 f24 grn"><?php echo $deleted_product['sold'];?></span><br />Sold Items</p>
                <p><span class="fm1 f24"><?php echo $deleted_product['availability'];?></span><br />Available Stock</p>
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
			<?php echo $parent;?><?php echo (end($deleted_product['parents'])===$parent)?'':'<span class="span_bg img_arrow_right"></span>'; ?>
		<?php endforeach; ?>
	</p>
	<div class="show_more_options blue"><span class="span_bg"></span><p>View Features and Specifications</p></div>
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



<?php $product_counter++;$mycounter++;?>
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
		<li><a href="#op_buyer">Feedbacks as a Buyer</a></li>
		<li><a href="#op_seller">Feedbacks as a Seller</a></li>
		<li><a href="#yp_buyer">Feedbacks for others - Buyer</a></li>
		<li><a href="#yp_seller">Feedbacks for others - Seller</a></li>
	</ul>

	<div class="clear"></div>
	<div id="others_post">

		<div id="op_buyer">
			<h4>Feedbacks others left for you as a buyer</h4>
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
	<h4>Feedbacks others left for you as a seller</h4>
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
	<h4>Feedbacks you posted as a buyer</h4>
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
	<h4>Feedbacks you posted as a seller</h4>
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


		
		<div class="profile_main_content" id="personal_information">
			<!--<form method="post" id="personal_profile_main" name="personal_profile_main">-->
			<?php 
			$attr=array('id'=>'personal_profile_main', 'name'=>'personal_profile_main');
			echo form_open('',$attr);
			?>
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
					<input type="text" name="mobile" id="mobile" maxlength="10" value="<?php echo $contactno;?>" <?php echo (trim($contactno)==''?'':'disabled');?> placeholder="e.g. 9051234567">
					<span class="red ci_form_validation_error"><?php echo form_error('mobile'); ?></span>
					<input type="hidden" name="mobile_orig" value="<?php echo $contactno;?>">
					<input type="hidden" name="is_contactno_verify" value="<?php echo $is_contactno_verify;?>">
					
					<span class="verify toverify" style="<?php echo $is_contactno_verify == 0 && trim($contactno) !== ''?'':'display:none;'?>">
						<span class="verify_now">Verify</span>
						<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" class="verify_img" style="display:none;"/>
					</span>
					<span class="verify doneverify" style="<?php echo $is_contactno_verify == 0?'display:none;':''?>">
						<span class="span_bg chk_img"></span><span><strong>Verified</strong></span>
					</span>
					
					<span class="personal_contact_cont" style="<?php echo trim($contactno)!==''?'':'display:none;' ?>">
						<span class="edit_personal_contact">
							<span class="span_bg edit_btn"></span><span>Edit</span>
						</span>
						<span  class="cancel_personal_contact">
							<span class="span_bg cancel_btn"></span><span>Cancel</span>
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
						<span class="span_bg chk_img"></span><span><strong>Verified</strong></span>
					</span>
					
					<span class="personal_contact_cont" style="<?php echo trim($email)!==''?'':'display:none;' ?>">
						<span class="edit_personal_contact">
							<span class="span_bg edit_btn"></span><span>Edit</span>
						</span>
						<span  class="cancel_personal_contact">
							<span class="span_bg cancel_btn"></span><span>Cancel</span>
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
			<?php echo form_close();?>
			
			<div>
				<!--<form method="post" id="personal_profile_address" name="personal_profile_address" class="dropdownform">-->
				<?php
				$attr = array('id'=>'personal_profile_address','name'=>'personal_profile_address', 'class'=>'dropdownform');
				echo form_open('',$attr);
				?>
				<div class="personal_info_title">
					<span class="personal_info_icon address_img span_bg"></span> Address
				</div>
				<div class="edit_profile">
					<h2>+ Add an Address</h2>
				</div>
				<div class="address_information gen_information">
					<div class="add_info echoed_info">
						<?php if(trim($stateregion) != '' && trim($city) != ''):?>
							<?php echo $stateregion . ', ' . $city . '<br>' . $address?>
						<?php endif;?>
					</div>
					<div class="edit_address edit_info_btn">
						<span><span class="span_bg edit_btn"></span> Edit</span>
					</div>
					<div class="delete_information" name="del_address">
						<span><span class="span_bg delete_btn"></span> Delete</span>
					</div>
					<div class="clear"></div>
				</div>
				
				<!--<input id="json_city" value='<?php echo $json_city;?>' type="hidden">-->
				
				<div class="edit_fields profile_fields">
					<div class="inner_profile_fields">
						<div class="address_fields progress_update update_once">
							<div class="address_fields_layer1">
								<div>
									<select name="stateregion" id="personal_stateregion" class="address_dropdown stateregionselect" data-status="<?php echo $stateregionID?>">
										<option value="0">--- Select State/Region ---</option>
										<?php foreach($stateregion_lookup as $srkey=>$stateregion):?>
											<option class="echo" value="<?php echo $srkey?>" <?php echo $stateregionID == $srkey ? "selected":"" ?>><?php echo $stateregion?></option>
										<?php endforeach;?>
									</select>
									<p>State/Region</p>
									<input type="hidden" name="stateregion_orig" value="<?php echo $stateregionID?>">
								</div>
								<div>
									<select name="city" id="personal_city" class="address_dropdown cityselect" data-status="<?php echo $cityID?>">
										<option value="0">--- Select City ---</option>
										<option class="optionclone" value="" style="display:none;" disabled></option>
										
										<?php if($cityID != '' && $stateregionID != ''):?>
											<?php foreach($city_lookup[$stateregionID] as $lockey=>$city):?>
												<option class="echo" value="<?php echo $lockey?>" <?php echo $cityID == $lockey ? "selected":"" ?> ><?php echo $city?></option>
											<?php endforeach;?>
										<?php endif;?>
										
									</select>
									<p>City</p>
									<input type="hidden" name="city_orig" value="<?php echo $cityID?>">
								</div>
								<div>
									<select class="disabled_country" disabled>
										<option selected=""><?php echo $country_name?></option>
									</select>
									<input type="hidden" name="country" value="<?php echo $country_id?>">
									<p>Country</p>
								</div>
							</div>
							<div class="address_fields_layer2">
								<div>
									<input type="text" name="address" value="<?php echo $address?>">
									<p>Street Address</p>
									<input type="hidden" name="address_orig" value="<?php echo $address?>"> 
								</div>
							</div>
							<input type="hidden" name="addresstype" value="0"/>
							<div class="clear"></div>
							<input type="hidden" class="progress_update_hidden" value="">
						</div>
						
						<div class="view_map_btn">
							<input type="button" id="view_map" value="Mark on map">
							<?php if($lat == 0 && $lng == 0):?>
								<span class="maploc_stat">Location not set</span>
							<?php else:?>
								<span class="maploc_stat">Location set</span>
							<?php endif;?>
							<input type="hidden" name="map_lat" id="map_lat" value="<?php echo $lat;?>">
							<input type="hidden" name="map_lng" id="map_lng" value="<?php echo $lng;?>">
							<input type="hidden" name="temp_lat" id="temp_lat" value="<?php echo $lat;?>">
							<input type="hidden" name="temp_lng" id="temp_lng" value="<?php echo $lng;?>">
						</div>
						
						<div id="map"  style="display: none">
							<span id="refresh_map">Search address</span>
							<span id="current_loc">Current location</span>
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
				<?php echo form_close();?>
				
				<div class="clear"></div>
				<div>
					<!--<form method="post" id="personal_profile_school" name="personal_profile_school" class="dropdownform">-->
					<?php
					$attr = array('id'=>'personal_profile_school', 'name'=>'personal_profile_school', 'class'=>'dropdownform');
					echo form_open('', $attr);
					?>
					<div class="personal_info_title">
						<span class="personal_info_icon school_img span_bg"></span> School
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
							<span><span class="span_bg edit_btn"></span> Edit</span>
						</div>
						<div class="delete_information" name="del_school">
							<span><span class="span_bg delete_btn"></span> Delete</span>
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
			<?php echo form_close();?>
		</div>
		
		<div class="clear"></div>
		
		<!--<form method="post" id="personal_profile_work" name="personal_profile_work" class="dropdownform">-->
		<?php 
		$attr = array('id'=>'personal_profile_work', 'name'=>'personal_profile_work', 'class'=>'dropdownform');
		echo form_open('',$attr);
		?>
		<div>
			<div class="personal_info_title">
				<span class="personal_info_icon work_img span_bg"></span> Work
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
					<span><span class="span_bg edit_btn"></span> Edit</span>
				</div>
				<div class="delete_information" name="del_work">
					<span><span class="span_bg delete_btn"></span> Delete</span>
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
<?php echo form_close();?>

</div>
</div>


<div class="profile_main_content" id="payment">
	<h2>Update your payment details</h2>
	<p>
		Any changes to banking information after the 15th day of the month will not be in effect until the following month's payment.
	</p>
	<hr style="display: block; height: 1px; border: 0; border-top: 1px solid #ccc; margin: 1em 0; padding: 0;" />
	<div align="right">
		<input type="button" id="abi_btn" name="abi_btn" value="Add Bank" />
	</div>
	<div id="abi" style="display:none">
		<?php
			$attr = array('id'=>'billing_info', 'name'=>'billing_info');
			echo form_open('',$attr);
		?>	
		<div class="profile_fields">
			<div class="inner_profile_fields progress_update update_once">
				<div>
					<input type="hidden" name="bi_payment_type" id="bi_payment_type" value="Bank" />
				</div>				
				<div class="control-group">
						<label for="bi_bank">Bank:</label>
						<select id="bi_bank" name="bi_bank" style="width:50%" placeholder="Select Bank">
							<option value="">Select a bank...</option>
							<?php
							foreach($bank as $rows => $banks){
								echo "<option value='" . $banks['id'] ."'>". $banks['name'] ."</option>";
							}							
							?>							
						</select><br />
				</div>
				<div>
					<label for="bi_acct_name">Account Name:</label>
					<input type="text" name="bi_acct_name" id="bi_acct_name" maxlength="60" value="<?php #echo $bill_info[0][$bank_account_name]?>">
					<span class="red ci_form_validation_error"><?php echo form_error('bi_acct_name');?></span>
				</div>
				<div>
					<label for="bi_acct_no">Account Number:</label>
					<input type="text" name="bi_acct_no" id="bi_acct_no" value="<?php #echo $bank_account_number?>" maxlength="18">
					<span class="red ci_form_validation_error"><?php echo form_error('bi_acct_no');?></span>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		<div class="bottom_save" style="text-align:left">
			<input type="button" name="billing_info_btn" id="billing_info_btn" value="Save">
			<img src="<?=base_url()?>/assets/images/orange_loader_small.gif" id="load_deliver_address" style="position: relative; top:12px; left:15px;  display:none"/>
		</div>
		<?php echo form_close();?>			
	</div>
	<hr style="display: block; height: 1px; border: 0; border-top: 1px solid #ccc; margin: 1em 0; padding: 0;" />
	<div class="billing_info_grid">
			
		<?php foreach($bill as $rows => $billing_info){ ?>
			<?php 
				$bi_checked = "";
				$bi_checked_val = "";
					
				if($billing_info['is_default'] == "1"){ 
					$bi_checked = " checked='checked' ";
					$bi_checked_val = "checked";
				} 
			?>
			<?php
			if($rows >= 0){
				$attr = array('id'=>'ubi_bictr'.$rows, 'name'=>'ubi_bictr'.$rows);
				echo form_open('',$attr);
			?>
				<div style="width:inherit;">
					<div id="bi-right" style="float:right; width:200px;">
							<div class="post_item_button">
								<input type="button" name="bictr<?php echo $rows; ?>" id="bictr<?php echo $rows; ?>" value="Edit">
								<input type="button" name="sv_bictr<?php echo $rows; ?>" id="sv_bictr<?php echo $rows; ?>" value="Save" style="display:none">				 	
								<span class="border_white">|</span>
								<input type="button" name="cn_bictr<?php echo $rows; ?>" id="cn_bictr<?php echo $rows; ?>" value="Cancel" style="display:none">
								<input type="button" name="del_bictr<?php echo $rows; ?>" id="del_bictr<?php echo $rows; ?>"value="Delete">
								<input type="hidden" name="bi_id_bictr<?php echo $rows; ?>" id="bi_id_bictr<?php echo $rows; ?>" value ="<?php echo $billing_info['id_billing_info'];?>" />
							</div>
							<div id="bi_check_bictr<?php echo $rows; ?>"  style="display:none;">
								<img id="bi_check_img" style="position: relative; vertical-align: middle;" src="<?php echo base_url(); ?>/assets/images/check_icon.png">
								Saved!
							</div>	
					</div>
					<div id="bi-left" style="float:left; width:inherit;">
							<div class="profile_fields" id="bi_div_bictr<?php echo $rows; ?>">
								<div class="inner_profile_fields progress_update update_once">
									<div>
										<label for="bi_chk_bictr<?php echo $rows; ?>">Default Bank: </label>
										<input type="checkbox" name="bi_chk_bictr<?php echo $rows; ?>" id="bi_chk_bictr<?php echo $rows; ?>" value="1" disabled="disabled" <?php echo $bi_checked; ?>>
										<input type="hidden" name="hbi_chk_bictr<?php echo $rows; ?>" id="hbi_chk_bictr<?php echo $rows; ?>" value ="<?php echo $bi_checked_val;?>"/>
									</div>			
									<div>						
										<label for="bi_ban_bictr<?php echo $rows; ?>">Account Name: </label>
										<input type="text" name="bi_ban_bictr<?php echo $rows; ?>" id="bi_ban_bictr<?php echo $rows; ?>" value ="<?php echo $billing_info['bank_account_name'];?>" disabled="disabled" maxlength="60"/>
										<input type="hidden" name="hbi_ban_bictr<?php echo $rows; ?>" id="hbi_ban_bictr<?php echo $rows; ?>" value ="<?php echo $billing_info['bank_account_name'];?>"/>
										<span class="red ci_form_validation_error"><?php #echo form_error('bi_acct_name');?></span>
									</div>
									<div>
										<label for="bi_bar_bictr<?php echo $rows; ?>">Account Number: </label>
										<input type="text" name="bi_bar_bictr<?php echo $rows; ?>" id="bi_bar_bictr<?php echo $rows; ?>" value ="<?php echo $billing_info['bank_account_number'];?>" disabled="disabled" maxlength="18"/>
										<input type="hidden" name="hbi_bar_bictr<?php echo $rows; ?>" id="hbi_bar_bictr<?php echo $rows; ?>" value ="<?php echo $billing_info['bank_account_number'];?>"/>
										<span class="red ci_form_validation_error"><?php #echo form_error('bi_acct_name');?></span>
									</div>
									<div>
										<label for="bi_bn_bictr<?php echo $rows; ?>">Bank: </label>
										<select name="bi_bns_bictr<?php echo $rows; ?>" id="bi_bns_bictr<?php echo $rows; ?>" style="display:none"></select>
										<input type="text" name="bi_bn_bictr<?php echo $rows; ?>" id="bi_bn_bictr<?php echo $rows; ?>" value ="<?php echo $billing_info['bank_name'];?>" disabled="disabled"/>
										<input type="hidden" name="hbi_bn_bictr<?php echo $rows; ?>" id="hbi_bn_bictr<?php echo $rows; ?>" value ="<?php echo $billing_info['bank_name'];?>"/>
										<span class="red ci_form_validation_error"><?php #echo form_error('bi_acct_name');?></span>
									</div>
								</div>
							</div>	
					</div>
					<div style="clear:both"></div>
				</div>
				<div>
					<hr style="display: block; height: 1px; border: 0; border-top: 1px solid #ccc; margin: 1em 0; padding: 0;" />
				</div>
			<?php 
			echo form_close(); 
			}
			?>	
		<?php }?>	
	</div>		
</div>


<div class="profile_main_content" id="delivery_address">
	<!--<form method="post" id="c_deliver_address" name="c_deliver_address">	-->

	<?php
	$attr = array('id'=>'c_deliver_address', 'name'=>'c_deliver_address');
	echo form_open('',$attr);
	?>
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
				<input maxlength="10" placeholder="eg. 9051235678" type="text" name="c_mobile" id="c_mobile" value="<?php echo $c_mobile?>">
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
						<select name="c_stateregion" class="address_dropdown stateregionselect" data-status="<?php echo $c_stateregionID?>">
							<option value="0">--- Select State/Region ---</option>
							<?php foreach($stateregion_lookup as $srkey=>$stateregion):?>
								<option class="echo" value="<?php echo $srkey?>" <?php echo $c_stateregionID == $srkey ? "selected":"" ?>><?php echo $stateregion?></option>
							<?php endforeach;?>
						</select>
						<p>State/Region</p>
					</div>
					<div>
						<select name="c_city" class="address_dropdown cityselect" data-status="<?php echo $c_cityID?>">
							<option value="0">--- Select City ---</option>
							<option class="optionclone" value="" style="display:none;" disabled></option>
							<?php foreach($city_lookup as $parentkey=>$arr):?>
								<?php foreach($arr as $lockey=>$city):?>
									<option class="echo" value="<?php echo $lockey?>" data-parent="<?php echo $parentkey?>" <?php echo $c_cityID == $lockey ? "selected":"" ?> ><?php echo $city?></option>
								<?php endforeach;?>
							<?php endforeach;?>
						</select>
						<p>City</p>
					</div>
					<div>
						<select class="disabled_country" disabled>
							<option selected=""><?php echo $country_name?></option>
						</select>
						<input type="hidden" name="c_country" value="<?php echo $country_id?>">
						<p>Country</p>
					</div>
				</div>
				<div class="delivery_address_content_layer2">
					<div>
						<input type="text" name="c_address" value="<?php echo $c_address?>">
						<p>Street address</p>
					</div>
				</div>
			</div>
			<div>
				<label></label>
				<input type="checkbox" name="c_def_address" id="c_def_address"> <span>Set as Default Address</span>
				<a class="tooltips" href="javascript:void(0)"><p class="span_bg"></p><!-- <img src="<?=base_url()?>/assets/images/icon_qmark.png"> --><span>Setting as default updates address in Personal Information</span></a>
		</div>
			<br>
			<div style="padding-left:100px">
				<label></label>
				<br><span class="red ci_form_validation_error"><?php echo form_error('c_stateregion');?></span>
				<br><span class="red ci_form_validation_error"><?php echo form_error('c_city');?></span>
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
	<?php echo form_close();?>

</div>	

<div class="profile_main_content" id="transactions">
	<h2>Transactions</h2>
	
	<div>
		<ul class="idTabs transact_tabs">
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
						<?php foreach($transact['products'] as $opk=>$product):?>
						<div class="transac_prod_first">
							<img src="<?=base_url()?><?php echo $product['product_image_path'];?>">
							<div>
								<p class="transac_prod_name">
									<a href="<?php echo base_url();?>item/<?php echo $product['product_id'];?>/<?php echo es_url_clean($product['name']);?>"><?php echo $product['name'];?></a><br />
									<?php if( count($product['attr'] !== 0) ):?>
										<?php foreach($product['attr'] as $temp):?>
											<span><?php echo $temp['field'];?>:</span><span><?php echo $temp['value'];?></span>
										<?php endforeach;?>
									<?php endif;?>
								</p>
								<p>Bought from: <a href="<?php echo base_url();?>vendor/<?php echo $product['seller'];?>"><?php echo $product['seller'];?></a></p>
								<p>Quantity:<span class="fm1 f16"><?php echo $product['order_quantity']?></span></p>
								<p>Total:<span class="fm1 f16">Php<?php echo number_format($product['price'],2,'.',',');?></span></p>
							</div>
							<div>
								<?php if($product['status'] == 0):?>
									<?php
										$attr = array('class'=>'transac_response');
										echo form_open('',$attr);
									?>
										<span class = "transac_response_btn">Forward payment to seller</span>
										<input type="hidden" name="buyer_response" value="<?php echo $opk;?>">
										<input type="hidden" name="transaction_num" value="<?php echo $tk;?>">
									<?php echo form_close();?>
								<?php elseif($product['status'] == 1):?>
									<span>Paid</span>
								<?php elseif($product['status'] == 2):?>
									<span>Payment returned by seller</span>
								<?php elseif($product['stuats'] == 3):?>
									<span>Cash on delivery</span>
								<?php endif;?>
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
						<p> <?php echo $this->lang->line('rating')[1].':'; ?> : 
							<?php for($x=0;$x<5;$x++):?>
							<?php if($x<$user['rating1']):?>
							<span class="span_bg star_on"></span>
						<?php else:?>
						<span class="span_bg star_off"></span>
					<?php endif;?>
				<?php endfor;?>
			</p>
			<p> <?php echo $this->lang->line('rating')[2].':'; ?> 
				<?php for($x=0;$x<5;$x++):?>
				<?php if($x<$user['rating2']):?>
				<span class="span_bg star_on"></span>
			<?php else:?>
			<span class="span_bg star_off"></span>
		<?php endif;?>
	<?php endfor;?>
</p>
<p> <?php echo $this->lang->line('rating')[2].':'; ?> 
	<?php for($x=0;$x<5;$x++):?>
	<?php if($x<$user['rating3']):?>
	<span class="span_bg star_on"></span>
<?php else:?>
	<span class="span_bg star_off"></span>
<?php endif;?>
<?php endfor;?>
</p>
<?php else: ?>
	<p class="transac-feedback-btn"> + Feedback for <?php echo $user['name'];?></p>
	<div class="transac-feedback-container">
		<!--<form class="transac-feedback-form">-->
		<?php
		$attr = array('class'=>'transac-feedback-form');
		echo form_open('',$attr);
		?>
		<input type="hidden" name="feedb_kind" value="0">
		<input type="hidden" name="order_id" value="<?php echo $tk;?>">
		<input type="hidden" name="for_memberid" value="<?php echo $uk;?>">
		<textarea rows="4" cols="50" name="feedback-field"></textarea><br>
		<span class="red ci_form_validation_error"><?php echo form_error('feedback-field'); ?></span>
		<span><?php echo $this->lang->line('rating')[0].':'; ?>  </span><div class="feedb-star rating1"></div><br>
		<span class="red ci_form_validation_error"><?php echo form_error('rating1'); ?></span>
		<span><?php echo $this->lang->line('rating')[1].':'; ?> </span><div class="feedb-star rating2"></div><br>
		<span class="red ci_form_validation_error"><?php echo form_error('rating2'); ?></span>
		<span><?php echo $this->lang->line('rating')[2].':'; ?>  </span><div class="feedb-star rating3"></div><br>
		<span class="red ci_form_validation_error"><?php echo form_error('rating3'); ?></span>
		<span class="raty-error error red"></span>
		<span class="feedback-submit">Submit</span><span class="feedback-cancel">Cancel</span>
		<?php echo form_close();?>
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
				
				<?php foreach($transact['products'] as $opk=>$product):?>
				<div class="sold_prod_container transac-product-container">
					<img src="<?=base_url()?><?php echo $product['product_image_path'];?>">
					<div>
						<p class="transac_prod_name">
							<a href="<?php echo base_url();?>item/<?php echo $product['product_id'];?>/<?php echo es_url_clean($product['name']);?>"><?php echo $product['name'];?></a>
							<?php if( count($product['attr'] !== 0) ):?>
								<?php foreach($product['attr'] as $temp):?>
									<span><?php echo $temp['field'];?>:</span><span><?php echo $temp['value'];?></span>
								<?php endforeach;?>
							<?php endif;?>
						</p>
						<p>Quantity:<span class="fm1 f18"><?php echo $product['order_quantity']?></span></p>
						<p>Total:<span class="fm1 f18">Php<?php echo number_format($product['price'],2,'.',',');?></span></p>
						<div>
						<?php if($product['status'] == 0):?>
							<?php
								$attr = array('class'=>'transac_response');
								echo form_open('',$attr);
							?>
							<span class="transac_response_btn">Return payment to buyer</span>
							<input type="hidden" name="seller_response" value="<?php echo $opk;?>">
							<input type="hidden" name="transaction_num" value="<?php echo $tk;?>">
							<input type="hidden" name="data" value='<?php echo $product['jsondata'];?>'>
							<input type="hidden" name="userdata" value="<?php echo $transact['buyer'] . '||' . $transact['buyer_email'];?>">
							<?php echo form_close();?>
						<?php elseif($product['status'] == 1):?>
							<span>Paid</span>
						<?php elseif($product['status'] == 2):?>
							<span>Payment returned to buyer</span>
						<?php elseif($product['stuats'] == 3):?>
							<span>Cash on delivery</span>
						<?php endif;?>
						</div>
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
				<p> <?php echo $this->lang->line('rating')[0].':'; ?> 
					<?php for($x=0;$x<5;$x++):?>
					<?php if($x<$user['rating1']):?>
					<span class="span_bg star_on"></span>
				<?php else:?>
				<span class="span_bg star_off"></span>
			<?php endif;?>
		<?php endfor;?>
	</p>
	<p> <?php echo $this->lang->line('rating')[1].':'; ?> 
		<?php for($x=0;$x<5;$x++):?>
		<?php if($x<$user['rating2']):?>
		<span class="span_bg star_on"></span>
	<?php else:?>
	<span class="span_bg star_off"></span>
<?php endif;?>
<?php endfor;?>
</p>
<p> <?php echo $this->lang->line('rating')[2].':'; ?> 
	<?php for($x=0;$x<5;$x++):?>
	<?php if($x<$user['rating3']):?>
	<span class="span_bg star_on"></span>
<?php else:?>
	<span class="span_bg star_off"></span>
<?php endif;?>
<?php endfor;?>
</p>
<?php else: ?>
	<p class="transac-feedback-btn"> + Feedback for <?php echo $user['name'];?></p>
	<div class="transac-feedback-container">
		<!--<form class="transac-feedback-form">-->
		<?php
		$attr = array('class'=>'transac-feedback-form');
		echo form_open('',$attr);
		?>
		<input type="hidden" name="feedb_kind" value="1">
		<input type="hidden" name="order_id" value="<?php echo $tk;?>">
		<input type="hidden" name="for_memberid" value="<?php echo $uk;?>">
		<textarea rows="4" cols="50" name="feedback-field"></textarea><br>
		<span class="red ci_form_validation_error"><?php echo form_error('feedback-field'); ?></span>
		<span><?php echo $this->lang->line('rating')[0].':'; ?>  </span><div class="feedb-star rating1"></div><br>
		<span class="red ci_form_validation_error"><?php echo form_error('rating1'); ?></span>
		<span><?php echo $this->lang->line('rating')[1].':'; ?>  </span><div class="feedb-star rating2"></div><br>
		<span class="red ci_form_validation_error"><?php echo form_error('rating2'); ?></span>
		<span><?php echo $this->lang->line('rating')[2].':'; ?>  </span><div class="feedb-star rating3"></div><br>
		<span class="red ci_form_validation_error"><?php echo form_error('rating3'); ?></span>
		<span class="raty-error error red"></span>
		<span class="feedback-submit">Submit</span> <span class="feedback-cancel">Cancel</span>
		<?php echo form_close();?>
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
		<script src="<?=base_url()?>assets/JavaScript/js/jquery.selectize.js" type="text/javascript"></script>
		<script src="<?=base_url()?>assets/JavaScript/js/chosen.jquery.min.js" type="text/javascript"></script>
		<!-- MEMBERPAGE JS-->
		<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/memberpage.js"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=&sensor=false"></script>