<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class messages_model extends CI_Model
{
    function __construct()
    {
	parent::__construct();
        $this->load->library("sqlmap");
    }
    public function send_message($sender,$recipient,$msg){
           
		$query = $this->sqlmap->getFilenameID('messages', 'send_message');
			
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':to_id',$recipient, PDO::PARAM_INT);
		$sth->bindParam(':from_id',$sender, PDO::PARAM_INT);
		$sth->bindParam(':message',$msg);
		$sth->execute();
		
		return $sth->rowCount();
    }
	        
    public function get_message($user_id){
        $query = $this->sqlmap->getFilenameID('messages', 'inbox_message');
	
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$user_id, PDO::PARAM_INT);
        $sth->execute();
        $recieveditems = $sth->fetchAll(PDO::FETCH_ASSOC);
	
        $query = $this->sqlmap->getFilenameID('messages', 'sentbox_message');
	
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$user_id, PDO::PARAM_INT);
        $sth->execute();

        $sentitems = $sth->fetchAll(PDO::FETCH_ASSOC);
		$result = array();
		$unread_msgs = 0;
		foreach($recieveditems as $db_row){
			if( $db_row['opened'] == 0){$unread_msgs++;}
		}
		$result['unread_msgs']=$unread_msgs;
		$result['inbox']=$recieveditems;
		$result['sentbox']=$sentitems;
	
        return $result;
	
    }
    
    public function get_recepientID($username){

        $query = $this->sqlmap->getFilenameID('messages', 'get_recepientID');
		
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':username',$username, PDO::PARAM_INT);
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
	    $result = $result[0]['id_member'];
		} else {
			$result = "false";
		}
        return $result;
    }
    
    public function delete_msg($id,$user_id) {
		$query = "UPDATE `es_messages`
				SET `is_delete` = CASE
						WHEN `is_delete` = '0' THEN `is_delete` + (CASE WHEN `from_id` = $user_id THEN 2 ELSE 1 END) 
						WHEN `is_delete` = '1' THEN `is_delete` + 2
						WHEN `is_delete` = '2' THEN `is_delete` + 1
						ELSE 3
					   END
				WHERE `id_msg` IN($id);";
		$sth = $this->db->conn_id->prepare($query);
		$sth->execute();
		return $sth->rowCount();
    }
	public function get_all_messages ($id) {
           
		$query = $this->sqlmap->getFilenameID('messages', 'all_messages');
			
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$id, PDO::PARAM_INT);
		$sth->execute();
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        $result = array();
		$unread_msg = 0;
		for($ctr = 0 ; $ctr < sizeof($rows) ; $ctr++){
		    $inbox=$rows[$ctr]['to_id'].$rows[$ctr]['from_id'];
		    $sentbox=$rows[$ctr]['from_id'].$rows[$ctr]['to_id'];
			$status = ($rows[$ctr]['from_id'] == $id ? "sender" : "reciever");
		    if(array_key_exists($sentbox,$data)){ //sentbox
				    $data[$sentbox][$rows[$ctr]['id_msg']] =$rows[$ctr];
					$data[$sentbox][$rows[$ctr]['id_msg']]['status'] = $status;
		    }elseif(array_key_exists($inbox,$data) ){ //inbox
				    $data[$inbox][$rows[$ctr]['id_msg']] = $rows[$ctr];
					$data[$inbox][$rows[$ctr]['id_msg']]['status'] =$status;
			}else {
				if($status == "sender" && ($rows[$ctr]['is_delete'] == '0' || $rows[$ctr]['is_delete'] == '1') ) {				        
				    $data[$sentbox][$rows[$ctr]['id_msg']] = $rows[$ctr];				
				    $data[$sentbox][$rows[$ctr]['id_msg']]['status'] = 'sender';		
				    $data[$sentbox][$rows[$ctr]['id_msg']]['name'] = ($rows[$ctr]['from_id'] == $id ? $rows[$ctr]['recipient'] : $rows[$ctr]['sender']);		
				    
				} else if($status == "reciever" && ($rows[$ctr]['is_delete'] == '0' || $rows[$ctr]['is_delete'] == '2') ){	        
				    $data[$sentbox][$rows[$ctr]['id_msg']] = $rows[$ctr];				
				    $data[$sentbox][$rows[$ctr]['id_msg']]['status'] = 'reciever';		
				    $data[$sentbox][$rows[$ctr]['id_msg']]['name'] = ($rows[$ctr]['from_id'] == $id ? $rows[$ctr]['recipient'] : $rows[$ctr]['sender']);		
				    if ($rows[$ctr]['opened'] == 0 )$unread_msg++ ;
				}	
			}
		}
		$result['messages'] = array_values($data);
		for($x = 0;$x < sizeof($result['messages']); $x++){
			$ask = "";
			foreach($result['messages'][$x] as $key  =>$row){
				$delete = $result['messages'][$x][$key]['is_delete'];
				$status = $result['messages'][$x][$key]['status'];
				if($status == "sender" && ($delete == '0' || $delete == '1')){
				}else if($status == "reciever" && ($delete == '0' || $delete == '2')){
				}else{
					$ask = "true";
				unset($result['messages'][$x][$key]);
				}
			}
		}
		$result['unread_msgs'] = $unread_msg;
		//print "<pre>";
		//print_r($result);
		//print "</pre>";
		return $result;
	}
	
	public function is_seened($id,$from_ids){
		
		$query = "UPDATE `es_messages`
				SET `opened` = '1'
				WHERE `to_id` = :id AND `id_msg` IN($from_ids);";
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$id, PDO::PARAM_INT);
		
		$sth->execute();
		
		$row = $sth->rowCount();
		if($row == 0 ){
				return false;
		}
		else{
				return true;
		}
	}
}

?>