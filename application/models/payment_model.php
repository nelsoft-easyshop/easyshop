<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class payment_model extends CI_Model
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library("sqlmap");
	}	


	   function getUserAddress($member_id)
    {
        $query = $this->sqlmap->getFilenameID('payment', 'get_address');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id_member', $member_id);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        return $row;
        
    }

    function payment($invoice_no,$ItemTotalPrice,$ip,$member_id,$productstring,$productCount)
    {
        $query = $this->sqlmap->getFilenameID('payment','payment_transaction');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':invoice_no',$invoice_no,PDO::PARAM_STR);
        $sth->bindParam(':total_amt',$ItemTotalPrice,PDO::PARAM_STR);
        $sth->bindParam(':ip',$ip,PDO::PARAM_STR);
        $sth->bindParam(':member_id',$member_id,PDO::PARAM_INT);
        $sth->bindParam(':string',$productstring,PDO::PARAM_STR);
        $sth->bindParam(':product_count',$productCount,PDO::PARAM_INT);

        $sth->execute();
       
        $row = $sth->fetch(PDO::FETCH_ASSOC);
  
        return $row;
        
    }
	
	
	public function sendNotificationEmail($data, $email, $string)
	{
		$this->load->library('email');	
		$this->load->library('parser');
		
		$this->email->set_newline("\r\n");
		$this->email->from('noreply@easyshop.ph', 'Easyshop.ph');
		$this->email->subject($this->lang->line('notification_subject'));
		
		if($string === 'buyer'){
			$msg = $this->parser->parse('templates/email_purchase_notification_buyer',$data,true);
		}
		else if($string === 'seller'){
			$msg = $this->parser->parse('templates/email_purchase_notification_seller',$data,true);
		}
		//print($msg);
		//die();
		
		//$this->email->to($email);
		$this->email->to('janz.stephen@gmail.com');
		
		$this->email->message($msg);
		$result = $this->email->send();

		$errmsg = $this->email->print_debugger();
		
		return $result;
	}
	
	/*
	 *	Function to get Transaction Details for summary in notification email
	 */
	public function getTransactionDetails($data)
	{
		//devcode
		//$data['member_id'] = 1;
		//$data['order_id'] = 4;
		
		$query = $this->sqlmap->getFilenameID('payment','getTransactionDetails');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':buyer_id',$data['member_id']);
        $sth->bindParam(':order_id',$data['order_id']);
		$sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		/*
		print('<pre>');
		print_r($row);
		print('</pre>');
		*/
		
		$data = array(
			'id_order' => $row[0]['id_order'],
			'dateadded' => $row[0]['dateadded'],
			'buyer_name' => $row[0]['buyer'],
			'buyer_email' => $row[0]['buyer_email'],
			'totalprice' => $row[0]['totalprice'],
			'products' => array()
		);
		
		foreach($row as $value){
			$temp = $value;
			if(!isset($data['products'][$value['id_order_product']])){
				$data['products'][$value['id_order_product']] = array_slice($temp,6,8);
			}
			$data['products'][$value['id_order_product']]['attr'][] = array_slice($temp,14,2);
			if(!isset($data['seller'][$value['seller_id']])){
				$data['seller'][$value['seller_id']]['email'] = $value['seller_email'];
				$data['seller'][$value['seller_id']]['seller_name'] = $value['seller'];
				$data['seller'][$value['seller_id']]['totalprice'] = 0;
			}
			if(!isset($data['seller'][$value['seller_id']]['products'][$value['id_order_product']])){
				$data['seller'][$value['seller_id']]['products'][$value['id_order_product']] = array_slice($temp,9,5);
				$data['seller'][$value['seller_id']]['totalprice'] += $value['baseprice'];
			}
			$data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['attr'][] = array_slice($temp,14,2);
			
		}
		
		/*
		print('<pre>');
		print_r($data);
		print('</pre>');
		die();
		*/
		
		return $data;

	}
	
	/*
	 *	Updates es_order_product_status
	 *	Checks es_order_product_status if all orders have buyer/seller response and updates
	 *		es_order as complete
	 *	USED IN MEMBERPAGE - TRANSACTIONS TAB
	 */
	function updateTransactionStatus($data)
	{
		$query = $this->sqlmap->getFilenameID('payment','updateTransactionStatus');
		$sth = $this->db->conn_id->prepare($query);

		$sth->bindParam(':status', $data['status']);
		$sth->bindParam(':order_product_id', $data['order_product_id']);
		$sth->bindParam(':order_id', $data['transaction_num']);
		$result = $sth->execute();
		
		//print_r($sth->errorInfo());
		return $result;
	}
 

	function getShippingDetails($product_id,$product_item_id)
	{
		$query = "
			SELECT 
			  a.`product_item_id`
			  , b.product_id
			  , c.location 
			  , c.`id_location`
			  , c.`type`
			FROM
			  `es_product_shipping_detail` a
			  , `es_product_shipping_head` b
			  , `es_location_lookup` c 
			WHERE b.`id_shipping` = a.`shipping_id` 
			AND b.`location_id` = c.`id_location`
			AND b.`product_id` = :product_id
			AND a.`product_item_id` = :product_item_id
		";
		$sth = $this->db->conn_id->prepare($query);

		$sth->bindParam(':product_id', $product_id,PDO::PARAM_INT);
		$sth->bindParam(':product_item_id', $product_item_id,PDO::PARAM_INT); 
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		 
		return $row[0];
	}

	function getCityFromRegion($id_location)
	{
		$query = "
		SELECT * FROM `es_location_lookup` WHERE `type` = 3 AND parent_id = :id_location
		";
		$sth = $this->db->conn_id->prepare($query);
 
		$sth->bindParam(':id_location', $id_location,PDO::PARAM_INT); 
     	$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
	 
		return $row;
	}
	function getRegionOrMajorIsland($id_location,$type)
	{
		$query = "
		SELECT * FROM `es_location_lookup` WHERE `type` = :type AND id_location = :id_location
		";
		$sth = $this->db->conn_id->prepare($query);
 
		$sth->bindParam(':id_location', $id_location,PDO::PARAM_INT);
		$sth->bindParam(':type', $type,PDO::PARAM_INT); 
     	$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_COLUMN, 1);
	 	  
		return $row[0];
	}

 
 
}


/* End of file payment_model.php */
/* Location: ./application/models/payment_model.php */