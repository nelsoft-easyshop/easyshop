<link rel="stylesheet" type="text/css" href="/assets/css/owl.carousel.css" media='screen'>
<div class="container" id="rec">
    <div class="prod-detail-main">
        <div class="div-rec-title">
            <p class="p-rec-title">Recommended</p>
            <span class="span-rec-nav">
                <span class="span-see-all">
                    <a href="#">see all</a>
                </span>
                <a class="prev">
                    <i class="fa fa-angle-left span-nav-prev"></i>
                </a>
                <a class="next">
                    <i class="fa fa-angle-right span-nav-next"></i>
                </a>
            </span>
        </div>
         
        <div id="recommended" class="owl-carousel owl-theme">
            <?php
                for($i=0; $i<=3; $i++){
            ?>
            <div class="item">
                <a href="#">
                    <div class="div-rec-product-image">
                        <center>
                            <span class="span-me">
                                <img src="/assets/images/products/nikon-logo.png" class="img-rec-product">
                            </span>
                        </center>
                    </div>
                </a>
                <span class="span-circle-new">NEW</span>
                <span class="span-circle-discount">100%</span>
                <div class="clear"></div>
                <a href="#">
                    <p class="p-rec-product-name">
                        Nikon D300
                    </p>
                </a>
                <p class="p-rec-product-price">
                    <s>P200.00</s> <span>P150.00</span>
                </p>
                <table width="100%">
                    <tbody>
                        <tr>
                            <td>
                                <a class="btn btn-default-1 btn-add-cart" target="_blank" href="/item/boom">
                                    <span class="icon-cart"></span> ADD TO CART
                                </a>
                            </td>
                            <td class="td-logo-store" align="right">
                                <a href="#">
                                    <span>
                                        <div class="store-logo-container ">
                                            <div class="span-store-logo">
                                                    <img src="/assets/images/products/nikon-logo.png" class="store-logo">
                                            </div>
                                        </div>
                                    </span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="item">
                <a href="#">
                    <div class="div-rec-product-image">
                        <center>
                            <span class="span-me">
                                <img src="/assets/images/img_main_product.png" class="img-rec-product">
                            </span>
                        </center>
                    </div>
                </a>
                <span class="span-circle-new">NEW</span>
                <span class="span-circle-discount">100%</span>
                <div class="clear"></div>
                <a href="#">
                    <p class="p-rec-product-name">
                        Easy Clothes
                    </p>
                </a>
                <p class="p-rec-product-price">
                    <s>P200.00</s> <span>P150.00</span>
                </p>
                <table width="100%">
                    <tbody>
                        <tr>
                            <td>
                                <a class="btn btn-default-1 btn-add-cart" target="_blank" href="/item/boom">
                                    <span class="icon-cart"></span> ADD TO CART
                                </a>
                            </td>
                            <td class="td-logo-store" align="right">
                                <a href="#">
                                    <span>
                                        <div class="store-logo-container ">
                                            <div class="span-store-logo">
                                                    <img src="/assets/images/img_main_product.png" class="store-logo">
                                            </div>
                                        </div>
                                    </span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="item">
                <a href="#">
                    <div class="div-rec-product-image">
                        <center>
                            <span class="span-me">
                                <img src="/assets/images/img_easy_treat_v1.jpg" class="img-rec-product">
                            </span>
                        </center>
                    </div>
                </a>
                <span class="span-circle-new">NEW</span>
                <span class="span-circle-discount">100%</span>
                <div class="clear"></div>
                <a href="#">
                    <p class="p-rec-product-name">
                        Easy Clothes
                    </p>
                </a>
                <p class="p-rec-product-price">
                    <s>P200.00</s> <span>P150.00</span>
                </p>
                <table width="100%">
                    <tbody>
                        <tr>
                            <td>
                                <a class="btn btn-default-1 btn-add-cart" target="_blank" href="/item/boom">
                                    <span class="icon-cart"></span> ADD TO CART
                                </a>
                            </td>
                            <td class="td-logo-store" align="right">
                                <a href="#">
                                    <span>
                                        <div class="store-logo-container ">
                                            <div class="span-store-logo">
                                                    <img src="/assets/images/img_easy_treat_v1.jpg" class="store-logo">
                                            </div>
                                        </div>
                                    </span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <?php
                }
            ?>
        </div>
    </div>
</div>

<script src="/assets/js/src/vendor/owl.carousel.min.js" type="text/javascript"></script>