<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/owl.carousel.css" media='screen'>
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.bxslider.css" media='screen' />
    <link rel="stylesheet" type="text/css" href="/assets/css/product-search-new.css" media='screen'>
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.product-search-by-category.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>

<div class="loader">
    <img src="<?php echo getAssetsDomain(); ?>assets/images/es-loader-3.gif"/>
    <br/>
    <br/>
    <span>Loading items...</span>
</div>

<section class="breadcrumbs-bg">
    <div class="container">
        <div class="default-breadcrumbs-container col-md-12 col-sm-12 col-xs-12">
            <ul>
                <li>
                    <a href="/">Home</a>
                </li> 
                <?php foreach ($breadCrumbs as $crumbs): ?>
                <li class="bc-arrow"> 
                    <a href="/category/<?=$crumbs->getSlug(); ?>">
                        <?=html_escape($crumbs->getName());?>
                    </a>
                </li> 
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>


<?php if($productCount > 0): ?>
    <?php if($categoryHeaderData): ?>
    <section class=" color-default search-parallax-container <?php if((isset($categoryHeaderData['top'])&&isset($categoryHeaderData['bottom']))||(isset($categoryHeaderData['top'])&&!isset($categoryHeaderData['bottom']))):?>search-parallax-container-mobile<?php endif; ?>" style="<?php if(!isset($categoryHeaderData['top'])&&isset($categoryHeaderData['bottom'])):?>height: 220px;<?php endif; ?><?php if(isset($categoryHeaderData['top'])&&!isset($categoryHeaderData['bottom'])):?>height: auto;<?php endif; ?>">
        <div id="parallax-1" class="search-parallax">
            <?php if(isset($categoryHeaderData['top'])): ?>
             <div id="parallax-4" style="margin-top: -10px;">
               
            </div>
            <?php endif;?>
            <div class="banner-template-2">
                <?php if(isset($categoryHeaderData['top'])): ?>
                <ul class="top-slider">
                    <?php foreach($categoryHeaderData['top']['image'] as $topBanner): ?>
                        
                        <?php if(trim($topBanner['target']['url']) !== ''): ?>
                            <a href="<?php echo html_escape($topBanner['target']['url']); ?>" target="<?php echo $topBanner['target']['targetString']; ?>">
                        <?php endif; ?>
                                <li class="<?php if(isset($categoryHeaderData['top'])&&!isset($categoryHeaderData['bottom'])):?>top-slider-item<?php endif;?>" style="background: url(<?php echo getAssetsDomain().'.'.$topBanner['path']; ?>) center no-repeat; background-size: cover;">
                                    <img class="top-slider-img" src="<?php echo getAssetsDomain().'.'.$topBanner['path']; ?>" style="<?php if(isset($categoryHeaderData['top'])&&!isset($categoryHeaderData['bottom'])):?>margin-top: -52px;<?php endif;?><?php if(isset($categoryHeaderData['top'])&&isset($categoryHeaderData['bottom'])):?>margin-top: -43px;<?php endif;?>"/>
                                </li>
                        <?php if(trim($topBanner['target']['url']) !== ''): ?>
                            </a>
                        <?php endif; ?>
                  
                    <?php endforeach; ?>
                </ul>

                <?php endif; ?>
            </div>
            <div class="mobile-banner">
                <?php if(isset($categoryHeaderData['top'])): ?>
                    <?php if(trim($topBanner['target']['url']) !== ''): ?>
                        <a href="<?php echo html_escape($topBanner['target']['url']); ?>" target="<?php echo $topBanner['target']['targetString']; ?>">
                    <?php endif; ?>
                    <img class="img-responsive" src="<?php echo getAssetsDomain().'.'.$topBanner['path']; ?>"/>
                    <?php if(trim($topBanner['target']['url']) !== ''): ?>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php if(isset($categoryHeaderData['bottom'])): ?>
            <center class="search-slider" style="<?php if(!isset($categoryHeaderData['top'])&&isset($categoryHeaderData['bottom'])):?>margin-top: 0px;<?php endif;?> <?php if(isset($categoryHeaderData['top'])&&isset($categoryHeaderData['bottom'])):?>margin-top: 0px;<?php endif;?>">
                <center class="search-slider">
                    <div class="left-shade">
                    </div>
                    <div class="right-shade">
                    </div>
                    <div class="container">
                        <ul class="slider1 <?php if(!isset($categoryHeaderData['top'])&&isset($categoryHeaderData['bottom'])):?>slider-bottom<?php endif; ?> clear" width="100%">
                            <?php foreach($categoryHeaderData['bottom']['image'] as $bottomBanner): ?>
                                <li class="slide">
                                    
                                    <?php if(trim($bottomBanner['target']['url']) !== ''): ?>
                                          <a href="<?php echo html_escape($bottomBanner['target']['url']); ?>" target="<?php echo $bottomBanner['target']['targetString']; ?>">
                                    <?php endif; ?> 

                                        <img src="<?php echo getAssetsDomain().'.'.html_escape($bottomBanner['path']); ?>">
                                     
                                    <?php if(trim($bottomBanner['target']['url']) !== ''): ?>
                                          </a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clear"></div>
                    </div>
                </center>
            </center>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <section id="parallax-2" class="bg-search-section color-default">
    <br/>
        <div class="container">
            <div class="row">
                <div class="col-xs-3 search-left-wing" style="position: relative;">

                    <?php if(count($categories) > 0): ?>
                    <div class="panel-group panel-category border-0" id="category" style="position: relative;">
                        <div class="panel panel-default panel-left-wing border-0 no-padding">
                            <div class="panel-heading border-0 panel-category-heading" id="cat-header">
                                <h4 class="panel-title">
                                    <a id="toggle-cat" class="a-category" data-parent="#category">
                                        SUB-CATEGORIES
                                    </a>
                                </h4>
                            </div>
                            <div id="category-list" class="panel-collapse collapse in">
                                <div class="panel-body no-padding">
                                    <ul class="list-unstyled list-category">
                                        <?php foreach ($categories as $category): ?>
                                            <a href="/category/<?=$category->getSlug();?>" class="color-default tab_categories">
                                                <li>
                                                    <?=html_escape($category->getName());?> 
                                                </li>
                                            </a>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="panel-group panel-category panel-filter-search-cont border-0 container-filter" style="position: relative;" id="filter-panel-container">
                        <div class="panel panel-default panel-left-wing border-0 no-padding">
                            <div class="panel-heading border-0 panel-category-heading" id="cat-header">
                                <h4 class="panel-title">
                                    <a id="toggle-cat" class="a-category" data-parent="#category">
                                        FILTER PRODUCTS
                                    </a>
                                </h4>
                            </div>
                            <div id="category-list" class="panel-collapse collapse in">
                                <div class="panel-body no-padding">
                                    <ul class="list-unstyled list-filter-search">
                                        <li>
                                            <p class="p-filter-name">By Condition</p>
                                            <select id="filter-condition" class="select-filter">
                                                <option value="">-- Select Condition --</option>
                                                <?php foreach ($availableCondition as $condition): ?>
                                                    <option value="<?=html_escape($condition);?>" <?=(isset($getParameter['condition']) && strtolower($condition) === strtolower($getParameter['condition'])) ? 'selected="true"' : '';?> >
                                                        <?=html_escape($condition);?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </li>
                                        <li>
                                            <p class="p-filter-name">By Price</p>
                                            <table width="100%">
                                                <tr>
                                                    <td align="right">
                                                        from
                                                    </td>
                                                    <td>
                                                        <input value="<?=isset($getParameter['startprice']) ? number_format( (float)$getParameter['startprice'], 2) : ''; ?>" id="filter-from-price" type="text" class="input-filter-price price-field" placeholder="0.00">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="right">
                                                        to
                                                    </td>
                                                    <td>
                                                        <input value="<?=isset($getParameter['startprice']) ? number_format( (float)$getParameter['endprice'], 2) : ''; ?>" id="filter-to-price" type="text" class="input-filter-price price-field" placeholder="0.00">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="right">
                                                        &nbsp;
                                                    </td>
                                                    <td>
                                                        <input id="filter-btn" type="button" class="btn-filter btn-filter-price" value="filter price">
                                                    </td>
                                                </tr>
                                            </table>
                                        </li>
                                        <?php foreach ($attributes as $attrName => $attrListValue):?>
                                        <li class="meo">
                                            <p class="p-filter-name">By <?=html_escape(ucfirst(strtolower($attrName))); ?></p>
                                            <ul class="list-unstyled">
                                                <?php foreach ($attrListValue as $value):?>
                                                    <li class="checkbox">
                                                        <label>
                                                            <input type="checkbox" <?=(isset($getParameter[strtolower($attrName)]) && strpos($getParameter[strtolower($attrName)],strtolower($value)) !== false)?'checked':'';?> class="checkBox cbx" data-head="<?= html_escape(strtolower($attrName));?>" data-value="<?= html_escape(strtolower($value)); ?>" >
                                                            <?= html_escape(strtoupper($value));?>
                                                        </label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-9">
                    <div class="search-header">
                        <h3>
                            <?=html_escape($categoryName);?> <span class="category-count-item">(<?=$productCount; ?>)</span>
                        </h3>
                    </div>
                    <div class="div-product-view-option">
                        <table class="p-view color-default pull-left">
                            <tbody>
                                <tr>
                                    <td class="td-view p-view2 color-default">VIEW STYLE:</td>
                                    <td class="td-view" style="padding-top: 3px;">
                                        <span class="gv fa fa-icon-view-grid fa-2x icon-view icon-grid <?=$isListView ? '' : 'active-view'?>"></span>
                                        <span class="lv fa fa-icon-view-list fa-2x icon-view icon-list <?=$isListView ? 'active-view' : ''?>"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="vendor-select-con">
                        <select id="filter-sort" name="sort" class="sort_select form-select-default color-default pull-right">
                            <option value="">Default Sorting</option>
                            <option value="<?=strtolower(\EasyShop\Entities\EsProduct::SEARCH_SORT_POPULAR); ?>" <?=(isset($getParameter['sortby']) && strtolower(\EasyShop\Entities\EsProduct::SEARCH_SORT_POPULAR) === strtolower($getParameter['sortby'])) ? 'selected="true"' : '';?> >Popularity</option>
                            <option value="<?=strtolower(\EasyShop\Entities\EsProduct::SEARCH_SORT_HOT); ?>" <?=(isset($getParameter['sortby']) && strtolower(\EasyShop\Entities\EsProduct::SEARCH_SORT_HOT) === strtolower($getParameter['sortby'])) ? 'selected="true"' : '';?> >Hot</option>
                        </select>
                        <div class="clear"></div>
                    </div>
                    
                    <div class="search-results-container <?=$isListView ? 'list-search' : ''?>">
                        <?=$productView; ?> 
                        <div class="group-container row loading-row" data-id="2" id="page-2"></div>
                    </div> 
                    
                    <div id="sticky-pagination">
                        <?php if($productCount > 0): ?>
                        <center>
                            <div class="row">
                                <div class="col-md-12" class="scrollSpyPage" id="myScrollspy" style="padding: 0px; background: #fff; display: none; "> 
                                    <?=$pagination; ?> 
                                </div> 
                            </div>
                            <div id="simplePagination" class="scrollSpyPage">
                                <?=$pagination; ?>
                            </div>
                        </center>
                        <?php endif; ?>
                    </div>
                    
                </div>
           </div>
        </div>
    </section>
    <div class="mobile-left-wing">
        <div class="row row-left-wing">
            <a href="#">
                <div class="col-xs-6 col-categories">
                    Sub-Categories
                </div>
                
            </a>
            <a href="">
                <div class="col-xs-6 col-filter">
                    Filter
                </div>
            </a>
        </div>
    </div>
    <div class="filter-modal">
        <h1>Filter Products</h1>
    </div>
    <div class="category-modal">
        <h1>Sub-Categories</h1>
        <ul class="list-unstyled list-category">
            <?php foreach ($categories as $category): ?>
                <a href="/category/<?=$category->getSlug();?>" class="color-default tab_categories">
                    <li>
                        <?=html_escape($category->getName());?> 
                    </li>
                </a>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
<!-- Display for non-item category-->
    <section class="bg-search-section color-default">
        <div class="container">
            <br/><br/>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="no-search-resul-title">
                        There are no item to display for <strong><?=html_escape($categoryName);?></strong> category.
                    </h3>
                </div>
            </div>
            <br/>
            <br/>
            <div class="row">
                <div class="col-md-12">
                    <h4>Browse our categories here:</h4>
                    <div class="panel-group panel-group-cat" id="accordion" role="tablist" aria-multiselectable="true">
                        
                        <?php foreach ($categories as $key => $category): ?>
                        <div class="panel panel-default panel-cat no-radius">
                            <div class="panel-heading no-radius panel-cat-head" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#cat-<?=$key;?>" aria-expanded="true" aria-controls="collapseOne">
                                        <span class="search-category-icon" style="background: url(/assets/images/categories/icon-categories/<?=$category->getSlug();?>.png );"></span>
                                        <?=html_escape($category->getName());?> 
                                        <span class="fa panel-stat fa-lg pull-right"></span>
                                    </a>
                                </h4>
                            </div>
                            <div id="cat-<?=$key;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body no-radius panel-cat-sub">
                                    <div class="row"> 
                                        <?php foreach ($category->nextCategory as $value): ?> 
                                            <div class="col-md-3">
                                                <a href="/category/<?=$value->getSlug();?> "><?=html_escape($value->getName());?> </a>
                                            </div>
                                        <?php endforeach; ?> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<!--END-->

<div id="hidden-elements">
    <input type="hidden" id="hidden-queryString" value="<?=$_SERVER['QUERY_STRING'];?>" />
    <input type="hidden" id="hidden-currentUrl" value="<?=site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']); ?>" />
    <input type="hidden" id="hidden-typeView" value="<?=$isListView ? 'list' : 'grid'; ?>" />
    <input type="hidden" id="hidden-emptySearch" value="<?=(isset($products))?"false":"";?>" />
    <input type="hidden" id="hidden-loadUrl" value="/cat/more/<?=$categorySlug . '?' . $_SERVER['QUERY_STRING']; ?>" />
    <input type="hidden" id="hidden-totalPage" value="<?=$totalPage; ?>" />
    <input type="hidden" id="hidden-segment" value="<?=$categorySlug; ?>" />
</div>

<div id="div-holder" style="display:none">
<?=$productView; ?> 
</div>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script src="/assets/js/src/vendor/bower_components/bootstrap.js"></script>
    <script src='/assets/js/src/vendor/jquery.simplemodal.js' type='text/javascript'></script>
    <script src="/assets/js/src/vendor/jquery.sticky-sidebar-scroll.js"></script>
    <script src="/assets/js/src/vendor/owl.carousel.min.js"></script>
    <script src="/assets/js/src/vendor/jquery.bxslider.min.js"></script>
    <script src="/assets/js/src/easyshop.simplePagination.js"></script>
    <script src="/assets/js/src/product-search-by-category.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
    <script src="/assets/js/src/product-search.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.product-search-by-category-new.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>
