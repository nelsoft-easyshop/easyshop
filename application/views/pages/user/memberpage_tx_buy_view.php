<?php foreach($transaction['buy'] as $tk=>$transact):?>
		<div class="transac-container content-paging" data-pm="<?php echo $transact['payment_method']?>" data-invoice="<?php echo $transact['invoice_no']?>">
			<div class="transac_title">
				<?php if($transact['payment_method']==1 && $transact['is_flag'] == 1):?>
					<span><strong>ON HOLD - PAYPAL PAYMENT UNDER REVIEW</strong></span>
				<?php else:?>
				<div class="transac_title_table">
					<div class="transac_title_col1">
						<span><strong>Transaction No.:</strong></span>
						<span><?php echo $transact['invoice_no'];?></span>
					</div>
					<div class="transac_title_col2">
						<span><strong>Date:</strong></span>
						<span class="transac_title_date"><?php echo $transact['dateadded']?></span>
					</div>
					<!-- If payment method is dragon pay / direct bank deposit-->
					<div class="transac_title_col3">
						<?php if( ($transact['payment_method'] == 2 || $transact['payment_method'] == 4) && $transact['transac_stat'] == 99):?>
							<?php $attr = array('class'=>'');
								echo form_open('',$attr);
							?>
								<input type="button" class="dragonpay_update_btn css_dp_btn" name="dragonpay_update_btn" value="Confirm Dragonpay Payment">
                                <input type="hidden" name="invoice_num" value="<?php echo $transact['invoice_no'];?>">
								<input type="hidden" name="transaction_num" value="<?php echo $tk;?>">
								<input type="hidden" name="dragonpay" value="1">
							<?php echo form_close();?>
						<?php elseif($transact['payment_method'] == 5 && $transact['transac_stat'] == 99):?>
								
								<a class='payment_details_btn' href='javascript:void(0)' style='float:right; text-decoration:underline; font-weight:bold;'>+ Add Deposit Details</a>
								
                                <div class="payment_details_cont" style="display:none;">
								<?php $attr = array('class'=>'payment_bankdeposit');
									$disable = $transact['bd_details']['bd_datemodified'] != '' ? true : false;
									echo form_open('',$attr);
								?>
									<h2>Payment Details</h2>
									<label for="bank"><span style="color:red;">*</span> Bank: </label> 
									<input type="text" name="bank" value="<?php echo html_escape($transact['bd_details']['bank'])?>" <?php echo $disable ? 'disabled':''?>><br/>
									<label for="ref_num"><span style="color:red;">*</span> Reference #: </label>
									<input type="text" name="ref_num" value="<?php echo html_escape($transact['bd_details']['ref_num'])?>" <?php echo $disable ? 'disabled':''?>><br/>
									<label for="amount"><span style="color:red;">*</span> Amount Deposited: </label>
									<input type="text" name="amount" class="bankdeposit_amount price" value="<?php echo  $transact['bd_details']['amount'] != '' ? html_escape(number_format($transact['bd_details']['amount'],2,'.',',')) : '';?>" <?php echo $disable ? 'disabled':''?>><br/>
									<label for="date"><span style="color:red;">*</span> Date of Deposit: </label>
									<input type="text" name="date" class="modal_date" value="<?php echo html_escape($transact['bd_details']['date_deposit'])?>" <?php echo $disable ? 'disabled':''?> autocomplete="off"><br/>
									<label for="comment">Comments: </label>
									<textarea name="comment" cols="55" rows="5" data-value="<?php echo html_escape($transact['bd_details']['comment'])?>" <?php echo $disable ? 'disabled':''?>><?php echo html_escape($transact['bd_details']['comment'])?></textarea>
									<input type="hidden" name="invoice_num" value="<?php echo $transact['invoice_no'];?>">
									<input type="hidden" name="transaction_num" value="<?php echo $tk;?>">
									<input type="hidden" name="bank_deposit" value="1">
									<input type="submit" class="bank_deposit_submit orange_btn3" name="bank_deposit_submit" value="Submit">
									<div class="tx_modal_edit css_modal_edit footer" style="display:<?php echo $disable ? '':'none;'?>">Edit</div>
									<div class="tx_modal_cancel css_modal_cancel footer" style="display:none;">Cancel</div>
									<div class="css_modal_date footer"><?php echo $transact['bd_details']['bd_datemodified']?></div>
								<?php echo form_close();?>
								</div>
						<?php endif;?>
					</div>
					<!-- End of dragonpay / direct bank deposit button-->
				</div>
				<?php endif;?>
			</div>
			
			<?php if( $transact['payment_method'] == 5 && $transact['transac_stat'] == 99 ):?>
				<div style="margin-left:5px;border-bottom:1px dotted #CECECE;margin-bottom:10px;padding-bottom:10px;">
					<p><strong>Deposit payment to:</strong></p>
					<p>
						<span style="margin:0px 5px 0px 3em;">Bank:</span>
						<span style="padding-right:2em;margin-right:3em;font-weight:bold;"><?php echo html_escape($transact['bank_template']['bank_name']);?></span>
						<br>
						<span style="margin-right:5px;margin-left:3em;">Bank Account Name:</span>
						<span style="padding-right:2em;margin-right:3em;border-right:1px dotted #CECECE;font-weight:bold;"><?php echo html_escape($transact['bank_template']['bank_accname']);?></span>
						<span style="margin-right:5px;">Bank Account Number:</span>
						<span style="padding-right:2em;margin-right:3em;font-weight:bold;"><?php echo html_escape($transact['bank_template']['bank_accnum']);?></span>
					</p>
				</div>
			<?php endif;?>
			
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
										Quantity: <span><?php echo $product['order_quantity']?></span>
									</span>	
									<span class="title_top_transac_col3">
										Total: <span>Php <?php echo number_format($product['price'],2,'.',',');?></span>
									</span>
								</div>	
								<div class="transac_bought_con tx_cont">
									<span class="transac_bought_con_col1">Bought from: </span>
									<span class="transac_bought_con_col2">
										<a href="<?php echo base_url();?><?php echo $product['seller_slug'];?>">
											<?php echo html_escape($product['seller']);?>
										</a>
									</span>
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
															<span class="trans_alert trans_red">Pending Shipping Info</span>
														<?php elseif( $product['has_shipping_summary'] == 1 ):?>
															<span class="trans_alert trans_orange">Item on route</span>
														<?php endif;?>
													<?php endif;?>
												<?php elseif($product['status'] == 1):?>
													<span class="trans_alert trans_green">Item Received</span>
												<?php elseif($product['status'] == 2):?>
													<span class="trans_alert trans_red">Seller canceled order</span>
												<?php elseif($product['status'] == 3):?>
													<span class="trans_alert trans_green">Cash on delivery</span>
												<?php elseif($product['status'] == 4):?>
													<span class="trans_alert trans_green">Paid</span>
												<?php elseif($product['status'] == 5):?>
													<span class="trans_alert trans_red">Payment Refunded</span>
												<?php endif;?>
											<?php endif;?>
										<?php else:?>
											<?php if($transact['payment_method'] == 2):?>
												<span class="trans_alert trans_red">CONFIRM DRAGONPAY PAYMENT</span>
											<?php elseif($transact['payment_method'] == 5):?>
												<?php if( $transact['bd_details']['bd_datemodified'] != '' ):?>
													<?php if($transact['bd_details']['is_invalid'] == 1):?>
														<span class="trans_alert trans_red">WRONG DEPOSIT DETAILS</span>
													<?php else:?>
														<span class="trans_alert trans_orange">PROCESSING DEPOSIT DETAILS</span>
													<?php endif;?>
												<?php else:?>
													<span class="trans_alert trans_red">DEPOSIT DETAILS REQUIRED</span>
												<?php endif;?>
											<?php elseif($transact['payment_method'] == 1 && $transact['is_flag'] == 1):?>
												<span class="trans_alert trans_red">ON HOLD</span>
											<?php endif;?>
										<?php endif;?>
                                        
										<?php if( $product['has_shipping_summary'] == 1 ):?>
											<div><span class="shipping_comment">+ View shipment detail</span></div>
											<div class="shipping_comment_cont" style="display:none;">
												<h2>Shipping Details</h2>
												<div>   
                                                    <br/>
													<label for="courier">Shipped By: </label>
													<input type="text" name="courier" value="<?php echo html_escape($product['courier']);?>" disabled ><br/>
													<label for="tracking_num">Tracking Number: </label>
													<input type="text" name="tracking_num" value="<?php echo html_escape($product['tracking_num']);?>" disabled ><br/>
													<label for="delivery_date">Delivery Date: </label>
													<input type="text" name="delivery_date" value="<?php echo html_escape($product['delivery_date'])?>" disabled> <br/>
													<label for="expected_date">Expected Date of Arrival: </label>
													<input type="text" name="expected_date" value="<?php echo html_escape($product['expected_date'])?>" disabled><br/>
													<br/>
                                                    <label for="comment">Comments: </label>
													<textarea name="comment" cols="55" rows="5" disabled ><?php echo html_escape($product['shipping_comment']); ?></textarea>								
													<span style="display: block;margin-left: 33em;padding: 10px 0; font-weight:bold;"><?php echo $product['datemodified'];?></span>
												</div>
											</div>
										<?php endif;?>
									</span>
								</div>
								
								<?php if( $product['has_shipping_summary'] == 1 && $transact['transac_stat'] == 0 && $product['status'] == 0 && $transact['payment_method'] != 3 && $transact['is_flag'] == 0):?>
									<div class="transac_prod_btns tx_btns">
										<?php
											$attr = array('class'=>'transac_response');
											echo form_open('',$attr);
										?>							
											<input type="button" value="Item received" class="transac_response_btn tx_forward transac_orange_btn">
											<input type="hidden" name="buyer_response" value="<?php echo $opk;?>">
											<input type="hidden" name="transaction_num" value="<?php echo $tk;?>">
											<input type="hidden" name="invoice_num" value="<?php echo $transact['invoice_no'];?>">
										<?php echo form_close();?>
										
										<?php echo form_open('');?>
											<?php if($product['is_reject'] == 0):?>
												<input type="button" value="Reject Item" class="reject_btn reject_item	reject">
												<input type="hidden" name="method" value="reject">
											<?php else:?>
												<input type="button" value="Unreject Item" class=" reject_btn reject_item unreject">
												<input type="hidden" name="method" value="unreject">
											<?php endif;?>
											
											<input type="hidden" name="order_product" value="<?php echo $opk;?>">
											<input type="hidden" name="transact_num" value="<?php echo $tk;?>">
											<input type="hidden" name="seller_id" value="<?php echo $product['seller_id']?>">
										<?php echo form_close();?>										
									</div>
								<?php endif; ?>
								
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
							</div>
							<div class="clear"></div>
						</div>
						<?php endforeach;?>
					</div>
				 <div class="feedback_wrapper">
					<?php foreach($transact['users'] as $uk=>$user):?>
					<div class="feedback_container">
						<?php if( $user['has_feedb'] == 0 ): ?>
							<p class="transac-feedback-btn"> + Feedback for <?php echo html_escape($user['name']);?></p>
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