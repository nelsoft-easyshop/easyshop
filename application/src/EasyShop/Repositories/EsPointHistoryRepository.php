<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class EsPointHistoryRepository extends EntityRepository
{
    /**
     * Count user point activity based on type and date
     * @param  integer     $memberId
     * @param  integer     $pointType
     * @param  date(Y-m-d) $date
     * @return integer
     */
    public function countUserPointActivity($memberId, $pointType = null, $date = null)
    {
        $query =  $this->_em->createQueryBuilder()
                            ->select('COUNT(ph.id) as total_count')
                            ->from('EasyShop\Entities\EsPointHistory', 'ph')
                            ->where('ph.member = :member_id')
                            ->setParameter('member_id', $memberId);

        if($pointType !== null){
            $query->andWhere('ph.type = :point_type')
                  ->setParameter('point_type', $pointType);
        }

        if($date !== null){
            $query->andWhere('date(ph.dateAdded) = :date_added')
                  ->setParameter('date_added', $date);
        }

        $resultCount = $query->getQuery()->getOneOrNullResult();

        return isset($resultCount['total_count']) ? $resultCount['total_count'] : 0;
    }
    
    /**
     * Returns all data inside Point History Table
     *
     * @param integer $userId
     * @param integer $offset
     * @param integer $limit
     * @param boolean $asArray
     * @return EasyShop\Entities\EsPointHistory[]
     */
    public function getUserPointHistory($userId, $offset = 0, $limit = 12)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $query = $queryBuilder->select('ph')
                            ->from('EasyShop\Entities\EsPointHistory','ph') 
                            ->where('ph.member = :memberId')
                            ->setParameter('memberId', $userId)
                            ->setFirstResult( $offset )
                            ->setMaxResults( $limit )
                            ->orderBy('ph.dateAdded', 'DESC')
                            ->getQuery();
        $result = $query->getResult();
              
        return $result;
    }
}

