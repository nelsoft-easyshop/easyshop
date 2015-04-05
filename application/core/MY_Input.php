<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Input extends CI_Input 
{
    function __construct()
    {
        parent::__construct();
    }
        
    /**
     * Fetch the IP Address
     * Accepts CIDR notation
     *
     * @return   string
     */
    public function ip_address()
    {
        if ($this->ip_address !== FALSE)
        {
            return $this->ip_address;
        }

        $proxy_ips = config_item('proxy_ips');
        
        if ( ! empty($proxy_ips))
        {
            $proxy_ips = explode(',', str_replace(' ', '', $proxy_ips));
            foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP') as $header)
            {
                if (($spoof = $this->server($header)) !== FALSE || TRUE)
                {          
                    if (strpos($spoof, ',') !== FALSE)
                    {
                        $spoof = explode(',', $spoof, 2);
                        $spoof = $spoof[0];
                    }

                    if ( ! $this->valid_ip($spoof))
                    {
                        $spoof = FALSE;
                    }
                    else
                    {
                        break;
                    }
                }
            }

            $this->ip_address = ($spoof !== FALSE && $this->checkIp4($_SERVER['REMOTE_ADDR'], $proxy_ips))
                                ? $spoof : $_SERVER['REMOTE_ADDR'];
        }
        else
        {
            $this->ip_address = $_SERVER['REMOTE_ADDR'];
        }

        if ( ! $this->valid_ip($this->ip_address))
        {
            $this->ip_address = '0.0.0.0';
        }

        return $this->ip_address;
    }
    
    /**
     * Compares two IPv4 addresses.
     * In case a subnet is given, it checks if it contains the request IP.
     * Based on Symonfy IpUtils class
     *
     * @param string $requestIp IPv4 address to check
     * @param string $proxies   Proxy IPv4 address or subnet in CIDR notation
     *
     * @return bool
     */
    private function checkIp4($requestIp, $proxies)
    {
        if (!is_array($requestIp)) {
            $requestIp = [ $requestIp ];
        }
        
        foreach ($requestIp as $ip) {
            if (strpos($ip, '/') !== false) {
                list($address, $netmask) = explode('/', $ip, 2);
                if ($netmask < 1 || $netmask > 32) {
                    return false;
                }
            } 
            else {
                $address = $ip;
                $netmask = 32;
            }
            $isValid = substr_compare(sprintf('%032b', ip2long($ip)), sprintf('%032b', ip2long($address)), 0, $netmask) === 0;
            return $isValid;
        }
        
        return false;
    }
        
}

