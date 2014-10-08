<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
    'facebook' => array(
        'key' => array(
            'appId'  => '781007275276393',
            'secret' => '45ef9229f399e330b2c91eb474825044'
            ),
        'redirect_url' => 'https://easyshop.ph/SocialMediaController/registerFacebookUser',
        'permission_to_access' => array(
            'email'
            ),
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
);

return $config;
