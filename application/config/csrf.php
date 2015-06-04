<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
            'bypassURI' => array(
                            '/pay/postBackDragonPay',
                            '/payment/dragonPayPostBack',
                            '/payment/ipn2',
                            '/payment/pesoPayDataFeed',
                            '/payment/unFlagOrder',
            ),
            'bypassFirstSegment' => array(
                            'webservice',
                            'mobile',
                            'api',
                            'extensions',
            ),
        );
        
/* End of file csrf.php */
/* Location: ./application/config/csrf.php */

