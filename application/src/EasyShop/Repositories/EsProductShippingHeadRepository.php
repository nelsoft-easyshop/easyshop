<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 
use EasyShop\Entities\EsProductShippingHead; 

class EsProductShippingHeadRepository extends EntityRepository
{
    /**
     * Check if product is available for free shipping
     * @return [type] [description]
     */
    public function getShippingTotalPrice($productId)
    { 
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder()
                            ->select('SUM(sh.price) as shipping_total') 
                            ->from('EasyShop\Entities\EsProductShippingHead','sh')
                            ->where('sh.product = :productId')
                            ->setParameter('productId', $productId)
                            ->getQuery();

        $result = $qb->getResult();

        return $result;
    }
}