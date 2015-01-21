
<?php foreach ($products as $value): ?>
<?php
    $productEntity = $value;
    $productName = html_escape($productEntity->getName());
    $productSlug = $productEntity->getSlug();
    $productPrice = number_format($productEntity->getFinalPrice(), 2,'.',',');
    $productCondition = html_escape($productEntity->getCondition());
    $originalPrice = number_format($productEntity->getOriginalPrice(),2,'.',',');
    $percentage = $productEntity->getDiscountPercentage(); 
    $isProductNew = $productEntity->getIsNew();
    $isFreeShipping = $productEntity->getIsFreeShipping();
    $briefDescription = html_escape($productEntity->getBrief());
    $productImagePath = $productEntity->directory .'categoryview/'. $productEntity->imageFileName;
    $hasSecondImage = $productEntity->hasSecondaryImage;
    $secondaryImage = $productEntity->secondaryImageDirectory .'categoryview/'. $productEntity->secondaryImageFileName;
    $memberEntity = $value->getMember();
    $ownerAvatar = $productEntity->ownerAvatar;
    $sellerStoreName = html_escape($memberEntity->getStoreName());
    $sellerSlug = html_escape($memberEntity->getSlug());
?>
<div class="col-search-item col-xs-3">
    <div class="search-item-container">
        <a href="/item/<?=$productSlug;?>" class="search-item-link-image">
            <div class="search-item-img-container" style="background: #fff url(/<?=$productImagePath;?>) center no-repeat; background-size: cover;">
                <?php if($hasSecondImage): ?>
                <div class="search-item-img-container-hover" style="background: #fff url(/<?=$secondaryImage;?>) center no-repeat; background-size: cover;">
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
            <div class="search-item-price">
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
            <button class="btn btn-search-add-cart">
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
                    <td>
                        <a href="#">
                            <div class="search-item-img-container" style="background: #fff url(/<?=$productImagePath;?>) center no-repeat; background-size: cover;">
                                <?php if($hasSecondImage): ?>
                                <div class="search-item-img-container-hover" style="background: #fff url(/<?=$secondaryImage;?>)) center no-repeat; background-size: cover;">
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
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac rutrum augue, at pellentesque est. Proin ullamcorper laoreet dolor. Vestibulum quis placerat enim.
                        </span>
                        <div class="divider-gray"></div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="search-item-seller-img-list">
                                    <div class="search-item-seller-cont">
                                        <img src="<?=$ownerAvatar; ?>" class="search-item-seller-img" />
                                    </div>
                                </div>
                                <a href="/<?=$sellerSlug;?>" class="search-item-seller-name">
                                    <?=$sellerStoreName; ?>
                                </a>
                            </div>

                            <?php if($isFreeShipping): ?>
                            <div class="col-xs-6">
                                <span class="search-item-shipping-text pull-right">
                                    <span class="search-item-shipping-label">Shipping : </span>
                                    <span class="search-item-shipping-data">Free</span>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="search-item-td-price">
                        <div class="search-item-price">
                            <?php if($percentage > 0):?>
                            <span class="original-price">
                                <s>P<?=$originalPrice; ?></s>
                            </span>
                            <?php endif; ?> 
                            <span class="new-price">
                                P<?=$productPrice; ?>
                            </span>
                        </div>
                        <button class="btn btn-search-add-cart">
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


