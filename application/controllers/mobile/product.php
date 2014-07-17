<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product extends MY_Controller {


    function __construct() {
        parent::__construct();
        $this->load->helper('htmlpurifier');

        //Loading Models
        $this->load->model('product_model'); 

        //Making response json type
        header('Content-type: application/json');
    }

    public function item($slug = '')
    {
        $productRow = $this->product_model->getProductBySlug($slug);  
        $id = $productRow['id_product'];
        $productImages = $this->product_model->getProductImages($id);
        $productAttributes = $this->product_model->getProductAttributes($id, 'NAME');
        $productAttributes = $this->product_model->implodeAttributesByName($productAttributes);
        $productQuantity = $this->product_model->getProductQuantity($id, false, false, $productRow['start_promo']);
        $rating = $this->product_model->getVendorRating($productRow['sellerid']);
 
        $productDetails = array(
            'name' => $productRow['product_name'],
            'description' => $productRow['description'],
            'brand' => $productRow['brand_name'],
            'condition' => $productRow['condition'],
            'discount' => $productRow['discount'],
            'basePrice' => $productRow['price']

            ); 

        $sellerRating = array();
        $sellerRating['rateCount'] = $rating['rate_count'];  
        $sellerRating['rateDescription'][$this->lang->line('rating')[0]] = $rating['rating1'];
        $sellerRating['rateDescription'][$this->lang->line('rating')[1]] = $rating['rating2'];
        $sellerRating['rateDescription'][$this->lang->line('rating')[2]] = $rating['rating3'];  
        $sellerDetails = array(
            'sellerName' => $productRow['sellerusername'],
            'sellerRating' => $sellerRating
            );

        $paymentMethodArray = $this->config->item('Promo')[0]['payment_method'];
        if(intval($productRow['is_promote']) === 1){
            $bannerfile = $this->config->item('Promo')[$productRow['promo_type']]['banner'];
            if(strlen(trim($bannerfile)) > 0){
                $banner_view = $this->load->view('templates/promo_banners/'.$bannerfile, $productRow, TRUE); 
            }
            $paymentMethodArray = $this->config->item('Promo')[$productRow['promo_type']]['payment_method'];
        }
  
        $data = array( 
            "productDetails" => $productDetails,
            "productImages" => $productImages,
            "sellerDetails" => $sellerDetails,
            "productAttributes" => $productAttributes,
            "paymentMethod" => $paymentMethodArray
            );

        die(json_encode($data,JSON_PRETTY_PRINT));
    }
}

/* End of file product.php */
/* Location: ./application/controllers/product.php */
