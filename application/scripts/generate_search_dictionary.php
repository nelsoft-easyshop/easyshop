<?php

include_once  __DIR__.'/bootstrap.php';
$CI =& get_instance();
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$emailService = $CI->kernel->serviceContainer['email_notification'];
$viewParser = new \CI_Parser();

use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;
use EasyShop\Entities\EsMember as EsMember;
use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Entities\EsBrand as EsBrand;

class GenerateSearchDictionary extends ScriptBaseClass
{

    private $connection;
    private $dictionary;

    /**
     * Constructor
     * @param string                                   $hostName
     * @param string                                   $dbUsername
     * @param string                                   $dbPassword
     * @param EasyShop\Notifications\EmailNotification $emailService
     * @param EasyShop\ConfigLoader\ConfigLoader       $configLoader
     * @param \CI_Parser                               $viewParser
     * @param EasyShop\PointTracker\PointTracker       $pointTracker
     */
    public function __construct(
        $hostName,
        $dbUsername,
        $dbPassword,
        $emailService,
        $configLoader,
        $viewParser,
        $dictionary
    ) {
        parent::__construct($emailService, $configLoader, $viewParser);

        $this->connection = new PDO(
            $hostName,
            $dbUsername,
            $dbPassword
        );
        $this->dictionary = $dictionary;
        $this->dictionary->initialize();
    }

    /**
     * Execute script
     */
    public function execute()
    {
        $activeProducts = $this->getActiveProducts();

        foreach ($activeProducts as $product) {
            $words = explode(" ", $product['productName']);
            $runningWord = '';
            foreach ($words as $index => $word) {
                $this->dictionary->insertWordIntoDictionary($word);
                $runningWord .= $word.' ';
                if ($index > 0) {
                    $this->dictionary->insertWordIntoDictionary(trim($runningWord));
                }
            }
        }

        $productBrand = $this->getProductsWithBrand();
        foreach ($productBrand as $brand) {
            $this->dictionary
                 ->insertWordIntoDictionary(
                     $brand['brandName'],
                     $brand['brandOccurences']
                 );
        }

        $this->deleteAllKeywords();
        $this->insertGeneratedKeywords();

        echo "Dictionary updated \n\n";
    }

    /**
     * Get all active products
     * @return array
     */
    private function getActiveProducts()
    {
        $getActiveProductsQuery = "
            SELECT 
                es_product.name as productName
            FROM es_product 
            INNER JOIN es_member 
                ON es_member.id_member = es_product.member_id AND 
                es_member.is_active = :memberActive
            WHERE
                es_product.is_delete = :deleteStatus AND 
                es_product.is_draft = :draftStatus
        ";

        $getActiveProducts = $this->connection->prepare($getActiveProductsQuery);
        $getActiveProducts->bindValue("memberActive", EsMember::DEFAULT_ACTIVE);
        $getActiveProducts->bindValue("deleteStatus", EsProduct::ACTIVE);
        $getActiveProducts->bindValue("draftStatus", EsProduct::ACTIVE);
        $getActiveProducts->execute();
        $activeProducts = $getActiveProducts->fetchAll(PDO::FETCH_ASSOC);

        return $activeProducts;
    }

    /**
     * Get all products with brand
     * @return array
     */
    private function getProductsWithBrand()
    {
        $getProductBrandQuery = "
            SELECT 
                es_brand.name as brandName,
                COUNT(es_brand.name) as brandOccurences
            FROM es_product 
            INNER JOIN es_brand 
                ON es_product.brand_id = es_brand.id_brand
            INNER JOIN es_member
                ON es_member.is_active = :memberActive
                AND es_member.id_member = es_product.member_id
            WHERE
                es_product.is_delete = :deleteStatus AND 
                es_product.is_draft = :draftStatus AND
                es_product.brand_id <> :customBrand
            GROUP BY es_product.brand_id
            HAVING brandOccurences >= :brandOccurenceLimit
        ";

        $brandOccurenceLimit = $this->dictionary->getOccurencesLimit();
        $getProductBrand = $this->connection->prepare($getProductBrandQuery);
        $getProductBrand->bindValue("memberActive", EsMember::DEFAULT_ACTIVE);
        $getProductBrand->bindValue("deleteStatus", EsProduct::ACTIVE);
        $getProductBrand->bindValue("draftStatus", EsProduct::ACTIVE);
        $getProductBrand->bindValue("customBrand", EsBrand::CUSTOM_CATEGORY_ID);
        $getProductBrand->bindValue("brandOccurenceLimit", $brandOccurenceLimit);
        $getProductBrand->execute();
        $productBrand = $getProductBrand->fetchAll(PDO::FETCH_ASSOC);

        return $productBrand;
    }

    /**
     * Delete all data in es_keywords table
     */
    private function deleteAllKeywords()
    {
        $deleteQuery = "DELETE FROM es_keywords WHERE 1";
        $deleteKeywords = $this->connection->prepare($deleteQuery);
        $deleteKeywords->execute();
    }

    /**
     * Insert all generated keywords in es_keywords table
     */
    private function insertGeneratedKeywords()
    {
        $wordList =  $this->dictionary->getDictionary() ;
        $insertKeywordQuery = "INSERT INTO es_keywords (`keywords`, `occurences`) VALUES ";
        foreach ($wordList as $word) {
            $insertKeywordQuery .= "(?, ?),";
        }
        $insertKeywordQuery = rtrim($insertKeywordQuery, ',');
        $insertKeyword = $this->connection->prepare($insertKeywordQuery);
        $count = 1;
        foreach ($wordList as $word => $occurence) {
            $insertKeyword->bindValue($count, $word, PDO::PARAM_STR);
            $insertKeyword->bindValue($count + 1, $occurence, PDO::PARAM_INT);
            $count += 2;
        }
        $insertKeyword->execute();
    }
}

/**
 * Class to generate dictionary array
 *
 */
class Dictionary
{
    /**
     * Minimum length for word to enter the dictionary
     *
     * @var integer
     */
    const WORD_MIN_LENGTH = 2;

    /**
     * Minimum occurrences for word to enter the dictionary (DEV)
     *
     * @var integer
     */
    const DEV_MIN_OCCURENCES = 3;

    /**
     * Minimum occurrences for word to enter the dictionary (PROD)
     *
     * @var integer
     */
    const PROD_MIN_OCCURENCES = 20;

    /**
     * Dictionary array
     *
     * @var string[]
     */
    private $dictionary;

    /**
     * Minimum occurrences for word to enter the dictionary
     * Depends on the environment
     *
     * @var integer
     */
    private $occurencesMinimum;

    /**
     * Minimum occurrences for word to enter the dictionary
     * Depends on the environment
     *
     * @var integer
     */
    public function initialize()
    {
        $this->dictionary = [];
        if (ENVIRONMENT === 'development') {
            $this->occurencesMinimum = self::DEV_MIN_OCCURENCES;
        }
        else {
            $this->occurencesMinimum = self::PROD_MIN_OCCURENCES;
        }
    }
    
    /**
     * Gets the occurrences limit
     *
     * @var integer
     */
    public function getOccurencesLimit()
    {
        return $this->occurencesMinimum;
    }

    /**
     * Inserts a word into the dictionary
     *
     * @param string $word
     * @param integer $sqlOccurences
     */
    public function insertWordIntoDictionary($word, $sqlOccurences = null)
    {
        if (strlen(trim($word)) >= self::WORD_MIN_LENGTH) {
            $word = htmlspecialchars($word);
            $word = strtolower($word);
            $word = preg_replace('/[^A-Za-z0-9\ ]/', '', $word);
            $word = preg_replace('/\s+/', ' ', $word);
            if (isset($this->dictionary[$word])) {
                $occurences = $this->dictionary[$word];
                if ($sqlOccurences === null) {
                    $occurences++;
                }
                else {
                    $occurences = $occurences + $sqlOccurences;
                }
                $this->dictionary[$word] = $occurences;
            }
            else {
                $occurences = $sqlOccurences === null ? 1 : $sqlOccurences;
                $this->dictionary[$word] = $occurences;
            }
    
        }
    }
    
    /**
     * Returns the dictionary array
     *
     * @return string[]
     */
    public function getDictionary()
    {
        foreach ($this->dictionary as $index => $word) {
            if ($word < $this->occurencesMinimum) {
                unset($this->dictionary[$index]);
            }
        }
        return  $this->dictionary;
    }
}

$dictionary = new Dictionary();
$generateSearchDictionary  = new GenerateSearchDictionary(
    $CI->db->hostname,
    $CI->db->username,
    $CI->db->password,
    $emailService,
    $configLoader,
    $viewParser,
    $dictionary
);

$generateSearchDictionary->execute();
