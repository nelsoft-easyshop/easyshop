<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" href="/assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/assets/css/product_search_category_responsive.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/> 
    <link rel="stylesheet" href="/assets/css/product_advance_search.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<?php else: ?>
    <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.advance-search-main.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
<?php endif; ?>



<section class="bg-cl-fff">
<?php
    $attr = array('id'=>'advsrch', 'autocomplete'=>'off', 'method'=>'get');
    echo form_open('',$attr);
?>

<div class="container display-when-desktop" id="main_search_container"> 
    <div class="left_attribute">
        <?php if(isset($subCategory)): ?>
            <h3>Categories</h3>
            <ul>
                <?php foreach ($subCategory as $value):?>
                    <li style="border:0px">
                        <a class="cbx" data-head="category" data-value="<?=$value->getIdCat()?>" >
                            <input type='radio' class='adv_catpanel' name='_subcat' value="<?=html_escape($value->getIdCat())?>"> 
                            <?=html_escape($value->getName())?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if(isset($attributes)): ?>
            <?php foreach ($attributes as $attrName => $attrListValue):?>
                <h3><?= html_escape($attrName); ?></h3>
                <ul>
                <?php foreach ($attrListValue as $value):?>
                    <li style="border:0px">
                        <a class="cbx" data-head="<?= html_escape(strtolower($attrName)); ?>" data-value="<?= html_escape(strtolower($value)); ?>" >
                            <input type="checkbox" <?=(isset($getParameter[strtolower($attrName)]) && strpos($getParameter[strtolower($attrName)],strtolower($value)) !== false)?'checked':'';?> class="checkBox" >
                            <label for="cbx"><?= html_escape(ucfirst($value)); ?></label>
                        </a>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        <?php endif; ?>

        <p class="more_attr">More Filters</p>
        <p class="less_attr">Less Filters</p>
    </div> 

    <div class="right_product">
        <div class="advsearch">
            <div class="inputRow">
                <span class="adv_is">   
                    <label>Keyword: </label>
                    <input type="text" name="q_str" id="keywordTxt" value="<?=isset($getParameter['q_str']) ? html_escape($getParameter['q_str']) : ''?>" size="30" maxlength="300" placeholder="Enter keywords or item number" />
                </span>
                <span class="adv_is">
                    <select name="category" id="selectCat" title="Select item category">
                        <option value="1">- All -</option>
                        <?php foreach ($parentCategory as $key => $value): ?>
                            <option value="<?php echo $value->getIdCat();?>" <?=(isset($getParameter['category']) && (int)$getParameter['category'] === (int)$value->getIdCat()) ? 'selected' : '';?> >
                                <?=html_escape($value->getName());?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </span>
                <input value="SEARCH" type="button" id="btn_srch" />
            </div>
            <div class="inputRow">
                <span class="adv_us">
                    <label>Seller:</label>
                    <input type="text" name="seller" id="sellerTxt" value="<?=isset($getParameter['seller']) ? html_escape($getParameter['seller']) : '' ?>" size="30" maxlength="30" placeholder="Search for a seller's item" />
                </span>
                <span class="adv_us">
                    <label>Location:</label>
                    <select title="Select item location" name="location" id="selectLocation" class="advsrchLocation">
                        <option value="">- All -</option>
                        <?php foreach($locatioList['area'] as $island=>$loc):?>
                            <option value="<?php echo $locatioList['islandkey'][$island];?>" <?=(isset($getParameter['location']) && (int)$getParameter['location'] === (int)$locatioList['islandkey'][$island]) ? 'selected' : '';?> ><?php echo $island;?></option>
                                <?php foreach($loc as $region=>$subloc):?>
                                    <option value="<?php echo $locatioList['regionkey'][$region];?>" style="margin-left:15px;" <?=(isset($getParameter['location']) && (int)$getParameter['location'] === (int)$locatioList['regionkey'][$region])?'selected':'';?> >&nbsp;&nbsp;&nbsp;<?php echo $region;?></option>
                                        <?php foreach($subloc as $id_cityprov=>$cityprov):?>
                                                <option value="<?php echo $id_cityprov;?>" style="margin-left:30px;" <?=(isset($getParameter['location']) && (int)$getParameter['location'] === (int)$id_cityprov)?'selected':'';?> >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cityprov;?></option>
                                        <?php endforeach;?>
                                <?php endforeach;?>
                        <?php endforeach;?>
                    </select>
                </span>
            </div>
            <div class="inputRow">
                <span class="adv_us">
                    <label>Condition:</label>
                    <select title="Select item condition" name="condition" id="selectCondition" class="prod_condition">
                    <option value="">- All -</option>
                        <?php foreach($defaultCondition as $con): ?>
                            <option value="<?php echo $con;?>" <?=(isset($getParameter['condition']) && strtolower($con) === strtolower($getParameter['condition'])) ? 'selected' : '';?> ><?php echo html_escape($con); ?></option>
                        <?php endforeach; ?>
                    </select>
                </span>
                <span class="adv_us">
                    <label>Price:</label>
                    <input type="text" name="startprice" class="priceField" id="price1" value="<?=isset($getParameter['startprice']) ? $getParameter['startprice'] : '' ?>" maxlength="10" size="6" placeholder="Min" title="Minimum price">
                    to
                    <input type="text" name="endprice" class="priceField" id="price2" value="<?=isset($getParameter['endprice']) ? $getParameter['endprice'] : '' ?>" maxlength="10" size="6" placeholder="Max" title="Maximum price">
                </span>
            </div>
        </div> 
        
        <?php if(isset($products)): ?>
        <div class="adv_ctr"><strong style="font-size:14px"><?=$productCount;?></strong> result<?=(number_format($productCount) > 1)?"s":"";?>  found</div>
        <?php endif; ?>

        <!-- Buttons start -->
        <div id="list" class="list <?=(isset($_COOKIE['view']) && $_COOKIE['view'] == "product-list")?"list-active":"";?>"></div>
        <div id="grid" class="grid <?=(isset($_COOKIE['view']) && $_COOKIE['view'] == "product-list")?"":"grid-active";?> "></div>
        <!-- Buttons end -->
        <div class="clear"></div> 
        <div id="product_content">
            <?php if(isset($products)): ?>
                <?php if(count($products)>0): ?>
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
                                            <img alt="<?php echo $productName; ?>" src="<?php echo getAssetsDomain() ?><?=$productImagePath; ?>">
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
                    <br><br><h3 align='center'>No Result Found.</h3>
                <?php endif;?>
            <?php else: ?>
                <br><br><h3 align='center'>Begin searching by applying search filters.</h3>
            <?php endif; ?>
        </div>
        <div class="loading_products" style="display: inline-block;text-align: center;width: 100%;"></div>
    </div>
</div>
<?php echo form_close();?>

<!-- MOBILE VERSION SECTION -->
<?php
    $attr = array('id'=>'madvsrch', 'autocomplete'=>'off', 'method'=>'get');
    echo form_open('',$attr);
?>
    <div class="display-when-mobile-1024 pd-tb-45">
        <div class="container container-responsive container-search">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-group panel-search" id="accordion">

                        <div class="panel panel-default no-border ">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" class="a-accordion-header" href="#collapseOne">
                                        Advanced Search <i class="adv glyphicon <?=(isset($products))?'glyphicon-chevron-down':'glyphicon-chevron-up';?> pull-right"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse <?=(isset($products))?'':'in';?>">
                            <div class="panel-body">
                                <table width="100%">
                                    <tr>
                                        <td class="td-search-label">Keyword: </td>
                                        <td class="td-search-input">
                                           <input type="text" name="q_str" id="keywordTxt" class="form-control input-sm no-border" value="<?=(isset($string))?html_escape($string):'';?>" size="30" maxlength="300" placeholder="Enter keywords or item number" />
                                       </td>
                                     </tr>
                                    <tr>
                                        <td class="td-search-label">Category: </td>
                                        <td class="td-search-input">
                                            <select name="category" id="selectCat" class="form-control input-sm no-border" title="Select item category">
                                                <option value="1">- All -</option>
                                                <?php foreach ($parentCategory as $key => $value): ?>
                                                    <option value="<?php echo $value->getIdCat();?>" <?=isset($getParameter['category']) && ((int)$getParameter['category'] === (int)$value->getIdCat())?'selected':'';?> ><?php echo html_escape($value->getName());?></option>
                                                <?php endforeach; ?>
                                            </select> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-search-label">Seller: </td>
                                        <td class="td-search-input">
                                            <input type="text" name="seller" class="form-control no-border input-sm" id="sellerTxt" value="<?=isset($getParameter['seller']) ? html_escape($getParameter['seller']) : ''?>" size="30" maxlength="30" placeholder="Search for a seller's item" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-search-label">Location: </td>
                                        <td class="td-search-input">
                                            <select title="Select item location" name="location" id="selectLocation" class="advsrchLocation form-control input-sm no-border">
                                                <option value="">- All -</option>
                                                <?php foreach($locatioList['area'] as $island=>$loc):?>
                                                    <option value="<?php echo $locatioList['islandkey'][$island];?>" <?=(isset($getParameter['location']) && (int)$getParameter['location'] === (int)$locatioList['islandkey'][$island]) ? 'selected' : '';?> ><?php echo $island;?></option>
                                                        <?php foreach($loc as $region=>$subloc):?>
                                                            <option value="<?php echo $locatioList['regionkey'][$region];?>" style="margin-left:15px;" <?=(isset($getParameter['location']) && (int)$getParameter['location'] === (int)$locatioList['regionkey'][$region])?'selected':'';?> >&nbsp;&nbsp;&nbsp;<?php echo $region;?></option>
                                                                <?php foreach($subloc as $id_cityprov=>$cityprov):?>
                                                                        <option value="<?php echo $id_cityprov;?>" style="margin-left:30px;" <?=(isset($getParameter['location']) && (int)$getParameter['location'] === (int)$id_cityprov)?'selected':'';?> >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cityprov;?></option>
                                                                <?php endforeach;?>
                                                        <?php endforeach;?>
                                                <?php endforeach;?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-search-label">Condition: </td>
                                        <td class="td-search-input">
                                            <select title="Select item condition" name="condition" id="selectCondition" class="prod_condition form-control input-sm no-border">
                                                <option value="">- All -</option>
                                                <?php foreach($defaultCondition as $con): ?>
                                                    <option value="<?php echo $con;?>" <?=(isset($getParameter['condition']) && strtolower($con) === strtolower($getParameter['condition'])) ? 'selected' : '';?> ><?php echo html_escape($con); ?></option>
                                                <?php endforeach; ?>
                                            </select> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-search-label">Price: </td>
                                        <td class="td-search-input">
                                            <input type="text" name="startprice" class="priceField" id="mprice1" value="<?=isset($getParameter['startprice']) ? $getParameter['startprice'] : '' ?>" maxlength="10" size="6" placeholder="Min" title="Minimum price">
                                            to
                                            <input type="text" name="endprice" class="priceField" id="mprice2" value="<?=isset($getParameter['endprice']) ? $getParameter['endprice'] : '' ?>" maxlength="10" size="6" placeholder="Max" title="Maximum price">
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td colspan="2" class="td-search-button">
                                            <br/>
                                            <input type="button" value="SEARCH" id="mbtn_srch" class="btn btn-lg btn-block" />  
                                            <br/>
                                            <center><a data-toggle="modal" data-target="#refine" class="a-refine">Refine Search</a></center>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            </div>
                        </div>

                        <!-- Product section -->
                        <div id="paste-product">
                            <?php if(isset($products)): ?>
                                <?php if(count($products)>0): ?>
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
                                                         <a class="a-item-name" href="/item/<?=$productSlug; ?>"> 
                                                            <img alt="<?php echo $productName; ?>" src="<?php echo getAssetsDomain() ?><?=$productImagePath; ?>">
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
                                    <div class="panel panel-default no-border">
                                        <center>
                                            <h4><span class="glyphicon glyphicon glyphicon-warning-sign"></span> No result found.</h4>
                                        </center>
                                    </div>
                                <?php endif;?>
                            <?php else: ?>
                                <div class="panel panel-default no-border">
                                    <center>
                                        <h4><span class="glyphicon glyphicon-search"></span> Begin searching by applying search filters.</h4>
                                    </center>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="loading_products" style="display: inline-block;text-align: center;width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo form_close();?> 

<!-- Modal -->
<div class="modal fade" id="refine" tabindex="-1" role="dialog" aria-labelledby="SubCategories" aria-hidden="true">
    <div class="modal-dialog no-border border-0">
        <div class="modal-content no-border">
            <div class="modal-header bg-orange">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" class="color-white">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title color-white" id="SubCategories">Refine Search</h4>
            </div>
            <div class="modal-body no-border">
                <p class="h3-cat-title">Categories</p>
                <?php if(isset($subCategory)): ?>
                    <ul class="list-unstyled">
                    <?php foreach ($subCategory as $value):?>
                        <li>
                            <a class="cbx" data-head="q_cat" data-value="<?=$value->getIdCat()?>" >
                                <input type='checkbox' class='adv_catpanel' value="<?=html_escape($value->getIdCat())?>"> 
                                <label for="cbx"><?=html_escape($value->getName())?></label>
                            </a>
                        </li>
                    <?php endforeach; ?> 
                    </ul>
                    <div class="clear"></div> 
                <?php endif; ?>
                <?php if(isset($attributes)): ?>
                    <?php foreach ($attributes as $attrName => $attrListValue):?>
                        <p class="h3-cat-title"><?= html_escape($attrName) ?></p>
                        <ul class="list-unstyled">
                        <?php foreach ($attrListValue as $value):?>
                           <li>
                                <a class="cbx" data-head="<?= html_escape(strtolower($attrName))?>" data-value="<?=html_escape(strtolower($value))?>" >
                                    <input type="checkbox" <?=(isset($getParameter[strtolower($attrName)]) && strpos($getParameter[strtolower($attrName)],strtolower($value)) !== false)?'checked':'';?> class="checkBox" data-head="<?=strtolower(html_escape($attrName))?>" data-value="<?=strtolower(html_escape($value))?>" >
                                    <label for="cbx"><?=html_escape(ucfirst($value));?></label>
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
    <input type="hidden" id="hidden-loadUrl" value="/advsrch/more?<?=$_SERVER['QUERY_STRING']; ?>" />
</div>

</section>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src="/assets/js/src/vendor/bootstrap.js" type="text/javascript"></script>
    <script src="/assets/js/src/vendor/bower_components/jquery.easing.js" type="text/javascript"></script>
    <script src="/assets/js/src/vendor/jquery.scrollUp.min.js" type="text/javascript"></script>
    <script src="/assets/js/src/advsearch.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.advance_search_main.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

