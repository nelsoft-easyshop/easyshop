<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE; Safari; Mozilla" />

<!-- Load body -->

<div class="clear"></div>
<section class="bg-product-section color-default">
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
                                        <select id="condition-filter" class="select-filter">
                                            <option value="">--Select Condition--</option>
                                        <?php foreach($this->lang->line('product_condition') as $x): ?>
                                            <option value="<?php echo $x;?>"><?php echo $x; ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </li>
                                    <li>
                                        <p class="p-filter-name">By Price</p>
                                        from <input type="text" id="price1" class="price-field input-filter-price"/> 
                                        to <input type="text" id="price2" class="price-field input-filter-price"/>
                                    </li>
                                    <li>
                                        <center>
                                            <input type="button" class="btn-filter" id="btnFilter" value="filter"/>
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
                <input type="hidden" id="queryString" value='<?=json_encode($_GET); ?>' />

               <?=$viewProductCategory;?>

<!--
                <div class="view row row-items grid" id="fuck">
                    <?php if($product_count > 0):?>
                        <?php foreach($products as $catID=>$p):?>
                            <?php foreach($p['products'] as $prod):?>
                                <div class="panel panel-default panel-list-item">
                                    <table width="100%">
                                        <tr>
                                            
                                            <td width="20%" class="td-list-image" style="background: url(<?=base_url()?><?php echo $prod['product_image_path']?>) center no-repeat; background-cover: cover;">
                                                <a href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                                <div class="span-space">
                                                    <span class="span-discount-pin">10% OFF</span>
                                                </div>
                                                </a>
                                            </td>
                                            
                                            <td width="55%" class="td-list-item-info">
                                                <p class="p-list-item-name">
                                                    
                                                        <?php 
                                                            $prod_name = html_escape($prod['name']);
                                                            if(strlen($prod_name)>35){
                                                        ?>
                                                            <a class="color-default" rel="tooltiplist" target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>" data-toggle="tooltip" data-placement="bottom"  title="<?php echo html_escape($prod['name']);?>">
                                                                <?php echo substr_replace( $prod_name, "...", 35);?>
                                                            </a>
                                                        <?php  
                                                            }else{
                                                        ?>
                                                            <a class="color-default" target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                                                <?php echo $prod_name;?>
                                                            </a>
                                                        <?php
                                                            }
                                                        ?>
                                                    
                                                    <script>
                                                        $(document).ready(function(){
                                                            $('[rel=tooltiplist]').tooltip({
                                                                placement : 'top'
                                                            });
                                                        });                                                     
                                                    </script>
                                                </p>
                                                <p class="p-list-item-category">
                                                    Electronics and Gadgets
                                                </p>
                                                <div class="div-list-desc-container">
                                                    Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
                                                </div>
                                            </td>
                                            <td width="25%" class="td-list-price">
                                                <p class="p-list-price">
                                                    P <?php echo html_escape($prod['price'])?>
                                                </p>
                                                <div class="clear"></div>
                                                <p class="p-list-discount">
                                                    <s> P 1,200.00 </s>
                                                </p>
                                                <p class="p-discount">
                                                    <span><s> P 1200.00 </s></span>
                                                </p>
                                                
                                                <center>
                                                    <button class="btn btn-default-cart">
                                                        <span class="fa fa-shopping-cart"></span> ADD TO CART
                                                    </button>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        <?php endforeach;?>
                    <?php endif;?>
                </div>
--><!--
                <center>
                    <ul class="pagination pagination-items">
                        <li class="disabled"><a href="#"><span>&laquo;</span></a></li>
                        <li class="active"><a href="#"><span>1</span></a></li>
                        <li><a href="#"><span>2</span></a></li>
                        <li><a href="#"><span>3</span></a></li>
                        <li><a href="#"><span>4</span></a></li>
                        <li><a href="#"><span>5</span></a></li>
                        <li><a href="#"><span>6</span></a></li>
                        <li><a href="#"><span>7</span></a></li>
                        <li><a href="#"><span>&raquo;</span></a></li>
                    </ul>
                </center>
    -->
            </div>
        </div>
        
    </div>
    </div>
    
</section>

 
<script src='/assets/js/src/vendorpage_new.js' type="text/javascript"></script>

