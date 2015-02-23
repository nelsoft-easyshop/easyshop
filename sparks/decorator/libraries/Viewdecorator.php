<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CI_Decorator
 *
 * Renamed to Viewdecorator due to issues with the CI prefix as the
 * spark was written for an older version of CI.
 *
 * @source https://github.com/ccschmitz/codeigniter-decorator
 * @author Chris Schmitz <ccschmitz@gmail.com>
 *
 */
class Viewdecorator
{
    /**
     * The DI Container array
     *
     * @var mixed
     */
    protected $serviceContainer;
    
    /**
     * The JWT Token
     *
     */
    protected $jwtToken;

    public function __construct()
    {
        $CI =& get_instance();
        $this->serviceContainer = $CI->kernel->serviceContainer;
        $this->jwtToken = $CI->session->userdata('jwtToken');
    }
    
    /**
     * The view data 
     *
     * @var mixed
     */
    protected $view_data;

    /**
     * Returns the decorated view data
     *
     */
    public function get_decorated_data()
    {
        return $this->view_data;
    }
    
}

