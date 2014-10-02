<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class messages_model extends CI_Model
{
    function __construct()
    {
	parent::__construct();
        $this->load->library("xmlmap");
    }
    public function send_message($sender,$recipient,$msg){
           
	$query = $this->xmlmap->getFilenameID('sql/messages', 'send_message');

	$sth = $this->db->conn_id->prepare($query);
	$sth->bindParam(':to_id',$recipient, PDO::PARAM_INT);
	$sth->bindParam(':from_id',$sender, PDO::PARAM_INT);
	$sth->bindParam(':message',$msg);
	$sth->bindParam(':date_time_sent', date('Y-m-d H:i:s'));
	$sth->execute();
	
	return $sth->rowCount();
    }
	        
    public function get_message($user_id){
        $query = $this->xmlmap->getFilenameID('sql/messages', 'inbox_message');
	
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$user_id, PDO::PARAM_INT);
        $sth->execute();
        $recieveditems = $sth->fetchAll(PDO::FETCH_ASSOC);
	
        $query = $this->xmlmap->getFilenameID('sql/messages', 'sentbox_message');
	
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
	  
    public function get_all_messages ($id,$todo=false) {
		
	$query = $this->xmlmap->getFilenameID('sql/messages', 'all_messages');
		
	$sth = $this->db->conn_id->prepare($query);
	$sth->bindParam(':id',$id, PDO::PARAM_INT);
	$sth->execute();
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	$data = array();
	$result = array();
	$unread_msg = 0;
	$unread_conve = 0;
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
			    $data[$sentbox][$rows[$ctr]['id_msg']]['unreadConve'] = 0;		
			    $data[$sentbox][$rows[$ctr]['id_msg']]['name'] = ($rows[$ctr]['from_id'] == $id ? $rows[$ctr]['recipient'] : $rows[$ctr]['sender']);		
			    if ($rows[$ctr]['opened'] == 0 )$unread_msg++ ;
			}	
		}
	}
	$result['messages'] = array_values($data);
	$size = sizeof($result['messages']);
	for($x = 0;$x < $size; $x++){ //get all msgs that is not deleted by receiver & count each msgs in conversation
		$ask = 0;
		foreach($result['messages'][$x] as $key  =>$row){
			$delete = $result['messages'][$x][$key]['is_delete'];
			$status = $result['messages'][$x][$key]['status'];
			$is_opened = $result['messages'][$x][$key]['opened'];
			if($status == "sender" && ($delete == '0' || $delete == '1')){
			}else if($status == "reciever" && ($delete == '0' || $delete == '2')){
			    if($is_opened == '0'){
					$ask = ++$ask;
			    }
			}else{
			    unset($result['messages'][$x][$key]);
			}
			$first_key = reset($result['messages'][$x])['id_msg'];
		}
		$result['messages'][$x][$first_key]['unreadConve'] = $ask;
	    $result['unread_msgs'] = $unread_msg;
	}
			
	if($todo == "Get_UnreadMsgs"){
		$size  = sizeof($result['messages']);
		for($x = 0;$size > $x;$x++){
			foreach($result['messages'][$x] as $key => $data){
				if(((isset($data['name']) && $data['to_id'] == $id) && $data['opened'] == '1' ) || ($data['status'] == "sender" && isset($data['name']) )){
					unset($result['messages'][$x]);
				}
			}
		}
		$result['Case'] = "UnreadMsgs";
	}
	
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