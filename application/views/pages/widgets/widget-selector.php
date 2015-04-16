<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Easyshop.ph | Widgets</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon"/>

        <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
            <link type="text/css" href="/assets/css/how-to-page.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
            <link type="text/css" href="/assets/css/widget-selector.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
        <?php else: ?>
            <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.widget-selector.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
        <?php endif; ?>
    </head>
    <body class="animated fadeIn">
        <header class="navbar navbar-static-top">
            <div class="navbar-inner">
                <div class="container">
                    <div class="logo-mobile">
                        <center>
                            <a href="/">
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/how-to-page/logo.png" alt="Online Shopping">
                            </a>
                        </center>
                    </div>
                    <div class="nav-collapse collapse" id="navigation">
                        <ul class="nav">
                            <li class="logo">
                                <a href="/">
                                    <img src="<?php echo getAssetsDomain(); ?>assets/images/how-to-page/logo.png" alt="Online Shopping">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <section class="slideshow">
            <div class="container single-image-before"></div>
            <div class="single-image single-image-widget">
                <div class="container">
                    <div class="me">
                        <div class="row-fluid">
                            <div class="span6  span-desc">
                                <div class="widget-item-container first">
                                    <div class="row-fluid">
                                        <div class="span6 span-iframe">
                                            <center>
                                                <div class="iframe-container">
                                                    <iframe src="<?=$firstWidgetLink;?>" width="250" height="360" scrolling="no" frameborder="0"></iframe>
                                                </div>
                                            </center>
                                        </div>
                                        <div class="span6">
                                            <p class="title-widget">EasyWidget A</p>
                                            <p class="desc-widget">
                                                This widget is the envy of all E-commerce widgets. It's simple and yet it delivers a polished look. Other than providing you with a search bar & a preview of our popular items, the icons on its left are links to the main categories on our site.
                                            </p>
                                            <br/>
                                            <p class="title-widget">Minimum Specifications</p>
                                            <p class="desc-widget">
                                                <b>Width : </b> 250px
                                                <br/>
                                                <b>Height : </b> 360px
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="embed-code-form">
                                                <label>Embed Code</label>
                                                <input type="text" class="form-control input-lg widget-link-textbox" readonly value="<?=html_escape('<iframe src="'.$firstWidgetLink.'" width="250" height="360" scrolling="no" frameborder="0"></iframe>');?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="v-divider"></div>
                            <div class="h-divider"></div>
                            <div class="span6 span-desc">
                                <div class="widget-item-container second">
                                    <div class="row-fluid">
                                        <center>
                                        <div class="span6 span-iframe">
                                            <div class="iframe-container">
                                                <iframe src="<?=$secondWidgetLink;?>" width="260" height="260" scrolling="no" frameborder="0"></iframe>
                                            </div>
                                        </div>
                                    </center>
                                        <div class="span6">
                                            <p class="title-widget">EasyWidget B</p>
                                            <p class="desc-widget">
                                                This widget is for the minimalist at heart. A search bar and 6 popular items, what else could you ask for?
                                            </p>
                                            <br/>
                                            <p class="title-widget">Minimum Specifications</p>
                                            <p class="desc-widget">
                                                <b>Width : </b> 260px
                                                <br/>
                                                <b>Height : </b> 260px
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="embed-code-form">
                                                <label>Embed Code</label>
                                                <input type="text" class="form-control input-lg widget-link-textbox" readonly value="<?=html_escape('<iframe src="'.$secondWidgetLink.'" width="260" height="260" scrolling="no" frameborder="0"></iframe>');?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container single-image-after">
                <div class="row-fluid">
                    
                </div>
            </div>
        </section>
        <section>
            <div class="container load-animate container-widget">
                <div class="row-fluid">
                    <div class="span12 box">
                        <div class="row-fluid desc-widget-text">
                            <div class="span4"></div>
                            <div class="span8 padding-top-70 padding-left-30 padding-right-30">
                                <h1 class="align-center">Select your easywidget</h1>
                                <p class="align-center">
                                    The <b>EasyWidget</b> is the quickest way for people to search for an item from EasyShop's database while they're on a third-party website. It's most suited for bloggers with a user-base who loves online shopping. 
                                    <br/><br/>
                                    To start using the <b>EasyWidget</b>, pick from the two variants that are available below, copy the code and paste it into your website and it'll work like magic.
                                </p>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12 padding-top-30">
                                <img class="img-widget-desc" src="<?php echo getAssetsDomain(); ?>assets/images/widget-desc-bg.jpg">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <footer>
            <a href="#" id="top">&#59235;</a>
            <section class="footer_links">
                <ul>
                    <li><a href="/">Visit Site</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="/terms">Terms &amp; Conditions</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="/policy">Privacy Policy</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </section>
            <section class="copyright">
                <p>Copyright © 2015 Easyshop.ph<br>All rights reserved.</p>
            </section>
        </footer>
        <div id="fb-root"></div>
        <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
            <script type='text/javascript' src="/assets/js/src/vendor/jquery-1.9.1.js" ></script>
            <script type="text/javascript" src="/assets/js/src/vendor/modernizr-2.6.2.min.js"></script>
            <script type="text/javascript" src="/assets/js/src/how-to-page-plugins.js"></script>
            <script type="text/javascript" src="/assets/js/src/how-to-page.js"></script>
            <script type="text/javascript" src="/assets/js/src/widget.js"></script>
        <?php else: ?>
            <script src="/assets/js/min/easyshop.widget-selector.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
        <?php endif; ?>
    </body>
</html>
