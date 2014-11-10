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
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,$collectionHelper,$productManager,$cartManager)
    {
        $this->em = $em;  
        $this->collectionHelper = $collectionHelper;
        $this->productManager = $productManager;
        $this->cartManager = $cartManager;
        $this->cartImplementation = $cartManager->getCartObject();
    }

    public function formatItem($productId,$isItemView = false)
    {
        $product = $this->productManager->getProductDetails($productId);

        $productDetails = array(
                'name' => utf8_encode($product->getName()),
                'slug' => $product->getSlug(),
                'description' => $product->getDescription(),
                'brand' => $product->getBrand()->getName(),
                'condition' => $product->getCondition(),
                'discount' => $product->getDiscountPercentage(),
                'price' => floatval($product->getFinalPrice()),
                'original_price' => floatval($product->getOriginalPrice()),
            );

        // get product images
        $productImages = [];
        $prodImgObj = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                    ->findBy(['product' => $productId]);

        $imageType = 'categoryview/';
        if($isItemView){
            $imageType = '';
        }

        foreach ($prodImgObj as $key => $value) {
            $productImages[] = array(
                                'product_image_path' => $value->getDirectory().$imageType.$value->getFilename(),
                                'id' => $value->getIdProductImage(),
                            );
        }

        // get user rating
        $userRating = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback')
                            ->findOneBy(['forMemberid' => $product->getMember()->getIdMember()]);
        $rateDescription = array(
                    'rateCount' => 0,
                    'rateDescription' => array(
                                'Item quality' => 0,
                                'Communication' => 0,
                                'Shipment time' => 0,
                            )
                );
        if($userRating){
        $rateDescription = array(
                    'rateCount' => count($userRating),
                    'rateDescription' => array(
                            'Item quality' => $userRating->getRating1(),
                            'Communication' => $userRating->getRating2(),
                            'Shipment time' => $userRating->getRating3(),
                        )
                );
        }
        $sellerDetails = array(
                            'sellerName' => $product->getMember()->getUsername(),
                            'sellerRating' => $rateDescription,
                            'sellerContactNumber' => "0".$product->getMember()->getContactno(),
                            'sellerEmail' => $product->getMember()->getEmail(),
                        );

        // get product combination
        $productAttributes = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->getProductAttributeDetailByName($productId);

        $productAttributesPrice = [];
        foreach ($productAttributes as $key => $value) {
            $productAttributesPrice[$value['attr_id']] = $value['attr_price'];
        }

        $productAttributes = $this->collectionHelper->organizeArray($productAttributes,true);

        // get product specification
        $productSpecification = array() ; $productCombinationAttributes = array(); 
        foreach ($productAttributes as $key => $productOption) {
            $newArrayOption = array(); 

            for ($i=0; $i < count($productOption) ; $i++) {
                $type = ($productAttributes[$key][$i]['type'] == 'specific' ? 'a' : 'b');
                $newKey = $type.'_'.$productAttributes[$key][$i]['attr_id'];
                $newArrayOption[] = array(
                                    'value' => $productOption[$i]['attr_value'],
                                    'price'=> $productOption[$i]['attr_price'],
                                    'img_id'=> $productImages[0]['id'],
                                    'name'=> $productOption[$i]['attr_name'],
                                    'id'=> $newKey
                                ); 
            }
 

            if(count($productOption)>1){
                $productCombinationAttributes = array_merge($productCombinationAttributes,$newArrayOption);
            }
            elseif((count($productOption) === 1)
                        &&(($productOption[0]['datatype_id'] === '5'))
                        ||($productOption[0]['type'] === 'option')){ 
                $productCombinationAttributes = array_merge($productCombinationAttributes,$newArrayOption);
                $productSpecification[] = $newArrayOption[0];
            }
            else{
                $productSpecification[] = $newArrayOption[0]; 
            }
        }

        // get product quantity
        $productQuantityObject = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->getProductInventoryDetail($productId);

        $temporaryArray = [];
        foreach ($productQuantityObject as $key => $value) {
             if(!array_key_exists($value['id_product_item'],$temporaryArray)){
                $temporaryArray[$value['id_product_item']] = array(
                                                            'quantity' => $value['quantity'],
                                                            'product_attribute_ids' => array(
                                                                            array(
                                                                                'id' => $value['product_attr_id'],
                                                                                'is_other' => $value['is_other'],
                                                                            )
                                                                        ),
                                                        );
             }
             else{ 
                $temporaryArray[$value['id_product_item']]['product_attribute_ids'][] =array( 
                                                                                'id' => $value['product_attr_id'],
                                                                                'is_other' => $value['is_other'],
                                                                            );
             }
        }

        $productQuantity = [];
        foreach ($temporaryArray as $key => $valuex) { 
            $newCombinationKey = array();
            $totalPrice = 0;
            for ($i=0; $i < count($valuex['product_attribute_ids']); $i++) { 
                $type = ($valuex['product_attribute_ids'][$i]['is_other'] == '0' ? 'a' : 'b'); 
                array_push($newCombinationKey, $type.'_'.$valuex['product_attribute_ids'][$i]['id']);
                if(isset($productAttributesPrice[$valuex['product_attribute_ids'][$i]['id']])){
                    $totalPrice += $productAttributesPrice[$valuex['product_attribute_ids'][$i]['id']];
                }
            }

            unset($temporaryArray[$key]['product_attribute_ids']);
            $temporaryArray[$key]['combinationId'] = ($newCombinationKey[0] == "a_0") ? [] : $newCombinationKey;
            $temporaryArray[$key]['id'] = $key;
            $temporaryArray[$key]['price'] = floatval(number_format($totalPrice + $product->getFinalPrice(), 2,'.',''));
            $productQuantity[] = $temporaryArray[$key];
        }

        // get reviews 
        $recentReview = []; 
        $productReviewObject = $this->em->getRepository('EasyShop\Entities\EsProductReview')
                                    ->getProductReview($productId);
        if($productReviewObject){
            $retrieve = array();
            foreach ($productReviewObject as $key => $value) {
                $retrieve[] = $value->getIdReview();
            }

            $productRepliesObject = $this->em->getRepository('EasyShop\Entities\EsProductReview')
                                                ->getReviewReplies($productId,$retrieve);

            foreach($productRepliesObject as $key=>$temp){
                $temp->setReview(html_escape($temp->getReview()));
            }

            $i = 0;
            foreach ($productReviewObject as $key => $value) { 
                $recentReview[$i]['id_review'] = $value->getIdReview();
                $recentReview[$i]['title'] = $value->getTitle();
                $recentReview[$i]['review'] = $value->getReview();
                $recentReview[$i]['rating'] = $value->getRating();
                $recentReview[$i]['datesubmitted'] = $value->getDatesubmitted()->format('Y-m-d H:i:s');
                $recentReview[$i]['reviewer'] = $value->getMember()->getUsername();
                $recentReview[$i]['replies'] = array();
                $recentReview[$i]['reply_count'] = 0;

                foreach($productRepliesObject as $reply){ 
                    if($value->getIdReview() == $reply->getPReviewid()){  
                        $recentReview[$i]['replies'][] = array(
                                                    'id_review' => $reply->getIdReview(),
                                                    'review' => $reply->getReview(),
                                                    'rating' => $reply->getRating(),
                                                    'datesubmitted' => $reply->getDatesubmitted()->format('Y-m-d H:i:s'),
                                                    'reviewer' => $reply->getMember()->getUsername(),
                                                );
                        $recentReview[$i]['reply_count']++;
                    }
                }
                $i++;
            } 
        }

        return array(
                'productDetails' => $productDetails,
                'productImages' => $productImages,
                'sellerDetails' => $sellerDetails,
                'productCombinationAttributes' => $productCombinationAttributes,
                'productSpecification' => $productSpecification,
                'productQuantity' => $productQuantity,
                'reviews' => $recentReview,
            );
    }

    public function formatCart($cartData)
    { 
        $formattedCartContents = array();
        $finalCart = array();
        foreach($cartData as $rowId => $cartItem){
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->findOneBy(['idProduct' => $cartItem['id']]);

            if($product){
                $productId = $product->getIdProduct();
                $member = $product->getMember();
                $attributes = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->getAttributesByProductIds($productId);

                $mappedAttributes = array();
                foreach($attributes as $attribute){
                    $isSelected = "false";
                    $optionalIdentifier = intval($attribute['is_other']) === 0 ? 'a_' : 'b_';

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

                    array_push($mappedAttributes, array(
                        'id' => $optionalIdentifier.$attribute['detail_id'],
                        'value' => $attribute['value'],
                        'name' => $attribute['head'],
                        'price' => $attribute['price'],
                        'imageId' => $attribute['image_id'],
                        'isSelected' => $isSelected,
                    ));
                }

                $formattedCartContents[$rowId] = [
                    'rowid' => $cartItem['rowid'],
                    'productId' =>  $cartItem['id'],
                    'productItemId' => $cartItem['product_itemID'], 
                    'slug' => $cartItem['slug'],
                    'name' => utf8_encode($cartItem['name']),
                    'quantity' => $cartItem['qty'], 
                    'originalPrice' => $cartItem['original_price'],
                    'finalPrice' => $cartItem['price'],  
                    'isAvailable' => isset($cartItem['isAvailable']) ? $cartItem['isAvailable'] : "true",  
                    'mapAttributes' => $mappedAttributes
                ];

                $format = $this->formatItem($cartItem['id']);
                $finalCart[] = array_merge($formattedCartContents[$rowId],$format);
            }
        } 

        return $finalCart;
    }

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

        return array(
                    'name' => utf8_encode($product->getName()), 
                    'slug' => $product->getSlug(),
                    'condition' => $product->getCondition(),
                    'discount' => floatval($product->getDiscountPercentage()),
                    'price' => floatval($product->getFinalPrice()),
                    'original_price' => floatval($product->getOriginalPrice()),
                    'product_image' => $imageDirectory.'categoryview/'.$imageFileName
                );
    }

    public function updateCart($mobileCartContents,$memberId)
    {
        $this->cartImplementation->destroy();
        foreach($mobileCartContents as $mobileCartContent){

            $options = array();
            foreach($mobileCartContent->mapAttributes as $attribute => $attributeArray){
                if(intval($attributeArray->isSelected) === 1 || strtolower($attributeArray->isSelected) === "true"){
                    $options[trim($attributeArray->name, "'")] = $attributeArray->value.'~'.$attributeArray->price;
                }
               
            }
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findOneBy(['slug' => $mobileCartContent->slug]);
            if($product){
                $this->cartManager->addItem($product->getIdProduct(), $mobileCartContent->quantity, $options);
            }
        }
        $this->cartImplementation->persist($memberId);
		
    }
}
 