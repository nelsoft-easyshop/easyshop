<?php
class MY_Controller extends CI_Controller 
{
	function __construct()
    {
        parent::__construct();
                
		$this->config->set_item('base_url',"https://".$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/");
        #$this->config->set_item('base_url',"http://".$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/");
		$this->load->model("user_model");
		$this->load->model("cart_model");
		$this->load->model("product_model");
    }
	
	function fill_header()
	{	
		if(strval(uri_string()) <> "login"){
			$this->session->set_userdata('user_cur_loc', uri_string());
		}
		else{
            #if user_cur_loc session is empty, set it to home else leave it as is
            #Fixes a bug that makes the user_cur_loc session variable contain rubbish URIs
            if(!$this->session->userdata('user_cur_loc')){
               $this->session->set_userdata('user_cur_loc','home');
            }
		}


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
		
		$data = array(
			'logged_in' => $logged_in,
			'uname' => $uname,
			'total_items'=> $this->cart_model->cart_size(),
			'category_search' => $this->product_model->getFirstLevelNodeAlphabetical(),
			'user_cur_loc' => $this->session->userdata('user_cur_loc'),
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
        
    function getcat() {
        
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
    

    #Experimental
    function getcatfast(){
        $rows = $this->product_model->getCatFast();
        $data = array();
        $x = 0;
        $y = $x;
        foreach($rows as $row){
            if(!isset($data[$x])){
                $data[$x] = array();
                $f = 1;
           }
           
            if(!in_array($row['level1_id'], $data[$x])){
                $data[$x]['id_cat'] = $row['level1_id'];
                $data[$x]['NAME'] = $row['level1_name'];
                $data[$x]['path'] = $row['img_level1'];
                $data[$x][0] = array();
                if($f === 0)
                    $x++;
            }
            else{
                $f = 0;                
            }
        }
        return $data;
    }

}


/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */