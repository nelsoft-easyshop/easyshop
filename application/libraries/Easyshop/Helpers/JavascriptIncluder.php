<?php

namespace Easyshop\Helpers\JavascriptIncluder;

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
     * @param string $devBaseDir javascripts development directory
     * @param string $prodBaseDir javascripts production directory
     * @param boolean $isProd TRUE when executing in production environment, FALSE otherwise
     */
    public function __construct($devBaseDir = "/js/src", $prodBaseDir = "/js/min", $isProd = false)
    {
        $this->devBaseDir = $devBaseDir;
        $this->prodBaseDir = $prodBaseDir;
        $this->isProd = $isProd;
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
