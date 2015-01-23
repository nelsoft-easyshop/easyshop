<?php if(count($homeContent['popularBrands']) > 0): ?>

    <div class="row-fluid row-category row-brand">   
        <div class="container">
            <div class="row row-category row-category-brand">        
                <div class="col-md-12">
                <div class="popular-brands carousel-wrapper">
                        <header class="content-title">
                            <div class="title-bg title-brand">
                                <span class="category-1-icon"></span><h3>POPULAR BRANDS</h3>
                            </div>
                        </header>
                    
                        <div class="carousel-controls div-slider-control slider-brand">
                            <span class="pull-right brand-nav">
                                <a id="popular-brand-prev"><i class="fa fa-angle-left fa-brand-prev"></i></a>
                                <a id="popular-brand-next"><i class="fa fa-angle-right fa-brand-next"></i></a>
                            </span>
                        </div>
                    </div>
                    <div id="brand-items" class="popular-brands owl-carousel"> 
                        <?php foreach($homeContent['popularBrands'] as $brand) : ?>
                        <div class="item">
                            <div class="brand-container">
                                <div class="span-brand">
                                    <a href="/advsrch?q_str=<?php echo html_escape( preg_replace('/[\- ]+/', '+', $brand['brand']->getName()) ); ?>&category=1&seller=&location=&condition=&startprice=&endprice=">
                                        <img class="brand-img" src="<?php echo getAssetsDomain().$brand['image']['directory'] . $brand['image']['file']?>" />
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

