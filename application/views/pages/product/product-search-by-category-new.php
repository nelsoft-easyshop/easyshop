<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome/css/font-awesome.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/easy-icons/easy-icons.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/product-search-new.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/owl.carousel.css" media='screen'>
<link rel="stylesheet" type="text/css" href="/assets/css/jquery.bxslider.css" media='screen' />

<section class="breadcrumbs-bg">
    <div class="container">
        <div class="default-breadcrumbs-container col-md-12 col-sm-12 col-xs-12">
            <ul>
                <li>
                    <a href="/">Home</a>
                </li> 
                <li class="bc-arrow"> 
                        Clothing  &amp; Accessories
                </li>
            </ul>
        </div>
    </div>
</section>

<?php if($categoryHeaderData !== false): ?>
<section class="bg-search-section color-default search-parallax-container">
    <div id="parallax-1" class="search-parallax">
        <?php if(isset($categoryHeaderData['top'])): ?>
        <div id="parallax-3" class="banner-template-1">
            <ul class="top-slider">
                <?php foreach($categoryHeaderData['top']['image'] as $topBanner): ?>
                    <?php if(trim($topBanner['target']['url']) !== '' && trim($topBanner['target']['url']) !== '/'): ?>
                        <a href="<?php echo html_escape($topBanner['target']['url']); ?>" target="<?php echo $topBanner['target']['targetString']; ?>">
                    <?php endif; ?>
                            <li style="background: url(<?php echo getAssetsDomain().'.'.$topBanner['path']; ?> ) center no-repeat; background-size: cover; "></li>
                    <?php if(trim($topBanner['target']['url']) !== '' && trim($topBanner['target']['url']) !== '/'): ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <br>
        <?php endif; ?>
        <?php if(isset($categoryHeaderData['bottom'])): ?>
        <center class="search-slider">
            <center class="search-slider">
                <div class="left-shade">
                </div>
                <div class="right-shade">
                </div>
                <div class="container">
                    <div class="slider1 clear" width="100%">
                        <?php foreach($categoryHeaderData['bottom']['image'] as $bottomBanner): ?>
                            <div class="slide">
                                
                                <?php if(trim($bottomBanner['target']['url']) !== '' && trim($bottomBanner['target']['url']) !== '/'): ?>
                                      <a href="<?php echo html_escape($bottomBanner['target']['url']); ?>" target="<?php echo $bottomBanner['target']['targetString']; ?>">
                                <?php endif; ?>

                                    <img src="<?php echo getAssetsDomain().'.'.html_escape($bottomBanner['path']); ?>">
                                 
                                <?php if(trim($bottomBanner['target']['url']) !== '' && trim($bottomBanner['target']['url']) !== '/'): ?>
                                      </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="clear"></div>
                </div>
            </center>   
        <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<section id="parallax-2" class="bg-search-section color-default">
<br/>
    <div class="container-non-responsive">
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
                                            Men's Clothing (75)
                                        </li>
                                    </a>
                                    <a href="#" class="color-default tab_categories">
                                        <li>
                                            Women's Clothing &amp; Gems (24)
                                        </li>
                                    </a>
                                    <a  href="#" class="color-default tab_categories">
                                        <li>
                                            Men's Shoes (23)
                                        </li>
                                    </a>
                                    <a  href="#" class="color-default tab_categories">
                                        <li>
                                            Women's Clothing (54)
                                        </li>
                                    </a>
                                    <a  href="#" class="color-default tab_categories">
                                        <li>
                                            Men's Accessories (54)
                                        </li>
                                    </a>
                                    <a  href="#" class="color-default tab_categories">
                                        <li>
                                            Women's Accessories (54)
                                        </li>
                                    </a>
                                    <a  href="#" class="color-default tab_categories">
                                        <li>
                                            Babies Clothes (54)
                                        </li>
                                    </a>
                                    <a  href="#" class="color-default tab_categories">
                                        <li>
                                            Unisex Accessories (54)
                                        </li>
                                    </a>
                                    <a  href="#" class="color-default tab_categories">
                                        <li>
                                            Kid's Clothing (54)
                                        </li>
                                    </a>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="panel-group panel-category border-0 panel-filter-product-new" id="filter-panel-container">
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
                                            <option value="0">New</option>
                                            <option value="1">New other (see details)</option>
                                            <option value="2">Manufacturer refurbished</option>
                                            <option value="3">Used</option>
                                            <option value="4">For parts or not working</option>
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
                                    <li>
                                        <p class="p-filter-name">By Brand</p>
                                        <ul class="list-unstyled">
                                            <li class="checkbox">
                                                <label>
                                                    <input type="checkbox"> Pandorra
                                                </label>
                                            </li>
                                            <li class="checkbox">
                                                <label>
                                                    <input type="checkbox"> Omega
                                                </label>
                                            </li>
                                            <li class="checkbox">
                                                <label>
                                                    <input type="checkbox"> Tagheuer
                                                </label>
                                            </li>
                                            <li class="checkbox">
                                                <label>
                                                    <input type="checkbox"> Patek Philippe
                                                </label>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <p class="p-filter-name">By Gender</p>
                                        <ul class="list-unstyled">
                                            <li class="checkbox">
                                                <label>
                                                    <input type="checkbox"> All
                                                </label>
                                            </li>
                                            <li class="checkbox">
                                                <label>
                                                    <input type="checkbox"> Male
                                                </label>
                                            </li>
                                            <li class="checkbox">
                                                <label>
                                                    <input type="checkbox"> Female
                                                </label>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <p class="p-filter-name">By Color</p>
                                        <select id="filter-color" class="select-filter">
                                            <option value="">-- Select Color --</option>
                                            <option value="0">Red</option>
                                            <option value="1">Blue</option>
                                            <option value="2">Green</option>
                                            <option value="3">Yellow</option>
                                            <option value="4">Brown</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xs-9">
                <div class="search-header">
                    <h3>
                        Clothing &amp; Accessories <span class="category-count-item">(2203)</span>
                    </h3>
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
                    <?php
                        for($x=1; $x<=6; $x++){
                    ?>
                    <div class="row" id="section-<?php echo $x?>">
                        <?php
                            for($i=1; $i<=8; $i++){
                        ?>
                        <div class="col-search-item col-xs-3">
                            <div class="search-item-container">
                                <a href="#" class="search-item-link-image">
                                    <div class="search-item-img-container" style="background: url(/assets/images/products/apple-p.jpg) center no-repeat; background-size: cover;">
                                        <div class="search-item-img-container-hover" style="background: url(/assets/images/products/apple-p-h.jpg) center no-repeat; background-size: cover;">
                                            
                                        </div>
                                        <span class="discount-circle-2">76%</span>
                                        <span class="new-circle-2">NEW</span>
                                    </div>
                                </a>
                                <div class="search-item-meta">
                                    <a href="#" class="search-item-name">
                                        breitling-chronometer
                                    </a>
                                    <div class="search-item-price">
                                        <span class="original-price">
                                            <s>P8,000</s>
                                        </span>
                                        <span class="new-price">
                                            P7,500
                                        </span>
                                    </div>
                                 </div>
                                <div class="search-item-actions">
                                    <button class="btn btn-search-add-cart">
                                        <span class="fa icon-cart fa-lg"></span>
                                        Add to cart
                                    </button>
                                    <div class="search-item-seller-cont pull-right">
                                        <img src="/assets/images/img_how-to-buy.png" class="search-item-seller-img" />
                                    </div>
                                </div>
                                <table class="search-item-list-table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <a href="#">
                                                    <div class="search-item-img-container" style="background: url(/assets/images/products/apple-p.jpg) center no-repeat; background-size: cover;">
                                                        <div class="search-item-img-container-hover" style="background: url(/assets/images/products/apple-p-h.jpg) center no-repeat; background-size: cover;">
                                                            
                                                        </div>
                                                        <span class="discount-circle-2">76%</span>
                                                        <span class="new-circle-2">NEW</span>
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="search-item-td-meta">
                                                <a href="#" class="search-item-name">
                                                    breitling-chronometer
                                                </a>
                                                <span class="search-item-description">
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac rutrum augue, at pellentesque est. Proin ullamcorper laoreet dolor. Vestibulum quis placerat enim.
                                                </span>
                                                <div class="divider-gray"></div>
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <div class="search-item-seller-img-list">
                                                            <div class="search-item-seller-cont">
                                                                <img src="/assets/images/img_how-to-buy.png" class="search-item-seller-img" />
                                                            </div>
                                                        </div>
                                                        <a href="" class="search-item-seller-name">
                                                            Seller2DaMax
                                                        </a>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <span class="search-item-shipping-text pull-right">
                                                            <span class="search-item-shipping-label">Shipping : </span>
                                                            <span class="search-item-shipping-data">Free</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="search-item-td-price">
                                                <div class="search-item-price">
                                                    <span class="original-price">
                                                        <s>P8,000</s>
                                                    </span>
                                                    <span class="new-price">
                                                        P7,500
                                                    </span>
                                                </div>
                                                <button class="btn btn-search-add-cart">
                                                    <span class="fa icon-cart fa-lg"></span>
                                                    Add to cart
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="col-search-item col-xs-3">
                            <div class="search-item-container">
                                <a href="#" class="search-item-link-image">
                                    <div class="search-item-img-container" style="background: url(/assets/images/products/samsung-p.jpg) center no-repeat; background-size: cover;">
                                        <div class="search-item-img-container-hover" style="background: url(/assets/images/products/samsung-p-h.jpg) center no-repeat; background-size: cover;">
                                            
                                        </div>
                                        <span class="discount-circle-2">76%</span>
                                        <span class="new-circle-2">NEW</span>
                                    </div>
                                </a>
                                <div class="search-item-meta">
                                    <a href="#" class="search-item-name">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac rutrum augue, at pellentesque est. Proin ullamcorper laoreet dolor
                                    </a>
                                    <div class="search-item-price">
                                        <span class="new-price">
                                            P7,500
                                        </span>
                                    </div>
                                 </div>
                                <div class="search-item-actions">
                                    <button class="btn btn-search-add-cart">
                                        <span class="fa icon-cart fa-lg"></span>
                                        Add to cart
                                    </button>
                                    <div class="search-item-seller-cont pull-right">
                                        <img src="/assets/images/img_how-to-buy.png" class="search-item-seller-img" />
                                    </div>
                                </div>
                                <table class="search-item-list-table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <a href="#">
                                                    <div class="search-item-img-container" style="background: url(/assets/images/products/samsung-p.jpg) center no-repeat; background-size: cover;">
                                                        <div class="search-item-img-container-hover" style="background: url(/assets/images/products/samsung-p-h.jpg) center no-repeat; background-size: cover;">
                                                            
                                                        </div>
                                                        <span class="discount-circle-2">76%</span>
                                                        <span class="new-circle-2">NEW</span>
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="search-item-td-meta">
                                                <a href="#" class="search-item-name">
                                                    Lorem ipsum dolor sit amet, consectetur
                                                </a>
                                                <span class="search-item-description">
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac rutrum augue, at pellentesque est. Proin ullamcorper laoreet dolor. Vestibulum quis placerat enim. Vestibulum at aliquet nibh, fringilla porta nulla. Cras at sem convallis, convallis magna vitae, porta leo. Phasellus suscipit pulvinar tortor, ac gravida felis sodales et.
                                                </span>
                                                <div class="divider-gray"></div>
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <div class="search-item-seller-img-list">
                                                            <div class="search-item-seller-cont">
                                                                <img src="/assets/images/img_how-to-buy.png" class="search-item-seller-img" />
                                                            </div>
                                                        </div>
                                                        <a href="" class="search-item-seller-name">
                                                            Seller2DaMax
                                                        </a>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <span class="search-item-shipping-text pull-right">
                                                            <span class="search-item-shipping-label">Shipping : </span>
                                                            <span class="search-item-shipping-data">Free</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="search-item-td-price">
                                                <div class="search-item-price">
                                                    <span class="original-price">
                                                        <s> </s>
                                                    </span>
                                                    <span class="new-price">
                                                        P7,500
                                                    </span>
                                                </div>
                                                <button class="btn btn-search-add-cart">
                                                    <span class="fa icon-cart fa-lg"></span>
                                                    Add to cart
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                    <?php
                        }
                    ?>
                </div>
                <div id="sticky-pagination">
                    <center>
                        <div class="row">
                            <div class="col-md-12" id="myScrollspy" style="padding: 0px; background: #fff;">
                                <ul class="pagination pagination-items nav" >
                                    <li data-page="1" class="extremes previous">
                                        <a href="#">
                                            <span> &laquo; </span>
                                        </a>
                                    </li>
                                    <li class="active">
                                        <a href="#section-1">
                                            <span>1</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#section-2">
                                            <span>2</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#section-3">
                                            <span>3</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#section-4">
                                            <span>4</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#section-5">
                                            <span>5</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#section-6">
                                            <span>6</span>
                                        </a>
                                    </li>
                                    <li data-page="1" class="extremes next">
                                        <a href="#">
                                            <span> &raquo; </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </center>
                </div>
            </div>
       </div>
    </div>
</section>
<script src="/assets/js/src/vendor/bootstrap.js"></script>
<script src="/assets/js/src/vendor/jquery.sticky-sidebar-scroll.js"></script>
<script src="/assets/js/src/vendor/owl.carousel.min.js"></script>
<script src="/assets/js/src/vendor/jquery.bxslider.min.js"></script>
<script src="/assets/js/src/product-search-by-category.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
