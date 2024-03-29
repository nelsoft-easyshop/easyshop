<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsProductHistoryView; 
use Doctrine\ORM\Query\ResultSetMapping;

class EsProductHistoryViewRepository extends EntityRepository
{
    public function getCountProductViewsByMemberToday($productId, $memberId)
    {
        $this->em = $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('count', 'count');
        $sql = "
            SELECT 
                COUNT(id_product_history_view) AS count
            FROM
                es_product_history_view
            WHERE
                DATE(date_viewed) = DATE(:date_today)
                    AND member_id = :member_id
                    AND product_id = :product_id
        ";

        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameter('product_id', $productId); 
        $query->setParameter('member_id', $memberId); 
        $query->setParameter('date_today', date("Y-m-d H:i:s")); 
        $result = $query->getOneOrNullResult();

        return (int) $result['count'];
    }
}

