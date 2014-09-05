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

    public function getUserById($uid){
    
        $query = "SELECT * FROM es_member WHERE id_member = :id";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$uid)	;
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $row = (!empty($row))?$row:false;
        return $row;
    
    }
    
    public function getUserByUsername($username){
    
        $query = "SELECT * FROM es_member WHERE username = :username";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':username',$username);	
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $row = (!empty($row))?$row:false;
        return $row;
    
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
    
    /**
     * Returns the admin member by id
     *
     * @param  integer $userid
     * @return mixed
     */
    public function getAdminUser($userid)
    {
        $query = "SELECT * FROM es_admin_member WHERE id_admin_member = :userid";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':userid',$userid); 
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $row = (!empty($row))?$row:false;
        
        return $row;
    }
    
    /**
     *  Fetch users $member_id is subscribed to.
     *
     *  @param integer $memberId
     *  @param inetger $limit
     *  @return array
     */
    function getFollowing($memberId, $limit = null)
    {
        $query = $this->xmlmap->getFilenameID('sql/users','getFollowing'); 
        $query .=  $limit === null ? '' : 'limit :limit';
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$memberId, PDO::PARAM_INT);
        if($limit !== null){
            $sth->bindParam(':limit',$limit, PDO::PARAM_INT);
        }
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($row as $k=>$r){
            if(($r['imgurl'] === "") || (!file_exists($r['imgurl']))){
                $row[$k]['imgurl'] = "assets/user/default/60x60.png";
            }
            else{
                $row[$k]['imgurl'] = $r['imgurl'] . "/60x60.png";
            }
        }
        
        return $row;
    }

    public function CountUsers()
    {
        $query = $this->xmlmap->getFilenameID('sql/users','getUserCount');
        $sth = $this->db->conn_id->prepare($query);
        $sth->execute(); 
        $number_of_rows = $sth->fetchColumn(); 
        
        return $number_of_rows;
    }
    
    

    /**
     * Return list of users following a certain user
     *
     * @param integer $memberId
     * @return array
     *
     */
    public function getFollowers($memberId)
    {
        $query = $this->xmlmap->getFilenameID('sql/users','getFollowers'); 
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$memberId, PDO::PARAM_INT);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($row as $k=>$r){
            if(($r['imgurl'] === "") || (!file_exists($r['imgurl']))){
                $row[$k]['imgurl'] = "assets/user/default/60x60.png";
            }
            else{
                $row[$k]['imgurl'] = $r['imgurl'] . "/60x60.png";
            }
        }
        
        return $row;
    }
    
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
