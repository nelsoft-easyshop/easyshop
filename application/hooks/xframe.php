<?php

/**
 * Hook for attaching x-frame header
 */
class XFrame_Filter
{  
    /**
     * The CI singleton
     */
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Attach X-frame-options header to HTTP response
     *
     */
    public function attachXframeHeader()
    {
        $this->CI->config->load('xframe', TRUE);
        $xframeConfig = $this->CI->config->item('xframe');        
        $firstUrlSegment = reset($this->CI->uri->segment_array());
        $fullUri = $_SERVER['REQUEST_URI'];

        if(! (in_array($firstUrlSegment, $xframeConfig['bypassFirstSegment']) ||
              in_array($fullUri, $xframeConfig['bypassURI']))
        ){
            $this->CI->output->set_header('X-Frame-Options: SAMEORIGIN');
        }
    }
    
}
