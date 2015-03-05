<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Resources extends MY_Controller {

    function __construct()
    {
        parent::__construct(); 
        header('Content-type: application/json');
        $this->em = $this->serviceContainer['entity_manager']; 
    }

    /**
     * Get all location and arrange recursive based on it's parent location
     * format for shipping address
     * @return json
     */
    public function getLocationForAddress()
    {
        $apiFormatter = $this->serviceContainer['api_formatter'];
        $locations = $apiFormatter->formatLocationForAddress();
    
        echo json_encode($locations,JSON_PRETTY_PRINT);
    }

    /**
     * Get all location and arrange recursive based on it's parent location
     * format for product shipping location
     * @return json
     */
    public function getLocationForShipping()
    {
        $apiFormatter = $this->serviceContainer['api_formatter'];
        $locations = $apiFormatter->formatLocationForShipping();

        echo json_encode($locations,JSON_PRETTY_PRINT);
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