<meta name="viewport" content="width=device-width, maximum-scale=1.0">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="<?= base_url() ?>assets/css/product_search_category_responsive.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="<?= base_url() ?>assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>

<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/> 

<link rel="canonical" href="<?php echo base_url()?>category/<?=$categorySlug?>"/>

<section style="color-gray display-when-desktop">
    <div class="container container-responsive">
        <div class="row">
            <div class="col-md-12">
                <section class="top_margin product-page-section display-when-desktop">
                    <div class="wrapper">
                        <div class="prod_categories">
                            <div class="nav_title">Categories <img src="/assets/images/img_arrow_down.png" class="drop-arrow"></div>
                            <?php echo $category_navigation; ?> 
                        </div> 
                        <div class="prod_cat_nav">
                            <div class="category_nav">
                                <ul>
                                <?php foreach($parentCategory as $catKey => $catValue): ?>
                                    <li class="<?=($catValue->getIdCat() == $breadcrumbs[0]['idCat'])?'active':'';?>">
                                        <a href="<?=base_url()?>category/<?=$catValue->getSlug();?>">
                                            <?=html_escape($catValue->getName());?>
                                        </a>
                                    </li>
                                <?php endforeach;?>
                                </ul>
                                <span class="span_bg prod_cat_drop"></span>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="bread_crumbs">
                            <ul>
                                <li class=""><a href="<?= base_url() ?>home">Home</a></li>
                                <?php foreach ($breadcrumbs as $crumbs): ?>
                                <li>
                                    <a href="<?= base_url() ?>category/<?php echo $crumbs['slug'] ?>">
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
            <div class="col-md-2 row-main">
                <div class="left_attribute"> 
                    <h3>Price</h3>
                    <input type="text" id="price1" class="priceField" value="<?=($this->input->get('startprice')?$this->input->get('startprice'):'')?>" maxlength=9 size=6>
                    to
                    <input type="text" id="price2" class="priceField" value="<?=($this->input->get('startprice')?$this->input->get('endprice'):'')?>" maxlength=9 size=6> 
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
                </div>
            </div>
            <div class="col-md-10 row-main" style="border: transparent #fff 1px; padding: 0px !important;">
                <?php if(count($subCategoryList) !== 0):?>
                    <div class="filters">           
                        <h2 class="margin-0">Categories:</h2>    
                        <div class="jcarousel category_carousel cc2_wrapper">
                            <div class="cc2">
                                <?php foreach ($subCategoryList as $subCatKey => $subCatValue): ?>
                                <div>
                                    <a class="cc2_title color-gray" href="<?=base_url()?>category/<?=$subCatValue['slug'];?>">
                                        <span><?php echo html_escape($subCatKey);?></span>
                                    </a>
                                    <?php if(count($subCatValue['item'])>0): ?>
                                    <span class="cat_carousel_img_con"><span class="cat_carousel_img"><img src="<?= base_url().$subCatValue['item'][0]['productImagePath']; ?>"></span></span><br />
                                    <div class="cc2_prod_name">
                                        <a href="<?php echo base_url()."item/".$subCatValue['item'][0]['slug']; ?>" title="<?PHP echo $subCatValue['item'][0]['name']; ?>">
                                            <span class="color-gray font-12">
                                            <?php echo html_escape($subCatValue['item'][0]['name']); ?>
                                            </span>
                                        </a>
                                    </div>
                                    <span class="recommended_product_price">PHP <?php echo number_format($subCatValue['item'][0]['price'],2,'.',',');?></span>
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
                    <div id="list" class="list "></div>
                    <div id="grid" class="grid grid-active"></div>
                </div>
                <div class="clear"></div>
                <div id="product_content" class="margin-left-42">
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
                 <div class="loading_products" style="display: inline-block;text-align: center;width: 100%;"></div>
            </div>
        </div>
    </div>
</section>
<div class="display-when-mobile-1024">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-group " id="categories">
                    <div class="panel panel-default panel-category no-border border-0">
                        <div class="panel-heading panel-category-heading no-border">
                            <h4 class="panel-title panel-title-category">
                                Categories
                                <a data-toggle="collapse" data-parent="#categories" href="#categories-body">
                                    <img class="pull-right" src="<?=base_url()?>assets/images/img_arrow_down.png">
                                </a>
                            </h4>
                        </div>
                        <div id="categories-body" class="panel-collapse collapse">
                            <div class="panel-body-category">
                                <ul class="list-unstyled">
                                    <?php foreach ($parentCategory as $key => $value): ?>
                                    <li class="list-category"><a href="<?=base_url().'category/'.$value->getSlug(); ?>"><?=$value->getName();?></a></li> 
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bread_crumbs_m">
                    <ul style="margin-bottom: 10px;">
                        <li class="li_home" ><a href="<?=base_url()?>home" style="color: #f18200;">Home</a></li>
                        <?php foreach($breadcrumbs as $crumbs): ?>
                        <li> <a href="<?=base_url()?>category/<?php echo $crumbs['slug']?>" style="color: #f18200;"> <?php echo html_escape($crumbs['name']);?> </a> </li>
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
                    <?php foreach ($products as $key => $value): ?>  
                    <h3></h3>
                    <div class="responsive-product panel panel-default no-border panel-items">
                        <table width="100%" class="">
                            <tr>
                                <td width="90px" class="v-align-top">
                                    <span class="prod_img_container">
                                        <img alt="<?php echo html_escape($value['name']); ?>" src="<?php echo base_url() . $value['productImagePath']; ?>">
                                    </span>
                                </td>
                                <td class="v-align-top">
                                    <p class="p-item-name"> 
                                        <a class="a-item-name" href="<?php echo base_url() . "item/" . $value['slug']; ?>"> 
                                            <?=(strlen(html_escape($value['name'])>35))?substr_replace(html_escape($value['name']), "...", 35):html_escape($value['name']);?>
                                        </a>
                                    </p>
                                    <p class="p-item-price"> 
                                        PHP <?php echo number_format($value['price'], 2);?>
                                    </p>
                                    <?php if(isset($value['percentage']) && $value['percentage'] > 0):?>
                                        <p class="p-item-discount">
                                            <span class="original_price">
                                                &#8369; <?php echo number_format($value['originalPrice'],2,'.',','); ?>
                                            </span>
                                            <span style="height: 20px;">
                                                |&nbsp; <strong><?PHP echo number_format($value['percentage'],0,'.',',');?>%OFF</strong>
                                            </span>
                                        </p>
                                    <?php endif; ?>
                                        
                                    <p class="p-item-condition">
                                        Condition:
                                        <strong>
                                           <?php echo ($value['isFreeShipping'])? es_string_limit(html_escape($value['condition']),15) : html_escape($value['condition']);?>
                                        </strong>
                                    </p> 
                                </td>
                                <td width="30px" class=" v-align-top">
                                    <?php if($value['isFreeShipping'] <= 0): ?>
                                        <span style="float:right;"><span class="span_bg img_free_shipping"></span></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php endforeach; ?>
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
                        <a href="<?php echo base_url()."category/".$subCatValue['slug']; ?>">
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
                <h3>Price</h3>
                <input type="text" id="rprice1" class="priceField" value="<?=($this->input->get('startprice')?$this->input->get('startprice'):'')?>" maxlength=9 size=6>
                to
                <input type="text" id="rprice2" class="priceField" value="<?=($this->input->get('startprice')?$this->input->get('endprice'):'')?>" maxlength=9 size=6> 
                <input class="rprice" type="button" value=">>"/>

                <?php if(count($products) > 0): ?>
                    <?php foreach ($attributes as $attrName => $attrListValue):?>
                    <h3 class="title h3-filter"><?=$attrName?></h3> 
                        <?php foreach ($attrListValue as $key => $value):?>
                            <div class="span-filter pull-left">
                                <a class="cbx" data-head="<?=strtolower($attrName)?>" data-value="<?=strtolower($value)?>" >
                                    <input type="checkbox" <?=(strpos($this->input->get(strtolower($attrName)),strtolower($value)) !== false)?'checked':'';?> class="checkBox" data-head="<?=strtolower($attrName)?>" data-value="<?=strtolower($value)?>" >
                                    <label for="cbx"><?=ucfirst($value);?></label>
                                </a>
                            </div>
                        <?php endforeach; ?>
                        <div class="clear"></div> 
                    <?php endforeach; ?>  
                <?php endif; ?>
            </div>
        </div> 
    </div>
</div> 

<script src="<?= base_url() ?>assets/js/src/bootstrap.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/vendor/jquery.easing.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/vendor/jquery.scrollUp.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $.scrollUp({
                scrollName: 'scrollUp', // Element ID
                scrollDistance: 300, // Distance from top/bottom before showing element (px)
                scrollFrom: 'top', // 'top' or 'bottom'
                scrollSpeed: 300, // Speed back to top (ms)
                easingType: 'linear', // Scroll to top easing (see http://easings.net/)
                animation: 'fade', // Fade, slide, none
                animationInSpeed: 200, // Animation in speed (ms)
                animationOutSpeed: 200, // Animation out speed (ms)
                scrollText: 'Scroll to top', // Text for element, can contain HTML
                scrollTitle: false, // Set a custom <a> title if required. Defaults to scrollText
                scrollImg: false, // Set true to use image
                activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
                zIndex: 2147483647 // Z-Index for the overlay
            });
});

</script>
<script src="<?= base_url() ?>assets/js/src/vendor/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/categorynavigation.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>

<script src="<?= base_url() ?>assets/js/src/advsearch.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
<script type="text/javascript">
    var currentUrl = "<?=site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']); ?>";  
    var currentQueryString = "<?=$_SERVER['QUERY_STRING']; ?>"; 
    var typeView = "<?=$_COOKIE['view']?>";
</script>
 
<script type="text/javascript">
    (function($) {
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

<script type="text/javascript">
    var p = $('.cc2_prod_name span');
    var divh = $('.cc2_prod_name').height();
    while ($(p).outerHeight()>divh) {
        $(p).text(function (index, text) {
            return text.replace(/\W*\s(\S)*$/, '...');
        });
    }
</script>