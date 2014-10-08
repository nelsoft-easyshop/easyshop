<?php

/**
 * OAuth param values
 */

require_once __DIR__ . '/../../src/EasyShop/Core/Configuration/Configuration.php';
$configService = new EasyShop\Core\Configuration\Configuration();

if(isset($_SERVER['HTTPS'])){
    $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
}
else{
    $protocol = 'http';
}
$baseUrl =  $protocol . "://" . $_SERVER['HTTP_HOST'];



$defaultParams = [
        'facebook' => array(
            'key' => array(
                'appId'  => '781007275276393',
                'secret' => '45ef9229f399e330b2c91eb474825044'
            ),
            'permission_to_access' => array(
                'email'
            ),
            'redirect_url' => $baseUrl.'/SocialMediaController/registerFacebookUser',
        ),
        'google' => array(
            'key' => array(
                'appId'  => '419706420463-ft6sqdhj8iga0rf1gokku4lv91k3m6aq.apps.googleusercontent.com',
                'secret' => 'M93sKhVwLMP6otEdcbM5lQvw',
                'apiKey' => 'AIzaSyAPUvSDrq59kJO4-a47SbqG1WPCEbVghSQ'
            ),
            'redirect_url' => $baseUrl.'/SocialMediaController/registerGoogleAccount',
            'permission_to_access' => array(
                'email',
                'https://www.googleapis.com/auth/userinfo.profile'
            ),
        )
    ];

if ($configService->isConfigFileExists()) {
    $serverConfig = $configService->getConfigValue();
    $oAuthParam = [
        'facebook' => array(
            'key' => isset($serverConfig['facebook_oauth']['key']) ? $serverConfig['facebook_oauth']['key'] : $defaultParams['facebook']['key'],
            'permission_to_access' => isset($serverConfig['facebook_oauth']['permission_to_access']) ? $serverConfig['facebook_oauth']['permission_to_access'] : $defaultParams['facebook']['permission_to_access'],
            'redirect_url' => isset($serverConfig['facebook_oauth']['redirect_url']) ? $serverConfig['facebook_oauth']['redirect_url'] : $defaultParams['facebook']['redirect_url'],
        ),
        'google'=> array(
            'key' => isset($serverConfig['google_oauth']['key']) ? $serverConfig['google_oauth']['key'] : $defaultParams['google']['key'],
            'permission_to_access' => isset($serverConfig['google_oauth']['permission_to_access']) ? $serverConfig['google_oauth']['permission_to_access'] : $defaultParams['google']['permission_to_access'],
            'redirect_url' => isset($serverConfig['google_oauth']['redirect_url']) ? $serverConfig['google_oauth']['redirect_url'] : $defaultParams['google']['redirect_url'],
        )
    ];
}
else {
    $oAuthParam = $defaultParams;
}


return $oAuthParam;
