<?php

class user_model extends CI_Model {

    function __construct() 
    {
        parent::__construct();
        $this->load->library("xmlmap");
    }
	
    public function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) //check ip from share internet 
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip if pass from proxy 
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
    }

    public function verify_member($data = array()) {
        $username = $data['login_username'];
        $password = $data['login_password'];
        $ip = $this->getRealIpAddr();
        $query = $this->xmlmap->getFilenameID('sql/users', 'user_login');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':username', $username);
        $sth->bindParam(':password', $password);
        $sth->bindParam(':ip', $ip);
		$sth->execute();
        $row = $sth->fetch();

        return $row;

    }

    public function logout(){
        $sid = $this->session->userdata('member_id');
        $sname = $this->session->userdata('usersession');
        $query = $this->xmlmap->getFilenameID('sql/users','user_logout');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':user_session',$sname);
        $sth->bindParam(':id_member',$sid);
        $sth->execute();
        $row = $sth->fetch();
        return $row;
    }
	
    public function getUsername($id){
        $query = $this->xmlmap->getFilenameID('sql/users','getUserName');
		
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id);
        $sth->execute();
        $row = $sth->fetch();
		
        return $row;
    }
	
	//COOKIE FUNCTIONS
	public function create_cookie($cookieval){
		$cookiedata = array(
			'name' => 'es_usr',
			'value' => $cookieval,
			'expire' => '86500'
		);
		set_cookie($cookiedata);
	}
	
	public function dbsave_cookie_keeplogin($temp = array()){
		$query = $this->xmlmap->getFilenameID('sql/users', 'store_cookie_keeplogin');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id_member', $temp['member_id']);
		$sth->bindParam(':ip', $temp['ip']);
		$sth->bindParam(':useragent', $temp['useragent']);
		$sth->bindParam(':usersession', $temp['session']);
        $sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	
	
	public function cookie_login($temp){
	    $query = $this->xmlmap->getFilenameID('sql/users', 'cookie_login');
	    $sth = $this->db->conn_id->prepare($query);
	    $sth->bindParam(':userip', $temp['userip']);
	    $sth->bindParam(':useragent', $temp['useragent']);
	    $sth->bindParam(':token', $temp['token']);
	    $sth->bindParam(':usersession', $temp['usersession']);
	    $sth->execute();
	    $row = $sth->fetch(PDO::FETCH_ASSOC);

	    return $row;
	}
	
	public function dbdelete_cookie_keeplogin($temp = array()){
	    $query = $this->xmlmap->getFilenameID('sql/users', 'delete_cookie_keeplogin');
	    $sth = $this->db->conn_id->prepare($query);
	    $sth->bindParam(':id_member', $temp['member_id']);
	    $sth->bindParam(':ip', $temp['ip']);
	    $sth->bindParam(':useragent', $temp['useragent']);
	    $sth->bindParam(':token', $temp['token']);
	    $sth->execute();
	}

	public function getUserAccessDetails($uid)
	{
		$query = $this->xmlmap->getFilenameID('sql/users','getUserAccessDetails');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$uid);	
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);

		return $row;
	}
    
    


	
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
