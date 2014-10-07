<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 
use EasyShop\Entities\EsProductShippingHead; 

class EsProductShippingHeadRepository extends EntityRepository
{
    /**
     * Returns the total shipping fee of a product
     *
     * @param integer $productId
     * @return integer
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

        $result = $qb->getOneOrNullResult();

        return intval($result['shipping_total']);
    }
    
    /**
     * Method used for reverting rubbish data from admin product csv uploads
     * @param int $id
     */
    public function deleteShippingHeadByProductId($id)
    {
        $query = $this->_em->createQuery("DELETE FROM EasyShop\Entities\EsProductShippingHead e 
            WHERE e.product = ?6");
        $query->setParameter(6, $id);
        $query->execute();       
    }

}

