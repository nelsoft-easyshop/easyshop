
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>"   media="screen"/>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" media="screen"/>
<div class="clear"></div>

<section class="top_margin">
    <div class="wrapper">
        <div class="prod_categories">
            <div class="nav_title">Categories <img src="<?=base_url()?>assets/images/img_arrow_down.png"></div>
            <?php echo $category_navigation; ?> 
        </div>
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
<div class="wrapper" id="main_search_container">
    <div class="left_attribute">
        <?php if(count($products) > 0): ?>
            <h3>Price</h3>
            <input type="text" id="price1" value="<?=($this->input->get('startprice')?$this->input->get('startprice'):'')?>" maxlength=9 size=6>
            to
            <input type="text" id="price2" value="<?=($this->input->get('startprice')?$this->input->get('endprice'):'')?>" maxlength=9 size=6> 
            <input class="price" type="button" value=">>"/>

            <?php foreach ($attributes as $attrName => $attrListValue):?>
            <h3><?=$attrName?></h3>
                <ul>
                <?php foreach ($attrListValue as $key => $value):?>
                    <li style="border:0px">
                        <a class="cbx" data-head="<?=strtolower($attrName)?>" data-value="<?=strtolower($value)?>" >
                            <input type="checkbox" <?=(strpos($this->input->get(strtolower($attrName)),strtolower($value)) !== false)?'checked':'';?> class="checkBox" data-head="<?=strtolower($attrName)?>" data-value="<?=strtolower($value)?>" >
                            <label for="cbx"><?=ucfirst($value);?></label>
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
        <?php if(count($products) <= 0): ?>
            <div style='margin-bottom: 100px;'>
             <span style='font-size:15px;'> Your search for <span style='font-weight:bold'><?php echo html_escape($string);?></span> did not return any results. </span>
            </div>
        <?php else: ?>
            <div class="adv_ctr">
                <strong style="font-size:14px"><?php echo number_format(count($products));?></strong> result found for <strong><?php echo html_escape($string);?></strong>
            </div>
            <div id="list" class="list "></div>
            <div id="grid" class="grid grid-active"></div>
            <div class="clear"></div>

            <div id="product_content">
            <?php 
            foreach ($products as $key => $value):
                  $typeOfView = "product";
                  if(isset($_COOKIE['view'])){ 
                      $typeOfView = ($_COOKIE['view'] == "product-list") ? "product-list" : "product";
                  }
            ?> 
                <div class="<?php echo $typeOfView; ?>"> 
                    <a href="<?php echo base_url() . "item/" . $value['slug']; ?>">
                        <span class="prod_img_wrapper">
                            <?php if((intval($value['isPromote']) == 1) && isset($value['percentage']) && $value['percentage'] > 0):?>
                                <span class="cd_slide_discount">
                                    <span><?php echo number_format($value['percentage'],0,'.',',');?>%<br>OFF</span>
                                </span>
                            <?php endif; ?>
                        
                            <span class="prod_img_container">
                                    <img alt="<?php echo html_escape($value['name']); ?>" src="<?php echo base_url() . $value['productImagePath']; ?>">
                            </span>
                        </span>
                    </a>
                    <h3>
                        <a href="<?php echo base_url() . "item/" . $value['slug']; ?>">
                            <?php echo html_escape($value['name']); ?>
                        </a>
                    </h3>
                    <div class="price-cnt">
                        <div class="price"> 
                            <span>&#8369;</span> <?php echo number_format($value['price'], 2);?>
                        </div>
                      
                        <?php if(isset($value['percentage']) && $value['percentage'] > 0):?>
                        <div>
                            <span class="original_price">
                                &#8369; <?php echo number_format($value['originalPrice'],2,'.',','); ?>
                            </span>
                            <span style="height: 20px;">
                                |&nbsp; <strong><?PHP echo number_format($value['percentage'],0,'.',',');?>%OFF</strong>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                   <div class="product_info_bottom">
                        <div>
                            Condition:
                            <strong>
                               <?php echo ($value['isFreeShipping'])? es_string_limit(html_escape($value['condition']),15) : html_escape($value['condition']);?>
                            </strong>
                        </div>
                        <?php if($value['isFreeShipping'] <= 0): ?>
                            <span style="float:right;"><span class="span_bg img_free_shipping"></span></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="<?= base_url() ?>assets/js/src/advsearch.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script type="text/javascript">
    var currentUrl = "<?=site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']); ?>"; 
    var loadUrl = "<?=base_url() . 'search_more/'; ?>"; 
    var currentQueryString = "<?=$_SERVER['QUERY_STRING']; ?>"; 
    var typeView = "<?=$_COOKIE['view']?>";
</script>
 