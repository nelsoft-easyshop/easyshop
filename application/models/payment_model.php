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

    function payment($paymentType,$invoice_no,$ItemTotalPrice,$ip,$member_id,$productstring,$productCount,$apiResponse)
    {
        $query = $this->sqlmap->getFilenameID('payment','payment_transaction');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':payment_type',$paymentType,PDO::PARAM_INT);
        $sth->bindParam(':invoice_no',$invoice_no,PDO::PARAM_STR);
        $sth->bindParam(':total_amt',$ItemTotalPrice,PDO::PARAM_STR);
        $sth->bindParam(':ip',$ip,PDO::PARAM_STR);
        $sth->bindParam(':member_id',$member_id,PDO::PARAM_INT);
        $sth->bindParam(':string',$productstring,PDO::PARAM_STR);
        $sth->bindParam(':product_count',$productCount,PDO::PARAM_INT);
        $sth->bindParam(':data_response',$apiResponse,PDO::PARAM_STR);

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
		
		if($string === 'buyer'){
			$this->email->subject($this->lang->line('notification_subject_buyer'));
			$msg = $this->parser->parse('templates/email_purchase_notification_buyer',$data,true);
		}
		else if($string === 'seller'){
			$this->email->subject($this->lang->line('notification_subject_seller'));
			$msg = $this->parser->parse('templates/email_purchase_notification_seller',$data,true);
		}
		else if($string === 'return_payment'){
			$this->email->subject($this->lang->line('notification_returntobuyer'));
			$msg = $this->parser->parse('templates/email_returntobuyer',$data,true);
		}
		
		$this->email->to($email);
		//$this->email->to('janz.stephen@gmail.com');
		
		$this->email->message($msg);
		$result = $this->email->send();

		$errmsg = $this->email->print_debugger();
		
		return $result;
	}
	
	/*
	 *Code	Description
	 *200	Successfully Sent
	 *201	Message Queued
	 *100	Not Authorized
	 *101	Not Enough Balance
	 *102	Feature Not Allowed
	 *103	Invalid Options
	 *104	Gateway Down
	 */
	//function sendNotificationMobile($mobile,$user)
	function sendNotificationMobile()
	{
		$fields = array();
		$fields["api"] = "dgsMQ8q77hewW766aqxK";
		
		$fields["number"] = 9177050441; //safe use 63
		//$fields["number"] = $mobile;
		
		$fields["message"] = 'Test message';
		$fields["from"] = 'Easyshop.ph';
		$fields_string = http_build_query($fields);
		$outbound_endpoint = "http://api.semaphore.co/api/sms";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $outbound_endpoint);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		
		return $output;
	}
	
	/*
	 *	Function to get Transaction Details for summary in notification email
	 */
	public function getTransactionDetails($data)
	{
		//devcode
		//$data['member_id'] = 74;
		//$data['order_id'] = 102;
		
		$query = $this->sqlmap->getFilenameID('payment','getTransactionDetails');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':buyer_id',$data['member_id']);
        $sth->bindParam(':order_id',$data['order_id']);
		$sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
		
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
			
			if(!isset($data['seller'][$value['seller_id']])){
				$data['seller'][$value['seller_id']]['email'] = $value['seller_email'];
				$data['seller'][$value['seller_id']]['seller_name'] = $value['seller'];
				$data['seller'][$value['seller_id']]['totalprice'] = 0;
			}
			if(!isset($data['seller'][$value['seller_id']]['products'][$value['id_order_product']])){
				$data['seller'][$value['seller_id']]['products'][$value['id_order_product']] = array_slice($temp,9,5);
				$data['seller'][$value['seller_id']]['totalprice'] += $value['baseprice'];
			}
			
			if( $value['is_other'] == 0 ){
				$newattr = array(
					'attr_name' => $temp['attr_name'],
					'attr_value' => $temp['attr_value']
				);
			}else if( $value['is_other'] == 1 ){
				$newattr = array(
					'attr_name' => $temp['field_name'],
					'attr_value' => $temp['value_name']
				);
			}
			$data['products'][$value['id_order_product']]['attr'][] = $newattr;
			$data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['attr'][] = $newattr;
		}
		
		return $data;

	}
	
	/*
	 *	Updates es_order_product status
	 *	Checks es_order_product status if all orders have buyer/seller response and updates
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
 

	function getShippingDetails($product_id ,$product_item_id,$city_id,$region_id,$major_island_id)
	{
		$query = "
			SELECT 
			  es_location_lookup.id_location AS id
			  , es_location_lookup.location
			  , es_location_lookup.`type`
			  , es_location_lookup.`parent_id`
			  , COALESCE(price, '0') AS price
			  , shipping.product_id 
			  , COALESCE(product_item_id, 'Not Available') AS product_item_id
			  , is_cod 
			  , CASE es_location_lookup.`type` 
			  WHEN '1' THEN   
				  IF(es_location_lookup.id_location = (SELECT COALESCE(:major_island_id,0)), 'Available', 'Not Avialable')
			  WHEN '2' THEN 
			      IF(es_location_lookup.id_location = (SELECT COALESCE(:region_id,0)), 'Available', 'Not Avialable')
			  WHEN '3' THEN 
				  IF(es_location_lookup.id_location = (SELECT COALESCE(:city_id,0)), 'Available', 'Not Avialable')
			  END AS availability
			FROM
			  `es_location_lookup` 
			  LEFT OUTER JOIN 
			    (
			    	SELECT 
					  a.`product_item_id`
					  , b.product_id
					  , c.location AS shipping_location
					  , c.`id_location` AS shipping_id_location
					  , c.`type` AS shipping_type
					  , b.`price` 
					  , d.is_cod 
					FROM
					  `es_product_shipping_detail` a
					  , `es_product_shipping_head` b
					  , `es_location_lookup` c 
					  , `es_product` d 
					WHERE b.`id_shipping` = a.`shipping_id` 
					  AND b.`location_id` = c.`id_location` 
					  AND d.`id_product` = b.`product_id` 
   				) AS shipping 
			    ON shipping.shipping_id_location = es_location_lookup.`id_location` 
			WHERE es_location_lookup.`type` IN (1, 2, 3) 
			  AND COALESCE(product_item_id, 'Not Available') != 'Not Available' 
			  AND
			  (CASE es_location_lookup.`type` 
			    WHEN '1' THEN   
					IF(es_location_lookup.id_location = (SELECT COALESCE(:major_island_id,0)), 'Available', 'Not Avialable')
			    WHEN '2' THEN 
					IF(es_location_lookup.id_location = (SELECT COALESCE(:region_id,0)), 'Available', 'Not Avialable')
			    WHEN '3' THEN 
					IF(es_location_lookup.id_location = (SELECT COALESCE(:city_id,0)), 'Available', 'Not Avialable')
			  END ) = 'Available'
			  AND `product_id` = :product_id  
			  AND `product_item_id` = :product_item_id
			  ORDER BY price DESC LIMIT 1
		";
		$sth = $this->db->conn_id->prepare($query);

		$sth->bindParam(':product_id', $product_id,PDO::PARAM_INT);
		$sth->bindParam(':product_item_id', $product_item_id,PDO::PARAM_INT); 
		$sth->bindParam(':city_id', $city_id,PDO::PARAM_INT); 
		$sth->bindParam(':region_id', $region_id,PDO::PARAM_INT); 
		$sth->bindParam(':major_island_id', $major_island_id,PDO::PARAM_INT); 
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		 
		return $row ;
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
	function getCityOrRegionOrMajorIsland($id_location)
	{ 
		$query = "
		SELECT 
		  a.*
		  , b.location AS parent_location 
		FROM
		  `es_location_lookup` a
		  , es_location_lookup b 
		WHERE a.`parent_id` = b.`id_location`
		AND a.`id_location` = :id_location
		";
		$sth = $this->db->conn_id->prepare($query);
 
		$sth->bindParam(':id_location', $id_location,PDO::PARAM_INT);
     	$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
	 	   
		return $row[0];
	}

 
 
}


/* End of file payment_model.php */
/* Location: ./application/models/payment_model.php */