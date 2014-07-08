<?php


/**
 * MY custom controller
 */
class MY_URI extends CI_URI 
{
    public function __construct()
    {
        parent::__construct();  
    }

    function _filter_uri($str)
    {
        if ($str != '' && $this->config->item('permitted_uri_chars') != '' && $this->config->item('enable_query_strings') == FALSE)
        {
            // preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
            // compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
            if ( ! preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($this->config->item('permitted_uri_chars'), '-'))."]+$|i", $str))
            {
                // header('https://easyshop.ph.rb/home/pagenotfound');
            }
        }

        // Convert programatic characters to entities
        $bad    = array('$',        '(',        ')',        '%28',      '%29');
        $good   = array('&#36;',    '&#40;',    '&#41;',    '&#40;',    '&#41;');

        return str_replace($bad, $good, $str);
    }
}


/* End of file MY_URI.php */
/* Location: ./application/core/MY_URI.php */