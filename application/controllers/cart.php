<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cart extends MY_Controller
{

    /**
     * The cartManager
     *
     * @var EasyShop\Cart\CartManager
     */
    private $cartManager;
    
    /**
     * The cart object
     * 
     * @var EasyShop\Cart\CartInterface
     */
    private $cartImplementation;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->cartManager = $this->serviceContainer['cart_manager'];
        $this->cartImplementation = $this->cartManager->getCartObject();
    }

    
    /**
     * Renders the cart page
     *
     *
     */
    public function index()
    {
        $data = $this->fill_header();
        if ($this->session->userdata('usersession')) {
            $memberId = $this->session->userdata('member_id');
            $cartContents = $this->cartManager->getValidatedCartContents($memberId);
            $this->cartImplementation->persist($memberId);
            $data['title'] = 'Cart | Easyshop.ph';
            $data['cart_items'] = $cartContents;
            $data['total'] = $this->cartImplementation->getTotalPrice();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/checkout_progressbar', $data);
            $this->load->view('pages/cart/cart-responsive', $data);
            $this->load->view('templates/footer_full');
        } 
        else {
            redirect('/login', 'refresh');
        }
    }
    
    
    /**
     * Action for adding an item into the cart
     *
     * @return mixed
     */
    public function doAddItem()
    {
        $productId = $this->input->post('productId');
        $options = $this->input->post('options');
        $quantity = $this->input->post('quantity');        
        $isSuccesful = $this->cartManager->addItem($productId, $quantity, $options);
        $isLoggedIn = $this->session->userdata('usersession') ? true : false;
        print json_encode(['isSuccessful' => $isSuccesful, 'isLoggedIn' => $isLoggedIn]);
    }
    

    /**
     * Remove an item from the cart
     *
     * @return mixed
     */
    function doRemoveItem()
    {
        $memberId =  $this->session->userdata('member_id');
        $rowId = $this->input->post('id');
        $isRemoveSuccesful = $this->cartManager->removeItem($memberId, $rowId);
        
        $response = 
        [
            'isSuccess' => $isRemoveSuccesful,
            'totalPrice' => $this->cartImplementation->getTotalPrice(),
            'numberOfItems' => $this->cartImplementation->getSize(true),
        ];

        echo json_encode($response);
    }

    
    
    /**
     * ajax - Change quantity
     *
     * @Param id
     * @Param qty
     *
     * @Return ajax
     */
    function fnc_qty()
    {
        $qty = intval($this->input->post("qty"));
        $id = $this->input->post("id");
        $cart = $this->cart_items($this->cart->contents());
        $result2 = $this->change_quantity($id,$cart[$id],$qty);
        echo json_encode($result2);
    }

    /**
     * Change quantity
     *
     * @Param id
     * @Param cart
     * @Param qty
     *
     * @Return array
     */
    public function change_quantity($id, $cart_item, $qty)
    {
        $data['rowid'] = $id;
        $data['qty'] = $qty;
        $PurchaseLimit = $this->config->item('Promo')[$cart_item['promo_type']];

        $max_qty = $cart_item['maxqty'];

        $PurchaseLimit = $PurchaseLimit['purchase_limit'];

        if (is_string($PurchaseLimit)) {
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
        if ($qty != 0) {
            $cart = $this->cart->contents();
            $totalprice = ($cart[$id]['price']) * $cart[$id]['qty'];
            $result = array(
                'subtotal' => number_format($totalprice, 2, '.', ','),
                'total' => $this->get_total_price(),
                'result' => true,
                'qty' => $cart[$id]['qty'],
                'maxqty' => $max_qty);
        }

        return $result;
    }

    /**
     * Retrieve total price
     *
     * @return integer
     */
    function get_total_price()
    {
        $cart = $this->cart->contents();
        $total = 0;
        foreach ($cart as $key => $row) {
            $total += $row['price'] * $row['qty'];
        }
        return number_format($total,2,'.',',');
    }

    /**
     * Remove selected item in cart
     *
     * @return array
     */
    function removeselected()
    {
        $userdata = $this->session->all_userdata();
        $itemList = $userdata['choosen_items'];
        $slug = $this->input->post('slug');

        $key = "";
        foreach ($itemList as $key => $value) {
            if ($value['slug'] == $slug) {
                unset($itemList[$key]);
            }
        }
        $this->session->set_userdata('choosen_items', $itemList);

        echo '{"e":"0"}';
    }
}
