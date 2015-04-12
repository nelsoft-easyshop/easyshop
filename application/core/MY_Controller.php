<?php

use EasyShop\Entities\EsMember; 
/**
 * MY custom controller
 */
class MY_Controller extends CI_Controller 
{
    /**
     * Services container
     * 
     * @var Pimple\Container
     */
    protected $serviceContainer;
    
    
    /**
     * Full URLS that are to be ignored in getting the referrer
     *
     * @var string[]
     */
    private $referrerIgnoredFullurl = [
        'login',
        'register',
        'favicon.ico'
    ];
    
    /**
     * Partial URLS (first segment) that are to be ignored in getting the referrer
     *
     * @var string[]
     */
    private $referrerIgnoredFirstSegmentUrls = [
        'assets',
    ];
   
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();        

        /**
         * Store the current url in the session except for certain URLs
         * This is done to get the referrer of a certain page more effectively.
         */
        $url = uri_string();
        $firstSegment = $this->uri->segment(1);
        if( in_array($url, $this->referrerIgnoredFullurl) === false && 
            in_array($firstSegment, $this->referrerIgnoredFirstSegmentUrls) === false){
            $this->session->set_userdata('uri_string', $url);
        }

        if (isset ($this->kernel)) {
            /**
             * This way service container is more accessible to child classes
             */
            $this->serviceContainer = $this->kernel->serviceContainer;
            $this->load->helper('view_helper');
        }

        /**
         * Load custom common functions
         */
        $this->load->helper('common_helper');

        if(!$this->session->userdata('member_id')){
            $this->check_cookie();
        }
   
    }

   
    /**
     * Authenticates the user based on the remember-me cookie
     *
     * @return boolean
     */
    public function check_cookie()
    {   
        $cookie = get_cookie('es_usr');
        if($cookie === '' || !$cookie){
            return false;
        }
        
        $userIp = $this->session->userdata('ip_address');
        $userAgent = $this->session->userdata('user_agent');
        $cisessionId = $this->session->userdata('session_id');
        $authenticationResult = $this->serviceContainer['account_manager']
                                     ->authenticateViaCookie($cookie, $userIp, $userAgent, $cisessionId);
        
        if($authenticationResult['isSuccessful']){
            $member = $authenticationResult['member'];
            $this->session->set_userdata('member_id', $member->getIdMember());
            $this->session->set_userdata('usersession', $authenticationResult['usersession']);
            $cookiedata = [
                'name' => 'es_usr',
                'value' => $authenticationResult['newCookie'],
                'expire' => EasyShop\Account\AccountManager::REMEMBER_ME_COOKIE_LIFESPAN_IN_SEC,
            ];
            set_cookie($cookiedata);
            $cartData = $this->serviceContainer['cart_manager']
                             ->synchCart($member->getIdMember());
            $this->session->set_userdata('cart_contents', $cartData);
            return true;
        }
        return false;
    }
    

    /**
     *  Displays navigation categories
     *
     */
    public function getcat()
    {
        $this->load->model("product_model");
        $rows = $this->product_model->getCategoriesNavigation();
        $data = array();
        $idx_lvl1 = 0;
        $idx_lvl2 = 0;
        foreach($rows as $row){
            if((strlen(trim($row['level1_id']) > 0))&&(!array_key_exists($row['level1_id'], $data))){
                $data[$row['level1_id']] = array(
                        'id_cat' => $row['level1_id'],
                        'NAME' => $row['level1_name'],
                        'path' => $row['img_level1'],
                        'slug' => $row['level1_slug'],
                        0 => array(),
                    );
                $idx_lvl1 = $row['level1_id'];
            }
            if((strlen(trim($row['level2_id']) > 0))&&(!array_key_exists($row['level2_id'], $data[$idx_lvl1][0]))){
                //start popular items : this is the slowest part of this function
                $down_cat = $this->product_model->selectChild($row['level2_id']);   
                if((count($down_cat) === 1)&&(trim($down_cat[0]) === ''))
                    $down_cat = array();
                array_push($down_cat, $row['level2_id']);
                $pitem = $this->product_model->getPopularitem($down_cat,6);     
                //end popular items                
             
                $data[$idx_lvl1][0][$row['level2_id']] =  array(
                        'id_cat' => $row['level2_id'],
                        'name' => $row['level2_name'],
                        'slug' => $row['level2_slug'],
                        6 => array(),
                        'popular' => $pitem,
                    );
                $idx_lvl2 = $row['level2_id'];
            }
            if(strlen(trim($row['level3_id']) > 0)){
                array_push($data[$idx_lvl1][0][$idx_lvl2][6], array(
                        'id_cat' => $row['level3_id'],
                        'name' => $row['level3_name'],
                        'slug' => $row['level3_slug'],));
            }
        }
        foreach($data as $key=>$x){
            $this->map_array($x[0]);
            $data[$key][0] = $x[0];
        }
        $this->map_array($data);

        return $data;
    }
    
    
    private function map_array(&$array){
        $temp = array();
        foreach($array as $x){
            array_push($temp, $x);
        }
        $array = $temp; 
    }  
}


/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */

