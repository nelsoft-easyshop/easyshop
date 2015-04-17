<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE; Safari; Mozilla" />

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/vendorview.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
<?php else: ?>
    <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.vendorview.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
<?php endif; ?>

<div class="clear"></div>
<section class="bg-product-section color-default"><br>
<div class="container bg-product-section">
    <div class="row row-products">
        <div class="col-md-3 no-padding col-left-wing">
            <div class="left-wing">
                <div class="panel-group panel-category border-0" id="category">
                    <div class="panel panel-default  border-0 no-padding">
                        <div class="panel-heading border-0 panel-category-heading" id="cat-header">
                            <!-- here -->
                            <h4 class="panel-title">
                                <a id="toggle-cat" class="a-category" data-parent="#category">
                                    CATEGORIES<b class="cat fa fa-minus-square-o pull-right"></b>
                                </a>
                            </h4>
                        </div>

                        <div class="category-list panel-collapse collapse in">
                            <div class="panel-body border-0 no-padding">
                                <ul class="list-unstyled list-category">    
                                    <?php $isFirst = true; ?>
                                    <?php foreach( $categoryProducts as $categoryId => $categoryData ):?>
                                        <?php $categoryWrapper = $categoryData['category']; ?>
                                        <?php $isSearch = $categoryData['cat_type'] === \EasyShop\Category\CategoryManager::CATEGORY_SEARCH_TYPE; ?>
                                        <?php if($categoryWrapper->getIsHidden()): ?>
                                            <?php continue; ?>
                                        <?php endif; ?>
                                        <li>
                                            <a href="javascript: void(0)" data-link="#def-<?php echo $isSearch ? 'search' : $categoryWrapper->getId(); ?>" class="color-default tab_categories <?php echo $isFirst ? 'active' : ''; ?> ">
                                                <span class='catText'>
                                                    <?php echo html_escape($categoryWrapper->getCategoryName());?>
                                                </span> 
                                            <?php $children = $categoryWrapper->getChildren(); ?>
                                            <?php 
                                                /**
                                                 * If child has no products remove it from the children array
                                                 */
                                            ?>
                                            <?php foreach($children as $key => $child): ?>
                                                <?php if(!in_array((int)$child->getId(), array_keys($categoryProducts))): ?>
                                                    <?php unset($children[$key]); ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <?php if($categoryWrapper->getIsCustom() && empty($children) === false): ?>
                                                <i class="fa fa-caret-down fa-lg pull-right"></i>
                                            </a>
                                                <ul class="list-sub-category">
                                                <?php foreach($children as $child): ?>
                                                    <li>
                                                        <a href="javascript:void(0)" class="color-default tab_categories simplemodal-close" data-link="#def-<?php echo $child->getId(); ?>" >
                                                            <?php echo html_escape($child->getCategoryName()); ?> 
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                            </a>
                                        </li>
                                        <?php $isFirst = false; ?>
                                    <?php endforeach;?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group panel-category border-0" id="filter">
                    <div class="panel panel-default  border-0 no-padding" id="filter-header">
                        <div class="panel-heading border-0 panel-category-heading">
                            <h4 class="panel-title">
                                <a id="toggle-filter" class="a-filter" data-parent="#filter">
                                    FILTER PRODUCTS <b class="fil fa fa-minus-square-o pull-right"></b>
                                </a>
                            </h4>
                        </div>
                        <div id="filter-list1" class="panel-collapse collapse in">
                            <div class="panel-body border-0 no-padding">
                                <ul class="list-unstyled list-filter">
                                    <input type="hidden" id="hidden-currentUrl" value="<?=site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']); ?>" />
                                    <li>
                                        <p class="p-filter-name">By Condition</p>
                                        <select id="filter-condition" class="select-filter">
                                            <option value="">-- Select Condition --</option>
                                            <?php foreach($product_condition as $key=>$pc):?>
                                                <option value="<?php echo $key;?>"><?php echo $pc?></option>
                                            <?php endforeach;?>

                                        </select>
                                    </li>
                                    <li>
                                        <p class="p-filter-name">By Price</p>
                                        from <input id="filter-lprice" type="text" class="input-filter-price price-field" placeholder="0.00"/> to <input id="filter-uprice" type="text" class="input-filter-price price-field" placeholder="0.00"/>
                                    </li>
                                    <li>
                                        <center>
                                            <input class="btn-filter" id="filter-btn" type="button" value="filter"/>
                                        </center>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-products">
            <div class="div-products">
                <div class="div-product-view-option">
                    <table class="p-view color-default pull-left">
                        <tr>
                            <td class="td-view p-view2 color-default">VIEW STYLE:</td>
                            <td class="td-view" style="padding-top: 3px;"><span class="gv fa fa-icon-view-grid fa-2x icon-view icon-grid active-view"></span> <span class="lv fa fa-icon-view-list fa-2x icon-view icon-list"></span></td>
                        </tr>
                    </table>
                </div>

                <div class="clear"></div>

                <input type="hidden" id="vid" value="<?php echo $arrVendorDetails['id_member']?>">
                <input type="hidden" id="vname" value="<?php echo $arrVendorDetails['username']?>">
                <input type="hidden" id="queryString" value='<?=html_escape(json_encode($this->input->get())); ?>' />

                <div class="vendor-select-con">
                    <select class="sort_select form-select-default color-default pull-right">
                        <option value="0" class="order-by-default">Default Sorting</option>
                        <option value="1" class="order-by-popularity">Popularity</option>
                        <option value="2" class="order-by-lastmodified">Most Recent</option>
                        <option value="3" class="order-by-hotness">Hot</option>
                    </select>
                    <div class="clear"></div>
                </div>
                <?php $isFirst = true; ?>
                <?php foreach($categoryProducts as $catId => $categoryData):?>
                    <?php $categoryWrapper = $categoryData['category']; ?>
                    <?php $isSearch = $categoryData['cat_type'] === \EasyShop\Category\CategoryManager::CATEGORY_SEARCH_TYPE; ?>
                    <div class="view row row-items grid category-products <?php echo $isFirst ? 'active' : ''; ?>" 
                        id="def-<?php echo $isSearch ? 'search' : $categoryWrapper->getId(); ?>"
                        data-catId='<?php echo html_escape($categoryData['json_subcat']);?>' 
                        data-group="<?php echo $categoryWrapper->getId(); ?>" 
                        data-productcount="<?php echo $categoryData['non_categorized_count'] ?>"
                        data-catType="<?php echo html_escape($categoryData['cat_type']); ?>"
                        data-isCustom="<?php echo json_encode($categoryWrapper->getIsCustom()); ?>"
                        style = "<?php echo $isFirst ? '' : 'display:none;'; ?>"
                    >                    
                        <div class="loading_div" style="text-align:center;display:none;"><img src="<?php echo getAssetsDomain()?>assets/images/loading/preloader-grayBG.gif"></div>
                        <?php if($categoryData['non_categorized_count'] === 0): ?>
                            <span>No items available for this category.</span>
                        <?php else:?>
                            <?=$categoryData['product_html_data'];?>
                        <?php endif;?>
                        
                        <center>
                            <div class="simplePagination">
                                <?=$categoryData['pagination'];?>
                            </div>
                        </center>
                        
                    </div>
                    <?php $isFirst = false; ?>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
</section>
<div class="mobile-left-wing">
    <div class="row row-left-wing">
        <a href="#">
            <div class="col-xs-6 col-categories">
                Categories
            </div>
            
        </a>
        <a href="">
            <div class="col-xs-6 col-filter">
                Filter
            </div>
        </a>
    </div>
</div>
<!-- here-->
<div class="categories-modal">
    <h1>Categories</h1>
    <div class="category-list panel-collapse collapse in">
        <div class="panel-body border-0 no-padding">
            <ul class="list-unstyled list-category">    
                <?php $isFirst = true; ?>
                <?php foreach( $categoryProducts as $categoryId => $categoryData ):?>
                    <?php $categoryWrapper = $categoryData['category']; ?>
                    <?php $isSearch = $categoryData['cat_type'] === \EasyShop\Category\CategoryManager::CATEGORY_SEARCH_TYPE; ?>
                    <?php if($categoryWrapper->getIsHidden()): ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <li>
                        <a href="javascript: void(0)" data-link="#def-<?php echo $isSearch ? 'search' : $categoryWrapper->getId(); ?>" class="color-default tab_categories <?php if($categoryWrapper->getIsCustom() && empty($children) === false): ?>simplemodal-close<?php endif;?>">
                            <span class='catText'>
                                <?php echo html_escape($categoryWrapper->getCategoryName());?>
                            </span> 
                        <?php $children = $categoryWrapper->getChildren(); ?>
                        <?php 
                            /**
                             * If child has no products remove it from the children array
                             */
                        ?>
                        <?php foreach($children as $key => $child): ?>
                            <?php if(!in_array((int)$child->getId(), array_keys($categoryProducts))): ?>
                                <?php unset($children[$key]); ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if($categoryWrapper->getIsCustom() && empty($children) === false): ?>
                            <i class="fa fa-caret-down fa-lg pull-right"></i>
                        </a>
                            <ul class="list-sub-category">
                            <?php foreach($children as $child): ?>
                                <li>
                                    <a href="javascript:void(0)" class="color-default tab_categories simplemodal-close" data-link="#def-<?php echo $child->getId(); ?>" >
                                        <?php echo html_escape($child->getCategoryName()); ?> 
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        </a>
                    </li>
                    <?php $isFirst = false; ?>
                <?php endforeach;?>
            </ul>
        </div>
        <a class="simplemodal-close close-hide">x</a>
    </div>
</div>
<div class="filter-modal">
    <h1>Filter Products</h1>
</div>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src="/assets/js/src/vendor/bootstrap.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
    <script src='/assets/js/src/vendor/jquery.Jcrop.min.js' type='text/javascript'></script>
    <script src='/assets/js/src/vendor/jquery.simplemodal.js' type='text/javascript'></script>
    <script src="/assets/js/src/vendor/jquery.scrollTo.js" type="text/javascript"></script>
    <script src="/assets/js/src/vendor/chosen.jquery.min.js" type="text/javascript"></script>
    <script src="/assets/js/src/easyshop.simplePagination.js"></script>
    <script src='/assets/js/src/vendorpage_new.js?ver=<?=ES_FILE_VERSION?>' type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.user_vendor_view.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

