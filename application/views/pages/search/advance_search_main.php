
<link rel="stylesheet" href="<?=base_url()?>assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?=base_url()?>assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<style type="text/css">
 
#btn_srch{
    width:65px;
}
</style>
<?php
    $attr = array('id'=>'advsrch', 'autocomplete'=>'off', 'method'=>'get');
    echo form_open('',$attr);
?>
<div class="wrapper" id="main_search_container"> 
    <div class="left_attribute">
        <?php if(isset($subCategory)): ?>
            <h3>Categories</h3>
            <ul>
                <?php foreach ($subCategory as $key => $value):?>
                    <li style="border:0px">
                        <a class="cbx" data-head="q_cat" data-value="<?=$value->getIdCat()?>" >
                            <input type='checkbox' class='adv_catpanel' name='_subcat' value="<?=html_escape($value->getIdCat())?>"> 
                            <label for="cbx"><?=html_escape($value->getName())?></label>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if(isset($attributes)): ?>
            <?php foreach ($attributes as $attrName => $attrListValue):?>
                <h3><?=$attrName?></h3>
                <ul>
                <?php foreach ($attrListValue as $key => $value):?>
                    <li style="border:0px">
                        <a class="cbx" data-head="<?=strtolower($attrName)?>" data-value="<?=strtolower($value)?>" >
                            <input type="checkbox" <?=(strpos($this->input->get(strtolower($attrName)),strtolower($value)) !== false)?'checked':'';?> class="checkBox" >
                            <label for="cbx"><?=ucfirst($value);?></label>
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
                    <input style="" type="text" name="q_str" id="keywordTxt" value="<?=(isset($string))?html_escape($string):'';?>" size="30" maxlength="300" placeholder="Enter keywords or item number" />
                </span>
                <span class="adv_is">
                    <select name="q_cat" id="selectCat" title="Select item category">
                        <option value="1">- All -</option>
                        <?php foreach ($parentCategory as $key => $value): ?>
                            <option value="<?php echo $value->getIdCat();?>" <?=($this->input->get('q_cat')==$value->getIdCat())?'selected':'';?> ><?php echo $value->getName();?></option>
                        <?php endforeach; ?>
                    </select>
                </span>
                <input value="SEARCH" type="submit" id="btn_srch" />
            </div>
            <div class="inputRow">
                <span class="adv_us">
                    <label>Seller:</label>
                    <input type="text" name="seller" id="sellerTxt" value="<?=html_escape($this->input->get('seller'))?>" size="30" maxlength="30" placeholder="Search for a seller's item" />
                </span>
                <span class="adv_us">
                    <label>Location:</label>
                    <select title="Select item location" name="location" id="selectLocation" class="advsrchLocation">
                        <option value="">- All -</option>
                            <?php foreach($locatioList['area'] as $island=>$loc):?>
                                <option value="<?php echo $locatioList['islandkey'][$island];?>" <?=($this->input->get('location') == $locatioList['islandkey'][$island])?'selected':'';?> ><?php echo $island;?></option>
                                    <?php foreach($loc as $region=>$subloc):?>
                                        <option value="<?php echo $locatioList['regionkey'][$region];?>" style="margin-left:15px;" <?=($this->input->get('location') == $locatioList['regionkey'][$region])?'selected':'';?> >&nbsp;&nbsp;&nbsp;<?php echo $region;?></option>
                                            <?php foreach($subloc as $id_cityprov=>$cityprov):?>
                                                <option value="<?php echo $id_cityprov;?>" style="margin-left:30px;" <?=($this->input->get('location') == $id_cityprov)?'selected':'';?> >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cityprov;?></option>
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
                            <option value="<?php echo $con;?>" <?=(strtolower($con) == strtolower($this->input->get('condition')))?'selected':'';?> ><?php echo $con; ?></option>
                        <?php endforeach; ?>
                    </select>
                </span>
                <span class="adv_us">
                    <label>Price:</label>
                    <input type="text" name="startprice" id="price1" value="<?=$this->input->get('startprice')?>" maxlength="10" size="6" placeholder="Min" title="Minimum price">
                    to
                    <input type="text" name="endprice" id="price2" value="<?=$this->input->get('endprice')?>" maxlength="10" size="6" placeholder="Max" title="Maximum price">
                </span>
    <!--             <span class="adv_us">
                    <label>Sort by:</label>
                    <select name="_sop" id="_sop" title="Sort item">
                        <option value="popular" <?php if($getsop == "popular"){?>selected="selected"<?php } ?>>Popular</option>
                        <option value="hot" <?php if($getsop == "hot"){?>selected="selected"<?php } ?>>Hot</option>     
                        <option value="new" <?php if($getsop == "new"){?>selected="selected"<?php } ?>>New</option>
                        <option value="con" <?php if($getsop == "con"){?>selected="selected"<?php } ?>>Item Condition</option>
                    </select>
                </span> -->
            </div>
        </div>

        <?php if(isset($cntr)): ?>
            <div class="adv_ctr"><strong style="font-size:14px"><?php echo ($cntr>0)?number_format($cntr):'No';?></strong> result<?php echo  ($cntr>1 || $cntr === 0)?'s':'';?> found</div>
        <?php endif ?>
        
        <!-- Buttons start -->
        <div id="list" class="list list-active" title="List"></div>
        <div id="grid" class="grid" title="Grid"></div>
        <!-- Buttons end -->
        <div class="clear"></div> 
        <div id="product_content">
            <?php if(isset($products)): ?>
                <?php if(count($products)>0): ?>
                    <?php 
                    foreach ($products as $key => $value):
                        $productEntity = $value->getProduct();
                        $productName = html_escape($productEntity->getName());
                        $productSlug = $productEntity->getSlug();
                        $productPrice = number_format($productEntity->getPrice(), 2,'.',',');
                        $productCondition = html_escape($productEntity->getCondition());
                        $originalPrice = number_format($productEntity->getOriginalPrice(),2,'.',',');
                        $percentage = $productEntity->getDiscountPercentage();
                        $isPromote = intval($productEntity->getIsPromote());
                        $isFreeShipping = $productEntity->getIsFreeShipping(); 
                        $productImagePath = $value->getProductImagePath();

                        $typeOfView = "product";
                        if(isset($_COOKIE['view'])){ 
                            $typeOfView = ($_COOKIE['view'] == "product-list") ? "product-list" : "product";
                        }
                    ?> 
                        <div class="<?php echo $typeOfView; ?>"> 
                            <a href="<?php echo base_url() . "item/" . $productSlug; ?>">
                                <span class="prod_img_wrapper">
                                    <?php if(($isPromote == 1) && $isFreeShipping):?>
                                        <span class="cd_slide_discount">
                                            <span><?php echo number_format($percentage,0,'.',',');?>%<br>OFF</span>
                                        </span>
                                    <?php endif; ?>
                                
                                    <span class="prod_img_container">
                                            <img alt="<?php echo $productName; ?>" src="<?php echo base_url() . $productImagePath; ?>">
                                    </span>
                                </span>
                            </a>
                            <h3>
                                <a href="<?php echo base_url() . "item/" . $productSlug; ?>">
                                    <?php echo $productName; ?>
                                </a>
                            </h3>
                            <div class="price-cnt">
                                <div class="price"> 
                                    <span>&#8369;</span> <?php echo $productPrice;?>
                                </div>
                              
                                <?php if($percentage && $percentage > 0):?>
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

<script src="<?= base_url() ?>assets/js/src/vendor/jquery.easing.min.js" type="text/javascript"></script> 
<script src="<?= base_url() ?>assets/js/src/vendor/jquery.scrollUp.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/advsearch.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script type="text/javascript">
    var currentUrl = "<?=site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']); ?>";
    var typeView = "<?=(isset($_COOKIE['view']))?$_COOKIE['view']:'product'?>";
</script>