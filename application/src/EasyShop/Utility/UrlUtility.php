<?php

namespace EasyShop\Utility;

/**
 * URL utility class
 *
 */
class UrlUtility
{
    /**
     * Determines if a url is external or not. All URLs with no http(s)://
     * are automatically assumed to be internal. 
     *
     * @param string $url 
     * @param string $internalClass
     * @param string $externalClass
     * 
     * @return mixed
     */
    public function parseExternalUrl($url, $internalClass = "", $externalClass = "")
    {
        $formattedUrl = trim($url);
        $serverBaseUrl = $_SERVER['HTTP_HOST'];
        if(strpos($formattedUrl, '://') === false){
            if(substr($formattedUrl,0,1) !== "/"){
                $formattedUrl = '/'.$formattedUrl;
            }
            $formattedUrl = '//'.$serverBaseUrl.$formattedUrl;
        }
        
        $linkUrl = parse_url($formattedUrl);

        if( $linkUrl['host'] == $serverBaseUrl) {
            $target = '_self';
            $class = $internalClass;
        } else {
            $target = '_blank';
            $class = $externalClass;
        }

        $output = array(
            'classString'     => $class,
            'targetString'    => $target,
            'url'       => $url
        );

        return $output;
    }

}
