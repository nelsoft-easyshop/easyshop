<?php foreach($transaction['complete']['buy'] as $tk=>$transact):?>
			<div class="transac-container content-paging" data-pm="<?php echo $transact['payment_method']?>" data-invoice="<?php echo $transact['invoice_no']?>">
				<div class="transac_title">
					<div class="transac_title_table">
						<div class="transac_title_col1">
							<span><strong>Transaction No.:</strong></span>
							<span><?php echo $transact['invoice_no'];?></span>
						</div>
						<div class="transac_title_col2">
							<span><strong>Date:</strong></span>
							<span class="transac_title_date"><?php echo $transact['dateadded']?></span>
						</div>
						<div class="transac_title_col3">

						</div>
					</div>
				</div>
				<div class="transac_prod_wrapper">
					<div class="transac-product-container">
						<?php foreach($transact['products'] as $opk=>$product):?>
						<div class="transac_prod_first">
							<div class="img_transac_prod_con">
								<span class="img_transac_prod">
									<img src="<?=base_url()?><?php echo $product['product_image_path'];?>">
								</span>
							</div>
							<div class="info_transac_prod_con">
								<div class="title_top_transac_table">
									<span class="title_top_transac_col1">
										<a href="<?php echo base_url();?>item/<?php echo $product['slug'];?>"><?php echo html_escape($product['name']);?></a>
									</span>
									<span class="title_top_transac_col2">
										Quantity:<span><?php echo $product['order_quantity']?></span>
									</span>
									<span class="title_top_transac_col3">
										Total:<span>Php<?php echo number_format($product['price'],2,'.',',');?></span>
									</span>
								</div>
								<div class="transac_bought_con">
									<span class="transac_bought_con_col1">Bought from: </span>
									<span class="transac_bought_con_col2"><a href="<?php echo base_url();?><?php echo $product['seller_slug'];?>"><?php echo $product['seller'];?></a></span>
									<span class="transac_bought_con_col3">
										Status:
										<?php if($product['status'] == 1):?>
											<span class="trans_alert trans_green">Item Received</span>
										<?php elseif($product['status'] == 2):?>
											<span class="trans_alert trans_red">Order Canceled</span>
										<?php elseif($product['status'] == 3):?>
											<span class="trans_alert trans_green">Cash on delivery</span>
										<?php elseif($product['status'] == 4):?>
											<span class="trans_alert trans_green">Paid</span>
										<?php elseif($product['status'] == 5):?>
											<span class="trans_alert trans_red">Payment Returned</span>
										<?php endif;?>
									</span>
								</div>
								<?php if( count($product['attr']) !== 0 ):?>
									<div class="show_more_options blue">										
										<span class="span_bg"></span>
										<p>View Features and Specifications</p>
									</div>
									<div class="attr_hide">
										<?php foreach($product['attr'] as $temp):?>
											<p class="feat_and_specs_items"><strong><?php echo html_escape($temp['field']);?>:</strong> <?php echo html_escape($temp['value']);?></p>
										<?php endforeach;?>
									</div>
								<?php endif;?>
								<div class="clear"></div>
							</div>
						</div>
						<?php endforeach;?>	
					</div>
					<div class="feedback_wrapper">
						<?php foreach($transact['users'] as $uk=>$user):?>
						<div class="feedback_container">
							<?php if( $user['has_feedb'] == 0 ): ?>
								<p class="transac-feedback-btn"> + Feedback for <?php echo $user['name'];?></p>
								<div class="transac-feedback-container">
									<h2>Feedback</h2>
									<?php
									$attr = array('class'=>'transac-feedback-form');
									echo form_open('',$attr);
									?>
									<input type="hidden" name="feedb_kind" value="0">
									<input type="hidden" name="order_id" value="<?php echo $tk;?>">
									<input type="hidden" name="for_memberid" value="<?php echo $uk;?>">
									<textarea rows="4" cols="50" name="feedback-field"></textarea>
									<span class="red ci_form_validation_error"><?php echo form_error('feedback-field'); ?></span>
									<br>
									<span class="star-label"><?php echo $this->lang->line('rating')[0].':'; ?>  </span><div class="feedb-star rating1"></div>
									<span class="red ci_form_validation_error"><?php echo form_error('rating1'); ?></span>
									<br>
									<span class="star-label"><?php echo $this->lang->line('rating')[1].':'; ?> </span><div class="feedb-star rating2"></div>
									<span class="red ci_form_validation_error"><?php echo form_error('rating2'); ?></span>
									<br>
									<span class="star-label"><?php echo $this->lang->line('rating')[2].':'; ?>  </span><div class="feedb-star rating3"></div>
									<span class="red ci_form_validation_error"><?php echo form_error('rating3'); ?></span>
									<br>
									<span class="raty-error"></span>
									<br>
									<span class="feedback-submit">Submit</span><span class="feedback-cancel">Cancel</span>
									<?php echo form_close();?>
								</div>
							<?php endif;?>
						</div>
					<?php endforeach;?>
					</div>
	</div>
	</div>
<?php endforeach;?>