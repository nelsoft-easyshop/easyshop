
<div class="row" id="page-<?=$currentPage?>">
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
                <div class="search-item-img-container" style="background: #fff url(<?=$productImagePath;?>) center no-repeat; background-size: cover;">
                    <?php if($hasSecondImage): ?>
                    <div class="search-item-img-container-hover" style="background: #fff url(<?=$secondaryImage;?>) center no-repeat; background-size: cover;">
                    </div>
                    <?php endif; ?>
                    
                    <?php if($percentage > 0):?>
                    <span class="discount-circle-2"><?=$percentage; ?>%</span>
                    <?php endif;?>
                    
                    <?php if($isProductNew): ?>
                    <span class="new-circle-2">NEW</span>
                    <?php endif; ?>
                </div>
            </a>
            <div class="search-item-meta">
                <a href="/item/<?=$productSlug;?>" class="search-item-name">
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
                    <img src="<?=$ownerAvatar;?>" class="search-item-seller-img" />
                </div>
            </div>
            <table class="search-item-list-table">
                <tbody>
                    <tr>
                        <td class="search-item-td-image">
                            <a href="/item/<?=$productSlug;?>">
                                <div class="search-item-img-container" style="background: #fff url(<?=$productImagePath;?>) center no-repeat; background-size: cover;">
                                    <?php if($hasSecondImage): ?>
                                    <div class="search-item-img-container-hover" style="background: #fff url(<?=$secondaryImage;?>)) center no-repeat; background-size: cover;">
                                    </div>
                                    <?php endif;?>
                                    
                                    <?php if($percentage > 0):?>
                                    <span class="discount-circle-2"><?=$percentage; ?>%</span>
                                    <?php endif;?>
                                    
                                    <?php if($isProductNew): ?>
                                    <span class="new-circle-2">NEW</span>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </td>
                        <td class="search-item-td-meta">
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
                                            <img src="<?=$ownerAvatar; ?>" class="search-item-seller-img" />
                                        </div>
                                    </div>
                                    <a href="/<?=$sellerSlug;?>" class="search-item-seller-name">
                                        <?=$sellerStoreName; ?>
                                    </a>
                                </div>

                                
                                <div class="col-md-6">
                                    <?php if($isFreeShipping): ?>
                                    <span class="search-item-shipping-text pull-right">
                                        <span class="search-item-shipping-label">Shipping : </span>
                                        <span class="search-item-shipping-data">Free</span>
                                    </span>
                                    <?php endif; ?>
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
                                        <div class="col-xs-6 search-list-cart-button btn-add-cart">
                                            <button data-slug="<?=$productSlug;?>" data-productid="<?=$productId;?>" class="btn btn-search-add-cart btn-add-to-cart">
                                                <span class="fa icon-cart fa-lg"></span>
                                                Add to cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                        </td>
                        <td class="search-item-td-price">
                            <div class="search-item-price">
                                <?php if($percentage > 0):?>
                                <span class="original-price">
                                    <s>P<?=$originalPrice; ?></s>
                                </span>
                                <?php endif; ?> 
                                <span class="new-price with-discount-list">
                                    P<?=$productPrice; ?>
                                </span>
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

