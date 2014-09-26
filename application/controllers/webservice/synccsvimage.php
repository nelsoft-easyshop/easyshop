<?php 

use EasyShop\Entities\EsProductImage;
use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsMember;

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
    public function doUpload($files) {
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
    public function checkIfImagesExist($checkImagesId)
    {

        $errorSummary = array();
        foreach($checkImagesId["product"] as $ids)
        {
            $values = $this->EsProductImagesRepository->getDefaultImage($ids);            
            
            $images =  strtolower(str_replace("assets/product/", "", $values->getProductImagePath()));

            $path = "./assets/admin/$images";

            if(file_exists($path)) {

                continue;
            }
            else {
                    $errorSummary[] = $images;
                    $result =  $this->EsProductRepository->deleteProductFromAdmin($ids);
            }
        }
        if(!empty($errorSummary)) {
            $jsonp = "jsonCallback({'sites':[{'success': '".ucfirst(implode(",",$errorSummary))." does not exist in the image folder"."',},]});";
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
    public function syncImages($imagesId)
    {
      
        foreach($imagesId["product"] as $ids)
        {

            $values = $this->EsProductImagesRepository->getDefaultImage($ids);            
            
            $images =  strtolower(str_replace("assets/product/", "", $values->getProductImagePath()));

            $path = "./assets/admin/$images";

            $date = date("Ymd");
            $imageId =  $values->getIdProductImage();
            $productId = $values->getProduct()->getIdProduct();
            $memberId =  $values->getProduct()->getMember()->getIdMember();

            $filename = $productId.'_'.$memberId.'_'.$date;
            $newfilename = $productId.'_'.$memberId.'_'.$date.".".$values->getProductImageType();
            $imageDirectory = "./assets/product/$filename/".$newfilename;
            $tempDirectory = "./assets/product/".$filename."/";                

            mkdir($tempDirectory.'categoryview/', 0777, true);
            mkdir($tempDirectory.'small/', 0777, true);
            mkdir($tempDirectory.'thumbnail/', 0777, true);
            mkdir($tempDirectory.'other/', 0777, true);      
                     
            if(copy($path, $imageDirectory)){

                $this->imageresize($imageDirectory, $tempDirectory."small",$this->img_dimension["small"]);
                $this->imageresize($imageDirectory, $tempDirectory."categoryview",$this->img_dimension["categoryview"]);
                $this->imageresize($imageDirectory, $tempDirectory."thumbnail",$this->img_dimension["thumbnail"]);
                $this->imageresize($imageDirectory, $tempDirectory,$this->img_dimension["usersize"]);
                $this->EsProductImagesRepository->renameImagesFromAdmin( $imageDirectory, $productId);                    
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



