<?php


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
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->set_item('base_url',"https://".$_SERVER["SERVER_NAME"]."/");

        $url = uri_string();
        
        if($url !== 'login' && $url !== 'register'){
            $this->session->set_userdata('uri_string', $url);
        }
        
        if (isset ($this->kernel)) {
            
            /* This way service container is more accessible to child classes */
            $this->serviceContainer = $this->kernel->serviceContainer;
            $this->load->helper('view_helper');
        }
        /*  Load custom common functions */
        $this->load->helper('common_helper');
    }
	
    #fill_header is not run in the constructor of MY_Controller despite that fact that all pages need it
    #because it would add unnecessary overhead for all ajax calls. Instead it is called only in the 
    #controller functions that need it
    function fill_header()
    {   
        $this->load->model("user_model");
        $this->load->model("product_model");
        $this->load->model("messages_model");
        $user = array();
        $usersession = $this->session->userdata('usersession');
        if(!empty($usersession) || $this->check_cookie()){
            $uid = $this->session->userdata('member_id'); 
            $user = $this->user_model->getUserById($uid);
            $logged_in = true;
        }
        else{
            $logged_in = false;
        }
        $carts=$this->session->userdata('cart_contents');
        $sizecart = 0;
        if(!empty($carts)){
            if(isset($carts['total_items'])){
                $sizecart = sizeof($carts) - 2 ;
            }else{
                $sizecart = sizeof($carts);
            }
        }
        $unread = $this->messages_model->get_all_messages($this->session->userdata('member_id'),"Get_UnreadMsgs");
        $msgs['unread_msgs'] = (isset($unread['unread_msgs']) ?$unread['unread_msgs'] : 0);
        $msgs['msgs'] = (isset($unread['unread_msgs']) ? ($unread['unread_msgs'] != 0 ? reset($unread['messages']) : ""):0);		
        $data = array(
            'logged_in' => $logged_in,
            'user' => $user,
            'total_items'=> $sizecart,
            'msgs'=> $msgs,
            'category_search' => $this->product_model->getFirstLevelNode(),
            );
        return $data;
    }
	
    function check_cookie(){
        $this->load->model("cart_model");
        $this->load->model("user_model");
		$cookieval = get_cookie('es_usr');
		if($cookieval != ''){
			$data = array(
				'userip' => $this->session->userdata('ip_address'),
				'useragent' => $this->session->userdata('user_agent'),
				'token' => $cookieval,
				'usersession' => $this->session->userdata('session_id')
				);
			$cookielogin = $this->user_model->cookie_login($data);
			if($cookielogin['o_success'] >= 1){
				$this->session->set_userdata('member_id', $cookielogin['o_memberid']);
				$this->session->set_userdata('usersession', $cookielogin['o_usersession']);
				$this->session->set_userdata('cart_contents', $this->cart_model->cartdata($cookielogin['o_memberid']));
				$this->user_model->create_cookie($cookielogin['o_token']);
				return true;
			}
			else
				return false;
		}
		else
			return false;
    }
    

    /*
     *  Displays navigation categories
     */
    function getcat(){
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
    
    /**
     *  Authentication method for webservice
     *
     *  @param string $postedData
     *  @param string $postedHash
     *  @param string $evaluate
     */
    public function authentication($postedData, $postedHash, $evaluate = "")
    {
        foreach ($postedData as $data => $value) {
            
            if($data == "hash" || $data == "_token" || $data == "csrfname" || $data == "callback" || $data == "password" || $data == "_") {
                 continue;               
            }

            else
                $evaluate .= $value;
        }
        $this->load->model("user_model");
        $password = $this->user_model->getAdminUser($postedData["userid"]);

        $hash = $evaluate.$password["password"];

        if(sha1($hash) != $postedHash){
            $error = json_encode("error");
                    exit($error);
        }   
    }





}


/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */