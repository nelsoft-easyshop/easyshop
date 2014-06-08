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
                #$row['product_image_path'] = ($row['product_image_path'][0]=='.')?substr($row['product_image_path'],1,strlen($row['product_image_path'])):$row['product_image_path'];
                #$row['product_image_path'] = ($row['product_image_path'][0]=='/')?substr($row['product_image_path'],1,strlen($row['product_image_path'])):$row['product_image_path'];
                $rev_url = strrev($row['product_image_path']);
                $row['path'] = substr($row['product_image_path'],0,strlen($rev_url)-strpos($rev_url,'/'));
                $row['file'] = substr($row['product_image_path'],strlen($rev_url)-strpos($rev_url,'/'),strlen($rev_url));
            }
            #unset($row['product_image_path']);
            $array[$key] = $row;
        }
    }
}



