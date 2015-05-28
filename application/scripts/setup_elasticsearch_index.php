<?php

include_once  __DIR__.'/bootstrap.php';
$CI =& get_instance();
$entityManager = $CI->kernel->serviceContainer['entity_manager'];
$elasticSearchClient = $CI->kernel->serviceContainer['elasticsearch_client'];
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$emailService = $CI->kernel->serviceContainer['email_notification'];
$stringUtility = $CI->kernel->serviceContainer['string_utility'];
$viewParser = new \CI_Parser();

use EasyShop\Entities\EsMember as EsMember;
use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;

class SetupElasticSearch extends ScriptBaseClass
{
    private $em;
    private $elasticSearchClient;
    private $indexName;
    private $stringUtility;

    /**
     * Constructor
     * @param \Elasticsearch                           $elasticSearchClient
     * @param EasyShop\Notifications\EmailNotification $emailService
     * @param EasyShop\ConfigLoader\ConfigLoader       $configLoader
     * @param \CI_Parser                               $viewParser
     * @param EasyShop\Utility\StringUtility           $stringUtility
     */
    public function __construct(
        $entityManager,
        $elasticSearchClient,
        $emailService,
        $configLoader,
        $viewParser,
        $stringUtility
    ) {
        $this->em = $entityManager;
        $this->elasticSearchClient = $elasticSearchClient;
        $this->indexName = 'easyshop';
        $this->stringUtility = $stringUtility;
        parent::__construct($emailService, $configLoader, $viewParser);
    }

    /**
     * Execute function to setup elasticsearch configuration
     */
    public function execute()
    {
        $this->createIndex($this->indexName);
        $this->createProductMapping();
        $this->indexProducts();
        $this->createMemberMapping();
        $this->indexUsers();
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
     * Create es_member mapping
     */
    private function createMemberMapping()
    {
        $typeName = 'es_member';
        $params['index'] = $this->indexName;
        $params['type'] = $typeName;
        $params['body'][$typeName] = [
            'dynamic' => 'strict',
            'properties' => [
                'member_id' => [
                    'type' => 'integer',
                ],
                'store_name' => [
                    'type' => 'string',
                    'null_value' => 'na',
                    'boost' => 50,
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
     * Index all users in database
     */
    private function indexUsers()
    {
        $activeUsers = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->getAllActiveUsersRaw();

        $jsonUsers = [];
        $jsonUsers['index'] = $this->indexName;
        $jsonUsers['type'] = 'es_member';
        foreach ($activeUsers as $user) {
            $jsonUsers['body'][] = [
                'index' => [
                    '_id' => $user['idMember']
                ]
            ];

            $jsonUsers['body'][] = [
                'member_id' => $user['idMember'],
                'store_name' => $this->stringUtility->removeNonUTF(trim($user['storeName'])),
                'date_created' => date('Y-m-d h:m:s', strtotime($user['datecreated'])),
                'date_modified' => date('Y-m-d h:m:s', strtotime($user['lastmodifieddate'])),
            ];
        }

        $this->bulkIndex($jsonUsers);
    }

    /**
     * Bulk index data into elasticsearch index
     * @param  array $jsonData
     */
    public function bulkIndex($jsonData)
    {
        $params['index'] = $this->indexName;
        $params['body']['index']['refresh_interval'] = -1;
        $this->elasticSearchClient->indices()->putSettings($params);

        $this->elasticSearchClient->bulk($jsonData);

        $params['index'] = $this->indexName;
        $params['body']['index']['refresh_interval'] = '1s';
        $this->elasticSearchClient->indices()->putSettings($params);
    }

    /**
     * Index all products in database
     */
    private function indexProducts()
    {
        $activeProducts = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                   ->getAllActiveProductsRaw();
        $jsonProducts = [];
        $jsonProducts['index'] = $this->indexName;
        $jsonProducts['type'] = 'es_product';
        foreach ($activeProducts as $product) {
            $jsonProducts['body'][] = [
                'index' => [
                    '_id' => $product['idProduct']
                ]
            ];

            $jsonProducts['body'][] = [
                'product_id' => $product['idProduct'],
                'name' => utf8_encode(trim($product['name'])),
                'keywords' => $this->stringUtility->removeNonUTF(trim($product['searchKeyword'])),
                'clickcount' => (int) $product['clickcount'],
                'date_created' => date('Y-m-d h:m:s', strtotime($product['createddate'])),
                'date_modified' => date('Y-m-d h:m:s', strtotime($product['lastmodifieddate'])),
            ];
        }

        $this->bulkIndex($jsonProducts);
    }
}

$setupElasticSearch  = new SetupElasticSearch(
    $entityManager,
    $elasticSearchClient,
    $emailService,
    $configLoader,
    $viewParser,
    $stringUtility
);

$setupElasticSearch->execute();
