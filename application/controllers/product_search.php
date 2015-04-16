<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    

use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsCat as EsCat;

class product_search extends MY_Controller {
    
    function __construct()  
    { 
        parent::__construct();
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
            $queryString = trim($this->input->get('query'));
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
                      : EsCat::ROOT_CATEGORY_ID; 

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
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Easyshop.com - Advanced Search',
            'metadescription' => '',
            'relCanonical' => '',
            'render_searchbar' => false
        ];

        $this->load->spark('decorator');    
        $this->load->view('templates/header_primary',  $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/search/advance_search_main',$response);
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view')); 
    }

    /**
     * load more product when scroll in advance search
     * @return json
     */
    public function loadMoreProductAdvance()
    {
        $EsLocationLookupRepository = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');

        $searchProductService = $this->serviceContainer['search_product']; 
        $search = $searchProductService->getProductBySearch($this->input->get());
        $response['products'] = $search['collection']; 

        $response['typeOfView'] = trim($this->input->get('typeview'));
        $data['view'] = $this->load->view('pages/search/product_search_by_searchbox_more',$response,true);
        $data['count'] = count($response['products']);
        echo json_encode($data);
    }

    /**
     * load more product when scroll
     * @return json
     */
    public function loadMoreProduct()
    {
        $searchProductService = $this->serviceContainer['search_product']; 
        $search = $searchProductService->getProductBySearch($this->input->get()); 
        $typeOfView = trim($this->input->get('typeview'));
        $currentPage = (int) $this->input->get('page'); 
        $productViewData = [
            'products' => $search['collection'],
            'currentPage' => $currentPage + 1,
            'isListView' => $typeOfView === 'list',
        ];
        $data['view'] = $this->load->view('partials/search-products', $productViewData, true); 
        $data['count'] = count($search['collection']);
        echo json_encode($data);
    }

    /*   
     *   Returns results of searching products through the search bar
     *   Route: search/(:any)
     */
    public function search()
    {
        if(trim($this->input->get('q_str')) === ""
           && (int) trim($this->input->get('category')) === EsCat::ROOT_CATEGORY_ID
           || trim($this->input->get('q_str')) === ""
              && !$this->input->get('category')){
            redirect('cat/all');
        } 

        $searchProductService = $this->serviceContainer['search_product'];
        $categoryManager = $this->serviceContainer['category_manager']; 

        $response['string'] = $this->input->get('q_str') ? trim(utf8_decode($this->input->get('q_str'))) : "";
        $parameter = $response['getParameter'] = $this->input->get();
        $search = $searchProductService->getProductBySearch($parameter);

        $response['products'] = $search['collection']; 
        $response['productCount'] = $search['count']; 
        $response['attributes'] = $searchProductService->getProductAttributesByProductIds($search['collection']);
        $response['availableCondition'] = [];
        if(isset($response['attributes']['Condition'])){
            $response['availableCondition'] = $response['attributes']['Condition'];
            unset($response['attributes']['Condition']);
        }
        $response['totalPage'] = ceil($search['count'] / $searchProductService::PER_PAGE);
        $paginationData = [
            'totalPage' => $response['totalPage'],
        ];
        $response['pagination'] = $this->load->view('pagination/search-pagination', $paginationData, true);

        $category = EsCat::ROOT_CATEGORY_ID; 
        $parentCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                                   ->findBy(['parent' => $category]);
        $response['categories'] = $categoryManager->applyProtectedCategory($parentCategory, false); 
        
        $response['categorySelected'] = $this->input->get('category') ? (int) $this->input->get('category') : $category;
        $response['isListView'] = isset($_COOKIE['view']) && (string)$_COOKIE['view'] === "list";

        $headerData = [ 
            "memberId" => $this->session->userdata('member_id'),
            'title' => (($response['string']==='')?"Search":html_escape($response['string'])).' | Easyshop.ph' 
        ];

        $productViewData = [
            'products' => $search['collection'],
            'currentPage' => 1,
            'isListView' => $response['isListView'],
        ];
        $response['productView']  = $this->load->view('partials/search-products', $productViewData, true);

        $this->load->spark('decorator');
        $this->load->view('templates/header_primary',  $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/search/product-search-new',$response);
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
}

/* End of file product_search.php */
/* Location: ./application/controllers/product_search.php */