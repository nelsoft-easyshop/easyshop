<?php foreach($deleted_products as $deleted_product):?>
	<div class="post_items_content content-paging">
		<div class="post_item_content_left">
            <div class="post_item_img_table">
            
                <span class="post_item_img_con">
                   <img src="<?php echo getAssetsDomain()?><?php echo $deleted_product['path'].'categoryview/'.$deleted_product['file']; ?>" class="product_img">
                </span>
            </div>
			<p><small>Last modified : <?php echo date_format(date_create($deleted_product['lastmodifieddate']),'Y-m-d')?></small></p>
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
					<?php echo html_escape($deleted_product['name']);?>
				</p>
				<div class="post_item_button">
					<?php echo form_open('product/changeDelete'); ?>
						<input type="hidden" name="p_id" value ="<?php echo $deleted_product['id_product'];?>" /> 
						<input type="hidden" name="action" value ="restore" /> 
						<input class="manage_lnk restore_lnk span_bg" type = "submit" value="Restore Item"> </input>
					<?php echo form_close(); ?>
                    
                    <span class="border_white">|</span>
					<?php $attributes = array('class'=>'fulldelete'); ?>
					<?php echo form_open('product/changeDelete', $attributes); ?>
                        <input type="hidden" name="p_id" value ="<?php echo $deleted_product['id_product'];?>" /> 
						<input type="hidden" name="action" value ="fulldelete" /> 
                        <input class="delete_lnk span_bg" type = "submit" value="Remove"> </input>
					<?php echo form_close(); ?>
				</div>
                
                
             
                
                
			</div>
			<div class="price_container"> 
				<p>
					<span class="fm1 f24 orange">PHP <?php echo number_format($deleted_product['price'],2,'.',',');?></span>
					<br />Price<br/>
					<?PHP if($deleted_product['discount'] > 0): ?>   
					    <small class="original_price"> &#8369; <?php echo number_format($deleted_product['original_price'],2,'.',','); ?> </small> | <strong> <?php echo number_format( $deleted_product['percentage'],0,'.',',');?> % OFF  </strong>
					<?PHP endif;?>	
				</p>
				<p>
					<p><span class="fm1 f24 grn"><?php echo $deleted_product['sold'];?></span><br />Sold Items</p>
					<p><span class="fm1 f24"><?php echo $deleted_product['availability'];?></span>
					    <br />Available Stock<br/>
					    <br/>
		      
					    <?PHP IF($deleted_product['is_free_shipping']): ?>
					      <span class="span_bg img_free_shipping"></span>
					    <?PHP ENDIF; ?>
					</p>
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
				<?php $i = 0; foreach($deleted_product['data_attr'] as $key=>$data_attr): ?>								
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
