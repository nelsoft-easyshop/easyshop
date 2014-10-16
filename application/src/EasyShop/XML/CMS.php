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

        if($nodeName == "categorySectionAdd") {
             $string = '
        <categorySection>
        <categorySlug>'.$value.'</categorySlug>
        <sub>
            <text>Default</text>
            <target>/</target>
        </sub>
        <productPanel>
            <slug>kj-star-wireless-mobile-phone-monopod</slug>
        </productPanel>            
    </categorySection>'; 
        }   
        if($nodeName == "otherCategories") {
             $string = '
            <categorySlug>'.$value.'</categorySlug>';       
         }           

        if($nodeName == "subCategorySection") {
             $string = '
        <sub>
            <text>'.$value.'</text>
            <target>'.$target.'</target>
        </sub>'; 
        }           

        if($nodeName == "adsSection") {
             $string = '
            <ad>
            <img>'.$value.'</img>
            <target>'.$target.'</target>
        </ad>'; 
        }         
        if($nodeName == "subSliderSection") {
             $string = '<image>
                <path>'.$value.'</path>
                <target>'.$target.'</target>
            </image>'; 
        }        
        if($nodeName == "sliderSection") {
    $string = '<slide>
            <template>'.$value.'</template>
            <image>
                <path>unavailable_product_img.jpg</path>
                <target>/</target>
            </image>

        </slide>'; 
        }
        if($nodeName == "categorySubSlug") {
           $string ='<categorySubSlug>'.$value.'</categorySubSlug>';
        }  
        if($nodeName == "boxContent") {
            $string ='


            <boxContent>
            <value>'.$value.'</value>
        <type>'.$type.'</type>
            <target>'.$coordinate.'</target>
            <actionType>'.$target.'</actionType>
        </boxContent>';
        }        
        if($nodeName=="feedFeaturedProduct") {
            $string = '
                    <product>
            <slug>'.$value.'</slug>
        </product>
            '; 
        }
        if($nodeName=="feedPopularItems") {
            $string = '
                    <product>
            <slug>'.$value.'</slug>
        </product>
            ';
        }
        if($nodeName=="feedPromoItems") {
            $string = '
                    <product>
            <slug>'.$value.'</slug>
        </product>
            ';
        }
        if($nodeName == "product_panel" ) {
            $string = '<product_panel>
            <value>'.$value.'</value> 
            <type>'.$type.'</type>
        </product_panel>'; 
        }        
        if($nodeName == "productPanelNew" ) {
            $string = '
        <productPanel>
            <slug>'.$value.'</slug>
        </productPanel>'; 
        }
        if($nodeName == "mainSlide") {

 $string = '<mainSlide> 
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
        if($nodeName == "product_panel_main") {
            $referred = "/map/section[".$index.']/product_panel_main['.$productindex.']';            
        }
        else {
            $referred = "/map/section[".$index.']/product_panel['.$productindex.']';            
        }
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
     *  Method used to remove xml contents for  under new_home_files.xml
     *
     *  @param string $file
     *  @param string $nodeName
     *  @param int $index
     *  @param int $productindex  
     *  @return boolean
     */
    public function removeXmlNode($file,$nodeName,$index, $subIndex) 
    {

        if($nodeName == "mainSliderSection"){
            $referred = "/map/sliderSection/slide[".$index."]"; 

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

        else if($nodeName == "categorySectionPanel"){
            $referred = "/map/categorySection[".$index."]"; 

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
        else if($nodeName == "subSliderSection"){
            $referred = "/map/sliderSection/slide[".$index.']/image['.$subIndex.']'; 

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
        else if($nodeName == "categoryProductPanel") {

            $referred = "/map/categorySection[".$index."]/productPanel[".$subIndex."]"; 

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
        else if($nodeName == "categorySection") {

            $referred = "/map/categorySection[".$index."]/sub[".$subIndex."]"; 

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
        else if($nodeName == "adsSection") {
            $referred = "/map/adSection/ad[".$index."]"; 

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
        else if($nodeName == "productPanel") {
            $referred = "/map/sellerSection/productPanel[".$index."]"; 

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
        else if($nodeName == "otherCategories") {
            $xml = new \SimpleXMLElement(file_get_contents($file) );            
            $result = current($xml->xpath( "//otherCategories/categorySlug[$index]" ));

            $dom = dom_import_simplexml($result[0]);

            $dom->parentNode->removeChild($dom);
            if($xml->asXml($file)) {
                return true;            
            }
            else {
                    return false;
            }  
        }        
        else {
            $xml = new \SimpleXMLElement(file_get_contents($file) );            
            $result = current($xml->xpath( "//category[$index]/sub/categorySubSlug[$subIndex]" ));

            $dom = dom_import_simplexml($result[0]);

            $dom->parentNode->removeChild($dom);
            if($xml->asXml($file)) {
                return true;            
            }
            else {
                    return false;
            }            
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
    public function addXmlFormatted($file,$xml_string,$target_node,$tabs,$newLines,$move = true) 
    {        
        $sxe = new \SimpleXMLElement(file_get_contents($file));
        $insert = new \SimpleXMLElement($xml_string);
        $target = current($sxe->xpath($target_node));

        $this->simplexml_insert_formatted($insert, $target,$tabs,$newLines,$move);
        if($sxe->asXml($file)) {
            return true;

        }
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
    public function simplexml_insert_formatted(\SimpleXMLElement $insert, \SimpleXMLElement $target,$tabs,$newLines,$move = true) 
    {
        $target_dom = dom_import_simplexml($target);

        $document = $target_dom->ownerDocument;
        $insert_dom = $document->importNode(dom_import_simplexml($insert), true);
        $document->formatOutput = true;
        $parentNode = $target_dom->parentNode;
        if($move){
            if ($target_dom->nextSibling) {
                $result =  $parentNode->insertBefore($insert_dom, $target_dom->nextSibling);
                $parentNode->insertBefore($document->createTextNode($newLines), $result);
                $parentNode->insertBefore($document->createTextNode($tabs), $result);

            } 
            else {
                $result =  $target_dom->parentNode->appendChild($insert_dom);
            }
        } 
        else {
            $parentNode->insertBefore($insert_dom,$result);   

        }
        return $result;
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
        $tabs = "\t\t\t\t";
        if($move){
            if ($target_dom->nextSibling) {
                $result =  $parentNode->insertBefore($insert_dom, $target_dom->nextSibling);
                $parentNode->insertBefore($document->createTextNode("\n"), $result);
                $parentNode->insertBefore($document->createTextNode($tabs), $result);

            } 
            else {
                $result =  $target_dom->parentNode->appendChild($insert_dom);
            }
        } 
        else {
            $parentNode->insertBefore($insert_dom,$result);   

        }
        return $result;
    }
}




