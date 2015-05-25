<?php

namespace EasyShop\Search;

use EasyShop\Entities\EsProduct as EsProduct;

class SearchUser
{
    /**
     * Number of user to display per request
     */
    const PER_PAGE = 30;

    /**
     * Number of product to display per user
     */
    const PRODUCT_PER_USER = 5;

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Sphinx Search Client
     *
     * @var sphinxapi
     */
    private $sphinxClient;

    /**
     * User manager instance
     *
     * @var EasyShop\User\UserManager
     */
    private $userManager;

    /**
     * Config Loader instance
     * @var EasyShop\ConfigLoader\ConfigLoader
     */
    private $configLoader;

    /**
     * Product Manager Instance
     * @var EasyShop\Product\ProductManager
     */
    private $productManager;

    /**
     * Elastic Search Client
     * @var \Elasticsearch
     */
    private $elasticSearchClient;

    /**
     * Constructor. Retrieves Entity Manager instance
     *
     */
    public function __construct(
        $em,
        $sphinxClient,
        $userManager,
        $configLoader,
        $productManager,
        $elasticSearchClient
    ) {
        $this->em = $em;
        $this->sphinxClient = $sphinxClient;
        $this->userManager = $userManager;
        $this->configLoader = $configLoader;
        $this->productManager = $productManager;
        $this->elasticSearchClient = $elasticSearchClient;
    }

    /**
     * Filter search by using search string
     * @param string $queryString
     * @return array
     */
    private function filterBySearchString($queryString)
    {
        $ids = [];
        $isElasticSearchEnabled = $this->configLoader->getItem('search','enable_elasticsearch');
        $sphinxMatchMatches = $this->configLoader->getItem('search', 'sphinx_match_matches');

        if ($isElasticSearchEnabled === false) {
            $this->sphinxClient->SetMatchMode('SPH_MATCH_ANY');
            $this->sphinxClient->SetFieldWeights([
                'store_name' => 100,
            ]);
            $this->sphinxClient->setLimits(0, $sphinxMatchMatches, $sphinxMatchMatches);
            $this->sphinxClient->AddQuery($queryString.'*', 'users users_delta');
            $sphinxResult = $this->sphinxClient->RunQueries();

            if ($sphinxResult === false) {
                // remove all double spaces
                $clearString = str_replace('"', '', preg_replace('!\s+!', ' ', trim($queryString)));
                if ($clearString !== "") {
                    // make string alpha numeric
                    $explodedStringWithRegEx = explode(' ', trim(preg_replace('/[^A-Za-z0-9\ ]/', '', $clearString)));
                    $wildCardString = trim(implode('* +', $explodedStringWithRegEx));
                    if ($wildCardString !== "") {
                        // add characters in need of fulltext
                        $searchString = '+'.$wildCardString .'*';
                        // remove excess '+' character
                        $searchString = rtrim($searchString, "+");
                        $users = $this->em->getRepository('EasyShop\Entities\EsMember')
                                          ->searchUser($searchString, $clearString);
                        foreach ($users as $user) {
                            $ids[] = $user['idMember'];
                        }
                    }
                }
            }
            elseif (isset($sphinxResult[0]['matches'])) {
                foreach ($sphinxResult[0]['matches'] as $memberId => $member) {
                    $ids[] = $memberId;
                }
            }
        }
        else {
            $elasticSearchResult = $this->searchElastic($queryString);
            foreach ($elasticSearchResult as $response) {
                $ids[] = $response['fields']['member_id'][0];
            }
        }

        return $ids;
    }

    /**
     * Search items using elasticsearch
     * @param  string  $queryString
     * @param  integer $limit
     * @return array
     */
    public function searchElastic($queryString, $limit = 10000)
    {
        $searchParams['index'] = 'easyshop';
        $searchParams['type']  = 'es_member';
        $searchParams['size'] = $limit;
        $searchParams['fields'] = ['member_id', 'store_name'];
        $searchParams['body'] = [
            'query' => [
                'bool' => [
                    'minimum_number_should_match' => 1,
                    'should' => [
                        [
                            'match' => [
                                'store_name' => [
                                    'query' => $queryString,
                                    'operator' => 'and',
                                    'boost' => 10.0,
                                ]
                            ]
                        ],
                        [
                            'multi_match' => [
                                'query' => $queryString,
                                'type' => 'phrase',
                                'fields' => ['store_name^15']
                            ]
                        ],
                        [
                            'wildcard' => [
                                'store_name' => $queryString.'*',
                            ]
                        ],
                        [
                            'multi_match' => [
                                'query' => $queryString,
                                'type' => 'best_fields',
                                'operator' => 'and',
                                'fields' => ['store_name^5'],
                                'tie_breaker' => 0.3,
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $queryResponse = $this->elasticSearchClient->search($searchParams);
        if ((int)$queryResponse['hits']['total'] <= 0) {
            $searchParams['body']['query']['bool']['should'][] = [
                'fuzzy' => [
                    'store_name' => [
                        'value' => $queryString,
                        'prefix_length' => 2,
                        'max_expansions' => 100
                    ]
                ]
            ];
            $searchParams['body']['query']['bool']['should'][] = [
                'match' => [
                    'store_name' => [
                        'query' => $queryString,
                    ]
                ]
            ];
            $queryResponse = $this->elasticSearchClient->search($searchParams);
        }

        return $queryResponse['hits']['hits'];
    }

    /**
     * Return all users processed by all filters
     * @param  array   $parameters
     * @param  boolean $isHydrate
     * @return array
     */
    public function searchUser($parameters, $isHydrate = true)
    {
        $queryString = $parameters['q_str'];
        $pageNumber = isset($parameters['page']) && $parameters['page']?trim($parameters['page']):false;
        $perPage = isset($parameters['limit']) ? $parameters['limit'] : self::PER_PAGE;
        $offset = bcmul($pageNumber, $perPage);
        $memberIds = $this->filterBySearchString($queryString);

        if ($isHydrate) {
            $paginatedMemberIds = array_slice($memberIds, $offset, $perPage);
            $members = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->findBy(['idMember' => $paginatedMemberIds]);
            foreach ($members as $keyMember => $member) {
                $members[$keyMember]->userImage = $this->userManager->getUserImage($member->getIdMember(), 'small');
                $members[$keyMember]->userProducts = [];
                $userProducts = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                         ->getUserProducts(
                                             $member->getIdMember(),
                                             EsProduct::ACTIVE,
                                             EsProduct::ACTIVE,
                                             0,
                                             self::PRODUCT_PER_USER,
                                             "",
                                             "p.clickcount"
                                         );
                foreach ($userProducts as $product) {
                    $members[$keyMember]->userProducts[] = $this->productManager->getProductDetails($product);
                }
            }
        }
        else {
            $members = [];
        }

        return [
            'collection' => $members,
            'count' => count($memberIds),
        ];
    }
}
