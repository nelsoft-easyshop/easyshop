<?php 

class FeedWebService extends MY_Controller 
{

    /**
     *  Constructor call for Administrator's authentication. Authentication method is located in MY_Controller.php
     *
     *  
     */
    private $map, $targetNode, $xmlCmsService;
    public $xmlFileService;
    
    public function __construct()
    {
        parent::__construct();
        $this->xmlCmsService = $this->serviceContainer['xml_cms'];
        $this->xmlFileService = $this->serviceContainer['xml_resource'];
        $this->declareEnvironment();
        if($this->input->get()) {
            $this->authentication($this->input->get(), $this->input->get('hash'));
        }  

    }
    /**
     *  Environment declarations
     */
    public function declareEnvironment()
    {
        $this->file  = APPPATH . "resources/". $this->xmlFileService->getContentXMLfile().".xml"; 
        $this->map = new SimpleXMLElement(file_get_contents($this->file));
        $this->json = file_get_contents(APPPATH . "resources/json/jsonp.json");


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
        $string = $this->xmlCmsService->getString("feedPromoItems",$this->input->get("slug"), "", "", "");
        $addXml = $this->xmlCmsService->addXml($this->file,$string,'/map/feedPromoItems/product[last()]');
        if($addXml === TRUE) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }
    }

    /**
     *  Sets xml node under feedPromoItems 
     */
    public function setfeedPromoItems()
    {
        $index = (int) $this->input->get("index");
        $order = (int) $this->input->get("order");

        $map = simplexml_load_file($this->file);

        $slug = $this->input->get("slug") == "" ? $map->feedPromoItems->product[$index]->slug : $this->input->get("slug");
        $string = $this->xmlCmsService->getString("feedPromoItems",$slug, "", "", "");

        if($index > count($map->feedPromoItems->product) - 1    || $order > count($map->feedPromoItems->product) - 1 || $index < 0 || $order < 0) {
               exit("Parameters out of bounds");
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
        $string = $this->xmlCmsService->getString("feedPopularItems",$this->input->get("slug"), "", "", "");
        $addXml = $this->xmlCmsService->addXml($this->file,$string,'/map/feedPopularItems/product[last()]');
        if($addXml === TRUE) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
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

        $map = simplexml_load_file($this->file);

        $slug = $this->input->get("slug") == "" ? $map->feedPopularItems->product[$index]->slug : $this->input->get("slug");
        $string = $this->xmlCmsService->getString("feedPopularItems",$slug, "", "", "");
        if($index > count($map->feedPopularItems->product) - 1    || $order > count($map->feedPopularItems->product) - 1 || $index < 0 || $order < 0) {
                exit("Parameters out of bounds");
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
        $string = $this->xmlCmsService->getString("feedFeaturedProduct",$this->input->get("slug"), "", "", "");
        $addXml = $this->xmlCmsService->addXml($this->file,$string,'/map/feedFeaturedProduct/product[last()]');
        if($addXml === TRUE) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output($this->json);
        }
    }

    /**
     *  Sets xml node under feedFeaturedProduct
     */    
    public function setfeedFeaturedProduct()
    {
        $index = (int) $this->input->get("index");
        $order = (int) $this->input->get("order");

        $map = simplexml_load_file($this->file);

        $slug = $this->input->get("slug") == "" ? $map->feedFeaturedProduct->product[$index]->slug : $this->input->get("slug");
        $string = $this->xmlCmsService->getString("feedFeaturedProduct",$slug, "", "", "");

        if($index > count($map->feedFeaturedProduct->product) - 1    || $order > count($map->feedFeaturedProduct->product) - 1 || $index < 0 || $order < 0) {
                exit("Parameters out of bounds");
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
        $choice = $this->input->get("choice");
        $xPath = "//$choice/img";
        $target = current($this->map->xpath($xPath)); 
        $node = dom_import_simplexml($target); 
        $node->nodeValue = $this->input->get("img");

        $xPath = "//$choice/target";
        $target = current($this->map->xpath($xPath)); 
        $node = dom_import_simplexml($target); 
        $node->nodeValue = $this->input->get("target");

        if($this->map->asXml($this->file)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->json);
        }
    }

    /**
     *  Sets xml node under sekect nodes
     */
    public function setSelect()
    {
        $value = $this->input->get("value");
        $id = $this->input->get("id");
        $xPath = "/map/select[@id='".$id."']";
        $target = current($this->map->xpath($xPath)); 
        $node = dom_import_simplexml($target); 
        $node->nodeValue = $value;
        if($this->map->asXml($this->file)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->json);
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



