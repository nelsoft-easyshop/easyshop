<?php

include_once  __DIR__.'/bootstrap.php';
$CI =& get_instance();
$productManager = $CI->kernel->serviceContainer['product_manager'];
$emailService = $CI->kernel->serviceContainer['email_notification'];
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$viewParser = new \CI_Parser();

use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;

class UpdateSearchKeyword extends ScriptBaseClass
{
    private $connection;
    private $productManager;

    /**
     * Constructor
     * @param string                                   $hostName
     * @param string                                   $dbUsername
     * @param string                                   $dbPassword
     * @param EasyShop\Notifications\EmailNotification $emailService
     * @param EasyShop\ConfigLoader\ConfigLoader       $configLoader
     * @param \CI_Parser                               $viewParser
     * @param EasyShop\Product\ProductManager          $productManager
     */
    public function __construct(
        $hostName,
        $dbUsername,
        $dbPassword,
        $emailService,
        $configLoader,
        $viewParser,
        $productManager
    ) {
        parent::__construct($emailService, $configLoader, $viewParser);
        $this->connection = new PDO(
            $hostName,
            $dbUsername,
            $dbPassword
        );
        $this->productManager = $productManager;
    }

    /**
     * Execute script
     */
    public function execute()
    {
        $productds = $this->getAllProducts();

        echo PHP_EOL .'Scanning of data started ('.date('M-d-Y h:i:s A').')'.PHP_EOL;
        echo PHP_EOL;

        foreach ($productds as $product) {
            $this->productManager->generateSearchKeywords($product['id_product']);
            echo $product['id_product'] . ' DONE'.PHP_EOL;
        }

        echo PHP_EOL .'Scanning of data ended ('.date('M-d-Y h:i:s A').')'.PHP_EOL;
        echo PHP_EOL.count($productds).' ROWS AFFECTED!'.PHP_EOL;
    }

    private function getAllProducts()
    {
        $getProductsQuery = "
            SELECT id_product FROM es_product
        ";

        $getProducts = $this->connection->prepare($getProductsQuery);
        $getProducts->execute();
        $products = $getProducts->fetchAll(PDO::FETCH_ASSOC);

        return $products;
    }
}

$updateSearchKeyword  = new UpdateSearchKeyword(
    $CI->db->hostname,
    $CI->db->username,
    $CI->db->password,
    $emailService,
    $configLoader,
    $viewParser,
    $productManager
);

$updateSearchKeyword->execute();
