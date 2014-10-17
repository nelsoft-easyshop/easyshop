<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 

class EsVendorSubscribeRepository extends EntityRepository
{
    public function getFollowers($userId,$offset = 0,$perPage = 6)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $qbResult = $qb->select('vs')
                            ->from('EasyShop\Entities\EsVendorSubscribe','vs')
                            ->where('vs.vendor = :userId')
                            ->setParameter('userId', $userId)
                            ->getQuery();
        $totalCount = count($qbResult->getResult());
        $qbResult->setFirstResult($offset)
                 ->setMaxResults($perPage);
        $result = $qbResult->getResult();

        return array(
                    'count' =>  $totalCount,
                    'followers' => $result
                );

        $result;
    }

    public function getRecommendToFollow($userId,$memberId,$perPage = 6)
    { 
        $this->em =  $this->_em;
 
        $qb = $this->em->createQueryBuilder();
        $qbResult = $qb->select('m')
                            ->from('EasyShop\Entities\EsMember','m')
                            // ->join('EasyShop\Entities\EsVendorSubscribe','vs', 
                            //                 'WITH','m.idMember = vs.member')
                            ->where('m.idMember != :userId') 
                            ->setParameter('userId', $userId)
                            ->andWhere('m.idMember != :memberId') 
                            ->setParameter('memberId', $memberId)
                            ->getQuery()  
                            ->setMaxResults($perPage);

        $result = $qbResult->getResult();
        return $result;
    }
}