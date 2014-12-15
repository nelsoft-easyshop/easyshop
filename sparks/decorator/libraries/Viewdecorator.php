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
class Viewdecorator extends MY_Controller 
{
    
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

