<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    

use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsCat as EsCat;

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
    public $per_page = 15; 

     /**
      * Returns recommended keywords for search bar
      * @return JSON
      */
    public function suggest()
    {  
        $response = [];
        if($this->input->get('query')){
            $queryString =  $this->input->get('query'); 
            $queryString = trim($queryString);  
            $searchSuggestions = $this->serviceContainer['search_product']
                                      ->getKeywordSuggestions($queryString);
            $response = $searchSuggestions;
        }
        
        echo json_encode($response);
    }

    /**
    *  Advance search main function
    *  route: advsrch
    */
    public function advance()
    {
        $EsLocationLookupRepository = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');

        $searchProductService = $this->serviceContainer['search_product'];
        $categoryManager = $this->serviceContainer['category_manager']; 

        $categoryId = $this->input->get('category') && count($this->input->get()) > 0
                      ? trim($this->input->get('category'))
                      : EsCat::MAIN_PARENT_CATEGORY;
        $memberId = $this->session->userdata('member_id');

        if(count($_GET)>0){
            $parameter = $this->input->get();
            $search = $searchProductService->getProductBySearch($parameter);
            $response['products'] = $search['collection']; 
            $response['attributes'] = $searchProductService->getProductAttributesByProductIds($response['products']);
            $response['productCount'] = $search['count']; 
        }

        $subCategory = $EsCatRepository->findBy(['parent' => $categoryId]);
        $response['subCategory'] = $categoryManager->applyProtectedCategory($subCategory,false);
        $parentCategory = $EsCatRepository->findBy(['parent' => 1]);
        $response['parentCategory'] = $categoryManager->applyProtectedCategory($parentCategory, false);
        $response['locatioList'] = $EsLocationLookupRepository->getLocation();
        $response['defaultCondition'] = $this->lang->line('product_condition');
        $response['getParameter'] = $this->input->get();

        $headerData = [
            'title' => 'Easyshop.com - Advanced Search',
            'metadescription' => '',
            'relCanonical' => '',
            'render_searchbar' => false
        ];

        $this->load->spark('decorator');    
        $this->load->view('templates/header',  $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/search/advance_search_main',$response);
        $this->load->view('templates/footer');
    }

    public function loadMoreProduct()
    {
        $EsLocationLookupRepository = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');

        $searchProductService = $this->serviceContainer['search_product'];
        $categoryManager = $this->serviceContainer['category_manager']; 

        $categoryId = ($this->input->get('category') && count($this->input->get())>0)?trim($this->input->get('category')):1;
        $memberId = $this->session->userdata('member_id');

        $search = $searchProductService->getProductBySearch($this->input->get());
        $response['products'] = $search['collection']; 

        $response['typeOfView'] = trim($this->input->get('typeview'));
        $data['view'] = $this->load->view('pages/search/product_search_by_searchbox_more',$response,true);
        $data['count'] = count($response['products']);
        echo json_encode($data);
    }

    /*   
     *   Returns results of searching products through the search bar
     *   Route: search/(:any)
     */
    public function searchfaster()
    {
        if(trim($this->input->get('q_str')) === "" && intval(trim($this->input->get('category'))) <= 1){
            redirect('cat/all');
        }

        $EsLocationLookupRepository = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');

        $searchProductService = $this->serviceContainer['search_product'];
        $categoryManager = $this->serviceContainer['category_manager']; 

        $response['string'] = ($this->input->get('q_str')) ? trim($this->input->get('q_str')) : "";
        $categoryId = ($this->input->get('category') && count($this->input->get())>0)?trim($this->input->get('category')):1;
        $parameter = $response['getParameter'] = $this->input->get();

        $search = $searchProductService->getProductBySearch($parameter);
        $response['products'] = $search['collection']; 
        $response['productCount'] = $search['count']; 
        $response['attributes'] = $searchProductService->getProductAttributesByProductIds($response['products']);

        $parentCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                            ->findBy(['parent' => 1]);

        $protectedCategory = $categoryManager->applyProtectedCategory($parentCategory, false); 

        $response['parentCategory'] = $categoryManager->setCategoryImage($protectedCategory);

        $response['category_navigation_desktop'] = $this->load->view('templates/category_navigation_responsive',
                [
                    'parentCategory' =>  $response['parentCategory'],
                    'environment' => 'desktop'
                ], true );

        $response['category_navigation_mobile'] = $this->load->view('templates/category_navigation_responsive',
                [
                    'parentCategory' =>  $response['parentCategory'],
                    'environment' => 'mobile'
                ], true );

        $headerData = [
            'title' => (($response['string']==='')?"Search":$response['string']).' | Easyshop.ph'
        ];

       
        $this->load->spark('decorator');    
        $this->load->view('templates/header',  $this->decorator->decorate('header', 'view', $headerData));
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