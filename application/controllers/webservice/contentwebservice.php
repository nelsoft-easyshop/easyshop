<?php 

class Contentwebservice extends MY_Controller 
{

    /**
     *  Constructor call for Administrator's authentication. Authentication method is located in MY_Controller.php
     *
     *  
     */
    private $map, $targetNode, $xmlCmsService;
    
    public function __construct()
    {
        parent::__construct();
        $this->declareEnvironment();
        $this->xmlCmsService = $this->serviceContainer['xml_cms'];
        if($this->input->post()) {
            $this->authentication($this->input->post(), $this->input->post('hash'));
        }  

    }
    /**
     *  Environment declarations
     */
    public function declareEnvironment()
    {
        $this->file = APPPATH . "resources/page/content_files.xml"; 
        $this->map = new SimpleXMLElement(file_get_contents($this->file));

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
        $string = $this->xmlCmsService->getString("feedPromoItems",$this->input->post("slug"), "", "", "");
        $addXml = $this->xmlCmsService->addXml($this->file,$string,'/map/feedPromoItems/product[last()]');
        if($addXml === TRUE) {
            return json_encode("success");
        }
    }

    /**
     *  Sets xml node under feedPromoItems 
     */
    public function setfeedPromoItems()
    {
        $index = (int) $this->input->post("index");
        $order = (int) $this->input->post("order");

        $map = simplexml_load_file($this->file);

        $slug = $this->input->post("slug") == "" ? $map->feedPromoItems->product[$index]->slug : $this->input->post("slug");
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
                        $this->xmlCmsService->swapXmlForSetfeedPromoItems($this->file,$this->input->post("index"),$this->input->post("order"),$slug);
                    }
                    else {
                        $index = ($index == 0 ? 1 : $index + 1);
                        $this->xmlCmsService->removeXML($this->file,"/map/feedPromoItems/product",$index);
                        $order = ($order == 0 ? 1 : $order);
                        $this->xmlCmsService->addXml($this->file,$string,'/map/feedPromoItems/product['.$order.']');
                     
                    } 
                }

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
        $string = $this->xmlCmsService->getString("feedPopularItems",$this->input->post("slug"), "", "", "");
        $addXml = $this->xmlCmsService->addXml($this->file,$string,'/map/feedPopularItems/product[last()]');
        if($addXml === TRUE) {
           return json_encode("success");
        }
    }

    /**
     *  Sets xml node under feedPopularItems
     *
     */
    public function setfeedPopularItems()
    {
        $index = (int) $this->input->post("index");
        $order = (int) $this->input->post("order");

        $map = simplexml_load_file($this->file);

        $slug = $this->input->post("slug") == "" ? $map->feedPopularItems->product[$index]->slug : $this->input->post("slug");
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
                        $this->xmlCmsService->swapXmlForSetfeedPopularItems($this->file,$this->input->post("index"),$this->input->post("order"),$slug);
                    }
                    else {
                        $index = ($index == 0 ? 1 : $index + 1);
                        $this->xmlCmsService->removeXML($this->file,"/map/feedPopularItems/product",$index);
                        $order = ($order == 0 ? 1 : $order);
                        $this->xmlCmsService->addXml($this->file,$string,'/map/feedPopularItems/product['.$order.']');
                     
                    } 
                }

            }
        }

    }

    /**
     *  Adds xml node under feedFeaturedProduct
     *
     *  @return JSON
     */
    public function addfeedFeaturedProduct()
    {
        $string = $this->xmlCmsService->getString("feedFeaturedProduct",$this->input->post("slug"), "", "", "");
        $addXml = $this->xmlCmsService->addXml($this->file,$string,'/map/feedFeaturedProduct/product[last()]');
        if($addXml === TRUE) {
            return json_encode("success");
        }
    }

    /**
     *  Sets xml node under feedFeaturedProduct
     */    
    public function setfeedFeaturedProduct()
    {
        $index = (int) $this->input->post("index");
        $order = (int) $this->input->post("order");

        $map = simplexml_load_file($this->file);

        $slug = $this->input->post("slug") == "" ? $map->feedFeaturedProduct->product[$index]->slug : $this->input->post("slug");
        $string = $this->xmlCmsService->getString("feedFeaturedProduct",$slug, "", "", "");

        if($index > count($map->feedFeaturedProduct->product) - 1    || $order > count($map->feedFeaturedProduct->product) - 1 || $index < 0 || $order < 0) {
                echo "Parameters out of bounds";
        }
        else {
            if(!isset($order)) {
                $map->feedFeaturedProduct->product[$index]->slug = $slug;
            }
            else {
                if($index <= $order) {
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
                        $this->xmlCmsService->swapXmlForSetfeedFeaturedProduct($this->file,$this->input->post("index"),$this->input->post("order"),$slug);
                    }
                    else {
                        $index = ($index == 0 ? 1 : $index + 1);
                        $this->xmlCmsService->removeXML($this->file,"/map/feedFeaturedProduct/product",$index);
                        $order = ($order == 0 ? 1 : $order);
                        $this->xmlCmsService->addXml($this->file,$string,'/map/feedFeaturedProduct/product['.$order.']');
                     
                    } 
                }

            }
        }

    }

    /**
     *  Sets xml node under feedBanner
     */
    public function setFeedBanner()
    {
        $choice = $this->input->post("choice");
        $xPath = "//$choice/img";
        $target = current($this->map->xpath($xPath)); 
        $node = dom_import_simplexml($target); 
        $node->nodeValue = $this->input->post("img");

        $xPath = "//$choice/target";
        $target = current($this->map->xpath($xPath)); 
        $node = dom_import_simplexml($target); 
        $node->nodeValue = $this->input->post("target");

        if($this->map->asXml($this->file))
        {
            return json_encode("success");
        }
    }

}



