<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product extends MY_Controller {


    function __construct() {
        parent::__construct();
        $this->load->helper('htmlpurifier');

        //Loading Models
        $this->load->model('product_model'); 
        $this->load->model('memberpage_model'); 

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
        $seller = $this->memberpage_model->get_member_by_id($productRow['sellerid']); 
        $productSpecification = array();
        $productCombinationAttributes = array();

        foreach ($productAttributes as $key => $productOption) {

            if(count($productOption)>1){
                $productCombinationAttributes[$key] = $productOption;
            }
            elseif((count($productOption) === 1)&&(($productOption[0]['datatype'] === '5'))||($productOption[0]['type'] === 'option')){
                $productCombinationAttributes[$key] = $productOption;
                $productSpecification[$key] = $productOption;
            }
            else{
                $productSpecification[$key] = $productOption;
            } 
        }

 
        foreach ($productQuantity as $key => $value) {
            unset($productQuantity[$key]['attr_lookuplist_item_id']);
            unset($productQuantity[$key]['attr_name']);
        } 

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
            'sellerRating' => $sellerRating,
            'sellerContactNumber' => $seller['contactno']
            );

        $paymentMethodArray = $this->config->item('Promo')[0]['payment_method'];
        if(intval($productRow['is_promote']) === 1){
            $bannerfile = $this->config->item('Promo')[$productRow['promo_type']]['banner'];
            if(strlen(trim($bannerfile)) > 0){
                $banner_view = $this->load->view('templates/promo_banners/'.$bannerfile, $productRow, TRUE); 
            }
            $paymentMethodArray = $this->config->item('Promo')[$productRow['promo_type']]['payment_method'];
        }

        $shipment_information =  $this->product_model->getShipmentInformation($id);
        $shiploc = $this->product_model->getLocation(); 
      
        foreach ($shipment_information as $key => $value) {
            $shipment_information[$key]['quantity'] = $productQuantity[$value['product_item_id']]['quantity'];

 

            if($value['location_type'] ==  3){
                $shipment_information[$key]['available_location'] = $value['location'];
            }
            elseif ($value['location_type'] ==  1) {
                $shipment_information[$key]['available_location'][$value['location']] = $shiploc['area'][$shipment_information[$key]['location']];
            }
            else{
               
            }

            
        }

        // echo '<pre>',print_r($shipment_information);exit();
   

        $data = array( 
            "productDetails" => $productDetails,
            "productImages" => $productImages,
            "sellerDetails" => $sellerDetails,
            "productCombinationAttributes" => $productCombinationAttributes,
            "productSpecification" => $productSpecification,
            "paymentMethod" => $paymentMethodArray 
            );
 
        die(json_encode($data,JSON_PRETTY_PRINT));
    }
}

/* End of file product.php */
/* Location: ./application/controllers/product.php */
