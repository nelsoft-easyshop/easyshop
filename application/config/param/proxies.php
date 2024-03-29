<?php

/**
 * Trusted proxies
 *
 * Include reverse proxies (CDNs) 
 * and load balancers
 */

$trustedProxies = [   
    /**
     * Load balancer IP
     */
    '172.31.0.0/20',
    /**
     * Cloud flare Ips
     */
    '199.27.128.0/21',
    '173.245.48.0/20',
    '103.21.244.0/22',
    '103.22.200.0/22',
    '103.31.4.0/22',
    '141.101.64.0/18',
    '108.162.192.0/18',
    '190.93.240.0/20',
    '188.114.96.0/20',
    '197.234.240.0/22',
    '198.41.128.0/17',
    '162.158.0.0/15',
    '104.16.0.0/12',
];

return $trustedProxies;


