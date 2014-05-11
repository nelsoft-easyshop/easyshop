<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class cart_model extends CI_Model
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library("sqlmap");
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
        $query = $this->sqlmap->getFilenameID('cart', 'get_cart_data');
        
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id);
        $sth->execute();
        $rows =  $sth->fetchAll(PDO::FETCH_ASSOC) ;	
        $aa = unserialize($rows[0]['userdata']);
        return $aa;
    }
    
    function save_cartitems($data,$id){
        $query = $this->sqlmap->getFilenameID('cart', 'save_cart_data');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id); 
        $sth->bindParam(':data',$data);
        $sth->execute();
    }
    
    
    /*  This function is no longer used.
     *  Marked for removal. 5/10/2014
     */
	public function getSpecificProductQuantity($product_id,$product_attr_id,$length){ 
	    $query = "
		SELECT a.id_product_item, a.quantity, COALESCE(b.product_attr_id,0) AS product_attr_id,cnt.count,
		COALESCE(b.is_other,0) AS is_other  FROM es_product_item a
		LEFT JOIN es_product_item_attr b ON b.product_id_item = a.id_product_item 
		INNER JOIN (
		SELECT a.id_product_item, a.quantity, COALESCE(b.product_attr_id,0) AS product_attr_id,COUNT(*) AS `count`,
		COALESCE(b.is_other,0) AS is_other  FROM es_product_item a
		LEFT JOIN es_product_item_attr b ON b.product_id_item = a.id_product_item 
		WHERE a.product_id = $product_id AND b.product_attr_id IN($product_attr_id) GROUP BY a.`id_product_item`
		) AS `cnt` ON a.`id_product_item` = cnt.id_product_item
		WHERE a.product_id = $product_id AND b.product_attr_id IN($product_attr_id) HAVING `count` = $length
		UNION
			
		SELECT b.id_product_item,b.quantity,'','',''
		FROM es_product AS a
		LEFT JOIN `es_product_item` AS b ON a.`id_product` = b.`product_id`
		WHERE b.product_id = $product_id";
	    $sth = $this->db->conn_id->prepare($query);
	    $sth->execute();
	    $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
    
	    $data = array();
	    $ctr = 0;
	    foreach($rows as $row){
            if(!array_key_exists($ctr,  $data)){
                $data[$ctr] = array();
                $data[$ctr]['quantity'] = $row['quantity'];
                $data[$ctr]['id_product_item'] = $row['id_product_item'];
                $data[$ctr]['product_attribute_ids'] = array();
                $data[$ctr]['attr_lookuplist_item_id'] = array();
            }
            array_push($data[$ctr]['product_attribute_ids'], array('id'=>$row['product_attr_id'], 'is_other'=> $row['is_other']));
	    }
	    
	    return $data;
	}
}

/* End of file cart_model.php */
/* Location: ./application/models/cart_model.php */

