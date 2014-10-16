<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 

class EsVendorSubscribeRepository extends EntityRepository
{
    public function getFollowers($userId,$offset = 0,$perPage = 1)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $qbResult = $qb->select('vs')
                            ->from('EasyShop\Entities\EsVendorSubscribe','vs')
                            ->where('vs.vendor = :userId')
                            ->setParameter('userId', $userId)
                            ->getQuery()
                            ->setFirstResult($offset)
                            ->setMaxResults($perPage);
        $result = $qbResult->getResult();

        return $result;
    }
}