<?php

namespace EasyShop\Utility;

/**
 * URL utility class
 *
 */
class UrlUtility
{
    /**
     * Determines if a url is external or not. All URLs with no http(s):// or www
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
        if(!is_string($url)){
            $url = '';
        }
        $formattedUrl = trim($url);
        if(substr($formattedUrl, 0, 4) === 'www.'){
            $formattedUrl = 'http://'.$formattedUrl;
        }
        $url = $formattedUrl;
        $serverBaseUrl = $_SERVER['HTTP_HOST'];
        if(strpos($formattedUrl, '://') === false){
            if(substr($formattedUrl,0,1) !== "/"){
                $formattedUrl = '/'.$formattedUrl;
            }
            $formattedUrl = '//'.$serverBaseUrl.$formattedUrl;
        }
        $linkUrl = parse_url($formattedUrl);
        if( $linkUrl['host'] === $serverBaseUrl) {
            $target = '_self';
            $class = $internalClass;
        } 
        else {
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
    
    /**
     * Add rel no follows to hyperlinks in a body of html
     *
     * @param string $html
     * @return string
     */
    public function addRelNofollow($html)
    {
        $asciiHtml = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $doc = new \DOMDocument();
        /**
         * @ = suppresses error messages
         */
        @$doc->loadHTML($asciiHtml);
        $tags = $doc->getElementsByTagName('a');
        foreach($tags as $a){
            $a->setAttribute('rel', 'nofollow');
        }
        return @$doc->saveHTML($doc); 
    }

}
