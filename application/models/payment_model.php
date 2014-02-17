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
}


/* End of file payment_model.php */
/* Location: ./application/models/payment_model.php */