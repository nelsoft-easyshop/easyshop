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

class UpdateElasticSearchIndexes extends ScriptBaseClass
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
        parent::__construct($emailService, $configLoader, $viewParser);
    }

    /**
     * Execute function to setup elasticsearch configuration
     */
    public function execute()
    {
        $this->updateProductIndex();
        $this->removeProductFromIndex();
        $this->updateUserIndex();
        $this->removeUserFromIndex();
    }

    /**
     * Index modified actives users within the day
     */
    private function updateUserIndex()
    {
        $activeUsers = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->getLatestModifiedActiveUsers();

        if ($activeUsers) {
            $jsonUsers = [];
            $jsonUsers['index'] = $this->indexName;
            $jsonUsers['type'] = 'es_member';
            foreach ($activeUsers as $user) {
                $jsonUsers['body'][] = [
                    'index' => [
                        '_id' => $user->getIdMember()
                    ]
                ];

                $jsonUsers['body'][] = [
                    'member_id' => $user->getIdMember(),
                    'store_name' => trim($user->getStoreName()),
                    'date_created' => $user->getDatecreated()->format('Y-m-d h:m:s'),
                    'date_modified' => $user->getLastmodifieddate()->format('Y-m-d h:m:s'),
                ];
            }

            $this->bulkIndex($jsonUsers);
        }
    }

    /**
     * Remove users from index
     */
    private function removeUserFromIndex()
    {
        $nonActiveUsers = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->getLatestModifiedNonActiveUsers();

        if ($nonActiveUsers) {
            $jsonUsers = [];
            $jsonUsers['index'] = $this->indexName;
            $jsonUsers['type'] = 'es_member';
            foreach ($nonActiveUsers as $user) {
                $jsonUsers['body'][] = [
                    'delete' => [
                        '_id' => $user->getIdMember()
                    ]
                ];
            }

            $this->bulkIndex($jsonUsers);
        }
    }

    /**
     * Index modified active products within the day
     */
    private function updateProductIndex()
    {
        $activeProducts = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                   ->getLatestModifiedActiveProducts();

        if ($activeProducts) {
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
                    'name' => trim($product->getName()),
                    'keywords' => trim($product->getSearchKeyword()),
                    'clickcount' => (int) $product->getClickcount(),
                    'date_created' => $product->getCreateddate()->format('Y-m-d h:m:s'),
                    'date_modified' => $product->getLastmodifieddate()->format('Y-m-d h:m:s'),
                ];
            }

            $this->bulkIndex($jsonProducts);
        }
    }

    /**
     * Remove products from index
     */
    private function removeProductFromIndex()
    {
        $deletedProducts = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                   ->getLatestModifiedDeletedProducts();
        if ($deletedProducts) {
            $jsonProducts = [];
            $jsonProducts['index'] = $this->indexName;
            $jsonProducts['type'] = 'es_product';
            foreach ($deletedProducts as $product) {
                $jsonProducts['body'][] = [
                    'delete' => [
                        '_id' => $product->getIdProduct()
                    ]
                ];
            }

            $this->bulkIndex($jsonProducts);
        }
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
}

$updateElasticSearchIndexes  = new UpdateElasticSearchIndexes(
    $entityManager,
    $elasticSearchClient,
    $emailService,
    $configLoader,
    $viewParser
);

$updateElasticSearchIndexes->execute();
