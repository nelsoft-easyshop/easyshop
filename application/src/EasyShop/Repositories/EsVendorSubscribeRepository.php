<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 

class EsVendorSubscribeRepository extends EntityRepository
{
    public function getFollowers($userId)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $qbResult = $qb->select('vs')
                                ->from('EasyShop\Entities\EsVendorSubscribe','vs')
                                ->where('vs.vendor = :userId')
                                ->setParameter('userId', $userId)
                                ->getQuery();
        $result = $qbResult->getResult();
}