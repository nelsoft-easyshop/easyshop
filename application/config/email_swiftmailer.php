<?php 
$config = Array(
            'protocol' => 'smtp',
            //'smtp_host' => 'ssl://smtp.googlemail.com',
            //'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => 'noreply@easyshop.ph',
            'smtp_pass' => '3a5y5h0p_noreply',
            //'smtp_user' => 'admin@easyshop.ph',
            //'smtp_pass' => 'SECRET345y5h0p',
            'mailtype'  => 'html', 
            //'charset'   => 'iso-8859-1',
            'charset' => 'utf-8',
            //new entry
            'multipart' => 'related',
            'smtp_crypto' => 'ssl',
            'from_email' => 'noreply@easyshop.ph',
            'from_name' => 'Easyshop.ph',
            'queue_type' => 1
        );

return $config;
/* End of file email_swiftmailer.php */
/* Location: ./application/config/email_swiftmailer.php */
