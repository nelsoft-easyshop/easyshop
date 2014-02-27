<link rel="stylesheet" href="<?=base_url()?>assets/css/my_cart_css.css" type="text/css" media="screen"/>

        <div class="clear"></div>

        <section>
            <div class="wrapper">
                <h2 class="my_cart_title">My Cart</h2>
				<input type="hidden" id="mycart" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
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
             
                        <a href="<?=base_url().'item/'.$row['id'].'/'.es_url_clean($row['name']);?>" class="has-tooltip" data-image="<?=base_url()?><?php echo $row['img'][0]['path']; ?>categoryview/<?php echo $row['img'][0]['file']; ?>"><img style="height:60px;width: 60px" src="<?=base_url()?><?php echo $row['img'][0]['path']; ?>thumbnail/<?php echo $row['img'][0]['file']; ?>"></a>
                        <p class="product_title"><?PHP echo html_escape($row['name']); ?></p>
                        <p class="attr_container">
                            <table>
                                <?PHP 
                                if(!array_filter($row['options'])){
                                    echo  '<tr><td><span class="attr">No attributes</span></td><td></td></tr>';
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
                        </p>
                    </div>
                    <div>
                        <span>
                            <p>&#8369; <?PHP echo number_format($row['price'],2,'.',','); ?></p>
                            <p>&#8369; <?PHP echo number_format($row['price'],2,'.',','); ?></p>
                            <p>Discount 0%</p>
                        </span>
                    </div>
                    <div>
                        <span>
                            <input id="<?PHP echo $row['rowid']; ?>" type="text" class="inpt_qty" onchange="sum(this.value,this.id);" maxlength="3" value="<?PHP echo $row['qty']; ?>">
                        </span>
                    </div>
                    <div>
                        <p>Php <p class="subtotal" id="subtotal<?PHP echo $row['rowid']; ?>"><?PHP echo " ".number_format($row['subtotal'],2,'.',','); ?></p></p>
                    </div>
                    <div>
                        <p>
                        <a class="delete"><input type="button" class="del" id="<?PHP echo $row['rowid']; ?>" onclick="del(this.id);" name="delete" value="Remove" > </a>
                            <a href="">Move to wish list</a>
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

<script src="<?=base_url().$page_javascript?>" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        $('#checkAll').click(function () {    
            $('input:checkbox').prop('checked', this.checked);    
        });
        $("#proceed_payment").click(function(event){
            event.preventDefault();
			var csrftoken = $('#mycart').val();
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
                alert("You must select atleast ONE to proceed to payment");
            }else{
                    $.ajax({
                        async:true,
                        url:"<?=base_url()?>payment/cart_items",
                        type:"POST",
                        data:{itm:data1, es_csrf_token:csrftoken},
                        success:function(data){
                            window.location.replace("<?=base_url()?>payment/shipping");
                        }
                    });
            }
        });
    });
</script>