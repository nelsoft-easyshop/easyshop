<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use EasyShop\Entities\EsAddress as EsAddress;

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

    private $pointTracker;

    private $em;
    
    /**
     * Product Manager instance
     *
     * @var EasyShop\Product\ProductManager
     */
    private $productManager;

    public function __construct()
    {
        parent::__construct();
        $this->cartManager = $this->serviceContainer['cart_manager'];
        $this->productManager = $this->serviceContainer['product_manager'];
        $this->pointTracker = $this->serviceContainer['point_tracker'];
        $this->em = $this->serviceContainer['entity_manager'];
        $this->cartImplementation = $this->cartManager->getCartObject();
    }

    /**
     * Renders the cart page
     *
     *
     */
    public function index()
    {
        $esAddressRepository = $this->em->getRepository('EasyShop\Entities\EsAddress');
        if ($this->session->userdata('member_id')) {
            $memberId = (int) $this->session->userdata('member_id');
            $cartContents = $this->cartManager->getValidatedCartContents($memberId); 
            $totalAmount = $this->cartImplementation->getTotalPrice();
            $locations = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                  ->getLocationLookup();
            $userAddress = $esAddressRepository->getConsigneeAddress($memberId, EsAddress::TYPE_DELIVERY, true);

            $headerData = [
                "memberId" => $this->session->userdata('member_id'),
                "title" => "Cart | Easyshop.ph",
            ];

            $referer =  $this->serviceContainer['http_request']
                             ->headers->get('referer');
            $continueUrl = $referer;
            if (strpos($referer, '/item/') === false) {
                $continueUrl = '/product/categories_all';
            }
            $bodyData = [
                'userAddress' => $userAddress,
                'continue_url' => $continueUrl,
                'cart_items' => $cartContents,
                'totalAmount' => str_replace( ',', '', $totalAmount ),
                'userPoints' => $this->pointTracker->getUserPoint($memberId),
                'isCartEmpty' => $this->cartImplementation->getSize() === 0,
                'locations' => $locations,
                'totalShippingFee' => $this->cartManager->getCartShippingFee($userAddress['stateRegion'] ,$memberId)
            ];

            $this->load->spark('decorator');
            $this->load->view('templates/header_alt2', $this->decorator->decorate('header', 'view', $headerData));
            $this->load->view('pages/cart/cart', $bodyData);
            $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view', $headerData));
        } 
        else {
            redirect('/login', 'refresh');
        }
    }

    /**
     * Action for adding an item into the cart
     * Promo cannot be added via the express add button
     *
     * @return mixed
     */
    public function doAddItem()
    {
        $productId = $this->input->post('productId');
        $memberId = $this->session->userdata('member_id');
        
        $isLoggedIn = $this->session->userdata('usersession') ? true : false;
        
        $product = $this->em->find('EasyShop\Entities\EsProduct', $productId);
        

        if($this->input->post('express')){
            $defaultAttributeData = $this->productManager->getProductDefaultAttributes($productId);
            $hasStock = $defaultAttributeData['hasStock'];
            $defaultAttributes = $defaultAttributeData['defaultAttributes'];
            if(!$hasStock || $product->getIsPromote()){
                print json_encode(['isSuccessful' => false, 'isLoggedIn' => $isLoggedIn]);
                exit();
            }
            $options = [];
            foreach($defaultAttributes as $attribute){
                $options[strtolower($attribute["attr_name"])] = $attribute["attr_value"]."~".$attribute["attr_price"];
            }
            $quantity = 1;
        }
        else{
            $options = $this->input->post('options') ? $this->input->post('options') : [];
            $quantity = $this->input->post('quantity');
        }
        $isSuccesful = false;
        if ($product) {
            $seller = $product->getMember();
            $member = $this->em->find('EasyShop\Entities\EsMember', $memberId);
            if($member && $seller->getIdMember() !== (int)$memberId && $member->getIsEmailVerify()){
                $isSuccesful = $this->cartManager->addItem($productId, $quantity, $options);
            }
        }
        
        print json_encode(['isSuccessful' => $isSuccesful, 'isLoggedIn' => $isLoggedIn]);
    }

    /**
     * Remove an item from the cart
     *
     * @return mixed
     */
    public function doRemoveItem()
    {
        $esAddressRepository = $this->em->getRepository('EasyShop\Entities\EsAddress');
        $memberId =  $this->session->userdata('member_id');
        $rowId = $this->input->post('id');
        $isRemoveSuccesful = $this->cartManager->removeItem($memberId, $rowId);
        $userAddress = $esAddressRepository->getConsigneeAddress($memberId, EsAddress::TYPE_DELIVERY, true);
        
        $response = [
            'isSuccessful' => $isRemoveSuccesful,
            'totalPrice' => $this->cartImplementation->getTotalPrice(),
            'numberOfItems' => $this->cartImplementation->getSize(true),
            'totalShippingFee' => $this->cartManager->getCartShippingFee($userAddress['stateRegion'] ,$memberId)
        ];

        echo json_encode($response);
    }

    /**
     * Change quantityof cart item.
     * @return json
     */
    public function doChangeQuantity()
    {
        $esAddressRepository = $this->em->getRepository('EasyShop\Entities\EsAddress');
        $cartId = $this->input->post("id");
        $quantity = $this->input->post("qty");
        $memberId = $this->session->userdata('member_id');
        $isSuccessful = $this->cartManager->changeItemQuantity($cartId, $quantity);
        $userAddress = $esAddressRepository->getConsigneeAddress($memberId, EsAddress::TYPE_DELIVERY, true);

        if ($isSuccessful) {
            $cartItem = $this->cartManager->getValidatedCartContents($memberId)[$cartId];
            $itemSubtotal = ($cartItem['price']) * $cartItem['qty'];

            $result = [
                'itemSubtotal' => number_format($itemSubtotal, 2, '.', ','),
                'cartTotal' => $this->cartImplementation->getTotalPrice(),
                'qty' =>  $cartItem['qty'],
                'maxqty' => $cartItem['maxqty'],
                'totalShippingFee' => $this->cartManager->getCartShippingFee($userAddress['stateRegion'] ,$memberId)
            ];
        }
        $result['isSuccessful'] = $isSuccessful;

        print json_encode($result);
    }

    /**
     * Removes selected item from the chosen item session
     *
     * @return array
     */
    public function doRemoveSelected()
    {
        if ($this->session->userdata('member_id')) {
            $itemList = $this->session->userdata['choosen_items'];
            $removeRowId = $this->input->post('rowid');

            foreach ($itemList as $rowId => $cartRow) {
                if ($rowId === $removeRowId) {
                    unset($itemList[$rowId]);
                    break;
                }
            }
            $this->session->set_userdata('choosen_items', $itemList);

            print json_encode(['isSuccessful' => true]);
        }
    }

    /**
     * Get cart total shipping fee 
     * @return float
     */
    public function getCartTotalShippingFee()
    {
        $memberId = $this->session->userdata('member_id');
        $cityLocation = (int)$this->input->post('city_id'); 
        $shippingFee = $this->cartManager->getCartShippingFee($cityLocation, $memberId, true);

        if($shippingFee){
            echo number_format($shippingFee, 2, '.', '');
        }
        else{
            echo "Some product not available in location";
        }
    }
}
