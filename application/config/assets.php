<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$awsConfig = require APPPATH . '/config/param/aws.php';

$config = array(
    "bucket" => $awsConfig['s3']['bucket'],
);

if(ENVIRONMENT == 'production'){
    $config['assetsBaseUrl'] = "https://s3-ap-southeast-1.amazonaws.com/easyshop-staging/easyshop-production";
}
else if(ENVIRONMENT == 'staging'){
    $config['assetsBaseUrl'] = "https://s3-ap-southeast-1.amazonaws.com/easyshop-staging/easyshop-staging";
}
else{
    $config['assetsBaseUrl'] = "assets/";
}


/* End of file assets.php */
/* Location: ./application/config/assets.php */