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

    function index()
    {
        $data = $this->fill_header();
        if($this->session->userdata('usersession')){
            $cart = $this->cart_items($this->cart->contents());
            $member_id =  $this->session->userdata('member_id');
            $this->cart_model->save_cartitems(serialize($cart),$member_id);
            $data['title'] = 'Cart | Easyshop.ph';
            $data['cart_items'] =$cart;
            $data['total'] = $this->get_total_price();
            $this->load->view('templates/header', $data);
            $this->load->view('pages/cart/mycart_view', $data);
            $this->load->view('templates/footer_full');
        }else{
            redirect(base_url().'login', 'refresh');
        }
    }

    function add_item()
    {
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
            $result = false;
        }
        else{
            $data=$this->check_prod($_POST['id'],$go,$_POST['qty'])['data'];
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
                }
                else{
                    $this->cart->insert($data);
                }
                $result= sha1(md5("tanggap"));
            }
        }
        $this->session->set_userdata('cart_total_perItem',$this->cart_size());

        if(!($this->session->userdata('usersession'))){
            $result = "login_to_add_item2cart";
        }
        echo json_encode($result);
    }

    public function cart_items($carts)
    {
        foreach ($carts as $row){
            $data = $this->check_prod($row['id'],$row['options'],$row['qty']);
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

        return $this->cart->contents();
    }

    private function check_prod($id,$opt,$userQTY)
    {
        $member_id = $this->session->userdata('member_id');
        $useraccessdetails = $this->user_model->getUserById($member_id);

        $product = $this->product_model->getProductById($id);

        $final_price = $product['price'];
        $product_attr_id = "0";
        $add_price = 0;
        if(!empty($opt)){
            $product_attr_id = "";
            $key =  array_keys($opt);
            for($a=0;$a < sizeof($key);$a++){
                $attr=$key[$a];
                $attr_value=$opt[$key[$a]];
                $attr_value = (strpos($attr_value, "~"))? explode("~", $attr_value)[0]:$attr_value;
                $sum = $this->cart_model->checkProductAttributes($id,$attr,$attr_value);
                if($sum['result']== true){
                    $add_price +=  $sum['price'];
                    $opt[$attr] = $attr_value.'~'.$sum['price'];
                }
                else{
                    return false;
                }
                $product_attr_id .= ($a === sizeof($key)-1 ? $sum['attr_id'] : $sum['attr_id'].",");
            }
            $final_price = $product['price'] + $add_price;
        }
        $qty = $this->product_model->getProductQuantity($id, false, false, $product['start_promo']);

        $ss = array_keys($qty);
        $ff = $qty[$ss[0]];
        $attr = explode(",",$product_attr_id);
        $productItemId = 0;
        if(
            sizeof($qty) == 1 &&
            $ff['product_attribute_ids'][0]['id'] == 0 &&
            $ff['product_attribute_ids'][0]['is_other'] == 0
        ){
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
        $promo = $this->config->item('Promo')[$product['promo_type']];
        $PurchaseLimit = $promo['purchase_limit'];
        $d_quantity = 0;
        if(($product['is_promote'] == 1 && intval($userQTY) >= intval($PurchaseLimit)) &&  $max_qty != 0){
            $d_quantity = $PurchaseLimit;
        }
        else{
            if($userQTY > $max_qty || $max_qty == 0){
                $d_quantity = $max_qty;
            }
            else{
                $d_quantity = $userQTY;
            }
        }
        $data = array(
            'id'      => $id,
            'qty' => $d_quantity,
            'price'   => $final_price,
            'original_price' => $product['original_price'],
            'name'    => stripslashes($product['product']),
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
            'start_promo' => $product['start_promo'] ,
        );
        $result['data'] = $data;
        $result['delete_to_cart'] = (
            $product['sellerid'] == $member_id ||
            intval($useraccessdetails['is_email_verify']) !== 1  ||
            intval($product['is_draft']) !== 0 ||
            intval($product['is_delete']) !== 0 ||
            $product['can_purchase'] === false
        );

        return $result;
    }

    function cart_size()
    {
        $carts=$this->cart->contents();
        $cart_size =sizeof($carts);

        return $cart_size;
    }

    function remove_item()
    {
        $MemberId =  $this->session->userdata('member_id');
        $data = array(
            'rowid' => $this->input->post('id'),
            'qty'   => 0
        );
        $result=false;
        if($this->cart->update($data)){
            $result=array(
                'result'=>true,
                'total'=>  $this->get_total_price(),
                'total_items'=>  $this->cart_size());
            $Cart = $this->cart_items($this->cart->contents());
            $this->cart_model->save_cartitems(serialize($Cart),$MemberId);
        }

        echo json_encode($result);
    }

    function fnc_qty()
    {
        $qty = intval($this->input->post("qty"));
        $id = $this->input->post("id");
        $cart = $this->cart_items($this->cart->contents());
        $result2 = $this->change_quantity($id,$cart[$id],$qty);

        echo json_encode($result2);
    }

    public function change_quantity($id,$cart_item,$qty)
    {
        $data['rowid'] = $id;
        $data['qty'] = $qty;
        $PurchaseLimit = $this->config->item('Promo')[$cart_item['promo_type']];

        $max_qty = $cart_item['maxqty'];

        $PurchaseLimit = $PurchaseLimit['purchase_limit'];

        if(is_string($PurchaseLimit)){
            $PurchaseLimit = $this->config->item('Promo')[$cart_item['promo_type']][$PurchaseLimit];
            foreach($PurchaseLimit as $items){
                if(
                    (strtotime(date('H:i:s')) > strtotime($items['start'])) &&
                    (strtotime(date('H:i:s')) < strtotime($items['end']))
                ){
                    $PurchaseLimit = $max_qty;
                }
                else{
                    $PurchaseLimit = 0;
                }
            }
        }
        $result = false;

        if($cart_item['is_promote'] == "1" && $qty > $PurchaseLimit){
            $data['qty'] = $PurchaseLimit;
        }
        else if ($qty > $max_qty ){
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
    function get_total_price()
    {
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
