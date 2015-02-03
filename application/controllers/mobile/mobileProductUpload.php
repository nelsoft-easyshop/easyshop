<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
use EasyShop\Upload\AssetsUploader as AssetsUploader;

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
        $temporaryId = $this->productUploadManager->requestUploadToken($this->member->getIdMember());
        $date = date("Ymd");
        $memberId = $this->member->getIdMember(); 
        $tempDirectory =  'assets/temp_product/'. $temporaryId.'_'.$memberId.'_'.$date.'/'; 

        mkdir($tempDirectory, 0777, true);
        mkdir($tempDirectory.'categoryview/', 0777, true);
        mkdir($tempDirectory.'small/', 0777, true);
        mkdir($tempDirectory.'thumbnail/', 0777, true);
        mkdir($tempDirectory.'other/', 0777, true);
        mkdir($tempDirectory.'other/categoryview/', 0777, true);
        mkdir($tempDirectory.'other/small/', 0777, true);
        mkdir($tempDirectory.'other/thumbnail/', 0777, true);

        echo json_encode(['upload_token' => $temporaryId]);
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

        $currentImageCount = (int) trim($this->input->post('count')); 
        $tempId = (string) trim($this->input->post('upload_token'));

        if(isset($_FILES['userfile'])){
            $pathDirectory = 'assets/temp_product/sample/';
            $allowedMime = explode('|', AssetsUploader::ALLOWABLE_IMAGE_MIME_TYPES);
            $fileExtension = strtolower(end(explode('.', $_FILES['userfile']['name'][0]))); 
 
            if(in_array(strtolower($fileExtension),$allowedMime)
               && $_FILES['userfile']['size'][0] < AssetsUploader::MAX_ALLOWABLE_SIZE_KB * 1024
               && (bool) $_FILES['userfile']['error'][0] === false
               && $tempId === (string) $this->member->getTempId()){ 
                
                $memberId = $this->member->getIdMember();
                $date = date("Ymd"); 
                $tempDirectory =  'assets/temp_product/'. $tempId.'_'.$memberId.'_'.$date.'/'; 
                 
                foreach ($_FILES['userfile']['name'] as $key => $value) {
                    if($key !== 0){
                        unset($_FILES['userfile']['name'][$key]);
                        unset($_FILES['userfile']['type'][$key]);
                        unset($_FILES['userfile']['tmp_name'][$key]);
                        unset($_FILES['userfile']['error'][$key]);
                        unset($_FILES['userfile']['size'][$key]);
                    }
                    else{
                        $fileNames[] = $tempId.'_'.$memberId.'_'.$fullDate.$currentImageCount.'.'.$fileExtension;
                        $currentImageCount++;
                    }
                }

                $imageUpload = $assetsUploader->uploadProductImage($fileNames, $tempDirectory);
                $imageUpload['pictureCount'] = $currentImageCount;
            }
            else{ 
                $imageUpload = [
                    'isSuccess' => false,
                    'fileNames' => [],
                    'errorMessage' => "Please select valid image type. Allowed type: ".AssetsUploader::ALLOWABLE_IMAGE_MIME_TYPES." Allowed max size: ".AssetsUploader::MAX_ALLOWABLE_SIZE_KB." kb",
                    'pictureCount' => $currentImageCount
                ];
            }
        }
        else{
            $imageUpload = [
                'isSuccess' => false,
                'fileNames' => [],
                'errorMessage' => 'No image selected',
                'pictureCount' => $currentImageCount
            ];
        }
 
        echo json_encode($imageUpload, JSON_PRETTY_PRINT);
    }

    public function processUpload()
    { 
        $isSuccess = false;
        $errorMessage = "";

        $tempId = (string) trim($this->input->post('upload_token'));
        
        if($tempId === (string) $this->member->getTempId()){
            $categoryId = trim($this->input->post('category'));
            $productTitle = trim($this->input->post('title'));
            $productDescription = trim($this->input->post('description'));
            $price = trim($this->input->post('price'));
            $discount = trim($this->input->post('discount'));
            $isCashOnDelivery = (bool) trim($this->input->post('isCod'));
            $quantity = trim($this->input->post('quantity'));
            $images = json_decode(trim($this->input->post('images')));
            $locationId = trim($this->input->post('shipping_info'));

            
        }
        else{
            $errorMessage = "Invalid Request. Upload token did not match. "
        }
    }
}