<?php

/**
 * OAuth param values
 */

require_once __DIR__ . '/../../src/EasyShop/Core/Configuration/Configuration.php';
$configService = new EasyShop\Core\Configuration\Configuration();

if ($configService->isConfigFileExists()) {
    $serverConfig = $configService->getConfigValue();
    $oAuthParam = [
        'facebook' => array(
            'key' => $serverConfig['facebook_login_credentials']['key'],
            'permission_to_access' => $serverConfig['facebook_login_credentials']['permission_to_access'],
            'redirect_url' => $serverConfig['facebook_login_credentials']['redirect_url'],
        ),
        'google'=> array(
            'key' => $serverConfig['google_login_credentials']['key'],
            'permission_to_access' => $serverConfig['google_login_credentials']['permission_to_access'],
            'redirect_url' => $serverConfig['google_login_credentials']['redirect_url'],
        )
    ];
}
else {
    $oAuthParam = [
        'facebook' => array(
            'key' => array(
                'appId'  => '781007275276393',
                'secret' => '45ef9229f399e330b2c91eb474825044'
            ),
            'permission_to_access' => array(
                'email'
            ),
            'redirect_url' => 'https://easyshop.ph/SocialMediaController/registerFacebookUser',
        ),
        'google' => array(
            'key' => array(
                'appId'  => '419706420463-ft6sqdhj8iga0rf1gokku4lv91k3m6aq.apps.googleusercontent.com',
                'secret' => 'M93sKhVwLMP6otEdcbM5lQvw',
                'apiKey' => 'AIzaSyAPUvSDrq59kJO4-a47SbqG1WPCEbVghSQ'
            ),
            'redirect_url' => 'https://easyshop.ph/SocialMediaController/registerGoogleAccount',
            'permission_to_access' => array(
                'email',
                'https://www.googleapis.com/auth/userinfo.profile'
            ),
        )
    ];
}

return $oAuthParam;
