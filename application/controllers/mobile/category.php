<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Category extends MY_Controller {

    public $per_page = 12;

    function __construct() {
        parent::__construct();
		$this->load->helper('htmlpurifier');
		
        //Loading Models
        $this->load->model('product_model'); 

        //Making response json type
        header('Content-type: application/json');
    }

    public function getCategories()
    { 
        $categorySlug = urldecode($this->input->get('slug'));
        $param = urldecode($this->input->get('detail')); 
        $need = 
    	$all = $this->product_model->selectAllCategory(); 
    	$categoryId = 1;

    	if($categorySlug){
    		$categoryArray = $this->product_model->getCategoryBySlug($categorySlug);
            if($param){
                die(json_encode($categoryArray,JSON_PRETTY_PRINT));
            }
    
            $categoryId = $categoryArray['id_cat']; 
            $parentId = $categoryArray['parent_id']; 
    	}
 
        if($this->input->get('up')){
            $categoryId = 1;

            for ($i=1; $i < $this->input->get('up'); $i++) { 
                $categoryArrayParent = $this->product_model->selectCategoryDetails($parentId);   
                $parentId = $categoryId = $categoryArrayParent['parent_id'];
                if($parentId == 1){
                    break;
                }
            }

            if($parentId != 1){
              $categoryArrayParent = $this->product_model->selectCategoryDetails($parentId);   
              $categoryId = $categoryArrayParent['id_cat'];
            }
        }

    	$jsonCategory = $this->buildTree($all,$categoryId);  

 		die(json_encode($jsonCategory,JSON_PRETTY_PRINT));
    }

    public function getCategoriesProduct()
    {
        $categorySlug = $this->input->get('slug');
        $start = $this->input->get('start');
        $category_array = $this->product_model->getCategoryBySlug($categorySlug);
        $categoryId = $category_array['id_cat']; 
        $downCategory = $this->product_model->selectChild($categoryId);

        if(empty($downCategory)){
            $downCategory = array();
        }
        
        array_push($downCategory, $categoryId);
        $categories = implode(",", $downCategory);
        $items = $this->product_model->getProductsByCategory($categories,array(),0,"<",$start,$this->per_page,"");

        die(json_encode($items,JSON_PRETTY_PRINT));
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
