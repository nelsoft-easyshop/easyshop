<?php 

namespace EasyShop\Api;

use EasyShop\Entities\EsProductImage as EsProductImage;
 
class ApiFormatter
{
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Collection Helper
     *
     * @var Easyshop\CollectionHelper\CollectionHelper
     */
    private $collectionHelper;

    /**
     * The product manager
     *
     * @var EasyShop\Cart\CartManager
     */
    private $cartManager;

    /**
     * The cartManager
     *
     * @var EasyShop\Product\ProductManager
     */
    private $productManager;

    /**
     * The review product service
     *
     * @var EasyShop\Review\ReviewProduct
     */
    private $reviewProductService;

    /**
     * StringUtility
     *
     * @var EasyShop\Utility\StringUtility
     */
    private $stringUtility;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,
                                $collectionHelper,
                                $productManager,
                                $cartManager,
                                $reviewProductService,
                                $stringUtility)
    {
        $this->em = $em;  
        $this->collectionHelper = $collectionHelper;
        $this->productManager = $productManager;
        $this->cartManager = $cartManager;
        $this->cartImplementation = $cartManager->getCartObject();
        $this->reviewProductService = $reviewProductService;
        $this->stringUtility = $stringUtility;
    }

    /**
     * Format item array to display on mobile api
     * @param  integer $productId  [description]
     * @param  boolean $isItemView [description]
     * @return array
     */
    public function formatItem($productId, $isItemView = false)
    {
        $esMemberFeedbackRepo = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback');
        $esProductRepo = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $product = $this->productManager->getProductDetails($productId);
        $memberId = $product->getMember()->getIdMember();

        $productDetails = [
                'name' => utf8_encode($product->getName()),
                'slug' => $product->getSlug(),
                'description' => $this->stringUtility->purifyHTML($product->getDescription()),
                'brand' => $product->getBrand()->getName(),
                'condition' => $product->getCondition(),
                'discount' => $product->getDiscountPercentage(),
                'price' => floatval($product->getFinalPrice()),
                'original_price' => floatval($product->getOriginalPrice()),
                'isFreeShipping' => $product->getIsFreeShipping(),
            ];

        // get product images
        $productImages = [];
        $prodImgObj = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                               ->findBy(['product' => $productId]);

        $imageType = 'categoryview/';
        if($isItemView){
            $imageType = '';
        }

        foreach ($prodImgObj as $key => $value) {
            $productImages[] = [
                            'product_image_path' => $value->getDirectory().$imageType.$value->getFilename(),
                            'id' => $value->getIdProductImage(),
                        ];
        }

        // get user rating
        $userRating = $esMemberFeedbackRepo->getUserFeedbackAverageRating($memberId);

        $userRatingCount = $esMemberFeedbackRepo->getUserTotalFeedBackCount($memberId);

        $rateDescription = [
                    'rateCount' => $userRatingCount,
                    'rateDescription' => [
                            'Item quality' => $userRating['rating1'],
                            'Communication' => $userRating['rating2'],
                            'Shipment time' => $userRating['rating3'],
                        ]
                ];
        $sellerDetails = [
                    'sellerName' => $product->getMember()->getUsername(),
                    'sellerRating' => $rateDescription,
                    'sellerContactNumber' => $product->getMember()->getContactno() ? "0".$product->getMember()->getContactno() : "N/A",
                    'sellerEmail' => $product->getMember()->getEmail(),
                ];

        // get product combination
        $productAttributes = $esProductRepo->getProductAttributeDetailByName($productId);

        $productAttributesPrice = [];
        foreach ($productAttributes as $key => $value) {
            $productAttributesPrice[$value['attr_id']] = $value['attr_price'];
        }

        $productAttributes = $this->collectionHelper->organizeArray($productAttributes,true);
        $formattedProductAttributes = $productAttributes;
        // get product specification
        $productSpecification = [] ; $productCombinationAttributes = []; 
        foreach ($formattedProductAttributes as $key => $productOption) {
            $newArrayOption = []; 

            for ($i=0; $i < count($productOption) ; $i++) {
                $type = ($formattedProductAttributes[$key][$i]['type'] == 'specific' ? 'a' : 'b');
                $newKey = $type.'_'.$formattedProductAttributes[$key][$i]['attr_id'];
                $newArrayOption[] = [
                                'value' => $productOption[$i]['attr_value'],
                                'price'=> $productOption[$i]['attr_price'],
                                'img_id'=> $productImages[0]['id'],
                                'name'=> $productOption[$i]['attr_name'],
                                'id'=> $newKey
                            ]; 
            }

            if(count($productOption)>1){
                $productCombinationAttributes = array_merge($productCombinationAttributes,$newArrayOption);
            }
            elseif((count($productOption) === 1)
                        &&(((int)$productOption[0]['datatype_id'] === 5))
                        ||((string)$productOption[0]['type'] === 'option')){ 
                $productCombinationAttributes = array_merge($productCombinationAttributes,$newArrayOption);
                $productSpecification[] = $newArrayOption[0];
            }
            else{
                $productSpecification[] = $newArrayOption[0]; 
            }
        }

        // get product quantity
        $productInventory = $esProductRepo->getProductInventoryDetail($productId);

        $temporaryArray = [];
        foreach ($productInventory as $key => $value) {
             if(!array_key_exists($value['id_product_item'],$temporaryArray)){
                $temporaryArray[$value['id_product_item']] = [
                                                        'quantity' => $value['quantity'],
                                                        'product_attribute_ids' => [
                                                                        [
                                                                'id' => $value['product_attr_id'],
                                                                'is_other' => $value['is_other'],
                                                            ]
                                                        ],
                                                    ];
             }
             else{ 
                $temporaryArray[$value['id_product_item']]['product_attribute_ids'][] = [
                                                                                'id' => $value['product_attr_id'],
                                                                                'is_other' => $value['is_other'],
                                                                            ];
             }
        }

        $productQuantity = [];
        foreach ($temporaryArray as $key => $valuex) { 
            $newCombinationKey = [];
            $totalPrice = 0;
            for ($i=0; $i < count($valuex['product_attribute_ids']); $i++) { 
                $type = ($valuex['product_attribute_ids'][$i]['is_other'] == '0' ? 'a' : 'b');
                $newCombinationKey[] = $type.'_'.$valuex['product_attribute_ids'][$i]['id'];
                if(isset($productAttributesPrice[$valuex['product_attribute_ids'][$i]['id']])){
                    $totalPrice += $productAttributesPrice[$valuex['product_attribute_ids'][$i]['id']];
                }
            }

            unset($temporaryArray[$key]['product_attribute_ids']);
            $temporaryArray[$key]['id'] = $key;
            $temporaryArray[$key]['price'] = floatval(number_format($totalPrice + $product->getFinalPrice(), 2,'.',''));
            if($newCombinationKey[0] === "a_0"){
                if(empty($productAttributes) === false){
                    $allCombination = $this->collectionHelper
                                           ->generateCombinations($productAttributes);
                    foreach ($allCombination as $keyComb => $combination) {
                        foreach ($combination as $value) {
                            $type = ($value['attr_id'] == '0') ? 'a' : 'b';
                            $temporaryArray[$keyComb]['combinationId'][] = $type . '_' . $value['attr_id'];
                        }
                        $temporaryArray[$key]['combinationId'] = $temporaryArray[$keyComb]['combinationId'];
                        $productQuantity[] = $temporaryArray[$key];
                    }
                }
                else{
                    $temporaryArray[$key]['combinationId'] = [];
                    $productQuantity[] = $temporaryArray[$key];
                }
                break;
            }

            $temporaryArray[$key]['combinationId'] = $newCombinationKey;
            $productQuantity[] = $temporaryArray[$key];
        }

        $recentReview = $this->reviewProductService->getProductReview($productId);

        return [
            'productDetails' => $productDetails,
            'productImages' => $productImages,
            'sellerDetails' => $sellerDetails,
            'productCombinationAttributes' => $productCombinationAttributes,
            'productSpecification' => $productSpecification,
            'productQuantity' => $productQuantity,
            'reviews' => $recentReview,
        ];
    }

    /**
     * Format cart array to display on mobile api
     * @param  array   $cartData 
     * @param  boolean $forPayment
     * @param  string  $paymentType
     * @return array
     */
    public function formatCart($cartData, $includeValidation = false)
    { 
        $formattedCartContents = [];
        $finalCart = []; 
        foreach($cartData as $rowId => $cartItem){
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findOneBy(['idProduct' => $cartItem['id']]);

            if($product){
                $productId = $product->getIdProduct();
                $member = $product->getMember();
                $attributes = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                       ->getAttributesByProductIds($productId);

                $mappedAttributes = [];
                foreach($attributes as $attribute){
                    $isSelected = "false";
                    $optionalIdentifier = (int)$attribute['is_other'] === 0 ? 'a_' : 'b_';

                    if( (int)$attribute['datatype_id'] === 5 
                        || (string) $attribute['type'] === 'option' ){

                        foreach($cartItem['options'] as $head => $option){
                            $explodedOption = explode('~',$option);
                            $fieldValue = $explodedOption[0];
                            $fieldPrice = isset($explodedOption[1]) ? $explodedOption[1] : 0;
                            if(strtolower($attribute['head']) == strtolower($head) &&
                                strtolower($attribute['value']) == strtolower($fieldValue) &&
                                strtolower($attribute['price']) == strtolower($fieldPrice)){
                                $isSelected = "true";
                                break;
                            }
                        }

                        $mappedAttributes[] = [
                            'id' => $optionalIdentifier.$attribute['detail_id'],
                            'value' => $attribute['value'],
                            'name' => $attribute['head'],
                            'price' => $attribute['price'],
                            'imageId' => $attribute['image_id'],
                            'isSelected' => $isSelected,
                        ];
                    }
                }

                $formattedCartContents[$rowId] = [
                    'rowid' => $cartItem['rowid'],
                    'productId' =>  $cartItem['id'],
                    'productItemId' => $cartItem['product_itemID'], 
                    'slug' => $cartItem['slug'],
                    'name' => utf8_encode($cartItem['name']),
                    'quantity' => $cartItem['qty'],  
                    'isAvailable' => false,  
                    'mapAttributes' => $mappedAttributes,
                ]; 

                if($includeValidation){
                    $validation = [
                        'canPurchaseWithOther' => $cartItem['canPurchaseWithOther'],
                        'hasNoPuchaseLimitRestriction' => $cartItem['hasNoPuchaseLimitRestriction'],
                        'isAvailableInLocation' => $cartItem['isAvailableInLocation'],
                        'isQuantityAvailable' => $cartItem['isQuantityAvailable'],
                        'dragonpay' => $cartItem['dragonpay'],
                        'paypal' => $cartItem['paypal'],
                        'cash_delivery' => $cartItem['cash_delivery'],
                        'pesopaycdb' => $cartItem['pesopaycdb'],
                        'directbank' => $cartItem['directbank'],
                        'error_message' => [],
                    ];

                    $formattedCartContents[$rowId] = array_merge($formattedCartContents[$rowId],$validation);
                }

                $format = $this->formatItem($cartItem['id']);
                $finalCart[] = array_merge($formattedCartContents[$rowId],$format);
            }
        } 

        return $finalCart;
    }

    /**
     * Include error on cart before checkout
     * @param  mixed  $cartContent
     * @param  string $paymentType
     * @return mixed
     */
    public function includeCartError($cartContent, $paymentType)
    { 
        foreach ($cartContent as $key => $cartItem) {
            if(!isset($cartItem[$paymentType]) || !$cartItem[$paymentType]){
                $cartContent[$key]['error_message'][] = "Not Available for selected payment type";
            }

            if(!$cartItem['canPurchaseWithOther']){
                $cartContent[$key]['error_message'][] = "This item can only be purchased individually.";
            }

            if(!$cartItem['hasNoPuchaseLimitRestriction']){
                $cartContent[$key]['error_message'][] = "You have exceeded your purchase limit for a promo for this item.";
            }

            if(!$cartItem['isAvailableInLocation']){
                $cartContent[$key]['error_message'][] = "This item is not available in your location.";
            }

            if(!$cartItem['isQuantityAvailable']){
                $cartContent[$key]['error_message'][] = "The availability of this items is less than your desired quantity.";
            }

            unset($cartContent[$key]['canPurchaseWithOther']);
            unset($cartContent[$key]['hasNoPuchaseLimitRestriction']);
            unset($cartContent[$key]['isAvailableInLocation']);
            unset($cartContent[$key]['isQuantityAvailable']);
            unset($cartContent[$key]['dragonpay']);
            unset($cartContent[$key]['paypal']);
            unset($cartContent[$key]['cash_delivery']);
            unset($cartContent[$key]['pesopaycdb']);
            unset($cartContent[$key]['directbank']);

            $cartContent[$key]['isAvailable'] = empty($cartContent[$key]['error_message']);
        }

        return $cartContent;
    }

    /**
     * Format display item array 
     * @param  integer $idProduct
     * @return array
     */
    public function formatDisplayItem($idProduct)
    {
        $product = $this->productManager->getProductDetails($idProduct);
        $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                 ->getDefaultImage($idProduct);

        $imageDirectory = EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
        $imageFileName = EsProductImage::IMAGE_UNAVAILABLE_FILE;

        if($productImage != NULL){
            $imageDirectory = $productImage->getDirectory();
            $imageFileName = $productImage->getFilename();
        }

        return [
            'name' => utf8_encode($product->getName()), 
            'slug' => $product->getSlug(),
            'condition' => $product->getCondition(),
            'discount' => floatval($product->getDiscountPercentage()),
            'price' => floatval($product->getFinalPrice()),
            'original_price' => floatval($product->getOriginalPrice()),
            'product_image' => $imageDirectory.'categoryview/'.$imageFileName,
            'isFreeShipping' => $product->getIsFreeShipping(),
        ];
    }

    /**
     * Update Cart data and format cart item array
     * @param  array $mobileCartContents
     * @param  integer $memberId
     * @return array
     */
    public function updateCart($mobileCartContents, $memberId)
    {
        $unavailableItem = [];
        $itemList = []; 
        $slugList = []; 
        $this->cartImplementation->destroy();
        foreach($mobileCartContents as $mobileCartContent){
            $options = [];
            foreach($mobileCartContent->mapAttributes as $attribute => $attributeArray){
                if( (bool) $attributeArray->isSelected || strtolower($attributeArray->isSelected) === "true"){
                    $options[trim($attributeArray->name, "'")] = $attributeArray->value.'~'.$attributeArray->price;
                }
               
            }
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findOneBy(['slug' => $mobileCartContent->slug]);
            if($product){
                $this->cartManager->addItem($product->getIdProduct(), $mobileCartContent->quantity, $options);
                $itemList[] = [
                    'rowid' => $product->getIdProduct(),
                    'id' =>  $product->getIdProduct(),
                    'product_itemID' => 0, 
                    'slug' => $product->getSlug(),
                    'name' => utf8_encode($product->getName()),
                    'qty' => $mobileCartContent->quantity, 
                    'original_price' => $product->getPrice(),
                    'price' => $product->getPrice(),
                    'options' => [], 
                ];
                $slugList[] = $product->getSlug();
            }
        }
        $this->cartImplementation->persist($memberId);
        $cartData = $this->cartManager->getValidatedCartContents($memberId);

        foreach ($cartData as $data) {  
            if(($key = array_search($data['slug'], $slugList)) !== false) {
                unset($itemList[$key]);
            }
        } 

        return [
            'availableItems' => $this->formatCart($cartData),
            'unavailableItems' => $this->formatCart($itemList),
        ];
    }
}
 