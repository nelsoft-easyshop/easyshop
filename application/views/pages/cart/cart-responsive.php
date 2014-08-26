<link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?=base_url()?>assets/css/my_cart_css.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>

<div class="container container-cart-responsive">	
	<h2 class="my_cart_title">My Cart</h2>
	<table width="100%" class="table table-responsive font-roboto hide-to-536">
		<tr class="tr-header-cart">
			<td style="vertical-align: middle;" width="5%"><input type="checkbox" id="checkAll" checked="checked"/></td>
			<td align="left" colspan="2" width="35%">Item List</td>
			<td align="center" width="20%">Price</td>
			<td align="center" width="20%">Quantity</td>
			<td align="right" width="20%">Sub Total</td>
			<td width="5%"  class="display-when-desktop">&nbsp;</td>
		</tr>
		 <?php foreach ($cart_items as $row ): ?>
		<tr id="row<?php echo $row['rowid']; ?>" class="tr-cart-content">
			<td> 
				<input type="checkbox" class="rad" id="rad_<?php echo $row['rowid'] ?>" value="<?php echo number_format($row['price'] * $row['qty'],2,'.',','); ?>" checked="checked" data="<?php echo $row['rowid'] ?>" name="checkbx[]">
			</td>
			<td width="7%">
				 <a href="<?=base_url().'item/'.$row['slug'];?>" class="has-tooltip" data-image="<?=base_url()?><?php echo $row['img'][0]['path']; ?>categoryview/<?php echo $row['img'][0]['file']; ?>">
					<img class="img-responsive thumbnail no-border thumbnail-item" src="<?=base_url()?><?php echo $row['img'][0]['path']; ?>thumbnail/<?php echo $row['img'][0]['file']; ?>">
				</a>
				
			</td>
			<td style="align:left;">
				<a class="product_title" href="<?=base_url().'item/'.$row['slug'];?>"> <?php echo html_escape($row['name']); ?></a>
				
				
				<br/>
				 <?php 
					if(!array_filter($row['options'])){
						echo  '<span class="attr b-label font-12">No additional details</span>';
					}else{                                    
					$key =  array_keys($row['options']); //get the key of options,used in checking the product in the database
					for($a=0;$a < sizeof($key);$a++){
						$attr=$key[$a];
						$attr_value=$row['options'][$key[$a]];
						$attr_value2 = explode('~', $attr_value);
						echo  '<span class="attr b-label font-12">'.html_escape($attr).':</span><span class="b-label font-12">'.html_escape($attr_value2[0]).'</span><br/>';
					}
					}
				?>
				
			</td>
			<td style="vertical-align:top; text-align:center;">
				<span class="span-price">
				<p class="p-price">&#8369; <?php echo number_format($row['price'],2,'.',','); ?></p>
				 <?php if($row['is_promote'] === "1"): ?>
					<p>&#8369; <?php echo number_format($row['original_price'],2,'.',','); ?></p>
					<p>Discount <?php echo round(($row['original_price'] - $row['price'])/$row['original_price'] * 100);?>%</p>
				<?php endif; ?>
				</span>
			</td>
			<td>
				<center>
					<input id="<?php echo $row['rowid']; ?>" onkeypress="return isNumberKey(event);" type="text" class="inpt_qty" mx="<?php echo $row['maxqty'];?>" onchange="sum(this);" maxlength="3" value="<?php echo $row['qty']; ?>">
					<br/>
					<p class="p-availability">Availability : <?php echo $row['maxqty']; ?></p>
				</center>
			</td>
			<td style="text-align: right;">
				<?php
					$totalprice = $row['price'] * $row['qty'];
				?>
				<p class="subtotal" >Php <span id="subtotal<?php echo $row['rowid']; ?>"><?php echo " ".number_format($totalprice,2,'.',','); ?></span></p>
				<p class="display-when-mobile">
					<a class="btn btn-orange btn-remove"><i class="glyphicon glyphicon-trash"></i> Remove</a>
					<!-- <a href="">Move to wish list</a> -->
				</p>
			</td>
			<td class="display-when-desktop" align="center" >
				<p>
					<a class="delete"><input type="button" class="del span_bg cart_delete" id="<?php echo $row['rowid']; ?>" onclick="del(this.id);" name="delete" value="Remove" style="font-size: 13px;"> </a>
					<!-- <a href="">Move to wish list</a> -->
				</p>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<div class="display-when-mobile-536">
		<table width="100%" class="table table-responsive font-roboto">
			<tr class="tr-header-cart">
				<td style="vertical-align: middle;" width="5%"><input type="checkbox" id="checkAll" checked="checked"/></td>
				<td align="left" colspan="2" width="35%">Item List</td>
			</tr>
			 <?php foreach ($cart_items as $row ): ?>
			<tr id="row<?php echo $row['rowid']; ?>" class="tr-cart-content">
				<td>
					<input type="checkbox" class="rad" id="rad_<?php echo $row['rowid'] ?>" value="<?php echo number_format($row['price'] * $row['qty'],2,'.',','); ?>" checked="checked" data="<?php echo $row['rowid'] ?>" name="checkbx[]">
				</td>
				<td>
					<div class="row">
						<div class="col-sm-1">
							<a class="product_title" href="<?=base_url().'item/'.$row['slug'];?>"> <?php echo html_escape($row['name']); ?></a>
							
							<table width="100%">
								<tr>
									
									<td width="50%">
										<a href="<?=base_url().'item/'.$row['slug'];?>" class="has-tooltip" data-image="<?=base_url()?><?php echo $row['img'][0]['path']; ?>categoryview/<?php echo $row['img'][0]['file']; ?>">
											<img class="img-responsive thumbnail no-border thumbnail-item" src="<?=base_url()?><?php echo $row['img'][0]['path']; ?>thumbnail/<?php echo $row['img'][0]['file']; ?>">
										</a>
									</td>
									<td style="vertical-align: top; text-align:right;" align="right"  width="50%">
										<p style="margin-top:-15px;">
											<?php
												$totalprice = $row['price'] * $row['qty'];
											?>
											<p class="subtotal pull-right" >Php <span id="subtotal<?php echo $row['rowid']; ?>"><?php echo " ".number_format($totalprice,2,'.',','); ?></span></p>
										</p>
										
										<p align="right" style="margin-top: 52px;">
											<a class="btn btn-orange btn-remove pull-right"><i class="glyphicon glyphicon-trash"></i> Remove</a>
										</p>
									</td>
								</tr>
							</table>
							
						</div>
						<div class="col-sm-1">
							
							<table style="margin-top: -15px;">
								<tr>
									<td>
										<b class="b-label">Price: </b>
									</td>
									<td style="padding-left: 5px;">
										<span class="span-price">
										<span class="p-price">&#8369; <?php echo number_format($row['price'],2,'.',','); ?></span>
										 <?php if($row['is_promote'] === "1"): ?>
											<p>&#8369; <?php echo number_format($row['original_price'],2,'.',','); ?></p>
											<p>Discount <?php echo round(($row['original_price'] - $row['price'])/$row['original_price'] * 100);?>%</p>
										<?php endif; ?>
										</span>
									</td>
								</tr>
								<tr>
									<td>
										<b class="b-label">Quantity: </b> 
									</td>
									<td style="padding-left: 5px;">
										<input id="<?php echo $row['rowid']; ?>" onkeypress="return isNumberKey(event);" type="text" class="inpt_qty" mx="<?php echo $row['maxqty'];?>" onchange="sum(this);" maxlength="3" value="<?php echo $row['qty']; ?>" style="height: 25px !important;">
									</td>
								</tr>
								<tr>
									<td>
										<b class="b-label">Availability: </b>
									</td>
									<td style="padding-left: 5px; margin-top: 5px; font-size: 12px;">
										
										<?php echo $row['maxqty']; ?>
									</td>
								</tr>
								<br/>
								 <?php 
									if(!array_filter($row['options'])){
										echo  '<tr><td colspan=\'2\'><span class="attr b-label">No additional details</span></td></tr>';
									}else{                                    
									$key =  array_keys($row['options']); //get the key of options,used in checking the product in the database
									for($a=0;$a < sizeof($key);$a++){
										$attr=$key[$a];
										$attr_value=$row['options'][$key[$a]];
										$attr_value2 = explode('~', $attr_value);
										echo  '<tr><td><span class="attr b-label"><b>'.html_escape($attr).':</b></span></td><td style="padding-left: 5px;"><span class="font-12">'.html_escape($attr_value2[0]).'</span></td></tr>';
									}
									}
								?>
							</table>
						</div>
					</div>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
	
	<div class="my_cart_total my_cart_total_div pull-right roboto">
		<p>Total: &#8369;<span id="total"><?php echo $total; ?></span></p>
		<p><span>VAT included</span></p>
	</div>
	<div class="display-when-desktop pull-right">
		<div class="may_cart_payment">
			<a href="<?php echo isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url().'product/categories_all'; ?>" class="continue">Continue Shopping</a> 
			<?php if(!count($cart_items) <=0){ ?>
			 <a class="btn payment" id="proceed_payment">Proceed to Payment<span></span></a> 
		    <?php } ?>
		   
		</div>
	</div>
	
	<div class="display-when-mobile" style="margin-top: -20px !important;">
		<div class="may_cart_payment">
			<center>
				<a href="<?php echo isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url().'product/categories_all'; ?>" class="continue">Continue Shopping</a>
			</center>
			
			<?php if(!count($cart_items) <=0){ ?>
			 <a class="btn payment btn-lg btn-block" id="proceed_payment">Proceed to Payment<span></span></a> 
		   <?php } ?>
		   
		</div>
	</div>	
</div>	


<div id="navigator">
</div>
<div class="clear"></div>
        
<script src="<?=base_url()?>assets/js/src/vendor/numeral.min.js"></script>
<script src="<?=base_url()?>assets/js/src/cart.js" type="text/javascript"></script>

<script>
    $(document).ready(function(){
        $('#checkAll').click(function () {
	
            $('input:checkbox').prop('checked', this.checked);
			value = 0;
			if ($(this).prop('checked')) {
				for (var i=0;i<$(".subtotal").length;i++) {
					value = Number($(".subtotal").eq(i).html().replace(/\$/g,'').replace(/,/g,'')) + value;
				}
				$("#total").html(Number(value).toLocaleString('en')+".00");
				$("#total").html(numeral(parseFloat(value).toFixed(2)).format('0,0.00'));
								}else{
				$("#total").html("0.00");
			}
        });
        $('.rad').on("click", function () {
			
            var total = 0;
            $('.rad').each(function(){
                if($(this).prop('checked')){
                    total += parseFloat($(this).val().replace(/,/g,''));
                }
            });
            var ttl = numeral(parseFloat(total).toFixed(2)).format('0,0.00');
            $("#total").html(ttl);
        });
        
        
        $("#proceed_payment").click(function(event){
            event.preventDefault();
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');

           var data1 = $(".wrapper input:checkbox:not(:checked)").map(function(){
                return $(this).attr('data');
            }).toArray();

            var a = parseInt(0);
            var b = parseInt(0);
            $(".wrapper").find(".rad").each(function(){
                if ($(this).prop('checked')==false){
                    a ++;
                }
                b ++;
            });
            if(a==b){
                alert("You must select at least one item to proceed with your payment");
            }else{
                    $.ajax({
                        async:true,
                        url:"<?=base_url()?>payment/cart_items",
                        type:"POST",
                        dataType:"json",
                        data:{itm:data1, csrfname:csrftoken},
                        success:function(data){
                            if(data == true){
                                window.location.replace("<?=base_url()?>payment/review");
                            }else{
                                alert(data,  'Remove these items from your cart to proceed with your checkout.');
                            }
                        }
                    });
            }
        });

    });
</script>