<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Decorator class (an implementation of the decorator pattern for CI)
 * 
 * This class is integrated into the project using CI's package manager
 * sparks with some effort. Sparks has long since been unsupported and 
 * now pales in comparison compared to PHP's other package managers. Because
 * of this, this class will be maintained as part of the application itself
 * rather than as a spark package and will therefore be versioned along with
 * the project source code.
 *
 * @source https://github.com/ccschmitz/codeigniter-decorator
 * @author Chris Schmitz <ccschmitz@gmail.com>
 */
class Decorator 
{

    private $_decorators_directory_name = 'decorators';

    private $_ci;
    private $_decorators_directory;

    public function __construct()
    {
        $this->_ci =& get_instance();

        $this->_decorators_directory = rtrim(APPPATH, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$this->_decorators_directory_name.DIRECTORY_SEPARATOR;
        
        log_message('debug', 'Decorator class initialized');
    }

    public function decorate($class = NULL, $method = NULL, $params = array())
    {
        if ( ! $class)
        {
            $class = $this->_ci->router->class.'_decorator';
        }
        else
        {
            if ( ! strpos($class, '_decorator'))
            {
                $class .= '_decorator';
            }
        }
        

        if ( ! $method)
        {
            $method = $this->_ci->router->method;
        }

        if ( ! is_array($params))
        {
            $params = array($params);
        }

        $file = $this->_decorators_directory.$class.'.php';

        if (file_exists($file))
        {
            require($file);

            $decorator = new $class();
            $returned_data = call_user_func_array(array($decorator, $method), $params);

            if ( ! $returned_data)
            {
                return call_user_func(array($decorator, 'get_decorated_data'));
            }
            else
            {
                return $returned_data;
            }
        }
        else
        {
            show_error('Decorator <em>'.$this->_decorators_directory.$class.'</em> could not be found.');
        }
    }
}