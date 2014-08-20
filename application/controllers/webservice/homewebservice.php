<?php 

class Homewebservice extends MY_Controller 
{

    /**
     *  Constructor call for Administrator's authentication. Authentication method is located in MY_Controller.php
     *
     *  $xmlCmsService used for accessing functions under application/src/Easyshop/XML/CMS.php
     *  $xmlFileService used for accessing Resource class
     */
    private $xmlCmsService;
    public $xmlFileService;

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('user_model');

        $this->xmlCmsService = $this->serviceContainer['xml_cms'];
        $this->xmlFileService = $this->serviceContainer['xml_resource'];
        $this->declareEnvironment();

        if($this->input->get()) {
            $this->authentication($this->input->get(), $this->input->get('hash'));
        }  
    }
  
    /**
     *  Environment declaration of APPPATH . "resources/page/home_files.xml"; 
     *
     *  
     */
    public function declareEnvironment()
    {

        $env = strtolower(ENVIRONMENT);
        $this->file  = APPPATH . "resources/". $this->xmlFileService->getHomeXMLfile().".xml"; 
        $this->json = file_get_contents(APPPATH . "resources/json/jsonp.json");
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
     *  Method used to change the contents of mainSlide node under home_files.xml
     *
     *  @return JSONP
     */
    public function setMainSlide() 
    {

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
        $nodeName =  $this->input->get("nodename");
        $type = "image";
        $coordinate = ($coordinate == "") ? "0,0,0,0" : $coordinate;
        $string = $this->xmlCmsService->getString($nodeName, $value, $type, $coordinate, $target);

        if($index > count($map->mainSlide) - 1 || $order > count($map->mainSlide) - 1 || $index < 0 || $order < 0) {
            exit("error");
        } 
        else {

            $index = (int)($index); 
            
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

                        $this->xmlCmsService->swapXmlForSetMainSlide($file, $orindex,$ororder, $plusin, $plusor, $value,$type,$coordinate,$target);
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
                            ->set_output($jsonFile);
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
                    $this->xmlCmsService->swapXmlForAddMainSlide($file, $orindex, $index,$value,$type,$coordinate,$target);
                
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
     *  Method used to add contents to productSlide node under home_files.xml
     *
     *  @return JSONP
     */
    public function addProductSlide() 
    {
        $jsonFile = $this->json;
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

        if($orindex == 0) {
            $this->xmlCmsService->addXml($file,$string,'/map/productSlide[last()]');
            $this->xmlCmsService->swapXmlForAddProductSlide($file,$orindex, $index,$value);
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
        $map = simplexml_load_file($this->file);   
                
        $index = $this->input->get("index");
        $userid = $this->input->get("userid");
        $hash = $this->input->get("hash");
        $productindex = $this->input->get("productindex");
        $type = $this->input->get("type");
        $value =  $this->input->get("value");

        $index = (int)$index;
        $productindex = (int)$productindex;

        $map->section[$index]->product_panel[$productindex]->value = $value;
        $map->section[$index]->product_panel[$productindex]->type = $type;

        if($map->asXML($this->file)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($jsonFile);
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

        $index = $this->input->get("index");
        $userid = $this->input->get("userid");
        $hash = $this->input->get("hash");
        $productindex = $this->input->get("productindex");
        $type = $this->input->get("type");
        $value =  $this->input->get("value");
        $coordinate =  $this->input->get("coordinate");
        $target =  $this->input->get("target");
        $nodeName = "product_panel_main";
        
        $map = simplexml_load_file($file);   
        
        $index = (int)$index;
        $productindex = (int)$productindex;


                if(strtolower($type) == "image") {
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
        $file = $this->file;
        $map = simplexml_load_file($file);   
        $index = $this->input->get("index");
        $userid = $this->input->get("userid");
        $hash = $this->input->get("hash");
        $productindex = $this->input->get("productindex");
        $type = strtolower($this->input->get("type"));
        $value =  $this->input->get("value");
        $coordinate =  $this->input->get("coordinate");
        $target =  $this->input->get("target");
        $nodeName = "product_panel_main";
        
        $index = (int)$index;
        $productindex = (int)$productindex;

            if($index > count($map->section) || $productindex > count($map->section[$index]->product_panel_main) || $index < 0 || $productindex < 0) {
                exit("Index out of bounds");
            } 
            else {
                if(strtolower($type) == "image" && isset($coordinate) && isset($target)) {           
                    $coordinate = $coordinate == "" ? "0,0,0,0" : $coordinate;
                    $target = $target == "" ? "" : $target;

                    if($productindex == 0) {
                        if(isset($map->section[$index]->product_panel_main[$productindex]->coordinate) && isset($map->section[$index]->product_panel_main[$productindex]->target)) {   
                        
                            $newindex = ($index == 0 ? 1 : $index);
                            $newprodindex = ($productindex == 0 ? 1 : $productindex);

                            $string = $this->xmlCmsService->getString($nodeName, $value, $type, $coordinate, $target);

                            $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel_main['.$newprodindex.']');
                            $this->xmlCmsService->swapXmlForAddSectionMainSlide_notimage2($file, $newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target);
                            return $this->output
                                    ->set_content_type('application/json')
                                    ->set_output($jsonFile);
                        } 
                        else {
                            $string = $this->xmlCmsService->getString($nodeName, $value, $type, $coordinate, $target);
                            $newindex = ($index == 0 ? 1 : $index);
                            $newprodindex = ($productindex == 0 ? 1 : $productindex);
                    
                            $this->xmlCmsService->swapXmlForAddSectionMainSlide_image($file, $newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target);
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
                
                else if(strtolower($type) != "image") {   
                        
                    if($coordinate != "" || $target != "") {
                        echo "Only Image";
                    } 
                    else {    

                        if($productindex == 0) {
                            if(!isset($map->section[$index]->product_panel_main[$productindex]->coordinate) && !isset($map->section[$index]->product_panel_main[$productindex]->target)) {

                                $newindex = ($index == 0 ? 1 : $index + 1);
                                $newprodindex = ($productindex == 0 ? 1 : $productindex);

                                $string = $this->xmlCmsService->getString($nodeName, $value, $type, "", "");
                                $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel_main['.$newprodindex.']');     
                                $this->xmlCmsService->swapXmlForAddSectionMainSlide_notimage1($file, $newprodindex,$newindex,$index,$productindex,$value,$type);     
                                return $this->output
                                    ->set_content_type('application/json')
                                    ->set_output($jsonFile);
                            } 
                            else {

                                $newindex = ($index == 0 ? 1 : $index);
                                $newprodindex = ($productindex == 0 ? 1 : $productindex);

                                $string = $this->xmlCmsService->getString($nodeName, $value, $type, $coordinate, $target);

                                $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel_main['.$newprodindex.']');
                                $this->xmlCmsService->swapXmlForAddSectionMainSlide_notimage2($file,$newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target);
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
    }

    /**
     *  Method used to set xml contents for product_slide nodes for image types under home_files.xml
     *
     *  @return JSONP
     */
    public function setProductSlide() 
    {
        $jsonFile = $this->json;
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

            if($index > count($map->productSlide) - 1    || $order > count($map->productSlide) - 1 || $index < 0 || $order < 0) {
                exit("Index out of bounds");
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
                                $this->xmlCmsService->swapXmlForSetProductSlide($file,$orindex,$ororder,$value);
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
        $file = $this->file;
        $index = $this->input->get("index");
        $userid = $this->input->get("userid");
        $hash = $this->input->get("hash");
        $productindex = $this->input->get("productindex");
        $type = strtolower($this->input->get("type"));
        $value =  $this->input->get("value");
        
        $type = ($type == "") ? "product" : $type;

        $index = (int)$index;
        $productindex = (int)$productindex;
        $nodeName = "product_panel";
        $map = simplexml_load_file($file);   
        
        if($index > count($map->section) - 1 || $productindex > count($map->section[$index]->product_panel) - 1 || $index < 0 || $productindex < 0) {
            exit("Parameter out of bounds");
        } 
        else {
            $node = $map->section[$index]->product_panel[$productindex];
    
            $string = $this->xmlCmsService->getString($nodeName,$value,$type, "", "");

            
            $newprodindex = ($productindex == 0 ? 1 : $productindex);
            $newindex = ($index == 0 ? 1 : $index);
            
            
                if($index > 0) {

                    $newindex += 1;
                    $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel['.$newprodindex.']');
                    if($productindex == 0) {
                        $this->xmlCmsService->swapXmlForSectionProduct($file, $newprodindex,$newindex,$value,$type,$index,$productindex);
                    }
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);
                } 
                else {
                    $this->xmlCmsService->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel['.$newprodindex.']');
                    $this->xmlCmsService->swapXmlForSectionProduct($newprodindex,$newindex,$value,$type,$index,$productindex);
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);
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


}




