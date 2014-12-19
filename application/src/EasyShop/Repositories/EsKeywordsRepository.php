<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsKeywords;

class EsKeywordsRepository extends EntityRepository
{
    /**
     * Retrieves suggested words for search string
     *
     * @param string $keyword
     * @param integer $limit
     * @return string[]
     */
    public function getSimilarKeywords($keyword, $limit = null)
    {
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult( 'id_keywords', 'idKeywords');
        $rsm->addScalarResult( 'keywords', 'keyword');
        $rsm->addScalarResult( 'occurences', 'occurences');
        $keyword .= '*';
        
        $sql = " SELECT 
                    id_keywords, 
                    keywords, 
                    occurences,  
                    MATCH (keywords) AGAINST (:keyword IN BOOLEAN MODE) as relevance
                FROM es_keywords WHERE 
                MATCH (keywords) AGAINST (:keyword IN BOOLEAN MODE)
                ORDER BY relevance DESC ";
                
        if($limit !== null){
            $sql .= " LIMIT :limit";
        }
        
        $query = $this->_em->createNativeQuery($sql, $rsm);
        
        $query->setParameter('keyword', $keyword);
        if($limit !== null){
            $query->setParameter('limit', $limit);
        }
        $results = $query->execute();

        return $results;
   
    }
}