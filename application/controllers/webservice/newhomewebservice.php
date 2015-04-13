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

    /**
     * The Mobile XML resource
     */    
    private $defaultIndex = 0;    

    /**
     * Handles if the request is authenticated
     * @var bool
     */    
    private $isAuthenticated = false; 

    public function __construct() 
    {
        parent::__construct();

        $this->xmlCmsService = $this->serviceContainer['xml_cms'];
        $this->xmlFileService = $this->serviceContainer['xml_resource'];
        $this->em = $this->serviceContainer['entity_manager'];
        $this->authenticateRequest = $this->serviceContainer['webservice_manager'];
        $this->file  = APPPATH . "resources/". $this->xmlFileService->getHomeXmlFile().".xml"; 
        $this->tempHomefile  = APPPATH . "resources/". $this->xmlFileService->getTempHomeXMLfile().".xml"; 
        $this->slugerrorjson = file_get_contents(APPPATH . "resources/json/slugerrorjson.json");        
        $this->json = file_get_contents(APPPATH . "resources/json/jsonp.json");    
        $this->usererror = file_get_contents(APPPATH . "resources/json/usererrorjson.json");        

        if($this->input->get()) {        
            $this->isAuthenticated = $this->authenticateRequest->authenticate($this->input->get(), 
                                                                              $this->input->get('hash'),
                                                                              true);
            if(!$this->isAuthenticated) {
                echo json_encode( ['error' => 'You are not authorized to make this changes.' ] );
                exit();
            }            
        }
    }

    /**
     * Returns Assets Link
     * @return JSONP
     */
    public function getAssetsLink()
    {
        $this->config->load('assets', true);
        echo trim($this->config->item('assetsBaseUrl', 'assets'));             
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
        $subPanelIndex = ($this->input->get("subpanelindex")) ? $this->input->get("subpanelindex") : null;          
        if($nodename == "subSliderSection" || $nodename == "mainSliderSection") {
            $this->file = $this->tempHomefile;
        }
        $remove = $this->xmlCmsService->removeXmlNode($this->file, $nodename,$index, $subIndex, $subPanelIndex);
        if($remove) {
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

        if($addXml === true) {
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

        $sellerResult = $this->em->getRepository('EasyShop\Entities\EsMember')
                    ->findBy(['slug' => $value]);
        if($sellerResult) {
            $map->menu->topSellers->seller[$index] = $value;

            if($map->asXML($this->file)) {
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
            }             
        }
        else {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->usererror);         
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

        $sellerResult = $this->em->getRepository('EasyShop\Entities\EsMember')
                    ->findBy(['slug' => $value]);
        if($sellerResult) {
            $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/menu/topSellers/seller[last()]',"\t\t\t","\n");

            if($addXml === true) {
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
            }            
        }
        else {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->usererror);            
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

        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $value]);
                        
        if(!$product){
            return $this->output
                ->set_content_type('application/json')
                ->set_output( $this->slugerrorjson);
        }
        else {
            $string = $this->xmlCmsService->getString("addTopProducts",$value, "", "", ""); 

            $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/menu/topProducts/product[last()]',"\t\t\t","\n");

            if($addXml === true) {
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
            }               
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
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $value]);
                        
        if(!$product){
            return $this->output
                ->set_content_type('application/json')
                ->set_output( $this->slugerrorjson);
        }
        else {
            $map->menu->topProducts->product[$index] = $value;

            if($map->asXML($this->file)) {
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
            }   
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

        if($addXml === true) {
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
     *  Sets Brand Section
     *  @return JSON
     */
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

        if($addXml === true) {
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
        $string = $this->xmlCmsService->getString("productPanel",$value, "", "", ""); 
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $value]);
                        
        if(!$product){
            return $this->output
                ->set_content_type('application/json')
                ->set_output( $this->slugerrorjson);
        }
        else {
            $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/sellerSection/productPanel[last()]',"\t\t","\n");    
            if($addXml === true) {
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
            }   
        }


    }

    /**
     *  Adds ads nodes under adSection parent node
     *  @return JSON
     */
    public function addAdSection()
    {
        $awsUploader = $this->serviceContainer['aws_uploader'];         
        $imgDimensions = [
            'x' => $this->input->get('x'),
            'y' => $this->input->get('y'),
            'w' => $this->input->get('w'),
            'h' => $this->input->get('h')
        ];         
        $this->config->load("image_path");             
        $index = (int)$this->input->get("index");
        $target = $this->input->get("target");
        $filename = date('yhmdhs');
        $file_ext = explode('.', $_FILES['myfile']['name']);
        $file_ext = strtolower(end($file_ext));  
        $path_directory = $this->config->item('ads_img_directory');
        $map = simplexml_load_file($this->file);
        $this->upload->initialize([ 
            "upload_path" => $path_directory,
            "overwrite" => false, 
            "encrypt_name" => false,
            "file_name" => $filename,
            "remove_spaces" => true,
            "allowed_types" => "jpg|jpeg|png|gif", 
            "xss_clean" => false
        ]); 
        
        if ( ! $this->upload->do_upload("myfile")) {
            $error = ['error' => $this->upload->display_errors()];
                     return $this->output
                                 ->set_content_type('application/json')
                                 ->set_output(json_encode($error));
        } 
        else {
            $uploadData = $this->upload->data();  
            $imageData = $this->upload->data();            
            $value = $path_directory.$filename.'.'.$file_ext; 
        
            $imgDirectory = $path_directory.$filename.'.'.$file_ext;

            $this->config->load('image_dimensions', true);
            $imageDimensionsConfig = $this->config->config['image_dimensions'];


            if($imgDimensions['w'] > 0 && $imgDimensions['h'] > 0){       
                $this->cropImage($imgDirectory, $imgDimensions);
            }

            if( $imageData['image_width'] !== $imageDimensionsConfig["cmsImagesSizes"]["adsImage"][0] 
                || $imageData['image_height'] !== $imageDimensionsConfig["cmsImagesSizes"]["adsImage"][1] ){    
                $imageUtility = $this->serviceContainer['image_utility'];     
                $imageUtility->imageResize($imgDirectory, $imgDirectory, $imageDimensionsConfig["cmsImagesSizes"]["adsImage"], false);                           
            }


            $string = $this->xmlCmsService->getString("adsSection", $value, "", "", $target);

            $index = $index == 0 ? 1 : $index + 1;
            $result = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/adSection/ad[last()]',"\t\t","\n");

            if(strtolower(ENVIRONMENT) !== 'development' && $result){
                $result = $awsUploader->uploadFile($uploadData['full_path'],  $value);
            } 

            if($result) {

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
        $awsUploader = $this->serviceContainer['aws_uploader'];          
        $imgDimensions = [
            'x' => $this->input->get('x'),
            'y' => $this->input->get('y'),
            'w' => $this->input->get('w'),
            'h' => $this->input->get('h')
        ];                   
        $index = (int)$this->input->get("index");
        $target = $this->input->get("target");
        $map = simplexml_load_file($this->file);
        $this->config->load("image_path"); 
        if(!empty($_FILES['myfile']['name'])) {
            $filename = date('yhmdhs');
            $file_ext = explode('.', $_FILES['myfile']['name']);
            $file_ext = strtolower(end($file_ext));  
            $path_directory = $this->config->item('ads_img_directory');
            $this->upload->initialize([ 
                "upload_path" => $path_directory,
                "overwrite" => false, 
                "encrypt_name" => false,
                "file_name" => $filename,
                "remove_spaces" => true,
                "allowed_types" => "jpg|jpeg|png|gif", 
                "xss_clean" => false
            ]); 
            if ( ! $this->upload->do_upload("myfile")) {
                $error = ['error' => $this->upload->display_errors()];
                         return $this->output
                                     ->set_content_type('application/json')
                                     ->set_output(json_encode($error));
            } 
            else {
                $uploadData = $this->upload->data();                  
                $this->config->load('image_dimensions', true);
                $imageDimensionsConfig = $this->config->config['image_dimensions'];

                $imageData = $this->upload->data();                             
                $value = $path_directory.$filename.'.'.$file_ext; 
            
                $imgDirectory = $path_directory.$filename.'.'.$file_ext;

                if($imgDimensions['w'] > 0 && $imgDimensions['h'] > 0){       
                    $this->cropImage($imgDirectory, $imgDimensions);
                }

                if( $imageData['image_width'] !== $imageDimensionsConfig["cmsImagesSizes"]["adsImage"][0] || $imageData['image_height'] !== $imageDimensionsConfig["cmsImagesSizes"]["adsImage"][1] ){    
                    $imageUtility = $this->serviceContainer['image_utility'];
                    $imageUtility->imageResize($imgDirectory, $imgDirectory, $imageDimensionsConfig["cmsImagesSizes"]["adsImage"], false);
                }

                $map->adSection->ad[$index]->img = $value;
                $map->adSection->ad[$index]->target = $target;
 
            }
        }
        else {
            $map->adSection->ad[$index]->img = $map->adSection->ad[$index]->img;
            $map->adSection->ad[$index]->target = $target; 
        }
        $result = $map->asXML($this->file);
        if(strtolower(ENVIRONMENT) !== 'development' && $result){
            $result = $awsUploader->uploadFile($uploadData['full_path'],  $value);
        } 

        if($result) {
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

        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $slug]);
                        
        if(!$product){
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output( $this->slugerrorjson);
        }
        else {
            $map->sellerSection->productPanel[$index]->slug = $slug;

            if($map->asXML($this->file)) {
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
            }  
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
        $string = $this->xmlCmsService->getString("categorySubSlug",$value); 
        $index = $index == 0 ? 1 : $index + 1;  
        $addXml = $this->xmlCmsService->addXml($this->file,$string,'/map/categoryNavigation/category['.$index.']/sub/categorySubSlug[last()]');    
        if($addXml === true) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }
    }   

    /**
     *  Sets subCategory node under parent categorySection node
     *  @return JSON
     */
    public function setSubCategorySection()
    {
        $map = simplexml_load_file($this->file);

        $index = (int)$this->input->get("index");
        $subIndex = (int) $this->input->get("subIndex");
        $text = $this->input->get("value");

        $map->categorySection[$index]->sub[$subIndex]->text = $text;

        if($map->asXML($this->file)) {
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
        }  
    } 

    /**
     * Sets main category section slug
     */
    public function setCategorySection()
    {
        $map = simplexml_load_file($this->file); 
        $index = (int) $this->input->get("index");  
        $value = $this->input->get("value");  
        $map->categorySection[$index]->categorySlug = $value;
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

        $index = (int) $this->input->get("index");
        $subIndex = (int) $this->input->get("subindex");
        $panelindex = (int) $this->input->get("subPanelIndex");
        $value = $this->input->get("value");

        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $value]);
                        
        if(!$product){
            return $this->output
                ->set_content_type('application/json')
                ->set_output( $this->slugerrorjson);            
        }
        else {
            $map->categorySection[$index]->sub[$subIndex]->productSlugs[$panelindex] = $value;
            if($map->asXML($this->file)) {
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
            }             
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
        $string = $this->xmlCmsService->getString("categorySectionAdd",$value); 
        $xPath = count($map->categorySection) > 0 ? '/map/categorySection[last()]' : '/map/adSection[last()]';
        $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,$xPath,"\t","\n");    
        if($addXml === true) {
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
        $subindex = (int)$this->input->get("subindex");        
        $value = $this->input->get("value");
        $string = $this->xmlCmsService->getString("productPanelNew",$value, "", "", ""); 
        $index = $index == 0 ? 1 : $index + 1;  
        $subindex = $subindex == 0 ? 1 : $subindex + 1;        
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $value]);
                        
        if(!$product){
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output( $this->slugerrorjson);
        }
        else {
            if(count($map->categorySection[$index-1]->sub[$subindex-1]->productSlugs) <= 1 &&
                trim($map->categorySection[$index-1]->sub[$subindex-1]->productSlugs[0]) === "") {
                $map->categorySection[$index-1]->sub[$subindex-1]->productSlugs[0]= $value;
                $addXml = $map->asXML($this->file); 
            }        
            else {
                $addXml = $this->xmlCmsService->addXmlFormatted($this->file,
                                                                $string,
                                                                '/map/categorySection['.$index.']/sub['.$subindex.']/productSlugs[last()]',
                                                                "\t\t\t",
                                                                "\n");    
            }    
            if($addXml === true) {
                return $this->output
                            ->set_content_type('application/json')
                            ->set_output($this->json);
            }
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
        $string = $this->xmlCmsService->getString("subCategorySection",$value, "", "", ""); 
        if(count($map->categorySection[$index]->sub) > 0 ) {
            $index = $index == 0 ? 1 : $index + 1;  
            $xmlTarget = '/map/categorySection['.$index.']/sub[last()]';            
        }
        else {
            $index = $index == 0 ? 1 : $index + 1;              
            $xmlTarget = '/map/categorySection['.$index.']/categorySlug[last()]';
        }        
        $addXml = $this->xmlCmsService->addXmlFormatted($this->file,$string,$xmlTarget,"\t\t","\n");    
        if($addXml) {
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
        $subPanelIndex = (int) $this->input->get("subpanelindex"); 

        $tempSlug = (string) $map->categorySection[$index]->sub[$subIndex]->productSlugs[$order];

        $map->categorySection[$index]->sub[$subIndex]->productSlugs[$order] = $map->categorySection[$index]->sub[$subIndex]->productSlugs[$subPanelIndex];

        $map->categorySection[$index]->sub[$subIndex]->productSlugs[$subPanelIndex] =  $tempSlug;
    
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
        $awsUploader = $this->serviceContainer['aws_uploader'];        
        $map = simplexml_load_file($this->file);        
        $action = $this->input->get("action");
        $slug = $this->input->get("slug");
        if($action === "slug") {
            $sellerResult = $this->em->getRepository('EasyShop\Entities\EsMember')
                        ->findBy(['slug' => $slug]);
            if($sellerResult) {
                $map->sellerSection->sellerSlug = $slug;
                if($map->asXML($this->file)) {
                    return $this->output
                            ->set_content_type('application/json')
                            ->set_output($this->json);
                } 
            }
            else {
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->usererror);
            }
        }
        else if ($action === "deleteLogo"){
            $map->sellerSection->sellerLogo = '';
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
            $this->config->load("image_path");                 
            $path_directory = $this->config->item('partner_img_directory').($action === "logo" ? "/" : "/banners/");

            $this->upload->initialize([ 
                "upload_path" => $path_directory,
                "overwrite" => false, 
                "encrypt_name" => false,
                "file_name" => $filename,
                "remove_spaces" => true,
                "allowed_types" => "jpg|jpeg|png|gif", 
                "xss_clean" => false
            ]); 
            if ( ! $this->upload->do_upload("myfile")) {
                $error = ['error' => $this->upload->display_errors()];
                         return $this->output
                                ->set_content_type('application/json')
                                ->set_output(json_encode($error));
            } 
            else {
                $uploadData = $this->upload->data();                
                $value = "/".$path_directory.$filename.'.'.$file_ext; 

                if($action == "logo") {
                    $map->sellerSection->sellerLogo = $value;
                }
                else  {
                    $map->sellerSection->sellerBanner = $value;
                }
                $result = $map->asXML($this->file);
                if(strtolower(ENVIRONMENT) !== 'development' && $result){
                    $result = $awsUploader->uploadFile($uploadData['full_path'],  ltrim($value,"/"));
                }                 
                if($result) {
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
        $map = simplexml_load_file($this->tempHomefile);

        $index = (int)$this->input->get("index");
        $template = $this->input->get("template");
        $string = $this->xmlCmsService->getString("sliderSection",$template, " "); 
        $index = $index === 0 ? 1 : $index + 1;  

        $this->config->load('image_dimensions', true);        
        $imageDimensionsConfig = $this->config->config['image_dimensions'];        
        $defaultTemplateSliderCount = count($imageDimensionsConfig["cmsImagesSizes"]["mainSlider"]["$template"]);   
        $addXml = $this->xmlCmsService->addXmlFormatted($this->tempHomefile,
                                                        $string,'/map/sliderSection/slide[last()]', 
                                                        "\t\t",
                                                        "\n");  

        return $this->output
                ->set_content_type('application/json')
                ->set_output($this->json);
    }    

    /**
     *  Sets slide of slide nodes under sliderSection parent node
     *  @return JSON
     */
    public function editSubSlider()
    {
        $awsUploader = $this->serviceContainer['aws_uploader'];        
        $imgDimensions = [
            'x' => $this->input->get('x'),
            'y' => $this->input->get('y'),
            'w' => $this->input->get('w'),
            'h' => $this->input->get('h')
        ];   
        $index = (int)$this->input->get("index");
        $subIndex = (int)$this->input->get("subIndex");
        $target = $this->input->get("target");
        $value = $this->input->get("value");
        $map = simplexml_load_file($this->tempHomefile);        
        if(!empty($_FILES['myfile']['name'])) {
            $this->config->load("image_path"); 
            $filename = date('yhmdhs');
            $file_ext = explode('.', $_FILES['myfile']['name']);
            $file_ext = strtolower(end($file_ext));  
            $path_directory = $this->config->item('homeslider_img_directory');

            $this->upload->initialize([ 
                "upload_path" => $path_directory,
                "overwrite" => false, 
                "encrypt_name" => false,
                "file_name" => $filename,
                "remove_spaces" => true,
                "allowed_types" => "jpg|jpeg|png|gif", 
                "xss_clean" => false
            ]); 
            if ( ! $this->upload->do_upload("myfile")) {
                $error = ['error' => $this->upload->display_errors()];
                         return $this->output
                                     ->set_content_type('application/json')
                                     ->set_output(json_encode($error));
            } 
            else {
                $uploadData = $this->upload->data();                   
                $value = "/".$this->config->item('homeslider_img_directory').$filename.'.'.$file_ext; 
                $imgDirectory = $this->config->item('homeslider_img_directory').$filename.'.'.$file_ext;

                if($imgDimensions['w'] > 0 && $imgDimensions['h'] > 0){       
                    $this->cropImage($imgDirectory, $imgDimensions);
                }
                $imageData = $this->upload->data(); 
                $template = $map->sliderSection->slide[$index]->template;
                $subSliderCount = count($map->sliderSection->slide[$index]->image);

                $this->config->load('image_dimensions', TRUE);
                $imageDimensionsConfig = $this->config->config['image_dimensions'];
                $imageUtility = $this->serviceContainer['image_utility'];
                if($subIndex >= $subSliderCount) {
                    $tempDimensions = end($imageDimensionsConfig["cmsImagesSizes"]["mainSlider"]["$template"]);
                    $imageUtility->imageResize($imgDirectory, $imgDirectory, $tempDimensions, false);
                    reset($imageDimensionsConfig["cmsImagesSizes"]["mainSlider"]["$template"]);                
                }
                else {
                    $tempDimensions = $imageDimensionsConfig["cmsImagesSizes"]["mainSlider"]["$template"][$subIndex];
                    $imageUtility->imageResize($imgDirectory, $imgDirectory, $tempDimensions, false);
                }

                $map->sliderSection->slide[$index]->image[$subIndex]->path = $value;
                $map->sliderSection->slide[$index]->image[$subIndex]->target = $target;

            }
        }
        else {
            $map->sliderSection->slide[$index]->image[$subIndex]->path = $map->sliderSection->slide[$index]->image[$subIndex]->path;
            $map->sliderSection->slide[$index]->image[$subIndex]->target = $target;
        }
        $result = $map->asXML($this->tempHomefile);
        if(strtolower(ENVIRONMENT) !== 'development' && $result){
            $result = $awsUploader->uploadFile($uploadData['full_path'],  $value);
        }         
        if($result) {
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
        $map = simplexml_load_file($this->tempHomefile);

        $index = (int)$this->input->get("index");
        $value = $this->input->get("value");        

        $map->sliderSection->slide[$index]->template = $value;

        if($map->asXML($this->tempHomefile)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }    
    }    

    /**
     *  Sets position of sliderSection->slide node
     *  @return JSON
     */
    public function setPositionParentSlider()
    {
        $template = [];
        $image = [];
        $map = simplexml_load_file($this->tempHomefile);        
        $order = (int) $this->input->get("order");  
        $index = (int)  $this->input->get("index");  
        $nodename =  $this->input->get("nodename");  
        $action = $this->input->get("action");        
        if($action == "up" && ($index !== $order)) {
            $sliderOrder = $order;
            $sliderIndex = $index;
        }
        else if($action == "down" && ($index + 1) != count($map->sliderSection->slide)){
            $sliderOrder = $index;
            $sliderIndex = $order;
        }

        $template = $map->sliderSection->slide[$sliderOrder]->template;
        foreach($map->sliderSection->slide[$sliderOrder]->image as $images) {
                $image[] = $images;
        }
        $this->xmlCmsService->removeXmlNode($this->tempHomefile,$nodename,$sliderOrder + 1); 
        $string = $this->xmlCmsService->getString("sliderSection", $template, "", "", "");      
        $this->xmlCmsService->addXmlFormatted($this->tempHomefile,$string,'/map/sliderSection/slide['.($sliderOrder + 1).']',"\t\t","\n\n");
        $this->xmlCmsService->syncSliderValues($this->tempHomefile,$image,$template,$sliderIndex,$sliderOrder);
        return $this->output
                ->set_content_type('application/json')
                ->set_output($this->json);            
    }

    /**
     *  Sets position of slide nodes under sliderSection parent node
     *  @return JSON
     */
    public function setSliderPosition()
    {
        $map = simplexml_load_file($this->tempHomefile);
        $order = (int) $this->input->get("order");  
        $index = (int)  $this->input->get("index");  
        $subIndex = (int) $this->input->get("subIndex"); 

        $tempPath = (string) $map->sliderSection->slide[$index]->image[$order]->path;
        $tempTarget = (string) $map->sliderSection->slide[$index]->image[$order]->target;

        $map->sliderSection->slide[$index]->image[$order]->path =  $map->sliderSection->slide[$index]->image[$subIndex]->path;
        $map->sliderSection->slide[$index]->image[$order]->target = $map->sliderSection->slide[$index]->image[$subIndex]->target;

        $map->sliderSection->slide[$index]->image[$subIndex]->path =  $tempPath;
        $map->sliderSection->slide[$index]->image[$subIndex]->target =  $tempTarget;
    
        if($map->asXML($this->tempHomefile)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }         
    }  

    /**
     *  Syncs the changes from new_home_page_temp.xml to new_home_page.xml
     *  @return VIEW
     */
    public function commitSliderChanges()
    {
        if($this->isAuthenticated) {
            $map = simplexml_load_file($this->tempHomefile);

            foreach ($map->sliderSection->slide as $key => $slider) {
                $sliders[] = $slider;
            }        
            $this->xmlCmsService->removeXmlNode($this->file,"tempHomeSlider");
            $this->xmlCmsService->syncTempSliderValues($this->file, $this->tempHomefile,$sliders);
            $this->fetchPreviewSlider();
        }
        else {
            return json_encode("error");
        }
    }

    /**
     *  Retrieves a partial view of the home slider
     *  @return VIEW
     */
    public function fetchPreviewSlider()
    {

        $homeContent = $this->serviceContainer['xml_cms']->getHomeData(true);

        $sliderSection = $homeContent['slider']; 
        $homeContent['slider'] = [];
        foreach($sliderSection as $slide){
            $sliderView = $this->load->view($slide['template'],$slide, true);
            array_push($homeContent['slider'], $sliderView);
        }
        $data['homeContent'] = $homeContent;
        $this->load->view('partials/sliderpreview', $data);
    }    

    /**
     *  Adds slider under sliderSection node
     *  @return JSON
     */
    public function addSubSlider()
    {
        $awsUploader = $this->serviceContainer['aws_uploader'];
        $imgDimensions = [
            'x' => $this->input->get('x'),
            'y' => $this->input->get('y'),
            'w' => $this->input->get('w'),
            'h' => $this->input->get('h')
        ];        
        $index = (int)$this->input->get("index");
        $target = $this->input->get("target");

        $filename = date('yhmdhs');
        $file_ext = explode('.', $_FILES['myfile']['name']);
        $file_ext = strtolower(end($file_ext));  
        $this->config->load("image_path");            
        $path_directory = $this->config->item('homeslider_img_directory');
        $map = simplexml_load_file($this->tempHomefile);

        $this->load->library('image_lib');    
        $this->upload->initialize([ 
            "upload_path" => $path_directory,
            "overwrite" => true, 
            "encrypt_name" => false,
            "file_name" => $filename,
            "remove_spaces" => true,
            "allowed_types" => "jpg|jpeg|png|gif", 
            "xss_clean" => false
        ]); 
        

        if ( ! $this->upload->do_upload("myfile")) {
            $error = ['error' => $this->upload->display_errors()];
                     return $this->output
                                 ->set_content_type('application/json')
                                 ->set_output(json_encode($error));
        } 
        else {
            $uploadData = $this->upload->data();            
            $value = "/".$this->config->item('homeslider_img_directory').$filename.'.'.$file_ext; 
            $subSliderCount = count($map->sliderSection->slide[$index]->image);
            $template = (string)$map->sliderSection->slide[$index]->template;            
            $string = $this->xmlCmsService->getString("subSliderSection", $value, "", "", $target);      
            if(trim($map->sliderSection->slide[$index]->image->path) === "") {
                $map->sliderSection->slide[$index]->image->path = $value;
                $map->sliderSection->slide[$index]->image->target = $target;
                $result = $map->asXML($this->tempHomefile); 
            }
            else {
                $index = $index === 0 ? 1 : $index + 1;
                $result = $this->xmlCmsService->addXmlFormatted($this->tempHomefile,
                                                                $string,
                                                                '/map/sliderSection/slide['.$index.']/image[last()]',
                                                                "\t\t\t","\n");
                $subSliderCount += 1;
            }      

            $imgDirectory = $this->config->item('homeslider_img_directory').$filename.'.'.$file_ext;

            if($imgDimensions['w'] > 0 && $imgDimensions['h'] > 0){       
                $this->cropImage($imgDirectory, $imgDimensions);
            }

            $this->config->load('image_dimensions', true);
            $imageDimensionsConfig = $this->config->config['image_dimensions'];
            $defaultTemplateSliderCount = count($imageDimensionsConfig["cmsImagesSizes"]["mainSlider"][$template]);
            $imageUtility = $this->serviceContainer['image_utility'];
            if($subSliderCount >= $defaultTemplateSliderCount) {
                $tempDimensions = end($imageDimensionsConfig["cmsImagesSizes"]["mainSlider"][$template]);
                $imageUtility->imageResize($imgDirectory, $imgDirectory, $tempDimensions, false);                
                reset($imageDimensionsConfig["cmsImagesSizes"]["mainSlider"][$template]);                
            }
            else {
                $tempDimensions = $imageDimensionsConfig["cmsImagesSizes"]["mainSlider"][$template][$subSliderCount - 1];
                $imageUtility->imageResize($imgDirectory, $imgDirectory, $tempDimensions, false);
            } 
            if(strtolower(ENVIRONMENT) !== 'development' && $result){
                $result = $awsUploader->uploadFile($uploadData['full_path'],  $value);
            }          
            if($result) {
                return $this->output
                            ->set_content_type('application/json')
                            ->set_output($this->json);             
            }             
        }
    }

    /**
     *  Handles the cropping functionality
     *  @param string $imgDirectory
     *  @param array $imgDimensions
     */
    public function cropImage($imgDirectory, $imgDimensions)
    {

        $this->load->library('image_lib');

        $img_config = [
            'source_image'      => $imgDirectory,
            'new_image'         => $imgDirectory,
            'maintain_ratio'    => false,
            'width'             => $imgDimensions['w'],
            'height'            => $imgDimensions['h'],
            'x_axis'            => $imgDimensions['x'],
            'y_axis'            => $imgDimensions['y']
        ];
        $img_config['source_image'] = $imgDirectory;
        $this->image_lib->initialize($img_config); 

        $this->image_lib->crop();
    }


    /**
     *  Method to display the contents of the new_home_files_temp.xml from the function call from Easyshop.ph.admin
     *  @return string
     */
    public function getTempContents() 
    {         
        if(!file_exists($this->tempHomefile)) {
            copy($this->file, $this->tempHomefile);
            chmod($this->tempHomefile, 0766);
        }  
        $this->output
             ->set_content_type('text/plain') 
             ->set_output(file_get_contents($this->tempHomefile));
    }     

    /**
     *  Method that handles synching sliderSection values from new_home_files.xml to new_home_files_temp.xml
     *  @return string
     */
    public function syncTempHomeFiles()
    {
        if($this->isAuthenticated) {
            $map = simplexml_load_file($this->file);

            foreach ($map->sliderSection->slide as $slider) {
                $sliders[] = $slider;
            }

            $this->xmlCmsService->removeXmlNode($this->tempHomefile, "tempHomeSlider");
            $this->xmlCmsService->syncTempSliderValues($this->tempHomefile, $this->file, $sliders);  
             
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);                        
        }
        else {
            return json_encode("error");
        }        
          
    }


    /**
     * Retrieves template dimensions for the posted index and templateName
     * @return JSON
     */
    public function getTemplateImageDimension()
    {
        $index = (int)$this->input->get("index");
        $type = $this->input->get("type");
        $currentSliderCount = (int)$this->input->get("currentSliderCount");
        $template = trim($this->input->get("template"));


        $this->config->load('image_dimensions', true);
        $imageDimensionsConfig = $this->config->config['image_dimensions'];
        $imageDimensions = $imageDimensionsConfig["cmsImagesSizes"][$type];

        if($type === "mainSlider") {
            $defaultTemplateCount = count($imageDimensions[$template]);
            if($index >= $defaultTemplateCount) {
                $dimensions = end($imageDimensions[$template]);
            }
            else {
                $dimensions = $imageDimensions[$template][$index];
            }
        }
        else {
            $dimensions = $imageDimensions;
        }

        $jsonString = "jsonCallback({'sites':[{'success': '".implode(",",$dimensions)."',},]});";
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output($jsonString);                
    }    

}



