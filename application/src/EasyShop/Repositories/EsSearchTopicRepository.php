<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsSearchTopic as EsSearchTopic; 

class EsSearchTopicRepository extends EntityRepository
{
    /**
     * Select rows bases on query string
     * @param  string $queryString 
     * @return array
     */
    public function getTopicOrderByWord($queryString)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('st')
                    ->from('EasyShop\Entities\EsSearchTopic','st') 
                    ->where('st.topic = :queryString')
                    ->setParameter('queryString', $queryString)
                    ->orderBy('st.weight', 'DESC')
                    ->getQuery();
 
        $result = $query->getResult();

        return $result;
    }
}
