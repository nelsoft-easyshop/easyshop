<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$oAuthParam = require APPPATH . '/config/param/oauth.php';
$config = array(
    'facebook' => array(
        'key' => $oAuthParam['facebook']['key'],
        'redirect_url' => $oAuthParam['facebook']['redirect_url'],
        'permission_to_access' => $oAuthParam['facebook']['permission_to_access'],
    ),
    'google' => array(
        'key' => $oAuthParam['google']['key'],
        'redirect_url' => $oAuthParam['google']['redirect_url'],
        'permission_to_access' => $oAuthParam['google']['permission_to_access'],
    )
);

return $config;
