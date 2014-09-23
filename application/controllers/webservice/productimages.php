<?php 

use EasyShop\Entities\EsProductImage;

class ProductImages extends MY_Controller 
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
        $this->em = $this->serviceContainer['entity_manager'];
    }

    /**
     * Passess the posted data to upload_files method
     *
     */ 
    public function index()
    {
        $result = $this->upload_files( $_FILES['image'],$this->input->get("filenames"),$this->input->get("time"));
    }

    /**
     * Performs multiple uploads
     * @param FILE $files
     * @param string $filenames
     * @param string $times
     * @return array
     */ 
    private function upload_files($files, $filenames, $times)
    {

        $config = array(
            'upload_path'   => "assets/product",
            'allowed_types' => 'jpg|gif|png',
            'overwrite'     => 1,                       
        );

        $this->load->library('upload', $config);

        $images = array();
        
        foreach ($files['name'] as $key => $image) {
            $EsProductImagesRepository = $this->em->getRepository('EasyShop\Entities\EsProductImage');

            $_FILES['image[]']['name']= $files['name'][$key];
            $_FILES['image[]']['type']= $files['type'][$key];
            $_FILES['image[]']['tmp_name']= $files['tmp_name'][$key];
            $_FILES['image[]']['error']= $files['error'][$key];
            $_FILES['image[]']['size']= $files['size'][$key];

            $values = $EsProductImagesRepository->getDefaultImage($filenames[$key]);

            if(strtolower($files['name'][$key]) == strtolower(str_replace("assets/product/", "", $values->getProductImagePath()))) {
                $images[] = $times[$key];
                $config['file_name'] = $times[$key].".".$values->getProductImageType();
                $this->upload->initialize($config);
                $path = "assets/product/".$config['file_name'];
                if (!$this->upload->do_upload('image[]')) {
                    return $this->upload->display_errors();
                }
                else {
                    $result = $EsProductImagesRepository->renameImagesFromAdmin( $path, $filenames[$key]);
                }
            }
        }
        return $images;
    }
}



