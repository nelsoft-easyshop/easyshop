<?php

namespace EasyShop\Search;

class SearchUser
{
    /**
     * Number of user to display per request
     */
    const PER_PAGE = 30;

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
     * Constructor. Retrieves Entity Manager instance
     *
     */
    public function __construct(
        $em,
        $sphinxClient,
        $userManager,
        $configLoader
    ) {
        $this->em = $em;
        $this->sphinxClient = $sphinxClient;
        $this->userManager = $userManager;
        $this->configLoader = $configLoader;
    }

    /**
     * Filter search by using search string
     * @param string $queryString
     * @return array
     */
    private function filterBySearchString($queryString)
    {
        $ids = [];
        $sphinxMatchMatches = $this->configLoader->getItem('search','sphinx_match_matches');
        $this->sphinxClient->SetMatchMode('SPH_MATCH_ANY');
        $this->sphinxClient->SetFieldWeights([
            'store_name' => 100,
        ]);
        $this->sphinxClient->setLimits(0, $sphinxMatchMatches, $sphinxMatchMatches);
        $this->sphinxClient->AddQuery($queryString.'*', 'users');
        $sphinxResult = $this->sphinxClient->RunQueries();

        if ($sphinxResult === false) {
            $clearString = str_replace('"', '', preg_replace('!\s+!', ' ', $queryString));
            if (trim($clearString) !== "") {
                $explodedStringWithRegEx = explode(' ', trim(preg_replace('/[^A-Za-z0-9\ ]/', '', $clearString))); 
                $wildCardString = !implode('* +', $explodedStringWithRegEx)
                                  ? "" 
                                  : '+'.implode('* +', $explodedStringWithRegEx) .'*';
                $searchString = str_replace("+*", "", $wildCardString);
                $users = $this->em->getRepository('EasyShop\Entities\EsMember')
                                         ->searchUser($searchString); 
                foreach ($users as $user) {
                    $ids[] = $user['idMember']; 
                }
            }
        }
        elseif (isset($sphinxResult[0]['matches'])) { 
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
        $offset = bcmul($pageNumber, $perPage);
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
