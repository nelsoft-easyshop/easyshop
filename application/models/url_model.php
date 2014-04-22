<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class url_model extends CI_Model
{
    public function __construct(){
       	parent::__construct();
		$this->load->library("sqlmap");
    }
    
    public function getURLS($type = 'index'){
        $url = array();
        switch($type){
          
            case 'category':
                $query = $this->sqlmap->getFilenameID('url', 'getCategoryURL');
                $sth = $this->db->conn_id->prepare($query);
                $sth->execute();
                $row = $sth->fetchALL(PDO::FETCH_ASSOC);
                foreach($row as $x){
                    array_push($url,$x['category_url']);
                }
                break;
            case 'product':
                $query = $this->sqlmap->getFilenameID('url', 'getProductURL');
                $sth = $this->db->conn_id->prepare($query);
                $sth->execute();
                $row = $sth->fetchALL(PDO::FETCH_ASSOC);
                foreach($row as $x){
                    array_push($url,$x['product_url']);
                }
                break;
            case 'vendor':
                $query = $this->sqlmap->getFilenameID('url', 'getVendorURL');
                $sth = $this->db->conn_id->prepare($query);
                $sth->execute();
                $row = $sth->fetchALL(PDO::FETCH_ASSOC);
                
                foreach($row as $x){
                    array_push($url,$x['vendor_url']);
                }
                break;
            default:
                $url = array('me','sell/step1', 'cart', 'login', 'register', 'registration/success', 'subscription/success');
                break;
        }
        return $url;
    }
}

/* End of file url_model.php */
/* Location: ./application/models/url_model.php */