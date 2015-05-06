<div class="group-container group-seller row" data-id="<?=$currentPage?>" id="page-<?=$currentPage?>">
    <?php foreach ($sellers as $seller): ?>
        <div class="seller-panel-column col-md-4 col-sm-6 col-xs-12">
            <div class="seller-item-container">
                <table width="100%">
                    <tr>
                        <td width="80">
                            <div class="search-seller-img-container">
                                <img src="<?=getAssetsDomain().'.'.$seller->userImage;?>" />
                            </div>
                        </td>
                        <td valign="middle">
                            <div class="search-seller-info">
                                <a href="/<?=$seller->getSlug();?>" class="link"><?=html_escape($seller->getStoreName());?></a>
                                <div class="search-seller-products-container">
                                    <a href="#">
                                        <div class="search-seller-item-img-container">
                                            <img src="/assets/images/products/apple-p.jpg" />
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="search-seller-item-img-container">
                                            <img src="/assets/images/products/lg-p.jpg" />
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="search-seller-item-img-container">
                                            <img src="/assets/images/products/htc-p.jpg" />
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="search-seller-item-img-container">
                                            <img src="/assets/images/products/sony-p.jpg" />
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="search-seller-item-img-container">
                                            <img src="/assets/images/products/samsung-p.jpg" />
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    <?php endforeach;?>
</div>