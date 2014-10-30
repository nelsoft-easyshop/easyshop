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
    public $per_page = 15; 

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
        header ('Content-type: text/html; charset=ISO-8859-1');
        // Load Repository
        $EsLocationLookupRepository = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');

        // Load Service
        $searchProductService = $this->serviceContainer['search_product'];
        $categoryManager = $this->serviceContainer['category_manager']; 

        $categoryId = ($this->input->get('category') && count($this->input->get())>0)?trim($this->input->get('category')):1;
        $memberId = $this->session->userdata('member_id');

        if(count($_GET)>0){

            $parameter = $this->input->get();

            // getting all products
            $search = $searchProductService->getProductBySearch($parameter);
            $response['products'] = $search['collection']; 
            // get all attributes to by products
            $response['attributes'] = $searchProductService->getProductAttributesByProductIds($response['products']);
 
            // get total product Count
            $response['productCount'] = $search['count']; 
        }

        // Load sub category to display
        $subCategory = $EsCatRepository->findBy(['parent' => $categoryId]);
        $response['subCategory'] = $categoryManager->applyProtectedCategory($subCategory,FALSE);

        // Load parent category to display
        $parentCategory = $EsCatRepository->findBy(['parent' => 1]);
        $response['parentCategory'] = $categoryManager->applyProtectedCategory($parentCategory, FALSE);

        // Load all location
        $response['locatioList'] = $EsLocationLookupRepository->getLocation();

        // Load all default condition
        $response['defaultCondition'] = $this->lang->line('product_condition');

        // Load header data
        $data = array(
            'title' => 'Easyshop.com - Advanced Search',
            'render_searchbar' => false
        );
        $data = array_merge($data, $this->fill_header()); 

        $this->load->view('templates/header', $data); 
        $this->load->view('pages/search/advance_search_main',$response);
        $this->load->view('templates/footer');
    }

    public function loadMoreProduct()
    {
        // Load Repository
        $EsLocationLookupRepository = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');

        // Load Service
        $searchProductService = $this->serviceContainer['search_product'];
        $categoryManager = $this->serviceContainer['category_manager']; 

        $categoryId = ($this->input->get('category') && count($this->input->get())>0)?trim($this->input->get('category')):1;
        $memberId = $this->session->userdata('member_id');

        // getting all products
        $search = $searchProductService->getProductBySearch($this->input->get());
        $response['products'] = $search['collection']; 

        $response['typeOfView'] = trim($this->input->get('typeview'));
        $data['view'] = $this->load->view('pages/search/product_search_by_searchbox_more',$response,TRUE);
        $data['count'] = count($response['products']);
        echo json_encode($data);
    }

    /*   
     *   Returns results of searching products through the search bar
     *   Route: search/(:any)
     */
    public function searchfaster()
    { 
        header ('Content-type: text/html; charset=ISO-8859-1');

        // Check if search is empty if true redirect to all category view
        if(trim($this->input->get('q_str')) === "" && intval(trim($this->input->get('category'))) <= 1){
            redirect('cat/all');
        }

        // Load Repository
        $EsLocationLookupRepository = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');
        $EsCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');

        // Load Service
        $searchProductService = $this->serviceContainer['search_product'];
        $categoryManager = $this->serviceContainer['category_manager']; 

        $response['string'] = ($this->input->get('q_str')) ? trim($this->input->get('q_str')) : "";
        $categoryId = ($this->input->get('category') && count($this->input->get())>0)?trim($this->input->get('category')):1;
        $parameter = $this->input->get();

        // getting all products 
        $search = $searchProductService->getProductBySearch($parameter);
        $response['products'] = $search['collection']; 
 
        // get total product Count 
        $response['productCount'] = $search['count']; 

        // get all attributes to by products
        $response['attributes'] = $searchProductService->getProductAttributesByProductIds($response['products']);

        $parentCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                            ->findBy(['parent' => 1]);
        
        // Apply protected category
        $protectedCategory = $categoryManager->applyProtectedCategory($parentCategory, FALSE); 
        
        // Set image in every category
        $response['parentCategory'] = $categoryManager->setCategoryImage($protectedCategory);

        // category navigation of desktop version
        $response['category_navigation_desktop'] = $this->load->view('templates/category_navigation_responsive',
                array('parentCategory' =>  $response['parentCategory'],
                    'environment' => 'desktop'), TRUE );

        // category navigation of mobile version
        $response['category_navigation_mobile'] = $this->load->view('templates/category_navigation_responsive',
                array('parentCategory' =>  $response['parentCategory'],
                    'environment' => 'mobile'), TRUE );

        $data = array(
                'title' => (($response['string']==='')?"Search":$response['string']).' | Easyshop.ph'
                );

        $data = array_merge($data, $this->fill_header());

        // Load view
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