<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class cart_model extends CI_Model
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library("xmlmap");
	}	

    
	function checkProductAttributes($id,$attr,$attr_value) # getting the product attirbute using product ID,attr,attr value
	{
            $query = $this->xmlmap->getFilenameID('sql/cart', 'checkProductAttributes');

            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':id',$id);
            $sth->bindParam(':attr',$attr);
            $sth->bindParam(':attr_value',$attr_value);
            $sth->execute();
            $rows = $sth->fetchAll(PDO::FETCH_ASSOC);	
            $data = array();
            if(empty($rows)){
                $data['result'] = false;
            }
            else{
                foreach($rows as $row){
					$index = $row['name'];
					if(!array_key_exists($index, $data))
					$data[$index] =$row['attr_value']; 
					$data['price'] = $row['attr_price'];
					$data['result'] = true;
					$data['attr_id'] = $row['id_optional_attrdetail'];
                }
            }
            return $data;
	}
        
    function cartdata($id){
        $query = $this->xmlmap->getFilenameID('sql/cart', 'get_cart_data');
        
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id);
        $sth->execute();
        $rows =  $sth->fetchAll(PDO::FETCH_ASSOC) ;	
        $aa = unserialize($rows[0]['userdata']);
        return $aa;
    }
    
    function save_cartitems($data,$id){
        $query = $this->xmlmap->getFilenameID('sql/cart', 'save_cart_data');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id); 
        $sth->bindParam(':data',$data);
        $sth->execute();

        return $sth->rowCount();
    }
    
    public function isCartCheckoutPromoAllow($cart){
        $this->load->config('promo', TRUE);
        $count_solo_items = 0;
        foreach($cart as $cart_item){
            $promo_solo_restriction = $this->config->item('Promo')[$cart_item['promo_type']]['cart_solo_restriction'];
            if ((intval($cart_item['is_promote']) === 1) && $promo_solo_restriction ) {
                $count_solo_items ++;
            }
        }
        if(($count_solo_items === 0) || ($count_solo_items === 1 && count($cart) === 1)){
            return true;
        }else{
            return false;
        }

    }
    
    public function isCartInsertPromoAllow($cart, $item = array()){
        $this->load->config('promo', TRUE);
        $can_insert_cart = true;
        
        if(count($item) > 0){
            $cart['temporary'] = $item;
        }
        
        foreach($cart as $cart_item){
            $promo_solo_restriction = $this->config->item('Promo')[$cart_item['promo_type']]['cart_solo_restriction'];
            if ((intval($cart_item['is_promote']) === 1) && $promo_solo_restriction ) {
                $can_insert_cart = false;
                break;
            }
        }
        
        return $can_insert_cart;
    }

    
}

/* End of file cart_model.php */
/* Location: ./application/models/cart_model.php */

