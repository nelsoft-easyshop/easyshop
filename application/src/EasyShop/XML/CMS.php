<?php

namespace EasyShop\XML;
use EasyShop\Entities\EsProductImage as EsProductImage;
use EasyShop\Entities\EsBrand as EsBrand;
class CMS
{

    const AT_SHOW_PRODUCT_DETAILS = "show product details";

    const AT_SHOW_PRODUCT_LIST = "show product list";

    const NODE_TYPE_PRODUCT = "product";


    /**
     * The xml resource getter
     *
     * @var EasyShop\XML\Resource
     */
    private $xmlResourceGetter;

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;
    
    
    /**
     * Product Manager
     *
     * @var EasyShop\Product\ProductManager
     */
    private $productManager;
    
    /**
     * User Manager
     *
     * @var EasyShop\Product\UserManager
     */
    private $userManager;
    
    
    /**
     * Url Utility
     *
     * @var EasyShop\Utility\urlUtility
     */
    private $urlUtility;
    
    /**
     * Loads dependencies
     *
     */
    public function __construct($xmlResourceGetter, $em, $productManager, $userManager, $urlUtility)
    {
        $this->xmlResourceGetter = $xmlResourceGetter;
        $this->em = $em;
        $this->productManager = $productManager;
        $this->userManager = $userManager;
        $this->urlUtility = $urlUtility;
    }
  

    /**
     *  Syncs values from new_home_page.xml to new_home_page_temp.xml
     *  @param string $tempHomeFile
     *  @param string $homeFile
     *  @param array $sliders
     *  @param int $order
     */
    public function syncTempSliderValues($tempHomeFile, $homeFile ,$sliders, $index = 0)
    {

        $map = simplexml_load_file($tempHomeFile);        
  
        foreach ($sliders as $key => $value) {  
            $map->sliderSection->slide[$key]->template = $value->template;
            $map->asXML($tempHomeFile);                
            $string = $this->getString("sliderSection", "", "", "", "");       
            $this->addXmlFormatted($map,$string,'/map/sliderSection/slide[last()]',"\t\t","\n\n", true, true);   
        }

        foreach($sliders as $key => $value) {

            $this->syncSliderValues($tempHomeFile, $value->image, "", $key, $key );
        }
    }


    /**
     *  Handles the re-ordering of sliderSection parent node
     *  @param string $image
     *  @param string $template
     *  @param int $index
     *  @param int $order
     *  @param int $subIndex
     */
    public function syncSliderValues($file,$image, $template, $index, $order, $subIndex = 0)
    {
        $map = simplexml_load_file($file);

        foreach($image as $key => $insertImages) {
            if(count($image) == 1) {    
                $map->sliderSection->slide[$index]->image[$subIndex]->path = $insertImages->path;
                $map->sliderSection->slide[$index]->image[$subIndex]->target = $insertImages->target;
                $map->asXML($file);                
            }
            else {
                if($subIndex == 0 ) {
                    $map->sliderSection->slide[$index]->image[$subIndex]->path = $insertImages->path;
                    $map->sliderSection->slide[$index]->image[$subIndex]->target = $insertImages->target;
                }
                else {
                    $string = $this->getString("subSliderSection", $insertImages->path, "", "", $insertImages->target);      
                    $this->addXmlFormatted($map,$string,'/map/sliderSection/slide['.($index + 1).']/image[last()]',"\t\t\t","\n\n", true, true);           
                } 
            }
        $map->asXML($file);
        $subIndex++;  
        }        
    }     

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
    public function getString($nodeName, $value = null, $type = null, $coordinate = null, $target = null) 
    {
        if($nodeName == "addBrands") {
             $string = '
            <brandId>'.$value.'</brandId>';   
        }            
        if($nodeName == "addTopSellers") {
             $string = '
            <seller>'.$value.'</seller>';   
        }             
        if($nodeName == "addTopProducts") {
             $string = '
            <product>'.$value.'</product>';   
        }         

        if($nodeName == "newArrivals") {
             $string = '
        <arrival>
                <text>'.$value.'</text>
                <target>'.$target.'</target>
            </arrival>'; 
        }  
        if($nodeName == "categorySectionAdd") {
             $string = '
        <categorySection>
        <categorySlug>'.$value.'</categorySlug>
        <sub>
            <text>Default</text>
            <productSlugs> </productSlugs>
        </sub>
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
            <productSlugs> </productSlugs>            
        </sub>'; 
        }           

        if($nodeName=="productPanel") {
             $string = '
                <productPanel>
             <slug>'.$value.'</slug>
        </productPanel>'; 
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
                <path>'.$type.'</path>
                <target>/</target>
            </image>

        </slide>'; 
        }       
        if($nodeName == "categorySubSlug") {
           $string ='<categorySubSlug>'.$value.'</categorySubSlug>';
        }  
        if($nodeName == "boxContent") {
        $string ='<boxContent>
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
            <productSlugs>'.$value.'</productSlugs>'; 
        }
        if($nodeName == "mainSlide") {

        $string = '
        <mainSlide> 
        <value>'.$value.'</value> 
        <type>image</type>
        <imagemap>
            <target>'.$coordinate.'</target>
        </imagemap>
        <actionType>'.$target.'</actionType>
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
     *  @param int $subIndex  
     *  @param int $subPanelIndex  
     *  @return boolean
     */
    public function removeXmlNode($file,$nodeName,$index = null, $subIndex = null, $subPanelIndex = null) 
    {

        if($nodeName == "tempHomeSlider"){
            $index = 0;
            $map = simplexml_load_file($file);
            $tempSliderCount = count($map->sliderSection->slide);

            foreach ($map->sliderSection->slide as $key => $value) {
                if( $tempSliderCount> 1) {
                    $this->removeXmlNode($file, "mainSliderSection",1);
                    $tempSliderCount--;
                }
                else {
                    $imageCount = count($value->image);
                    foreach($value->image as $images) {
                        if($imageCount > 1) {
                            $this->removeXmlNode($file, "subSliderSection",1,1);
                            $imageCount--;   
                        }
                     }
                }
                $index++;                
            }  
            return true;          
        }
        else if($nodeName == "mainSliderSection"){
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
        else if($nodeName == "newArrival"){
            $referred = "/map/menu/newArrivals/arrival[".$index."]"; 

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
            $subPanelIndex = (int) $subPanelIndex === 0 ? 1 : $subPanelIndex + 1;
            $referred = "//categorySection[$index]/sub[$subIndex]/productSlugs[$subPanelIndex]";
            $xml = new \SimpleXMLElement(file_get_contents($file) );            
            $result = current($xml->xpath($referred));

            $dom = dom_import_simplexml($result[0]);

            $dom->parentNode->removeChild($dom);

            return $xml->asXml($file);
    
        }        
        else if($nodeName == "subCategorySection") {
            $referred = "/map/categorySection[".$index."]"."/sub[".$subIndex."]"; 

            $doc = new \SimpleXMLElement(file_get_contents($file));
            if($target = current($doc->xpath($referred))) {
                $dom = dom_import_simplexml($target);

                $dom->parentNode->removeChild($dom);
                return $doc->asXml($file);
            }
            else {
                    return false;
            }
        }           
        else if($nodeName == "boxContent") {

            $referred = "/map/section[".$index."]/boxContent[".$subIndex."]"; 
            $doc = new \SimpleXMLElement(file_get_contents($file));
            if($target = current($doc->xpath($referred))) {
                $dom = dom_import_simplexml($target);
                $dom->parentNode->removeChild($dom);
                return $doc->asXml($file);
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

        else if($nodeName == "brands") {
            $xml = new \SimpleXMLElement(file_get_contents($file) );            
            $result = current($xml->xpath( "//brandSection/brandId[$index]" ));

            $dom = dom_import_simplexml($result[0]);

            $dom->parentNode->removeChild($dom);
            if($xml->asXml($file)) {
                return true;            
            }
            else {
                    return false;
            }  
        } 

        else if($nodeName == "topSellers") {
            $xml = new \SimpleXMLElement(file_get_contents($file) );            
            $result = current($xml->xpath( "//topSellers/seller[$index]" ));

            $dom = dom_import_simplexml($result[0]);

            $dom->parentNode->removeChild($dom);
            if($xml->asXml($file)) {
                return true;            
            }
            else {
                    return false;
            }  
        }        
        else if($nodeName == "topProducts") {
            $xml = new \SimpleXMLElement(file_get_contents($file) );            
            $result = current($xml->xpath( "//topProducts/product[$index]" ));

            $dom = dom_import_simplexml($result[0]);

            $dom->parentNode->removeChild($dom);
            if($xml->asXml($file)) {
                return true;            
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
        $referred = $nodeName.'['.$index.']';
        $doc = new \SimpleXMLElement(file_get_contents($file));
        if($target = current($doc->xpath($referred))) {
            $dom = dom_import_simplexml($target);

            $dom->parentNode->removeChild($dom);
            return ($doc->asXml($file)) ? true : false;
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
    public function addXmlFormatted($file,$xml_string,$target_node,$tabs,$newLines,$move = true, $isSimpleXmlLoaded = false) 
    {        
        $sxe = (!$isSimpleXmlLoaded) ? new \SimpleXMLElement(file_get_contents($file)) : $file;
        $insert = new \SimpleXMLElement($xml_string);
        $target = current($sxe->xpath($target_node));

        $this->simplexml_insert_formatted($insert, $target,$tabs,$newLines,$move);

        if(!$isSimpleXmlLoaded) {
            if($sxe->asXml($file)) {
                return true;
            }        
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
    
    /**
     * Returns the category navigation section
     *
     * @param boolean $isTemporaryFile
     * @return mixed
     */
    public function getMenuData($isTemporaryFile = false)
    {
        $homeXmlFile = (!$isTemporaryFile) ? $this->xmlResourceGetter->getHomeXMLfile() : $this->xmlResourceGetter->getTempHomeXMLfile();
        $xmlContent = $this->xmlResourceGetter->getXMlContent($homeXmlFile);
        
        $homePageData = [];

        $homePageData['menu']['newArrivals'] = isset($xmlContent['menu']['newArrivals']['arrival']) ? $xmlContent['menu']['newArrivals']['arrival'] : [];
        if(isset( $homePageData['menu']['newArrivals']['text'] )){
            $singleNewArrivalNode = $homePageData['menu']['newArrivals'];
            $homePageData['menu']['newArrivals']  = [];
            $homePageData['menu']['newArrivals'][] = $singleNewArrivalNode;
        }

        $homePageData['menu']['topProducts']  = [];
        $homePageData['menu']['topSellers']  = [];
        
        $xmlContent['menu']['topProducts']['product'] = isset($xmlContent['menu']['topProducts']['product'] ) ? $xmlContent['menu']['topProducts']['product']  : [];
        if(!is_array($xmlContent['menu']['topProducts']['product'])){
            $singleProduct = $xmlContent['menu']['topProducts']['product'] ;
            $xmlContent['menu']['topProducts']['product'] = [];
            $xmlContent['menu']['topProducts']['product'][] = $singleProduct;
        }
       
        foreach($xmlContent['menu']['topProducts']['product'] as $productSlug){
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findOneBy(['slug' => $productSlug]);
            if($product){
                if($this->productManager->isProductActive($product)){
                    $homePageData['menu']['topProducts'][] = $product;
                }
            }                    
        }
        
        if(!is_array($xmlContent['menu']['topSellers']['seller'])){
            $singleSeller = $xmlContent['menu']['topSellers']['seller'] ;
            $xmlContent['menu']['topSellers']['seller'] = [];
            $xmlContent['menu']['topSellers']['seller'][] = $singleSeller;
        }
        
        foreach($xmlContent['menu']['topSellers']['seller'] as $sellerSlug){
            $seller['details'] = $this->em->getRepository('EasyShop\Entities\EsMember')
                                          ->findOneBy(['slug' => $sellerSlug]);
            if($seller['details']){
                $seller['image'] = $this->userManager->getUserImage($seller['details']->getIdMember());
                array_push($homePageData['menu']['topSellers'], $seller);
            }
        }
        
        foreach ($xmlContent['categoryNavigation']['category'] as $key => $category) {

            $categoryEntity = $this->em->getRepository('Easyshop\Entities\EsCat')
                             ->findOneBy(['slug' => $category['categorySlug']]);
            if($categoryEntity === null){
                continue;
            }
                
            $featuredCategory['popularCategory'][$key]['category'] = $categoryEntity;
            $featuredCategory['popularCategory'][$key]['subCategory'] = [];
            $category['sub']['categorySubSlug'] = isset($category['sub']['categorySubSlug']) ? $category['sub']['categorySubSlug']  : [];
            if(!is_array($category['sub']['categorySubSlug'])){
                $singleSubcategory = $category['sub']['categorySubSlug'];
                $category['sub']['categorySubSlug'] = [];
                $category['sub']['categorySubSlug'][] = $singleSubcategory;
            }

            foreach ($category['sub']['categorySubSlug'] as $subKey => $subCategory) {
                $subcategoryEntity =  $this->em->getRepository('Easyshop\Entities\EsCat')
                                           ->findOneBy(['slug' => $subCategory]);
                if($subcategoryEntity !== null){
                    $featuredCategory['popularCategory'][$key]['subCategory'][$subKey] = $subcategoryEntity;
                }
            }
        }

        foreach ($xmlContent['categoryNavigation']['otherCategories']['categorySlug'] as $key => $category) {
            $featuredCategory['otherCategory'][$key] = $this->em->getRepository('Easyshop\Entities\EsCat')
                                                                ->findOneBy(['slug' => $category]);
        }
        $homePageData['categoryNavigation'] = $featuredCategory;

        return $homePageData;
    }

    /**
     * Returns the home page data
     * 
     * @param boolean $isTemporaryFile
     * @return mixed
     */
    public function getHomeData($isTemporaryFile = false)
    {
        $homeXmlFile = (!$isTemporaryFile) ? $this->xmlResourceGetter->getHomeXMLfile() : $this->xmlResourceGetter->getTempHomeXMLfile();
        $xmlContent = $this->xmlResourceGetter->getXMlContent($homeXmlFile);

        $homePageData['categorySection'] = [];

        if(isset($xmlContent['categorySection']['categorySlug'])){
            $temporary = $xmlContent['categorySection'];
            $xmlContent['categorySection'] = array();
            array_push($xmlContent['categorySection'], $temporary);
        }

        $xmlContent['categorySection']  = !isset($xmlContent['categorySection']) ? [] : $xmlContent['categorySection'];
        foreach($xmlContent['categorySection'] as $categorySection){
            $sectionData['category'] = $this->em->getRepository('EasyShop\Entities\EsCat')
                                                ->findOneBy(['slug' => $categorySection['categorySlug']]);                                     
           
            if(!isset($categorySection["sub"])){
                $categorySection["sub"] = [];
            }

            if(isset($categorySection['sub']['text'])){
                $subTemporary = $categorySection['sub'];
                $categorySection['sub'] = [ $subTemporary ];
            }   
            
            $sectionData['products'] = [];
            $sectionData['subHeaders'] = [];

            $isFirstRun = true;
            foreach ($categorySection['sub'] as $index => $subHeaderSection) {
            
                $subHeaderSection['text'] = (is_array($subHeaderSection['text']) && empty($subHeaderSection['text'])) ? '' : $subHeaderSection['text'];
                
                if (!isset($subHeaderSection['productSlugs']) || !$subHeaderSection['productSlugs']) {
                    $subHeaderSection['productSlugs'] = [];
                }  
                $sectionData['subHeaders'][$index] = $subHeaderSection;
                
                if(!$isFirstRun){
                    continue;
                }

                if(!is_array($subHeaderSection['productSlugs'] )){
                    $subHeaderSection['productSlugs'] = [ $subHeaderSection['productSlugs'] ];
                }

                foreach ($subHeaderSection['productSlugs'] as $idx => $xmlProductData) {
                    $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                        ->findOneBy(['slug' => $xmlProductData]);
                    if ($product) {
                        if($this->productManager->isProductActive($product)){
                            $sectionData['products'][$idx]['product'] =  $this->productManager->getProductDetails($product);
                            $secondaryImage =  $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                                        ->getSecondaryImage($product->getIdProduct());
                            $sectionData['products'][$idx]['productSecondaryImage'] = $secondaryImage;
                            $sectionData['products'][$idx]['userimage'] =  $this->userManager->getUserImage($product->getMember()->getIdMember());
                        }
                    }
                }
                
                $isFirstRun = false;
            }

            $homePageData['categorySection'][] = $sectionData;
        }

        

        $homePageData['adSection'] = isset($xmlContent['adSection']['ad']) ? $xmlContent['adSection']['ad'] : [];
       
        if(isset($homePageData['adSection']['img'])){
            $temporaryAdSection = $homePageData['adSection'];
            $homePageData['adSection'] = [ $temporaryAdSection ] ;
        }

        $sliderTemplates = [];
        if (isset($xmlContent['sliderTemplate']['template'])) {
            foreach($xmlContent['sliderTemplate']['template'] as $template){
                $sliderTemplates[] = $template['templateName'];
            }
        }

        $homePageData['slider'] = isset($xmlContent['sliderSection']['slide']) ? $xmlContent['sliderSection']['slide'] : [];     
        if(isset($homePageData['slider']['template'])){
            $singeSlider =  $homePageData['slider'];
            $homePageData['slider'] = [];
            $homePageData['slider'][] = $singeSlider;
        }
         
        foreach($homePageData['slider'] as $idx => $slide){
            $template = in_array($slide['template'],$sliderTemplates) ? 'template'.$slide['template'] : 'templateA';
            $template = 'partials/homesliders/'.$template;
            $homePageData['slider'][$idx]['template'] = $template;            
            if(isset($homePageData['slider'][$idx]['image']['path'])){
                $temporary = $homePageData['slider'][$idx]['image'];
                $homePageData['slider'][$idx]['image'] = array();
                array_push($homePageData['slider'][$idx]['image'], $temporary);
            }
            
            foreach($homePageData['slider'][$idx]['image'] as $index => $sliderImage){
                $homePageData['slider'][$idx]['image'][$index]['path'] = empty($sliderImage['path']) ? '/' : $sliderImage['path'];
                $target = $sliderImage['target'];
                $homePageData['slider'][$idx]['image'][$index]['target'] = $this->urlUtility->parseExternalUrl($target);
            }
        }

        //Get feature vendor details
        $featuredVendor['memberEntity'] = $this->em->getRepository('EasyShop\Entities\EsMember')
                                                   ->findOneBy(['slug' => $xmlContent['sellerSection']['sellerSlug']]);
        $featuredVendor['vendor_image'] = array();
        $featuredSellerId = 0;
        if($featuredVendor['memberEntity']){
            $featuredVendor['vendor_image'] = $this->userManager->getUserImage($featuredVendor['memberEntity']->getIdMember());
            $featuredSellerId = $featuredVendor['memberEntity']->getIdmember();
        }
        $featuredVendor['banner'] = $xmlContent['sellerSection']['sellerBanner'];
        $featuredVendor['logo'] = $xmlContent['sellerSection']['sellerLogo'];

        if(!isset($xmlContent['sellerSection']['productPanel'])){
            $xmlContent['sellerSection']['productPanel'] = array();
        }
        shuffle($xmlContent['sellerSection']['productPanel']);    
        $featuredVendor['product'] = [];
          
        foreach ($xmlContent['sellerSection']['productPanel'] as $key => $product) {
        
            $productSlug = isset($product['slug']) ? $product['slug'] : $product;        
            $productData = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->findOneBy(['slug' => $productSlug, 'member' => $featuredSellerId]);
            if($productData){
                if($this->productManager->isProductActive($productData)){
                    $featuredVendor['product'][$key]['product'] = $this->productManager->getProductDetails($productData);
                    $secondaryProductImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                                    ->getSecondaryImage($productData->getIdProduct());
                    $featuredVendor['product'][$key]['secondaryProductImage'] = $secondaryProductImage;    
                }
            }
        }
        $homePageData['seller'] = $featuredVendor;
        
        //Get Popular Brands
        $popularBrands = [];
        if(isset($xmlContent['brandSection']['brandId'])){
            if(!is_array($xmlContent['brandSection']['brandId'])){
                $temporaryBrandId = $xmlContent['brandSection']['brandId'];
                $xmlContent['brandSection']['brandId'] = [ $temporaryBrandId ];
            }
            foreach ($xmlContent['brandSection']['brandId'] as $key => $brandId) {
                $brandObject =  $this->em->getRepository('EasyShop\Entities\EsBrand')
                                         ->findOneBy(['idBrand' => $brandId]);
                if($brandObject){
                    $popularBrands[$key]['brand'] = $brandObject;
                    $popularBrands[$key]['image']['directory'] = EsBrand::IMAGE_DIRECTORY;
                    $popularBrands[$key]['image']['file'] = $popularBrands[$key]['brand'] && trim($popularBrands[$key]['brand']->getImage())  !== ""  ?
                                                                            $popularBrands[$key]['brand']->getImage() : EsBrand::IMAGE_UNAVAILABLE_FILE;
                }
            }
        }

        $homePageData['popularBrands'] = $popularBrands;

        return $homePageData;
    }
    
    

    /**
     * Returns the mobile home page data
     * @return array
     */
    public function getMobileHomeData($baseUrl)
    {
        $homeXmlFile = $this->xmlResourceGetter->getMobileXMLfile();
        $pageContent = $this->xmlResourceGetter->getXMlContent($homeXmlFile); 

        if(!isset($pageContent['mainSlide'][0])){
            $temp = $pageContent['mainSlide'];
            $pageContent['mainSlide'] = [];
            $pageContent['mainSlide'][] = $temp;
        }

        // banner images
        $bannerImages = [];
        foreach ($pageContent['mainSlide'] as $key => $value) {
            $bannerImages[] = [
                'name' => '0',
                'image' => isset($value['value']) ? $value['value'] : "", 
                'target' => !isset($value['imagemap']['target']) || empty($value['imagemap']['target'])
                            ? "" : $value['imagemap']['target'],
                'actionType' => !isset($value['actionType']) || empty($value['actionType'])
                                ? "" : trim($value['actionType']),
            ];
        }

        $sectionImages = [
            'name' => '',
            'bgcolor' => '',
            'type' => 'promo',
            'data' => $bannerImages,
        ]; 

        $productSections[] = $sectionImages;


        if(!isset($pageContent['section'][0])){
            $temp = $pageContent['section'];
            $pageContent['section'] = [];
            $pageContent['section'][] = $temp;
        }

        foreach ($pageContent['section'] as $value) {
            $productArray = []; 

            if(!isset($value['boxContent'][0])){
                $temp = $value['boxContent'];
                $value['boxContent'] = [];
                $value['boxContent'][] = $temp;
            }

            foreach ($value['boxContent'] as $valueLevel2) {

                $slug = isset($valueLevel2['value']) ? $valueLevel2['value'] : ""; 
                $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->findOneBy(['slug' => $slug]);

                $productName = "";
                $productSlug = "";
                $productBasePrice = 0;
                $productFinalPrice = 0;
                $productDiscount = 0;
                $productImagePath = "";
                $target = "";

                if((string) $valueLevel2['type'] === self::NODE_TYPE_PRODUCT){
                    if($product){
                        $product = $this->productManager->getProductDetails($product->getIdProduct());

                        $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                                 ->getDefaultImage($product->getIdProduct());
            
                        $directory = EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
                        $imageFileName = EsProductImage::IMAGE_UNAVAILABLE_FILE;

                        if($productImage != null){
                            $directory = $productImage->getDirectory();
                            $imageFileName = $productImage->getFilename();
                        }

                        $productName = utf8_encode($product->getName());
                        $productSlug = $product->getSlug();
                        $productDiscount = floatval($product->getDiscountPercentage());
                        $productBasePrice = floatval($product->getPrice());
                        $productFinalPrice = floatval($product->getFinalPrice());
                        $productImagePath = $directory.$imageFileName;
                        if((string) trim($valueLevel2['actionType']) === self::AT_SHOW_PRODUCT_DETAILS){
                            $target = $baseUrl.'mobile/product/item/'.$productSlug;
                        }
                        else{
                            $target = empty($valueLevel2['target']) || !isset($valueLevel2['target']) 
                                      ? "" : $valueLevel2['target'];
                        }
                    }

                    $productArray[] = [
                        'name' => $productName,
                        'slug' => $productSlug,
                        'discount_percentage' => $productDiscount,
                        'base_price' => $productBasePrice,
                        'final_price' => $productFinalPrice,
                        'image' => $productImagePath, 
                        'actionType' => !isset($valueLevel2['actionType']) || empty($valueLevel2['actionType'])
                                        ? "" : trim($valueLevel2['actionType']),
                        'target' => $target,
                    ];
                }
            }

            $categoryObject = $this->em->getRepository('EasyShop\Entities\EsCat')
                                       ->findOneBy(['slug' => $value['name']]);

            $categoryName = "";
            $categoryIcon = $baseUrl.EsBrand::IMAGE_DIRECTORY.EsBrand::IMAGE_UNAVAILABLE_FILE;
            if($categoryObject){
                $categoryName = $categoryObject->getName();
                $categorySlug = $categoryObject->getSlug();

                $categoryIconObject = $this->em->getRepository('EasyShop\Entities\EsCatImg')
                                               ->findOneBy(['idCat' => $categoryObject->getIdCat()]);

                if($categoryIconObject){
                    $categoryIcon = $baseUrl.'assets/'.$categoryIconObject->getPath();
                }

                $productArray[] = [
                    'name' => "",
                    'slug' => "",
                    'discount_percentage' => 0,
                    'base_price' => 0,
                    'final_price' => 0,
                    'image' => "",
                    'actionType' => self::AT_SHOW_PRODUCT_LIST,
                    'target' => $baseUrl.'mobile/category/getCategoriesProduct?slug='.$categorySlug,
                ];
            }

            $productSections[] = [
                'name' => $categoryName,
                'bgcolor' => $value['bgcolor'],
                'type' => $value['type'],
                'icon' => $categoryIcon,
                'data' => $productArray,
            ];
        }

        $display = [
            'section' => $productSections,
        ];

        return $display;
    }
    
    /**
     * Gets the category page header data
     *
     * @param string $categorySlug
     * @return mixed
     */
    public function getCategoryPageHeader($categorySlug)
    {  
        $categoryXmlFile = $this->xmlResourceGetter->getCategoryXmlFile();
        $categoryXmlObjects = $this->xmlResourceGetter->getXMlContent($categoryXmlFile, $categorySlug, 'category'); 

        $categoryXmlArray = json_decode(json_encode((array) $categoryXmlObjects), 1);

        if(isset($categoryXmlArray[0])  && $categoryXmlArray[0] === false){
            return false;
        }
   
        if(isset($categoryXmlArray['top']['image']['path']) || isset($categoryXmlArray['top']['image']['target'])  ){
            $singleBanner = $categoryXmlArray['top']['image'];
            $categoryXmlArray['top']['image'] = [];
            $categoryXmlArray['top']['image'][] = $singleBanner;
        }
  
        if(isset($categoryXmlArray['bottom']['image']['path']) || isset($categoryXmlArray['bottom']['image']['target'])  ){
            $singleBanner = $categoryXmlArray['bottom']['image'];
            $categoryXmlArray['bottom']['image'] = [];
            $categoryXmlArray['bottom']['image'][] = $singleBanner;
        }
        
        if(isset($categoryXmlArray['top'])){
            foreach($categoryXmlArray['top']['image'] as $index => $topImage){
                $target =  $categoryXmlArray['top']['image'][$index]['target'];
                $categoryXmlArray['top']['image'][$index]['target'] = $this->urlUtility->parseExternalUrl( $target );
            }
        }
        if(isset($categoryXmlArray['bottom'])){
            foreach($categoryXmlArray['bottom']['image'] as $index => $topImage){
                $target =  $categoryXmlArray['bottom']['image'][$index]['target'];
                $categoryXmlArray['bottom']['image'][$index]['target'] = $this->urlUtility->parseExternalUrl( $target );
            }
        }
        
        return $categoryXmlArray;
    }
    
    /**
     * Retrieves the featured products
     *
     * @param integer $memberId
     * @return EasyShop\Entities\EsProduct[]
     */
    public function getFeaturedProducts($memberId)
    {
        $followedSellerIds = [];
        $miscellaneousXmlFile = $this->xmlResourceGetter->getMiscellaneousXmlFile();
        
        $usersBeingFollowed = $this->em->getRepository('\EasyShop\Entities\EsVendorSubscribe')
                                       ->getUserFollowing($memberId);
        foreach($usersBeingFollowed['following'] as $userBeingFollowed){
            $followedSellerIds[] = $userBeingFollowed->getVendor()->getIdMember();
          
        }
        
        $easyshopId = trim($this->xmlResourceGetter->getXMlContent($miscellaneousXmlFile, 'easyshop-member-id', 'select'));
        $easyshopId = empty($easyshopId) ? [] :  [ $easyshopId ];
        $partnerIds = trim($this->xmlResourceGetter->getXMlContent($miscellaneousXmlFile, 'partners-member-id', 'select'));
        $partnerIds = empty($partnerIds) ? [] : explode(',', $partnerIds);
        $followedSellerIds = array_merge($followedSellerIds, $easyshopId);
        $followedSellerIds = array_merge($followedSellerIds, $partnerIds);
        $followedSellerIds = array_map('intval', $followedSellerIds);
        $followedSellerIds = array_unique($followedSellerIds);

        $products = $this->productManager->getRandomProductsFromUsers($followedSellerIds, 10);

        $featuredProductSlugs = [];        
        foreach($products as $index => $product){
            if(!$this->productManager->isProductActive($product)){
                continue;
            }
            $featuredProductSection['products'][$index]['product'] =  $this->productManager->getProductDetails($product);
            $secondaryImage =  $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                        ->getSecondaryImage($product->getIdProduct());
            $featuredProductSection['products'][$index]['productSecondaryImage'] = $secondaryImage;
            $featuredProductSection['products'][$index]['userimage'] =  $this->userManager->getUserImage($product->getMember()->getIdMember());  
            $featuredProductSlugs[] = $product->getSlug();
        }

        $featuredProductSection['subHeaders'] = [];
        $featuredProductSection['subHeaders'][] = [
            'productSlugs' => $featuredProductSlugs,
            'text' => 'Followed Sellers',
        ];

        $miscellaneousFileContents = $this->xmlResourceGetter->getXMlContent($miscellaneousXmlFile); 
        $promoProductSlugs = [];
        foreach($miscellaneousFileContents['feedPromoItems']['product'] as $promoProduct){
            $promoProductSlugs[] = $promoProduct['slug']; 
        }

        $featuredProductSection['subHeaders'][] = [
            'productSlugs' => $promoProductSlugs,
            'text' => 'Promos',
        ];

        $newProductSlugs = $this->em->getRepository('\EasyShop\Entities\EsProduct')
                                    ->getNewestProductSlugs();

        $featuredProductSection['subHeaders'][] = [
            'productSlugs' => $newProductSlugs,
            'text' => 'New Products',
        ];

        return $featuredProductSection;
    }

    /**
     * Get products for widgets
     * @param  integer $productCount
     * @return mixed
     */
    public function getWidgetProducts($productCount)
    {
        $widgetXml = $this->xmlResourceGetter->getWidgetXmlFile();
        $widgetXmlContent = $this->xmlResourceGetter->getXMlContent($widgetXml);
        $slugs = $widgetXmlContent['displayProducts']['slug'];
        shuffle($slugs);
        $finalSlugs = array_slice($slugs, 0, $productCount);

        $products = [];
        foreach ($finalSlugs as $slug) {
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findOneBy(['slug' => trim($slug)]);
            if($product){
                $products[] = $this->productManager->getProductDetails($product);
            }
        }

        return $products;
    }
}




