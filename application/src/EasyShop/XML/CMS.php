<?php 

namespace EasyShop\XML;

class CMS
{

    /**
     *  Method used to return the needed strings in adding/settings values of xml nodes. The indentions of the strings are taken 'as-is'
     *
     *  @param string $nodeName
     *  @param string $value
     *  @param string $type
     *  @param string $coordinate
     *  @param string $target
     *
     *  @return string
     */
    public $string;

    public function getString($nodeName, $value, $type, $coordinate, $target) 
    {
        if($nodeName == "product_panel" ) {
            $string = '<product_panel>
            <value>'.$value.'</value> 
            <type>'.$type.'</type>
        </product_panel>'; 
        }
        if($nodeName == "mainSlide") {

 $string = '    
    <mainSlide> 
        <value>'.$value.'</value> 
        <type>image</type>
        <imagemap>
            <coordinate>'.$coordinate.'</coordinate>
            <target>'.$target.'</target>
        </imagemap>
    </mainSlide>';   

        }
        if($nodeName == "product_panel_main") {

            if(strtolower($type) != "image") {

            $string = '<product_panel_main>
                <value>'.$value.'</value> 
                <type>'.$type.'</type>
            </product_panel_main>'; 

            } 
            else {

$string = '<product_panel_main>
            <value>'.$value.'</value> 
            <type>'.$type.'</type>
            <imagemap>
                <coordinate>'.$coordinate.'</coordinate>
                <target>'.$target.'</target>
            </imagemap>
        </product_panel_main>'; 

            }

        }
        if($nodeName == "productSlide") {

            $string = '    
<productSlide>
        <value>'.$value.'</value> 
        <type>'.$type.'</type>
   </productSlide>'; 

        }
        if($nodeName == "typeNode") {

$string = '<typeNode>
        <value>'.$value.'</value>
    </typeNode >'; 
        }
            return $string;
    }

    /**
     *  Method used to swap xml contents for productSlide nodes under home_files.xml
     *  @param string $file
     *  @param string $orindex
     *  @param string $ororder
     *  @param string $value  
     */
    public function swapXmlForSetProductSlide($file,$orindex,$ororder,$value)
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
     *  Method used to swap xml contents for addProductSlide method, user for re-ordering nodes
     *
     *  @param string $file
     *  @param integer $orindex
     *  @param integer $neworindex
     *  @param string $value
     */
    public function swapXmlForAddProductSlide($file, $orindex,$neworindex,$value) 
    {

        $orindex = (int) $orindex;
        $neworindex = (int) $neworindex;

        $map = simplexml_load_file($this->file);
        $map->productSlide[$neworindex]->value = $map->productSlide[$orindex]->value;        
        $map->productSlide[$orindex]->value = $value;

        $map->asXML($this->file);
        
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
    public function swapXmlForAddMainSlide($file, $orindex,$neworindex,$value,$type,$coordinate,$target) 
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
    public function swapXmlForSetMainSlide($file, $orindex,$ororder, $plusin, $plusor, $value,$type,$coordinate,$target) 
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
    public function swapXmlForAddSectionMainSlide_image($file, $newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target) 
    {

        $newprodindex = (int) $newprodindex;
        $newindex = (int) $newindex;

        $map = simplexml_load_file($file);

        $map->section[$index]->product_panel_main[$newprodindex]->value = $map->section[$index]->product_panel_main[$productindex]->value;
        $map->section[$index]->product_panel_main[$newprodindex]->type = $map->section[$index]->product_panel_main[$productindex]->type;
        $map->section[$index]->product_panel_main[$newprodindex]->imagemap->coordinate = $map->section[$index]->product_panel_main[$productindex]->imagemap->coordinate;
        $map->section[$index]->product_panel_main[$newprodindex]->imagemap->target = $map->section[$index]->product_panel_main[$productindex]->imagemap->target;

        $map->section[$index]->product_panel_main[$productindex]->value = $value;
        $map->section[$index]->product_panel_main[$productindex]->type = $value;
        $map->section[$index]->product_panel_main[$productindex]->imagemap->coordinate = $coordinate;
        $map->section[$index]->product_panel_main[$productindex]->imagemap->target = $target;

        $map->asXML($file);
        
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
    public function swapXmlForAddSectionMainSlide_notimage2($file, $newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target) 
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
     *  @param string $value  
     *  @param string $type  
     */
    public function swapXmlForAddSectionMainSlide_notimage1($file, $newprodindex,$newindex,$index,$productindex,$value,$type) 
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
     *  Method used to remove xml contents for product_panel_main nodes under home_files.xml, user for re-ordering
     *
     *  @param string $file
     *  @param string $nodeName
     *  @param string $index
     *  @param string $productindex  
     *
     *  @return boolean
     */
    public function removeXMLForSetSectionMainPanel($file,$nodeName,$index,$productindex) 
    {
        
        $index = (int)$index;
        $productindex = (int)$productindex;

        $referred = "/map/section[".$index.']/product_panel_main['.$productindex.']';
        $doc = new \SimpleXMLElement(file_get_contents($file));
        if($target = current($doc->xpath($referred))) {
            $dom = dom_import_simplexml($target);

            $dom->parentNode->removeChild($dom);
            if($doc->asXml($file)) {
                return true;              
            } 
            else {
                return false;
            }

        } 
        else {
                return false;
            }
    }

    /**
     *  Method used to remove xml nodes under home_files.xml
     *
     *  @return JSON
     */
    public function removeXML($file,$nodeName,$index) 
    {
        $referred = "//".$nodeName.'['.$index.']';
        $doc = new \SimpleXMLElement(file_get_contents($file));
        if($target = current($doc->xpath($referred))) {
            $dom = dom_import_simplexml($target);

            $dom->parentNode->removeChild($dom);
            $doc->asXml($file);


        }
        else {
                return false;
        }
        
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
    public function swapXmlForSectionProduct($file,$newprodindex,$newindex,$value,$type,$index,$productindex) 
    {
        $map = simplexml_load_file($file);
            
        $map->section[$index]->product_panel[$newprodindex]->value = $map->section[$index]->product_panel[$productindex]->value;
        $map->section[$index]->product_panel[$newprodindex]->type = $map->section[$index]->product_panel[$productindex]->type;
    
        $map->section[$index]->product_panel[$productindex]->value = $value;
        $map->section[$index]->product_panel[$productindex]->type = $type;
        $map->asXML($file);
        
    }

    /**
     *  Method used to add xml contents for child nodes under home_files.xml
     *
     *  @param string $file
     *  @param string $xml_string
     *  @param boolean $move
     *
     *  @return boolean
     */
    public function addXmlChild($file,$xml_string,$target_node,$move = true) 
    {
        
        
        $sxe = new \SimpleXMLElement(file_get_contents($file));
        $insert = new \SimpleXMLElement($xml_string);
        $target = current($sxe->xpath($target_node));

        $this->simplexml_insert_after_child($insert, $target,$move);
        if($sxe->asXml($file)) {
            return true;
        }

    }

    /**
     *  Method used to add xml contents for parent nodes under home_files.xml
     *
     *  @param string $file
     *  @param string $xml_string
     *  @param boolean $move
     *
     *  @return boolean
     */
    public function addXml($file,$xml_string,$target_node,$move = true) 
    {
        
        
        $sxe = new \SimpleXMLElement(file_get_contents($file));
        $insert = new \SimpleXMLElement($xml_string);
        $target = current($sxe->xpath($target_node));

        $this->simplexml_insert_after($insert, $target,$move);
        if($sxe->asXml($file)) {
            return true;

        }
    }

    /**
     *  Method used to add xml contents for child nodes under home_files.xml
     *
     *  @param SimpleXmlElement $insert
     *  @param SimpleXmlElement $target
     *  @param boolean $move 
     *
     *  @return boolean $result
     */
    public function simplexml_insert_after_child(\SimpleXMLElement $insert, \SimpleXMLElement $target,$move = true) 
    {
        $target_dom = dom_import_simplexml($target);

        $document = $target_dom->ownerDocument;
        $insert_dom = $document->importNode(dom_import_simplexml($insert), true);
        $document->formatOutput = true;
        $parentNode = $target_dom->parentNode;

        if($move){
            if ($target_dom->nextSibling) {
                $result =  $parentNode->insertBefore($insert_dom, $target_dom->nextSibling);
                $parentNode->insertBefore($document->createTextNode("\n"), $result);
                $parentNode->insertBefore($document->createTextNode("\n"), $result);
                $parentNode->insertBefore($document->createTextNode("\t\t"), $result);
            } 
            else {
                $result =  $target_dom->parentNode->appendChild($insert_dom);
            }
        } 
        else {
            $result =  $parentNode->insertBefore($document->createTextNode("\n"), $target_dom);
            $parentNode->insertBefore($insert_dom,$result);   

        }
        return $result;
    }

    /**
     *  Method used to add xml contents for parent nodes under home_files.xml
     *
     *  @param SimpleXmlElement $insert
     *  @param SimpleXmlElement $target
     *  @param boolean $move
     *
     *  @return boolean $result
     */
    public function simplexml_insert_after(\SimpleXMLElement $insert, \SimpleXMLElement $target,$move = true) 
    {
        $target_dom = dom_import_simplexml($target);

        $document = $target_dom->ownerDocument;
        $insert_dom = $document->importNode(dom_import_simplexml($insert), true);
        $document->formatOutput = true;
        $parentNode = $target_dom->parentNode;

        if($move){
            if ($target_dom->nextSibling) {
                $result =  $parentNode->insertBefore($insert_dom, $target_dom->nextSibling);
                $parentNode->insertBefore($document->createTextNode("\n"), $result);
                $parentNode->insertBefore($document->createTextNode("\n"), $result);
                $parentNode->insertBefore($document->createTextNode("\t\t"), $result);

            } 
            else {
                $result =  $target_dom->parentNode->appendChild($insert_dom);
            }
        } 
        else {
            $result =  $parentNode->insertBefore($document->createTextNode("\n"), $target_dom);
            $parentNode->insertBefore($insert_dom,$result);   

        }
        return $result;
    }
}




