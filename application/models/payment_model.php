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

    function payment($paymentType,$invoice_no,$ItemTotalPrice,$ip,$member_id,$productstring,$productCount,$apiResponse,$tid)
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
        $sth->bindParam(':tid',$tid,PDO::PARAM_STR);

        $sth->execute();
       
        $row = $sth->fetch(PDO::FETCH_ASSOC);
  
        return $row;
        
    }

    function checkMyDp($transactionId)
    {
    	$query = "CALL es_sp_checkDP(:tid)";

    	$sth = $this->db->conn_id->prepare($query);
    	$sth->bindParam(':tid',$transactionId,PDO::PARAM_STR); 
    	$sth->execute();
    }

    function deductQuantity($productId,$itemId,$qty)
    {
    	$query = "
		UPDATE 
		  `es_product_item` 
		SET
		  `quantity` = `quantity` - :quantity 
		WHERE `product_id` = :product_id 
		  AND `id_product_item` = :item_id;
    	";
        ;
    	$sth = $this->db->conn_id->prepare($query);
    	$sth->bindParam(':quantity',$qty,PDO::PARAM_INT);
    	$sth->bindParam(':product_id',$productId,PDO::PARAM_INT);
    	$sth->bindParam(':item_id',$itemId,PDO::PARAM_INT);
    	
    	if ($sth->execute()){
		  // success
    		return 1;
		}
		else{
		  	return 0;
		}
    }

    function updatePaymentIfComplete($id,$data,$tid)
    {
        $query = $this->sqlmap->getFilenameID('payment','updatePaymentIfComplete');
    	$sth = $this->db->conn_id->prepare($query);
    	$sth->bindParam(':data',$data,PDO::PARAM_STR);
    	$sth->bindParam(':id_order',$id,PDO::PARAM_INT);
    	$sth->bindParam(':tid',$tid,PDO::PARAM_STR);
    	
    	if ($sth->execute()){
		  // success
    		return 1;
		}
		else{
		  	return 0;
		}
    }
	
	
	public function sendNotificationEmail($data, $email, $string)
	{
		$this->load->library('email');	
		$this->load->library('parser');
		
		$this->email->set_newline("\r\n");
		$this->email->from('noreply@easyshop.ph', 'Easyshop.ph');
		$this->email->attach(getcwd() . "/assets/images/img_logo.png", "inline");
		
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
		//$this->email->to('janz.stephen@gmail.com'); // Test
		
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
	function sendNotificationMobile($mobile, $msg)
	{
		$fields = array();
		$fields["api"] = "dgsMQ8q77hewW766aqxK";
		
		//$fields["number"] = 9177050441; //safe use 63
		$fields["number"] = $mobile;
		
		$fields["message"] = $msg;
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
	 *	Sent right after transaction is made
	 *  $data = array(
	 *		'member_id' => Member ID who made the purchase (buyerID)
	 *		'order_id'	=> Transaction Number
	 *		'invoice_no' => Invoice number
	 *	)
	 */
	public function getPurchaseTransactionDetails($data)
	{
		$query = $this->sqlmap->getFilenameID('payment','getPurchaseTransactionDetails');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':buyer_id',$data['member_id']);
        $sth->bindParam(':order_id',$data['order_id']);
		$sth->bindParam(':invoice_no', $data['invoice_no']);
		$sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		$data = array(
			'id_order' => $row[0]['id_order'],
			'dateadded' => $row[0]['dateadded'],
			'buyer_name' => $row[0]['buyer'],
			'buyer_email' => $row[0]['buyer_email'],
			'buyer_contactno' => $row[0]['buyer_contactno'],
			'totalprice' => $row[0]['totalprice'],
			'invoice_no' => $row[0]['invoice_no'],
			'products' => array()
		);
		
		foreach($row as $value){
			$temp = $value;
			if(!isset($data['products'][$value['id_order_product']])){
				$data['products'][$value['id_order_product']] = array_slice($temp,6,8);
				$data['products'][$value['id_order_product']]['order_product_id'] = $temp['id_order_product'];
			}
			
			if(!isset($data['seller'][$value['seller_id']])){
				$data['seller'][$value['seller_id']]['email'] = $value['seller_email'];
				$data['seller'][$value['seller_id']]['seller_name'] = $value['seller'];
				$data['seller'][$value['seller_id']]['seller_contactno'] = $value['seller_contactno'];
				$data['seller'][$value['seller_id']]['totalprice'] = 0;
			}
			if(!isset($data['seller'][$value['seller_id']]['products'][$value['id_order_product']])){
				$data['seller'][$value['seller_id']]['products'][$value['id_order_product']] = array_slice($temp,9,5);
				$data['seller'][$value['seller_id']]['totalprice'] += preg_replace('/\,/', '' , $value['finalprice']);
				$data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['order_product_id'] = $value['id_order_product'];
			}
			
			if(!isset($data['products'][$value['id_order_product']]['attr'])){
				$data['products'][$value['id_order_product']]['attr'] = array();
			}
			if(!isset($data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['attr'])){
				$data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['attr'] = array();
			}
			if( $value['is_other'] === '0' ){
				array_push($data['products'][$value['id_order_product']]['attr'], array('attr_name' => $temp['attr_name'],'attr_value' => $temp['attr_value']));
				array_push($data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['attr'], array('attr_name' => $temp['attr_name'],'attr_value' => $temp['attr_value']));
			}else if( $value['is_other'] === '1' ){
				array_push($data['products'][$value['id_order_product']]['attr'], array('attr_name' => $temp['field_name'], 'attr_value' => $temp['value_name']));
				array_push($data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['attr'], array('attr_name' => $temp['field_name'],'attr_value' => $temp['value_name']));
			}else{
				array_push($data['products'][$value['id_order_product']]['attr'], array('attr_name' => 'N/A', 'attr_value' => 'N/A'));
				array_push($data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['attr'], array('attr_name' => 'N/A','attr_value' => 'N/A'));
			}
		}
		
		return $data;
	}
	
	/*
	 *	Function to get product order transaction details for email notification upon transaction response
	 *	Used by memberpage->transactionResponse function 
	 */
	public function getOrderProductTransactionDetails($data)
	{
		$query = $this->sqlmap->getFilenameID('payment','getOrderProductTransactionDetails');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':order_product_id', $data['order_product_id']);
		$sth->bindParam(':order_id', $data['transaction_num']);
		$sth->bindParam(':invoice_num', $data['invoice_num']);
		$sth->bindParam(':member_id', $data['member_id']);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		$parseData = array(
			'invoice_no' => $row[0]['invoice_no'],
			'order_product_id' => $row[0]['id_order_product'],
			'product_name' => $row[0]['product_name'],
			'order_quantity' => $row[0]['order_quantity'],
			'price' => number_format($row[0]['total'], 2, '.', ','),
			'attr' => array()
		);
		
		if($data['status'] === 1){ // if forward to seller
			$parseData['user'] = $row[0]['buyer'];
			$parseData['email'] = $row[0]['seller_email'];
			$parseData['mobile'] = trim($row[0]['seller_contactno']);
		} else if($data['status'] === 2){ // if return to buyer
			$parseData['user'] = $row[0]['seller'];
			$parseData['email'] = $row[0]['buyer_email'];
			$parseData['mobile'] = trim($row[0]['buyer_contactno']);
		}
		
		foreach( $row as $r){
			if($r['is_other'] === '0'){
				array_push($parseData['attr'], array('field' => ucwords(strtolower($r['attr_name'])), 'value' => ucwords(strtolower($r['attr_value'])) ));
			}else if($r['is_other'] === '1'){
				array_push($parseData['attr'], array('field' => ucwords(strtolower($r['field_name'])), 'value' => ucwords(strtolower($r['value_name'])) ));
			}
		}
		
		return $parseData;
	}
	
	
	/*	STORED PROCEDURE
	 *	Updates es_order_product status
	 *	Checks es_order_product status if all orders have buyer/seller response and updates
	 *		es_order as complete
	 *	USED IN MEMBERPAGE - TRANSACTIONS TAB
	 *
	 *	Args:
	 *		transaction_num, order_product_id, status
	 *		member_id
	 *
	 */
	function updateTransactionStatus($data)
	{
		$query = $this->sqlmap->getFilenameID('payment','updateTransactionStatus');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':status', $data['status']);
		$sth->bindParam(':order_product_id', $data['order_product_id']);
		$sth->bindParam(':order_id', $data['transaction_num']);
		$sth->bindParam(':invoice_num', $data['invoice_num']);
		$sth->bindParam(':member_id', $data['member_id']);
		$sth->execute();
        
		$row = $sth->fetch(PDO::FETCH_ASSOC);
        
	    return $row;
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
			  ORDER BY price ASC LIMIT 1
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

	// Used by add feedback - memberpage
	function checkTransaction($temp){
		$query = $this->sqlmap->getFilenameID('payment','checkTransaction');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':buyer', $temp['buyer']);
		$sth->bindParam(':seller', $temp['seller']);
		$sth->bindParam(':order_id', $temp['order_id']);
		$result = $sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		return $row;
	}
	
	// Used by dragonpay - memberpage
	function checkTransactionBasic($temp)
	{
		$query = $this->sqlmap->getFilenameID('payment','checkTransactionBasic');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':transaction_num', $temp['transaction_num']);
		$sth->bindParam(':invoice_num', $temp['invoice_num']);
		$sth->bindParam(':member_id', $temp['member_id']);
		$result = $sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $row;
	}
	
	// Check order product before saving comment
	function checkOrderProductBasic($temp)
	{
		$query = $this->sqlmap->getFilenameID('payment','checkOrderProductBasic');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id_order_product', $temp['order_product']);
		$sth->bindParam(':order_id', $temp['transact_num']);
		$sth->bindParam(':member_id', $temp['member_id']);
		$result = $sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		return $row;
	}
	
	// Add shipping comment, binded to order_product
	function addShippingComment($temp)
	{
		$query = $this->sqlmap->getFilenameID('payment','addShippingComment');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':order_product', $temp['order_product']);
		$sth->bindParam(':comment', $temp['comment']);
		$sth->bindParam(':member_id', $temp['member_id']);
		$sth->bindParam(':courier', $temp['courier']);
		$sth->bindParam(':tracking_num', $temp['tracking_num']);
		$sth->bindParam(':expected_date', $temp['expected_date']);
		$result = $sth->execute();
		
		return $result;
	}
	
	function addBankDepositDetails($temp)
	{
		$query = $this->sqlmap->getFilenameID('payment','addBankDepositDetails');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':order_id', $temp['order_id']);
		$sth->bindParam(':bank', $temp['bank']);
		$sth->bindParam(':ref_num', $temp['ref_num']);
		$sth->bindParam(':amount', $temp['amount']);
		$sth->bindParam(':date_deposit', $temp['date_deposit']);
		$sth->bindParam(':comment', $temp['comment']);
		$result = $sth->execute();
		
		return $result;
	}
	
	// Used by dragonpay - memberpage
	function updateTransactionStatusBasic($temp)
	{
		$query = $this->sqlmap->getFilenameID('payment','updateTransactionStatusBasic');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':transaction_num', $temp['transaction_num']);
		$sth->bindParam(':invoice_num', $temp['invoice_num']);
		$sth->bindParam(':member_id', $temp['member_id']);
		$sth->bindParam(':order_status', $temp['order_status']);
		$result = $sth->execute();
		
		return $result;
	}
	
	// Used by dragonpay - memberpage
	function addOrderHistory($temp)
	{
		$query = $this->sqlmap->getFilenameID('payment','addOrderHistory');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':order_id', $temp['order_id']);
		$sth->bindParam(':order_status', $temp['order_status']);
		$sth->bindParam(':comment', $temp['comment']);
		$result = $sth->execute();
		
		return $result;
	}
}


/* End of file payment_model.php */
/* Location: ./application/models/payment_model.php */