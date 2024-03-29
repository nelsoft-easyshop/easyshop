<?php 

use EasyShop\Entities\EsProductImage;
use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsMember;
use EasyShop\Entities\EsAdminImages;
use EasyShop\Entities\EsOptionalAttrdetail;
use EasyShop\Entities\EsOptionalAttrhead;

class SyncCsvImage extends MY_Controller 
{
    /**
     * The entity manager
     *
     */
    private $em;   

    /**
     * The product manager
     *
     */
    private $productManager;

    /**
     * Handles if the request is authenticated
     * @var bool
     */    
    private $isAuthenticated = false;     

    public function __construct()  
    { 
        parent::__construct(); 
        $this->productManager = $this->serviceContainer['product_manager'];
        $this->em = $this->serviceContainer['entity_manager'];
        $this->EsProductImagesRepository = $this->em->getRepository('EasyShop\Entities\EsProductImage');
        $this->EsProductRepository = $this->em->getRepository('EasyShop\Entities\EsProduct');            
        $this->EsAdminImagesRepository = $this->em->getRepository('EasyShop\Entities\EsAdminImages');
        $this->authenticateRequest = $this->serviceContainer['webservice_manager'];

        if($this->input->get()) {
            $this->isAuthenticated = $this->authenticateRequest->authenticate($this->input->get(), 
                                                                              $this->input->get('hash'),
                                                                              true);
        }

        if(!$this->isAuthenticated) {
            exit("Your request is not authenticated");
        }          
    }

    /**
     * Evaluates the posted data 
     * @return JSON
     */ 
    public function index()
    {
        if($this->input->get("product")){
            $result = $this->checkIfImagesExist($this->input->get());               
            if(!is_array($result)){
                return $this->output
                            ->set_content_type('application/json')
                            ->set_output($result);    
            }
            else {
                return $this->syncImages($result);
            }
        }
        else {
            $this->doUpload($this->input->get());  
        }
    }

    /**
     * Upload images inside admin folder
     */ 
    private function doUpload($files) {

        $this->upload->initialize(array(
            "upload_path"   => "assets/admin",
            "allowed_types" => "jpg|jpeg|png|gif",
            "overwrite" => "true",
            "remove_spaces" => "true"

        ));
        if(!$this->upload->do_multi_upload("image")){
            $jsonpReturn = "jsonCallback({'sites':[{'success': '".$this->upload->display_errors()."',},]});";
        }   
        else {
            foreach($_FILES['image']['name'] as $names){
                $this->EsAdminImagesRepository->insertImage($names);
            }            
            $jsonpReturn = "jsonCallback({'sites':[{'success': '"."success"."',},]});";

        }   

       return $this->output
                   ->set_content_type('application/json')
                   ->set_output($jsonpReturn);  
    }

    /**
     * Checks if all of the listed images in the csv exists in the admin folder
     * @param int $imagesId
     * @return JSONP
     */ 
    private function checkIfImagesExist($checkImagesId)
    {
        $flag = 0;
        $errorSummary = array();
        foreach($checkImagesId["product"] as $ids)
        {
            $imagesValues = $this->EsProductImagesRepository->getProductImages($ids);            
            foreach($imagesValues as $values) {
                if($values){
                    $images =  strtolower(str_replace("assets/product/", "", $values->getProductImagePath()));
                }

                $path = "./assets/admin/$images";

                if(file_exists($path)) {
                    continue;
                }
                else {
                    if(!in_array($images,$errorSummary)){
                        $errorSummary[] = $images;
                        $flag = 1;
                    }
                } 
            }
            if($flag === 1){
                $this->deleteCSVProducts($ids);
            }
        }
        if(!empty($errorSummary)) {
            return  "jsonCallback({'sites':[{'success': '"."Kindly upload the following image/s: <br/>".ucfirst(implode("<br/>",$errorSummary)).""."',},]});";
        }
        else {
            return $checkImagesId;
        }
    }

    /**
     * Deletes products upload from the csv files that have errors
     * @param int $ids
     */ 
    private function deleteCSVProducts($ids)
    {
        $this->em->getRepository('EasyShop\Entities\EsOptionalAttrdetail')
                                    ->deleteAttrDetailByProductId($ids);

        $this->em->getRepository('EasyShop\Entities\EsOptionalAttrhead')
                                    ->deleteAttrHeadById($ids);                                    

        $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                    ->deleteImageByProductId($ids);   

        $productItem = $this->em->getRepository('EasyShop\Entities\EsProductItem')
                                ->findOneBy(['product' => $ids]);                                    

        $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                                    ->deleteShippingDetailByProductItem($productItem);         

        $this->em->getRepository('EasyShop\Entities\EsProductShippingHead')
                                    ->deleteShippingHeadByProductId($ids);  

        $this->em->getRepository('EasyShop\Entities\EsProductItem')
                                    ->deleteProductItemByProductID($ids);

        $this->EsProductRepository->deleteProductFromAdmin($ids);                                                                                                                                                                     
    }

    /**
     * Creates directories and resizes images inside assets/product
     * @param int $productIds
     * @return JSONP
     */ 
    private function syncImages($productIds)
    {
        //Get images config dimensions
        $this->config->load('image_dimensions', TRUE);
        $this->config->load("image_path");
        $imageUtility = $this->serviceContainer['image_utility'];    
        $imageDimensions = $this->config->config['image_dimensions'];
        $date = date("Ymd");
        $gisTime = date("Gis");
        foreach($productIds["product"] as $ids)
        {
            $imagesValues = $this->EsProductImagesRepository->getProductImages($ids);            

            $productAttr = $this->em
                                ->getRepository('EasyShop\Entities\EsProduct')
                                ->getAttributesByProductIds($ids);

            $hasAttribute = count($productAttr) > 0;
            foreach($imagesValues as $key => $values) {

                $images =  strtolower(str_replace("assets/product/", "", $values->getProductImagePath()));
                $path = "./assets/admin/$images";
                $productId = $values->getProduct()->getIdProduct();
                $memberId =  $values->getProduct()->getMember()->getIdMember();
                $productImageId = $values->getIdProductImage();
 
                $directoryName = $productId.'_'.$memberId.'_'.$date;
                $filename = $productId.'_'.$memberId.'_'.$date.$gisTime.$key.".".$values->getProductImageType();
                $fullImagePath = "./".$this->config->item('product_img_directory').$directoryName."/".$filename;
                $productDirectory = "./".$this->config->item('product_img_directory').$directoryName."/"; 

                if(!file_exists($productDirectory)){
                    $newSlug = $this->productManager->generateSlug($values->getProduct()->getName());
                    $values->getProduct()->setSlug($newSlug);
                    mkdir($productDirectory.'categoryview/', 0777, true);
                    mkdir($productDirectory.'small/', 0777, true);
                    mkdir($productDirectory.'thumbnail/', 0777, true);
                    mkdir($productDirectory.'other/', 0777, true);
                    if($hasAttribute) {
                        mkdir($productDirectory.'other/categoryview', 0777, true); 
                        mkdir($productDirectory.'other/small', 0777, true); 
                        mkdir($productDirectory.'other/thumbnail', 0777, true); 
                        $this->doCopyForOtherDir($productAttr, 
                                                 $gisTime,
                                                 $date, 
                                                 $productId, 
                                                 $memberId, 
                                                 $productImageId, 
                                                 $directoryName, 
                                                 $imageDimensions);
                    }

                }

                if(file_exists($path) && copy($path, $fullImagePath)) {
                    $productImageObject = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                                    ->findBy([
                                                        'product' => $productId, 
                                                        'idProductImage' => $productImageId
                                                    ]);
                    foreach ($productImageObject as $image ) {
                        $image->setProductImagePath($fullImagePath);
                    }
                    $imageUtility->imageResize($fullImagePath, $productDirectory."small",$imageDimensions["productImagesSizes"]["small"]);
                    $imageUtility->imageResize($fullImagePath, $productDirectory."categoryview",$imageDimensions["productImagesSizes"]["categoryview"]);
                    $imageUtility->imageResize($fullImagePath, $productDirectory."thumbnail",$imageDimensions["productImagesSizes"]["thumbnail"]);
                    $imageUtility->imageResize($fullImagePath, $productDirectory,$imageDimensions["productImagesSizes"]["usersize"]);
                }

                $this->em->flush();

            } 
        }

        $jsonp = "jsonCallback({'sites':[{'success': 'success',},]});";
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output($jsonp);         
    }

    /**
     * Creates directories for product attributes if exists
     * @param array $productAttr
     * @param date $gisTime
     * @param date $date
     * @param int $productId
     * @param int $memberId
     * @param int $productImageId
     * @param string $filename
     * @param array $imageDimensions
     */ 
    private function doCopyForOtherDir($productAttr, 
                                       $gisTime, 
                                       $date, 
                                       $productId, 
                                       $memberId, 
                                       $productImageId, 
                                       $filename, 
                                       $imageDimensions)
    {  
  
        $this->config->load("image_path");
        $imageUtility = $this->serviceContainer['image_utility'];            
        foreach ($productAttr as $key => $attr) {
            $values = $this->em
                           ->getRepository('EasyShop\Entities\EsProductImage')
                           ->find($attr["image_id"]);

            if(!$values) {
                continue;
            }

            $images =  strtolower(str_replace("assets/product/", "", $values->getProductImagePath()));
            $path = "./assets/admin/$images";

            $attributeFileName = $productId.'_'.$memberId.'_'.$date.$gisTime.$key."o.".$values->getProductImageType();
            $fullImagePath = "./".$this->config->item('product_img_directory').$filename."/other/".$attributeFileName;
            $productDirectory = "./".$this->config->item('product_img_directory').$filename."/other/"; 
            if(file_exists($path) && copy($path, $fullImagePath)){
                $productObject = $this->em->find('EasyShop\Entities\EsProduct', $productId);

                $values->setProductImagePath($fullImagePath);
                $values->setProductImageType($values->getProductImageType());
                $values->setProduct($productObject);

                $productAttrDetail = $this->em
                                          ->getRepository('EasyShop\Entities\EsOptionalAttrdetail')
                                          ->findOneBy(['productImgId' => $attr["image_id"]]);
                $productAttrDetail->setProductImgId($values->getIdProductImage());
                $imageUtility->imageResize($fullImagePath, $productDirectory."small", $imageDimensions["productImagesSizes"]["small"]);
                $imageUtility->imageResize($fullImagePath, $productDirectory."categoryview", $imageDimensions["productImagesSizes"]["categoryview"]);
                $imageUtility->imageResize($fullImagePath, $productDirectory."thumbnail", $imageDimensions["productImagesSizes"]["thumbnail"]);
                $imageUtility->imageResize($fullImagePath, $productDirectory, $imageDimensions["productImagesSizes"]["usersize"]);        
            }
        }
        $this->em->flush();
    }

    /**
     * Handles deleting of images upload by the administrator
     * @return JSONP
     */
    public function deleteImage()
    {
        if($this->EsAdminImagesRepository->deleteImage($this->input->get("imageId"))) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output("jsonCallback({'sites':[{'success': 'success',},]});");
        }
    }



}



