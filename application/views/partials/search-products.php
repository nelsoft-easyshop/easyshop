
<div class="group-container row" data-id="<?=$currentPage?>" id="page-<?=$currentPage?>">
    <?php foreach ($products as $value): ?>
    <?php
        $productEntity = $value;
        $productId = $productEntity->getIdProduct();
        $productName = html_escape($productEntity->getName());
        $productSlug = $productEntity->getSlug();
        $productPrice = number_format($productEntity->getFinalPrice(), 2,'.',',');
        $productCondition = html_escape($productEntity->getCondition());
        $originalPrice = number_format($productEntity->getOriginalPrice(),2,'.',',');
        $percentage = $productEntity->getDiscountPercentage(); 
        $isProductNew = $productEntity->getIsNew();
        $isFreeShipping = $productEntity->getIsFreeShipping();
        $briefDescription = html_escape($productEntity->getBrief());
        $productImagePath = getAssetsDomain().$productEntity->directory .'categoryview/'. $productEntity->imageFileName;
        $hasSecondImage = $productEntity->hasSecondaryImage;
        $secondaryImage = getAssetsDomain().$productEntity->secondaryImageDirectory .'categoryview/'. $productEntity->secondaryImageFileName;
        $memberEntity = $value->getMember();
        $ownerAvatar = getAssetsDomain().'.'.$productEntity->ownerAvatar;
        $sellerStoreName = html_escape($memberEntity->getStoreName());
        $sellerSlug = html_escape($memberEntity->getSlug());
    ?>
    <div class="col-search-item <?=$isListView ? 'col-xs-12' : 'col-sm-3 col-xs-6' ; ?>">
        <div class="search-item-container">
            <a href="/item/<?=$productSlug;?>" class="search-item-link-image">
                <div class="search-item-img-container">
                    <img src="<?=$productImagePath;?>" class="search-item-image-primary <?php if($hasSecondImage): ?> search-item-has-secondary <?php endif; ?>"  />

                    <?php if($hasSecondImage): ?>
                    <img src="<?=$secondaryImage;?>" class="search-item-image-secondary" />
                    <?php endif; ?>

                    
                </div>
                <?php if($percentage > 0):?>
                <span class="discount-circle-2"><?PHP echo number_format($percentage,0,'.',',');?>%</span>
                <?php endif;?>
                
                <?php if($isProductNew): ?>
                <span class="new-circle-2">NEW</span>
                <?php endif; ?>
            </a>
            <div class="search-item-meta">
                <a href="/item/<?=$productSlug;?>" class="search-item-name" <?php if(strlen($productName)>18): ?>rel="tooltiplist" data-toggle="tooltip" data-placement="top" title="<?php echo $productName; ?>"<?php endif;?>>
                    <?=$productName; ?>
                </a>
                <div class="search-item-price with-discount">
                    <?php if($percentage > 0):?>
                    <span class="original-price">
                        <s>P<?=$originalPrice; ?></s>
                    </span>
                    <?php endif; ?>
                    <span class="new-price">
                        P<?=$productPrice; ?>
                    </span>
                </div>
            </div>
            <div class="search-item-actions">
                <button data-slug="<?=$productSlug;?>" data-productid="<?=$productId;?>" class="btn btn-search-add-cart btn-add-to-cart">
                    <span class="fa icon-cart fa-lg"></span>
                    Add to cart
                </button>
                <div class="search-item-seller-cont pull-right">
                    <a href="/<?=$sellerSlug;?>" >
                        <img src="<?=$ownerAvatar;?>" class="search-item-seller-img" />
                    </a>
                </div>
            </div>
            <table class="search-item-list-table">
                <tbody>
                    <tr>
                        <td class="search-item-td-image">
                            <a href="/item/<?=$productSlug;?>">
                                <div class="search-item-img-container">
                                    <img src="<?=$productImagePath;?>" class="search-item-image-primary <?php if($hasSecondImage): ?> search-item-has-secondary <?php endif; ?>"  />

                                    <?php if($hasSecondImage): ?>
                                    <img src="<?=$secondaryImage;?>" class="search-item-image-secondary" />
                                    <?php endif; ?>
                                    <?php if($percentage > 0):?>
                                    <span class="discount-circle-2"><?PHP echo number_format($percentage,0,'.',',');?>%</span>
                                    <?php endif;?>
                                    
                                    <?php if($isProductNew): ?>
                                    <span class="new-circle-2">NEW</span>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </td>
                        <td class="search-item-td-meta">
                            <div class="search-meta-hand">
                                <a href="/item/<?=$productSlug;?>" class="search-item-name">
                                    <?=$productName; ?>
                                </a>
                                <span class="search-item-description">
                                    <?=$briefDescription; ?>
                                </span>
                                <div class="divider-gray"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="search-item-seller-img-list">
                                            <div class="search-item-seller-cont">
                                                <a href="/<?=$sellerSlug;?>">
                                                    <img src="<?=$ownerAvatar; ?>" class="search-item-seller-img" />
                                                </a>
                                            </div>
                                        </div>
                                        <a href="/<?=$sellerSlug;?>" class="search-item-seller-name">
                                            <?=$sellerStoreName; ?>
                                        </a>
                                    </div>

                                    
                                    <div class="col-md-6">
                                        <span class="search-item-shipping-text pull-right">
                                            <?php if($isFreeShipping): ?>
                                                <span class="search-item-shipping-label">Shipping : </span>
                                                <span class="search-item-shipping-data">Free</span>
                                            <?php endif; ?>
                                        </span>
                                        <div class="clear"></div>
                                        <div class="search-price-list-mobile">
                                            <div class="col-xs-6 search-item-price">
                                                <?php if($percentage > 0):?>
                                                <span class="original-price">
                                                    <s>P<?=$originalPrice; ?></s>
                                                </span>
                                                <?php endif; ?> 
                                                 
                                                <span class="new-price with-discount-list">
                                                    P<?=$productPrice; ?>
                                                </span>
                                            </div>
                                            <div class="col-xs-6 search-list-cart-button">
                                                <button data-slug="<?=$productSlug;?>" data-productid="<?=$productId;?>" class="btn btn-search-add-cart btn-add-to-cart">
                                                    <span class="fa icon-cart fa-lg"></span>
                                                    Add to cart
                                                </button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                   
                                </div>
                            </div>
                        </td>
                        <?php $priceFontStyle = strlen((string)$originalPrice) > 11 ? "font-size:12px !important;" : ""; ?>
                        <?php $pricePaddingTop = strlen((string)$originalPrice) > 11 ? "margin-top:37px;" : ""; ?>
                        <td class="search-item-td-price">
                            <div class="search-item-price">
                                <div style="<?php echo $pricePaddingTop; ?>">
                                <?php if($percentage > 0):?>
                                
                                    <span class="original-price" style="<?php echo $priceFontStyle; ?>">
                                        <s>P<?=$originalPrice; ?></s>
                                    </span>
                              
                                <?php endif; ?> 
                                
                                <span class="new-price with-discount-list" style="<?php echo $priceFontStyle; ?>">
                                    P <?=$productPrice?>
                                 </span>
                                  
                                 
                                </div>
                                <?php if($isFreeShipping): ?>
                                     <span class="free-shipping-tag">
                                        <i class="fa fa-truck fa-lg"></i> FREE 
                                        SHIPPING
                                        <span class="free-shipping-tag-tail"></span>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <button data-slug="<?=$productSlug;?>" data-productid="<?=$productId;?>" class="btn btn-search-add-cart btn-add-to-cart">
                                <span class="fa icon-cart fa-lg"></span>
                                Add to cart
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php endforeach; ?>

</div>

