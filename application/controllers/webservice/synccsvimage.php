<?php 

use EasyShop\Entities\EsProductImage;
use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsMember;
use EasyShop\Entities\EsAdminImages;

class SyncCsvImage extends MY_Controller 
{
    
    /**
     * The entity manager
     *
     */
    private $em;   

    public function __construct()  
    { 
        parent::__construct(); 
        // Loading entity manager 
        $this->load->library('image_lib');        
        $this->em = $this->serviceContainer['entity_manager'];
        $this->img_dimension['usersize'] = array(1024,768);
        $this->img_dimension['small'] = array(400,535);
        $this->img_dimension['categoryview'] = array(220,200);
        $this->img_dimension['thumbnail'] = array(60,80);       
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
                $this->EsAdminImagesRepository->insertImages($names);
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
        $this->load->model('product_model');
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
                $slug =  $productObject->getSlug();
                $newSlug = $this->product_model->createSlug($slug);

                $filename = $productId.'_'.$memberId.'_'.$date;
                $newfilename = $productId.'_'.$memberId.'_'.$date.".".$values->getProductImageType();
                $imageDirectory = "./assets/product/$filename/".$newfilename;
                $tempDirectory = "./assets/product/".$filename."/";                

                if(!file_exists($tempDirectory)){
                    mkdir($tempDirectory.'categoryview/', 0777, true);
                    mkdir($tempDirectory.'small/', 0777, true);
                    mkdir($tempDirectory.'thumbnail/', 0777, true);
                    mkdir($tempDirectory.'other/', 0777, true);                  
                }
    
                if(copy($path, $imageDirectory)){

                    $this->imageresize($imageDirectory, $tempDirectory."small",$this->img_dimension["small"]);
                    $this->imageresize($imageDirectory, $tempDirectory."categoryview",$this->img_dimension["categoryview"]);
                    $this->imageresize($imageDirectory, $tempDirectory."thumbnail",$this->img_dimension["thumbnail"]);
                    $this->imageresize($imageDirectory, $tempDirectory,$this->img_dimension["usersize"]);
                    $this->EsProductImagesRepository->renameImagesAndSlugsFromAdmin($newSlug, $imageDirectory, $productId, $productImageId);                    
                }
            } 
        }

        $jsonp = "jsonCallback({'sites':[{'success': 'success',},]});";
        return $this->output
        ->set_content_type('application/json')
        ->set_output($jsonp);         

    }

    /**
     * Creates directories, checks if the passed image name exists in the admin folder
     * @param int $imagesId
     * @return JSONP
     */ 
    private function imageresize($imageDirectory, $newDirectory, $dimension)
    {
        $config['image_library'] = 'GD2';
        $config['source_image'] = $imageDirectory;
        $config['maintain_ratio'] = true;
        $config['quality'] = '85%';
        $config['new_image'] = $newDirectory;
        $config['width'] = $dimension[0];
        $config['height'] = $dimension[1]; 

        $this->image_lib->initialize($config); 
        $this->image_lib->resize();
        $this->image_lib->clear();        
    }

}



