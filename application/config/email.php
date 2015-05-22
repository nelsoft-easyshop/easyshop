<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = Array(
            'protocol' => 'smtp',
            //'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_port' => 465,
            // 'smtp_user' => 'noreply@easyshop.ph',
            // 'smtp_pass' => '3a5y5h0p_noreply',
            'smtp_user' => 'admin@easyshop.ph',
            'smtp_pass' => 'SECRET345y5h0p',
            'mailtype'  => 'html', 
            //'charset'   => 'iso-8859-1',
            'charset' => 'utf-8',
            //new entry
            'multipart' => 'related',
            //'smtp_crypto' => 'ssl'
            'images' => [
                "/assets/images/email/icon-facebook.png",
                "/assets/images/email/icon-twitter.png",
                "/assets/images/email/filler.jpg",
                "/assets/images/es-logo-login.png",
                "/assets/images/email/shadow.png",
            ]
        );

        
/* End of file email.php */
/* Location: ./application/config/email.php */

