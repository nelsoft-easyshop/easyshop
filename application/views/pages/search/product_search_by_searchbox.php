
<meta name="viewport" content="width=device-width, maximum-scale=1.0">
<link rel="stylesheet" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/bootstrap-mods.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/>
<link rel="stylesheet" href="/assets/css/product_search_category_responsive.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="/assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<div class="clear"></div>
    <section style="color-gray display-when-desktop">
        <div class="container container-responsive">
            <div class="row">
                <div class="col-md-12">
                    <section class="top_margin product-page-section display-when-desktop">
                        <div class="wrapper">
                            <?php echo $category_navigation_desktop; ?>  
                            <div class="prob_cat_nav">
                                <div class="category_nav product_content">
                                    <ul>
                                    </ul>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="bread_crumbs"></div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row display-when-desktop">
                <div class="col-md-2 row-main">
                    <div class="wrapper" id="main_search_container">
                        <div class="left_attribute">
                            <?php if($productCount > 0): ?>
                                <h3>Price</h3>
                                <input type="text" id="price1" class="priceField" value="<?=isset($getParameter['startprice']) ? $getParameter['startprice'] : '' ?>" maxlength=9 size=6>
                                to
                                <input type="text" id="price2" class="priceField" value="<?=isset($getParameter['endprice']) ? $getParameter['endprice'] : '' ?>" maxlength=9 size=6> 
                                <input class="price" type="button" value=">>"/>

                                <?php foreach ($attributes as $attrName => $attrListValue):?>
                                <h3><?= html_escape($attrName); ?></h3>
                                    <ul>
                                    <?php foreach ($attrListValue as $value):?>
                                        <li style="border:0px">
                                            <a class="cbx" data-head="<?= html_escape(strtolower($attrName))?>" data-value="<?= html_escape(strtolower($value)); ?>" >
                                                <input type="checkbox" <?=(isset($getParameter[strtolower($attrName)]) && strpos($getParameter[strtolower($attrName)],strtolower($value)) !== false)?'checked':'';?> class="checkBox" data-head="<?= html_escape(strtolower($attrName));?>" data-value="<?= html_escape(strtolower($value)); ?>" >
                                                <label for="cbx"><?= html_escape(ucfirst($value));?></label>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                    </ul>
                                <?php endforeach; ?>
                                <p class="more_attr">More Filters</p>
                                <p class="less_attr">Less Filters</p>
                            <?php endif; ?>
                        </div>
                     
                        <div class="right_product">
                            <?php if($productCount <= 0): ?>
                                <div style='margin-bottom: 100px;'>
                                    <span style='font-size:15px;'> Your search for <span style='font-weight:bold'><?php echo html_escape($string);?></span> did not return any results. </span>
                                </div>
                            <?php else: ?>
                                <div class="adv_ctr">
                                    <strong style="font-size:14px"><?=$productCount ;?></strong> result<?=(number_format($productCount) > 1)?"s":"";?> found for <strong><?php echo html_escape($string);?></strong>
                                </div>
                                <div id="list" class="list <?=(isset($_COOKIE['view']) && $_COOKIE['view'] == "product-list")?"list-active":"";?>"></div>
                                <div id="grid" class="grid <?=(isset($_COOKIE['view']) && $_COOKIE['view'] == "product-list")?"":"grid-active";?> "></div>
                                <div class="clear"></div>

                                <div id="product_content">
                                    <?php 
                                    foreach ($products as $value):
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

                                        $typeOfView = "product";
                                        if(isset($_COOKIE['view'])){ 
                                            $typeOfView = ($_COOKIE['view'] == "product-list") ? "product-list" : "product";
                                        }
                                    ?> 
                                        <div class="<?php echo $typeOfView; ?>"> 
                                            <a href="/<?php echo "item/" . $productSlug; ?>">
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
                                              
                                                <?php if(floatval($percentage) > 0):?>
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
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="loading_products" style="display: inline-block;text-align: center;width: 100%;"></div>
            </div>
        </div>
    </section>

    <div class="display-when-mobile-1024">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?=$category_navigation_mobile;?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php if($productCount > 0): ?>
                    <div class="search_result_m">
                        <p class="search_result "><strong style="font-size:14px"><?=$productCount;?></strong> result<?=($productCount > 1)?"s":"";?> found for <strong><?php echo html_escape($string);?></strong></p>
                    </div>
                    <?php endif; ?>
                    <br/>
                    <div id="paste-product">
                        <?php if($productCount > 0): ?>
                            <?php foreach ($products as $value): ?>
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
                        <?php else: ?>
                            <div>
                                <span style='font-size:15px;'> Your search for <span style='font-weight:bold'><?php echo html_escape($string);?></span> did not return any results. </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="loading_products" style="display: inline-block;text-align: center;width: 100%;"></div>
                </div>
            </div>
        </div>

        <div class="div-button-fixed navbar-fixed-bottom display-when-mobile-1024">
            <table width="100%" style="margin-top: 20px;">
                <tr>
                    <td width="100%" class="td-filter_solo button-bottom">
                        <a href="#" data-toggle="modal" data-target="#filter" class="btn-sub-cat">
                            <p width="100%" class="p-link-category"><i class="glyphicon glyphicon-filter"></i> Filter
                            </p>
                        </a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="modal fade" id="filter" tabindex="-1" role="dialog" aria-labelledby="Filter" aria-hidden="true">
        <div class="modal-dialog no-border border-0">
            <div class="modal-content no-border">
                <div class="modal-header bg-orange">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="color-white">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title color-white" id="Filter">
                        <i class="glyphicon glyphicon-filter"></i> Filter
                    </h4>
                </div>
                <div class="modal-body no-border">
                    <h3 class="h3-filter-price">Price</h3>
                    <input type="text" id="rprice1" class="priceField" value="<?=isset($getParameter['startprice']) ? $getParameter['startprice'] : '' ?>" maxlength=9 size=6>
                    to
                    <input type="text" id="rprice2" class="priceField" value="<?=isset($getParameter['endprice']) ? $getParameter['endprice'] : '' ?>" maxlength=9 size=6> 
                    <input class="rprice" type="button" value=">>"/>
                    <?php if($productCount > 0): ?>
                        <?php foreach ($attributes as $attrName => $attrListValue):?>
                         <h3 class="title h3-filter"><?=html_escape($attrName); ?></h3> 
                        <ul class="list-unstyled"> 
                            <?php foreach ($attrListValue as $value):?>
                            <li>
                                <a class="cbx" data-head="<?= html_escape(strtolower($attrName));?>" data-value="<?= html_escape(strtolower($value)) ?>" >
                                    <input type="checkbox" <?=(isset($getParameter[strtolower($attrName)]) && strpos($getParameter[strtolower($attrName)],strtolower($value)) !== false)?'checked':'';?> class="checkBox" data-head="<?= html_escape(strtolower($attrName)); ?>" data-value="<?= html_escape(strtolower($value)); ?>" >
                                    <label class="cbx-label" for="cbx"><?= html_escape(ucfirst($value));?></label>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                            <div class="clear"></div> 
                        <?php endforeach; ?>
                    <?php else: ?>
                        <h3>No available filter</h3>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


<div id="hidden-elements">
    <input type="hidden" id="hidden-currentUrl" value="<?=site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']); ?>" />
    <input type="hidden" id="hidden-typeView" value="<?=(isset($_COOKIE['view']))?$_COOKIE['view']:'product'?>" />
    <input type="hidden" id="hidden-emptySearch" value="<?=(isset($products))?"false":"";?>" />
    <input type="hidden" id="hidden-loadUrl" value="/search/more?<?=$_SERVER['QUERY_STRING']; ?>" />
</div> 

<script src="/assets/js/src/vendor/bootstrap.js" type="text/javascript"></script>
<script src="/assets/js/src/advsearch.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.easing.min.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.scrollUp.min.js" type="text/javascript"></script>
