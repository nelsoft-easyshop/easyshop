<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link type="text/css" href='/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
        <link type="text/css" href='/assets/css/easy-icons/easy-icons.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
        <link type="text/css" href='/assets/css/widget.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    </head>
    <body>
        <div class="es-widget-1">
            <div class="row row-widget">
                <div class="col-xs-3">
                    <div class="left-wing">
                        <center>
                            <div class="es-logo-container">
                                <a href="/">
                                    <img class="es-logo" src="<?=getAssetsDomain();?>assets/images/categories/icon-widget/es-logo.png" />
                                </a>
                                <span class="arrow-es"></span>
                            </div>
                            <ul class="list-unstyled category-list">
                                <?php foreach ($categories as $category): ?>
                                    <li>
                                        <a href="/category/<?=$category->getSlug();?>">
                                            <img src="<?=getAssetsDomain();?>assets/images/categories/icon-widget/<?=$category->getSlug();?>.png">
                                        </a>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                        </center>
                    </div>
                </div>
                <div class="col-xs-9">
                    <div class="right-wing">
                        <div class="search-form">
                            <form method="get" action="/search/search.html">
                                <input type="text" class="search-textfield" name="q_str" placeholder="Search item here"/>
                                <i class="icon-search"></i>
                            </form>
                        </div>
                        <div class="item-container">
                            <p class="container-title">Popular products</p>
                            <?php foreach ($products as $product): ?>
                                <div class="item">
                                    <a href="/item/<?=$product->getSlug();?>">
                                        <div class="item-img-container">
                                            <img src="<?=getAssetsDomain().$product->getDefaultImage()->getDirectory().'thumbnail/'.$product->getDefaultImage()->getFilename();?>" />
                                        </div>
                                    </a>
                                    <a href="/item/<?=$product->getSlug();?>" class="item-name">
                                        <?=html_escape($product->getName());?>
                                    </a>
                                    <p class="item-price">&#8369; <?=number_format($product->getFinalPrice(), 2, '.', ',')?></p>
                                </div>
                            <?php endforeach;?>
                        </div>
                        <div class="clear"></div>
                        <div class="see-all-container">
                            <a href="/">See All</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="/assets/js/src/vendor/jquery-1.9.1.js"></script>
    <script src="/assets/js/src/widget.js?ver=<?php echo ES_FILE_VERSION ?>" type='text/javascript' ></script>
</html>