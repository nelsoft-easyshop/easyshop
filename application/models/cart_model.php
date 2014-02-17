<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class cart_model extends CI_Model
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library("sqlmap");
		$this->load->library("cart");
	}	

        function cart_size(){
            $this->load->library('cart');
            $carts=$this->cart->contents();
            $cart_size =sizeof($carts);
            return $cart_size;
        }
	function checkProductAttributes($id,$attr,$attr_value) # getting the product attirbute using product ID,attr,attr value
	{
            $query = $this->sqlmap->getFilenameID('cart', 'checkProductAttributes');

            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':id',$id);
            $sth->bindParam(':attr',$attr);
            $sth->bindParam(':attr_value',$attr_value);
            $sth->execute();
            $rows = $sth->fetchAll(PDO::FETCH_ASSOC);	
            $data = array();
            if(empty($rows)):
                $data['result'] = false;
            else:
                foreach($rows as $row){
					$index = $row['name'];
					if(!array_key_exists($index, $data)) #
						$data[$index] =$row['attr_value']; 
					$data['price'] = $row['attr_price'];
					$data['result'] = true;
                }
            endif;
            return $data;
	}
        
        function cartdata($id){
            $query = $this->sqlmap->getFilenameID('cart', 'get_cart_data');
            
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':id',$id);
            $sth->execute();
            $rows =  $sth->fetchAll(PDO::FETCH_ASSOC) ;	
            $aa = unserialize($rows[0]['userdata']);
            return $aa;
        }
        function save_cartitems($data,$id){
            $this->db->query("UPDATE `es_member` SET `userdata` = '$data' WHERE `id_member` = $id;");
        }
}

/* End of file cart_model.php */
/* Location: ./application/models/cart_model.php */

