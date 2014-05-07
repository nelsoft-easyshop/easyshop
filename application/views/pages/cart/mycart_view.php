<link rel="stylesheet" href="<?=base_url()?>assets/css/my_cart_css.css?ver=1.0" type="text/css" media="screen"/>

        <div class="clear"></div>

        <section>
            <div class="wrapper">
                <h2 class="my_cart_title">My Cart</h2>
                <div class="my_cart_header">
                    <div><input type="checkbox" id="checkAll" checked="checked"> Buy all</div>
                    <div>Price</div>
                    <div>Quantity</div>
                    <div>Sub Total</div>
                    <div></div>
                </div>

                <div class="clear"></div>

				
                <?PHP foreach ($cart_items as $row ): ?>
                <div class="my_cart_content" id="row<?PHP echo $row['rowid']; ?>">
                    
                    <div>
                        <input type="checkbox" class="rad" value="<?PHP echo $row['rowid']; ?>" checked="checked">

                        <a href="<?=base_url().'item/'.$row['id'].'/'.es_url_clean($row['name']);?>" class="has-tooltip" data-image="<?=base_url()?><?php echo $row['img'][0]['path']; ?>categoryview/<?php echo $row['img'][0]['file']; ?>"> 
                            <span style='background-color: #FFFFFF; border: 1px solid #E5E5E5; display: inline-block;'>
                                <span style=' display: table-cell; width: 60px; height: 60px; vertical-align: middle;'>
                                    <img style="max-height: 60px; max-width: 60px; height:auto;width: auto; vertical-align: middle;" src="<?=base_url()?><?php echo $row['img'][0]['path']; ?>thumbnail/<?php echo $row['img'][0]['file']; ?>">
                                </span>
                            </span>
                        </a>
                        <div style='float:right; margin-right: 130px;'>
                        <span class="product_title" style='width:300px !important'>  <a href="<?=base_url().'item/'.$row['id'].'/'.es_url_clean($row['name']);?>"> <?PHP echo html_escape($row['name']); ?></a></span>
                        <br/>
                        <span class="attr_container" style='width:300px !important'>
                            <table>
                                <?PHP 
                                if(!array_filter($row['options'])){
                                    echo  '<tr><td><span class="attr">No additional details</span></td><td></td></tr>';
                                }else{                                    
                                $key =  array_keys($row['options']); //get the key of options,used in checking the product in the database
                                for($a=0;$a < sizeof($key);$a++){
                                    $attr=$key[$a];
                                    $attr_value=$row['options'][$key[$a]];
                                    echo  '<tr><td><span class="attr">'.html_escape($attr).':</span></td><td> <span class="attr_content">'.html_escape($attr_value).'</span></td></tr>';
                                }
                                }
                                ?>
                            </table>
                        </span>
                        </div>
                    </div>
                    <div>
                        <span>
                            <p>&#8369; <?PHP echo number_format($row['price'],2,'.',','); ?></p>
                            <!--<p>&#8369; <?PHP echo number_format($row['price'],2,'.',','); ?></p>
                            <p>Discount 0%</p> -->
                        </span>
                    </div>
                    <div>
                        <span>
                            <input id="<?PHP echo $row['rowid']; ?>" type="text" class="inpt_qty" mx="<?PHP echo $row['maxqty'];?>" onchange="sum(this);" maxlength="3" value="<?PHP echo $row['qty']; ?>">
                        </span>
			<span>
			    <p>Availability : <?PHP echo $row['maxqty']; ?></p>
			</span>
                    </div>
                    <div>
                        <p>Php <p class="subtotal" id="subtotal<?PHP echo $row['rowid']; ?>"><?PHP echo " ".number_format($row['subtotal'],2,'.',','); ?></p></p>
                    </div>
                    <div>
                        <p>
                        <a class="delete"><input type="button" class="del" id="<?PHP echo $row['rowid']; ?>" onclick="del(this.id);" name="delete" value="Remove" > </a>
                        <!-- <a href="">Move to wish list</a> -->
                        </p>
                    </div>
                </div>
                <?PHP endforeach; ?>
                
                <div class="my_cart_total">
                    <p>Total: <span id="total"><?PHP echo $total; ?></span></p>
                    <p><span>VAT included</span></p>
                </div>
                <div class="may_cart_payment">
                    <a href="<?php echo isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url().'product/categories_all'; ?>" class="continue">Continue Shopping</a> 
                    <?php if(!count($cart_items) <=0){ ?>
                     <a class="payment" id="proceed_payment">Proceed to Payment<span></span></a> 
                   <?php } ?>
                   
                </div>
            </div>
        </section>

        <div class="clear"></div>

<script src="<?=base_url().$page_javascript.'?ver=1.0'?>" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        $('#checkAll').click(function () {    
	    if ($(this).prop('checked')) {
			var value = 0;
			$(".subtotal").each(function(){
				value = Number($(".subtotal").html().replace(/\$/g,'').replace(/,/g,'')) + value;
			});
			//$("#total").html(Number(value).toLocaleString('en')+".00");
			$("#total").html(parseFloat(value).toFixed(2));
	    }else{
			$("#total").html("0.00");
	    }
            $('input:checkbox').prop('checked', this.checked);
        });
        $('.rad').click(function () {
			var value = Number($("#subtotal"+$(this).val()).html().replace(/\$/g,'').replace(/,/g,''));
			var total = Number($("#total").html().replace(/\$/g,'').replace(/,/g,''));    
			var sum = 0;
			if ($(this).prop('checked')) {
			sum = value + total;
			}else {
			sum = total - value;
			}
			//$("#total").html(Number(sum).toLocaleString('en')+".00");
			$("#total").html(parseFloat(sum).toFixed(2));
        });
        $("#proceed_payment").click(function(event){
            event.preventDefault();
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');

           var data1 = $(".wrapper input:checkbox:not(:checked)").map(function(){
                return $(this).val();
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
                        data:{itm:data1, csrfname:csrftoken},
                        success:function(data){
			    window.location.replace("<?=base_url()?>payment/review");
                        }
                    });
            }
        });
    });
</script>