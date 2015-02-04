<?php 

use EasyShop\Entities\EsProduct; 



class HomeWebService extends MY_Controller 
{

    /**
     *  Constructor call for Administrator's authentication. Authentication method is located in MY_Controller.php
     *
     *  $xmlCmsService used for accessing functions under application/src/Easyshop/XML/CMS.php
     *  $xmlFileService used for accessing Resource class
     *  $em entity manager injection
     */
    private $xmlCmsService;
    private $xmlFileService;
    private $em;

    public function __construct() 
    {
        parent::__construct();

        $this->xmlCmsService = $this->serviceContainer['xml_cms'];
        $this->xmlFileService = $this->serviceContainer['xml_resource'];
        $this->em = $this->serviceContainer['entity_manager'];
        $this->declareEnvironment();

        if($this->input->get()) {
            $this->authentication($this->input->get(), $this->input->get('hash'));
        }  
    }
  
    /**
     *  Environment declaration:
     *  1. APPPATH . "resources/page/home_files.xml" 
     *  2. APPPATH . "resources/json/jsonp.json"
     *  
     */
    private function declareEnvironment()
    {

        $env = strtolower(ENVIRONMENT);
        $this->file  = APPPATH . "resources/". $this->xmlFileService->getHomeXMLfile().".xml"; 
        $this->json = file_get_contents(APPPATH . "resources/json/jsonp.json");
        $this->slugerrorjson = file_get_contents(APPPATH . "resources/json/slugerrorjson.json");
        $this->boundsjson = file_get_contents(APPPATH . "resources/json/boundsjson.json");
    }

    /**
     *  Removes mainSlides/productSlides
     *
     *  @return JSON
     */
    public function removeContent() 
    {
        $map = simplexml_load_file($this->file);        
        $index =  $this->input->get("index");
        $nodeName =  $this->input->get("nodename");        
        $productindex =  $this->input->get("productindex");        
        $file = $this->file;
        $jsonFile = $this->json;        
        if($nodeName == "mainSlide") {
            if(count($map->mainSlide) > 1){
                $this->xmlCmsService->removeXML($file,$nodeName,$index);
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);                  
            }
         
        }
        else if($nodeName == "product_panel_main" || $nodeName == "product_panel") {
            if(count($map->mainSlide) > 1){
                $this->xmlCmsService->removeXMLForSetSectionMainPanel($file,$nodeName,$index,$productindex);
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);                  
            }
        }
        else {
            if(count($map->productSlide) > 1){

                $this->xmlCmsService->removeXML($file,$nodeName,$index);
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);                  
            }
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
     *  Method used to change the contents of ProductSlide_Title node under home_files.xml
     *
     *  @return JSONP
     */
    public function setProductTitle() 
    {
        $jsonFile = $this->json;
        $map = simplexml_load_file($this->file);
        
        $userid = $this->input->get("userid");
        $value =  $this->input->get("productslidetitle");
        $hash = $this->input->get("hash");

        $value = ($value == "") ? $map->productSlide_title->value : $value;

        $map->productSlide_title->value = $value;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($jsonFile);
        }
    }

    /**
     *  Method used to change the contents of productSideBanner node under home_files.xml
     *
     *  @return JSONP
     */
    public function setProductSideBanner() 
    {
        $jsonFile = $this->json;
        $map = simplexml_load_file($this->file);
        
        $userid = $this->input->get("userid");
        $value =  $this->input->get("value");
        $hash = $this->input->get("hash");
        $value = ($value == "") ? $map->productSideBanner->value : $value;

        $map->productSideBanner->value= $value;

        if($map->asXML($this->file)) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($jsonFile);
        }
    }

    /**
     *  Method used to add contents to mainSlide node under home_files.xml
     *
     *  @return JSONP
     */
    public function addMainSlide() 
    {
        $jsonFile = $this->json;
        $map = simplexml_load_file($this->file);  

        $count = count($map->mainSlide);

        $filename = date('yhmdhs');
        $index = $count;
        $userid =  $this->input->get("userid");
        $hash =  $this->input->get("hash");
        $value =  $this->input->get("value");
        $coordinate =  $this->input->get("coordinate");
        $target =  $this->input->get("target");
        
        $nodeName =  "mainSlide";
        $type = "image";
        $coordinate = ($coordinate == "") ? "0,0,0,0" : $coordinate;

        $index = (int)($index); 

        $file_ext = explode('.', $_FILES['myfile']['name']);
        $file_ext = strtolower(end($file_ext));  
        $path_directory = 'assets/images/mainslide';

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
                $data = array('upload_data' => $this->upload->data());
                $file = $this->file;
                $value = "assets/images/mainslide/".$filename.'.'.$file_ext;
                $string = $this->xmlCmsService->getString($nodeName, $value, $type, $coordinate, $target);
                $orindex = $index;
                $index = ($index == 0 ? 1 : $index);

                if($orindex == 0) {
                    $this->xmlCmsService->addXml($file,$string,'/map/mainSlide['.$index.']');
                    $this->swapXmlForAddMainSlide($file, $orindex, $index,$value,$type,$coordinate,$target);
                
                } 
                else {
                    $this->xmlCmsService->addXml($file,$string,'/map/mainSlide['.$index.']');
                }
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);
        }
    }


    /**
     *  Method used to change the contents of mainSlide node under home_files.xml
     *
     *  @return JSONP
     */
    public function setMainSlide() 
    {

        $filename = date('yhmdhs');
        $jsonFile = $this->json;
        $file = $this->file;
        $map = simplexml_load_file($file);  
        $index = $this->input->get("index");
        $userid =  $this->input->get("userid");
        $hash =  $this->input->get("hash");
        $value =  $this->input->get("value");
        $coordinate =  $this->input->get("coordinate");
        $target =  $this->input->get("target");
        $order =  $this->input->get("order");
        $nodeName =  "mainSlide";
        $type = "image";
        $coordinate = ($coordinate == "") ? "0,0,0,0" : $coordinate;
        $index = (int)($index); 
        $value = !empty($_FILES['myfile']['name']) ? $value : $map->mainSlide[$index]->value;
        if(!empty($_FILES['myfile']['name'])){

            $file_ext = explode('.', $_FILES['myfile']['name']);
            $file_ext = strtolower(end($file_ext));  
            $path_directory = 'assets/images/mainslide';
            $value = $path_directory."/".$filename.".".$file_ext;
            $this->upload->initialize(array( 
                "upload_path" => $path_directory,
                "overwrite" => FALSE, 
                "encrypt_name" => FALSE,
                "file_name" => $filename,
                "remove_spaces" => TRUE,
                "allowed_types" => "jpg|jpeg|png|gif", 
                "xss_clean" => FALSE
            ));        
            $string = $this->xmlCmsService->getString($nodeName, $value, $type, $coordinate, $target);
            if ( ! $this->upload->do_upload("myfile")) {
                $error = array('error' => $this->upload->display_errors());
                         return $this->output
                                ->set_content_type('application/json')
                                ->set_output($error);
            }  
            else {
                $map->mainSlide[$index]->value = $value;
                $map->mainSlide[$index]->imagemap->coordinate = $coordinate;
                $map->mainSlide[$index]->imagemap->target = $target;
                
                if($map->asXML($file)) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);
                } 
            }
        }
        else {
            $string = $this->xmlCmsService->getString($nodeName, $value, $type, $coordinate, $target);

            if($index > count($map->mainSlide) - 1 || $order > count($map->mainSlide) - 1 || $index < 0 || $order < 0) {
                exit("error");
            } 
            else {
                if($order == "") {
                    
                    $sxe = new SimpleXMLElement(file_get_contents($file));
                    $map->mainSlide[$index]->value = $value;
                    $map->mainSlide[$index]->imagemap->coordinate = $coordinate;
                    $map->mainSlide[$index]->imagemap->target = $target;
                    
                    if($map->asXML($file)) {
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_output($jsonFile);
                    } 
                        
                } 
                else {
                    if($index <= $order) {
                        
                        $value = ($value == "" ? $map->mainSlide[$index]->value : $value);
                        $type = ($type == "" ? $map->mainSlide[$index]->type : $type);
                        $coordinate = ($coordinate == "" ? $map->mainSlide[$index]->imagemap->coordinate : $coordinate);
                        $target = ($target == "" ? $map->mainSlide[$index]->imagemap->target : $target);

                        $index = ($index == 0 ? 1 : $index + 1);
                        $order = ($order == 0 ? 1 : $order + 1);
                        $this->xmlCmsService->addXml($file,$string,'/map/mainSlide['.$order.']');
                        $this->xmlCmsService->removeXML($file,$nodeName,$index);
                    } 
                    else  {
                        if($order == 0) {
                           
                            $value = ($value == "" ? $map->mainSlide[$index]->value : $value);
                            $type = ($type == "" ? $map->mainSlide[$index]->type : $type);
                            $coordinate = ($coordinate == "" ? $map->mainSlide[$index]->imagemap->coordinate : $coordinate);
                            $target = ($target == "" ? $map->mainSlide[$index]->imagemap->target : $target);

                            $orindex = $index;
                            $ororder = $order;
                            $index = ($index == 0 ? 1 : $index + 1);
                            $this->xmlCmsService->removeXML($file,$nodeName,$index);
                            $order = ($order == 0 ? 1 : $order);
                            $this->xmlCmsService->addXml($file,$string,'/map/mainSlide['.$order.']');
                            $two = 1;
                            $plusin = $two;
                            $plusor = $two;

                            $this->swapXmlForSetMainSlide($file, $orindex,$ororder, $plusin, $plusor, $value,$type,$coordinate,$target);
                        } 
                        else {
                    
                            $value = ($value == "" ? $map->mainSlide[$index]->value : $value);
                            $type = ($type == "" ? $map->mainSlide[$index]->type : $type);
                            $coordinate = ($coordinate == "" ? $map->mainSlide[$index]->imagemap->coordinate : $coordinate);
                            $target = ($target == "" ? $map->mainSlide[$index]->imagemap->target : $target);


                                $index = ($index == 0 ? 1 : $index + 1);
                                $this->xmlCmsService->removeXML($file,$nodeName,$index);
                                $order = ($order == 0 ? 1 : $order);
                                $this->xmlCmsService->addXml($file,$string,'/map/mainSlide['.$order.']');                 
                        }
                    }
                                return $this->output
                                ->set_content_type('application/json')
                                ->set_output($jsonFile);
                }   
            }            
        }        


    }

    /**
     *  Method used to add contents to productSlide node under home_files.xml
     *
     *  @return JSONP
     */
    public function addProductSlide() 
    {
        $jsonFile = $this->json;
        $slugerrorjson = $this->slugerrorjson;
        $boundsjson = $this->boundsjson;
        $file = $this->file;
        $map = simplexml_load_file($this->file);  
        $count = count($map->productSlide) - 1;
        $index = $count;
        $userid =  $this->input->get("userid");
        $hash =  $this->input->get("hash");
        $value =  $this->input->get("value");
        $nodeName =  "productSlide";
        $type = "product";
        $coordinate = "";
        $target = "";
        $index = (int)($index); 
        
        $value = ($value == "" ? $map->productSlide[$index]->value : $value);
    
        $string = $this->xmlCmsService->getString($nodeName, $value, $type, $coordinate, $target);
        $orindex = $index;
        $index = ($index == 0 ? 1 : $index);


        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $value]);
                        
        if(!$product){
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($slugerrorjson);
        }
        if($orindex == 0) {
            $this->xmlCmsService->addXml($file,$string,'/map/productSlide[last()]');
            $this->swapXmlForAddProductSlide($file,$orindex, $index,$value);
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);
        } 
        else {
            $this->xmlCmsService->addXml($file,$string,'/map/productSlide[last()]');
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);
        }
                    
            
    }

    /**
     *  Method used to set xml contents for section heads under home_files.xml
     *
     *  @return JSONP
     */
    public function setSectionHead() 
    {
        $jsonFile = $this->json;
        $index = $this->input->get("index");
        $userid =  $this->input->get("userid");
        $hash = $this->input->get("hash");
        $type = $this->input->get("type");
        $value =  $this->input->get("value");
        $css_class = $this->input->get("css_class");
        $title = $this->input->get("title");
        $layout = $this->input->get("layout");

        $map = simplexml_load_file($this->file);

        if($index > count($map->section) - 1 || $index < 0) {
            exit('Index out of bounds');
        } 
        else {
            $index = (int)$index;
            $type = $type == "" ? $map->section[$index]->type : $type;
            $value = $value == "" ? $map->section[$index]->value : $value;
            $css_class = $css_class == "" ? $map->section[$index]->css_class : $css_class;
            $title = $title == "" ? $map->section[$index]->title : $title;
            $layout = $layout == "" ? $map->section[$index]->layout : $layout;
            
            $map->section[$index]->type = $type;
            $map->section[$index]->value = $value;
            $map->section[$index]->css_class = $css_class;
            $map->section[$index]->title = $title;
            $map->section[$index]->layout = $layout;

            if($map->asXML($this->file)) {
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);
            }
        }
    }

    /**
     *  Method used to set xml contents for product_panel nodes under home_files.xml
     *
     *  @return JSONP
     */
    public function setSectionProduct()
    {
        $jsonFile = $this->json;
        $slugerrorjson = $this->slugerrorjson;
        $boundsjson = $this->boundsjson;
        $map = simplexml_load_file($this->file);   
                
        $index = $this->input->get("index");
        $userid = $this->input->get("userid");
        $hash = $this->input->get("hash");
        $productindex = $this->input->get("productindex");
        $type = $this->input->get("type");
        $value =  $this->input->get("myfile");


        $index = (int)$index;
        $productindex = (int)$productindex;
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $value]);
                        
        if(!$product && strtolower($type) !== "image"){
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($slugerrorjson);
        } 
        else {
            if(strtolower($type) == "image") {

                $filename = date('yhmdhs');
                $file_ext = explode('.', $_FILES['myfile']['name']);
                $file_ext = strtolower(end($file_ext));  
                $path_directory = 'assets/images/';   
                $value = $path_directory.$filename.'.'.$file_ext;    

                $this->upload->initialize(array( 
                    "upload_path" => $path_directory,
                    "overwrite" => FALSE, 
                    "encrypt_name" => FALSE,
                    "file_name" => $filename,
                    "remove_spaces" => TRUE,
                    "allowed_types" => "jpg|jpeg|png|gif", 
                    "xss_clean" => FALSE
                )); 

                if ( ! $this->upload->do_upload("myfile") && !empty($_FILES['myfile']['name'])) {
                    $error = array('error' => $this->upload->display_errors());
                             return $this->output
                                    ->set_content_type('application/json')
                                    ->set_output($error);
                }
                else {
                    $map->section[$index]->product_panel[$productindex]->value = $value;
                    $map->section[$index]->product_panel[$productindex]->type = $type;
                    if($map->asXML($this->file)) {
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_output($jsonFile);
                    }                       
                }               

            }
            else {
                    $map->section[$index]->product_panel[$productindex]->value = $value;
                    $map->section[$index]->product_panel[$productindex]->type = $type;
                    if($map->asXML($this->file)) {
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_output($jsonFile);
                    }                 

            }

        }

    }

    /**
     *  Method used to set xml contents for product_panel_main nodes under home_files.xml
     *
     *  @return JSONP
     */
    public function setSectionMainPanel()
    {
        $file = $this->file;
        $jsonFile = $this->json;
        $slugerrorjson = $this->slugerrorjson;
        $boundsjson = $this->boundsjson;

        $index = $this->input->get("index");
        $userid = $this->input->get("userid");
        $hash = $this->input->get("hash");
        $productindex = $this->input->get("productindex");
        $type = strtolower($this->input->get("type"));
        $value =  $this->input->get("myfile");
        $coordinate =  $this->input->get("coordinate");
        $target =  $this->input->get("target");
        $nodeName = "product_panel_main";
        
        $map = simplexml_load_file($file);   
        
        $index = (int)$index;
        $productindex = (int)$productindex;
        $valueForImage = !empty($_FILES['myfile']['name']) ? $value :  $map->section[$index]->product_panel_main[$productindex]->value;
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $value]);
                        
        if(!$product && strtolower($type) !== "image"){
            return $this->output
                ->set_content_type('application/json')
                ->set_output($slugerrorjson);
                exit();
        }  

                if(strtolower($type) == "image") {

                    $filename = date('yhmdhs');
                    $file_ext = explode('.', $_FILES['myfile']['name']);
                    $file_ext = strtolower(end($file_ext));  
                    $path_directory = 'assets/images/';   
                    $valueForImage = $path_directory.$filename.'.'.$file_ext;   
                    $value = !empty($_FILES['myfile']['name']) ? $valueForImage :  $map->section[$index]->product_panel[$productindex]->value;         

                    $this->upload->initialize(array( 
                        "upload_path" => $path_directory,
                        "overwrite" => FALSE, 
                        "encrypt_name" => FALSE,
                        "file_name" => $filename,
                        "remove_spaces" => TRUE,
                        "allowed_types" => "jpg|jpeg|png|gif", 
                        "xss_clean" => FALSE
                    )); 

                    if ( ! $this->upload->do_upload("myfile") && !empty($_FILES['myfile']['name']) ) {
                        $error = array('error' => $this->upload->display_errors());
                                 return $this->output
                                        ->set_content_type('application/json')
                                        ->set_output($error);
                    }   
                    else {
                        if($coordinate != "" && $target != "") {
                            
                            $coordinate = $coordinate == "" ? "0,0,0,0" : $coordinate;
                            $target = $target == "" ? "" : $target;

                            $q = $this->xmlCmsService->getString($nodeName,$value,$type,$coordinate,$target);
                            $index = ($index == 0 ? 1 : $index + 1);
                            $productindex = ($productindex == 0 ? 1 : $productindex + 1);
                            $this->xmlCmsService->addXmlChild($file,$q,'/map/section['.$index.']/product_panel_main['.$productindex.']');
                            $this->xmlCmsService->removeXMLForSetSectionMainPanel($file,$nodeName,$index,$productindex);
                            return $this->output
                                        ->set_content_type('application/json')
                                        ->set_output($jsonFile);
                                
                        } 
                        else {
                                return $this->output
                                    ->set_content_type('application/json')
                                    ->set_output(json_encode("error"));
                        }

                    }                    


                        
                } 
                else {    

                    $map->section[$index]->product_panel_main[$productindex]->value = $value;
                    $map->section[$index]->product_panel_main[$productindex]->type = $type;
                    if($map->asXML($file)) {
                        return $this->output
                                    ->set_content_type('application/json')
                                    ->set_output($jsonFile);
                    } 
                    else {
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode("error"));
                    }
                    
                }
    }

    /**
     *  Method used to add xml contents for product_panel_main nodes under home_files.xml
     *
     *  @return JSONP
     */
    public function addSectionMainPanel() 
    {

        $jsonFile = $this->json;
        $slugerrorjson = $this->slugerrorjson;
        $boundsjson = $this->boundsjson;
        $file = $this->file;
        $map = simplexml_load_file($file);   
        $index = $this->input->get("index");
        $userid = $this->input->get("userid");
        $hash = $this->input->get("hash");
        $productindex = $this->input->get("productindex");
        $type = strtolower($this->input->get("type"));
        $value =  $this->input->get("myfile");
        $coordinate =  $this->input->get("coordinate");
        $target =  $this->input->get("target");
        $nodeName = "product_panel_main";
        $type = strtolower($type);
        $index = (int)$index;
            if(!is_numeric($index) || !is_numeric($productindex) || $index > count($map->section) || $productindex > count($map->section[$index]->product_panel_main) || $index < 0 || $productindex < 0) {
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($boundsjson);
            } 
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $value]);
            if(!$product && $type != "image") {

                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($slugerrorjson);
            }             
            else {
                $productindex = (int)$productindex;
                if(strtolower($type) == "image" && isset($coordinate) && isset($target)) {  
                    $filename = date('yhmdhs');
                    $file_ext = explode('.', $_FILES['myfile']['name']);
                    $file_ext = strtolower(end($file_ext));  
                    $path_directory = 'assets/images/';   
                    $value = $path_directory.$filename.'.'.$file_ext;

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
                                       
                        $coordinate = $coordinate == "" ? "0,0,0,0" : $coordinate;
                        $target = $target == "" ? "" : $target;


                        if($productindex == 0) {

                            if(isset($map->section[$index]->product_panel_main[$productindex]->coordinate) && isset($map->section[$index]->product_panel_main[$productindex]->target)) {   
                             
                                $newindex = ($index == 0 ? 1 : $index);
                                $newprodindex = ($productindex == 0 ? 1 : $productindex);

                                $string = $this->xmlCmsService->getString($nodeName, $value, $type, $coordinate, $target);

                                $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel_main['.$newprodindex.']');
                                $this->swapXmlForAddSectionMainSlide_notimage2($file, $newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target);
                                return $this->output
                                        ->set_content_type('application/json')
                                        ->set_output($jsonFile);
                            } 
                            else {

                                $string = $this->xmlCmsService->getString($nodeName, $value, $type, $coordinate, $target);
                                $newindex = ($index == 0 ? 1 : $index);
                                $newprodindex = ($productindex == 0 ? 1 : $productindex);
                        
                                $this->swapXmlForAddSectionMainSlide_image($file, $newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target);
                                return $this->output
                                        ->set_content_type('application/json')
                                        ->set_output($jsonFile);
                            }  
                        } 
                        else {
                            
                            $index = ($index == 0 ? 1 : $index + 1);
                            $productindex = ($productindex == 0 ? 1 : $productindex);
                                
                            $string = $this->xmlCmsService->getString($nodeName, $value, $type, $coordinate, $target);

                            $index = ($index == 0 ? 1 : $index);
                            $productindex = ($productindex == 0 ? 1 : $productindex);
                    
                            $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$index.']/product_panel_main['.$productindex.']');
                            return $this->output
                                    ->set_content_type('application/json')
                                    ->set_output($jsonFile);
                                
                        }                           
                    }    
                }
                
                else if(strtolower($type) != "image") {   
                        
                    if($productindex == 0) {
                        if(!isset($map->section[$index]->product_panel_main[$productindex]->coordinate) && !isset($map->section[$index]->product_panel_main[$productindex]->target)) {

                            $newindex = ($index == 0 ? 1 : $index + 1);
                            $newprodindex = ($productindex == 0 ? 1 : $productindex);

                            $string = $this->xmlCmsService->getString($nodeName, $value, $type, "", "");
                            $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel_main['.$newprodindex.']');     
                            $this->swapXmlForAddSectionMainSlide_notimage1($file, $newprodindex,$newindex,$index,$productindex,$value,$type);     
                            return $this->output
                                ->set_content_type('application/json')
                                ->set_output($jsonFile);
                        } 
                        else {

                            $newindex = ($index == 0 ? 1 : $index);
                            $newprodindex = ($productindex == 0 ? 1 : $productindex);

                            $string = $this->xmlCmsService->getString($nodeName, $value, $type, $coordinate, $target);

                            $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel_main['.$newprodindex.']');
                            $this->swapXmlForAddSectionMainSlide_notimage2($file,$newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target);
                            return $this->output
                                ->set_content_type('application/json')
                                ->set_output($jsonFile);
                        }
                        
                    } 
                    else {

                            $newindex = ($index == 0 ? 1 : $index + 1);
                            $newprodindex = ($productindex == 0 ? 1 : $productindex);
                            $string = $this->xmlCmsService->getString($nodeName, $value, $type, "", "");
                            $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel_main['.$newprodindex.']');
                            return $this->output
                                ->set_content_type('application/json')
                                ->set_output($jsonFile);                        
                    }       
                    
                }
            }
    }

    /**
     *  Method used to set xml contents for product_slide nodes for image types under home_files.xml
     *
     *  @return JSONP
     */
    public function setProductSlide() 
    {
        $jsonFile = $this->json;
        $slugerrorjson = $this->slugerrorjson;
        $boundsjson = $this->boundsjson;

        $map = simplexml_load_file($this->file);   

        $index = $this->input->get("index");
        $userid = $this->input->get("userid");
        $hash = $this->input->get("hash");
        $value =  $this->input->get("value");
        $order =  $this->input->get("order");
        $type =  $this->input->get("type");

        $nodeName =  $this->input->get("nodename");
        $index = (int)$index;
        $string = $this->xmlCmsService->getString($nodeName, $value, $type, "", "");

        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $value]);
                        
        if(!$product){
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($slugerrorjson);
        }

        if($index > count($map->productSlide) - 1    || $order > count($map->productSlide) - 1 || $index < 0 || $order < 0) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($boundsjson);
        }
        else {
            $file = $this->file;
            $value = ($value == "" ? $map->productSlide[$index]->value : $value);
    
            if($order == "") {
                    $map->productSlide[$index]->value = $value;
                    $type->productSlide[$index]->type = $type;
                    if($map->asXML($this->file)) {
                        return true;
                    } 
                    else {
                        return false;
                    }
                } 
                else {
                        
                    if($index <= $order) {
                        $index = ($index == 0 ? 1 : $index + 1);
                        $order = ($order == 0 ? 1 : $order + 1);

                        $this->xmlCmsService->addXml($file,$string,'/map/productSlide['.$order.']');
                        $this->xmlCmsService->removeXML($file,$nodeName,$index);
                    } 
                    else {

                        if($order == 0) {
                            $value = ($value == "" ? $map->productSlide[$index]->value : $value);
                            
                            $orindex = $index;
                            $ororder = $order;
                            $index = ($index == 0 ? 1 : $index + 1);
                            $this->xmlCmsService->removeXML($file,$nodeName,$index);
                            $order = ($order == 0 ? 1 : $order);
                            $this->xmlCmsService->addXml($file,$string,'/map/productSlide['.$order.']');
                            $this->swapXmlForSetProductSlide($file,$orindex,$ororder,$value);
                        } 
                        else {
                                $value = ($value == "" ? $map->productSlide[$index]->value : $value);
                
    
                            $index = ($index == 0 ? 1 : $index + 1);
                            $this->xmlCmsService->removeXML($file,$nodeName,$index);
                            $order = ($order == 0 ? 1 : $order);
                            $this->xmlCmsService->addXml($file,$string,'/map/productSlide['.$order.']');
                            
                        }
                    }
                }

        return $this->output
                ->set_content_type('application/json')
                ->set_output($jsonFile);
        }
    }

    /**
     *  Method used to add xml contents for product_panel nodes home_files.xml
     *
     *  @return JSONP
     */
    public function addSectionProduct()
    {
        $jsonFile = $this->json;
        $slugerrorjson = $this->slugerrorjson;
        $boundsjson = $this->boundsjson;
        $file = $this->file;
        $index = $this->input->get("index");
        $userid = $this->input->get("userid");
        $hash = $this->input->get("hash");
        $productindex = $this->input->get("productindex");
        $type = strtolower($this->input->get("type"));
        $value =  $this->input->get("myfile");
        
        $type = ($type == "") ? "product" : $type;
        $type = strtolower($type);
        $index = (int)$index;

        $nodeName = "product_panel";
        $map = simplexml_load_file($file);   
        
        if(!is_numeric($index) || !is_numeric($productindex) || $index > count($map->section) - 1 || $productindex > count($map->section[$index]->product_panel) - 1 || $index < 0 || $productindex < 0) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($boundsjson);
        } 


        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $value]); 
        if(!$product && $type == "product") {

            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($slugerrorjson);
        }       
        else {

            if($type == "product") {

                $productindex = (int)$productindex;            
                $node = $map->section[$index]->product_panel[$productindex];
        
                $string = $this->xmlCmsService->getString($nodeName,$value,$type, "", "");

                
                $newprodindex = ($productindex == 0 ? 1 : $productindex);
                $newindex = ($index == 0 ? 1 : $index);
                
                
                    if($index > 0) {

                        $newindex += 1;
                        $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel['.$newprodindex.']');
                        if($productindex == 0) {
                            $this->swapXmlForSectionProduct($file, $newprodindex,$newindex,$value,$type,$index,$productindex);
                        }
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_output($jsonFile);
                    } 
                    else {

                        $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel['.$newprodindex.']');
                        $this->swapXmlForSectionProduct($file, $newprodindex,$newindex,$value,$type,$index,$productindex);
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_output($jsonFile);
                    } 

            }
            else {
                $productindex = (int)$productindex;            
                $node = $map->section[$index]->product_panel[$productindex];
        
                $string = $this->xmlCmsService->getString($nodeName,$value,$type, "", "");

                
                $newprodindex = ($productindex == 0 ? 1 : $productindex);
                $newindex = ($index == 0 ? 1 : $index);
                $filename = date('yhmdhs');
                $file_ext = explode('.', $_FILES['myfile']['name']);
                $file_ext = strtolower(end($file_ext));  
                $path_directory = 'assets/product/';   
                $value = $path_directory.$filename.'.'.$file_ext;

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
                    if($index > 0) {

                        $newindex += 1;
                        $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel['.$newprodindex.']');
                        if($productindex == 0) {
                            $this->swapXmlForSectionProduct($file, $newprodindex,$newindex,$value,$type,$index,$productindex);
                        }
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_output($jsonFile);
                    } 
                    else {
                        $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel['.$newprodindex.']');
                        $this->swapXmlForSectionProduct($file, $newprodindex,$newindex,$value,$type,$index,$productindex);
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_output($jsonFile);
                    } 
                }

            }
  
        }

    }
    /**
     *  Method used to add xml contents for typeNodes under home_files.xml
     *
     *  @return JSONP
     */
    public function addType() 
    {
        $jsonFile = $this->json;
        $file = $this->file;
        $userid = $this->input->get("userid");
        $value =  $this->input->get("value");
        $string = $this->xmlCmsService->getString("typeNode",$value, "", "", "");

        
        if($this->xmlCmsService->addXml($file,$string,'/map/typeNode[last()]')) {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output($jsonFile);
        } 
    }

    /**
     *  Method used to add xml contents for child nodes under home_files.xml
     *
     *  @return JSONP
     */
    public function settext() 
    {

        $jsonFile = $this->json;
        $userid = $this->input->get("userid");
        $value =  $this->input->get("value");
        $hash = $this->input->get("hash");

            $map = simplexml_load_file($this->file);
            $value = $value == "" ? $map->text->value : $value;
            $map->text->value= $value;
            if($map->asXML($this->file)) {
                
                return $this->output
                            ->set_content_type('application/json')
                            ->set_output($jsonFile);
            }
    }
    /**
     *  Method used to swap xml contents for setMainSlide method, user for re-ordering nodes
     *
     *  @param string $file
     *  @param integer $orindex
     *  @param integer $ororder
     *  @param integer $plusin
     *  @param integer $plusor
     *  @param string $value
     *  @param string $type
     *  @param string $coordinate
     *  @param string $target  
     */
    private function swapXmlForSetMainSlide($file, $orindex,$ororder, $plusin, $plusor, $value,$type,$coordinate,$target) 
    {
        $orindex =  (int) $orindex;
        $ororder =  (int) $ororder;
        $plusor =  (int) $plusor;
    
        $map = simplexml_load_file($file);
 
        $map->mainSlide[$plusor]->value = $map->mainSlide[$ororder]->value;
        $map->mainSlide[$plusor]->type = $map->mainSlide[$ororder]->type;
        $map->mainSlide[$plusor]->imagemap->coordinate = $map->mainSlide[$ororder]->imagemap->coordinate;
        $map->mainSlide[$plusor]->imagemap->target = $map->mainSlide[$ororder]->imagemap->target;

        $map->mainSlide[$ororder]->value = $value;
        $map->mainSlide[$ororder]->type = $type;
        $map->mainSlide[$ororder]->imagemap->coordinate = $coordinate;
        $map->mainSlide[$ororder]->imagemap->target = $target;

        $map->asXML($file);
    }

    /**
     *  Method used to swap xml contents for addMainSlide method, user for re-ordering nodes
     *
     *  @param string $file
     *  @param integer $orindex
     *  @param integer $neworindex
     *  @param string $value
     *  @param string $type
     *  @param string $coordinate
     *  @param string $target
     */
    private function swapXmlForAddMainSlide($file, $orindex,$neworindex,$value,$type,$coordinate,$target) 
    {
        $orindex = (int) $orindex;
        $neworindex = (int) $neworindex;

        $map = simplexml_load_file($file);

        $map->mainSlide[$neworindex]->value = $map->mainSlide[$orindex]->value;
        $map->mainSlide[$neworindex]->type = $map->mainSlide[$orindex]->type;
        $map->mainSlide[$neworindex]->imagemap->coordinate = $map->mainSlide[$orindex]->imagemap->coordinate;
        $map->mainSlide[$neworindex]->imagemap->target = $map->mainSlide[$orindex]->imagemap->target;
         
        $map->mainSlide[$orindex]->value = $value;
        $map->mainSlide[$orindex]->type = $type;
        $map->mainSlide[$orindex]->imagemap->coordinate = $coordinate;
        $map->mainSlide[$orindex]->imagemap->target = $target;

        $map->asXML($file);
    }
    /**
     *  Method used to swap xml contents for addProductSlide method, user for re-ordering nodes
     *
     *  @param string $file
     *  @param integer $orindex
     *  @param integer $neworindex
     *  @param string $value
     */
    private function swapXmlForAddProductSlide($file, $orindex,$neworindex,$value) 
    {

        $orindex = (int) $orindex;
        $neworindex = (int) $neworindex;

        $map = simplexml_load_file($this->file);
        $map->productSlide[$neworindex]->value = $map->productSlide[$orindex]->value;        
        $map->productSlide[$orindex]->value = $value;

        $map->asXML($this->file);
        
    }

    /**
     *  Method used to swap xml contents for product_panel_main nodes for not image types under home_files.xml
     *
     *  @param string $file
     *  @param string $newprodindex
     *  @param string $newindex
     *  @param string $index
     *  @param string $productindex  
     *  @param string $value  
     *  @param string $type  
     *  @param string $coordinate  
     *  @param string $target  
     */
    private function swapXmlForAddSectionMainSlide_notimage2($file, $newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target) 
    {

        $newprodindex = (int) $newprodindex;
        $newindex = (int) $newindex;

        $map = simplexml_load_file($file);
     
        $map->section[$index]->product_panel_main[$newprodindex]->value = $map->section[$index]->product_panel_main[$productindex]->value;
        $map->section[$index]->product_panel_main[$newprodindex]->type = $map->section[$index]->product_panel_main[$productindex]->type;
        $map->section[$index]->product_panel_main[$newprodindex]->coordinate = $map->section[$index]->product_panel_main[$productindex]->coordinate;
        $map->section[$index]->product_panel_main[$newprodindex]->target = $map->section[$index]->product_panel_main[$productindex]->target;

        $map->section[$index]->product_panel_main[$productindex]->value = $value;
        $map->section[$index]->product_panel_main[$productindex]->type = $value;
        $map->section[$index]->product_panel_main[$productindex]->coordinate = $coordinate;
        $map->section[$index]->product_panel_main[$productindex]->target = $target;
        
        $map->asXML($file);
        
    }

    /**
     *  Method used to swap xml contents for product_panel_main nodes for image types under home_files.xml
     *
     *  @param string $file
     *  @param string $newprodindex
     *  @param string $newindex
     *  @param string $index
     *  @param string $productindex  
     *  @param string $value  
     *  @param string $type  
     *  @param string $coordinate  
     *  @param string $target  
     */
    private function swapXmlForAddSectionMainSlide_image($file, $newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target) 
    {

        $newprodindex = (int) $newprodindex;
        $newindex = (int) $newindex;

        $map = simplexml_load_file($file);

        $map->section[$index]->product_panel_main[$newprodindex]->value = $map->section[$index]->product_panel_main[$productindex]->value;
        $map->section[$index]->product_panel_main[$newprodindex]->type = $map->section[$index]->product_panel_main[$productindex]->type;
        $map->section[$index]->product_panel_main[$newprodindex]->imagemap->coordinate = $map->section[$index]->product_panel_main[$productindex]->imagemap->coordinate;
        $map->section[$index]->product_panel_main[$newprodindex]->imagemap->target = $map->section[$index]->product_panel_main[$productindex]->imagemap->target;

        $map->section[$index]->product_panel_main[$productindex]->value = $value;
        $map->section[$index]->product_panel_main[$productindex]->type = $type;
        $map->section[$index]->product_panel_main[$productindex]->imagemap->coordinate = $coordinate;
        $map->section[$index]->product_panel_main[$productindex]->imagemap->target = $target;

        $map->asXML($file);
        
    }

    /**
     *  Method used to swap xml contents for product_panel_main nodes for image types under home_files.xml
     *
     *  @param string $file
     *  @param string $newprodindex
     *  @param string $newindex
     *  @param string $index
     *  @param string $value  
     *  @param string $type  
     */
    private function swapXmlForAddSectionMainSlide_notimage1($file, $newprodindex,$newindex,$index,$productindex,$value,$type) 
    {
    
        $newprodindex = (int) $newprodindex;
        $newindex = (int) $newindex;

        $map = simplexml_load_file($file);
    
        $map->section[$index]->product_panel_main[$newprodindex]->value = $map->section[$index]->product_panel_main[$productindex]->value;
        $map->section[$index]->product_panel_main[$newprodindex]->type = $map->section[$index]->product_panel_main[$productindex]->type;
        
        $map->section[$index]->product_panel_main[$productindex]->value = $value;
        $map->section[$index]->product_panel_main[$productindex]->type = $type;

        $map->asXML($file);
        
    }

    /**
     *  Method used to swap xml contents for product_panel nodes under home_files.xml, user for re-ordering
     *
     *  @param string $file
     *  @param string $newprodindex
     *  @param string $newindex
     *  @param string $value
     *  @param string $type
     *  @param string $index
     *  @param string $productindex
     */
    private function swapXmlForSectionProduct($file,$newprodindex,$newindex,$value,$type,$index,$productindex) 
    {
        $map = simplexml_load_file($file);
            
        $map->section[$index]->product_panel[$newprodindex]->value = $map->section[$index]->product_panel[$productindex]->value;
        $map->section[$index]->product_panel[$newprodindex]->type = $map->section[$index]->product_panel[$productindex]->type;
    
        $map->section[$index]->product_panel[$productindex]->value = $value;
        $map->section[$index]->product_panel[$productindex]->type = $type;
        $map->asXML($file);
        
    }

    /**
     *  Method used to swap xml contents for productSlide nodes under home_files.xml
     *  @param string $file
     *  @param string $orindex
     *  @param string $ororder
     *  @param string $value  
     */
    private function swapXmlForSetProductSlide($file,$orindex,$ororder,$value)
    {
        
        $orindex = (int) $orindex;
        $ororder = (int) $ororder;
        if($orindex > 1) {
            $orindex = $orindex - 1;            
        }

        $map = simplexml_load_file($file);
        $map->productSlide[$orindex]->value = $map->productSlide[$ororder]->value;
        $map->productSlide[$ororder]->value = $value;
        $map->asXML($file);
        
    }


}




