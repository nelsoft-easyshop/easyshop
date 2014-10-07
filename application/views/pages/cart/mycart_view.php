<link rel="stylesheet" href="<?=base_url()?>assets/css/my_cart_css.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>

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
                        <input type="checkbox" class="rad" id="rad_<?PHP echo $row['rowid'] ?>" value="<?PHP echo number_format($row['price'] * $row['qty'],2,'.',','); ?>" checked="checked" data="<?PHP echo $row['rowid'] ?>" name="checkbx[]">

                        <a href="<?=base_url().'item/'.$row['slug'];?>" class="has-tooltip" data-image="<?=base_url()?><?php echo $row['imagePath']; ?>categoryview/<?php echo $row['imageFile']; ?>"> 
                            <span style='background-color: #FFFFFF; border: 1px solid #E5E5E5; display: inline-block;'>
                                <span style=' display: table-cell; width: 60px; height: 60px; vertical-align: middle; text-align: center;'>
                                    <img style="max-height: 60px; max-width: 60px; height:auto;width: auto; vertical-align: middle;" src="<?=base_url()?><?php echo $row['imagePath']; ?>thumbnail/<?php echo $row['imageFile'] ?>">
                                </span>
                            </span>
                        </a>
                        <div style='float:right; margin-right: 130px;'>
                            <?PHP if($row['is_promote'] === "1"): ?>
                                <span style="float: right; margin-right: -125px; color: #ff4400; font-size: 12px; border-bottom: 1px dotted;">PROMO ITEM</span>           
                            <?PHP endif; ?>
                            <span class="product_title" style='width:300px !important'>  <a href="<?=base_url().'item/'.$row['slug'];?>"> <?PHP echo html_escape($row['name']); ?></a></span>
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
                                    $attr_value2 = explode('~', $attr_value);
                                    echo  '<tr><td><span class="attr">'.html_escape($attr).':</span></td><td> <span class="attr_content">'.html_escape($attr_value2[0]).'</span></td></tr>';
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
                            <?PHP if($row['is_promote'] === "1"): ?>
                                <p>&#8369; <?PHP echo number_format($row['original_price'],2,'.',','); ?></p>
                                <p>Discount <?php echo round(($row['original_price'] - $row['price'])/$row['original_price'] * 100);?>%</p>
                            <?PHP endif; ?>
                        </span>
                    </div>
                    <div>
                        <span>
                            <input id="<?PHP echo $row['rowid']; ?>" onkeypress="return isNumberKey(event);" type="text" class="inpt_qty" mx="<?PHP echo $row['maxqty'];?>" onchange="sum(this);" maxlength="3" value="<?PHP echo $row['qty']; ?>">
                        </span>
			<span>
			    <p>Availability : <?PHP echo $row['maxqty']; ?></p>
			</span>
                    </div>
                    <div>
                        <?PHP
                            $totalprice = $row['price'] * $row['qty'];
                        ?>
                        <p>Php <p class="subtotal" id="subtotal<?PHP echo $row['rowid']; ?>"><?PHP echo " ".number_format($totalprice,2,'.',','); ?></p></p>
                    </div>
                    <div>
                        <p>
                        <a class="delete"><input type="button" class="del span_bg cart_delete" id="<?PHP echo $row['rowid']; ?>" onclick="del(this.id);" name="delete" value="Remove" > </a>
                        <!-- <a href="">Move to wish list</a> -->
                        </p>
                    </div>
                </div>
                <?PHP endforeach; ?>
                
                <div class="my_cart_total">
                    <p>Total: &#8369;<span id="total"><?PHP echo $total; ?></span></p>
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