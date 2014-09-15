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
                    if(!array_key_exists($index, $data)){
                        $data[$index] =$row['attr_value'];
                    }
                    $data['price'] = $row['attr_price'];
                    $data['result'] = true;
                    $data['attr_id'] = $row['id_optional_attrdetail'];
                }
            }

            return $data;
	}
        
    function cartdata($id,$cartItems_loggedOut=false){
        $query = $this->xmlmap->getFilenameID('sql/cart', 'get_cart_data');
        
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id);
        $sth->execute();
        $rows =  $sth->fetchAll(PDO::FETCH_ASSOC) ;
        $cart_items_user = unserialize($rows[0]['userdata']);

        if($cartItems_loggedOut){
            if(!($cart_items_user)){
                $cart_items_user = $cartItems_loggedOut;
            }else{
                unset($cartItems_loggedOut['total_items']);
                unset($cartItems_loggedOut['cart_total']);
                unset($cart_items_user['total_items']);
                unset($cart_items_user['cart_total']);
                foreach($cart_items_user as $key_user => $row_user){
                    foreach($cartItems_loggedOut as $key_loggedout => $row_loggedout){
                        if($key_loggedout == $key_user){
                            $qty =  intval($cartItems_loggedOut[$key_loggedout]['qty']) + intval($cart_items_user[$key_user]['qty']);
                            $cart_items_user[$key_user]['qty'] =($qty > $cart_items_user[$key_user]['maxqty'])? $cart_items_user[$key_user]['maxqty'] : $qty;
                            unset($cartItems_loggedOut[$key_loggedout]);
                        }else{
                            $cart_items_user[$key_loggedout] = $row_loggedout;
                        }
                    }
                }
                $sizeCart = sizeof($cart_items_user);
                $cart_items_user['total_items'] = $sizeCart;
                $cart_items_user['cart_total'] = 1;
            }
        }
        return $cart_items_user;
    }

    function save_cartitems($data,$id){
        $query = $this->xmlmap->getFilenameID('sql/cart', 'save_cart_data');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id);
        $sth->bindParam(':data',$data);
        $sth->execute();

        return $sth->rowCount();
    }
    
    
    /*  
     *  Checks if @cart array has items that are allowed to be purchased only one at a time.
     *  Typically used before payment is validated.
     */
    
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
    
    
    /*   
     *   Function is unused. Remove if not needed.
     */ 
    
    public function isCartInsertPromoAllow($cart, $item = array()){
        $this->load->config('promo', TRUE);
        $can_insert_cart = true;

        if(count($item) > 0 && count($cart) > 0){
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

