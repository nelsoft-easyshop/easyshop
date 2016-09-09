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
}

/* End of file category.php */
/* Location: ./application/controllers/mobile/category.php */
