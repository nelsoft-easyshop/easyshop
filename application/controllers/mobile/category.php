<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Category extends MY_Controller {

    public $per_page = 12;

    function __construct()
    {
        parent::__construct();

        // Load entity manager
        $this->em = $this->serviceContainer['entity_manager']; 

        //Making response json type
        header('Content-type: application/json');
    }

    /**
     * Get All categories and arrange it recursively by its parent
     * @return JSON
     */
    public function getCategories()
    { 
        $categorySlug = urldecode($this->input->get('slug'));
        $param = urldecode($this->input->get('detail'));  

        $categories = $this->em->getRepository('EasyShop\Entities\EsCat')
                               ->selectAllCategory(); 

        $jsonCategory = $this->__buildTree($categories);  

        print(json_encode($jsonCategory,JSON_PRETTY_PRINT));
    }

    /**
     * Get all product under the given categories
     * @return JSON
     */
    public function getCategoriesProduct()
    {
        $searchProductService = $this->serviceContainer['search_product'];
        $esCatRepository = $this->em->getRepository('EasyShop\Entities\EsCat');

        $categorySlug = $this->input->get('slug');
        $page = ($this->input->get('page')) ? $this->input->get('page') : 0 ;
        $category = $esCatRepository->findOneBy(['slug' => $categorySlug]);
        $formattedRelatedItems = [];
        if($category){
            $getParameter['page'] = $page;
            $getParameter['category'] = $category->getIdCat(); 
            $search = $searchProductService->getProductBySearch($getParameter);
            $products = $search['collection'];

            foreach ($products as $key => $value) {
                $formattedRelatedItems[] = $this->serviceContainer['api_formatter']
                                                ->formatDisplayItem($value->getIdProduct());
            }
        }

        print(json_encode($formattedRelatedItems,JSON_PRETTY_PRINT));
    }

    /**
     * Arrange array recursively based on its parent
     * @param  array   $elements
     * @param  integer $parentId
     * @return mixed
     */
    private function __buildTree(array $elements, $parentId = 1)
    {   
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) { 
                $children = $this->__buildTree($elements, $element['id_cat']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
}

/* End of file category.php */
/* Location: ./application/controllers/mobile/category.php */
