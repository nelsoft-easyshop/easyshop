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
        $productCategoryId = $productRow['cat_id'];

        $format = $this->serviceContainer['api_formatter']->formatItem($id);

        $relatedItems = $this->product_model->getRecommendeditem($productCategoryId,5,$id);
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
        $format = array_merge($format,array('relatedItems'=>$relatedItems));
        print(json_encode($format,JSON_PRETTY_PRINT));
    }
}

/* End of file product.php */
/* Location: ./application/controllers/mobile/product.php */
