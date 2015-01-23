<?php

$config = [];

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

$config['s3']['allowed_types'] = 'gif|jpg|png|jpeg';
$config['s3']['max_size'] = 5000;


return $config;


/* End of file aws.php */
/* Location: ./application/config/param/aws.php */

