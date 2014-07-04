<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Category extends MY_Controller {

    public $per_page;

    function __construct() {
        parent::__construct();
		$this->load->helper('htmlpurifier');
		
        //Loading Models
        $this->load->model('product_model'); 

        //Making response json type
        header('Content-type: application/json');
    }

    public function home()
    {
        $items =  $this->product_model->getHomeContent(); 
        die(json_encode($items,JSON_PRETTY_PRINT));
    }

    public function getCategories()
    { 
    	$categorySlug = urldecode($this->input->get('slug')); 
    	$all = $this->product_model->selectAllCategory(); 
    	$categoryId = 1;

    	if($categorySlug){
    		$category_array = $this->product_model->getCategoryBySlug($categorySlug);
    		$categoryId = $category_array['id_cat']; 
    	}

    	$jsonCategory = $this->buildTree($all,$categoryId);

 		die(json_encode($jsonCategory,JSON_PRETTY_PRINT));
    }
   
    private function buildTree(array $elements, $parentId = 1)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id_cat']);
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
/* Location: ./application/controllers/category.php */
