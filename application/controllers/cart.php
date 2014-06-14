<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Cart extends MY_Controller{

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('cart');
        $this->load->model('product_model');
        $this->load->model('user_model');
        $this->load->model('cart_model');
    }
    
    function index(){
        $data = $this->fill_header();
        if($this->session->userdata('usersession')){
            $cart = $this->cart_items($this->cart->contents());
            $id = $this->session->userdata('usersession');
            $member_id =  $this->session->userdata('member_id');
            $this->cart_model->save_cartitems(serialize($cart),$member_id);
            $data['title'] = 'Cart | Easyshop.ph';
            $data['cart_items'] =$cart;
            $data['total'] = $this->get_total_price();
            $this->load->view('templates/header', $data);
            $this->load->view('pages/cart/mycart_view', $data);
            $this->load->view('templates/footer_full');
        }else{
            redirect(base_url().'home', 'refresh');
        }
    }
    
    function check_product($id,$opt){ //OLD checking
        $base = $this->product_model->getProductById($id);
        $base_price = $base['price'];
        $real_price = $base_price;
        $product_attr_id = "0";
        if(!empty($opt)){
            $product_attr_id = "";
            $key =  array_keys($opt); //get the key of options,used in checking the product in the database
            $add_price = 0;
            for($a=0;$a < sizeof($key);$a++){//check attr if exist and sum all the attr's price
                $attr=$key[$a];
                $attr_value=$opt[$key[$a]];
                $sum = $this->cart_model->checkProductAttributes($id,$attr,$attr_value);
                if($sum['result']== true){ //if sum result = true , attr will add price, else return false (will return false if user tries changed it)
                    $add_price +=  $sum['price'];
                }else{
                    return false;
                }
                $product_attr_id .= ($a === sizeof($key)-1 ? $sum['attr_id'] : $sum['attr_id'].",");
           
            }
            $real_price = $base_price +$add_price;
        }  
        //$qty = $this->cart_model->getSpecificProductQuantity($id,$product_attr_id,$_POST['length']);
        $qty = $this->product_model->getProductQuantity($id);
        $ss = array_keys($qty);
        $ff = $qty[$ss[0]];
        $attr = explode(",",$product_attr_id);
        $productItemId = 0;
        if(sizeof($qty) == 1 && $ff['product_attribute_ids'][0]['id'] == 0 && $ff['product_attribute_ids'][0]['is_other'] == 0){
            $max_qty = $ff['quantity'];
            $productItemId = $ss[0];
        }else{
            foreach($attr as $attr_id){
                foreach($qty as $key => $row){
                    $cnt = 0;
                    foreach($row['product_attribute_ids'] as $key2 => $row2){   
                        if($attr_id == $row2['id']){
                            $cnt++;
                        }
                    }
                    if($cnt != 1){
                        unset($qty[$key]) ;
                    }
                    if($cnt == 1){
                        $productItemId = $key;
                    }
                }
            }
            $max_qty = reset($qty)['quantity'];
            $_POST['max_qty'] = $max_qty;
        }

        $data = array(
            'id'      => $_POST['id'],
            'qty'     => ($_POST['qty'] > $_POST['max_qty'] ? $_POST['max_qty'] : $_POST['qty'] ), //check if qty is > max qty,if its qty=maxqty else qty
            'price'   => $real_price,
            'name'    => $_POST['name'],
            'options' => $opt,
            'img'     => $this->product_model->getProductImages($_POST['id']),
            'member_id'  => $base['sellerid'],
            'brief'  => $base['brief'],
            'product_itemID'  => $productItemId,
            'maxqty' => $max_qty,
            'slug' => $base['slug'],
            );

        return $data;
    }
    
    function add_item(){    
        $result='';
        $carts=$this->cart->contents();
        if(intval($_POST['length']) == 0 || empty($_POST['opt'])){
            $out_opt = 0;
            $go=array();
        }
        else{
            $out_opt = sizeof($_POST['opt']);
            $go=$_POST['opt'];
        }
        if($out_opt !== intval($_POST['length'])){
            $result=sha1(md5("hinditanggap"));
        }
        else{
            $data=$this->check_prod($_POST['id'],$go,$_POST['qty'])['data'];
                        
            /*
             *  Validation is performed upon checkout. This is less imposing on the users.
             *
            if(!$this->cart_model->isCartInsertPromoAllow($carts, $data)){
                $result = 'This item or another item in your cart can only be purchased individually. ';
            }*/
            if(empty($carts)){
                $this->cart->insert($data);
                $result= sha1(md5("tanggap"));
            }
            else if(!is_array($go)){
                $this->cart->insert($data);
                $result= sha1(md5("tanggap"));
            }
            else{
                $to_transact = '';
                foreach ($carts as $row ){
                    $id=$row['rowid'];
                    $opt =  serialize($this->cart->product_options($id));
                    $opt_user =  serialize($go);
                    if($opt == $opt_user && $row['id'] == $data['id']){ //if product exist in cart , check if qty exceeds the maximum qty, if exceed get qty = max qty else qty + cart product qty
                        $data2 = array(
                               'rowid' => $id,
                               'qty'   => ($_POST['qty'] + $row['qty'] > $_POST['max_qty'] ? $_POST['max_qty'] : $_POST['qty'] + $row['qty'] )
                            );
                        $to_transact = 'update';
                        break;	
                    }
                    else{   
                        $to_transact = 'add';		
                    }
                }
                if($to_transact == 'update'){
                    $this->cart->update($data2);		
                }else{
                    $this->cart->insert($data);	
                }
                $result= sha1(md5("tanggap"));
            }
        }
		$this->session->set_userdata('cart_total_perItem',$this->cart_size());
        echo json_encode($result);
    }

    public function cart_items($carts){
        foreach($carts as $key => $row1){
            $data = $this->check_prod($row1['id'],$row1['options'],$row1['qty']);
            foreach ($carts as $row ){
                $id = $row['id'];
                $opt =  serialize($this->cart->product_options($row['rowid']));
                $opt_user =  serialize($data['data']['options']);
                if($opt == $opt_user && $id == $data['data']['id']){ //if product exist in cart , check if qty exceeds the maximum qty, if exceed get qty = max qty else qty + cart product qty
                    $data['data']['rowid']= $row['rowid'];

                    $this->cart->insert($data['data']);

                    if($data['data']['qty'] == "0" || $data['delete_to_cart'] === true){
                        $data_remove = array('rowid'=>$data['data']['rowid'],'qty'=> 0);
                        $this->cart->update($data_remove);
                    }
                    break;
                }
            }
        }
        return $this->cart->contents();
    }

	private function check_prod($id,$opt,$userQTY){    
        $product = $this->product_model->getProductById($id);
        $final_price = $product['price']; //product['price'] already has the promo calculations applied to it
        $product_attr_id = "0";
        $add_price = 0;
        if(!empty($opt)){
            $product_attr_id = "";
            $key =  array_keys($opt); //get the key of options,used in checking the product in the database
            
            for($a=0;$a < sizeof($key);$a++){//check attr if exist and sum all the attr's price
                $attr=$key[$a];
                $attr_value=$opt[$key[$a]];
                $sum = $this->cart_model->checkProductAttributes($id,$attr,$attr_value);
                if($sum['result']== true){ //if sum result = true , attr will add price, else return false (will return false if user tries changed it)
                    $add_price +=  $sum['price'];
                }else{
                    return false;
                }
                $product_attr_id .= ($a === sizeof($key)-1 ? $sum['attr_id'] : $sum['attr_id'].",");

            }
            $final_price = $product['price'] + $add_price;
        }
        #from this part, u already have (Price,prod_attr_id,prodID)
        $qty = $this->product_model->getProductQuantity($id);
        $ss = array_keys($qty);
        $ff = $qty[$ss[0]];
        $attr = explode(",",$product_attr_id);
        $productItemId = 0;
        if(sizeof($qty) == 1 && $ff['product_attribute_ids'][0]['id'] == 0 && $ff['product_attribute_ids'][0]['is_other'] == 0){
            $max_qty = $ff['quantity'];
            $productItemId = $ss[0];
        }else{
            foreach($attr as $attr_id){
                foreach($qty as $key => $row){
                    $cnt = 0;
                    foreach($row['product_attribute_ids'] as $key2 => $row2){
                        if($attr_id == $row2['id']){
                            $cnt++;
                        }
                    }
                    if($cnt != 1){
                        unset($qty[$key]) ;
                    }
                    if($cnt == 1){
                        $productItemId = $key;
                    }
                }
            }
            $max_qty = reset($qty)['quantity'];
        }
        #done checking if the attribute's are existing on DB and max_quantity
        $promo = $this->config->item('Promo')[$product['promo_type']];
        $data = array(
            'id'      => $id,
            'qty'     => ($product['is_promote'] == "1" && intval($userQTY) >= intval($promo['purchase_limit'])
                    ? $promo['purchase_limit']
                    : ($userQTY > $max_qty
                        ? $max_qty
                        : $userQTY )), #check check check! -_-
            'price'   => $final_price,
            'original_price' => $product['original_price'],
            'name'    => $product['product'],
            'options' => $opt,
            'img'     => $this->product_model->getProductImages($product['id_product']),
            'member_id'  => $product['sellerid'],
            'brief'  => $product['brief'],
            'product_itemID'  => $productItemId,
            'maxqty' => $max_qty,
            'slug' => $product['slug'],
            'is_promote' => $product['is_promote'],
            'additional_fee' => $add_price,
            'promo_type' => $product['promo_type'],
        );

        $result['data'] = $data;
        $result['delete_to_cart'] =($product['is_draft'] == "1" || $product['is_delete'] == "1" || $product['can_purchase'] === false);
        
        return $result;
    }

    function cart_size(){
        $carts=$this->cart->contents();
        $cart_size =sizeof($carts);
        return $cart_size;
    }
    
    function remove_item(){
        $data = array(
               'rowid' => $_POST['id'],
               'qty'   => 0
            );
        $result=false;
        if($this->cart->update($data)){
            $result=array(
                'result'=>true,
                'total'=>  $this->cart->total(),
                'total_items'=>  $this->cart_size());
        } 
        echo json_encode($result);
    }
    
    function fnc_qty(){
        $qty = intval($this->input->post("qty"));
        $id = $this->input->post("id");
        $cart = $this->cart_items($this->cart->contents());
        $result2 = $this->change_quantity($id,$cart[$id],$qty);
        echo json_encode($result2);
    }
    public function change_quantity($id,$cart_item,$qty){
        $data['rowid'] = $id;
        $data['qty'] = $qty;
        $purchase_limit = $this->config->item('Promo')[$cart_item['promo_type']]['purchase_limit'];
        $max_qty = $cart_item['maxqty'];

        $result = false;
        if($cart_item['is_promote'] == "1" && $qty > $purchase_limit){
            $data['qty'] = $purchase_limit;
        }else if ($qty > $max_qty ){
            $data['qty'] = $max_qty;
        }
        $this->cart->update($data);
        if($qty != 0){
            $cart=$this->cart->contents();
            $totalprice = ($cart[$id]['price']) * $cart[$id]['qty'];
            $result=array(
                'subtotal'=>  number_format($totalprice,2,'.',','),
                'total' =>  $this->get_total_price(),
                'result' => true,
                'qty' =>$cart[$id]['qty'],
                'maxqty' => $max_qty);
        }

        return $result;

    }
    function get_total_price(){
        $cart = $this->cart->contents();
        $total = 0;
        foreach($cart as $key => $row){
            $total += $row['price'] * $row['qty'];
        }
        return number_format($total,2,'.',',');
    }

    function removeselected()
    {   
        $userdata = $this->session->all_userdata();
        $itemList = $userdata['choosen_items'];
        $slug = $this->input->post('slug');

        $key = "";
        foreach ($itemList as $key => $value) {
            if($value['slug'] == $slug){
                unset($itemList[$key]);
            }
        }
        $this->session->set_userdata('choosen_items', $itemList);
        echo '{"e":"0"}';
        
    }
}

/* End of file cart.php */
/* Location: ./application/controllers/cart.php */
