<?php

namespace EasyShop\Search;

class SearchUser
{
    /**
     * Number of user to display per request
     */
    const PER_PAGE = 30;

    /**
     * Number of match matches for sphinx conf
     */
    const SPHINX_MATCH_MATCHES = 100000;


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
     * Constructor. Retrieves Entity Manager instance
     *
     */
    public function __construct(
        $em,
        $sphinxClient,
        $userManager
    ) {
        $this->em = $em;
        $this->sphinxClient = $sphinxClient;
        $this->userManager = $userManager;
    }

    /**
     * Filter search by using search string
     * @param string $queryString
     * @return array
     */
    private function filterBySearchString($queryString)
    {
        $ids = [];
        $this->sphinxClient->SetMatchMode('SPH_MATCH_ANY');
        $this->sphinxClient->SetFieldWeights([
            'store_name' => 100,
        ]);
        $this->sphinxClient->setLimits(0, self::SPHINX_MATCH_MATCHES, self::SPHINX_MATCH_MATCHES);
        $this->sphinxClient->AddQuery($queryString.'*', 'users');
        $sphinxResult = $this->sphinxClient->RunQueries();

        if ($sphinxResult === false) {
            // TO DO Fallback search using mysql
        }
        else if(isset($sphinxResult[0]['matches'])) {
            foreach ($sphinxResult[0]['matches'] as $memberId => $member) {
                $ids[] = $memberId;
            }
        }

        return $ids;
    }

    /**
     * Return all users processed by all filters
     * @param  array $parameters
     * @return array
     */
    public function searchUser($parameters)
    {
        $queryString = $parameters['q_str'];
        $pageNumber = isset($parameters['page']) && $parameters['page']?trim($parameters['page']):false;
        $perPage = isset($parameters['limit']) ? $parameters['limit'] : self::PER_PAGE;
        $offset = (int) bcmul($pageNumber, $perPage);
        $memberIds = $this->filterBySearchString($queryString);
        $paginatedMemberIds = array_slice($memberIds, $offset, $perPage);
        $members = $this->em->getRepository('EasyShop\Entities\EsMember')
                            ->findBy(['idMember' => $paginatedMemberIds]);
        foreach ($members as $keyMember => $member) {
            $members[$keyMember]->userImage = $this->userManager->getUserImage($member->getIdMember(), 'small');
            // TO DO get product
        }

        return [
            'collection' => $members,
            'count' => count($memberIds),
        ];
    }
}
