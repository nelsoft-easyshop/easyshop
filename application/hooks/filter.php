<?php
/**
 * Filter Class
 */
class IP_Filter
{
    /**
     * Holds CI instance
     *
     * @var CI instance
     */
    private $CI;
    
    /**
     * Enables splash
     *
     * @var boolean
     */
    private $enableSplash = false;
    
    /**
     * Bypass IP
     *
     * @var string
     */
    private $bypassIp;
    

    public function __construct()
    {
        $this->CI =& get_instance();
    }
    
    
    /**
     * Checks if the IP is allowed to access the site
     *
     */
    public function isIpAllowed()
    {
        $clientIP = $this->CI->kernel
                   ->serviceContainer['http_request']
                   ->getClientIp(); 

        $configService = $this->CI->kernel
                              ->serviceContainer['local_configuration'];
        if($configService->isConfigFileExists()){
            $serverConfig = $configService->getConfigValue();
            if(isset($serverConfig['maintenance_on'])){
                $this->enableSplash = $serverConfig['maintenance_on'];
            }
            if(isset($serverConfig['maintenance_bypass_ip'])){
                $this->bypassIp = $serverConfig['maintenance_bypass_ip'];
            }
        }

        $splashPageUrl = 'home/splash';
        $currentUrl = $this->CI->uri->uri_string();
        if($splashPageUrl !== $currentUrl){
            if ($this->enableSplash && ($this->bypassIp !== $clientIP)){
                redirect('/'.$splashPageUrl);  
            }
        }
    }

}

