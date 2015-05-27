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
        <div class="es-widget-2">
            <div class="title-bar">
                <a href="/" target="_blank">
                    <img src="<?=getAssetsDomain();?>assets/images/categories/icon-widget/easyshop-logo.png">
                </a>
            </div>
            <div class="search-form">
                <form method="get" action="/search/product" target="_blank">
                    <input type="text" class="search-textfield" name="q_str" placeholder="Search item here"/>
                    <i class="icon-search"></i>
                </form>
            </div>
            <div class="container-title">
                Popular items
            </div>
            <center>
                <div class="items-wrapper">
                    <center>
                        <div class="row">
                            <?php foreach ($products as $product): ?>
                            <div class="item">
                                <a href="/item/<?=$product->getSlug();?>" target="_blank">
                                    <div class="item-img-container">
                                        <img src="<?=getAssetsDomain().$product->getDefaultImage()->getDirectory().'thumbnail/'.$product->getDefaultImage()->getFilename();?>" />
                                    </div>
                                </a>
                            </div>
                            <?php endforeach;?>
                        </div>
                    </center>
                </div>
             </center>
        </div>
    </body>
    <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
        <script src="/assets/js/src/vendor/bower_components/jquery.js" type="text/javascript" ></script>
        <script src="/assets/js/src/widget.js?ver=<?php echo ES_FILE_VERSION ?>" type='text/javascript' ></script>
    <?php else: ?>
        <script src="/assets/js/min/easyshop.widget-page.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
    <?php endif; ?>
</html>
