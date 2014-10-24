<?php

$config = array();

if(ENVIRONMENT == 'production'){
    $config['s3']['key'] = 'AKIAILHPRP3MKZRSRYPA';
    $config['s3']['secret'] = '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';
    $config['s3']['bucket'] = "easyshop-production";
}
else{
    $config['s3']['key'] = 'AKIAILHPRP3MKZRSRYPA';
    $config['s3']['secret'] = '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';
    $config['s3']['bucket'] = "easyshop-staging";
}

return $config;