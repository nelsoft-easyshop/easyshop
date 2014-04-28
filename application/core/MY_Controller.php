<?php
class MY_Controller extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();

		$this->config->set_item('base_url',"https://".$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/");
        #$this->config->set_item('base_url',"http://".$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/");
		//$this->config->set_item('base_url',"https://".$_SERVER["SERVER_NAME"]."/");
        $this->load->model("user_model");
		$this->load->model("cart_model");
        $this->load->model("product_model");
		$this->load->vars(
			array('my_csrf' => array(
				'csrf_name' => $this->security->get_csrf_token_name(),
				'csrf_hash' => $this->security->get_csrf_hash()
				)
			)
		);
        
        $url = uri_string();
        if($url != 'login'){
            $this->session->set_userdata('uri_string', $url);
        }
	}
	
    #fill_header is not run in the constructor of MY_Controller despite that fact that all pages need it
    #because it would add unnecessary overhead for all ajax calls. Instead it is called only in the 
    #controller functions that need it
	function fill_header()
	{
		$usersession = $this->session->userdata('usersession');
		if(!empty($usersession) || $this->check_cookie()){

			$uid = $this->session->userdata('member_id'); 
			$row = $this->user_model->getUsername($uid);

			$logged_in = true;
			$uname = $row['username'];
		}
		else{
			$logged_in = false;
			$uname = '';
		}		
		$Tcart_items = $this->session->userdata('cart_contents');
		$data = array(
			'logged_in' => $logged_in,
			'uname' => $uname,
			'total_items'=> $Tcart_items['total_items'],
			'category_search' => $this->product_model->getFirstLevelNode(),
			'header_csrf' => array(
					'csrf_'
				)
			);
		return $data;
	}


	function check_cookie(){
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
    
    
    function getcat_old() {
        
		$row = $this->getdatafromcat(1);
		$arr_data = array();
		for($i=0;$i < count($row);$i++)
		{
			array_push($row[$i], $arr_data);
			$id= $row[$i]['id_cat'];
			$row2 = $this->product_model->getDownLevelNode($id);
			$arr_data2 = array();
			for($j=0;$j < count($row2);$j++)
			{
				array_push($row2[$j], $arr_data2);
				$row[$i][0][$j] = $row2[$j]; 

				$id2= $row2[$j]['id_cat'];
				$row3 = $this->product_model->getDownLevelNode($id2);
				$arr_data3 = array();
				$down_cat = $this->product_model->selectChild($id2);			
				if((count($down_cat) === 1)&&(trim($down_cat[0]) === ''))
					$down_cat = array();
				array_push($down_cat, $id2);
				$pitem = $this->product_model->getPopularitem($down_cat,6);		
				$row[$i][0][$j]['popular'] = $pitem;			
				for($k=0;$k < count($row3);$k++)
				{
					array_push($row3[$k], $arr_data3);
					$row[$i][0][$j][6][$k] = $row3[$k]; 
				}
			}
		}
        
		return $row;

	}
	
	function getdatafromcat($id){
		$row = $this->product_model-> getCatItemsWithImage($id);
		return $row;
	}

    //THIS METHOD IS 0.3 seconds faster than the original one
    function getcat(){
        $rows = $this->product_model->getCatFast();
        $data = array();
        $idx_lvl1 = 0;
        $idx_lvl2 = 0;
        foreach($rows as $row){
            if((strlen(trim($row['level1_id']) > 0))&&(!array_key_exists($row['level1_id'], $data))){
                $data[$row['level1_id']] = array(
                        'id_cat' => $row['level1_id'],
                        'NAME' => $row['level1_name'],
                        'path' => $row['img_level1'],
                        0 => array(),
                    );
                $idx_lvl1 = $row['level1_id'];
            }
            if((strlen(trim($row['level2_id']) > 0))&&(!array_key_exists($row['level2_id'], $data[$idx_lvl1][0]))){
                //start popular items : this is the slowest part of this function
                $down_cat = $this->product_model->selectChild($row['level2_id']);	
                //$down_cat = Array ( 0 => 18, 1 => 19, 2 => 20, 3 => 21, 4 => 22, 5 => 23);
				if((count($down_cat) === 1)&&(trim($down_cat[0]) === ''))
					$down_cat = array();
				array_push($down_cat, $row['level2_id']);
				$pitem = $this->product_model->getPopularitem($down_cat,6);		
                //end popular items                
                
                $data[$idx_lvl1][0][$row['level2_id']] =  array(
                        'id_cat' => $row['level2_id'],
                        'name' => $row['level2_name'],
                        6 => array(),
                        'popular' => $pitem,
                    );
                $idx_lvl2 = $row['level2_id'];
            }
            if(strlen(trim($row['level3_id']) > 0)){
                array_push($data[$idx_lvl1][0][$idx_lvl2][6], array(
                        'id_cat' => $row['level3_id'],
                        'name' => $row['level3_name'],));
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