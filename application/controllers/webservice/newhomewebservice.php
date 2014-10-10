<?php 

use EasyShop\Entities\EsProduct; 

class NewHomeWebService extends MY_Controller 
{
    /**
     * The XML file service
     */
    private $xmlCmsService;

    /**
     * The XML resource servie
     */
    private $xmlFileService;

    /**
     * The entity manager
     */    
    private $em;

    /**
     * The JSONP callback function
     */    
    private $json;

    /**
     * The Mobile XML resource
     */    
    private $file;    

    public function __construct() 
    {
        parent::__construct();

        $this->xmlCmsService = $this->serviceContainer['xml_cms'];
        $this->xmlFileService = $this->serviceContainer['xml_resource'];
        $this->em = $this->serviceContainer['entity_manager'];
        $this->file  = APPPATH . "resources/". $this->xmlFileService->getNewHomeXML().".xml"; 
        $this->json = file_get_contents(APPPATH . "resources/json/jsonp.json");    

/*        if($this->input->get()) {
            $this->authentication($this->input->get(), $this->input->get('hash'));
        }    */  

    }

    public function index()
    {
        $this->load->view("cmshome");
    }

    public function setBrandSection()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $brandId = $this->input->get("brandId");        

        $map->brandSection->brandId[$index] = $brandId;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }          
    }

    public function otherCategories()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $categorySlug = $this->input->get("categorySlug");        

        $map->categoryNavigation->otherCategories->categorySlug[$index] = $categorySlug;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    }

    public function setSellerSectionProductPanel()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $slug = $this->input->get("slug");        

        $map->sellerSection->productPanel[$index]->slug = $slug;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    }

    public function setProductPanel()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $productPanelIndex = (int)$this->input->get("productPanelIndex");        
        $slug = $this->input->get("slug");        

        $map->categorySection[$index]->productPanel[$productPanelIndex]->slug = $slug;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    }


    public function categorySectionHead()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $categorySlug = $this->input->get("categorySlug");        
        $subIndex = (int)$this->input->get("subIndex");        
        $sub = $this->input->get("sub");        
        $target = $this->input->get("target"); 

        $map->categorySection[$index]->categorySlug = $categorySlug;
        $map->categorySection[$index]->sub[$subIndex]->text = $sub;
        $map->categorySection[$index]->sub[$subIndex]->target = $target;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    }


    public function setMainCategories()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $value = $this->input->get("value");

        $map->categoryNavigation->category[$index]->categorySlug = $value;
        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    }

    public function setSubCategories()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $subIndex = (int)$this->input->get("subIndex");
        $value = $this->input->get("value");

        $map->categoryNavigation->category[$index]->sub->categorySubSlug[$subIndex] = $value;
        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    }

    public function addSubCategories()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $value = $this->input->get("value");

        $string = $this->xmlCmsService->getString("categorySubSlug",$value, "", "", ""); 

        $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/categoryNavigation/category['.$index.']/sub/categorySubSlug[last()]',"\t\t\t\t","\n");    
        if($addXml === TRUE) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }
    } 

    public function setOtherCategories()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $value = $this->input->get("value");

        $map->categoryNavigation->otherCategories->categorySlug[$index] = $value;
        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    }     

    public function addSliderSection()  
    {
        $target = $this->input->get("target");
        $index = (int)$this->input->get("index");

        $filename = date('yhmdhs');
        $file_ext = explode('.', $_FILES['myfile']['name']);
        $file_ext = strtolower(end($file_ext));  
        $path_directory = 'assets/cms/home';

        $this->upload->initialize(array( 
            "upload_path" => $path_directory,
            "overwrite" => FALSE, 
            "encrypt_name" => FALSE,
            "file_name" => $filename,
            "remove_spaces" => TRUE,
            "allowed_types" => "jpg|jpeg|png|gif", 
            "xss_clean" => FALSE
        )); 
        
        if ( ! $this->upload->do_upload("myfile")) {
            $error = array('error' => $this->upload->display_errors());
                     return $this->output
                            ->set_content_type('application/json')
                            ->set_output($error);
        } 
        else {
            echo 'here';
            $value = $filename.'.'.$file_ext; 
            $string = $this->xmlCmsService->getString("sliderSection", "test", "test", "", "");            
            echo $this->input->get("target");
            exit();
            $addXml = $this->xmlCmsService->addXml($this->file,$string,'/map/sliderSection/slide['.$index.']/image[last()]');
            if($addXml === TRUE) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json); 
            }   
        }

    }


}