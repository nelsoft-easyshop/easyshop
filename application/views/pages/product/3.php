    <div class="cd_product_container">
        <div>
            <?php foreach($items as $item): ?>
                <div class="cd_product">
                   <?php if($item['is_soldout']): ?>
                        <a href="javascript:void(0)" style='cursor: default;' class="cd_link_con"> 
                    <?php else: ?>
                        <a href="<?= '/item/'.$item['slug'];?>" class="cd_link_con"> 
                    <?php endif; ?> 
                    <?php if(intval($item['is_promote']) === 1): ?> 
                  
                        <div class="product_buy_con">
                                <span><span class="orange_btn3 <?php echo $item['is_soldout']||(!$item['start_promo'])?'disabled':'enabled';?>">BUY NOW</span></span>
                        </div>
                      
                    <?php else: ?>
                         <div class="product_buy_con">
                                <span><span class="orange_btn3 <?php echo $item['is_soldout']?'disabled':'enabled';?>">BUY NOW</span></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($item['is_soldout']): ?>
                        <div class="cd_soldout">
                            <p>SOLDOUT</p>
                        </div>
                    <?php endif; ?>
                    <div>
                        <span class="cd_slide_discount">
                            <?php if(intval($item['is_promote']) === 1): ?> 
                                <span><?php echo ($item['start_promo'])?number_format($item['percentage'],0,'.',','):'2';?>%<br>OFF</span>
                            <?php else: ?>
                                <span><?php echo number_format($item['percentage'],0,'.',',');?>%<br>OFF</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <a href="<?= '/item/'.$item['slug'];?>">
                        <span class="cd_prod_img_con">
                            <img src="/<?= $item['path'].'categoryview/'.$item['file']; ?>">
                        </span>
                    </a>
                    <h3>
                        <a href="<?= '/item/'.$item['slug'];?>"><?php echo es_string_limit(html_escape($item['name']), 54, '...');?></a>
                    </h3>
                    <div class="price-cnt">
                        <?php if(intval($item['is_promote']) === 1): ?> 
                            <?php if(($item['start_promo'])):  ?>
                                <div class="price">PHP <?php echo number_format($item['price'],2,'.',',');?></div>
                                <div class="discount_price">PHP <?php echo number_format($item['original_price'],2,'.',',');?></div>
                            <?php else: ?>
                                <div class="price"><span style='font-size:11px; font-weight:bold;'>as low as </span>PHP <?php echo number_format($item['original_price']*(1-0.99),2,'.',',');?>*</div>
                                <div class="discount_price">PHP <?php echo number_format($item['original_price'],2,'.',',');?></div>
                                <div></div>
                           <?php endif;  ?>
                        <?php else: ?>
                            <div class="price">PHP <?php echo number_format($item['price'],2,'.',',');?></div>
                            <div class="discount_price">PHP <?php echo number_format($item['original_price'],2,'.',',');?></div>
                        <?php endif; ?>
   
                    </div>
                    <div class="cd_condition">
                        Condition: <?=strtoupper(html_escape($item['condition']));?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>