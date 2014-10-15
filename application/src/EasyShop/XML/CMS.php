<?php 

namespace EasyShop\XML;
use EasyShop\Entities\EsBrand as EsBrand;
class CMS
{

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
     * Loads dependencies
     *
     * @param EasyShop\XML\Resource
     */
    public function __construct($xmlResourceGetter, $em, $productManager, $userManager)
    {
        $this->xmlResourceGetter = $xmlResourceGetter;
        $this->em = $em;
        $this->productManager = $productManager;
        $this->userManager = $userManager;
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
    public function getString($nodeName, $value, $type, $coordinate, $target) 
    {

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
            $parentNode->insertBefore($insert_dom,$result);   

        }
        return $result;
    }
    
    
    /**
     * Returns the home page data
     *
     * 
     */
    public function getHomeData()
    {
        $homeXmlFile = $this->xmlResourceGetter->getHomeXMLfile();
        $xmlContent = $this->xmlResourceGetter->getXMlContent($homeXmlFile);
        
        $homePageData = array();
        $homePageData['categorySection'] = array(); 
        

        if(isset($xmlContent['categorySection']['categorySlug'])){
            $temporary = $xmlContent['categorySection'];
            $xmlContent['categorySection'] = array();
            array_push($xmlContent['categorySection'], $temporary);
        }
        
        foreach($xmlContent['categorySection'] as $categorySection){
            $sectionData['category'] = $this->em->getRepository('EasyShop\Entities\EsCat')
                                                    ->findOneBy(['slug' => $categorySection['categorySlug']]);
            $sectionData['subHeaders'] = $categorySection['sub'];
            
            foreach($categorySection['productPanel'] as $idx=>$product){
                $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->findOneBy(['slug' => $product['slug']]);
                $sectionData['products'][$idx]['product'] =  $this->productManager->getProductDetails($product->getIdProduct());
                $sectionData['products'][$idx]['userimage'] =  $this->userManager->getUserImage($product->getMember()->getIdMember());   
            }
            array_push($homePageData['categorySection'], $sectionData);
        }
        
        $homePageData['adSection'] = $xmlContent['adSection']['ad'];

        //Get Category Navigation
        foreach ($xmlContent['categoryNavigation']['category'] as $key => $category) {
            $featuredCategory['popularCategory'][$key]['category'] = $this->em->getRepository('Easyshop\Entities\EsCat')
                                                                                ->findOneBy(['slug' => $category['categorySlug']]);

            foreach ($category['sub']['categorySubSlug'] as $subKey => $subCategory) {
            $featuredCategory['popularCategory'][$key]['subCategory'][$subKey] = $this->em->getRepository('Easyshop\Entities\EsCat')
                                                                                ->findOneBy(['slug' => $subCategory]);
            }
        }

        foreach ($xmlContent['categoryNavigation']['otherCategories']['categorySlug'] as $key => $category) {
        $featuredCategory['otherCategory'][$key] = $this->em->getRepository('Easyshop\Entities\EsCat')
                                                                ->findOneBy(['slug' => $category]);
        }
        $homePageData['categoryNavigation'] = $featuredCategory;

        //Get Popular Brands
        foreach ($xmlContent['brandSection']['brandId'] as $key => $brandId) {
            $popularCategory['popularBrand'][$key]['brand'] = $this->em->getRepository('EasyShop\Entities\EsBrand')
                                            ->findOneBy(['idBrand' => $brandId]);
            $popularCategory['popularBrand'][$key]['image']['directory'] = EsBrand::IMAGE_DIRECTORY;
            $popularCategory['popularBrand'][$key]['image']['file'] =
                $popularCategory['popularBrand'][$key]['brand']->getImage() ?: EsBrand::IMAGE_UNAVAILABLE_FILE;
        }
        $homePageData['popularCategory'] = $popularCategory;

        return $homePageData;
    }
    
    
}




