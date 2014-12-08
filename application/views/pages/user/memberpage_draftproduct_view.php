<?php foreach($draft_products as $draft_product): ?>
	<div class="post_items_content content-paging">
		<div class="post_item_content_left">
			<div class="post_item_img_table">

			
			    <span class="post_item_img_con">
				    <img src="<?php echo getAssetsDomain()?><?php echo $draft_product['path'].'categoryview/'.$draft_product['file']; ?>" class="product_img">
			    </span>
			</div>
			<p><small>Last modified : <?php echo date_format(date_create($draft_product['lastmodifieddate']),'Y-m-d')?></small></p>
			<p class="star_rating_reviews">
			<?php $rounded_score = round($draft_product['average_rating']); ?>
			<?php for($i = 0; $i < $rounded_score;$i++): ?>
				<span class="span_bg star_on"></span>
			<?php endfor; ?>
			<?php for($i = 0; $i < 5-$rounded_score;$i++): ?>
				<span class="span_bg star_off"></span>
			<?php endfor; ?>
				<br />
				<span class="span_bg reviews"></span><?php echo $draft_product['review_count']; ?> Reviews
			</p>
		</div>
		<div class="post_item_content_right">
			<div class="product_title_container">
				<p class="post_item_product_title fm1"><span><?php echo html_escape($draft_product['name']);?></span></p>
				<div class="post_item_button">
					<?php echo form_open('sell/edit/step2'); ?>
					<input type="hidden" name="p_id" value ="<?php echo $draft_product['id_product'];?>" /> 
					<input class="manage_lnk edit_lnk span_bg" type = "submit" value="Edit Item"> </input>
					<?php echo form_close(); ?> 
					<span class="border_white">|</span>
					
					<?php echo form_open('product/changeDelete'); ?>
					<input type="hidden" name="p_id" value ="<?php echo $draft_product['id_product'];?>" /> 
					<input type="hidden" name="action" value ="delete" /> 
					<input class="delete_lnk span_bg" type = "submit" value="Delete Item"> </input>
					<?php echo form_close(); ?>
				</div>
			</div>
			<div class="price_container" data-prodprice="<?php echo $draft_product['price'];?>">
				<p><span class="fm1 f24 orange">PHP <?php echo number_format($draft_product['price'],2,'.',',');?></span><br />Price<br/>
				<?PHP if($draft_product['discount'] > 0): ?>   
				    <small class="original_price"> &#8369; <?php echo number_format($draft_product['original_price'],2,'.',','); ?> </small> | <strong> <?php echo number_format( $draft_product['percentage'],0,'.',',');?> % OFF  </strong>
				<?PHP endif;?>	
				</p>
				
				
				<p><span class="fm1 f24 grn"><?php echo $draft_product['sold'];?></span><br />Sold Items</p>
				<p><span class="fm1 f24"><?php echo $draft_product['availability'];?></span>
				  <br />Available Stock<br/>
				  <?PHP IF($draft_product['is_free_shipping']): ?>
				    <span class="span_bg img_free_shipping"></span>
				 <?PHP ENDIF; ?>
				</p>
			</div>
			<p><strong>Description:</strong><br />
				<span class="item_prod_desc_content">
					<?php echo html_escape($draft_product['brief']); ?>
				</span>
				<span class="show_prod_desc blue f11">Read more</span>
			</p>
			<div class="clear"></div>
			<p class="post_item_category">
				<strong>Category:</strong><br />
				<?php foreach($draft_product['parents'] as $parent):?>
					<?php echo $parent;?><?php echo (end($draft_product['parents'])===$parent)?'':'<span class="span_bg img_arrow_right"></span>'; ?>
				<?php endforeach; ?>
			</p>
		
			<div class="show_more_options blue"><span class="span_bg"></span><p>View Features and Specifications</p></div>
			<div class="attr_hide">
				<?php $i = 0; 
				foreach($draft_product['data_attr'] as $key=>$data_attr): ?>								
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
<?php endforeach;?>
