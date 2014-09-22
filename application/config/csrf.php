<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
            'bypassURI' => array(
                            '/payment/dragonPayPostBack',
                            '/payment/ipn2',
                            '/payment/pesoPayDataFeed',
            ),
            'bypassFirstSegment' => array(
                            'webservice',
                            'mobile',
            ),
        );
        
/* End of file csrf.php */
/* Location: ./application/config/csrf.php */

