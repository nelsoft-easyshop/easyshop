<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE; Safari; Mozilla" />
<link rel="stylesheet" type="text/css" href="/assets/css/easy-icons/easy-icons.css" media='screen'>
<style>
    .vendor-footer-con{
        margin-bottom: 36px;
    }
    #simplemodal-container{
        min-height: 100px !important;
        padding: 10px; 
    }
</style>

<div class="clear"></div>
<section class="bg-product-section color-default"><br>
<div class="container bg-product-section">
    <div class="row row-products">
        <div class="col-md-3 no-padding col-left-wing">
            <div class="left-wing">
                <div class="panel-group panel-category border-0" id="category">
                    <div class="panel panel-default  border-0 no-padding">
                        <div class="panel-heading border-0 panel-category-heading" id="cat-header">
                            <h4 class="panel-title">
                                <a id="toggle-cat" class="a-category" data-parent="#category">
                                    CATEGORIES <b class="cat fa fa-minus-square-o pull-right"></b>
                                </a>
                            </h4>
                        </div>
                        <div id="category-list" class="panel-collapse collapse in">
                            <div class="panel-body border-0 no-padding">
                                <ul class="list-unstyled list-category">
                                    <?php foreach( $customCatProd as $catId=>$arrCat ):?>
                                        <a href="javascript: void(0)" data-link="#cus-<?php echo $catId?>" class="color-default tab_categories">
                                            <li>
                                                <span style="display: <?php echo $arrCat['isActive'] ? '' : 'none'?>" class="fa fa-caret-right active-category selected-marker"></span>  <?php echo $arrCat['name']?>
                                            </li>
                                        </a>
                                    <?php endforeach;?>
                                    <?php foreach( $defaultCatProd as $catId=>$arrCat ):?>
                                        <a href="javascript: void(0)" data-link="#def-<?php echo $catId?>" class="color-default tab_categories">
                                            <li>
                                                <span style="display: <?php echo $arrCat['isActive'] ? '' : 'none'?>" class="fa fa-caret-right active-category selected-marker"></span>  <?php echo $arrCat['name']?>
                                            </li>
                                        </a>
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
                                            <input id="filter-btn" type="button" class="btn-filter" value="filter"/>
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
                <input type="hidden" id="queryString" value='<?=json_encode($this->input->get()); ?>' />

                <div class="vendor-select-con">
                    <select data-group="<?php echo $catId?>" class="sort_select form-select-default color-default pull-right">
                        <option value="2">Default Sorting</option>
                        <option value="1">Popularity</option>
                        <option value="3">Hot</option>
                    </select>
                    <div class="clear"></div>
                </div>

                <?php foreach($customCatProd as $catId => $arrCat):?>
                <div class="view row row-items grid category-products <?php echo $arrCat['isActive'] ? 'active' : ''?>" 
                    id="cus-<?php echo $catId?>" data-catId='<?php echo $arrCat['json_subcat']?>' 
                    style="display:<?php echo $arrCat['isActive'] ? '' : 'none'?>" 
                    data-productcount="<?=$arrCat['non_categorized_count']?>"
                    data-catType="<?php echo $arrCat['cat_type']?>"
                >
                    <div class="loading_div" style="text-align:center;display:none;"><img src="assets/images/loading/preloader-grayBG.gif"></div>

                    <?php if((string)$arrCat['non_categorized_count'] === "0"): ?>
                        <span>No items available for this category.</span>
                    <?php else:?>
                        <?=$arrCat['product_html_data'];?>
                        <div class="clear"></div>
                        <div id="paginationDiv-<?php echo $catId?>" class="pagination-container">
                            <center>
                                <?php echo $arrCat['pagination']?>
                            </center>
                        </div>
                   <?php endif;?>
                </div>
                <?php endforeach;?>

                <?php foreach($defaultCatProd as $catId => $arrCat):?>
                    <div class="view row row-items grid category-products <?php echo $arrCat['isActive'] ? 'active' : ''?>" 
                        id="def-<?php echo $catId?>"
                        data-catId='<?php echo $arrCat['json_subcat']?>' 
                        style="display:<?php echo $arrCat['isActive'] ? '' : 'none'?>" 
                        data-group="<?php echo $catId?>" 
                        data-productcount="<?=$arrCat['non_categorized_count']?>"
                        data-catType="<?php echo $arrCat['cat_type']?>"
                    >
                        <div class="loading_div" style="text-align:center;display:none;"><img src="assets/images/loading/preloader-grayBG.gif"></div>

                        <?php if($arrCat['non_categorized_count'] === 0): ?>
                            <span>No items available for this category.</span>
                        <?php else:?>

                            <?=$arrCat['product_html_data'];?>

                    <?php endif;?>

                    </div>

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
<div class="categories-modal">
    
</div>

		<!-- preload the images -->
		<div style='display:none'>
			<img src='img/basic/x.png' alt='' />
		</div>
 
<script src='/assets/js/src/vendorpage_new.js?ver=<?=ES_FILE_VERSION?>' type="text/javascript"></script>
<script src="/assets/js/src/vendor/bootstrap.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script src='/assets/js/src/vendor/jquery.Jcrop.min.js' type='text/javascript'></script>
<script src='/assets/js/src/vendor/jquery.simplemodal.js' type='text/javascript'></script>
<script src="/assets/js/src/vendor/jquery.scrollTo.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/chosen.jquery.min.js" type="text/javascript"></script>

