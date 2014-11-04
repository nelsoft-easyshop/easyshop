<?php

namespace EasyShop\XML;
use EasyShop\Entities\EsProductImage as EsProductImage;
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
                <path>/assets/images/homeslider/unavailable_product_img.jpg</path>
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
    
    
    /**
     * Returns the home page data
     *
     * 
     */
    public function getHomeData($isCategoryNavigationOnly = false)
    {
        $homeXmlFile = $this->xmlResourceGetter->getHomeXMLfile();
        $xmlContent = $this->xmlResourceGetter->getXMlContent($homeXmlFile);
        
        $homePageData = array();
        $homePageData['categorySection'] = array(); 


        //Start Get Category Navigation
        $homePageData['menu']['newArrivals'] = $xmlContent['menu']['newArrivals'];
        $homePageData['menu']['topProducts']  = array();
        $homePageData['menu']['topSellers']  = array();
        
        foreach($xmlContent['menu']['topProducts']['product'] as $productSlug){
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findOneBy(['slug' => $productSlug]);
            array_push($homePageData['menu']['topProducts'], $product);
        }
        
        if(!is_array($xmlContent['menu']['topSellers']['seller'])){
            $temp = $xmlContent['menu']['topSellers']['seller'] ;
            $xmlContent['menu']['topSellers']['seller'] = array();
            array_push( $xmlContent['menu']['topSellers']['seller'], $temp);
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
        if($isCategoryNavigationOnly) {
            return $homePageData;
        }
        //End Get Category Navigation
        
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
                if($product){
                    $sectionData['products'][$idx]['product'] =  $this->productManager->getProductDetails($product);
                    $sectionData['products'][$idx]['userimage'] =  $this->userManager->getUserImage($product->getMember()->getIdMember());   
                }
            }
            array_push($homePageData['categorySection'], $sectionData);
        }
        
        $homePageData['adSection'] = $xmlContent['adSection']['ad'];
        $sliderTemplates = $xmlContent['sliderTemplate']['template'];
        $homePageData['slider'] = $xmlContent['sliderSection']['slide'];
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
                $target = $sliderImage['target'];
                $homePageData['slider'][$idx]['image'][$index]['target'] = $this->urlUtility->parseExternalUrl($target);
            }
            
            
        }

        //Get feature vendor details
        $featuredVendor['name'] = $this->em->getRepository('EasyShop\Entities\EsMember')
                                                ->findOneBy(['slug' => $xmlContent['sellerSection']['sellerSlug']]);
        $featuredVendor['vendor_image'] = array();
        if($featuredVendor['name']){
            $featuredVendor['vendor_image'] = $this->userManager->getUserImage($featuredVendor['name']->getIdMember());
        }
        $featuredVendor['banner'] = $xmlContent['sellerSection']['sellerBanner'];
        $featuredVendor['logo'] = $xmlContent['sellerSection']['sellerLogo'];

        shuffle($xmlContent['sellerSection']['productPanel']);    
        foreach ($xmlContent['sellerSection']['productPanel'] as $key => $product) {
            $productData = $this->em->getRepository('EasyShop\Entities\EsProduct')
                ->findOneBy(['slug' => $product['slug']]);
            if($productData){
                $featuredVendor['product'][$key]['product'] = $this->productManager->getProductDetails($productData);
                $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                    ->getDefaultImage($productData->getIdProduct());
                $featuredVendor['product'][$key]['image']['directory'] = EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
                $featuredVendor['product'][$key]['image']['imageFileName'] = EsProductImage::IMAGE_UNAVAILABLE_FILE;

                if ($productImage != NULL) {
                    $featuredVendor['product'][$key]['image']['directory'] = $productImage->getDirectory();
                    $featuredVendor['product'][$key]['image']['imageFileName'] = $productImage->getFilename();
                }
            }
        }
        $homePageData['seller'] = $featuredVendor;

        //Get Popular Brands
        foreach ($xmlContent['brandSection']['brandId'] as $key => $brandId) {
            $popularCategory['popularBrand'][$key]['brand'] = $this->em->getRepository('EasyShop\Entities\EsBrand')
                                            ->findOneBy(['idBrand' => $brandId]);
            $popularCategory['popularBrand'][$key]['image']['directory'] = EsBrand::IMAGE_DIRECTORY;
            $popularCategory['popularBrand'][$key]['image']['file'] = $popularCategory['popularBrand'][$key]['brand'] ?
                                                                      $popularCategory['popularBrand'][$key]['brand']->getImage() : 
                                                                      EsBrand::IMAGE_UNAVAILABLE_FILE;
        }
        $homePageData['popularCategory'] = $popularCategory;

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

        // banner images
        $bannerImages = [];
        foreach ($pageContent['mainSlide'] as $key => $value) {
            $bannerImages[] = array(
                            'name' => '0',
                            'image' => $value['value'],
                            'target' => $baseUrl.$value['imagemap']['target'],
                            'actionType' => $value['actionType'],
                        );
        }
        $sectionImages = array(
                        'name' => '',
                        'bgcolor' => '',
                        'type' => 'promo',
                        'data' => $bannerImages,
                    ); 

        $productSections[] = $sectionImages; 
        // product sections 
        foreach ($pageContent['section'] as $key => $value) {
            $productArray = [];
            // loop products
        
            foreach ($value['boxContent'] as $keyLevel2 => $valueLevel2) {

                $slug = (isset($valueLevel2['value'])) ? $valueLevel2['value'] : ""; 
                $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                            ->findOneBy(['slug' => $slug]);

                $productName = "";
                $productSlug = "";
                $productBasePrice = 0;
                $productFinalPrice = 0;
                $productDiscount = 0;
                $productImagePath = "";
                $target = "";

                if($product){
                    $product = $this->productManager->getProductDetails($product->getIdProduct());

                    $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                      ->getDefaultImage($product->getIdProduct());
        
                    $directory = EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY;
                    $imageFileName = EsProductImage::IMAGE_UNAVAILABLE_FILE;

                    if($productImage != NULL){
                        $directory = $productImage->getDirectory();
                        $imageFileName = $productImage->getFilename();
                    }

                    $productName = $product->getName();
                    $productSlug = $product->getSlug();
                    $productDiscount = floatval($product->getDiscountPercentage());
                    $productBasePrice = floatval($product->getPrice());
                    $productFinalPrice = floatval($product->getFinalPrice());
                    $productImagePath = $directory.$imageFileName;
                    $target = $baseUrl.'mobile/product/item/'.$productSlug;
                }

                $productArray[] = array(
                                    'name' => $productName,
                                    'slug' => $productSlug,
                                    'discount_percentage' => $productDiscount,
                                    'base_price' => $productBasePrice,
                                    'final_price' => $productFinalPrice,
                                    'image' => $productImagePath,
                                    'actionType' => $valueLevel2['actionType'],
                                    'target' => $target,
                                );
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

                $productArray[] = array(
                                        'name' => "",
                                        'slug' => "",
                                        'discount_percentage' => 0,
                                        'base_price' => 0,
                                        'final_price' => 0,
                                        'image' => "",
                                        'actionType' => 'show product list',
                                        'target' => $baseUrl.'mobile/category/getCategoriesProduct?slug='.$categorySlug,
                                    );
            }

            $productSections[] = array(
                                'name' => $categoryName,
                                'bgcolor' => $value['bgcolor'],
                                'type' => $value['type'],
                                'icon' => $categoryIcon,
                                'data' => $productArray,
                            );
        }

        $display = array( 
                    'section' => $productSections,
                );

        return $display;
    }
}




