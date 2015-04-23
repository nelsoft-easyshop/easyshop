<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <head>
        <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
            <link type="text/css" href='/assets/css/vendor/bower_components/bootstrap.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
            <link type="text/css" href='/assets/css/widget.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
        <?php else: ?>
            <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.widget-page.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
        <?php endif; ?>
        <link type="text/css" href='/assets/css/easy-icons/easy-icons.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
    </head>
    <body>
        <div class="es-widget-1">
            <div class="row row-widget">
                <div class="col-xs-3 col-left-wing">
                    <div class="left-wing">
                        <center>
                            <div class="es-logo-container">
                                <a href="/" target="_blank">
                                    <img class="es-logo" src="<?=getAssetsDomain();?>assets/images/categories/icon-widget/es-logo.png" />
                                </a>
                                <span class="arrow-es"></span>
                            </div>
                            <ul class="list-unstyled category-list">
                                <?php foreach ($categories as $category): ?>
                                    <li>
                                        <a href="/category/<?=$category->getSlug();?>" target="_blank">
                                            <img src="<?=getAssetsDomain();?>assets/images/categories/icon-widget/<?=$category->getSlug();?>.png">
                                        </a>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                        </center>
                    </div>
                </div>
                <div class="col-xs-9 col-right-wing">
                    <div class="right-wing">
                        <div class="search-form">
                            <form method="get" action="/search/search.html" target="_blank">
                                <input type="text" class="search-textfield" name="q_str" placeholder="Search item here"/>
                                <i class="icon-search"></i>
                            </form>
                        </div>
                        <div class="item-container">
                            <p class="container-title">Popular items</p>
                            <?php foreach ($products as $product): ?>
                                <div class="item">
                                    <a href="/item/<?=$product->getSlug();?>" target="_blank">
                                        <div class="item-img-container">
                                            <img src="<?=getAssetsDomain().$product->getDefaultImage()->getDirectory().'thumbnail/'.$product->getDefaultImage()->getFilename();?>" />
                                        </div>
                                    </a>
                                    <a href="/item/<?=$product->getSlug();?>" class="item-name" target="_blank">
                                        <?=html_escape($product->getName());?>
                                    </a>
                                    <p class="item-price">&#8369; <?=number_format($product->getFinalPrice(), 2, '.', ',')?></p>
                                </div>
                            <?php endforeach;?>
                        </div>
                        <div class="clear"></div>
                        <div class="see-all-container">
                            <a href="/" target="_blank">See All</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
        <script src="/assets/js/src/vendor/bower_components/jquery.js" type="text/javascript" ></script>
        <script src="/assets/js/src/widget.js?ver=<?php echo ES_FILE_VERSION ?>" type='text/javascript' ></script>
    <?php else: ?>
        <script src="/assets/js/min/easyshop.widget-page.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
    <?php endif; ?>
</html>
