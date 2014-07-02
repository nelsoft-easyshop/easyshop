<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


/**
*    UTILITY functions for handling products.
*    
*     Added here to make functions accessible to multiple models    
*
*/ 


/*
 *  Separates img path and img file from product_image_path
 *  Result is stored back to the original array by reference
 *  Arguments: @$array: 1D array data from database fetch
 *             @$empty: (boolean value) if true, the default path and file are set to empty strings
 */
 
if ( ! function_exists('explodeImagePath')){
    function explodeImagePath(&$array=array(), $empty = false){
        foreach($array as $key=>$row){		
            if(trim($row['product_image_path']) === ''){
                if(!$empty){
                    $row['path'] = 'assets/product/default/';
                    $row['file'] = 'default_product_img.jpg';
                }
                else{
                    $row['path'] = '';
                    $row['file'] = '';
                }
            }
            else{
                if(file_exists($row['product_image_path'])){
                    #$row['product_image_path'] = ($row['product_image_path'][0]=='.')?substr($row['product_image_path'],1,strlen($row['product_image_path'])):$row['product_image_path'];
                    #$row['product_image_path'] = ($row['product_image_path'][0]=='/')?substr($row['product_image_path'],1,strlen($row['product_image_path'])):$row['product_image_path'];
                    $rev_url = strrev($row['product_image_path']);
                    $row['path'] = substr($row['product_image_path'],0,strlen($rev_url)-strpos($rev_url,'/'));
                    $row['file'] = substr($row['product_image_path'],strlen($rev_url)-strpos($rev_url,'/'),strlen($rev_url));
                }else{
                    $row['path'] = 'assets/product/unavailable/';
                    $row['file'] = 'unavailable_product_img.jpg';
                }
            }
            #unset($row['product_image_path']);
            $array[$key] = $row;
        }
    }
}

/*
 *    Applies discount to a product
 *
 *    Requires product array with the following indexes:
 *    @price - product original price
 *    @startdate - start date of discount
 *    @enddate - end date of discount
 *    @is_promote - flag for promoted products
 *
 *    Returns by reference the following indexes:
 *    @original_price - original price
 *    @price - discounted price
 *    @start_promo - boolean, whether the promo is valid
 *    @percentage - the percentage of the discount
 *    @can_purchase - boolean, whether user can buy the item or not
 */

if ( ! function_exists('applyPriceDiscount')){
    function applyPriceDiscount(&$product = array()){  
        $CI = get_instance();
        $CI->load->model('product_model');
        $buyer_id = $CI->session->userdata('member_id');
        $product['start_promo'] = false;
        if(intval($product['is_promote']) === 1){
            $promo = $CI->product_model->GetPromoPrice($product['price'],$product['startdate'],$product['enddate'],$product['is_promote'],$product['promo_type'],$buyer_id,$product['id_product']);
            $product['original_price'] = $product['price'];    
            $product['can_purchase'] = $promo['can_purchase'];
            $product['is_soldout'] = $promo['is_soldout'];
            if($promo['is_soldout']){
                $product['price'] = $promo['sold_price'];
            }else{
                $product['price'] = $promo['price'];
            }
            $product['sold_price'] = $promo['sold_price'];
            $product['start_promo'] = ((intval($product['is_promote']) === 1)&&(strtotime($product['startdate']) < strtotime(date('Y-m-d H:i:s'))));
            $product['percentage'] = ($product['start_promo'])?($product['original_price'] - $product['price'])/$product['original_price'] * 100.00:0.00;
        }else{
            $product['original_price'] = $product['price']; 
            $product['can_purchase'] = true;

            if(intval($product['discount']) > 0){
                $product['price'] = $product['price'] * (1.0-($product['discount']/100.0));
            }
            $product['percentage'] = ($product['original_price'] - $product['price'])/$product['original_price'] * 100.00;      
        }
   }
}



