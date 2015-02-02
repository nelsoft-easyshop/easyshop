<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


    /**
    *    UTILITY functions for handling products.
    *    
    *     Added here to make functions accessible to multiple models    
    *
    */

    /**
    *  Separates img path and img file from product_image_path
    *  Result is stored back to the original array by reference
    *  @param array $array: 1D array data from database fetch
    *  @param bool $empty: (boolean value) if true, the default path and file are set to empty strings
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
                    if(!file_exists($row['product_image_path']) && strtolower(ENVIRONMENT) === 'development'){
                        $row['path'] = 'assets/product/unavailable/';
                        $row['file'] = 'unavailable_product_img.jpg';
                    }
                    else{
                        $rev_url = strrev($row['product_image_path']);
                        $row['path'] = substr($row['product_image_path'],0,strlen($rev_url)-strpos($rev_url,'/'));
                        $row['file'] = substr($row['product_image_path'],strlen($rev_url)-strpos($rev_url,'/'),strlen($rev_url));
                    } 
                }
                $array[$key] = $row;
            }
        }
    }

    /**
     *    Applies discount to a product
     *
     *    Requires product array with the following indexes:
     *    @param float $price - product original price
     *    @param string $startdate - start date of discount
     *    @param string $enddate - end date of discount
     *    @param bool $is_promote - flag for promoted products
     *
     *    Returns by reference the following indexes:
     *    @return float $original_price - original price
     *    @return float $price - discounted price
     *    @return bool $start_promo - boolean, whether the promo is valid
     *    @return float $percentage - the percentage of the discount
     *    @return bool $can_purchase - boolean, whether user can buy the item or not
     */

    if ( ! function_exists('applyPriceDiscount')){
        function applyPriceDiscount(&$product = array()){
            $CI = get_instance();
            $CI->load->model('product_model');
            $buyer_id = $CI->session->userdata('member_id');
            $product['start_promo'] = false;
            $product['end_promo'] = false;
            if(intval($product['is_promote']) === 1){
                $promo = $CI->product_model->GetPromoPrice($product['price'],$product['startdate'],$product['enddate'],$product['is_promote'],$product['promo_type'], $product['discount']);
                $product['start_promo'] = $promo['start_promo'];   
                $product['end_promo'] = $promo['end_promo'];  
                $product['original_price'] = $product['price'];    
                $product['can_purchase'] =  $CI->product_model->is_purchase_allowed($buyer_id,$product['promo_type'],$product['start_promo']);
                $product['sold_price'] = $CI->product_model->get_sold_price($product['id_product'], date('Y-m-d',strtotime($product['startdate'])), date('Y-m-d',strtotime($product['enddate'])));
                if($product['is_sold_out']){
                    $product['price'] = $product['sold_price'];
                }
                else{
                    $product['price'] = $promo['price'];
                }            
            }
            else{
                $product['original_price'] = $product['price']; 
                $product['can_purchase'] = true;
                if(intval($product['discount']) > 0){
                    $product['price'] = $product['price'] * (1.0-($product['discount']/100.0));
                }  
            }
            
            if($product['original_price'] <= 0){
                $product['percentage'] = 0;
            }
            else{
                $product['percentage'] = ($product['original_price'] - $product['price'])/$product['original_price'] * 100.00;
            }

            $product['is_free_shipping'] = $CI->product_model->is_free_shipping($product['id_product']);
            
        }
    }
