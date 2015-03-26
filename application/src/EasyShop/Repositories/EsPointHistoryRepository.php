<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class EsPointHistoryRepository extends EntityRepository
{
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
}