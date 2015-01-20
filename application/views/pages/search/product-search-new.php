<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome/css/font-awesome.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/easy-icons/easy-icons.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/product-search-new.css" media='screen'>

<section class="bg-search-section color-default">
    <br>
    <div class="container-non-responsive">
        <?php if($productCount > 0): ?>
        <div class="row">
            <div class="col-xs-3">
                <div class="panel-group panel-category border-0" id="category">
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
                                    <a href="#" class="color-default tab_categories">
                                        <li>
                                            <span style="display: " class="fa fa-caret-right active-category selected-marker"></span>
                                            Watches (75)
                                        </li>
                                    </a>
                                    <a href="#" class="color-default tab_categories">
                                        <li>
                                            Diamonds &amp; Gems (24)
                                        </li>
                                    </a>
                                    <a  href="#" class="color-default tab_categories">
                                        <li>
                                            Watch, Parts, Tools &amp; Guides (23)
                                        </li>
                                    </a>
                                    <a  href="#" class="color-default tab_categories">
                                        <li>
                                            Engagement &amp; Wedding Jewellery (54)
                                        </li>
                                    </a>
                                    <a href="#" class="color-default tab_categories">
                                        <li>
                                            Fashion Jewellery (103)
                                        </li>
                                    </a>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="panel-group panel-category border-0" id="filter-panel-container">
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
                                            <option value="<?=html_escape($condition);?>"><?=html_escape($condition);?></option>
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
                                                    <input id="filter-lprice" type="text" class="input-filter-price price-field" placeholder="0.00">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">
                                                    to
                                                </td>
                                                <td>
                                                    <input id="filter-lprice" type="text" class="input-filter-price price-field" placeholder="0.00">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">
                                                    &nbsp;
                                                </td>
                                                <td>
                                                    <input id="filter-btn" type="button" class="btn-filter" value="filter price">
                                                </td>
                                            </tr>
                                        </table>
                                    </li>
                                    <?php foreach ($attributes as $attrName => $attrListValue):?>
                                    <li>
                                        <p class="p-filter-name">By <?=html_escape($attrName); ?></p>
                                        <ul class="list-unstyled">
                                            <?php foreach ($attrListValue as $value):?>
                                            <li class="checkbox">
                                                <label>
                                                    <input type="checkbox" <?=(isset($getParameter[strtolower($attrName)]) && strpos($getParameter[strtolower($attrName)],strtolower($value)) !== false)?'checked':'';?> class="checkBox" data-head="<?= html_escape(strtolower($attrName));?>" data-value="<?= html_escape(strtolower($value)); ?>" >
                                                    <?= html_escape(ucfirst($value));?>
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
            
            <div class="col-xs-9">
                <div class="search-header">
                    <h5>
                    <strong><?=$productCount; ?></strong>
                    results for
                    <strong><?=html_escape($string); ?></strong>
                    </h5>
                </div>
                <div class="div-product-view-option">
                    <table class="p-view color-default pull-left">
                        <tbody>
                            <tr>
                                <td class="td-view p-view2 color-default">VIEW STYLE:</td>
                                <td class="td-view" style="padding-top: 3px;">
                                    <span class="gv fa fa-icon-view-grid fa-2x icon-view icon-grid active-view"></span>
                                    <span class="lv fa fa-icon-view-list fa-2x icon-view icon-list"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="vendor-select-con">
                    <select class="sort_select form-select-default color-default pull-right">
                        <option value="2">Default Sorting</option>
                        <option value="1">Popularity</option>
                        <option value="3">Hot</option>
                    </select>
                    <div class="clear"></div>
                </div>
                
                <div class="search-results-container">
                    <div class="row">
                        <?=$productView; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!--DISPLAY WHEN SEARCH RESULTS IS ZERO-->
        
        <div class="row">
            <div class="col-md-12">
                <h3 class="no-search-resul-title">
                    Your search for <strong><?=html_escape($string); ?></strong> did not return any results.
                </h3>
            </div>
        </div>
        <br/>
        <br/>
        <div class="row">
            <div class="col-md-7">
                <div id="search-tips-container">
                    <h4>Some helpful tips</h4>
                    <ul class="list-search-tips">
                        <li>Check your spelling for typing errors, eg. Jakcet instead of Jacket.</li>
                        <li>Try searching a similar search term or synonym.</li>
                        <li>Try searching just one keyword.</li>
                        <li>Try searching more general terms - you can then filter the search results.</li>
                    </ul>
                    <br/>
                    <br/>
                    <h4>Search again</h4>
                    <form class="nav-searchbar-inner" accept-charset="utf-8" role="search" name="site-search" method="get" action="/search/search.html" id="nav-searchbar">
                        <div class="col-xs-12 col-sm-9 col-md-9 form-404-input">
                            <div class="row">
                                <input type="text" name="q_str" class="ui-form-control input-404" placeholder="Enter keyword here">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-3">
                            <input type="submit" value="search" class="btn btn-default-4 btn-no-br submit-404">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-5">
                <h4>Browse our categories here:</h4>
                <ul class="list-category-search">
                    <li>
                        <a href="#" class="search-category-link">
                            <span class="search-category-icon" style="background: url(/assets/images/categories/icon-categories/clothing-accessories.png);">
                                
                            </span>
                            <span class="search-category-name">Clothing &amp; Accessories</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="search-category-link">
                            <span class="search-category-icon" style="background: url(/assets/images/categories/icon-categories/electronics-gadgets.png);">
                                
                            </span>
                            <span class="search-category-name">Electronics &amp; Gadgets</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="search-category-link">
                            <span class="search-category-icon" style="background: url(/assets/images/categories/icon-categories/clothing-accessories.png);">
                                
                            </span>
                            <span class="search-category-name">Clothing &amp; Accessories</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="search-category-link">
                            <span class="search-category-icon" style="background: url(/assets/images/categories/icon-categories/electronics-gadgets.png);">
                                
                            </span>
                            <span class="search-category-name">Electronics &amp; Gadgets</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="search-category-link">
                            <span class="search-category-icon" style="background: url(/assets/images/categories/icon-categories/clothing-accessories.png);">
                                
                            </span>
                            <span class="search-category-name">Clothing &amp; Accessories</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="search-category-link">
                            <span class="search-category-icon" style="background: url(/assets/images/categories/icon-categories/electronics-gadgets.png);">
                                
                            </span>
                            <span class="search-category-name">Electronics &amp; Gadgets</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="search-category-link">
                            <span class="search-category-icon" style="background: url(/assets/images/categories/icon-categories/clothing-accessories.png);">
                                
                            </span>
                            <span class="search-category-name">Clothing &amp; Accessories</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="search-category-link">
                            <span class="search-category-icon" style="background: url(/assets/images/categories/icon-categories/electronics-gadgets.png);">
                                
                            </span>
                            <span class="search-category-name">Electronics &amp; Gadgets</span>
                        </a>
                    </li>
                    
                </ul>
            </div>
        </div>
        <?php endif; ?>
        <!---END-->
    </div>
</section>
<script src="/assets/js/src/vendor/jquery.sticky-sidebar-scroll.js"></script>
<script src="/assets/js/src/product-search.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
