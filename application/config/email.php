<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => 'noreply@easyshop.ph',
			'smtp_pass' => '3a5y5h0p_noreply',
			'mailtype'  => 'html', 
			//'charset'   => 'iso-8859-1',
			'charset' => 'utf-8',
			//new entry
			'multipart' => 'related',
			//'smtp_crypto' => 'ssl'
		);

		
/* End of file email.php */
/* Location: ./application/config/email.php */