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
            $data=$this->check_prod($_POST['id'],$go,$_POST['qty']);
            $carts=$this->cart->contents();
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
    
    function index(){
        
        $data = $this->fill_header();
        if($this->session->userdata('usersession')){
            $this->cart_items();
            $id = $this->session->userdata('usersession');
            $carts=$this->cart->contents();
            $data['title'] = 'Cart | Easyshop.ph';
            $data['page_javascript'] = 'assets/JavaScript/cart.js';
            $data['cart_items'] = $carts;
            $data['total'] = number_format( $this->cart->total(),2,'.',',');
            $this->load->view('templates/header', $data);
            $this->load->view('pages/cart/mycart_view', $data);
            $this->load->view('templates/footer_full');
        }else{
            redirect(base_url().'home', 'refresh');
        }   
    }

    public function cart_items(){
        $carts=$this->cart->contents();
        foreach($carts as $key => $row1){
            $data = $this->check_prod($row1['id'],$row1['options'],$row1['qty']);
            foreach ($carts as $row ){
                $id = $row['id'];
                $opt =  serialize($this->cart->product_options($row['rowid']));
                $opt_user =  serialize($data['options']);
                if($opt == $opt_user && $id == $data['id']){ //if product exist in cart , check if qty exceeds the maximum qty, if exceed get qty = max qty else qty + cart product qty
                    $data2 = array(
                        'rowid' => $row['rowid'],
                        'qty'   => $data['qty'],
                        'promo_price'   => $data['promo_price']
                    );
                    $this->cart->update($data2);
                    break;
                }

            }
        }
        $carts2=$this->cart->contents();
    }
	private function check_prod($id,$opt,$userQTY){
        $base = $this->product_model->getProductById($id);
        $base_price = $base['price'];
        $real_price = $base_price;
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
            $real_price = $base_price + $add_price;
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
//            $_POST['max_qty'] = $max_qty;
        }
        #done checking if the attribute's are existing on DB and max_quantity
        $data = array(
            'id'      => $id,
            'qty'     => ($userQTY > $max_qty ? $max_qty : $userQTY ), //check if qty is > max qty,if its qty=maxqty else qty
            'price'   => $real_price,
            'promo_price' => $this->GetPromoPrice($base['price'],$base['startdate'],$base['enddate'],$base['is_promote'],"FirstPromo"),
            'name'    => $base['product'],
            'options' => $opt,
            'img'     => $this->product_model->getProductImages($base['id_product']),
            'member_id'  => $base['sellerid'],
            'brief'  => $base['brief'],
            'product_itemID'  => $productItemId,
            'maxqty' => $max_qty,
            'slug' => $base['slug'],
            'is_promote' => $base['is_promote'],
            'additional_fee' => $add_price
        );
        return $data;
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
    
    function change_qty(){
        $result=array();
        $result['result'] = false;
        $cart = $this->cart->contents();
            foreach($cart as $keys => $child){
                if($_POST['id']==$keys){
                    $max_qty = $cart[$keys]['maxqty'];
                    $data = array(
                           'rowid' => $_POST['id'],
                           'qty'   => ($_POST['qty'] > $max_qty ? $max_qty : $_POST['qty'])
                        );
                    if($this->cart->update($data)){
                        if($this->input->post('qty') != 0){
                            $carts=$this->cart->contents();
                            $result=array(
                                'subtotal'=>  number_format($carts[$_POST['id']]['subtotal'],2,'.',','),
                                'total' =>number_format( $this->cart->total(),2,'.',','),
                                'result' => true,
                                'maxqty' => $max_qty);
                        }
                    }
                }
            }
        echo json_encode($result);
    }

}

/* End of file cart.php */
/* Location: ./application/controllers/cart.php */
