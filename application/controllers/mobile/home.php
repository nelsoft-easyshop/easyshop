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
                            'name' => '0',

                            'image' => $value['value'],
                            'target' => base_url().$value['imagemap']['target'],
                            'actionType' => $value['actionType'],

                        );
        }
        $sectionImages = array(
                        'name' => '',
                        'bgcolor' => '',
                        'type' => 'promo',
                        'data' => $bannerImages,
                    ); 

        $productSections[] = $sectionImages; 
        // product sections 
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
                $target = "";

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
                    $productBasePrice = floatval($product->getPrice());
                    $productFinalPrice = floatval($product->getFinalPrice());
                    $productImagePath = $directory.$imageFileName;
                    $target = base_url().'mobile/product/item/'.$productSlug;
                }

                $productArray[] = array(
                                    'name' => $productName,
                                    'slug' => $productSlug,
                                    'discount_percentage' => $productDiscount,
                                    'base_price' => $productBasePrice,
                                    'final_price' => $productFinalPrice,
                                    'image' => $productImagePath,
                                    'actionType' => $valueLevel2['actionType'],
                                    'target' => $target,
                                );
            }

            $categoryObject = $this->em->getRepository('EasyShop\Entities\EsCat')
                                ->findOneBy(['slug' => $value['name']]);

            $categoryName = "";
            $categoryIcon = base_url()."assets/images/img_icon_bag2.png";
            if($categoryObject){
                $categoryName = $categoryObject->getName();
                $categorySlug = $categoryObject->getSlug();

                $categoryIconObject = $this->em->getRepository('EasyShop\Entities\EsCatImg')
                                ->findOneBy(['idCat' => $categoryObject->getIdCat()]);

                if($categoryIconObject){
                    $categoryIcon = base_url().'assets/'.$categoryIconObject->getPath();
                }

                $productArray[] = array(
                                        'name' => 0,
                                        'slug' => 0,
                                        'discount_percentage' => 0,
                                        'base_price' => 0,
                                        'final_price' => 0,
                                        'image' => 0,
                                        'actionType' => 'show product list',
                                        'target' => base_url().'mobile/category/getCategoriesProduct?slug='.$categorySlug,
                                    );
            }

            $productSections[] = array(
                                'name' => $categoryName,
                                'bgcolor' => $value['bgcolor'],
                                'type' => $value['type'],
                                'icon' => $categoryIcon,
                                'data' => $productArray,
                            );
        }

        $display = array( 
                    'section' => $productSections,
                );

        echo json_encode($display,JSON_PRETTY_PRINT);
    }
}

/* End of file home.php */
/* Location: ./application/controllers/mpobile/home.php */
