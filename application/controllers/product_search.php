<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    

use EasyShop\Entities\EsProduct;

class product_search extends MY_Controller {
    
    function __construct()  
    { 
        parent::__construct(); 
        $this->load->helper('htmlpurifier');
        $this->load->model("product_model");
        $this->load->model("search_model");

        // Loading entity manager 
        $this->em = $this->serviceContainer['entity_manager'];
    }
  
    /*   
     *   Number of returned products per request
     */
    public $per_page = 4; 

     /**
     *   Returns recommended keywords for search bar
     */
    function sch_onpress()
    {  
        header('Content-Type: text/plain'); 
        if($this->input->get('q')){

            $html = "";
            $stringData =  $this->input->get('q'); 
            $string = ltrim($stringData);  
            $words = explode(" ",trim($string)); 
            $keywords = $this->search_model->itemKeySearch($words);
           
            if(count($keywords) <= 0){
                $html = 0;
            }else{
                $html .= "<ul>";
                foreach ($keywords as $value) {
                    $showValue = $this->highlights($value,$stringData);
                    $html .= "<li><a href='".base_url()."search/search.html?q_str=".urlencode($value)."&q_cat=1'>".$showValue."</a></li>";

                }
                $html .= "</ul>";
            }

            echo $html;
        }
    }

    /**
    *  Advance search main function
    *  route: advsrch
    */
    public function advance()
    {
        $searchProductService = $this->serviceContainer['search_product']; 
        $productManager = $this->serviceContainer['product_manager']; 
        $collectionHelper = $this->serviceContainer['collection_helper']; 
        $categoryManager = $this->serviceContainer['category_manager']; 

        $EsProductRepository = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');
        $EsLocationLookupRepository = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');

        $queryString = trim($this->input->get('q_str'));
        $category = $getParamCategory = trim($this->input->get('q_cat'));
        $brand = trim($this->input->get('brand'));
        $condition = trim($this->input->get('condition'));
        $seller = trim($this->input->get('seller'));
        $location = trim($this->input->get('location'));
        $startPrice = trim($this->input->get('startprice'));
        $endPrice = trim($this->input->get('endprice'));
        $memberId = $this->session->userdata('member_id');
        $page = (trim($this->input->get('page'))) ? trim($this->input->get('page')) : 0;
        $storeKeyword = (trim($this->input->get('page'))) ? FALSE:TRUE;
        $category = ($category > 1) ? $EsCatRepository->getChildCategoryRecursive($category):array('1');

        if(count($_GET)>0){
            $productIds = $originalOrder = ($queryString)?$searchProductService->filterBySearchString($queryString,$storeKeyword):array();

            if($queryString == "" && $condition == "" && $seller == "" && $location == ""){ 
                $productIds = $searchProductService->filterByCategory($category,$productIds,FALSE);
            }
            else{
                $hasQueryString = (!$queryString) ? FALSE : TRUE; 
                $productIds = $searchProductService->filterByCategory($category, $productIds, $hasQueryString);
            }  
            
            if($condition){ 
                if($queryString == "" && $seller == "" && $location == "" && $getParamCategory == 1){ 
                    $productIds = $searchProductService->filterByCondition($condition, $productIds, FALSE);
                }
                else{
                    $productIds = ($condition)?$searchProductService->filterByCondition($condition,$productIds,TRUE):$productIds;
                }
            }

            if($seller){
                if($queryString == "" && $condition == "" && $seller != "" && $location == "" && $getParamCategory == 1){
                    $productIds = $EsProductRepository->findBySeller($seller);
                }
            }

            if($location){
                if($queryString == "" && $condition == "" && $seller == "" && $location != "" && $getParamCategory == 1){
                    $productIds = $searchProductService->filterByLocation($location,$productIds,FALSE);
                }
                else{
                    $productIds = ($location)?$searchProductService->filterByLocation($location,$productIds,TRUE):$productIds;
                }
            }

            $productIds = ($brand) ? $searchProductService->filterByBrand($brand,$productIds,TRUE) : $productIds;
            $productIds = $searchProductService->filterByOtherParameter($this->input->get(),$productIds);  
            $productIds = (count($originalOrder)>0) ? array_intersect($originalOrder, $productIds) : $productIds; 

            $filteredProduct = $EsProductRepository->getDetails($productIds,$page,$this->per_page);
            $discountedProduct = ($filteredProduct > 0) ? $productManager->getDiscountedPrice($memberId,$filteredProduct) : array();
            $filterSellerProduct = ($seller)?$searchProductService->filterBySeller($seller,$discountedProduct):$discountedProduct;
            $response['products'] = ($startPrice) ? $searchProductService->filterByPrice($startPrice,$endPrice,$filterSellerProduct) : $filterSellerProduct;

            // if ajax request display json data of products
            if($page){
                $response['typeview'] = trim($this->input->get('typeview'));
                $data['view'] = $this->load->view('pages/search/product_search_by_searchbox_more',$response,TRUE);
                $data['count'] = count($response['products']);

                die(json_encode($data));
            }

            $finalizedProductId = array();
            foreach ($response['products'] as $key => $value) {
                array_push($finalizedProductId, $value->getProduct()->getIdProduct());
            } 
            
            $organizedAttribute = array();
            if(count($finalizedProductId)>0){
                $brands = $EsProductRepository->getBrands($finalizedProductId);
                $attributes = $EsProductRepository->getAttributes($finalizedProductId);
                $brands = $EsProductRepository->getBrands($finalizedProductId);
                $organizedAttribute = $collectionHelper->organizeArray($attributes);
                $organizedAttribute['Brand'] = $brands;
                ksort($organizedAttribute);
            }

            $response['attributes'] = $organizedAttribute;
            $response['string'] = $queryString;
            $subCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                                                    ->findBy(['parent' => $getParamCategory]);
                                                    
            $response['subCategory'] = $categoryManager->applyProtectedCategory($subCategory,FALSE);
        }
        else{
            $subCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                                                    ->findBy(['parent' => 1]);
            $response['subCategory'] = $categoryManager->applyProtectedCategory($subCategory,FALSE);
        }
        
        $parentCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                                                    ->findBy(['parent' => 1]);

        $response['parentCategory'] = $categoryManager->applyProtectedCategory($parentCategory, FALSE);
        $response['locatioList'] = $EsLocationLookupRepository->getLocation();
        $response['defaultCondition'] = $this->lang->line('product_condition');

        $data = array(
            'title' => 'Easyshop.com - Advanced Search'
        );
        $data = array_merge($data, $this->fill_header());
        $data['render_searchbar'] = false;

        $this->load->view('templates/header', $data); 
        $this->load->view('pages/search/advance_search_main',$response);
        $this->load->view('templates/footer');
    }

    /*   
     *   Returns results of searching products through the search bar
     *   Route: search/(:any)
     */
    public function searchfaster()
    { 
        $searchProductService = $this->serviceContainer['search_product']; 
        $productManager = $this->serviceContainer['product_manager']; 
        $collectionHelper = $this->serviceContainer['collection_helper']; 
        $EsProductRepository = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');
        $categoryManager = $this->serviceContainer['category_manager']; 

        $queryString = (trim($this->input->get('q_str'))) ? trim($this->input->get('q_str')) : "";
        $category = trim($this->input->get('q_cat'));
        $brand = trim($this->input->get('brand'));
        $condition = trim($this->input->get('condition'));
        $startPrice = trim($this->input->get('startprice'));
        $endPrice = trim($this->input->get('endprice'));
        $memberId = $this->session->userdata('member_id');
        $page = (trim($this->input->get('page'))) ? trim($this->input->get('page')) : 0;
        $category = ($category > 1) ? $EsCatRepository->getChildCategoryRecursive($category):array('1');
        $storeKeyword = (trim($this->input->get('page'))) ? FALSE:TRUE;

        $productIds = $originalOrder = $searchProductService->filterBySearchString($queryString,$storeKeyword); 
        $productIds = ($category) ? $searchProductService->filterByCategory($category,$productIds,TRUE) : $productIds; 
        $productIds = ($brand) ? $searchProductService->filterByBrand($brand,$productIds,TRUE) : $productIds; 
        $productIds = ($condition) ? $searchProductService->filterByCondition($condition,$productIds,TRUE) : $productIds; 
        $productIds = $searchProductService->filterByOtherParameter($this->input->get(),$productIds);
        $productIds = array_intersect($originalOrder, $productIds);

        $filteredProduct = (count($productIds)>0)?$EsProductRepository->getDetails($productIds,$page,$this->per_page):array();
        $discountedProduct = $productManager->getDiscountedPrice($memberId,$filteredProduct);

        $response['products'] = ($startPrice) ? $searchProductService->filterByPrice($startPrice,$endPrice,$discountedProduct) : $discountedProduct;
        
        // if ajax request display json data of products
        if($page){
            $response['typeview'] = trim($this->input->get('typeview'));
            $data['view'] = $this->load->view('pages/search/product_search_by_searchbox_more',$response,TRUE);
            $data['count'] = count($response['products']);
            die(json_encode($data));
        }

        $finalizedProductId = array();
        $availableCondition = array();
        foreach ($response['products'] as $key => $value) {
            array_push($finalizedProductId, $value->getProduct()->getIdProduct());
            array_push($availableCondition, $value->getProduct()->getCondition());
        }

        $organizedAttribute = array();
        if(count($finalizedProductId)>0){
            $brands = $EsProductRepository->getBrands($finalizedProductId);
            $attributes = $EsProductRepository->getAttributes($finalizedProductId);
            $brands = $EsProductRepository->getBrands($finalizedProductId);
            $organizedAttribute = $collectionHelper->organizeArray($attributes);
            $organizedAttribute['Brand'] = $brands;
            $organizedAttribute['Condition'] = array_unique($availableCondition);
            ksort($organizedAttribute);
        }

        $response['attributes'] = $organizedAttribute;
        $response['string'] = $queryString;
        $response['category_navigation'] = $this->load->view('templates/category_navigation',array('cat_items' =>  $this->getcat(),), TRUE );
        $parentCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                                            ->findBy(['parent' => 1]);
        $response['parentCategory'] = $categoryManager->applyProtectedCategory($parentCategory, FALSE); 
        
        $data = array(
                'title' => ($queryString==='')?'Search | Easyshop.ph':$queryString.' | Easyshop.ph',
                );

        $data = array_merge($data, $this->fill_header());
        $this->load->view('templates/header', $data); 
        $this->load->view('pages/search/product_search_by_searchbox',$response);
        $this->load->view('templates/footer'); 
    }
    
    /**
     *   Search category list using string and organized based on its parent
     *   through the search bar
     *   Route: searchCategory
     *   @return JSON
     */
    public function searchCategory(){  
        
        $userId = $this->session->userdata('member_id');
        $isAdmin = false;
        if($userId){
            $this->load->model('user_model');
            $userdetails = $this->user_model->getUserById($userId);
            $isAdmin = (intval($userdetails['is_admin']) === 1);
        }

        $this->config->load('protected_category', TRUE);
        $protected_categories = $this->config->config['protected_category'];

        $string = $this->input->get('data');
        $explodString = explode(' ', trim($string));
        $newString = '+'.implode('* +', $explodString).'*';  
        $rows = $this->search_model->searchCategory($newString);
        foreach($rows as $idx=>$row){
            if(in_array($row['id_cat'],$protected_categories) && !$isAdmin){
                unset($rows[$idx]);
                continue;
            }
            $rows[$idx]['parent'] = $this->product_model->getParentId($row['id_cat']);
        }

        echo json_encode($rows);
    }

    /**
     *   Search Brand avaialable usjng given string
     *   Route: searchBrand
     *   @return JSON
     */
    public function searchBrand()
    {
        $string = $this->input->get('data');
        $explodString = explode(' ', trim($string));
        $newString = '+'.implode('* +', $explodString).'*';  
        $rows = $this->search_model->searchBrand($newString);
        echo json_encode($rows);
    }

    /**
     *  Hightlight string search to the available words
     *  @param string $text
     *  @param string $words
     *  @return string $text
     */
    private function highlights($text, $words)
    {
        $words = preg_replace('/\s+/', ' ',$words);
        $splitWords = explode(" ", $words);
        foreach($splitWords as $word){
            $color = "#e5e5e5";
            $text = preg_replace("|($word)|Ui","<mark>$1</mark>" , $text );
        } 

        return $text;
    }

    private function toUL($array = array(), $string = '')
    {
        $html = '<ul>' . PHP_EOL;
        foreach ($array as $value)
        {
            if($value['count'] <= 0){
                continue;
            }
            $html .= '<li><a href="search.html?q_str=' . $string .'&q_cat='.$value['item_id'].'">' . $value['name'].'('.$value['count'].')</a>';
            if (!empty($value['children'])){
                $html .= $this->toUL($value['children'], $string);
            }
            $html .= '</li>' . PHP_EOL;
        }

        $html .= '</ul>' . PHP_EOL;

        return $html;
    }


}

/* End of file product_search.php */
/* Location: ./application/controllers/product_search.php */