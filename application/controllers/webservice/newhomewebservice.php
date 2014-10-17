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
        $this->file  = APPPATH . "resources/". $this->xmlFileService->getHomeXMLfile().".xml"; 
        $this->json = file_get_contents(APPPATH . "resources/json/jsonp.json");    

        if($this->input->get()) {
            $this->authentication($this->input->get(), $this->input->get('hash'));
        }    
    }

    /**
     *  Removes mainSlides
     *  @return JSON
     */
    public function removeContent() 
    {    
        $index =  $this->input->get("index");
        $subIndex =  $this->input->get("subIndex");        
        $nodename =  $this->input->get("nodename");        

        $index = $index == 0 ? 1 : $index + 1;
        $subIndex = $subIndex == 0 ? 1 : $subIndex + 1;

        $remove = $this->xmlCmsService->removeXmlNode($this->file,$nodename,$index, $subIndex);
        if($remove == true) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->json);
        }            
    }

    /**
     *  Method that handles edit for brands node 
     *  @return JSON
     */
    public function setBrands()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $value = $this->input->get("value");        

        $map->brandSection->brandId[$index] = $value;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    }

    /**
     *  Method that handles add for topSellers node 
     *  @return JSON
     */
    public function addBrands()
    {
        $map = simplexml_load_file($this->file);

        $value = $this->input->get("value");
        $string = $this->xmlCmsService->getString("addBrands",$value, "", "", ""); 

        $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/brandSection/brandId[last()]',"\t\t","\n");

        if($addXml === TRUE) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }   
    } 

    /**
     *  Method that handles add,edit,delete for topSellers node 
     *  @return JSON
     */
    public function setTopSellers()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $value = $this->input->get("value");        

        $map->menu->topSellers->seller[$index] = $value;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    } 

    /**
     *  Method that handles add for topSellers node 
     *  @return JSON
     */
    public function addTopSellers()
    {
        $map = simplexml_load_file($this->file);

        $value = $this->input->get("value");
        $string = $this->xmlCmsService->getString("addTopSellers",$value, "", "", ""); 

        $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/menu/topSellers/seller[last()]',"\t\t\t","\n");

        if($addXml === TRUE) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }   
    } 

    /**
     *  Method that handles add for topProducts node 
     *  @return JSON
     */
    public function addTopProducts()
    {
        $map = simplexml_load_file($this->file);

        $value = $this->input->get("value");
        $string = $this->xmlCmsService->getString("addTopProducts",$value, "", "", ""); 

        $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/menu/topProducts/product[last()]',"\t\t\t","\n");

        if($addXml === TRUE) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }   
    } 
    /**
     *  Method that handles add,edit,delete for topProducts node 
     *  @return JSON
     */
    public function setTopProducts()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $value = $this->input->get("value");        

        $map->menu->topProducts->product[$index] = $value;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    }      


    /**
     *  Method that handles add for newArrival node 
     *  @return JSON
     */
    public function addNewArrival()
    {
        $map = simplexml_load_file($this->file);

        $value = $this->input->get("value");
        $target = $this->input->get("target");
        $string = $this->xmlCmsService->getString("newArrivals",$value, "", "", $target); 

        $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/menu/newArrivals/arrival[last()]',"\t\t\t","\n");

        if($addXml === TRUE) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }   
    } 

    /**
     *  Method that handles add,edit,delete for newArrival node 
     *  @return JSON
     */
    public function setNewArrival()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $value = $this->input->get("value");        
        $target = $this->input->get("target");        

        $map->menu->newArrivals->arrival[$index]->text = $value;
        $map->menu->newArrivals->arrival[$index]->target = $target;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    }     

    /**
     *  Method to display the contents of the home_files.xml from the function call from Easyshop.ph.admin
     *
     *  @return string
     */
    public function getContents() 
    {
        $this->output
            ->set_content_type('text/plain') 
            ->set_output(file_get_contents($this->file));
    }    

    /**
     *  Method that handles add,edit,delete for othercategories node 
     *  @return JSON
     */
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

    /**
     *  Method that handles add for othercategories node 
     *  @return JSON
     */
    public function addOtherotherCategories()
    {
        $map = simplexml_load_file($this->file);

        $value = $this->input->get("value");
        $string = $this->xmlCmsService->getString("otherCategories",$value, "", "", ""); 

        $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/categoryNavigation/otherCategories/categorySlug[last()]',"\t\t\t","\n");

        if($addXml === TRUE) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }   
    } 

    /**
     *  Add product panel node under sellerSection parent node
     *  @return JSON
     */
    public function addProductPanel()
    {
        $map = simplexml_load_file($this->file);

        $value = $this->input->get("value");
        $string = $this->xmlCmsService->getString("productPanelNew",$value, "", "", ""); 
        $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/sellerSection/productPanel[last()]',"\t\t","\n");    
        if($addXml === TRUE) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }
    }

    /**
     *  Adds ads nodes under adSection parent node
     *  @return JSON
     */
    public function addAdds()
    {
        $index = (int)$this->input->get("index");
        $target = $this->input->get("target");

        $filename = date('yhmdhs');
        $file_ext = explode('.', $_FILES['myfile']['name']);
        $file_ext = strtolower(end($file_ext));  
        $path_directory = 'assets/images';
        $map = simplexml_load_file($this->file);
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
            $value = "/".$path_directory."/".$filename.'.'.$file_ext; 
            $string = $this->xmlCmsService->getString("adsSection", $value, "", "", $target);      

            $index = $index == 0 ? 1 : $index + 1;
            $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/adSection/ad[last()]',"\t\t","\n");

            if($addXml === TRUE) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json); 
            }   
        } 
    }    

    /**
     *  Sets ads section
     *  @return JSON
     */
    public function setAdsSection()
    {
        $index = (int)$this->input->get("index");
        $target = $this->input->get("target");
        $map = simplexml_load_file($this->file);

        if(!empty($_FILES['myfile']['name'])) {
            $filename = date('yhmdhs');
            $file_ext = explode('.', $_FILES['myfile']['name']);
            $file_ext = strtolower(end($file_ext));  
            $path_directory = 'assets/images';
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
                $value = "/".$path_directory."/".$filename.'.'.$file_ext; 
                $map->adSection->ad[$index]->img = $value;
                $map->adSection->ad[$index]->target = $target;
 
            }
        }
        else {
            $map->adSection->ad[$index]->img = $map->adSection->ad[$index]->img;
            $map->adSection->ad[$index]->target = $target; 
        }

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }          
        
    }

    /**
     *  Modifies position of ads node
     *  @return JSON
     */
    public function setPositionAdsSection()
    {
        $map = simplexml_load_file($this->file);
        $index = (int)$this->input->get("index");        
        $order = (int)$this->input->get("order");     

        $tmpImage = (string) $map->adSection->ad[$order]->img;
        $tmpTarget = (string) $map->adSection->ad[$order]->target;

        $map->adSection->ad[$order]->img =  $map->adSection->ad[$index]->img;
        $map->adSection->ad[$order]->target =  $map->adSection->ad[$index]->target;

        $map->adSection->ad[$index]->img =  $tmpImage; 
        $map->adSection->ad[$index]->target =  $tmpTarget; 
        
        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }                     
    }

    /**
     *  Modifies position of product panel under seller node
     *  @return JSON
     */
    public function setPositionProductPanel()
    {
        $map = simplexml_load_file($this->file);
        $index = (int)$this->input->get("index");        
        $order = (int)$this->input->get("order");     

        $slug = (string) $map->sellerSection->productPanel[$order]->slug;

        $map->sellerSection->productPanel[$order]->slug =  $map->sellerSection->productPanel[$index]->slug;

        $map->sellerSection->productPanel[$index]->slug =  $slug; 
        
        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }                     
    }

    /**
     *  Modifies position of product panel under seller node
     *  @return JSON
     */
    public function setSellerProductPanel()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $slug = $this->input->get("value");        

        $map->sellerSection->productPanel[$index]->slug = $slug;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    }    

    /**
     *  Set categorySlug under categoryNavigation parent node
     *  @return JSON
     */
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

    /**
     *  Sets categorySubSlug under categoryNavigation parent node
     *  @return JSON
     */
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

    /**
     *  Adds categorySubSlug node under categoryNavigation parent node
     *  @return JSON
     */
    public function addSubCategories()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $value = $this->input->get("value");
        $string = $this->xmlCmsService->getString("categorySubSlug",$value, "", "", ""); 
        $index = $index == 0 ? 1 : $index + 1;  
        $addXml = $this->xmlCmsService->addXml($this->file,$string,'/map/categoryNavigation/category['.$index.']/sub/categorySubSlug[last()]');    
        if($addXml === TRUE) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }
    }   

    /**
     *  Sets subCategory node under parent categorySection node
     *  @return JSON
     */
    public function setSubCategoriesSection()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $subIndex = (int) $this->input->get("subIndex");
        $text = $this->input->get("text");
        $target = $this->input->get("target");

        $map->categorySection[$index]->sub[$subIndex]->text = $text;
        $map->categorySection[$index]->sub[$subIndex]->target = $target;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }  
    }    

    /**
     *  Sets productPanel under categorySection parent node
     *  @return JSON
     */
    public function setCategoryProductPanel()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $subIndex = (int)$this->input->get("subindex");
        $value = $this->input->get("value");

        $map->categorySection[$index]->productPanel[$subIndex]->slug = $value;
        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        } 
    }  

    /**
     *  Adds categorySection parent node
     *  @return JSON
     */
    public function addCategorySection()
    {
        $map = simplexml_load_file($this->file);
        $value = $this->input->get("value");  
        $string = $this->xmlCmsService->getString("categorySectionAdd",$value, "", "", ""); 
        $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/categorySection[last()]',"\t","\n");    
        if($addXml === TRUE) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }        
              
    }

    /**
     *  Add productPanel node under categorySection parent node
     *  @return JSON
     */
    public function addCategoryProductPanel()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $value = $this->input->get("value");
        $string = $this->xmlCmsService->getString("productPanelNew",$value, "", "", ""); 
        $index = $index == 0 ? 1 : $index + 1;  

        $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/categorySection['.$index.']/productPanel[last()]',"\t\t","\n");    
        if($addXml === TRUE) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }
    }      

    /**
     *  Adds sub node under categorySection parent node
     *  @return JSON
     */
    public function addSubCategorySection()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $value = $this->input->get("subCategoryText");
        $target = $this->input->get("subCategorySectionTarget");
        $string = $this->xmlCmsService->getString("subCategorySection",$value, "", "", $target); 
        $index = $index == 0 ? 1 : $index + 1;  
        $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/categorySection['.$index.']/sub[last()]',"\t\t","\n");    
        if($addXml === TRUE) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }
    }        

    /**
     *  Sets position of productPanel nodes under categorySection parent node
     *  @return JSON
     */
    public function setPositionCategoryProductPanel()
    {
        $map = simplexml_load_file($this->file);
        $order = (int) $this->input->get("order");  
        $index = (int)  $this->input->get("index");  
        $subIndex = (int) $this->input->get("subIndex"); 

        $tempSlug = (string)  $map->categorySection[$index]->productPanel[$order]->slug;

        $map->categorySection[$index]->productPanel[$order]->slug =  $map->categorySection[$index]->productPanel[$subIndex]->slug;

        $map->categorySection[$index]->productPanel[$subIndex]->slug =  $tempSlug;
    
        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }   
    }

    /**
     *  Sets seller's slug,logo, banner
     *  @return JSON
     */
    public function setSellerHead()
    {
        $map = simplexml_load_file($this->file);        
        $action = $this->input->get("action");
        $slug = $this->input->get("slug");
        if($action == "slug") {

            $map->sellerSection->sellerSlug = $slug;
            if($map->asXML($this->file)) {
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
            } 
        }
        else {
            $filename = date('yhmdhs');
            $file_ext = explode('.', $_FILES['myfile']['name']);
            $file_ext = strtolower(end($file_ext));  
            $path_directory = 'assets/images/';

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
                $value = "/".$path_directory.$filename.'.'.$file_ext; 

                if($action == "logo") {
                    $map->sellerSection->sellerLogo = $value;
                }
                else  {
                    $map->sellerSection->sellerBanner = $value;
                }

                if($map->asXML($this->file)) {
                    return $this->output
                            ->set_content_type('application/json')
                            ->set_output($this->json);
                } 
            }
        }
         
    }


    /**
     *  Adds slide nodes under sliderSection parent node
     *  @return JSON
     */
    public function addSliderSection()  
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $template = $this->input->get("template");
        $string = $this->xmlCmsService->getString("sliderSection",$template, "", "", ""); 
        $index = $index == 0 ? 1 : $index + 1;  
        $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/sliderSection/slide[last()]', "\t\t","\n");    
        if($addXml === TRUE) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }
    }    

    /**
     *  Sets slide of slide nodes under sliderSection parent node
     *  @return JSON
     */
    public function editSubSlider()
    {
        $index = (int)$this->input->get("index");
        $subIndex = (int)$this->input->get("subIndex");
        $target = $this->input->get("target");
        $value = $this->input->get("value");
        $map = simplexml_load_file($this->file);        

        if(!empty($_FILES['myfile']['name'])) {
            $filename = date('yhmdhs');
            $file_ext = explode('.', $_FILES['myfile']['name']);
            $file_ext = strtolower(end($file_ext));  
            $path_directory = 'assets/images/homeslider';

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
                $value = "/assets/images/homeslider/".$filename.'.'.$file_ext; 
                $map->sliderSection->slide[$index]->image[$subIndex]->path = $value;
                $map->sliderSection->slide[$index]->image[$subIndex]->target = $target;

            }
        }
        else {
            $map->sliderSection->slide[$index]->image[$subIndex]->path = $map->sliderSection->slide[$index]->image[$subIndex]->path;
            $map->sliderSection->slide[$index]->image[$subIndex]->target = $target;
        }
        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }           



    }  

    /**
     *  Set slider Design Template
     *  @return JSON
     */
    public function setSliderDesignTemplate()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $value = $this->input->get("value");        

        $map->sliderSection->slide[$index]->template = $value;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    }         

   /**
     *  Sets position of slide nodes under sliderSection parent node
     *  @return JSON
     */
    public function setSliderPosition()
    {
        $map = simplexml_load_file($this->file);
        $order = (int) $this->input->get("order");  
        $index = (int)  $this->input->get("index");  
        $subIndex = (int) $this->input->get("subIndex"); 

        $tempPath = (string) $map->sliderSection->slide[$index]->image[$order]->path;
        $tempTarget = (string) $map->sliderSection->slide[$index]->image[$order]->target;

        $map->sliderSection->slide[$index]->image[$order]->path =  $map->sliderSection->slide[$index]->image[$subIndex]->path;
        $map->sliderSection->slide[$index]->image[$order]->target = $map->sliderSection->slide[$index]->image[$subIndex]->target;

        $map->sliderSection->slide[$index]->image[$subIndex]->path =  $tempPath;
        $map->sliderSection->slide[$index]->image[$subIndex]->target =  $tempTarget;
    
        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }         
    }    

    /**
     *  Adds slider under sliderSection node
     *  @return JSON
     */
    public function addSubSlider()
    {
        $index = (int)$this->input->get("index");
        $target = $this->input->get("target");

        $filename = date('yhmdhs');
        $file_ext = explode('.', $_FILES['myfile']['name']);
        $file_ext = strtolower(end($file_ext));  
        $path_directory = 'assets/images/homeslider';
        $map = simplexml_load_file($this->file);
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
            $value = "/assets/images/homeslider/".$filename.'.'.$file_ext; 
            $string = $this->xmlCmsService->getString("subSliderSection", $value, "", "", $target);      
            if($map->sliderSection->slide[$index]->image->path == "unavailable_product_img.jpg" && $map->sliderSection->slide[$index]->image->target == "/") {
                $map->sliderSection->slide[$index]->image->path = $value;
                $map->sliderSection->slide[$index]->image->target = $target;
                if($map->asXML($this->file)) {
                    return $this->output
                            ->set_content_type('application/json')
                            ->set_output($this->json);
                }   
            }
            else {
                $index = $index == 0 ? 1 : $index + 1;
                $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/sliderSection/slide['.$index.']/image[last()]',"\t\t\t","\n");

            }
            if($addXml === TRUE) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json); 
            }   
        }
    }
}



