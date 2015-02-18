<?php

$config = [];

if(ENVIRONMENT == 'production'){
    $config['s3']['key'] = 'AKIAJIHHWXP22QNG5IYA';
    $config['s3']['secret'] = '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';
    $config['s3']['bucket'] = "easyshop-production";
}
else{
    $config['s3']['key'] = 'AKIAJIHHWXP22QNG5IYA';
    $config['s3']['secret'] = '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';
    $config['s3']['bucket'] = "easyshop-staging";
}

$config['s3']['allowed_types'] = 'gif|jpg|png|jpeg';
$config['s3']['max_size'] = 5000;


return $config;


/* End of file aws.php */
/* Location: ./application/config/param/aws.php */

