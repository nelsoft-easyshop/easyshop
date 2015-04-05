<?php 

namespace EasyShop\Api;

use EasyShop\Entities\EsProductImage as EsProductImage;
use EasyShop\Entities\EsLocationLookup as EsLocationLookup;
 
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
                'discount' => number_format($product->getDiscountPercentage(), 0, '.', ''),
                'price' => number_format($product->getFinalPrice(), 2, '.', ''),
                'original_price' => number_format($product->getOriginalPrice(), 2, '.', ''),
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
        $listArray = [];
        foreach ($formattedProductAttributes as $key => $productOption) {
            $newArrayOption = []; 

            for ($i=0; $i < count($productOption) ; $i++) {
                $type = ($formattedProductAttributes[$key][$i]['type'] == 'specific' ? 'a' : 'b');
                $newKey = $type.'_'.$formattedProductAttributes[$key][$i]['attr_id'];
                $newArrayOption[] = $listArray[$newKey] = [
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

        $temporaryArray = $this->productManager->getProductInventory($product);
        $productQuantity = [];
        foreach ($temporaryArray as $key => $valuex) {
            unset($temporaryArray[$key]['attr_lookuplist_item_id']);
            unset($temporaryArray[$key]['attr_name']);
            unset($temporaryArray[$key]['is_default']);
            $newCombinationKey = []; 
            for ($i=0; $i < count($valuex['product_attribute_ids']); $i++) { 
                $type = ($valuex['product_attribute_ids'][$i]['is_other'] == '0' ? 'a' : 'b');
                $newCombinationKey[] = $type.'_'.$valuex['product_attribute_ids'][$i]['id']; 
            }

            unset($temporaryArray[$key]['product_attribute_ids']);
            $temporaryArray[$key]['id'] = $key;
            $temporaryArray[$key]['price'] = number_format($product->getFinalPrice(), 2,'.','');
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

        foreach($productQuantity as $keyQuantity => $valueQuantity){
            $additionalPrice = 0;
            $original_price = $productQuantity[$keyQuantity]['price'];
            foreach($valueQuantity['combinationId'] as $cid){ 
                $additionalPrice += isset($listArray[$cid]) ? $listArray[$cid]['price'] : 0;
            }
            $productQuantity[$keyQuantity]['price'] = number_format(bcadd($original_price, $additionalPrice , 4), 2,'.','');
        }

        $recentReview = $this->reviewProductService->getProductReview($productId);

        
        $shipmentDetails = [];
        $shipmentPriceArray = [];
        $isFreeShippingNationwide = $this->productManager->isFreeShippingNationwide($productId);
        $shipmentDetails = [
            'min' => 0,
            'max' => 0
        ];
        if(!$isFreeShippingNationwide){
            $shippingDetails = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                                        ->getShippingDetailsByProductId($productId);
            foreach ($shippingDetails as $key => $value) {
                $shipmentPriceArray[] = $value['price']; 
            }

            $shipmentDetails = [
                'min' => empty($shipmentPriceArray) ? 0 : min($shipmentPriceArray),
                'max' => empty($shipmentPriceArray) ? 0 : max($shipmentPriceArray)
            ];
        } 

        return [
            'productDetails' => $productDetails,
            'productShipmentFee' => $shipmentDetails,
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
     * @param  mixed   $cartData
     * @param  boolean $includeValidation
     * @param  string  $paymentTypeString
     * @return mixed
     */
    public function formatCart($cartData, $includeValidation = false, $paymentTypeString = "")
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
                    'rowid' => isset($cartItem['rowid']) ? $cartItem['rowid'] : $rowId ,
                    'productId' =>  $cartItem['id'],
                    'productItemId' => $cartItem['product_itemID'], 
                    'slug' => $cartItem['slug'],
                    'name' => utf8_encode($cartItem['name']),
                    'quantity' => $cartItem['qty'],  
                    'isAvailable' => false,
                    'shippingFee' => 0,
                    'mapAttributes' => $mappedAttributes,
                    'error_message' => isset($cartItem['error_message']) ? $cartItem['error_message'] : [],
                ];

                if($includeValidation){
                    $errorMessage = [];

                    if(!isset($cartItem[$paymentTypeString]) || !$cartItem[$paymentTypeString]){
                        $errorMessage[] = "Not Available for selected payment type";
                    }

                    if(!$cartItem['canPurchaseWithOther']){
                        $errorMessage[] = "This item can only be purchased individually.";
                    }

                    if(!$cartItem['hasNoPuchaseLimitRestriction']){
                        $errorMessage[] = "You have exceeded your purchase limit for a promo for this item.";
                    }

                    if(!$cartItem['isAvailableInLocation']){
                        $errorMessage[] = "This item is not available in your location.";
                    }

                    if(!$cartItem['isQuantityAvailable']){
                        $errorMessage[] = "The availability of this items is less than your desired quantity.";
                    }

                    if(isset($formattedCartContents[$rowId]['error_message'])){
                        $formattedCartContents[$rowId]['error_message'] = array_merge($formattedCartContents[$rowId]['error_message'], $errorMessage);
                    }
                    else{
                        $formattedCartContents[$rowId]['error_message'] = $errorMessage;
                    }

                    $formattedCartContents[$rowId]['shippingFee'] = bcmul($cartItem['shippingFee'], $cartItem['qty'], 4);
                }
                $formattedCartContents[$rowId]['isAvailable'] = empty($formattedCartContents[$rowId]['error_message']);

                $format = $this->formatItem($cartItem['id']);
                $finalCart[] = array_merge($formattedCartContents[$rowId],$format);
            }
        } 

        return $finalCart;
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
            'discount' => number_format($product->getDiscountPercentage(), 0, '.', ''),
            'price' => number_format($product->getFinalPrice(), 2, '.', ''),
            'original_price' => number_format($product->getOriginalPrice(), 2, '.', ''),
            'product_image' => $imageDirectory.'categoryview/'.$imageFileName,
            'isFreeShipping' => $product->getIsFreeShipping(),
        ];
    }

    /**
     * Update Cart data and format cart item array
     * @param  mixed    $mobileCartContents 
     * @param  integer  $memberId 
     * @param  boolean  $includeUnavailable
     * @return mixed
     */
    public function updateCart($mobileCartContents, $memberId, $includeUnavailable = true)
    {
        $unavailableItem = [];
        $itemList = [];  
        $this->cartImplementation->destroy();
        foreach($mobileCartContents as $mobileCartContent){
            $options = [];
            foreach($mobileCartContent->mapAttributes as $attribute => $attributeArray){
                if( (is_bool($attributeArray->isSelected) && (bool)$attributeArray->isSelected) 
                     || is_string($attributeArray->isSelected) && strtolower($attributeArray->isSelected) === "true"){
                    $options[trim($attributeArray->name, "'")] = $attributeArray->value.'~'.$attributeArray->price;
                }
               
            }
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findOneBy(['slug' => $mobileCartContent->slug]);

            $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                           ->find($memberId);
            if($product){
                $errorMessage = [];

                if($product->getMember()->getIdMember() === (int) $memberId){
                    $errorMessage[] = "This is your own item!"; 
                }
                else{
                    $this->cartManager->addItem($product->getIdProduct(), $mobileCartContent->quantity, $options);
                }

                $cartContent = $this->cartManager->validateSingleCartContent($product->getIdProduct(), 
                                                                             $options, 
                                                                             $mobileCartContent->quantity)['itemData'];

                if($cartContent){
                    if($mobileCartContent->quantity > $cartContent['maxqty']){
                        $errorMessage[] = "Quantity Not Available";
                    }
                }
                else{
                    $errorMessage[] = "Item not available.";
                }

                if((bool)$member->getIsEmailVerify() === false){
                    $errorMessage[] = "Please verify your email.";
                }

                $itemList[$cartContent['product_itemID']] = [
                    'rowid' => $product->getIdProduct(),
                    'id' =>  $product->getIdProduct(),
                    'product_itemID' => $cartContent['product_itemID'], 
                    'slug' => $product->getSlug(),
                    'name' => utf8_encode($product->getName()),
                    'qty' => $mobileCartContent->quantity, 
                    'original_price' => $product->getPrice(),
                    'price' => $product->getPrice(),
                    'options' => isset($cartContent['options']) ? $cartContent['options'] : [],
                    'error_message' => $errorMessage,
                ];
            }
        }
        $this->cartImplementation->persist($memberId);
        $cartData = $this->cartManager->getValidatedCartContents($memberId);

        foreach ($cartData as $data) {
            if(isset($itemList[$data['product_itemID']])) {
                unset($itemList[$data['product_itemID']]);
            }
        }

        foreach ($itemList as $key => $item) {
            if(empty($item['error_message'])){
                $itemList[$key]['error_message'][] = "This item is unavailable.";
            }
        }

        if(!$includeUnavailable){
            return $this->formatCart($cartData);
        }

        return [
            'availableItems' => $this->formatCart($cartData),
            'unavailableItems' => $this->formatCart($itemList), 
            'canContinue' => count($itemList) ===  0,
        ];
    }

    /**
     * Format location for shipping list
     * @return array
     */
    public function formatLocationForShipping()
    {
        $location = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                             ->getLocation();

        $formedArray = [];
        foreach ($location['area'] as $majorIsland => $region) {
            $regionArray = [];
            foreach ($region as $regionKey => $province) {
                $provinceArray = [];
                foreach ($province as $key => $value) {
                    $provinceArray[] = [
                        'name' => $value,
                        'location_id' => $key,
                        'children' => [],
                    ];
                }
                $regionArray[] = [
                    'name' => $regionKey,
                    'location_id' => $location['regionkey'][$regionKey],
                    'children' => $provinceArray,
                ];
            }

            $array = [
                'name' => $majorIsland,
                'location_id' => $location['islandkey'][$majorIsland],
                'children' => $regionArray,
            ];
            $formedArray[] = $array;
        }

        return [
            'name' => 'Philippines',
            'location_id' => EsLocationLookup::PHILIPPINES_LOCATION_ID,
            'children' => $formedArray,
        ];
    }

    /**
     * Format location for address list
     * @return array
     */
    public function formatLocationForAddress()
    {
        $esLocationLookupRepository = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');
        $data['available_selection'] = $esLocationLookupRepository->getLocationLookup(); 
        $modifiedArray = [];
        $modifiedArray[0]['countryId'] = $data['available_selection']['countryId'];
        $modifiedArray[0]['coutryName'] = $data['available_selection']['countryName']; 
        $counter = 0;
        
        foreach ($data['available_selection']['stateRegionLookup'] as $key => $value) {
            $modifiedArray[0]['regions'][$counter]['regionId'] = $key; 
            $modifiedArray[0]['regions'][$counter]['regionName'] = $value;
            $arrayCity = [];
            $cityCounter = 0;
            foreach ($data['available_selection']['cityLookup'][$key] as $keyCity => $valueCity) {
                $arrayCity[$cityCounter]['cityId'] = $keyCity; 
                $arrayCity[$cityCounter]['cityName'] = $valueCity;
                $cityCounter++;
            }
            $modifiedArray[0]['regions'][$counter]['cities'] = $arrayCity;
            $counter++;
        }

        return $modifiedArray;
    }
}

