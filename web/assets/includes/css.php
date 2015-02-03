<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link type="text/css" href="/assets/css/style.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet"  media="all"/>
    <link type="text/css" href="/assets/css/responsive_css.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet"  media="all"/>
    <link type='text/css' href='/assets/css/basic.css?ver=<?=ES_FILE_VERSION?>' rel='stylesheet' media="all" />
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.global-includes.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>
