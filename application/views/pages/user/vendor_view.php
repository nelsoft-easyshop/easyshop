<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE; Safari; Mozilla" />

<!-- Load body -->
<div class="clear"></div>
<section class="bg-product-section color-default"><br>
<div class="container-non-responsive bg-product-section">
    <div class="row row-products">
        <div class="col-xs-3 no-padding col-left-wing">
            <div class="left-wing">
                <div class="panel-group panel-category border-0" id="category">
                    <div class="panel panel-default  border-0 no-padding">
                        <div class="panel-heading border-0 panel-category-heading" id="cat-header">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" class="a-category" data-parent="#category" href="#category-list">
                                    CATEGORIES <b class="cat fa fa-minus-square-o pull-right"></b>
                                </a>
                            </h4>
                        </div>
                        <div id="category-list" class="panel-collapse collapse in">
                            <div class="panel-body border-0 no-padding">
                                <ul class="list-unstyled list-category">
                                    <?php foreach( $defaultCatProd as $catId=>$arrCat ):?>
                                        <a href="javascript: void(0)" data-link="#def-<?php echo $catId?>" class="color-default tab_categories"><li><?php echo $arrCat['name']?></li></a>
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
                                <a data-toggle="collapse" class="a-filter" data-parent="#filter" href="#filter-list">
                                    FILTER PRODUCTS <b class="fil fa fa-minus-square-o pull-right"></b>
                                </a>
                            </h4>
                        </div>
                        <div id="filter-list" class="panel-collapse collapse in">
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
                                        from <input id="filter-lprice" type="text" class="input-filter-price price-field"/> to <input id="filter-uprice" type="text" class="input-filter-price price-field"/>
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
        <div class="col-xs-9 col-products">
            <div class="div-products">
                <div class="div-product-view-option">
                    <table class="p-view color-default pull-left">
                        <tr>
                            <td class="td-view p-view color-default">VIEW STYLE:</td>
                            <td class="td-view" style="padding-top: 3px;"><span class="gv fa fa-th-large fa-2x icon-view icon-grid active-view"></span> <span class="lv fa fa-th-list fa-2x icon-view icon-list"></span></td>
                        </tr>
                    </table>
                </div>

                <div class="clear"></div>

                <input type="hidden" id="vid" value="<?php echo $arrVendorDetails['id_member']?>">
                <input type="hidden" id="vname" value="<?php echo $arrVendorDetails['username']?>">
                <input type="hidden" id="queryString" value='<?=json_encode($this->input->get()); ?>' />

                <?php $divCounter = 0; foreach($defaultCatProd as $catId => $arrCat):?>
                <div class="view row row-items grid category-products <?php echo $divCounter === 0 ? 'active' : ''?>" id="def-<?php echo $catId?>" data-catId='<?php echo $arrCat['json_subcat']?>' data-catType="<?php echo $arrCat['cat_type']?>" style="display:<?php echo $divCounter>0 ? 'none' : ''?>">
                    <div class="vendor-select-con">
                        <select data-group="<?php echo $catId?>" class="sort_select form-select-default color-default pull-right">
                            <option value="1">Default Sorting</option>
                            <option value="2">Date Uploaded</option>
                            <option value="3">Hot</option>
                        </select>
                        <div class="clear"></div>
                    </div>
                    <div class="loading_div" style="text-align:center;display:none;"><img src="assets/images/orange_loader.gif"></div>

                    <?php if($arrCat['non_categorized_count'] === 0): ?>
                        <span>No items available for this category.</span>
                    <?php else:?>

                        <?=$arrCat['product_html_data'];?>

                        <div class="clear"></div>
                        <div id="paginationDiv-<?php echo $catId?>">
                            <center>
                                <ul class="pagination pagination-items">
                                    <li data-group="<?php echo $catId?>" class="pagination-maxleft"><a href="javascript:void(0)"><span>&laquo;</span></a></li>
                                    <?php for($i=1; $i<=ceil($arrCat['non_categorized_count']/$prodLimit); $i++):?>
                                        <li data-group="<?php echo $catId?>" class="pagination-indiv <?php echo $i===1 ? "active" : "" ?>" data-page="<?php echo $i;?>">
                                            <a href="javascript:void(0)">
                                                <span><?php echo $i?></span>
                                            </a>
                                        </li>
                                    <?php endfor;?>
                                    <li data-group="<?php echo $catId?>" class="pagination-maxright"><a href="javascript:void(0)"><span>&raquo;</span></a></li>
                                </ul>
                            </center>
                        </div>
                   <?php endif;?>

                </div>

                <?php $divCounter++; endforeach;?>

            </div>
        </div>
    </div>
</div>
 
    
</section>

 
<script src='/assets/js/src/vendorpage_new.js' type="text/javascript"></script>
<script src="/assets/js/src/bootstrap.js" type="text/javascript"></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.Jcrop.min.js'></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js'></script>
<script src="/assets/js/src/vendor/chosen.jquery.min.js" type="text/javascript"></script>

