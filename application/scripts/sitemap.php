<?php

include_once  __DIR__.'/bootstrap.php';

$CI =& get_instance();
$entityManager = $CI->kernel->serviceContainer['entity_manager'];
$categoryManager = $CI->kernel->serviceContainer['category_manager'];
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$emailService = $CI->kernel->serviceContainer['email_notification'];
$viewParser = new \CI_Parser();

use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;
use EasyShop\Entities\EsMember as EsMember;
use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Entities\EsCat as EsCat;

class GenerateSitemap extends ScriptBaseClass
{
    private $baseUrl;
    private $fileLocation;
    private $em;
    private $categoryManager;
    private $schemaUrl;

    /**
     * Constructor
     * @param EasyShop\Entities                        $entityManager
     * @param EasyShop\Category\CategoryManager        $categoryManager
     * @param EasyShop\Notifications\EmailNotification $emailService
     * @param EasyShop\ConfigLoader\ConfigLoader       $configLoader
     * @param \CI_Parser                               $viewParser
     */
    public function __construct(
        $entityManager,
        $categoryManager,
        $emailService,
        $configLoader,
        $viewParser
    ) {
        // parent::__construct($emailService, $configLoader, $viewParser);
        $this->em = $entityManager;
        $this->categoryManager = $categoryManager;
    }

    /**
     * execute script
     */
    public function execute()
    {
        $this->generateSitemapFile();
        $this->generateCategorySitemapFiles();
        $this->generateVendorXmlFile();
        $this->generateSitemapIndex();
    }

    /**
     * Set base url
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Set file location
     * @param string $fileLocation
     */
    public function setFileLocation($fileLocation)
    {
        $this->fileLocation = $fileLocation;

        return $this;
    }

    /**
     * Set schema url
     * @param string $schemaUrl
     */
    public function setSchemaUrl($schemaUrl)
    {
        $this->schemaUrl = $schemaUrl;

        return $this;
    }

    /**
     * Generate sitemap.xml
     */
    private function generateSitemapFile()
    {
        $commonURI = [
            'sell/step1',
            'cart',
            'login',
            'register',
            'faq',
            'policy',
            'terms',
            'contact'
        ];
        $xmlArray = [
            'loc' => $this->baseUrl,
            'priority' => 1,
            'changefreq' => 'never'
        ];
        $xml = new DOMDocument();
        $xmlUrlSet = $xml->createElement("urlset");
        $xmlUrlSet->setAttribute("xmlns", $this->schemaUrl);
        $xmlUrl = $this->writeUrlXml($xml, $xmlArray);
        $xmlUrlSet->appendChild($xmlUrl);
        $xmlArray['priority'] = 0.5;
        foreach ($commonURI as $url) {
            $xmlArray['loc'] = $this->baseUrl.$url;
            $xmlUrl = $this->writeUrlXml($xml, $xmlArray);
            $xmlUrlSet->appendChild($xmlUrl);
        }
        $xml->appendChild($xmlUrlSet);
        $xml->save($this->fileLocation.'sitemap.xml');
    }

    /**
     * Generate sitemap for categories
     */
    private function generateCategorySitemapFiles()
    {
        $xmlArray = [
            'priority' => 0.5,
        ];
        $categories = $this->getParentCategories();
        foreach ($categories as $category) {
            $xml = new DOMDocument();
            $xmlUrlSet = $xml->createElement("urlset");
            $xmlUrlSet->setAttribute("xmlns", $this->schemaUrl);

            $subCategoriesId = $this->em->getRepository('EasyShop\Entities\EsCat')
                                        ->getChildrenWithNestedSet($category->getIdCat());
            $subCategories = $this->em->getRepository('EasyShop\Entities\EsCat')
                                      ->findBy([
                                          'idCat' => $subCategoriesId
                                      ]);
            foreach ($subCategories as $subCategory) {
                $xmlArray['loc'] = $this->baseUrl.'category/'.$subCategory->getSlug();
                $xmlArray['changefreq'] = 'monthly';
                $xmlUrl = $this->writeUrlXml($xml, $xmlArray);
                $xmlUrlSet->appendChild($xmlUrl);
            }

            $productsWithCategory = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                             ->findBy([
                                                 'cat' => $subCategoriesId,
                                                 'isDraft' => EsProduct::ACTIVE,
                                                 'isDelete' => EsProduct::ACTIVE
                                             ]);
            foreach ($productsWithCategory as $product) {
                $xmlArray['loc'] = $this->baseUrl.'item/'.$product->getSlug();
                $xmlArray['changefreq'] = 'weekly';
                $xmlUrl = $this->writeUrlXml($xml, $xmlArray);
                $xmlUrlSet->appendChild($xmlUrl);
            }

            $xml->appendChild($xmlUrlSet);
            $xml->save($this->fileLocation.'sitemap-'.strtolower($category->getSlug()).'.xml');
        }
    }

    /**
     * Generate xml files for vendors
     */
    private function generateVendorXmlFile()
    {
        $xml = new DOMDocument();
        $xmlUrlSet = $xml->createElement("urlset");
        $xmlUrlSet->setAttribute("xmlns", $this->schemaUrl);
        $allMembers = $this->em->getRepository('EasyShop\Entities\EsMember')
                            ->findBy([
                                'isActive' => EsMember::DEFAULT_ACTIVE,
                                'isBanned' => EsMember::NOT_BANNED,
                            ]);

        $xmlArray = [
            'priority' => 0.5,
            'changefreq' => 'monthly'
        ];
        foreach ($allMembers as $member) {
            $xmlArray['loc'] = $this->baseUrl.$member->getSlug();
            $xmlUrl = $this->writeUrlXml($xml, $xmlArray);
            $xmlUrlSet->appendChild($xmlUrl);
        }
        $xml->appendChild($xmlUrlSet);
        $xml->save($this->fileLocation.'sitemap-vendor.xml');
    }

    /**
     * Generate filelist.txt
     */
    private function generateSitemapIndex()
    {
        $filelist = [];
        $xml = new DOMDocument();
        $xmlUrlSet = $xml->createElement("urlset");
        $xmlUrlSet->setAttribute("xmlns", $this->schemaUrl);
        $filelist[] = $this->compressGz($this->fileLocation, 'sitemap.xml');

        $xmlArray = [
            'priority' => 1,
            'changefreq' => 'weekly',
            'loc' => $this->baseUrl.'sitemap.xml.gz'
        ];
        $xmlUrl = $this->writeUrlXml($xml, $xmlArray);
        $xmlUrlSet->appendChild($xmlUrl);
        $filelist[] = $this->compressGz($this->fileLocation, 'sitemap-vendor.xml');
        $xmlArray['loc'] = $this->baseUrl.'sitemap-vendor.xml.gz';
        $xmlUrl = $this->writeUrlXml($xml, $xmlArray);
        $xmlUrlSet->appendChild($xmlUrl);

        $categories = $this->getParentCategories();
        foreach ($categories as $category) {
            $categoryFileName = strtolower($category->getSlug());
            $filelist[] = $this->compressGz($this->fileLocation, 'sitemap-'.$categoryFileName.'.xml');
            $xmlArray['loc'] = $this->baseUrl.'sitemap-'.$categoryFileName.'.xml.gz';
            $xmlUrl = $this->writeUrlXml($xml, $xmlArray);
            $xmlUrlSet->appendChild($xmlUrl);
        }

        $xml->appendChild($xmlUrlSet);
        $xml->save($this->fileLocation.'sitemap_index.xml');
        $filelist[] = 'sitemap_index.xml';

        $content = "";
        foreach ($filelist as $file) {
            $content = $content.$file.PHP_EOL;
        }
        
        $fp = fopen($this->fileLocation. "filelist.txt", "wb");
        fwrite($fp, $content);
        fclose($fp);

        echo 'Sitemap generation complete. The following files have been generated:';
        foreach ($filelist as $file) {
            echo "\n".$file;
        }
        echo "\nfilelist.txt";
    }

    /**
     * Get parent categories
     * @return EasyShop\Entities\EsCat[]
     */
    private function getParentCategories()
    {
        $parentCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                                   ->getParentCategories();
        $categories = $this->categoryManager
                           ->applyProtectedCategory($parentCategory, false);

        return $categories;
    }

    /**
     * Return the XML sitemap format based on http://www.sitemaps.org/protocol.html
     * @param DOMDocument $xml
     * @param mixed DOMDocument
     * @return DOMElement
     */
    private function writeUrlXml($xml, $data)
    {
        $xmlUrl = $xml->createElement("url");
        $xmlLoc = $xml->createElement("loc", htmlentities($data['loc']));
        $xmlPriority = $xml->createElement("priority", $data['priority']);
        $xmlLastmod = $xml->createElement("lastmod", date('Y-m-d'));
        $xmlChangefreq = $xml->createElement("changefreq", $data['changefreq']);
        $xmlUrl->appendChild($xmlLoc);
        $xmlUrl->appendChild($xmlPriority);
        $xmlUrl->appendChild($xmlLastmod);
        $xmlUrl->appendChild($xmlChangefreq);

        return $xmlUrl;
    }
    
    /**
     * Compresses a file using gzip
     * @param string $filelocation
     * @param  string $filename
     * @return string
     */
    private function compressGz($filelocation, $filename)
    {
        $gzfile = $filelocation.$filename.".gz";
        $fp = gzopen($gzfile, 'w9');
        gzwrite($fp, file_get_contents($filelocation.$filename));
        gzclose($fp);
        if (is_file($filelocation.$filename)) {
            unlink($filelocation.$filename);
        }

        return $filename.".gz";
    }
}

$generateSitemap  = new GenerateSitemap(
    $entityManager,
    $categoryManager,
    $emailService,
    $configLoader,
    $viewParser
);

$generateSitemap->setBaseUrl(base_url())
                ->setFileLocation(dirname(__FILE__).'/../../web/')
                ->setSchemaUrl("http://www.sitemaps.org/schemas/sitemap/0.9")
                ->execute();
