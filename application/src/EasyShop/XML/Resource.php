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
     * Returns the home xml file used by the application
     *
     * @return string
     */
    public function getHomeXMLfile()
    {
        $xmlfile = 'page/home_files';
        if($this->configurationService->isConfigFileExists() && strlen(trim($this->configurationService->getConfigValue('XML_home'))) > 0){
            $xmlfile = $this->configurationService->getConfigValue('XML_home');
        }
        
        return $xmlfile;
    
    }

    /**
     * Returns the home xml file used by the application
     *
     * @return string
     */
    public function getMobileXMLfile()
    {
        $xmlfile = 'page/mobile_home_files';
        if($this->configurationService->isConfigFileExists() && strlen(trim($this->configurationService->getConfigValue('XML_home'))) > 0){
            $xmlfile = $this->configurationService->getConfigValue('XML_home');
        }
        
        return $xmlfile;
    
    }    

}

