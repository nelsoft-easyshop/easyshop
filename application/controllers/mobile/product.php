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
            'sellerContactNumber' => $seller['contactno'],
            'sellerEmail ' => $seller['email']
            );

        $paymentMethodArray = $this->config->item('Promo')[0]['payment_method'];
        if(intval($productRow['is_promote']) === 1){
            $bannerfile = $this->config->item('Promo')[$productRow['promo_type']]['banner'];
            if(strlen(trim($bannerfile)) > 0){
                $banner_view = $this->load->view('templates/promo_banners/'.$bannerfile, $productRow, TRUE); 
            }
            $paymentMethodArray = $this->config->item('Promo')[$productRow['promo_type']]['payment_method'];
        }
 
        $data = array ( 
            'attr' => $this->product_model->getPrdShippingAttr($id), 
            'shipping_summary' => $this->product_model->getShippingSummary($id)  
            );

        $jsonFdata = array();
        if($data['shipping_summary']['has_shippingsummary']){
            if($data['attr']['has_attr'] === 1){
                $i = 0;
                foreach($data['attr']['attributes'] as $attrk=>$attrarr){
                    if( isset($data['shipping_summary'][$attrk]) ){ 
                        foreach($data['shipping_summary'][$attrk] as $lockey=>$price){
                            $jsonFdata[$attrk][$data['shipping_summary']['location'][$lockey]] = $price;
                        }
                        $i++;
                    }
                }
            } else { 
                foreach($data['shipping_summary'][$data['attr']['product_item_id']] as $lockey=>$price){
                    $jsonFdata[$data['attr']['product_item_id']][$data['shipping_summary']['location'][$lockey]] = $price;
                }
            }
        }

    foreach ($jsonFdata as $key => $value) {
        $productQuantity[$key]['location'] = $jsonFdata[$key];
    }
    
        $data = array( 
            "productDetails" => $productDetails,
            "productImages" => $productImages,
            "sellerDetails" => $sellerDetails,
            "productCombinationAttributes" => $productCombinationAttributes,
            "productSpecification" => $productSpecification,
            "paymentMethod" => $paymentMethodArray,
            "productCombinatiobDetails" => $productQuantity
            );
 
        die(json_encode($data,JSON_PRETTY_PRINT));
    }
}

/* End of file product.php */
/* Location: ./application/controllers/product.php */
