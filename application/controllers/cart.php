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

    public function doChangeQuantity()
    {
        $cartId = $this->input->post("id");
        $quantity = $this->input->post("qty");
        $memberId = $this->session->userdata('member_id');
        $isSuccessful = $this->cartManager->changeItemQuantity($cartId, $quantity);

        $cartItem = $this->cartManager->getValidatedCartContents($memberId)[$cartId];
        $itemSubtotal = ($cartItem['price']) * $cartItem['qty'];
        
        $result = array(
                'itemSubtotal' => number_format($itemSubtotal, 2, '.', ','),
                'cartTotal' => $this->cartImplementation->getTotalPrice(),
                'isSuccessful' => $isSuccessful,
                'qty' =>  $cartItem['qty'],
                'maxqty' => $cartItem['maxqty']);
                
        print json_encode($result);

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
