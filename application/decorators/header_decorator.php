<?php 

class Header_decorator extends Viewdecorator 
{
    /**
     * Generate header data
     *
     * @param string $title
     * @param string $metadescription
     * @param string $relCanonical
     * @param boolean $renderSearchbar
     */
    public function view($memberId, $title = "", $metadescription = "" , $relCanonical = "", $renderSearchbar = true)
    {
        $cartManager = $this->serviceContainer['cart_manager'];
        $cartImplementation = $cartManager->getCartObject();
        $messageManager = $this->serviceContainer['message_manager'];
        $isLoggedIn = false;
        $member = null;
        $unreadMessageCount = 0;
        $cart = [];
        $cartSize = 0;
        $chatServerHost = 0;
        $chatServerPort = 0;
        $listOfFeatureWithRestriction = '';

        if($memberId){
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
            $chatServerHost = $messageManager->getChatHost(true);
            $chatServerPort = $messageManager->getChatPort();
            $allowedFeatures = $this->serviceContainer['member_feature_restrict_manager']
                                                 ->getAllowedFeaturesForMember($memberId);
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
        $this->view_data['chatServerHost'] = $chatServerHost;
        $this->view_data['chatServerPort'] = $chatServerPort;
        $this->view_data['jwtToken'] = $this->jwtToken;
        $this->view_data['allowedFeatures'] = $allowedFeatures;
    }
}

