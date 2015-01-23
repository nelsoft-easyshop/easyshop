
<?php 
foreach ($products as $key => $value):
    $productEntity = $value;
    $productName = html_escape($productEntity->getName());
    $productSlug = $productEntity->getSlug();
    $productPrice = number_format($productEntity->getFinalPrice(), 2,'.',',');
    $productCondition = html_escape($productEntity->getCondition());
    $originalPrice = number_format($productEntity->getOriginalPrice(),2,'.',',');
    $percentage = $productEntity->getDiscountPercentage();
    $isPromote = intval($productEntity->getIsPromote());
    $isFreeShipping = $productEntity->getIsFreeShipping(); 
    $productImagePath = $productEntity->directory .'categoryview/'. $productEntity->imageFileName;
?> 
    <div class="<?php echo $typeOfView; ?>"> 
        <a href="<?php echo "/item/" . $productSlug; ?>">
            <span class="prod_img_wrapper">
                <?php if(floatval($percentage) > 0):?>
                <div>
                    <span class="cd_slide_discount">
                        <span><?php echo number_format($percentage,0,'.',',');?>%<br>OFF</span>
                    </span>
                </div>
                <?php endif; ?>
            
                <span class="prod_img_container">
                        <img alt="<?php echo $productName; ?>" src="<?php echo getAssetsDomain() ?><?php echo $productImagePath; ?>">
                </span>
            </span>
        </a>
        <h3>
            <a href="/<?php echo "item/" . $productSlug; ?>">
                <?php echo $productName; ?>
            </a>
        </h3>
        <div class="price-cnt">
            <div class="price"> 
                <span>&#8369;</span> <?php echo $productPrice;?>
            </div>
          
            <?php if($percentage > 0):?>
            <div>
                <span class="original_price">
                    &#8369; <?php echo $originalPrice; ?>
                </span>
                <span style="height: 20px;">
                    |&nbsp; <strong><?PHP echo number_format($percentage,0,'.',',');?>%OFF</strong>
                </span>
            </div>
            <?php endif; ?>
        </div>
       <div class="product_info_bottom">
            <div>
                Condition:
                <strong>
                   <?php echo ($isFreeShipping)? es_string_limit($productCondition,15) : $productCondition;?>
                </strong>
            </div>
            <?php if($isFreeShipping): ?>
                <span style="float:right;"><span class="span_bg img_free_shipping"></span></span>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

<div id="move-product">
    <?php foreach ($products as $key => $value): ?>
    <?php
        $productEntity = $value;
        $productName = html_escape($productEntity->getName());
        $productSlug = $productEntity->getSlug();
        $productPrice = number_format($productEntity->getFinalPrice(), 2,'.',',');
        $productCondition = html_escape($productEntity->getCondition());
        $originalPrice = number_format($productEntity->getOriginalPrice(),2,'.',',');
        $percentage = $productEntity->getDiscountPercentage();
        $isPromote = intval($productEntity->getIsPromote());
        $isFreeShipping = $productEntity->getIsFreeShipping();
        $productImagePath = $productEntity->directory .'categoryview/'. $productEntity->imageFileName;
    ?>
    <h3></h3>
    <div class="responsive-product panel panel-default no-border panel-items">
        <table width="100%" class="">
            <tr>
                <td width="90px" class="v-align-top">
                    <span class="prod_img_container">
                         <a class="a-item-name" href="/<?php echo "item/" . $productSlug; ?>"> 
                            <img alt="<?php echo $productName; ?>" src="<?php echo getAssetsDomain() ?><?php echo $productImagePath; ?>">
                        </a>
                    </span>
                </td>
                <td class="v-align-top">
                    <p class="p-item-name"> 
                        <a class="a-item-name" href="/<?php echo "item/" . $productSlug; ?>"> 
                            <?=(strlen($productName)>35)?substr_replace($productName, "...", 35):$productName;?>
                        </a>
                    </p>
                    <p class="p-item-price"> 
                        PHP <?php echo $productPrice;?>
                    </p>
                    <?php if($percentage > 0):?>
                        <p class="p-item-discount">
                            <span class="original_price">
                                &#8369; <?php echo $originalPrice; ?>
                            </span>
                            <span style="height: 20px;">
                                |&nbsp; <strong><?PHP echo $percentage;?>%OFF</strong>
                            </span>
                        </p>
                    <?php endif; ?>
                        
                    <p class="p-item-condition">
                        Condition:
                        <strong>
                           <?php echo ($isFreeShipping)? es_string_limit($productCondition,15) : $productCondition;?>
                        </strong>
                    </p> 
                </td>
                <td width="30px" class=" v-align-top">
                    <?php if($isFreeShipping): ?>
                        <span style="float:right;"><span class="span_bg img_free_shipping"></span></span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
    <?php endforeach; ?>
</div>