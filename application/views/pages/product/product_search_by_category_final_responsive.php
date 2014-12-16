<meta name="viewport" content="width=device-width, maximum-scale=1.0">
<link rel="stylesheet" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/bootstrap-mods.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="/assets/css/product_search_category_responsive.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="/assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>

<link rel="stylesheet" href="/assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/> 
<link type="text/css" href="/assets/css/jcarousel.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" media="all"/>

<section style="color-gray display-when-desktop">
    <div class="container container-responsive">
        <div class="row">
            <div class="col-md-12">
                <section class="top_margin product-page-section display-when-desktop">
                    <div class="wrapper">
                        <?php echo $category_navigation_desktop; ?>  
                        <div class="prod_cat_nav" id="prod_drop_nav">
                            <div id="cat_nav"class="category_nav">
                                <ul>
                                <?php foreach($parentCategory as $catValue): ?>
                                    <li class="<?=($catValue->getIdCat() == $breadcrumbs[0]['idCat'])?'active':'';?>">
                                        <a href="/category/<?=$catValue->getSlug();?>">
                                            <?=html_escape($catValue->getName());?>
                                        </a>
                                    </li>
                                <?php endforeach;?>
                                </ul>
                                <span id="cat" class="span_bg prod_cat_drop2"></span>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="bread_crumbs">
                            <ul>
                                <li class=""><a href="/home">Home</a></li>
                                <?php foreach ($breadcrumbs as $crumbs): ?>
                                <li>
                                    <a href="/category/<?php echo $crumbs['slug'] ?>">
                                        <?php echo html_escape($crumbs['name']); ?>
                                    </a>
                                </li> 
                                <?php endforeach; ?>
                            </ul> 
                        </div>
                    </div>
                    <br/>
                </section>
            </div>
        </div>
        <div class="row display-when-desktop">
            <div class="col-md-2 row-main row-left">
                <div class="left_attribute"> 
                    <h3>Price</h3>
                    <input type="text" id="price1" class="priceField" value="<?=isset($getParameter['startprice']) ? $getParameter['startprice'] : '' ?>" maxlength=9 size=6>
                    to
                    <input type="text" id="price2" class="priceField" value="<?=isset($getParameter['endprice']) ? $getParameter['endprice'] : '' ?>" maxlength=9 size=6> 
                    <input class="price" type="button" value=">>"/>

                    <?php foreach ($attributes as $attrName => $attrListValue):?>
                    <h3><?=html_escape($attrName)?></h3>
                        <ul>
                        <?php foreach ($attrListValue as $value):?>
                            <li style="border:0px">
                                <a class="cbx" data-head="<?=strtolower(html_escape($attrName))?>" data-value="<?=strtolower(html_escape($value))?>" >
                                    <input type="checkbox" <?=(isset($getParameter[strtolower($attrName)]) && strpos($getParameter[strtolower($attrName)],strtolower($value)) !== false)?'checked':'';?> class="checkBox" data-head="<?=strtolower($attrName)?>" data-value="<?=strtolower($value)?>" >
                                    <label for="cbx"><?=ucfirst(html_escape($value));?></label>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php endforeach; ?>
                    <p class="more_attr">More Filters</p>
                    <p class="less_attr">Less Filters</p> 
                </div>
            </div>
            <div class="col-md-10 row-main row-right" style="border: transparent #fff 1px; padding: 0px !important;">
                <?php if(count($subCategoryList) !== 0):?>
                    <div class="filters">           
                        <h2 class="margin-0">Categories:</h2>    
                        <div class="jcarousel category_carousel cc2_wrapper">
                            <div class="cc2">
                                <?php foreach ($subCategoryList as $subCatKey => $subCatValue):
                                    if(!empty($subCatValue['item'])){
                                        $productEntity = $subCatValue['item'];
                                        $popularProductName = html_escape($productEntity->getName());
                                        $popularProductSlug = html_escape($productEntity->getSlug());
                                        $popularProductImage = $productEntity->directory .'categoryview/'. $productEntity->imageFileName;
                                        $popularProductPrice = number_format($productEntity->getFinalPrice(),2,'.',',');
                                    }
                                ?>
                                <div>
                                    <a class="cc2_title color-gray" href="/category/<?=$subCatValue['slug'];?>">
                                        <span><?php echo html_escape($subCatKey);?></span>
                                    </a>
                                    <?php if(!empty($subCatValue['item'])): ?>
                                    <span class="cat_carousel_img_con"><span class="cat_carousel_img"><img src="/<?=$popularProductImage; ?>"></span></span><br />
                                    <div class="cc2_prod_name">
                                        <a href="/item/<?=$popularProductSlug; ?>" title="<?PHP echo $popularProductName; ?>">
                                            <span class="color-gray font-12">
                                            <?php echo $popularProductName; ?>
                                            </span>
                                        </a>
                                    </div>
                                    <span class="recommended_product_price">PHP <?php echo $popularProductPrice;?></span>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach;?>
                            </div>  
                        </div>
                        <a href="#" class="jcarousel-control-prev inactive category_carousel2_prev text-decoration-none">&lsaquo;</a>
                        <a href="#" class="jcarousel-control-next inactive category_carousel2_next text-decoration-none">&rsaquo;</a>            
                    </div>
                <?php endif; ?>
                <div class="clear"></div>
                <p class="search_result margin-left-42"> </p>
                <div class="pull-right div-view-buttons">
                    <div id="list" class="list <?=(isset($_COOKIE['view']) && $_COOKIE['view'] == "product-list")?"list-active":"";?>"></div>
                    <div id="grid" class="grid <?=(isset($_COOKIE['view']) && $_COOKIE['view'] == "product-list")?"":"grid-active";?> "></div>
                </div>
                <div class="clear"></div>
                <div id="product_content" class="margin-left-42">
                    <?php if(count($products) > 0): ?>
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
                            $isFreeShipping = ($productEntity->getIsFreeShipping())?TRUE:FALSE;
                            $productImagePath = $productEntity->directory .'categoryview/'. $productEntity->imageFileName;
                                        
                            $typeOfView = "product";
                            if(isset($_COOKIE['view'])){ 
                                $typeOfView = ($_COOKIE['view'] == "product-list") ? "product-list" : "product";
                            }
                        ?> 
                            <div class="<?php echo $typeOfView; ?>"> 
                                <a href="/item/<?=$productSlug; ?>">
                                    <span class="prod_img_wrapper">
                                        <?php if(floatval($percentage) > 0):?>
                                        <div>
                                            <span class="cd_slide_discount">
                                                <span><?php echo number_format($percentage,0,'.',',');?>%<br>OFF</span>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    
                                        <span class="prod_img_container">
                                                <img alt="<?php echo $productName; ?>" src="/<?=$productImagePath; ?>">
                                        </span>
                                    </span>
                                </a>
                                <h3>
                                    <a href="/item/<?=$productSlug; ?>">
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
                    <?php else: ?> 
                        <br />
                        <div style="text-align:center;font-weight:bold">
                            <span style='font-size:15px;'> No available products to display in this category </span>
                        </div>
                    <?php endif;?>
                </div> 
                 <div class="loading_products" style="display: inline-block;text-align: center;width: 100%;"></div>
            </div>
        </div>
    </div>
</section>
<div class="display-when-mobile-1024">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?=$category_navigation_mobile;?>
                <div class="bread_crumbs_m">
                    <ul style="margin-bottom: 10px;">
                        <li class="li_home" ><a href="/home" style="color: #f18200;">Home</a></li>
                        <?php foreach($breadcrumbs as $crumbs): ?>
                        <li> <a href="/category/<?php echo $crumbs['slug']?>" style="color: #f18200;"> <?php echo html_escape($crumbs['name']);?> </a> </li>
                        <?php endforeach;?>
                        
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="search_result_m">
                   <p class="search_result "> </p>
                </div>
                <div id="paste-product">
                    <?php if(count($products) > 0): ?>
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
                            $isFreeShipping = ($productEntity->getIsFreeShipping())?TRUE:FALSE;
                            $productImagePath = $productEntity->directory .'categoryview/'. $productEntity->imageFileName;
                        ?>
                        <h3></h3>
                        <div class="responsive-product panel panel-default no-border panel-items">
                            <table width="100%" class="">
                                <tr>
                                    <td width="90px" class="v-align-top">
                                        <span class="prod_img_container">
                                             <a class="a-item-name" href="/item/<?=$productSlug; ?>"> 
                                                <img alt="<?php echo $productName; ?>" src="/<?=$productImagePath; ?>">
                                            </a>
                                        </span>
                                    </td>
                                    <td class="v-align-top">
                                        <p class="p-item-name"> 
                                            <a class="a-item-name" href="/item/<?=$productSlug; ?>"> 
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
                        <br />
                        <div style="text-align:center;font-weight:bold">
                            <span style='font-size:15px;'> No available products to display in this category </span>
                        </div>
                    <?php endif;?>
                </div> 
                <div class="loading_products" style="display: inline-block;text-align: center;width: 100%;"></div>
            </div>
        </div>
    </div>
</div>
<div class="div-button-fixed navbar-fixed-bottom display-when-mobile-1024">
    <?php if(count($subCategoryList) !== 0):?>
    <table width="100%" style="margin-top: 20px;">
        <tr>
            <td width="50%" class="td-sub-cat button-bottom">
                <a href="#" data-toggle="modal" data-target="#subcategories" class="btn-sub-cat"><p width="100%" class="p-link-category"><i class="glyphicon glyphicon-th-list"></i> Sub Categories</p></a>
            </td>
            
            <td width="50%" class="td-filter button-bottom">
                <a href="#" data-toggle="modal" data-target="#filter" class="btn-sub-cat"><p width="100%" class="p-link-category"><i class="glyphicon glyphicon-filter"></i> Filter</p></a>
            </td>
        </tr>
    </table>
    <?php else:?>
    <table width="100%" style="margin-top: 20px;">
        <tr>
            <td width="100%" class="td-filter_solo button-bottom">
                <a href="#" data-toggle="modal" data-target="#filter" class="btn-sub-cat"><p width="100%" class="p-link-category"><i class="glyphicon glyphicon-filter"></i> Filter</p></a>
            </td>
        </tr>
    </table>
    <?php endif;?>
</div> 

<?php if(count($subCategoryList) !== 0):?>
<div class="modal fade" id="subcategories" tabindex="-1" role="dialog" aria-labelledby="SubCategories" aria-hidden="true">
    <div class="modal-dialog no-border border-0">
        <div class="modal-content no-border">
            <div class="modal-header bg-orange">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" class="color-white">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title color-white" id="SubCategories"><i class="glyphicon glyphicon-th-list"></i> Sub Categories</h4>
            </div>
            <div class="modal-body no-border no-padding">
                <ul class="list-unstyled ul-sub">
                    <?php foreach ($subCategoryList as $subCatKey => $subCatValue): ?>
                        <a href="/category/<?=$subCatValue['slug']; ?>">
                          <li><?php echo html_escape($subCatKey); ?></li>
                        </a>
                    <?php endforeach;?>
                 </ul>
            </div> 
        </div>
    </div>
</div>
<?PHP endif;?>

<div class="modal fade" id="filter" tabindex="-1" role="dialog" aria-labelledby="Filter" aria-hidden="true">
    <div class="modal-dialog no-border border-0">
        <div class="modal-content no-border">
            <div class="modal-header bg-orange">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" class="color-white">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title color-white" id="Filter"><i class="glyphicon glyphicon-filter"></i> Filter</h4>
            </div>
            <div class="modal-body no-border">
                <h3 class="h3-filter-price">Price</h3>
                <input type="text" id="rprice1" class="priceField" placeholder="Min" value="<?=isset($getParameter['startprice']) ? $getParameter['startprice'] : '' ?>" maxlength=9 size=6>
                to
                <input type="text" id="rprice2" class="priceField" placeholder="Max" value="<?=isset($getParameter['endprice']) ? $getParameter['endprice'] : '' ?>" maxlength=9 size=6> 
                <input class="rprice" type="button" value=">>"/>

                <?php if(count($products) > 0): ?>
                    <?php foreach ($attributes as $attrName => $attrListValue):?>
                        <h3 class="title h3-filter"><?=html_escape($attrName)?></h3> 
                        <ul class="list-unstyled">
                            <?php foreach ($attrListValue as $value):?>
                                <li>
                                    <a class="cbx" data-head="<?=strtolower(html_escape($attrName))?>" data-value="<?=strtolower(html_escape($value))?>" >
                                        <input type="checkbox" <?=(isset($getParameter[strtolower($attrName)]) && strpos($getParameter[strtolower($attrName)],strtolower($value)) !== false)?'checked':'';?> class="checkBox" data-head="<?=strtolower(html_escape($attrName))?>" data-value="<?=strtolower(html_escape($value))?>" >
                                        <label class="cbx-label" for="cbx"><?=ucfirst(html_escape($value));?></label>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clear"></div> 
                    <?php endforeach; ?>  
                <?php endif; ?>
            </div>
        </div> 
    </div>
</div> 

<div id="hidden-elements">
    <input type="hidden" id="hidden-currentUrl" value="<?=site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']); ?>" />
    <input type="hidden" id="hidden-typeView" value="<?=(isset($_COOKIE['view']))?$_COOKIE['view']:'product'?>" />
    <input type="hidden" id="hidden-emptySearch" value="<?=(isset($products))?"false":"";?>" />
    <input type="hidden" id="hidden-loadUrl" value="/cat/more/<?=$categorySlug .'?'. $_SERVER['QUERY_STRING']; ?>" />
</div>

<script src="/assets/js/src/vendor/bootstrap.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.easing.min.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.scrollUp.min.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="/assets/js/src/categorynavigation.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script src="/assets/js/src/advsearch.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script type="text/javascript"> 

    (function($) {
        var p = $('.cc2_prod_name span');
        var divh = $('.cc2_prod_name').height();
        while ($(p).outerHeight()>divh) {
            $(p).text(function (index, text) {
                return text.replace(/\W*\s(\S)*$/, '...');
            });
        } 

        $(function() {
            $( ".prod_cat_drop2" ).click(function() {
              $( "#cat_nav" ).toggleClass("category_nav_plus");
              $( "#cat" ).toggleClass("active_prod_cat_drop_arrow");
            });
        });
           

        $(function() {
            $('.jcarousel').jcarousel();

            $('.jcarousel-control-prev')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=1'
            });

            $('.jcarousel-control-next')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=1'
            });

            $('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function() {
                $(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                $(this).removeClass('active');
            })
            .jcarouselPagination();
        });
    })(jQuery);
</script> 
