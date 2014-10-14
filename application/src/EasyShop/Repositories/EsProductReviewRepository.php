<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 

class EsProductReviewRepository extends EntityRepository
{
    /**
     * Get all review of the product
     * @param  integer $productId
     * @return mixed
     */
    public function getProductReview($productId)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('r')
                    ->from('EasyShop\Entities\EsProductReview','r') 
                    ->where('r.isShow = 1')
                    ->andWhere('r.pReviewid = 0')
                    ->andWhere('r.product = :productId')
                    ->setParameter('productId', $productId)
                    ->getQuery()
                    ->setMaxResults(5);
 
        $result = $query->getResult();

        return $result;
    }

    /**
     * get all replies to the specific review
     * @param  integer $productId 
     * @param  array $reviewIds
     * @return mixed
     */
    public function getReviewReplies($productId,$reviewIds)
    { 
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $qbResult = $qb->select('r')
                                ->from('EasyShop\Entities\EsProductReview','r') 
                                ->where('r.isShow = 1') 
                                ->andWhere('r.product = :productId')
                                ->andWhere(
                                        $qb->expr()->in('r.pReviewid', $reviewIds)
                                    )
                                ->setParameter('productId', $productId)
                                ->orderBy('r.idReview', 'DESC')
                                ->getQuery();
        $result = $qbResult->getResult();

        return $result;
    }
}