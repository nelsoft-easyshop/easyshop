<?php

include_once  __DIR__.'/bootstrap.php';
$CI =& get_instance();
$entityManager = $CI->kernel->serviceContainer['entity_manager'];
$elasticSearchClient = $CI->kernel->serviceContainer['elasticsearch_client'];
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$emailService = $CI->kernel->serviceContainer['email_notification'];
$viewParser = new \CI_Parser();

use EasyShop\Entities\EsMember as EsMember;
use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;

class SetupElasticSearch
{
    private $em;
    private $elasticSearchClient;
    private $indexName;

    /**
     * Constructor
     * @param \Elasticsearch                           $elasticSearchClient
     * @param EasyShop\Notifications\EmailNotification $emailService
     * @param EasyShop\ConfigLoader\ConfigLoader       $configLoader
     * @param \CI_Parser                               $viewParser
     */
    public function __construct(
        $entityManager,
        $elasticSearchClient,
        $emailService,
        $configLoader,
        $viewParser
    ) {
        $this->em = $entityManager;
        $this->elasticSearchClient = $elasticSearchClient;
        $this->indexName = 'easyshop';
        // parent::__construct($emailService, $configLoader, $viewParser);
    }

    /**
     * Execute function to setup elasticsearch configuration
     */
    public function execute()
    {
        try {
            $this->createIndex($this->indexName);
            $this->createProductMapping();
            $this->indexProducts();
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Create easyshop index
     * @param  string $indexName
     */
    private function createIndex($indexName)
    {
        $indexParams['index'] = $indexName;
        if ($this->elasticSearchClient->indices()->exists($indexParams) === false) {
            $this->elasticSearchClient->indices()->create($indexParams);
        }
    }

    /**
     * Create es_product mapping
     */
    private function createProductMapping()
    {
        $typeName = 'es_product';
        $params['index'] = $this->indexName;
        $params['type'] = $typeName;
        $params['body'][$typeName] = [
            'dynamic' => 'strict',
            'properties' => [
                'product_id' => [
                    'type' => 'integer',
                ],
                'name' => [
                    'type' => 'string',
                    'null_value' => 'na',
                    'boost' => 100,
                ],
                'keywords' => [
                    'type' => 'string',
                    'null_value' => 'na',
                    'boost' => 25,
                ],
                'clickcount' => [
                    'type' => 'integer',
                ],
                'date_created' => [
                    'type' => 'date',
                    'format' => 'yyyy-MM-dd HH:mm:ss'
                ],
                'date_modified' => [
                    'type' => 'date',
                    'format' => 'yyyy-MM-dd HH:mm:ss'
                ],
            ]
        ];

        $this->elasticSearchClient->indices()->putMapping($params);
    }

    /**
     * Index all products in database
     */
    private function indexProducts()
    {
        $activeProducts = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                   ->findBy([
                                        'isDelete' => EsProduct::ACTIVE,
                                        'isDraft' => EsProduct::ACTIVE
                                   ]);
        $jsonProducts = [];
        $jsonProducts['index'] = $this->indexName;
        $jsonProducts['type'] = 'es_product';
        foreach ($activeProducts as $product) {
            $jsonProducts['body'][] = [
                'index' => [
                    '_id' => $product->getIdProduct()
                ]
            ];

            $jsonProducts['body'][] = [
                'product_id' => $product->getIdProduct(),
                'name' => trim($product->getName()),
                'keywords' => trim($product->getSearchKeyword()),
                'clickcount' => (int) $product->getClickcount(),
                'date_created' => $product->getCreateddate()->format('Y-m-d h:m:s'),
                'date_modified' => $product->getLastmodifieddate()->format('Y-m-d h:m:s'),
            ];
        }

        $params['index'] = $this->indexName;
        $params['body']['index']['refresh_interval'] = -1;
        $this->elasticSearchClient->indices()->putSettings($params);

        $this->elasticSearchClient->bulk($jsonProducts);

        $params['index'] = $this->indexName;
        $params['body']['index']['refresh_interval'] = '1s';
        $this->elasticSearchClient->indices()->putSettings($params);
    }
}

$setupElasticSearch  = new SetupElasticSearch(
    $entityManager,
    $elasticSearchClient,
    $emailService,
    $configLoader,
    $viewParser
);

$setupElasticSearch->execute();
