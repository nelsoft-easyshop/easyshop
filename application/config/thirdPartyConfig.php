<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
    'facebook' => array(
        'key' => array(
            'appId'  => '711804058875446',
            'secret' => 'ddf41dbcd1f11ac5e81bc19dff4e66c7'
            ),
        'redirect_url' => 'https://local.easyshop/socialMediaSetup/registerFacebookUser',
        'permission_to_access' => array(
            'email'
            ),
    ),
    'google' => array(
        'key' => array(
            'appId'  => '438420865946-m5vfe1fng13hl4n76p3j9votrdrafht1.apps.googleusercontent.com',
            'secret' => '9ro7XXdT3YzYwt9JtOB6l-0l',
            'apiKey' => 'AIzaSyD2PjmKzTeIfOg16pW5Shk_V0hMeAEYpR4'
        ),
        'redirect_url' => 'https://easyshop.com/socialMediaSetup/registerGoogleAccount',
        'permission_to_access' => array(
            'email',
            'https://www.googleapis.com/auth/userinfo.profile'
        ),
    )
);

return $config;
