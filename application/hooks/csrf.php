<?php
/**
 * CSRF Protection Class
 */
class CSRF_Protection
{
    /**
     * Holds CI instance
     *
     * @var CI instance
     */
    private $CI;
    
    /**
     * Name used to store token on session
     *
     * @var string
     */
    private static $token_name = 'csrfname';

    /**
     * Stores the token
     *
     * @var string
     */
    private static $token;
    
    public function __construct()
    {
        $this->CI =& get_instance();
    }
      
      
    /**
     * Generates a CSRF token and stores it on session. Only one token per session is generated.
     * This must be tied to a post-controller hook, and before the hook
     * that calls the inject_tokens method().
     *
     * @return void
     * @author Ian Murray
     */
    public function generate_token()
    {
        // Load session library if not loaded
        $this->CI->load->library('session');
        
        if ($this->CI->session->userdata(self::$token_name) === false){
            // Generate a token and store it on session, since old one appears to have expired.
            self::$token = md5(uniqid() . microtime() . rand());
            $this->CI->session->set_userdata(self::$token_name, self::$token);
        }
        else{
            // Set it to local variable for easy access
            self::$token = $this->CI->session->userdata(self::$token_name);
        }
    }
    
    
    /**
     * Validates a submitted token when POST request is made.
     *
     * @return void
     * @author Ian Murray
     */
    public function validate_tokens()
    {
        // Is this a post request?
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $this->CI->config->load('csrf', TRUE);
            $csrfConfig = $this->CI->config->item('csrf');
            
            $firstUrlSegment = reset($this->CI->uri->segment_array());
            
            if(empty($_POST) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0){
                show_error('Request was invalid. Selected file was too large.', 400);
            }
            else if(in_array($_SERVER['REQUEST_URI'], $csrfConfig['bypassURI'])){
                return true;
            }
            else if(in_array($firstUrlSegment,  $csrfConfig['bypassFirstSegment'])){
                return true;
            }       
            else{
                 // Is the token field set and valid?
                $posted_token = $this->CI->input->post(self::$token_name);
                if ($posted_token === FALSE || $posted_token != $this->CI->session->userdata(self::$token_name))
                {
                  // Invalid request, send error 400.
                  show_error('Request was invalid. Tokens did not match.', 400);
                }
            }
        }

    }
    

    /**
     * This injects hidden tags on all POST forms with the csrf token.
     * Also injects meta headers in <head> of output (if exists) for easy access
     * from JS frameworks.
     *
     * @return void
     * @author Ian Murray
     */
    public function inject_tokens()
    {
        $output = $this->CI->output->get_output();
        
        // Inject into form
        $output = preg_replace('/(<(form|FORM)[^>]*(method|METHOD)="(post|POST)"[^>]*>)/',
                                '$0<input type="hidden" name="' . self::$token_name . '" csrf-directive="' . self::$token . '" value="' . self::$token . '">', 
                                $output);
        
        // Inject into <head>
        $output = preg_replace('/(<\/head>)/',
                                '<meta name="csrf-name" content="' . self::$token_name . '">' . "\n" . '<meta name="csrf-token" content="' . self::$token . '">' . "\n" . '$0', 
                                $output);
        
        if (!defined('PHPUNIT_TEST')) {
            $this->CI->output->_display($output);
        }
    }
    
}

