<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product extends MY_Controller {


    function __construct()
    {
        parent::__construct();

        //Loading Helpers
        $this->load->helper('htmlpurifier');

        //Loading Models
        $this->load->model('product_model'); 
        $this->load->model('memberpage_model'); 

        //Making response json type
        header('Content-type: application/json');
    }

    /**
     * Retrieve product information based on given slug
     * @param  string $slug
     * @return JSON
     */
    public function item($slug = '')
    {
        $productRow = $this->product_model->getProductBySlug($slug);  
        $id = $productRow['id_product'];
        $sellerId = $productRow['sellerid'];
        $productCategoryId = $productRow['cat_id'];
        $productImages = $this->product_model->getProductImages($id);
        $mainImage = $productImages[0]['id_product_image'];
        $productAttributes = $this->product_model->getProductAttributes($id, 'NAME');
        $productAttributes = $this->product_model->implodeAttributesByName($productAttributes);
        $productQuantity = $this->product_model->getProductQuantity($id, false, false, $productRow['start_promo']);
        $rating = $this->product_model->getVendorRating($sellerId);
        $seller = $this->memberpage_model->get_member_by_id($sellerId);  
        $reviews = $this->getReviews($id,$sellerId);
        $relatedItems = $this->product_model->getRecommendeditem($productCategoryId,5,$id);
        $productSpecification = $productCombinationAttributes = $complete = $newProductImageArray = array();

        foreach ($productImages as $key => $value) {
            unset($productImages[$key]['is_primary']);
            unset($productImages[$key]['path']);
            unset($productImages[$key]['file']);
            unset($productImages[$key]['id_product_image']);
            $productImages[$key]['id'] = $value['id_product_image'];
            $newProductImageArray[] = $productImages[$key];
        }

        foreach ($productAttributes as $key => $productOption) {
            $newArrayOption = array(); 

            for ($i=0; $i < count($productOption) ; $i++) { 
                $type = ($productAttributes[$key][$i]['type'] == 'specific' ? 'a' : 'b');
                $newKey = $type.'_'.$productAttributes[$key][$i]['value_id']; 
                $newArrayOption[$newKey] = $productOption[$i];
                $newArrayOption[$newKey]['name'] = $key; 
                $newArrayOption[$newKey]['id'] = $newKey; 
            }

            foreach ($newArrayOption as $key => $value) {
                unset($newArrayOption[$key]['type']);
                unset($newArrayOption[$key]['datatype']);
                unset($newArrayOption[$key]['datatype']);
                unset($newArrayOption[$key]['img_path']);
                unset($newArrayOption[$key]['img_file']);
                unset($newArrayOption[$key]['value_id']);

                if($newArrayOption[$key]['img_id'] == 0){
                    $newArrayOption[$key]['img_id'] = $mainImage;
                }
            }

            if(count($productOption)>1){
                $productCombinationAttributes[$key] = $newArrayOption; 
            }
            elseif((count($productOption) === 1)&&(($productOption[0]['datatype'] === '5'))||($productOption[0]['type'] === 'option')){
                $productCombinationAttributes[$key] = $newArrayOption; 
                $productSpecification = $newArrayOption;
            }
            else{
                $productSpecification = $newArrayOption; 
            }
        }

        $productSpecificationNew = [];
        foreach ($productSpecification as $key => $value) {
            $productSpecificationNew[] = $value;
        }
        
        foreach ($productCombinationAttributes as $key => $value) {
            foreach ($productCombinationAttributes[$key] as $key2 => $value2) {
                $complete[] = $value2; 
            }
        }

        $productQuantityNew = [];
        foreach ($productQuantity as $key => $valuex) {
            unset($productQuantity[$key]['attr_lookuplist_item_id']);
            unset($productQuantity[$key]['attr_name']);
            $newCombinationKey = array();

            for ($i=0; $i < count($valuex['product_attribute_ids']); $i++) { 
                $type = ($valuex['product_attribute_ids'][$i]['is_other'] == '0' ? 'a' : 'b'); 
                array_push($newCombinationKey, $type.'_'.$valuex['product_attribute_ids'][$i]['id']);
            }

            unset($productQuantity[$key]['product_attribute_ids']);
            $productQuantity[$key]['combinationId'] = $newCombinationKey;
            $productQuantity[$key]['id'] = $key;
            $productQuantityNew[] = $productQuantity[$key];
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
 
        foreach ($relatedItems as $key => $value) {
            unset($relatedItems[$key]['is_sold_out']);
            unset($relatedItems[$key]['cat_id']);
            unset($relatedItems[$key]['clickcount']);
            unset($relatedItems[$key]['is_promote']);
            unset($relatedItems[$key]['is_promote']);
            unset($relatedItems[$key]['startdate']);
            unset($relatedItems[$key]['enddate']);
            unset($relatedItems[$key]['enddate']);
            unset($relatedItems[$key]['promo_type']);
            unset($relatedItems[$key]['start_promo']);
            unset($relatedItems[$key]['is_free_shipping']);
            unset($relatedItems[$key]['path']);
            unset($relatedItems[$key]['file']);
            unset($relatedItems[$key]['can_purchase']);
            unset($relatedItems[$key]['id_product']);
        } 

        foreach ($reviews as $key => $value) {
            unset($reviews[$key]['reviewerid']);
            unset($reviews[$key]['ISOdate']); 

            foreach ($reviews[$key]['replies'] as $key2 => $value2) {
                unset($reviews[$key]['replies'][$key2]['replyto']);
                unset($reviews[$key]['replies'][$key2]['reviewerid']);
                unset($reviews[$key]['replies'][$key2]['title']);
            }
        } 

        $data = array( 
            "productDetails" => $productDetails,
            "productImages" => $newProductImageArray,
            "sellerDetails" => $sellerDetails,
            "productCombinationAttributes" => $complete,
            "productSpecification" => $productSpecificationNew, 
            "productCombinatiobDetails" => $productQuantityNew,
            "reviews" => $reviews,
            "relatedItems" => $relatedItems
            ); 

        print(json_encode($data,JSON_PRETTY_PRINT));
    }

    /**
     * Get review of the seller
     * @param  integer $product_id
     * @param  integer $sellerid
     * @return mixed
     */
    public function getReviews($product_id, $sellerid)
    {
        $recent = array();
        $recent = $this->product_model->getProductReview($product_id);

        if(count($recent)>0){
            $retrieve = array();
            foreach($recent as $data){
                array_push($retrieve, $data['id_review']);
            }
            $replies = $this->product_model->getReviewReplies($retrieve, $product_id);
            foreach($replies as $key=>$temp){
                $temp['review'] = html_escape($temp['review']);
            }
            $i = 0;
            $userid = $this->session->userdata('member_id');
            foreach($recent as $review){
                $recent[$i]['replies'] = array();
                $recent[$i]['reply_count'] = 0;
                if($userid === $review['reviewerid']){
                    $recent[$i]['is_reviewer'] = 1;
                }
                else{
                    $recent[$i]['is_reviewer'] = 0;
                }

                foreach($replies as $reply){
                    if($review['id_review'] == $reply['replyto']){
                        array_push($recent[$i]['replies'], $reply);
                        $recent[$i]['reply_count']++;
                    }
                }
                $i++;
            }
        }

        return $recent;
    }
}

/* End of file product.php */
/* Location: ./application/controllers/mobile/product.php */
