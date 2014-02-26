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

    function payment($invoice_no,$ItemTotalPrice,$ip,$productstring,$item_count,$optionstring,$option_count,$member_id,$payment_type,$data_item,$data_response,$consignee,$streetno,$street,$city,$brgy,$country,$zipcode,$phone,$cellphone)
    {
        $query = $this->sqlmap->getFilenameID('payment','payment_transaction');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':invoice_no',$invoice_no);
        $sth->bindParam(':total_amt',$ItemTotalPrice);
        $sth->bindParam(':ip',$ip);
        $sth->bindParam(':product_in_cart',$productstring);
        $sth->bindParam(':product_counter',$item_count);
        $sth->bindParam(':product_option',$optionstring);
        $sth->bindParam(':product_option_counter',$option_count);
        $sth->bindParam(':member_id',$member_id);
        $sth->bindParam(':payment_type',$payment_type);
        $sth->bindParam(':payment_items',$data_item);
        $sth->bindParam(':payment_data',$data_response);
 

        $sth->bindParam(':consignee',$consignee);
        $sth->bindParam(':streetno',$streetno);
        $sth->bindParam(':street',$street);
        $sth->bindParam(':city',$city);
        $sth->bindParam(':brgy',$brgy);
        $sth->bindParam(':country',$country);
        $sth->bindParam(':zipcode',$zipcode);
        $sth->bindParam(':phone',$phone);
        $sth->bindParam(':cellphone',$cellphone); 


        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);

  
        return $row;
        
    }
	
	
	public function sendNotificationEmail($data)
	{
		$this->load->library('email');	
		$this->load->library('parser');
		
		$this->email->set_newline("\r\n");
		$this->email->to($data['email']);
		//$this->email->to('janz.stephen@gmail.com');
		$this->email->from('noreply@easyshop.ph', 'Easyshop.ph');
		$this->email->subject($this->lang->line('notification_subject'));
		$msg = $this->parser->parse('templates/email_purchase_notification',$data,true);
		
		//print($msg);
		//die();
		
		$this->email->message($msg);
		$result = $this->email->send();

		$errmsg = $this->email->print_debugger();
		
		return $result;
	}
	
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
			'email' => $row[0]['email'],
			'totalprice' => $row[0]['totalprice'],
			'products' => array()
		);
		
		foreach($row as $value){
			$temp = $value;
			if(!isset($data['products'][$value['id_order_product']])){
				$data['products'][$value['id_order_product']] = array();
				$data['products'][$value['id_order_product']] = array_slice($temp,5,6);
			}
			$data['products'][$value['id_order_product']]['attr'][] = array_slice($temp,11,2);
		}
		
		/*
		print('<pre>');
		print_r($data);
		print('</pre>');
		*/
				
		return $data;

	}
}


/* End of file payment_model.php */
/* Location: ./application/models/payment_model.php */