<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$awsConfig = require APPPATH . '/config/param/aws.php';

$config = [
    "bucket" => $awsConfig['s3']['bucket'],
    "allowed_types" => $awsConfig['s3']['allowed_types'],
    "max_size" => $awsConfig['s3']['max_size'],
];

if(ENVIRONMENT == 'production'){
    $config['assetsBaseUrl'] = "http://s3-ap-southeast-1.amazonaws.com/easyshop-production/";
}
else if(ENVIRONMENT == 'staging'){
    $config['assetsBaseUrl'] = "http://s3-ap-southeast-1.amazonaws.com/easyshop-staging/";
}
else{
    $config['assetsBaseUrl'] = "/";
}



/* End of file assets.php */
/* Location: ./application/config/assets.php */

