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

    public function __construct()  
    { 
        parent::__construct(); 

        $this->productManager = $this->serviceContainer['product_manager'];

        $this->em = $this->serviceContainer['entity_manager'];
      
        $this->EsProductImagesRepository = $this->em->getRepository('EasyShop\Entities\EsProductImage');
        $this->EsProductRepository = $this->em->getRepository('EasyShop\Entities\EsProduct');            
        $this->EsAdminImagesRepository = $this->em->getRepository('EasyShop\Entities\EsAdminImages');            

    }

    /**
     * Passess the posted data to syncImages method
     */ 
    public function index()
    {


        if($this->input->get("product")){
            $this->checkIfImagesExist($this->input->get());               
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
            if($flag == 1){
                $result =  $this->EsProductRepository->deleteProductFromAdmin($ids);                            
            }
        }
        if(!empty($errorSummary)) {
            $jsonp = "jsonCallback({'sites':[{'success': '"."Please upload ".ucfirst(implode(",",$errorSummary))." before proceeding uploading product info"."',},]});";
            return $this->output
                ->set_content_type('application/json')
                ->set_output($jsonp);        
        }
        else {
            $this->syncImages($checkImagesId);
        }
    }

    /**
     * Creates directories and resizes images inside assets/product
     * @param int $imagesId
     * @return JSONP
     */ 
    private function syncImages($imagesId)
    {
        //Get images config dimensions
        $this->config->load('image_dimensions', TRUE);
        $imageDimensions = $this->config->config['image_dimensions'];
        
        foreach($imagesId["product"] as $ids)
        {
            $imagesValues = $this->EsProductImagesRepository->getProductImages($ids);            

            foreach($imagesValues as $values) {

                $images =  strtolower(str_replace("assets/product/", "", $values->getProductImagePath()));
                $path = "./assets/admin/$images";

                $date = date("Ymd");
                $productId = $values->getProduct()->getIdProduct();
                $memberId =  $values->getProduct()->getMember()->getIdMember();
                $productImageId = $values->getIdProductImage();

                //Generate slug using the 'createSlug' method under product_model
                $productObject = $this->em->getRepository('EasyShop\Entities\EsProduct')
                            ->findOneBy(['idProduct' => $productId]);
 
                $filename = $productId.'_'.$memberId.'_'.$date;
                $newfilename = $productId.'_'.$memberId.'_'.$date.".".$values->getProductImageType();
                $imageDirectory = "./assets/product/$filename/".$newfilename;
                $tempDirectory = "./assets/product/".$filename."/"; 

                $attrImage = $this->em->getRepository('EasyShop\Entities\EsOptionalAttrdetail')
                                            ->findBy(['productImgId' => $productImageId]); 
                if(!file_exists($tempDirectory)){
                    $newSlug = $this->productManager->generateSlugForCSVProducts($productObject->getSlug());
                    mkdir($tempDirectory.'categoryview/', 0777, true);
                    mkdir($tempDirectory.'small/', 0777, true);
                    mkdir($tempDirectory.'thumbnail/', 0777, true);
                    mkdir($tempDirectory.'other/', 0777, true);
                    if($attrImage) {
                       mkdir($tempDirectory.'other/categoryview', 0777, true); 
                       mkdir($tempDirectory.'other/small', 0777, true); 
                       mkdir($tempDirectory.'other/thumbnail', 0777, true); 
                    }
                }
    
                if(copy($path, $imageDirectory)){
                    if($attrImage) {
                        $this->doCopyForOtherDir($attrImage, $date, $productId, $memberId, $productImageId, $filename, $imageDimensions);
                    }
                    $this->productManager->imageresize($imageDirectory, $tempDirectory."small",$imageDimensions["small"]);
                    $this->productManager->imageresize($imageDirectory, $tempDirectory."categoryview",$imageDimensions["categoryview"]);
                    $this->productManager->imageresize($imageDirectory, $tempDirectory."thumbnail",$imageDimensions["thumbnail"]);
                    $this->productManager->imageresize($imageDirectory, $tempDirectory,$imageDimensions["usersize"]);
                    $productObject->setSlug($newSlug);
                    $productImageObject = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                                    ->findBy(array('product' => $productId, 'idProductImage' => $productImageId));
                    foreach ($productImageObject as $image ) {
                        $image->setProductImagePath($imageDirectory);
                    }
                    $this->em->flush();

                }
            } 
        }

        $jsonp = "jsonCallback({'sites':[{'success': 'success',},]});";
        return $this->output
            ->set_content_type('application/json')
            ->set_output($jsonp);         
    }

    /**
     * Creates directories for product attributes if exists
     * @param array $attrImage
     * @param date $date
     * @param int $productId
     * @param int $memberId
     * @param int $productImageId
     * @param string $filename
     * @param array $imageDimensions
     */ 
    private function doCopyForOtherDir($attrImage, $date, $productId, $memberId, $productImageId, $filename, $imageDimensions)
    {
        $attrImage = $this->em->getRepository('EasyShop\Entities\EsOptionalAttrdetail')
                ->findBy(['productImgId' => $productImageId]);               

        foreach ($attrImage as $image) {

            $values = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                ->findOneBy(['idProductImage' => $image->getProductImgId()]);     

            $images =  strtolower(str_replace("assets/product/", "", $values->getProductImagePath()));
            $path = "./assets/admin/$images";

            $newfilename = $productId.'_'.$memberId.'_'.$date.".".$values->getProductImageType();
            $imageDirectory = "./assets/product/$filename/other/".$newfilename;
            $tempDirectory = "./assets/product/".$filename."/other/"; 
            if(copy($path, $imageDirectory)){
                $this->productManager->imageresize($imageDirectory, $tempDirectory."small",$imageDimensions["small"]);
                $this->productManager->imageresize($imageDirectory, $tempDirectory."categoryview",$imageDimensions["categoryview"]);
                $this->productManager->imageresize($imageDirectory, $tempDirectory."thumbnail",$imageDimensions["thumbnail"]);
                $this->productManager->imageresize($imageDirectory, $tempDirectory,$imageDimensions["usersize"]);
            }
        }                    
        
    }



}



