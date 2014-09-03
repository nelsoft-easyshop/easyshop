<?php 
foreach ($products as $key => $value):
 
?> 
    <div class="<?=$typeview?>"> 
        <a href="<?php echo base_url() . "item/" . $value['slug']; ?>">
            <span class="prod_img_wrapper">
                <?php if((intval($value['isPromote']) == 1) && isset($value['percentage']) && $value['percentage'] > 0):?>
                    <span class="cd_slide_discount">
                        <span><?php echo number_format($value['percentage'],0,'.',',');?>%<br>OFF</span>
                    </span>
                <?php endif; ?>
            
                <span class="prod_img_container">
                        <img alt="<?php echo html_escape($value['name']); ?>" src="<?php echo base_url() . $value['productImagePath']; ?>">
                </span>
            </span>
        </a>
        <h3>
            <a href="<?php echo base_url() . "item/" . $value['slug']; ?>">
                <?php echo html_escape($value['name']); ?>
            </a>
        </h3>
        <div class="price-cnt">
            <div class="price"> 
                <span>&#8369;</span> <?php echo number_format($value['price'], 2);?>
            </div>
          
            <?php if(isset($value['percentage']) && $value['percentage'] > 0):?>
            <div>
                <span class="original_price">
                    &#8369; <?php echo number_format($value['originalPrice'],2,'.',','); ?>
                </span>
                <span style="height: 20px;">
                    |&nbsp; <strong><?PHP echo number_format($value['percentage'],0,'.',',');?>%OFF</strong>
                </span>
            </div>
            <?php endif; ?>
        </div>
       <div class="product_info_bottom">
            <div>
                Condition:
                <strong>
                   <?php echo ($value['isFreeShipping'])? es_string_limit(html_escape($value['condition']),15) : html_escape($value['condition']);?>
                </strong>
            </div>
            <?php if($value['isFreeShipping'] <= 0): ?>
                <span style="float:right;"><span class="span_bg img_free_shipping"></span></span>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>