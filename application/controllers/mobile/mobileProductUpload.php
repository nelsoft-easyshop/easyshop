<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
use EasyShop\Upload\AssetsUploader as AssetsUploader;
use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Entities\EsBillingInfo as EsBillingInfo;
use EasyShop\Entities\EsLocationLookup as EsLocationLookup;

class MobileProductUpload extends MY_Controller 
{
    /**
     * The oauth2 server
     *
     */
    private $oauthServer;

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;
    
    /**
     * The authenticated member
     *
     * @var EasyShop\Entities\EsMember
     */
    private $member;

    /**
     * Mobile payment constructor
     */
    function __construct() 
    {

        // https://github.com/bshaffer/oauth2-server-php/issues/155
        // for inquiry in making access token as get param.

        parent::__construct();  
        $this->oauthServer =  $this->serviceContainer['oauth2_server'];
        $this->em = $this->serviceContainer['entity_manager'];
        $this->productUploadManager = $this->serviceContainer['product_upload_manager'];
        $this->stringUtility = $this->serviceContainer['string_utility'];

        header('Content-type: application/json');

        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        if (! $this->oauthServer->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $this->oauthServer->getResponse()->send();
            die;
        }
        
        $oauthToken = $this->oauthServer->getAccessTokenData(OAuth2\Request::createFromGlobals());
        $this->member = $this->em->getRepository('EasyShop\Entities\EsMember')->find($oauthToken['user_id']);
    }

    /**
     * Request token to upload product.
     * @return json
     */
    public function requestUploadToken()
    {
        $temporaryId = "";
        $date = date("Ymd");
        $isSuccess = false;
        $bankArray = [];
        $memberId = $this->member->getIdMember();
        $errorMessage = "";
 
        if((bool)$this->member->getIsEmailVerify()){
            $temporaryId = $this->productUploadManager->requestUploadToken($this->member->getIdMember());
            $tempDirectory =  'assets/temp_product/'. $temporaryId.'_'.$memberId.'_'.$date.'/'; 
            mkdir($tempDirectory, 0777, true);
            mkdir($tempDirectory.'categoryview/', 0777, true);
            mkdir($tempDirectory.'small/', 0777, true);
            mkdir($tempDirectory.'thumbnail/', 0777, true);
            mkdir($tempDirectory.'other/', 0777, true);
            mkdir($tempDirectory.'other/categoryview/', 0777, true);
            mkdir($tempDirectory.'other/small/', 0777, true);
            mkdir($tempDirectory.'other/thumbnail/', 0777, true);

            $bankAccounts = $this->em->getRepository("EasyShop\Entities\EsBillingInfo")
                                     ->findBy([
                                         "member"=>$this->member->getIdMember()
                                     ]);

            foreach ($bankAccounts as $account) {
                $bankArray[] = [
                    'billing_id' => $account->getIdBillingInfo(),
                    'account_name' => $account->getBankAccountName(),
                    'account_number' => $account->getBankAccountNumber(),
                    'bank_id' => $account->getBankId(),
                ];
            }

            $isSuccess = false;
        }
        else{
            $errorMessage = "Please verify your email address."
        }

        $returnArray = [
            'upload_token' => $temporaryId,
            'bankAccounts' => $bankArray,
            'isSuccess' => $isSuccess,
            'errorMessage' => $errorMessage,
        ];

        echo json_encode($returnArray);
    }

    /**
     * request to upload image on server
     * @return json
     */
    public function uploadImage()
    { 
        $assetsUploader = $this->serviceContainer['assets_uploader']; 
        $fileNames = [];
        $imageUpload = [];
        $currentImageCount = (int) trim($this->input->post('pictureCount')); 
        $tempId = (string) trim($this->input->post('upload_token')); 
        $isSuccess = false;
        $errorMessage = "";

        if((bool)$this->member->getIsEmailVerify()){
            if($tempId === (string) $this->member->getTempId()){
                if(isset($_FILES['userfile'])){   
                    $memberId = $this->member->getIdMember();
                    $date = date("Ymd"); 
                    $fullDate = date("YmdGis");
                    $tempDirectory =  'assets/temp_product/'. $tempId.'_'.$memberId.'_'.$date.'/'; 
                    foreach ($_FILES['userfile']['name'] as $key => $value) {
                        $fileExtension = strtolower(end(explode('.', $_FILES['userfile']['name'][$key]))); 
                        $fileNames[] = $tempId.'_'.$memberId.'_'.$fullDate.$currentImageCount.'.'.$fileExtension;
                        $currentImageCount++; 
                    }
                    $assetsUploader->uploadProductImage($fileNames, $tempDirectory);
                    $isSuccess = true;
                }
                else{
                    $errorMessage = "No image selected.";
                }
            }
            else{ 
                $errorMessage = "Invalid Request. Upload token did not match.";
            }
        }
        else{
            $errorMessage = "Please verify your email address."
        }

        $imageUpload = [
            'isSuccess' => $isSuccess,
            'fileNames' => $fileNames,
            'errorMessage' => $errorMessage,
            'pictureCount' => $currentImageCount
        ];
 
        echo json_encode($imageUpload, JSON_PRETTY_PRINT);
    }

    /**
     * process upload request of mobile
     * @return json
     */
    public function processUpload()
    { 
        $esBillingInfoRepo = $this->em->getRepository('EasyShop\Entities\EsBillingInfo'); 

        $isSuccess = false;
        $errorMessage = "";
        $date = date("Ymd");
        $images = [];
        $attributes = [];
        $shippingInfo = [];
        $memberId = $this->member->getIdMember();
        $billingInfoId = EsBillingInfo::DEFAULT_BILLING_ID;

        if((bool)$this->member->getIsEmailVerify()){
            $tempId = (string) trim($this->input->post('upload_token')); 
            if($tempId === (string) $this->member->getTempId()){
                $productTitle = trim($this->input->post('title'));
                $productDescription = trim($this->input->post('description'));
                $categoryId = trim($this->input->post('category'));
                $price = (float) trim(str_replace(',', '', $this->input->post('price')));
                $discount = (float) trim($this->input->post('discount'));
                $isCod = trim($this->input->post('isCod')) === "true";
                $isMeetUp = trim($this->input->post('isMeetUp')) === "true";
                $quantity = (int) trim($this->input->post('quantity'));
                $imageArray = json_decode(trim($this->input->post('images')), true);
                $shippingArray = json_decode(trim($this->input->post('shippingInfo')), true);
                $attributeArray = json_decode(trim($this->input->post('attributes')), true);
                $condition = trim($this->input->post('condition'));
                $bankDetails = json_decode(trim($this->input->post('bankDetails')), true);
                $isFreeShippingNationwide = trim($this->input->post('isFreeShippingNationwide')) === "true";
                
                if(is_null($imageArray) === false
                   && isset($imageArray[0])){
                    $images = $imageArray; 
                }

                if(is_null($attributeArray) === false ){
                    $attributes = $attributeArray; 
                }

                if(is_null($shippingArray) === false
                   && isset($shippingArray[0])){
                    $shippingInfo = $shippingArray;
                }

                $validData = [
                    'productName' => $productTitle,
                    'productPrice' => $price,
                    'productDescription' => $productDescription,
                    'images' => $images, 
                    'condition' => $condition, 
                ]; 

                $validate = $this->productUploadManager->validateUploadRequest($validData);
                if($validate['isSuccess']){

                    if(is_null($bankDetails) === false && isset($bankDetails['billing_info'])){
                        $billingInfoId = $bankDetails['billing_info'];
                        $accountName = $bankDetails['account_name'];
                        $accountNumber = $bankDetails['account_number'];
                        $bankId = $bankDetails['bank_id'];
                        $bankInfo = $esBillingInfoRepo->findOneBy([
                                                            'idBillingInfo' => $billingInfoId,
                                                            'member' => $memberId,
                                                        ]);

                        if($bankInfo){
                            $bankInfo->setDatemodified(date_create(date("Y-m-d H:i:s")));
                            $bankInfo->setBankAccountName($accountName);
                            $bankInfo->setBankAccountNumber($accountNumber);
                            $bankInfo->setBankId($bankId);
                            $this->em->flush(); 
                        }
                        else{
                            $newAccount = $esBillingInfoRepo->createNewPaymentAccount(
                                $memberId, 
                                $accountName, 
                                $accountNumber, 
                                $bankId
                            );

                            $billingInfoId = $newAccount->getIdBillingInfo();
                        }
                    } 

                    $product = $this->productUploadManager->createProduct(
                                                                $productTitle,
                                                                $condition,
                                                                $productDescription,
                                                                $categoryId,
                                                                $memberId,
                                                                $price,
                                                                $discount,
                                                                $isCod,
                                                                $isMeetUp,
                                                                EsProduct::ACTIVE,
                                                                $billingInfoId
                                                            );
                    if($product){
                        $productId = $product->getIdProduct();
                        $tempDirectory = './assets/temp_product/'. $tempId.'_'.$memberId.'_'.$date.'/';
                        $pathDirectory = './assets/product/'. $productId.'_'.$memberId.'_'.$date.'/';

                        foreach ($images as $key => $image) {  
                            $imageName = str_replace($tempId, $productId, $image); 
                            $imagePath = $pathDirectory.$imageName;
                            $fileType = strtolower(end(explode('.', $image)));
                            $isPrimary = $key === 0;
                            $productImage = $this->productUploadManager
                                                 ->addProductImage($imagePath, $fileType, $product, $isPrimary);
                        }

                        foreach ($attributes as $key => $attr) {
                            $headVal = $this->stringUtility->removeNonUTF(trim($key));
                            $attrHead = $this->productUploadManager
                                             ->addProductAttribute($headVal, $product);

                            foreach ($attr as $value) { 
                                $attrValue = $this->stringUtility->removeNonUTF(trim($value['value'])); 
                                $price = isset($value['price']) ? trim(str_replace(',', '', $value['price'])) : 0;
                                $image = isset($value['image']) ? $value['image'] : "";
                                $imageId = 0;
                                if($image !== ""){ 
                                    $imageName = str_replace($tempId, $productId, $image);
                                    $imagePath = $pathDirectory.$imageName;
                                    $fileType = end(explode('.', $image));
                                    $attrImage = $this->productUploadManager
                                                      ->addProductImage($imagePath, $fileType, $product, false);
                                    $imageId = $attrImage->getIdProductImage(); 
                                    $images[] = $image;
                                }

                                $this->productUploadManager
                                     ->addProductAttributeDetail($attrValue, $price, $attrHead, $imageId); 
                            } 
                        }
                        $productCombination = $this->productUploadManager->addNewCombination($product, $quantity);

                        if($isFreeShippingNationwide){
                            $this->productUploadManager->addShippingInfo($product, 
                                                                         $productCombination->getIdProductItem(),
                                                                         EsLocationLookup::PHILIPPINES_LOCATION_ID,
                                                                         0);
                        }
                        else{
                            foreach( $shippingInfo as $info ){
                                $price = trim(str_replace(',', '', $info['price']));
                                $mustBreak = false;
                                if($info['location_id'] === EsLocationLookup::PHILIPPINES_LOCATION_ID){
                                    $price = 0;
                                    $mustBreak = true;
                                }
                                $this->productUploadManager->addShippingInfo($product, 
                                                                             $productCombination->getIdProductItem(),
                                                                             trim($info['location_id']),
                                                                             $price);
                                if($mustBreak){
                                    break;
                                }
                            }
                        }

                        if(count($images) > 0){ 
                            $this->serviceContainer["assets_uploader"]
                                 ->uploadImageDirectory($tempDirectory, $pathDirectory, $productId, $images);
                        }

                        $this->member->setTempId("");
                        $this->em->flush();
                        $isSuccess = true;
                    }
                }
                else{
                    $errorMessage = $validate['message'];
                } 
            }
            else{
                $errorMessage = "Invalid Request. Upload token did not match.";
            }
        } 
        else{
            $errorMessage = "Please verify your email address.";
        }

        $returnArray = [
            'isSuccess' => $isSuccess,
            'message' => $errorMessage,
        ];

        echo json_encode($returnArray, JSON_PRETTY_PRINT);
    }
}

