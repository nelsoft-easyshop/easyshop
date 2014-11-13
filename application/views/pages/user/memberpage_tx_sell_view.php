<?php foreach($transaction['sell'] as $tk=>$transact):?>
			<div class="transac-container content-paging" data-pm="<?php echo $transact['payment_method']?>" data-invoice="<?php echo $transact['invoice_no']?>">
				<div class="transac_title">
					<?php if($transact['transac_stat'] != 99 && $transact['is_flag'] == 0): ?>
					<div class="transac_title_table">
						<div class="transac_title_col1">
							<span><strong>Transaction No.: </strong></span>		
							<span><?php echo $transact['invoice_no'];?></span>  
						</div>
						<div class="transac_title_col2">
							<span><strong>Date:</strong></span>
							<span class="transac_title_date"><?php echo $transact['dateadded']?></span>
						</div>
						<div class="transac_title_col3">
							<strong>Sold to: </strong> <a href="/<?php echo $transact['buyer_slug']?>"><?php echo $transact['buyer']?></a> <br />
							<span class="transac_address_details_show" style="color:#0191C8;cursor:pointer;font-size:10px;text-decoration:underline;">View Delivery details</span>
							<div style="display:none;" class="transac_address_cont">
								<?php foreach($transact['users'] as $uk=>$user):?>
									<span class="tad_1 consignee">
										<strong>Consignee: </strong>
										<span><?php echo html_escape($user['address']['consignee']);?></span>
									</span>
									<?php if(trim($user['address']['mobile']) != ''):?>
										<span class="tad_1 mobile">
											<strong>Mobile: </strong> 
											<span><?php echo html_escape($user['address']['mobile']);?></span>
										</span>
									<?php endif;?>
									<?php if(trim($user['address']['telephone']) != ''):?>
										<span class="tad_1 telephone">
											<strong>Telephone: </strong>
											<span><?php echo html_escape($user['address']['telephone']);?></span> 
										</span>
									<?php endif;?>
									<span style="display:block;"></span>
									<span class="tad_2 stateregion">
										<strong>State/Region:</strong>
										<span><?php echo $user['address']['stateregion']?></span> 
									</span>
									<span class="tad_2 city">
										<strong>City:</strong>
										<span><?php echo $user['address']['city'];?></span> 
									</span>
									<span class="tad_3 fulladdress">
										<strong>Address:</strong>
										<span><?php echo html_escape($user['address']['fulladd']);?></span> 
									</span>
									<?php if( $user['address']['lat']!=0 && $user['address']['lng']!=0 ):?>
										<span class="tsold_viewmap" data-lat="<?php echo $user['address']['lat'];?>" data-lng="<?php echo $user['address']['lng'];?>">View Map</span>
										<div class="map_modalcont" style="display:none;"></div>
									<?php endif;?>
								<?php endforeach;?>
								<span class="transac_address_details_hide">Close</span>
							</div>
							</span>
						</div>
					</div>
					<?php else:?>
						<?php if($transact['payment_method'] == 2):?>
							<span><strong>ON HOLD - PENDING DRAGONPAY PAYMENT FROM <?php echo html_escape($transact['buyer'])?></strong></span>
						<?php elseif($transact['payment_method'] == 5):?>
							<span><strong>ON HOLD - PENDING BANK DEPOSIT DETAILS FROM <?php echo html_escape($transact['buyer'])?></strong></span>
						<?php elseif($transact['payment_method'] == 1 && $transact['is_flag'] == 1):?>
							<span><strong>ON HOLD - PAYPAL PAYMENT UNDER REVIEW FROM <?php echo html_escape($transact['buyer'])?></strong></span>
						<?php endif;?>
					<?php endif;?>
				</div>
				<div class="transac_prod_wrapper">
					
					<?php foreach($transact['products'] as $opk=>$product):?>
					<div class="sold_prod_container transac-product-container">
						<div class="transac_prod_first">
							<div class="img_transac_prod_con">
								<span class="img_transac_prod">
									<img src="<?php echo getAssetsDomain()?><?php echo $product['product_image_path'];?>">
								</span>
							</div>
							<div class="info_transac_prod_con">
								<div class="title_top_transac_table">
									<span class="title_top_transac_col1">
										<a href="/item/<?php echo $product['slug'];?>"><?php echo html_escape($product['name']);?></a>
									</span>
									<span class="title_top_transac_col2">
										Quantity:<span><?php echo $product['order_quantity']?></span>
									</span>
									<span class="title_top_transac_col3">
										Total:<span>Php<?php echo number_format($product['price'],2,'.',',');?></span>
									</span>
								</div>
								<div class="transac_bought_con tx_cont">
									
									<span class="transac_bought_con_col3 tx_cont_col3">
										Status:
									<?php if($transact['transac_stat'] == 0 && $transact['is_flag'] == 0):?>
										<?php if($product['is_reject'] == 1):?>
											<span class="trans_alert trans_red">Item Rejected</span>
										<?php else:?>
											<?php if($product['status'] == 0):?>
												<?php if( $transact['payment_method'] == 3 ):?>
													<span class="trans_alert trans_green">Cash on delivery</span>
												<?php else:?>
													<?php if( $product['has_shipping_summary'] == 0 ):?>
														<span class="trans_alert trans_red">Easyshop received payment</span>
													<?php elseif( $product['has_shipping_summary'] == 1 ):?>
														<span class="trans_alert trans_orange">Item shipped</span>
													<?php endif;?>
												<?php endif;?>
											<?php elseif($product['status'] == 1):?>
												<span class="trans_alert trans_green">Buyer received item</span>
											<?php elseif($product['status'] == 2):?>
												<span class="trans_alert trans_red">Order Canceled</span>
											<?php elseif($product['status'] == 3):?>
												<span class="trans_alert trans_green">Cash on delivery</span>
											<?php elseif($product['status'] == 4):?>
												<span class="trans_alert trans_green">Payment Received</span>
											<?php elseif($product['status'] == 5):?>
												<span class="trans_alert trans_red">Payment Returned</span>
											<?php endif;?>
										<?php endif;?>
									<?php else:?>
										<span class="trans_alert trans_red">On Hold</span>
									<?php endif;?>
									</span>
				
								</div>
								
								<div class="transac_prod_btns tx_btns">
									<?php if($transact['transac_stat'] == 0 && $product['status'] == 0 && $transact['payment_method'] != 3  && $transact['is_flag'] == 0):?>										
										
										<div style="display:inline-block;"><input type="button" class="shipping_comment isform transac_orange_btn" value="Ship Item"></div>
										<div class="shipping_comment_cont" style="display:none;">
											<h2>Shipping Details</h2>
											<div>
												<?php
													$disable = trim($product['shipping_comment']) == '' ? false : true;
													$attr = array('class'=>'shipping_details');
													echo form_open('',$attr);
												?>
                                                    <br/>
													<label for="courier"><span style="color:red;">*</span> Shipped By: </label>
													<input type="text" name="courier" value="<?php echo html_escape($product['courier']);?>" placeholder="(e.g. LBC, Air21)"<?php echo $disable ? 'disabled':''; ?> ><br/>
													<label for="tracking_num">Tracking Number: </label>
													<input type="text" name="tracking_num" value="<?php echo html_escape($product['tracking_num']);?>" <?php echo $disable ? 'disabled':''; ?> ><br/>
													<label for="delivery_date"><span style="color:red;">*</span> Delivery Date: </label>
													<input autocomplete="off" type="text" class="modal_date" name="delivery_date" value="<?php echo html_escape($product['delivery_date'])?>" <?php echo $disable ? 'disabled':''; ?> > <br/>
													<label for="expected_date">Expected Date of Arrival: </label>
													<input autocomplete="off" type="text" class="modal_date" name="expected_date" value="<?php echo html_escape($product['expected_date'])?>" <?php echo $disable ? 'disabled':''; ?> ><br/>
                                                    <br/>
                                                    <label for="comment">Comments: </label>
													<textarea name="comment" cols="55" rows="5" data-value="<?php echo html_escape($product['shipping_comment']); ?>" <?php echo $disable ? 'disabled':''; ?>><?php echo html_escape($product['shipping_comment']); ?></textarea>
													<input name="order_product" type="hidden" value="<?php echo $opk;?>">
													<input name="transact_num" type="hidden" value="<?php echo $tk;?>">
													<input class="shipping_comment_submit orange_btn3" type="submit" value="Save">
													<div class="tx_modal_edit css_modal_edit footer" style="display: <?php echo $disable ? '':'none'?>;">Edit</div>
													<div class="tx_modal_cancel css_modal_cancel footer" style="display:none;">Cancel</div>
													<div class="css_modal_date footer"><?php echo $product['datemodified'];?></div>
												<?php echo form_close();?>
											</div>
										</div>
										
										<?php
											$attr = array('class'=>'transac_response');
											echo form_open('',$attr);
										?>
										<input type="button" value="Cancel Order" class="transac_response_btn tx_return">
										<input type="hidden" name="seller_response" value="<?php echo $opk;?>">
										<input type="hidden" name="transaction_num" value="<?php echo $tk;?>">
										<input type="hidden" name="invoice_num" value="<?php echo $transact['invoice_no'];?>">
										<?php echo form_close();?>
										
									<?php elseif( $transact['transac_stat'] == 0 && $product['status'] == 0 && $transact['payment_method'] == 3 ):?>
										<?php
											$attr = array('class'=>'transac_response');
											echo form_open('',$attr);
										?>
										<input type="button" value="Completed" class="transac_response_btn tx_cod">
										<input type="hidden" name="cash_on_delivery" value="<?php echo $opk;?>">
										<input type="hidden" name="transaction_num" value="<?php echo $tk;?>">
										<input type="hidden" name="invoice_num" value="<?php echo $transact['invoice_no'];?>">
										<?php echo form_close();?>
									<?php endif;?>
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
					</div>
				<?php endforeach;?>
			</div>

			<div class="feedback_wrapper">
				<?php foreach($transact['users'] as $uk=>$user):?>
				<?php if($transact['transac_stat'] != 99):?>
				<div class="feedback_container">
					<?php if( $user['has_feedb'] == 0 ): ?>
						<p class="transac-feedback-btn"> + Feedback for <?php echo html_escape($user['name']);?></p>
						<div class="transac-feedback-container">
							<h2>Feedback</h2>
							<?php
							$attr = array('class'=>'transac-feedback-form');
							echo form_open('',$attr);
							?>
							<input type="hidden" name="feedb_kind" value="1">
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
							<span class="feedback-submit">Submit</span> <span class="feedback-cancel">Cancel</span>
							<?php echo form_close();?>
						</div>
					<?php endif;?>
				</div>
			<?php endif;?>
			<?php endforeach;?>
			</div>
	</div>
<?php endforeach;?>