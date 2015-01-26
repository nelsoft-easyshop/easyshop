<?php 

use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsMember; 

class FeedWebService extends MY_Controller 
{

    private $map, $targetNode;
    
    /**
     * The XML service
     */
    private $xmlFileService;
    
    /**
     * The CMS Service
     *
     */
    private $xmlCmsService;
    
    /**
     * The entity manager
     *
     */
    
    private $em;    
    
    /**
     *  Constructor call for Administrator's authentication. Authentication method is located in MY_Controller.php
     *  
     */
    public function __construct()
    {
        parent::__construct();
        $this->xmlCmsService = $this->serviceContainer['xml_cms'];
        $this->xmlFileService = $this->serviceContainer['xml_resource'];
        $this->em = $this->serviceContainer['entity_manager'];        
        $this->declareEnvironment();
        $this->authenticateRequest = $this->serviceContainer['webservice_manager'];           
        if($this->input->get()) {
            $this->isAuthenticated = $this->authenticateRequest->authenticate($this->input->get(), 
                                                                              $this->input->get('hash'),
                                                                              true);
            if(!$this->isAuthenticated) {
                throw new Exception("Unauthorized Request.");
            }  
        }  

    }
    /**
     *  Environment declarations
     */
    public function declareEnvironment()
    {
        $this->file  = APPPATH . "resources/". $this->xmlFileService->getContentXMLfile().".xml"; 
        $this->map = new SimpleXMLElement(file_get_contents($this->file));
        $this->slugerrorjson = file_get_contents(APPPATH . "resources/json/slugerrorjson.json");
        $this->boundsjson = file_get_contents(APPPATH . "resources/json/boundsjson.json");        
        $this->usererror = file_get_contents(APPPATH . "resources/json/usererrorjson.json");        
        $this->json = file_get_contents(APPPATH . "resources/json/jsonp.json");


    }

    /**
     *  Removes feeds
     *
     *  @return JSON
     */
    public function removeContent() 
    {

        $index =  $this->input->get("index");
        $nodeName = $this->input->get("nodename");    
        $doc = new SimpleXMLElement(file_get_contents($this->file));
        $count =  count(current($doc->xpath($nodeName)));

        if($count > 1){
            if($this->xmlCmsService->removeXML($this->file,$nodeName."/product",$index)) {
                return $this->output
                            ->set_content_type('application/json')
                            ->set_output($this->json);              
            }                        
        }    


    }

    /**
     *  Method to display the contents of the content_files.xml from the function call from Easyshop.ph.admin
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
     *  Getter of value for select xml node
     *
     *  @return string
     */
    public function getSelectNode()
    {
        $this->load->library("xmlmap");
        $value = $this->xmlmap->getFilenameID('page/content_files', $this->input->post("id"));
        $node = dom_import_simplexml($value); 
        return $node->nodeValue;
    }

    /**
     *  Getter for feedFeaturedProduct xml node
     *
     *  @return string
     */
    public function getfeedFeaturedProduct()
    {
        $index = (int)$this->input->post("index");
        $xPath = '//feedFeaturedProduct/product['.$index.']';
        $target = current($this->map->xpath($xPath)); 
        $node = dom_import_simplexml($target); 
        return $node->nodeValue;
    }

    /**
     *  Getter for feedPopularItems xml node
     *
     *  @return string
     */
    public function getfeedPopularItems()
    {
        $index = (int)$this->input->post("index");
        $xPath = '//feedPopularItems/product['.$index.']';
        $target = current($this->map->xpath($xPath)); 
        $node = dom_import_simplexml($target); 
        return $node->nodeValue;
    }

    /**
     *  Getter for feedPromoItems xml node
     *
     *  @return string
     */
    public function getfeedPromoItems()
    {
        $index = (int)$this->input->post("index");
        $xPath = '//feedPromoItems/product['.$index.']';
        $target = current($this->map->xpath($xPath)); 
        $node = dom_import_simplexml($target); 
        return $node->nodeValue;
    }

    /**
     *  Adds xml node under feedPromoItems
     *
     *  @return JSON
     */
    public function addfeedPromoItems()
    {
        $slugerrorjson = $this->slugerrorjson;

        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $this->input->get("slug")]);
                        
        if(!$product) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($slugerrorjson);
        }
        else {
            $string = $this->xmlCmsService->getString("feedPromoItems",$this->input->get("slug"), "", "", "");
            $addXml = $this->xmlCmsService->addXml($this->file,$string,'/map/feedPromoItems/product[last()]');
            if($addXml === TRUE) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
            }            
        }                

    }

    /**
     *  Sets xml node under feedPromoItems 
     */
    public function setfeedPromoItems()
    {
        $slugerrorjson = $this->slugerrorjson;        
        $index = (int) $this->input->get("index");
        $order = (int) $this->input->get("order");

        $map = simplexml_load_file($this->file);

        $slug = $this->input->get("slug") == "" ? $map->feedPromoItems->product[$index]->slug : $this->input->get("slug");
        $string = $this->xmlCmsService->getString("feedPromoItems",$slug, "", "", "");

        if($index > count($map->feedPromoItems->product) - 1    || $order > count($map->feedPromoItems->product) - 1 || $index < 0 || $order < 0) {
               exit("Parameters out of bounds");
        }
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $this->input->get("slug")]);
                        
        if(!$product){
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($slugerrorjson);
        }         
        else {
            if(!isset($order)) {
                $map->feedPromoItems->product[$index]->slug = $slug;
            }
            else {
                if($index <= $order) {
                    $index = ($index == 0 ? 1 : $index + 1);
                    $order = ($order == 0 ? 1 : $order + 1);

                    $this->xmlCmsService->addXml($this->file,$string,'/map/feedPromoItems/product['.$order.']');
                    $this->xmlCmsService->removeXML($this->file,"/map/feedPromoItems/product",$index);
                } 
                else {
                    if($order == 0) {                       
                        $index = ($index == 0 ? 1 : $index + 1);
                        $this->xmlCmsService->removeXML($this->file,"/map/feedPromoItems/product",$index);
                        $order = ($order == 0 ? 1 : $order);
                        $this->xmlCmsService->addXml($this->file,$string,'/map/feedPromoItems/product['.$order.']');
                        $this->swapXmlForSetfeedPromoItems($this->file,$this->input->get("index"),$this->input->get("order"),$slug);
                    }
                    else {
                        $index = ($index == 0 ? 1 : $index + 1);
                        $this->xmlCmsService->removeXML($this->file,"/map/feedPromoItems/product",$index);
                        $order = ($order == 0 ? 1 : $order);
                        $this->xmlCmsService->addXml($this->file,$string,'/map/feedPromoItems/product['.$order.']');
                     
                    } 
                }
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
            }
        }

    }
    /**
     *  Adds xml node under feedPopularItems
     *
     *  @return JSON
     */
    public function addfeedPopularItems()
    {
        $slugerrorjson = $this->slugerrorjson;
        $boundsjson = $this->boundsjson;
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $this->input->get("slug")]);
                        
        if(!$product){
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($slugerrorjson);
                        exit();
        }        
        else {
            $string = $this->xmlCmsService->getString("feedPopularItems",$this->input->get("slug"), "", "", "");
            $addXml = $this->xmlCmsService->addXml($this->file,$string,'/map/feedPopularItems/product[last()]');
             
            if($addXml === TRUE) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
            }           
        }         

    }

    /**
     *  Sets xml node under feedPopularItems
     *
     */
    public function setfeedPopularItems()
    {
        $index = (int) $this->input->get("index");
        $order = (int) $this->input->get("order");
        $slugerrorjson = $this->slugerrorjson;
        $boundsjson = $this->boundsjson;
        $map = simplexml_load_file($this->file);

        $slug = $this->input->get("slug") == "" ? $map->feedPopularItems->product[$index]->slug : $this->input->get("slug");
        $string = $this->xmlCmsService->getString("feedPopularItems",$slug, "", "", "");
        if($index > count($map->feedPopularItems->product) - 1    || $order > count($map->feedPopularItems->product) - 1 || $index < 0 || $order < 0) {
                exit("Parameters out of bounds");
        }

        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $this->input->get("slug")]);
                        
        if(!$product){
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($slugerrorjson);
                        exit();
        }       
        else {
            if(!isset($order)) {
                $map->feedPopularItems->product[$index]->slug = $slug;
            }
            else {
                if($index <= $order) {
                    $map->feedPopularItems->product[$index]->slug = $slug;

                    $index = ($index == 0 ? 1 : $index + 1);
                    $order = ($order == 0 ? 1 : $order + 1);

                    $this->xmlCmsService->addXml($this->file,$string,'/map/feedPopularItems/product['.$order.']');
                    $this->xmlCmsService->removeXML($this->file,"/map/feedPopularItems/product",$index);
                } 
                else {
                    if($order == 0) {                       
                        $index = ($index == 0 ? 1 : $index + 1);
                        $this->xmlCmsService->removeXML($this->file,"/map/feedPopularItems/product",$index);
                        $order = ($order == 0 ? 1 : $order);
                        $this->xmlCmsService->addXml($this->file,$string,'/map/feedPopularItems/product['.$order.']');
                        $this->swapXmlForSetfeedPopularItems($this->file,$this->input->get("index"),$this->input->get("order"),$slug);
                    }
                    else {
                        $index = ($index == 0 ? 1 : $index + 1);
                        $this->xmlCmsService->removeXML($this->file,"/map/feedPopularItems/product",$index);
                        $order = ($order == 0 ? 1 : $order);
                        $this->xmlCmsService->addXml($this->file,$string,'/map/feedPopularItems/product['.$order.']');
                     
                    } 
                }

            }
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }

    }

    /**
     *  Adds xml node under feedFeaturedProduct
     *
     *  @return JSON
     */
    public function addfeedFeaturedProduct()
    {
        $slugerrorjson = $this->slugerrorjson;

        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $this->input->get("slug")]);
                        
        if(!$product){
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($slugerrorjson);
        }  
        else {
            $string = $this->xmlCmsService->getString("feedFeaturedProduct",$this->input->get("slug"), "", "", "");
            $addXml = $this->xmlCmsService->addXml($this->file,$string,'/map/feedFeaturedProduct/product[last()]');
            if($addXml === TRUE) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
            }            
        }



    }

    /**
     *  Sets xml node under feedFeaturedProduct
     */    
    public function setfeedFeaturedProduct()
    {
        $index = (int) $this->input->get("index");
        $order = (int) $this->input->get("order");
        $slugerrorjson = $this->slugerrorjson;
        $map = simplexml_load_file($this->file);

        $slug = $this->input->get("slug") == "" ? $map->feedFeaturedProduct->product[$index]->slug : $this->input->get("slug");
        $string = $this->xmlCmsService->getString("feedFeaturedProduct",$slug, "", "", "");

        if($index > count($map->feedFeaturedProduct->product) - 1    || $order > count($map->feedFeaturedProduct->product) - 1 || $index < 0 || $order < 0) {
                exit("Parameters out of bounds");
        }
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $this->input->get("slug")]);
                        
        if(!$product){
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($slugerrorjson);
        }           
        else {
            if(!isset($order)) {
                $map->feedFeaturedProduct->product[$index]->slug = $slug;
            }
            else {
                if($index <= $order) {
                    $map->feedFeaturedProduct->product[$index]->slug = $slug;
                    
                    $index = ($index == 0 ? 1 : $index + 1);
                    $order = ($order == 0 ? 1 : $order + 1);
                    
                    $this->xmlCmsService->addXml($this->file,$string,'/map/feedFeaturedProduct/product['.$order.']');
                    $this->xmlCmsService->removeXML($this->file,"/map/feedFeaturedProduct/product",$index);



                } 
                else {
                    if($order == 0) {                       
                        $index = ($index == 0 ? 1 : $index + 1);
                        $this->xmlCmsService->removeXML($this->file,"/map/feedFeaturedProduct/product",$index);
                        $order = ($order == 0 ? 1 : $order);
                        $this->xmlCmsService->addXml($this->file,$string,'/map/feedFeaturedProduct/product['.$order.']');
                        $this->swapXmlForSetfeedFeaturedProduct($this->file,$this->input->get("index"),$this->input->get("order"),$slug);

                    
                    }
                    else {
                        $index = ($index == 0 ? 1 : $index + 1);
                        $this->xmlCmsService->removeXML($this->file,"/map/feedFeaturedProduct/product",$index);
                        $order = ($order == 0 ? 1 : $order);
                        $this->xmlCmsService->addXml($this->file,$string,'/map/feedFeaturedProduct/product['.$order.']');
                     
                    } 
                }
                            return $this->output
                                ->set_content_type('application/json')
                                ->set_output($this->json);
            }
        }

    }

    /**
     *  Sets xml node under feedBanner
     */
    public function setFeedBanner()
    {
        $filename = date('yhmdhs');
        $choice = $this->input->get("choice");
        $file_ext = explode('.', $_FILES["myfile"]['name']);
        $file_ext = strtolower(end($file_ext));  
        $path_directory = 'assets/images/feed';

        $this->upload->initialize(array( 
            "upload_path" => $path_directory,
            "overwrite" => FALSE, 
            "encrypt_name" => FALSE,
            "file_name" => $filename,
            "remove_spaces" => TRUE,
            "allowed_types" => "jpg|jpeg|png|gif", 
            "xss_clean" => FALSE
        ));  
        $xPath = "//$choice/target";
        $target = current($this->map->xpath($xPath)); 
        $node = dom_import_simplexml($target); 
        $node->nodeValue = $this->input->get("target");

        if(!empty($_FILES["myfile"]['name'])) {
             if ( ! $this->upload->do_upload("myfile")) {
                $error = array('error' => $this->upload->display_errors());
                         return $this->output
                                ->set_content_type('application/json')
                                ->set_output($error);
            } 
            else {

                $xPath = "//$choice/img";
                $target = current($this->map->xpath($xPath)); 
                $node = dom_import_simplexml($target);
                $value = "$path_directory/".$filename.'.'.$file_ext;             
                $node->nodeValue = $value;
                }
        }           
        
        if($this->map->asXml($this->file)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->json);    
        }    

    }

    /**
     *  Sets xml node under select nodes
     */
    public function setSelect()
    {
        $valid = 1;
        $value = $this->input->get("value");
        $id = $this->input->get("id");
        $xPath = "/map/select[@id='".$id."']";

        if($this->input->get("checkuser") == 1) {

            if (strpos($value ,',') == true) {
                $idArr = explode(",", $value);
                 foreach($idArr as $ids) {
                    if(!is_numeric($ids)) {
                        exit();
                    }

                    $userTest = $this->em->find('EasyShop\Entities\EsMember',$ids);                              
                    if(!$userTest){
                        $valid = 0;
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_output($this->usererror);
                    }
                }
            }
            else {
              
                if(!is_numeric($value)) {
                    exit();
                }

                $exist =  $userTest = $this->em->find('EasyShop\Entities\EsMember',$this->input->get("value`")); 

                if(!$exist) {
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_output($this->usererror);
                            $valid = 0;
                }                
            }
            if($valid == 1) {
                $target = current($this->map->xpath($xPath)); 
                $node = dom_import_simplexml($target); 
                $node->nodeValue = $value;
                if($this->map->asXml($this->file)) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
                }  
            }


            
        }
        else {

            $target = current($this->map->xpath($xPath)); 
            $node = dom_import_simplexml($target); 
            $node->nodeValue = $value;
            if($this->map->asXml($this->file)) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
            }             
        }
     

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
    /**
     *  Method used to swap xml contents for feedPopularItems nodes under content_files.xml
     *  @param string $file
     *  @param string $original_index
     *  @param string $original_order
     *  @param string $value  
     */
    private function swapXmlForSetfeedPopularItems($file,$original_index,$original_order,$value)
    {
        
        $original_index = (int) $original_index;
        $original_order = (int) $original_order;
        if($original_index > 1) {
            $original_index = $original_index - 1;            
        }

        $map = simplexml_load_file($file);
        $map->feedPopularItems->product[$original_index]->slug = $map->feedPopularItems->product[$original_order]->slug;
        $map->feedPopularItems->product[$original_order]->slug = $value;
        $map->asXML($file);
        
    }
    /**
     *  Method used to swap xml contents for feedFeaturedProduct nodes under content_files.xml
     *  @param string $file
     *  @param string $original_index
     *  @param string $original_order
     *  @param string $value  
     */
    private function swapXmlForSetfeedFeaturedProduct($file,$original_index,$original_order,$value)
    {
        
        $original_index = (int) $original_index;
        $original_order = (int) $original_order;
        if($original_index > 1) {
            $original_index = $original_index - 1;            
        }

        $map = simplexml_load_file($file);
        $map->feedFeaturedProduct->product[$original_index]->slug = $map->feedFeaturedProduct->product[$original_order]->slug;
        $map->feedFeaturedProduct->product[$original_order]->slug = $value;
        $map->asXML($file);
        
    }

    /**
     *  Method used to swap xml contents for feedPromoItems nodes under content_files.xml
     *  @param string $file
     *  @param string $original_index
     *  @param string $original_order
     *  @param string $value  
     */
    private function swapXmlForSetfeedPromoItems($file,$original_index,$original_order,$value)
    {
        
        $original_index = (int) $original_index;
        $original_order = (int) $original_order;
        if($original_index > 1) {
            $original_index = $original_index - 1;            
        }

        $map = simplexml_load_file($file);
        $map->feedPromoItems->product[$original_index]->slug = $map->feedPromoItems->product[$original_order]->slug;
        $map->feedPromoItems->product[$original_order]->slug = $value;
        $map->asXML($file);
        
    }



}



