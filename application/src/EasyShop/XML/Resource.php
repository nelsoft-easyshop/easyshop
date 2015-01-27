<?php 

namespace EasyShop\XML;

class Resource
{

    /**
     * The local configuration service
     *
     * @var EasyShop\Core\Configuration
     */
    private $configurationService;

    /**
     * Inject class dependcies
     */
    public function __construct(\EasyShop\Core\Configuration\Configuration $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    /**
     * Returns the content xml file used by the application
     *
     * @return string
     */
    public function getContentXMLfile()
    {   
        $xmlfile = 'page/content_files';
        if($this->configurationService->isConfigFileExists() && strlen(trim($this->configurationService->getConfigValue('XML_content'))) > 0){
            $xmlfile = $this->configurationService->getConfigValue('XML_content');
        }
        
        return $xmlfile;
    }
    

    
    /**
     * Returns a specific content of an xml
     *
     * @param string $file
     * @param string $id
     * @param string $node
     */
    public function getXMlContent($file, $id = null, $node = null) 
    {
        $query = simplexml_load_file(APPPATH . "resources/" . $file . ".xml");   
        $xpath = '/map/';
        $xpathNode = null;
        $xpathId = null;
        
        if($id){
            $xpathId = '[@id="' . $id . '"]';
            $xpathNode = 'select';
        }
        
        if($node){
            $xpathNode = $node;
        }

        if($xpathNode === null && $xpathId === null){
            $xml = simplexml_load_file(APPPATH . "resources/" . $file . ".xml");
            $result = json_decode(json_encode($xml), 1);
            return $result;
        }
        else{
            $xpath = $xpath.$xpathNode .$xpathId;
            $result = $query->xpath(' /map/'.$node.'[@id="' . $id . '"] ');
             return reset($result);
        }

    }

    /**
     * Returns the home xml file used by the application
     *
     * @return string
     */
    public function getMobileXMLfile()
    {
        $xmlfile = 'page/mobile_home_files';
        if($this->configurationService->isConfigFileExists() && strlen(trim($this->configurationService->getConfigValue('XML_mobile_home'))) > 0){
            $xmlfile = $this->configurationService->getConfigValue('XML_mobile_home');
        }
        
        return $xmlfile;
    
    }    


    /**
     * Returns the home xml file used by the application
     *
     * @return string
     */
    public function getHomeXMLfile()
    {
        $xmlfile = 'page/new_home_page';
        if($this->configurationService->isConfigFileExists() && strlen(trim($this->configurationService->getConfigValue('XML_home'))) > 0){
            $xmlfile = $this->configurationService->getConfigValue('XML_home');
        }
        
        return $xmlfile;
    }   

    /**
     * Returns the temp home xml file used by the application
     *
     * @return string
     */
    public function getTempHomeXMLfile()
    {
        $xmlfile =  "local/new_home_page_temp";        
     
        return $xmlfile;
    }   
    
    /**
     * Returns the category xml file
     *
     * @return string
     */
    public function getCategoryXmlFile()
    {
        $xmlfile = 'page/category_files';
        if($this->configurationService->isConfigFileExists() && strlen(trim($this->configurationService->getConfigValue('XML_category'))) > 0){
            $xmlfile = $this->configurationService->getConfigValue('XML_category');
        }
        
        return $xmlfile;
    }

}

