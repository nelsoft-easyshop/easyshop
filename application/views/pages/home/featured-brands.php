<div class="row-fluid row-category row-brand">   
    <div class="container">
        <div class="row row-category">        
            <div class="col-md-12">
               <div class="popular-brands carousel-wrapper">
                    <header class="content-title">
                        <div class="title-bg title-brand">
                            <span class="category-1-icon"></span><h3><b>POPULAR BRANDS</b></h3>
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
                    <?php
                        for($i=1; $i<=14; $i++){
                    ?>
                    <div class="item">
                        <div class="brand-container">
                            <div class="span-brand">
                                <a href="#">
                                    <img class="brand-img" src="/assets/images/products/brands/brand-<?php echo $i;?>.png" />
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>