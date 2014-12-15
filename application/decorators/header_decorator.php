<?php 

class Header_decorator extends Viewdecorator 
{

    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Generate header data
     *
     * @param string $title
     * @param string $metadescription
     * @param string $relCanonical
     * @param boolean $renderSearchbar
     */
    public function view($title = "", $metadescription = "" , $relCanonical = "", $renderSearchbar = true)
    {
        $cartManager = $this->serviceContainer['cart_manager'];
        $cartImplementation = $cartManager->getCartObject();
        
        $isLoggedIn = false;
        $member = null;
        $memberId = $this->session->userdata('member_id');
        $unreadMessageCount = 0;
        $cart = [];
        $cartSize = 0;
        
        if(!empty($memberId) || $this->check_cookie()){
            $isLoggedIn = true;
            $member = $this->serviceContainer['entity_manager']
                           ->getRepository('EasyShop\Entities\EsMember')
                           ->find($memberId);
            $memberAvatar = $this->serviceContainer['user_manager']
                                 ->getUserImage($memberId, 'small');
            $member->profileImage =  ltrim($memberAvatar, '/');
            $unreadMessageCount = $this->serviceContainer['entity_manager']
                                       ->getRepository('EasyShop\Entities\EsMessages')
                                       ->getUnreadMessageCount($memberId);
            $cart = array_values($cartManager->getValidatedCartContents($memberId));
            $cartSize = $cartImplementation->getSize(true);
        }

        $cartTotalAmount = $cartSize > 0 ? $cartImplementation->getTotalPrice() : 0;
        $parentCategories = $this->serviceContainer['entity_manager']
                                 ->getRepository('EasyShop\Entities\EsCat')
                                 ->getParentCategories();
        $parentCategories = $this->serviceContainer['category_manager']
                                 ->applyProtectedCategory($parentCategories, false);

        $menu = $this->serviceContainer['xml_cms']
                     ->getMenuData();
                                 
        $this->view_data['logged_in'] = $isLoggedIn;
        $this->view_data['user'] = $member;
        $this->view_data['cartSize'] = $cartSize;
        $this->view_data['cartItems'] = $cart;
        $this->view_data['cartTotal'] = $cartTotalAmount;
        $this->view_data['unreadMessageCount'] = $unreadMessageCount;
        $this->view_data['categories'] = $parentCategories;
        $this->view_data['title'] = $title;
        $this->view_data['metadescription'] = $metadescription;
        $this->view_data['relCanonical'] = $relCanonical;
        $this->view_data['menu'] = $menu;
        $this->view_data['renderSearchbar'] = $renderSearchbar;
    }
}

