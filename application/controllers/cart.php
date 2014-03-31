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
        
    function check_product($id,$opt){
        $base = $this->product_model->getProduct($id);
        $base_price = $base['price'];
        $real_price = $base_price;
        if(!empty($opt)):
			$key =  array_keys($opt); //get the key of options,used in checking the product in the database
			$add_price =0;
            for($a=0;$a < sizeof($key);$a++){
                $attr=$key[$a];
                $attr_value=$opt[$key[$a]];
                $sum = $this->cart_model->checkProductAttributes($id,$attr,$attr_value);
                if($sum['result']== true):
                    $add_price +=  $sum['price'];
                else:
                    return false;
                endif;
            }

            $real_price = $base_price +$add_price;
        endif;
    
        $data = array(
            'id'      => $_POST['id'],
            'qty'     => $_POST['qty'],
            'price'   => $real_price,
            'name'    => $_POST['name'],
            'options' => $opt,
            'img'   => $this->product_model->getProductImages($_POST['id']),
            'member_id'  => $base['sellerid'],
	    'maxqty' => $this->input->post('max_qty')
            );
        return $data;
    }
    
    function add_item(){
        $result='';
        if(intval($_POST['length']) == 0 || empty($_POST['opt'])):
            $out_opt = 0;
            $go=array();
        else:
            $out_opt = sizeof($_POST['opt']);
            $go=$_POST['opt'];
        endif;
        if($out_opt !== intval($_POST['length'])):
            $result=sha1(md5("hinditanggap"));
        else:
            $data=$this->check_product($_POST['id'],$go);
            $carts=$this->cart->contents();  
            if(empty($carts)):
                $this->cart->insert($data);
                $result= sha1(md5("tanggap"));
    		elseif(!is_array($go)):	
                $this->cart->insert($data);
                $result= sha1(md5("tanggap"));
            else:
                foreach ($carts as $row ): 
                    $id=$row['rowid'];
                    $opt =  serialize($this->cart->product_options($id));
                    $opt_user =  serialize($go);
                    if($opt == $opt_user){
                        $data2 = array(
                               'rowid' => $id,
                               'qty'   => ($_POST['qty'] + $row['qty'] > $_POST['max_qty'] ? $_POST['max_qty'] : $_POST['qty'] + $row['qty'] )
                            );
 
                        $this->cart->update($data2);
 
                    }else{   

                        $this->cart->insert($data);         
 
                    }
                endforeach;
                $result= sha1(md5("tanggap"));
            endif;
        endif;
        echo json_encode($result);
    }
    
    function index(){
        if($this->session->userdata('usersession')){
		$id = $this->session->userdata('usersession');
                $carts=$this->cart->contents();
		$data['title'] = 'Cart | Easyshop.ph';
		$data['page_javascript'] = 'assets/JavaScript/cart.js';
		$data['cart_items'] = $carts;
		$data['total'] = number_format( $this->cart->total(),2,'.',',');
		$data = array_merge($data,$this->fill_header());
		$this->load->view('templates/header', $data);
		$this->load->view('pages/cart/mycart_view', $data);
		$this->load->view('templates/footer_full');
	}else{
            redirect(base_url().'home', 'refresh');
	}
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
        $data = array(
               'rowid' => $_POST['id'],
               'qty'   => $_POST['qty']
            );
		
        $result=false;
        if($this->cart->update($data)){
        $carts=$this->cart->contents();
            $result=array(
                'subtotal'=>  number_format($carts[$_POST['id']]['subtotal'],2,'.',','),
                'total' =>number_format( $this->cart->total(),2,'.',','));
        }
	echo json_encode($result);
        
    }

    
}

/* End of file cart.php */
/* Location: ./application/controllers/cart.php */
