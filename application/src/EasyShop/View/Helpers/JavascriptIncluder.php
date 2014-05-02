<?php

namespace EasyShop\View\Helpers;

/**
 * Description of JavascriptIncluder
 *
 * @author czarpino
 */
class JavascriptIncluder
{
    /**
     * Javascripts directory in dev environment
     * 
     * @var string
     */
    private $devBaseDir;
    
    /**
     * Javascripts directory in dev environment
     * 
     * @var string
     */
    private $prodBaseDir;
    
    /**
     * Environment flag
     * 
     * @var boolean
     */
    private $isProd = false;
    
    /**
     * Constructor.
     * 
     * @param string $devBaseDir 
     * 
     * @param boolean $isProd TRUE when executing in production environment, FALSE otherwise
     * @param string $devBaseDir javascripts development directory
     * @param string $prodBaseDir javascripts production directory
     */
    public function __construct($isProd = false, $devBaseDir = "/js/src", $prodBaseDir = "/js/min")
    {
        $this->isProd = $isProd;
        $this->devBaseDir = $devBaseDir;
        $this->prodBaseDir = $prodBaseDir;
    }
    
    /**
     * Print javascripts to include
     * 
     * @param string | array $paths
     * 
     * @return string
     */
    public function includeScripts($paths)
    {
        if (is_array($paths)) {
            foreach ($paths as $path) {
                $scriptTag .= $this->buildScriptTag($path);
            }
        
            echo $scriptTag;
            return $scriptTag;
        }
        
        $scriptTag = $this->buildScriptTag($paths);
        echo $scriptTag;
        return $scriptTag;
    }
    
    /**
     * Create js tag
     * 
     * @param string $path
     * 
     * @return string
     */
    private function buildScriptTag($path)
    {
        $baseDir = $this->isProd ? $this->prodBaseDir : $this->devBaseDir;
        return '<script type="text/javascript" src="' . $baseDir . '/' . $path . '"></script>';
    }
}
