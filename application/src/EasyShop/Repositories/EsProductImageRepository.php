<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

use EasyShop\Entities\EsProductImage;

class EsProductImageRepository extends EntityRepository
{
    
    /**
     * Returns the default image of a product
     *
     * @param integer $productId
     * @return EasyShop\Entities\EsProductImage
     *
     */
    public function getDefaultImage($productId)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder()
                            ->select('pi')
                            ->from('EasyShop\Entities\EsProductImage','pi')
                            ->where('pi.product = :productId')
                            ->andWhere('pi.isPrimary = 1')
                            ->setParameter('productId', $productId)
                            ->getQuery()
                            ->setMaxResults(1);

        $result = $qb->getOneOrNullResult();
        return $result;
    }
    
}
