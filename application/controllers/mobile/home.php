<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use EasyShop\Entities\EsProductImage as EsProductImage;

class Home extends MY_Controller {

    public $per_page;

    function __construct() {
        parent::__construct(); 
        $this->load->library("xmlmap"); 
        $this->em = $this->serviceContainer['entity_manager'];
        $this->pm = $this->serviceContainer['product_manager'];

        //Making response json type
        header('Content-type: application/json'); 
    }

    public function index()
    {
        $pageContent = $this->xmlmap->getFilename("page/mobile_home_files");

        // banner images
        $bannerImages = [];
        foreach ($pageContent['mainSlide'] as $key => $value) {
            $bannerImages[] = array(
                            'image_path' => $value['value'],
                            'target' => $value['imagemap']['target'],
                        );
        }

        // product sections
        $productSections = [];
        foreach ($pageContent['section'] as $key => $value) {
            $productArray = [];
            // loop products
        
            foreach ($value['boxContent'] as $keyLevel2 => $valueLevel2) { 

                $slug = (isset($valueLevel2['value'])) ? $valueLevel2['value'] : ""; 
                $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                            ->findOneBy(['slug' => $slug]);

                $productName = "";
                $productSlug = "";
                $productBasePrice = "";
                $productFinalPrice = "";
                $productDiscount = "";
                $productImagePath = "";

                if($product){
                    $product = $this->pm->getProductDetails($product->getIdProduct());

                    $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                      ->getDefaultImage($product->getIdProduct());
        
                    $directory = EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
                    $imageFileName = EsProductImage::IMAGE_UNAVAILABLE_FILE;

                    if($productImage != NULL){
                        $directory = $productImage->getDirectory();
                        $imageFileName = $productImage->getFilename();
                    }

                    $productName = $product->getName();
                    $productSlug = $product->getSlug();
                    $productDiscount = $product->getDiscountPercentage();
                    $productBasePrice = $product->getPrice();
                    $productFinalPrice = $product->getFinalPrice();
                    $productImagePath = $directory.$imageFileName;
                }

                $productArray[] = array(
                                    'name' => $productName,
                                    'slug' => $productSlug,
                                    'discount_percentage' => $productDiscount,
                                    'base_price' => $productBasePrice,
                                    'final_price' => $productFinalPrice,
                                    'product_image' => $productImagePath,
                                );
            }

            $productSections[] = array(
                                'title' => $value['name'],
                                'background_color' => $value['bgcolor'],
                                'type' => $value['type'],
                                'products' => $productArray,
                            );
        }

        $display = array(
                    'mainSlider' => $bannerImages,
                    'productSections' => $productSections,
                );

        echo json_encode($display,JSON_PRETTY_PRINT);
    }
}

/* End of file home.php */
/* Location: ./application/controllers/mpobile/home.php */
